<?php

namespace App\Http\Controllers;

use App\Models\EstadoCompra;
use Illuminate\Http\Request;

class EstadoCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $estadocompras = EstadoCompra::all();
        return view('estadocompras.index', compact('estadocompras')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('estadocompras.create');
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

        $estado = new EstadoCompra();
        $estado->detalle = $request->detalle;
        $estado->save();
        return redirect()->route('estadocompras.index')->with('mensaje', 'Se registro el estado de la manera correcta');

    }

    /**
     * Display the specified resource.
     */
    public function show(EstadoCompra $estadoCompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstadoCompra $estadocompras, $id)
    {
        //
        $estadocompras= EstadoCompra::findOrFail($id);
        return view('estadocompras.edit', ['estadocompras'=>$estadocompras]); 
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'detalle' => 'required',           
        ]);
    
        $estadoCompra = EstadoCompra::findOrFail($id);
        $estadoCompra->detalle = $request->detalle;
        $estadoCompra->save();
    
        return redirect()->route('estadocompras.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        EstadoCompra::destroy($id);
        return redirect()->route('estadocompras.index')->with('mensaje', 'Se eliminó el registro');
   
    }
}
