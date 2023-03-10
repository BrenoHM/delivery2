<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateProductRequest;
use App\Models\Addition;
use App\Models\Product;
use App\Models\ProductVariationOption;
use App\Models\Timeline;
use App\Models\Variation;
use App\Models\VariationOption;
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

        $variationNames = collect(VariationOption::get()->toArray())->mapWithKeys(function (array $item, int $key) {
            return [$item['id'] => $item['option']];
        });

        $variationOptions = Variation::with('options')->get();

        return Inertia::render('Client/Products/Create', [
            'additions' => $additions,
            'variationOptions' => $variationOptions,
            'variationNames' => $variationNames->all(),
            'action' => 'Salvar'
        ]);
    }

    public function store(StoreUpdateProductRequest $request)
    {
        
        $data = $request->all();

        $data['tenant_id'] = Auth::user()->tenant_id;

        //$request->photo->store('.');
        if( $request->photo ) {
            $path = Storage::put('tenants/'.Auth::user()->tenant_id.'/products', $request->photo, 'public');
            if( $path ) {
                $data['photo'] = env('AWS_URL') . '/' . $path;
            }
        }

        $product = Product::create($data);

        if( $product ) {

            if( $request->additions ) {
                $product->additions()->attach($request->additions);
            }

            if( $request->variations ) {
                $product->variations()->createMany($request->variations);
            }

        }

        return Redirect::route('client.products')->with('message', 'Produto Cadastrado com sucesso!');

    }

    public function show($tenant, Product $product)
    {

        $tenantId = config('tenant.id');

        if( $product->tenant_id !== $tenantId ){
            abort(404);
        }

        $isOpened = Timeline::isOpened($tenantId);

        return view('tenant.pages.show', [
            'product' => $product->load(['additions', 'variations', 'variations.option']),
            'tenant' => $tenant,
            'isOpened' => $isOpened
        ]);
        
    }

    public function edit(Product $product)
    {
        if($product->tenant_id !== Auth::user()->tenant_id) {

            return Redirect::route('client.products')->with('message', 'Este produto n??o pertence ao seu usu??rio!');

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

        $variationNames = collect(VariationOption::get()->toArray())->mapWithKeys(function (array $item, int $key) {
            return [$item['id'] => $item['option']];
        });

        $variationOptions = Variation::with('options')->get();

        return Inertia::render('Client/Products/Edit', [
            'product' => $product,
            'additions' => $add,
            'idsAdditions' => $ids,
            'variationNames' => $variationNames,
            'variationOptions' => $variationOptions,
            'variations' => json_decode($product->variations->toJson()),
            'action' => 'Editar'
        ]);
    }

    public function update(StoreUpdateProductRequest $request, $id)
    {

        //dd($request->all());
        
        $product = Product::with('variations')->where('id', $id);
        
        $builderProduct = $product->first();

        $data = $request->except(['photo', 'additions', 'variations']);

        if( $request->photo ){
            $path = Storage::put('tenants/'.Auth::user()->tenant_id.'/products', $request->photo, 'public');
            if( $path ) {
                $data['photo'] = env('AWS_URL') . '/' . $path;
            }
            if( $builderProduct->photo ) {
                $arrayHttpImage = explode("/", $builderProduct->photo);
                $imageOld = end($arrayHttpImage);
                Storage::delete('tenants/'.Auth::user()->tenant_id.'/products/'.$imageOld);
            }
        }

        $idsVariations = $request->variations ? collect($request->variations)->pluck('id')->toArray() : [];

        if( $product->update($data) ) {
            $builderProduct->additions()->sync($request->additions);

            $builderProduct->variations()->whereNotIn('id', $idsVariations)->delete();

            if( $request->variations ) {
                $builderProduct->variations()->upsert($request->variations, ['id']);
            }
            
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

    public function tb_variants()
    {
        $product = Product::find(1);

        //na hora de inserir
        // $product->variations()->createMany([
        //     ['variation_option_id' => 1, 'price' => 20],
        //     ['variation_option_id' => 2, 'price' => 40],
        //     ['variation_option_id' => 3, 'price' => 50],
        // ]);

        //no update
        // Delete
        $product
        ->variations()
        ->whereNotIn('id', [1, 2])
        ->delete();

        $product->variations()->upsert([
            ['id'=> 1, 'product_id' => 1, 'variation_option_id' => 1, 'price' => 25],
            ['id'=> 2, 'product_id' => 1, 'variation_option_id' => 2, 'price' => 45],
            ['id'=> null, 'product_id' => 1, 'variation_option_id' => 4, 'price' => 50],
        ], ['id']);

        dd('ok');
    }
}
