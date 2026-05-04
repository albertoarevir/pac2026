<?php

namespace App\Http\Controllers;

use App\Models\Modalidad;
use App\Models\Pac;
use App\Models\Tipocompra;
use Illuminate\Http\Request;
use App\Models\EstadoCompra;
use App\Models\EstadoLicitacion;
use App\Models\Orden;
use Carbon\Carbon;

class OrdenController extends Controller
{
    /**
     * Muestra el listado de órdenes con lógica de auditoría.
     */
    public function index()
    {
        $user = auth()->user();
        $dotacion = $user->departamento_id;
        $ordenes = null;

        if ($dotacion === 7) {
            $ordenes = Orden::with('estadocompra', 'pac.especie', 'licitacion')->orderBy('id', 'desc')->get();
        } else {
            $ordenes = Orden::whereHas('pac', function ($query) use ($dotacion) {
                $query->where('departamento_id', $dotacion);
            })->with('estadocompra', 'pac.especie', 'licitacion')->orderBy('id', 'desc')->get();
        }

        $ordenes->transform(function ($orden) {
            $fechaActual = Carbon::now();
            $fechaRegistro = Carbon::parse($orden->updated_at);
            $diasTranscurridos = $fechaActual->diffInDays($fechaRegistro);

            if ($orden->estado_id == 1 && $diasTranscurridos >= 20) {
                $orden->auditoria = '<span style="color: red;"><strong>Han pasado ' . $diasTranscurridos . ' días sin actualizar</strong></span>';
            } else {
                $orden->auditoria = '<span style="color: green;"><strong>Sin observación</strong></span>';
            }
            return $orden;
        });

        $pac = Pac::first();

        if ($pac === null) {
            return view('ordenes.index', ['mensaje' => 'NO HAY REGISTROS']);
        }

        $ordenesCount = $ordenes ? $ordenes->count() : 0;

        if ($ordenesCount == 0) {
            return view('ordenes.index', ['pac' => $pac, 'ordenesCount' => $ordenesCount, 'mensaje' => 'NO HAY REGISTROS']);
        }

        return view('ordenes.index', ['ordenes' => $ordenes, 'pac' => $pac, 'ordenesCount' => $ordenesCount]);
    }

    /**
     * Formulario de creación.
     */
    public function create(Request $request, $pac, $modalidad, $numero, $id_mod)
    {
        $pac_id = $pac;
        $modalidades = Modalidad::all();
        $tipocompras = Tipocompra::all();
        $estados = EstadoLicitacion::all();
        $estadocompras = EstadoCompra::all();

        $modalidad_obj = Modalidad::find($id_mod);
        $id_mod = $modalidad_obj ? $modalidad_obj->id : null;

        return view('ordenes.create', compact('pac_id', 'id_mod', 'tipocompras', 'estados', 'modalidades', 'numero', 'modalidad', 'estadocompras'));
    }

    /**
     * Guarda una nueva Orden de Compra.
     */
    public function store(Request $request, $pac_id, $id_mod)
    {
        // 1. Limpieza de datos antes de validar
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        // 2. Validación con Regex y mensajes personalizados
        $request->validate([
            'numero'            => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'         => 'required',
            'observacion'       => 'nullable',
            'monto'             => 'required',
            'fecha_seguimiento' => 'required|date',
        ], [
            'numero.regex' => 'El número de orden solo permite letras, números y guiones (-).',
            'numero.max'   => 'El número de orden no puede superar los 15 caracteres.'
        ]);

        // Formatear monto
        $monto = str_replace('.', '', $request->monto);
        $monto = str_replace(',', '.', $monto);

        $orden = new Orden();
        $orden->numero = $request->numero;
        $orden->estado_id = $request->estado_id;
        $orden->observacion = $request->observacion;
        $orden->monto = $monto;
        $orden->id_proyecto = $pac_id;
        $orden->id_licitacion = $id_mod;
        $orden->fecha_seguimiento = $request->fecha_seguimiento;
        $orden->save();

        return redirect()->route('ordenes.index')->with('success', 'Orden de compra guardada correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $orden = Orden::findOrFail($id);
        $user = auth()->user();
        $pacOwner = Pac::find($orden->id_proyecto);
        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para editar esta orden.');
        }
        $pac = $pacOwner;
        $tipocompras = Tipocompra::all();
        $estados = EstadoLicitacion::all();
        $modalidad = Modalidad::find($orden->id_licitacion);
        $estadocompras = EstadoCompra::all();
        
        return view('ordenes.edit', compact('orden', 'tipocompras', 'estados', 'pac', 'modalidad', 'estadocompras'));
    }

    /**
     * Actualiza la Orden de Compra.
     */
    public function update(Request $request, $id)
    {
        // 1. Limpieza de datos
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        // 2. Validación
        $request->validate([
            'numero'            => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'         => 'required|exists:estado_compras,id',
            'observacion'       => 'nullable',
            'id_proyecto'       => 'required',
            'id_licitacion'     => 'required',
            'monto'             => 'required',
            'fecha_seguimiento' => 'nullable',
        ], [
            'numero.regex' => 'El número de orden solo permite letras, números y guiones (-).',
            'numero.max'   => 'El número de orden no puede superar los 15 caracteres.'
        ]);

        $monto = str_replace('.', '', $request->monto);
        $monto = str_replace(',', '.', $monto);

        $orden = Orden::findOrFail($id);
        $user = auth()->user();
        $pacOwner = Pac::find($orden->id_proyecto);
        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }
        $orden->numero = $request->numero;
        $orden->estado_id = $request->estado_id;
        $orden->observacion = $request->observacion;
        $orden->id_proyecto = $request->id_proyecto;
        $orden->id_licitacion = $request->id_licitacion;
        $orden->monto = $monto;
        $orden->fecha_seguimiento = $request->fecha_seguimiento;
        $orden->save();

        return redirect()->route('ordenes.index')->with('mensaje', 'Se actualizó el registro correctamente');
    }

    /**
     * Elimina el registro.
     */
    public function destroy($id)
    {
        $orden = Orden::findOrFail($id);
        $user = auth()->user();
        $pacOwner = Pac::find($orden->id_proyecto);
        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para eliminar esta orden.');
        }
        $orden->delete();
        return redirect()->route('ordenes.index')->with('mensaje', 'Se eliminó el registro correctamente');
    }
}