<?php

namespace App\Http\Controllers;

use App\Models\AdditionOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function store($tenant, Request $request)
    {
        $validations = [
            'name' => 'required',
            'phone' => 'required',
            'payment_method' => 'required',
            'delivery_method' => 'required'
        ];

        if( $request->delivery_method == 'shipping' ) {
            $validations['zip_code'] = 'required';
            $validations['street'] = 'required';
            $validations['number'] = 'required';
            $validations['neighborhood'] = 'required';
            $validations['city'] = 'required';
            $validations['state'] = 'required';
        }

        $request->validate($validations);

        $freight_total = $request->delivery_method == 'local' ? 0 : Session::get('freight_details')['price'];

        $data = [
            'tenant_id' => config('tenant.id'),
            'name' => $request->name,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'delivery_method' => $request->delivery_method,
            'additional_information' => $request->additional_information,
            'freight_total' => $freight_total,
            'total' => \Cart::getTotal() + $freight_total
            //'status'
        ];

        if( $request->delivery_method == 'shipping' ) {
            $data['zip_code'] = $request->zip_code;
            $data['street'] = $request->street;
            $data['number'] = $request->number;
            $data['complement'] = $request->complement;
            $data['neighborhood'] = $request->neighborhood;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
        }

        $cartItems = \Cart::getContent();

        $result = DB::transaction(function () use ($data, $cartItems, $tenant) {

            $newOrder = Order::create($data);

            foreach($cartItems as $item) {
                $newOrderItem = OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item->attributes->product_id,
                    'quantity' => $item->quantity,
                    'total' => $item->price * $item->quantity,
                ]);
                if( $item->attributes->additions ) {
                    foreach($item->attributes->additions as $addition) {
                        AdditionOrderItem::create([
                            'order_item_id' => $newOrderItem->id,
                            'addition_id' => $addition->id,
                            'total' => $addition->price,
                        ]);
                    }
                }
            }

            return $newOrder;
        
        });

        \Cart::clear();

        return redirect()->route('order.show', [
            'tenant' => $tenant,
            'id' => $result->id
        ]);
    }

    public function show($tenant, $id)
    {
        dd($tenant, $id);
    }
}
