<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $estados = Estado::all();
        return view('estados.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('estados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'detalle' => 'required',                     
        ]);

        $estado = new Estado();
        $estado->detalle = $request->detalle;
        $estado->save();
        return redirect()->route('estados.index')->with('mensaje', 'Se registro el estado de la manera correcta');


    }

    /**
     * Display the specified resource.
     */
    public function show(Estado $estado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $estados= Estado::findOrFail($id);
        return view('estados.edit', ['estados'=>$estados]); 
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estado $estado)
{
    //
    $request->validate([
        'detalle' => 'required',           
    ]);

    $estado->detalle = $request->detalle;
    $estado->save();

    return redirect()->route('estados.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        Estado::destroy($id);
        return redirect()->route('estados.index')->with('mensaje', 'Se eliminó el registro');
    }
}
    
