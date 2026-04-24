<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\Departamento;
use Illuminate\Http\Request;

class EspecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especies = Especie::all();
        $departamentos = Departamento::all();
        return view('especies.index', compact('especies', 'departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::all(); // Fetch departments for the create form
        $especies = Especie::all(); // <--- ADD THIS LINE to fetch all species

        // Pass both variables to the view
        return view('especies.create', compact('departamentos', 'especies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'departamento_id' => 'required|exists:departamentos,id',
            'detalle' => 'required',
        ]);

        $especie = new Especie();
        $especie->departamento_id = $request->departamento_id;
        $especie->detalle = $request->detalle;

        $especie->save();
        return redirect()->route('especies.index')->with('mensaje', 'Se registró la Especie/Servicio de la manera correcta');
    }

    /**
     * Display the specified resource.
     */
    public function show(Especie $especie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $especies= Especie::findOrFail($id);
        return view('especies.edit', ['especies'=>$especies]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especie $especie, $id)
    {
        $request->validate([
            'detalle' => 'required',
        ]);

        $especies = Especie::findOrFail($id);
        $especies->detalle = $request->detalle;
        $especies->save();

        return redirect()->route('especies.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especie $especie, $id)
    {
        Especie::destroy($id);
        return redirect()->route('especies.index')->with('mensaje', 'Se eliminó el registro del control de Especies/Servicios');
    }
}