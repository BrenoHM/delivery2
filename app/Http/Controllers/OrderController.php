<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validations = [
            'name' => 'required',
            'phone' => 'required',
            'payment_method' => 'required',
            'delivery_method' => 'required'
        ];

        if( $request->delivery_method == 'shipping' ) {
            $validations['zip_code'] = 'required';
            $validations['number'] = 'required';
        }

        $request->validate($validations);

        dd($request->all());
    }
}
