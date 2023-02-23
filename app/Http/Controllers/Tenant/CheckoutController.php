<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index($tenant)
    {

        $tenantId = config('tenant.id');

        $cartItems = \Cart::getContent();
        
        $isOpened = Timeline::isOpened($tenantId);

        return view('tenant.pages.checkout', [
            'cartItems' => $cartItems,
            'tenant' => $tenant,
            'freightDetails' => Session::get('freight_details'),
            'isOpened' => $isOpened
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
