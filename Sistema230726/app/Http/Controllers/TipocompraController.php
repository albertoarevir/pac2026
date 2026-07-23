<?php

namespace App\Http\Controllers;

use App\Models\Tipocompra;
use Illuminate\Http\Request;
use App\Models\User;

class TipocompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $tipocompras = Tipocompra::all()->sortByDesc('id');
        return view('/tipodecompra.index', compact('tipocompras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipocompras = Tipocompra::all();
        return view('tipodecompra/create', compact('tipocompras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'detalle' => 'required',  
        //
        ]);
        $tipocompras = new Tipocompra();
        
        $tipocompras->detalle = $request->detalle;
       
        
        $tipocompras->save();
        return redirect()->route('tipodecompra.index')->with('mensaje', 'Se registro el Tipo de Compra de manera correcta');
   
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipocompra $tipocompra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipocompra $tipocompra, $id)
    {
        $tipocompras = Tipocompra::findOrFail($id);
        return view('tipodecompra.edit', ['tipocompras'=>$tipocompras]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tipocompra $tipocompra, $id)
    {
        $request->validate([
            'detalle' => 'required',           
        ]);
    
        $tipocompra = Tipocompra::findOrFail($id);     
        $tipocompra->detalle = $request->detalle;
        $tipocompra->save();
    
        return redirect()->route('tipodecompra.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipocompra $tipocompra, $id)
    {
        Tipocompra::destroy($id);
        return redirect()->route('tipodecompra.index')->with('mensaje', 'Se eliminó el registro del control de Departamento');
       
    }
}
