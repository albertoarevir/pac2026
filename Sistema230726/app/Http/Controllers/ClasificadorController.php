<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use Illuminate\Http\Request;

class ClasificadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clasificadors = Clasificador::all();       
        return view('clasificador.index', compact('clasificadors'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clasificador.create');
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'codigo_id' => 'required',
            'detalle' => 'required',                     
        ]);

        $clasificadors = new Clasificador();
        $clasificadors->codigo_id = $request->codigo_id;
        $clasificadors->detalle = $request->detalle;

        $clasificadors->save();
        return redirect()->route('clasificador.index')->with('mensaje', 'Se registro el estado de la manera correcta');


    }

    /**
     * Display the specified resource.
     */
    public function show(Clasificador $clasificador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $clasificadors = Clasificador::findOrFail($id);
        return view('clasificador.edit', ['clasificador'=>$clasificadors]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clasificador $clasificadors, $id)
    {
        //
        $request->validate([
            'codigo_id' => 'required',
            'detalle' => 'required',           
        ]);
    
        $clasificadors = Clasificador::findOrFail($id);     
        $clasificadors->codigo_id = $request->codigo_id;
        $clasificadors->detalle = $request->detalle;
        $clasificadors->save();
    
        return redirect()->route('clasificador.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clasificador $clasificadors, $id)
    {
        //
        Clasificador::destroy($id);
        return redirect()->route('clasificador.index')->with('mensaje', 'Se eliminó el registro del control de Especies/Servicios');
  
    }
}
