<?php

namespace App\Http\Controllers;

use App\Models\UnidadCompra;
use Illuminate\Http\Request;

class UnidadCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unidadcompras = UnidadCompra::all();          
        return view('/unidadcompra.index', compact('unidadcompras'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('unidadcompra/create');
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

        $unidadcompras = new UnidadCompra();        
        $unidadcompras->detalle = $request->detalle;
       
        
        $unidadcompras->save();
        return redirect()->route('unidadcompra.index')->with('mensaje', 'Se registro la Unidad de Compras de la manera correcta');

    }

    /**
     * Display the specified resource.
     */
    public function show(UnidadCompra $unidadCompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $unidadcompras= UnidadCompra::findOrFail($id);
        return view('unidadcompra.edit', ['unidadcompras'=>$unidadcompras]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnidadCompra $unidadCompra, $id)
    {
        //
        $request->validate([
            'detalle' => 'required',           
        ]);
    
        $unidadcompras = UnidadCompra::findOrFail($id);     
        $unidadcompras->detalle = $request->detalle;
        $unidadcompras->save();
    
        return redirect()->route('unidadcompra.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnidadCompra $unidadCompra, $id)
    {
        //
        UnidadCompra::destroy($id);
        return redirect()->route('unidadcompra.index')->with('mensaje', 'Se eliminó el registro de la manera correcta');
    }
}
