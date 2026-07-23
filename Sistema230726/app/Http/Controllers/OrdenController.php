<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
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
    public function index()
    {
        $user     = auth()->user();
        $dotacion = $user->departamento_id;

        if ($dotacion === 7) {
            $ordenes = Orden::with('estadocompra', 'pac.especie', 'licitacion')->orderBy('id', 'desc')->get();
        } else {
            $ordenes = Orden::whereHas('pac', function ($query) use ($dotacion) {
                $query->where('departamento_id', $dotacion);
            })->with('estadocompra', 'pac.especie', 'licitacion')->orderBy('id', 'desc')->get();
        }

        $ordenes->transform(function ($orden) {
            $fechaActual      = Carbon::now();
            $fechaRegistro    = Carbon::parse($orden->updated_at);
            $diasTranscurridos = $fechaActual->diffInDays($fechaRegistro);

            if ($orden->estado_id == 1 && $diasTranscurridos >= 20) {
                $orden->auditoria = '<span style="color: red;"><strong>Han pasado ' . $diasTranscurridos . ' dias sin actualizar</strong></span>';
            } else {
                $orden->auditoria = '<span style="color: green;"><strong>Sin observacion</strong></span>';
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

    public function create(Request $request, $pac, $modalidad, $numero, $id_mod)
    {
        $pac_id      = $pac;
        $modalidades = Modalidad::all();
        $tipocompras = Tipocompra::all();
        $estados     = EstadoLicitacion::all();
        $estadocompras = EstadoCompra::all();

        $modalidad_obj = Modalidad::find($id_mod);
        $id_mod        = $modalidad_obj ? $modalidad_obj->id : null;

        return view('ordenes.create', compact('pac_id', 'id_mod', 'tipocompras', 'estados', 'modalidades', 'numero', 'modalidad', 'estadocompras'));
    }

    public function store(Request $request, $pac_id, $id_mod)
    {
        $pac  = Pac::findOrFail($pac_id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para crear ordenes en este PAC.');
        }

        $montoLimpio = str_replace(['.', ','], ['', '.'], $request->monto);

        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero)),
            'monto'  => $montoLimpio,
        ]);

        $request->validate([
            'numero'            => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'         => 'required|exists:estado_compras,id',
            'observacion'       => 'nullable|string|max:1000',
            'monto'             => 'required|numeric|min:0',
            'fecha_seguimiento' => 'required|date',
        ], [
            'numero.regex'  => 'El numero de orden solo permite letras, numeros y guiones (-).',
            'numero.max'    => 'El numero de orden no puede superar los 15 caracteres.',
            'monto.numeric' => 'El monto debe ser un valor numerico.',
        ]);

        $orden                  = new Orden();
        $orden->numero          = $request->numero;
        $orden->estado_id       = $request->estado_id;
        $orden->observacion     = $request->observacion;
        $orden->monto           = $request->monto;
        $orden->id_proyecto     = $pac_id;
        $orden->id_licitacion   = $id_mod;
        $orden->fecha_seguimiento = $request->fecha_seguimiento;
        $orden->save();

        return redirect()->route('ordenes.index')->with('success', 'Orden de compra guardada correctamente.');
    }

    public function edit($id)
    {
        $orden     = Orden::findOrFail($id);
        $user      = auth()->user();
        $pacOwner  = Pac::find($orden->id_proyecto);

        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para editar esta orden.');
        }

        $pac           = $pacOwner;
        $tipocompras   = Tipocompra::all();
        $estados       = EstadoLicitacion::all();
        $modalidad     = Modalidad::find($orden->id_licitacion);
        $estadocompras = EstadoCompra::all();

        return view('ordenes.edit', compact('orden', 'tipocompras', 'estados', 'pac', 'modalidad', 'estadocompras'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        $request->validate([
            'numero'            => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'         => 'required|exists:estado_compras,id',
            'observacion'       => 'nullable',
            'monto'             => 'required',
            'fecha_seguimiento' => 'nullable',
        ], [
            'numero.regex' => 'El numero de orden solo permite letras, numeros y guiones (-).',
            'numero.max'   => 'El numero de orden no puede superar los 15 caracteres.',
        ]);

        $monto    = str_replace(['.', ','], ['', '.'], $request->monto);
        $orden    = Orden::findOrFail($id);
        $user     = auth()->user();
        $pacOwner = Pac::find($orden->id_proyecto);

        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para modificar esta orden.');
        }

        $anterior = 'Numero: ' . $orden->numero . ' | Estado: ' . $orden->estado_id . ' | Monto: ' . $orden->monto;

        $orden->numero            = $request->numero;
        $orden->estado_id         = $request->estado_id;
        $orden->observacion       = $request->observacion;
        $orden->monto             = $monto;
        $orden->fecha_seguimiento = $request->fecha_seguimiento;
        // id_proyecto e id_licitacion son inmutables una vez creada la orden
        $orden->save();

        return redirect()->route('ordenes.index')->with('mensaje', 'Se actualizo el registro correctamente');
    }

    public function destroy($id)
    {
        $orden    = Orden::findOrFail($id);
        $user     = auth()->user();
        $pacOwner = Pac::find($orden->id_proyecto);

        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para eliminar esta orden.');
        }

        $descripcion = 'Eliminacion de Orden #' . $orden->numero . ' - PAC #' . $orden->id_proyecto;
        $proyectoId  = $orden->id_proyecto;
        $orden->delete();

        return redirect()->route('ordenes.index')->with('mensaje', 'Se elimino el registro correctamente');
    }
}