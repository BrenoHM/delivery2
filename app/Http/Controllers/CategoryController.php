<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = 1;//Auth::user()->tenant_id;

        if( $request->json ) {
            $category = Category::when($request->term, function ($q, $term) { 
                return $q->whereRaw("UPPER(categorie) LIKE '%".mb_strtoupper($term)."%'");
            })
            ->where('tenant_id', $tenantId)
            ->paginate($request->per_page);

            return $category;
        }

        if( $request->dropdown ) {
            return Category::where('tenant_id', $tenantId)->get();
        }

        return Inertia::render('Client/Category/Index', []);
    }

    public function create(Request $request)
    {
        return Inertia::render('Client/Category/Create', []);
    }

    public function store(StoreUpdateCategoryRequest $request)
    {
        
        $data = $request->all();

        $data['tenant_id'] = Auth::user()->tenant_id;

        Category::create($data);

        return Redirect::route('category.index')->with('message', 'Categoria Cadastrada com sucesso!');

    }

    public function edit(Category $category)
    {
        if($category->tenant_id !== Auth::user()->tenant_id) {

            return Redirect::route('category.index')->with('message', 'Esta categoria não pertence ao seu usuário!');

        }

        return Inertia::render('Client/Category/Edit', [
            'category' => $category,
            'action' => 'Editar'
        ]);
    }

    public function update(StoreUpdateCategoryRequest $request, $id)
    {
        
        $category = Category::where('id', $id);

        $category->update($request->all());

        return Redirect::route('category.index')->with('message', 'Categoria Atualizada com sucesso!');

    }

    public function destroy(Category $category)
    {
        
        $category->delete();

        return response()->json(['ok']);
        
    }
}
