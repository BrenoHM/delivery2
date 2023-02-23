<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateFreightRequest;
use App\Models\Freight;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class FreightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->json ) {
            $freight = Freight::when($request->term, function ($q, $term) { 
                return $q->whereRaw("UPPER(neighborhood) LIKE '%".mb_strtoupper($term)."%'");
            })
            ->where('tenant_id', Auth::user()->tenant_id)
            ->paginate($request->per_page);

            return $freight;
        }

        return Inertia::render('Client/Freight/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Client/Freight/Create', [
            'action' => 'Salvar'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateFreightRequest $request)
    {

        $data = $request->all();

        $data['tenant_id'] = Auth::user()->tenant_id;

        Freight::create($data);

        return Redirect::route('freight.index')->with('message', 'Frete Cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Freight  $freight
     * @return \Illuminate\Http\Response
     */
    public function edit(Freight $freight)
    {
        if($freight->tenant_id !== Auth::user()->tenant_id) {

            return Redirect::route('freight.index')->with('message', 'Este frete não pertence ao seu usuário!');

        }

        return Inertia::render('Client/Freight/Edit', [
            'freight' => $freight,
            'action' => 'Editar'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Freight  $freight
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateFreightRequest $request, Freight $freight)
    {

        $freight->update($request->all());

        return Redirect::route('freight.index')->with('message', 'Frete Atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Freight  $freight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Freight $freight)
    {
        $freight->delete();

        return response()->json(['ok']);
    }

    public function search(Request $request)
    {
        
        $freight = Freight::where('neighborhood', $request->neighborhood)
            ->where('city', $request->city)
            ->where('state', $request->state)
            ->where('tenant_id', config('tenant.id'))
            ->first();

        if( !$freight ) {
            $request->session()->put('freight_details', null);
            throw new \Exception("No momento não estamos entregando na sua região, mas você pode buscar no local :)");
        }

        $request->session()->put('freight_details', [
            'zip_code' => $request->cep,
            'street' => $request->street,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
            'price' => $freight->price,
            'delivery_method' => null
        ]);

        return response()->json([
            'success' => true,
            'price' => $freight->price,
            'message' => "Retirar na " . $request->street . ", " . $request->neighborhood . " por <strong>R$ " . number_format($freight->price, 2, ",", ".") . "</strong>",
        ]);

    }
}
