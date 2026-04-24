<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use App\Models\Codigo;
use Illuminate\Http\Request;

class CodigoController extends Controller
{
    public function index()
    {
        $codigos = Codigo::all();  
        $clasificadors = Clasificador::all();     
        return view('codigos.index', compact('codigos', 'clasificadors'));
    }

    public function create()
    {
        $clasificadors = Clasificador::all();     
        return view('codigos.create', compact('clasificadors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigopre' => 'required|max:15|regex:/^[A-Z0-9-]+$/',                
            'detalle' => 'required',   
            'codigo_id' => 'required|exists:clasificadors,codigo_id',
        ], [
            'codigopre.regex' => 'El código presupuestario solo permite letras, números y guiones.',
            'codigo_id.exists' => 'El código seleccionado no es válido.',
        ]);

        $codigo = new Codigo();
        // Limpiamos el input antes de guardar (Mayúsculas y guiones)
        $codigo->codigopre = strtoupper(str_replace('/', '-', $request->codigopre));
        $codigo->detalle = $request->detalle;
        $codigo->codigo_id = $request->codigo_id;
        $codigo->save();

        return redirect()->route('codigos.index')->with('mensaje', 'Registro creado con éxito');
    }

    public function edit(Codigo $codigo)
    {
        // SOLUCIÓN AL ERROR: Pasamos los clasificadores a la vista de edición
        $clasificadors = Clasificador::all(); 
        return view('codigos.edit', [
            'codigos' => $codigo, 
            'clasificadors' => $clasificadors
        ]); 
    }

  public function update(Request $request, Codigo $codigo)
{
    $request->validate([
        // Agregamos \. a la regex para permitir puntos
        'codigopre' => 'required|max:20|regex:/^[A-Z0-9\.-]+$/',
        'detalle' => 'required',  
        'codigo_id' => 'required|exists:clasificadors,codigo_id',
    ], [
        'codigopre.regex' => 'El código presupuestario solo permite letras, números, puntos y guiones.',
    ]);

    // Quitamos el str_replace de guiones si quieres mantener los puntos tal cual
    $codigo->codigopre = strtoupper($request->codigopre);
    $codigo->detalle = $request->detalle;
    $codigo->codigo_id = $request->codigo_id; 
    $codigo->save();

    return redirect()->route('codigos.index')->with('mensaje', 'Se actualizó el registro correctamente');
}

    public function destroy(Codigo $codigo)
    {
        $codigo->delete();
        return redirect()->route('codigos.index')->with('mensaje', 'Registro eliminado correctamente');
    }
}