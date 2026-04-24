<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Models\User;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();
        $departamentos = Departamento::all()->sortByDesc('id');
        return view('/departamentos.index', compact('usuarios', 'departamentos'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departamentos/create');
        //
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
        $departamento = new Departamento();
        
        $departamento->detalle = $request->detalle;
       
        
        $departamento->save();
        return redirect()->route('departamentos.index')->with('mensaje', 'Se registro el Departamento de la manera correcta');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamentos, $id)
    {
        $departamentos= Departamento::findOrFail($id);
        return view('departamentos.edit', ['departamentos'=>$departamentos]); 
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departamento $departamentos, $id)
    {
        $request->validate([
            'detalle' => 'required',           
        ]);
    
        $departamentos = Departamento::findOrFail($id);     
        $departamentos->detalle = $request->detalle;
        $departamentos->save();
    
        return redirect()->route('departamentos.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departamento $departamentos, $id)
    {
        Departamento::destroy($id);
        return redirect()->route('departamentos.index')->with('mensaje', 'Se eliminó el registro del control de Departamento');
        //
    }
}
