<?php

namespace App\Http\Controllers;

use App\Models\EstadoLicitacion;
use Illuminate\Http\Request;

class EstadoLicitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $estadoslic = EstadoLicitacion::all();
        return view('estadolicitacion.index', compact('estadoslic'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('estadolicitacion.create');
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

        $estadoLicitacion = new EstadoLicitacion();
        $estadoLicitacion->detalle = $request->detalle;
        $estadoLicitacion->save();
        return redirect()->route('estadolicitacion.index')->with('success', 'Estado de La manera correcta');
    }

    /**
     * Display the specified resource.
     */
    public function show(EstadoLicitacion $estadoLicitacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
       
        $estadoLicitacion = EstadoLicitacion::findOrFail($id);
        return view('estadolicitacion.edit', compact('estadoLicitacion')); 
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
        $estadoLicitacion = EstadoLicitacion::findOrFail($id);  
        $estadoLicitacion->detalle = $request->detalle;
        $estadoLicitacion->save();
    
        return redirect()->route('estadolicitacion.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstadoLicitacion $estadoLicitacion, $id)
    {
        //
        EstadoLicitacion::destroy($id);
        return redirect()->route('estadolicitacion.index')->with('mensaje', 'Se eliminó el registro');
    }
}
