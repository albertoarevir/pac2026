<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function index()
    {
        $grados = Grado::all()->sortByDesc('id');
        return view('grados.index', ['grados'=>$grados]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('grados/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'detalle' => 'required',          
           
        ]);


        $grado = new Grado();
        
        $grado->detalle = $request->detalle;
       
        
        $grado->save();
        return redirect()->route('grados.index')->with('mensaje', 'Se registro el Grado de la manera correcta');

    }

    /**
     * Display the specified resource.
     */
    public function show(Grado $grado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $grados= Grado::findOrFail($id);
        return view('grados.edit', ['grados'=>$grados]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'detalle' => 'required',           
        ]);
    
        $grados = Grado::findOrFail($id);     
        $grados->detalle = $request->detalle;
        $grados->save();
    
        return redirect()->route('grados.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Grado::destroy($id);
        return redirect()->route('grados.index')->with('mensaje', 'Se eliminó el registro del control de expedientes');
    }

}
