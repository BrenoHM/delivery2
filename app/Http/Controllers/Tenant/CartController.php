<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Addition;
use App\Models\ProductVariationOption;
use App\Models\Timeline;
use Faker\Core\Uuid;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cartList(Request $request)
    {
        $cartItems = \Cart::getContent();

        $isOpened = Timeline::isOpened(config('tenant.id'));
        
        return view('tenant.pages.cart', [
            'cartItems' => $cartItems,
            'isOpened' => $isOpened
        ]);
    }


    public function addToCart($tenant, Request $request)
    {
        
        $additions = "";
        $price = $request->price;

        // se for produto com variação
        if( $request->variation_id ) {
            $productVariationOption = ProductVariationOption::with('option')->find($request->variation_id);
            $price = $productVariationOption->price;
        }

        if( isset($request->additions) && count($request->additions) > 0 ) {
            $additions = Addition::find($request->additions);
            if( $additions ) {
                foreach($additions as $addition) {
                    $price = $price + $addition->price;
                }
            }
        }

        \Cart::add([
            'id' => md5(now()),
            'name' => $request->name,
            'price' => $price,
            'quantity' => $request->quantity,
            'product_id' => $request->id,
            'attributes' => array(
                'product_id' => $request->id,
                'additions' => $additions,
                'variation_id' => $request->variation_id ?? null,
                'variation_description' => $productVariationOption->option->option ?? null,
                'photo' => $request->photo,
            ),
        ]);    

        // $cartItems = \Cart::get(50);

        //session()->flash('success', 'Product is Added to Cart Successfully !');

        return redirect()->route('cart.list', $tenant);
    }

    public function updateCart(Request $request)
    {
        //return response()->json($request->all());

        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        $item = \Cart::get($request->id);

        $subtotal = $item->price * $request->quantity;

        //dd($item->price);

        // session()->flash('success', 'Item Cart is Updated Successfully !');

        // return redirect()->route('cart.list');

        return response()->json([
            'success' => true,
            'subTotal' => $subtotal,
            'totalQuantityCart' => \Cart::getTotalQuantity(),
            'totalCart' => \Cart::getTotal() 
        ]);
    }

    public function removeCart(Request $request)
    {
        
        \Cart::remove($request->id);
        // session()->flash('success', 'Item Cart Remove Successfully !');

        // return redirect()->route('cart.list');

        return response()->json([
            'success' => true,
            'totalQuantityCart' => \Cart::getTotalQuantity(),
            'totalCart' => \Cart::getTotal() 
        ]);
    }

    public function clearAllCart()
    {
        // \Cart::clear();

        // session()->flash('success', 'All Item Cart Clear Successfully !');

        // return redirect()->route('cart.list');
    }

    public function getTotalCart(Request $request)
    {

        //if ($request->session()->has('freight_details')) {
            $request->session()->put('freight_details.delivery_method', $request->delivery_method);
        //}

        return response()->json([
            'success' => true,
            'totalCart' => \Cart::getTotal() 
        ]);
    }
}
