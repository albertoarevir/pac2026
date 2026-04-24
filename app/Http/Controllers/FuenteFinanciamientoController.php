<?php

namespace App\Http\Controllers;

use App\Models\FuenteFinanciamiento;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Especie;
use App\Models\Codigo;
use App\Models\Clasificador;
use App\Models\Estado;
use App\Models\EstadoModificacion;

class FuenteFinanciamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $fuentes = FuenteFinanciamiento::all(); // Obtener todos los registros de la tabla
        return view('fuentefinanciamiento.index', compact('fuentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Agrega esta línea para obtener todas las fuentes de financiamiento
        $fuentes = FuenteFinanciamiento::all();

        return view('/fuentefinanciamiento.create', compact('fuentes'));
    }


     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fuente = FuenteFinanciamiento::findOrFail($id);
        return view('fuentefinanciamiento.edit', compact('fuente'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'detalle' => 'required',
        ]);

        $fuentes = new FuenteFinanciamiento();
        $fuentes->detalle = $request->detalle;
        $fuentes->save();
        return redirect()->route('fuentefinanciamiento.index')->with('mensaje', 'Se registro el estado de la manera correcta');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, string $id)
    {
        // 1. Corregir la validación para que se base en el campo 'detalle'
        $request->validate([
            'detalle' => 'required',
        ]);

        // 2. Obtener la fuente de financiamiento por su ID
        $fuente = FuenteFinanciamiento::findOrFail($id);

        // 3. Asignar el nuevo valor y guardar los cambios
        $fuente->detalle = $request->detalle;
        $fuente->save();

        return redirect()->route('fuentefinanciamiento.index')->with('mensaje', 'Se actualizó el registro de la manera correcta');
    }
    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        FuenteFinanciamiento::destroy($id);
        return redirect()->route('fuentefinanciamiento.index')->with('mensaje', 'Se eliminó el registro');
    }
}
