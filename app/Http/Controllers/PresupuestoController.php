<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presupuesto;
use App\Models\Departamento;
use App\Models\Clasificador;

class PresupuestoController extends Controller
{
    public function index()
    {
        // Traemos los presupuestos con su departamento para evitar el problema de N+1
        $Presupuestos = Presupuesto::with('departamento')->orderBy('id', 'desc')->get();
        return view('presupuesto.index', compact('Presupuestos'));
    }

    public function create()
    {
        $departamentos = Departamento::all();
        $clasificadors = Clasificador::all();
        return view('presupuesto.create', compact('departamentos', 'clasificadors'));
    }

    public function store(Request $request)
    {
        // Limpiamos el formato de moneda (quitamos puntos) antes de validar
        if ($request->has('presupuesto')) {
            $request->merge([
                'presupuesto' => str_replace('.', '', $request->presupuesto)
            ]);
        }

        $request->validate([
            'year'           => 'required|integer',
            'clasificador'   => 'required|string|max:10',
            'codigo_id'      => 'required|string|max:50', // Este se guarda en 'item'
            'presupuesto'    => 'required|numeric',       // Este se guarda en 'monto'
            'departamento'   => 'required|exists:departamentos,id',
            'observacion'    => 'nullable|string'
        ]);

        $presupuesto = new Presupuesto();
        $presupuesto->year            = $request->year;
        $presupuesto->clasificador    = $request->clasificador;
        $presupuesto->item            = $request->codigo_id; 
        $presupuesto->monto           = $request->presupuesto;
        $presupuesto->departamento_id = $request->departamento;
        $presupuesto->observaciones   = $request->observacion;
        $presupuesto->save();

        return redirect()->route('presupuesto.index')
                         ->with('mensaje', 'Presupuesto creado con éxito.');
    }

    public function edit(string $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $departamentos = Departamento::all();
        $clasificadors = Clasificador::all();

        return view('presupuesto.edit', compact('presupuesto', 'departamentos', 'clasificadors'));
    }

    public function update(Request $request, string $id)
    {
        // Limpiamos el monto
        $request->merge([
            'presupuesto' => str_replace('.', '', $request->presupuesto)
        ]);

        $request->validate([
            'year'           => 'required|integer',
            'clasificador'   => 'required|string|max:10',
            'codigo_id'      => 'required|string|max:50',
            'presupuesto'    => 'required|numeric',
            'departamento'   => 'required|exists:departamentos,id',
            'observacion'    => 'nullable|string'
        ]);

        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->year            = $request->year;
        $presupuesto->clasificador    = $request->clasificador;
        $presupuesto->item            = $request->codigo_id;
        $presupuesto->monto           = $request->presupuesto;
        $presupuesto->departamento_id = $request->departamento;
        $presupuesto->observaciones   = $request->observacion;
        $presupuesto->save();

        return redirect()->route('presupuesto.index')
                         ->with('mensaje', 'Presupuesto actualizado con éxito.');
    }

    public function destroy(string $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->delete();

        return redirect()->route('presupuesto.index')
                         ->with('mensaje', 'Registro eliminado correctamente.');
    }
}