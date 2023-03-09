<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = \Cart::getContent();

        return view('site.pages.checkout', [
            'cartItems' => $cartItems
        ]);
    }

    public function addCart(Request $request)
    {
        
        \Cart::add([
            'id' => $request->id,
            'name' => $request->plan_name,
            'price' => $request->plan_item_price,
            'quantity' => 1,
            'product_id' => $request->id,
            'attributes' => array(
                'plan_id' => $request->plan_id,
                'plan_item_name' => $request->plan_item_name,
            ),
        ]);

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
            'state' => 'required'
        ];

        $messages = [
            'domain.unique' => 'Esta url de loja ja existe, escolha outra.'
        ];

        $request->validate($rules, $messages);
        
        dd($request->all());
    }
}
