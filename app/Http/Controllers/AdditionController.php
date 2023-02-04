<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateAdditionRequest;
use App\Models\Addition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AdditionController extends Controller
{
    public function index(Request $request)
    {
        if( $request->json ) {
            $addition = Addition::when($request->term, function ($q, $term) { 
                return $q->whereRaw("UPPER(addition) LIKE '%".mb_strtoupper($term)."%'");
            })
            ->where('user_id', Auth::id())
            ->paginate($request->per_page);

            return $addition;
        }

        if( $request->dropdown ) {
            return Addition::where('user_id', Auth::id())->get();
        }

        return Inertia::render('Client/Addition/Index', []);
    }

    public function create(Request $request)
    {
        return Inertia::render('Client/Addition/Create', []);
    }

    public function store(StoreUpdateAdditionRequest $request)
    {
        
        $data = $request->all();

        $data['user_id'] = Auth::id();

        Addition::create($data);

        return Redirect::route('addition.index')->with('message', 'Acréscimo Cadastrado com sucesso!');

    }

    public function edit(Addition $addition)
    {
        if($addition->user_id !== Auth::id()) {

            return Redirect::route('addition.index')->with('message', 'Este acréscimo não pertence ao seu usuário!');

        }

        return Inertia::render('Client/Addition/Edit', [
            'addition' => $addition,
            'action' => 'Editar'
        ]);
    }

    public function update(StoreUpdateAdditionRequest $request, $id)
    {
        
        $addition = Addition::where('id', $id);

        $addition->update($request->all());

        return Redirect::route('addition.index')->with('message', 'Acréscimo Atualizado com sucesso!');

    }

    public function destroy(Addition $addition)
    {
        
        $addition->delete();

        return response()->json(['ok']);
        
    }
}
