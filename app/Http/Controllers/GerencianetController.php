<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Exception;
use Illuminate\Http\Request;
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;

class GerencianetController extends Controller
{
    //
    public function testGerencianetSubscriber()
    {
        $plan = Plan::with('items')->find(1);
        //return $plan;
        
        $options = config('gerencianet');

        //dd($plan->plan_id);

        $params = ['id' => $plan->plan_id];

        $items = [
            [
                'name' => $plan->items[0]->name,
                'amount' => $plan->items[0]->amount,
                'value' => $plan->items[0]->value
            ]
        ];

        $paymentToken = '1a04df1d21aa726543112aafa4dc63c3af4ef999';

        $customer = [
            'name' => 'Breno Mol',
            'cpf' => '01603117644',
            'phone_number' => '31988975279',
            'email' => 'emaildobrenomol@gmail.com',
            'birth' => '1977-01-15'
        ];

        $billingAddress = [
            'street' => 'Av. JK',
            'number' => 909,
            'neighborhood' => 'Bauxita',
            'zipcode' => '35400000',
            'city' => 'Ouro Preto',
            'state' => 'MG',
        ];

        $body = [
            'items' => $items,
            'payment' => [
                'credit_card' => [
                    'billing_address' => $billingAddress,
                    'payment_token' => $paymentToken,
                    'customer' => $customer
                ]
            ]
        ];


        try {
            $api = new Gerencianet($options);
            $response = $api->createOneStepSubscription($params, $body);

            print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function createOneStepBillet()
    {
        $options = config('gerencianet');

        $plan = Plan::with('items')->find(1);

        $params = [
            "id" => $plan->plan_id // plan_id
        ];
        
        $items = [
            [
                'name' => $plan->items[0]->name,
                'amount' => $plan->items[0]->amount,
                'value' => $plan->items[0]->value
            ]
        ];
        
        $metadata = [
            "notification_url" => "https://your-domain.com.br/notification/"
        ];
        
        $customer = [
            "name" => "Gorbadoc Oldbuck",
            "cpf" => "94271564656"
        ];
        
        
        $body = [
            "items" => $items,
            "payment" => [
                "banking_billet" => [
                    "expire_at" => "2024-12-10",
                    "message" => "This is a space\n of up to 80 characters\n to tell\n your client something",
                    "customer" => $customer
                ]
            ]
        ];
        
        
        try {
            $api = new Gerencianet($options);
            $response = $api->createOneStepSubscription($params, $body);
        
            print_r("<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</pre>");
        } catch (GerencianetException $e) {
            print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
}
