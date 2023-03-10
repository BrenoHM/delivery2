<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = \Cart::getContent();

        return view('site.pages.checkout', [
            'cartItems' => $cartItems,
            'trial' => collect($cartItems)->whereNotNull('attributes.trial_days')->first()
        ]);
    }

    public function addCart(Request $request)
    {

        //dd($request->all());

        $data = [
            'id' => $request->id,
            'name' => $request->plan_name,
            'price' => $request->plan_item_price,
            'quantity' => 1,
            'product_id' => $request->id,
            'attributes' => array(
                'plan_id' => $request->plan_id,
                'plan_item_name' => $request->plan_item_name,
            ),
        ];

        if( $request->trial_days ) {
            $data['attributes']['trial_days'] = $request->trial_days;
        }
        
        \Cart::add($data);

        return redirect()->route('site.checkout.index');
    }

    public function removeCart(Request $request)
    {
        
        \Cart::remove($request->id);

        return redirect()->route('site.index');
    }

    public function process(Request $request)
    {

        if( $request->tenant_name ) {
            $request->merge(['domain' => Str::slug($request->tenant_name, '-')]);
        }

        $rules = [
            'name' => 'required',
            'cpf' => 'required',
            'phone_number' => 'required',
            'birth' => 'required',
            'tenant_name' => 'required',
            'domain' => 'required|unique:tenants,domain,NULL,id,deleted_at,NULL',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'zip_code' => 'required',
            'street' => 'required',
            'number' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required',
            'paymentMethod' => 'required'
        ];

        if( $request->paymentMethod == 'credit' ) {
            $rules['cc-form'] = 'required';
            $rules['cc-number'] = 'required';
            $rules['cc-expiration'] = 'required';
            $rules['cc-cvv'] = 'required';
            $rules['payment_token'] = 'required';
        }

        $messages = [
            'domain.unique' => 'Esta url de loja ja existe, escolha outra.'
        ];

        $request->validate($rules, $messages);

        //dd($request->all());

        $cartItems = \Cart::getContent();
        $cart = collect($cartItems)->first();

        $options = config('gerencianet');

        $params = ['id' => $cart->attributes->plan_id];

        $items = [
            [
                'name' => $cart->attributes->plan_item_name,
                'amount' => $cart->quantity,
                'value' => $cart->price
            ]
        ];

        $metadata = [
            'custom_id' => "1010", //after id tenant created
            'notification_url' => env('NOTIFICATION_URL')
        ];

        $customer = [
            'name' => $request->name,
            'cpf' => str_replace(['.', '-'], "", $request->cpf),
            'phone_number' => str_replace(['(', ')', ' ', '-'], "", $request->phone_number),
            'email' => $request->email,
            'birth' => date('Y-m-d', strtotime($request->birth))
        ];

        $billingAddress = [
            'street' => $request->street,
            'number' => $request->number,
            'neighborhood' => $request->neighborhood,
            'zipcode' => str_replace("-", "", $request->zip_code),
            'city' => $request->city,
            'state' => $request->state,
        ];

        if( $request->paymentMethod == 'credit' ) {
            $paymentToken = $request->payment_token;
            $payment = [
                'credit_card' => [
                    'billing_address' => $billingAddress,
                    'payment_token' => $paymentToken,
                    'customer' => $customer
                ]
            ];
        }else{
            //billet
            $payment = [
                "banking_billet" => [
                    "expire_at" => "2024-12-10",
                    "message" => "This is a space\n of up to 80 characters\n to tell\n your client something",
                    "customer" => $customer
                ]
            ];
        }

        $body = [
            'items' => $items,
            'payment' => $payment,
            'metadata' => $metadata
        ];

        if($request->paymentMethod == 'credit' && $request->trial_days) {
            $body['payment']['credit_card']['trial_days'] = (int)$request->trial_days;
        }

        try {
            $api = new Gerencianet($options);
            $response = $api->createOneStepSubscription($params, $body);

            if( $response['code'] == 200 ) {
                \Cart::clear();

                $message = 'Sua assinatura foi realizada com sucesso! Após aprovação do pagamento você receberá emails com as instruções de acesso!';

                if( $request->trial_days ){
                    $message = 'Sua assinatura foi realizada com sucesso! Em bre você receberá emails com as instruções de acesso para teste!';
                }

                return redirect()->route('site.finished')->with([
                    'message' => $message,
                    'response' => $response
                ]);
            }

            //print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {

            return redirect()->back()->withInput()->with(['error' => 'Houve um erro ao processar seu pagamento, favor entrar em contato com o administrador do sistema!']);
            //depois salvar depois no log
            // print_r($e->code);
            // print_r($e->error);
            // print_r($e->errorDescription);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['error' => 'Erro no servidor, favor entrar em contato com o administrador do sistema!']);
            //depois salvar depois no log
            //print_r($e->getMessage());
        }
        
    }

    public function convertSlug(Request $request)
    {
        return response()->json([
            'success' => true,
            'url' => Str::slug($request->term, '-') . '.' . env('APP_URL')
        ]);
    }
}
