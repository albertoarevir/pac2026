<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\Departamento;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $query = Bitacora::with(['user.departamento'])->latest();

        if ($authUser->departamento_id !== 7) {
            $deptId = $authUser->departamento_id;
            $query->whereHas('user', function ($q) use ($deptId) {
                $q->where('departamento_id', $deptId);
            });
        } elseif ($request->filled('departamento_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('departamento_id', $request->departamento_id);
            });
        }

        if ($request->filled('usuario')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'ilike', "%{$request->usuario}%");
            });
        }

        if ($request->filled('accion')) {
            $query->whereRaw('LOWER(accion) = ?', [strtolower($request->accion)]);
        }

        if ($request->filled('proyecto_id')) {
            $query->where('proyecto_id', $request->proyecto_id);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $bitacoras     = $query->paginate(8);
        $departamentos = Departamento::all();

        return view('bitacora.index', compact('bitacoras', 'departamentos'));
    }
}