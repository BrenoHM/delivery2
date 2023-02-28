<?php

namespace App\Http\Controllers;

use App\Models\AdditionOrderItem;
use App\Models\LogStatusOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariationOption;
use App\Models\StatusOrder;
use App\Models\VariationOrderItem;
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
                if( $item->attributes->variation_id ) {
                    $total = ProductVariationOption::find($item->attributes->variation_id)->price;
                    VariationOrderItem::create([
                        'order_item_id' => $newOrderItem->id,
                        'product_variation_option_id' => $item->attributes->variation_id,
                        'total' => $total
                    ]);
                }
            }

            //create new log status order
            LogStatusOrder::create([
                'order_id' => $newOrder->id,
                'status_order_id' => 1
            ]);

            return $newOrder;
        
        });

        \Cart::clear();

        return redirect()->route('order.information', [
            'tenant' => $tenant,
            'order' => $result->id
        ]);
    }

    public function orderInformation($tenant, Order $order)
    {

        $statusLabel = [
            'label' => 
                [
                    1 => 'Pedido Realizado',
                    2 => 'Em preparação',
                    3 => 'Saiu para entrega',
                    4 => 'Entregue'
                ],
            'icon' =>
                [
                    1 => 'fa-user',
                    2 => 'fa-bread-slice',
                    3 => 'fa-person-running',
                    4 => 'fa-truck'
                ]
        ];

        $typePix = [
            'cpf' => 'CPF',
            'phone' => 'Telefone',
            'email' => 'Email',
            'random' => 'Aleatória'
        ];

        $status = StatusOrder::find([1,2,3,4])->toArray();

        foreach($status as $key => $value) {
            $exist = LogStatusOrder::where('order_id', $order->id)->where('status_order_id', $value['id'])->orderBy('id', 'desc')->first();
            $status[$key]['label'] = $statusLabel['label'][$value['id']];
            $status[$key]['active'] = $exist ? true : false;
            $status[$key]['quando'] = $exist ? $exist->created_at->format('d/m/y H:i:s') : "";
            $status[$key]['icon'] = $statusLabel['icon'][$value['id']];
        }

        return view('tenant.pages.order-information', [
            'order' => $order,
            'status' => $status,
            'tenant' => Session::get('tenant'),
            'typePix' => $typePix
        ]);
    }
}
