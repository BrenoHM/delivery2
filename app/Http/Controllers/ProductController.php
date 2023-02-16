<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProductRequest;
use App\Models\Addition;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if( $request->json ) {
            $product = Product::with('category')
                        ->when($request->term, function ($q, $term) { 
                            return $q->whereRaw("UPPER(name) LIKE '%".mb_strtoupper($term)."%'");
                            // ->orWhereHas('category', function ($query) use ($term) {
                            //     $query->where('categorie', 'like', '%'.$term.'%');
                            // });
                                    //->whereRelation('category', DB::raw('UPPER(categorie)'), 'like', DB::raw('UPPER(categorie)') );
                        })
                        ->where('tenant_id', Auth::user()->tenant_id)
                        ->paginate($request->per_page);

            return $product;
        }

        return Inertia::render('Client/Products/Index', []);
    }

    public function create(Request $request)
    {
        $additions = Addition::where('tenant_id', Auth::user()->tenant_id)->get();

        return Inertia::render('Client/Products/Create', [
            'additions' => $additions
        ]);
    }

    public function store(StoreUpdateProductRequest $request)
    {
        
        $data = $request->all();

        $data['tenant_id'] = Auth::user()->tenant_id;

        //$request->photo->store('.');
        if( $request->photo ) {
            $path = Storage::put('', $request->photo, 'public');
            if( $path ) {
                $data['photo'] = env('AWS_URL') . '/' . $path;
            }
        }

        $product = Product::create($data);

        if( $product ) {
            if( $request->additions ) {
                $product->additions()->attach($request->additions);
            }
        }

        return Redirect::route('client.products')->with('message', 'Produto Cadastrado com sucesso!');

    }

    public function show($tenant, Product $product)
    {
        if( $product->tenant_id !== config('tenant.id') ){
            abort(404);
        }
        dd($product);
    }

    public function edit(Product $product)
    {
        if($product->tenant_id !== Auth::user()->tenant_id) {

            return Redirect::route('client.products')->with('message', 'Este produto não pertence ao seu usuário!');

        }

        $additions = Addition::where('tenant_id', Auth::user()->tenant_id)->get();
        $add = [];
        
        $ids = [];
        if( $product->additions ){
            foreach($product->additions as $id) {
                $ids[] = $id->id;
            }
        }

        if( $additions ) {
            foreach($additions as $addition) {
                $arrayAdition = $addition->toArray();
                $arrayAdition['checked'] = in_array($arrayAdition['id'], $ids) ? true : false;
                array_push($add, $arrayAdition);
            }
        }

        return Inertia::render('Client/Products/Edit', [
            'product' => $product,
            'additions' => $add,
            'idsAdditions' => $ids,
            'action' => 'Editar'
        ]);
    }

    public function update(StoreUpdateProductRequest $request, $id)
    {
        
        $product = Product::where('id', $id);
        
        $builderProduct = $product->first();

        $data = $request->except(['photo', 'additions']);

        if( $request->photo ){
            $path = Storage::put('', $request->photo, 'public');
            if( $path ) {
                $data['photo'] = env('AWS_URL') . '/' . $path;
            }
            if( $builderProduct->photo ) {
                $arrayHttpImage = explode("/", $builderProduct->photo);
                $imageOld = end($arrayHttpImage);
                Storage::delete($imageOld);
            }
        }

        if( $product->update($data) ) {
            $builderProduct->additions()->sync($request->additions);
        }

        return Redirect::route('client.products')->with('message', 'Produto Atualizado com sucesso!');

    }

    public function destroy(Product $product)
    {
        $product->delete();
        return 'ok';
    }

    // public function tb()
    // {
    //     $product = Product::find(1);
    //     //dd($product);
    //     //$product->additions()->attach(array(1,2));
    //     $product->additions()->sync(array(3,4));

    //     $product2 = Product::find(2);

    //     $product2->additions()->sync(array(1,2));
    //     dd('ok');
    // }
}
