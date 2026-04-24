<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\Departamento; // Importar el modelo  


class BitacoraController extends Controller
{

    public function index(Request $request)
{
    // Mantenemos la carga de relaciones para evitar lentitud
    $query = Bitacora::with(['user.departamento'])->latest();

    // 1. Filtro por Nombre de Funcionario
    if ($request->filled('usuario')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->usuario}%");
        });
    }

    // 2. Filtro por Departamento (Dotación)
    if ($request->filled('departamento_id')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('departamento_id', $request->departamento_id);
        });
    }

    // 3. Filtro por Acción
    if ($request->filled('accion')) {
        $query->where('accion', $request->accion);
    }

    // 4. Filtro por ID de Proyecto
    if ($request->filled('proyecto_id')) {
        $query->where('proyecto_id', $request->proyecto_id);
    }

    // 5. NUEVO: Filtro por Rango de Fechas (Inicio y Término)
    if ($request->filled('fecha_inicio')) {
        $query->whereDate('created_at', '>=', $request->fecha_inicio);
    }
    if ($request->filled('fecha_fin')) {
        $query->whereDate('created_at', '<=', $request->fecha_fin);
    }

    $bitacoras = $query->paginate(8); // Aumentado a 20 por ser pantalla ancha
    $departamentos = \App\Models\Departamento::all(); 

    return view('bitacora.index', compact('bitacoras', 'departamentos'));
}
}
