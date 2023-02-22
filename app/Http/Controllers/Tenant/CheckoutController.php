<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index($tenant)
    {
        $cartItems = \Cart::getContent();

        return view('tenant.pages.checkout', [
            'cartItems' => $cartItems,
            'tenant' => $tenant,
            'freightDetails' => Session::get('freight_details')
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
