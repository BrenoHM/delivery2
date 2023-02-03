<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProductRequest;
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
                        ->where('user_id', Auth::id())
                        ->paginate($request->per_page);

            return $product;
        }

        return Inertia::render('Client/Products/Index', []);
    }

    public function create(Request $request)
    {
        return Inertia::render('Client/Products/Create', []);
    }

    public function store(StoreUpdateProductRequest $request)
    {
        
        $data = $request->all();

        $data['user_id'] = Auth::id();

        //$request->photo->store('.');
        if( $request->photo ) {
            $path = Storage::put('', $request->photo, 'public');
            if( $path ) {
                $data['photo'] = env('AWS_URL') . '/' . $path;
            }
        }

        Product::create($data);

        return Redirect::route('client.products')->with('message', 'Produto Cadastrado com sucesso!');

    }

    public function edit(Product $product)
    {
        if($product->user_id !== Auth::id()) {

            return Redirect::route('client.products')->with('message', 'Este produto não pertence ao seu usuário!');

        }

        return Inertia::render('Client/Products/Edit', [
            'product' => $product,
            'action' => 'Editar'
        ]);
    }

    public function update(StoreUpdateProductRequest $request, $id)
    {
        
        $product = Product::where('id', $id);
        
        $builderProduct = $product->first();

        $data = $request->except('photo');  

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

        $product->update($data);

        return Redirect::route('client.products')->with('message', 'Produto Atualizado com sucesso!');

    }

    public function destroy(Product $product)
    {
        //dd($product->id);
        // Pet::where('id', $id)->delete();
        $product->delete();
        return 'ok';
        //return Redirect::route('client.products')->with('message', 'Produto inativado com sucesso!');
    }
}
