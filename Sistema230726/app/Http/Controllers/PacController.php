<?php

namespace App\Http\Controllers;

use App\Models\Clasificador;
use App\Models\Codigo;
use App\Models\Pac;
use App\Models\Orden;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Especie;
use App\Models\Estado;
use App\Models\Modalidad;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\PacRechazadoEvent;
use App\Models\EstadoModificacion;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\FuenteFinanciamiento;

class PacController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $dotacion = $user->departamento_id;

        $query = Pac::with(['departamento', 'especie', 'estado', 'modalidades.ordenes', 'infoPresupuesto']);

        if ($dotacion !== 7)                     { $query->where('departamento_id', $dotacion); }
        if ($request->filled('year'))            { $query->where('year', $request->year); }
        if ($request->filled('departamento_id')) { $query->where('departamento_id', $request->departamento_id); }
        if ($request->filled('clasificador')) {
            $val = str_replace(['\\\\', '%', '_'], ['\\\\\\\\', '\\\\%', '\\\\_'], $request->clasificador);
            $query->where('clasificador', 'LIKE', '%' . $val . '%');
        }
        if ($request->filled('codigo')) {
            $val = str_replace(['\\\\', '%', '_'], ['\\\\\\\\', '\\\\%', '\\\\_'], $request->codigo);
            $query->where('codigo', 'LIKE', '%' . $val . '%');
        }
        if ($request->filled('especie_id'))      { $query->where('especie_id', $request->especie_id); }

        $pacs                 = $query->orderBy('id', 'desc')->get();
        $especies             = \App\Models\Especie::all();
        $modalidades          = \App\Models\Modalidad::all();
        $estados              = \App\Models\Estado::all();
        $departamentos = $user->departamento_id === 7
            ? \App\Models\Departamento::all()
            : \App\Models\Departamento::where('id', $user->departamento_id)->get();
        $estados_modificacion = \App\Models\EstadoModificacion::all();
        $fuentes              = \App\Models\FuenteFinanciamiento::all();
        $clasificador         = \App\Models\Codigo::with('clasificador')->get();

        $pacs->transform(function ($pac) {
            $dias = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($pac->updated_at));
            $pac->auditoria = $dias >= 20
                ? '<span style="color: red;"><strong>Han pasado ' . $dias . ' dias sin actualizar</strong></span>'
                : '<span style="color: green;"><strong>Sin observacion</strong></span>';

            $presupuestoSap = \App\Models\Presupuesto::where('item', trim($pac->codigo))
                ->where('departamento_id', $pac->departamento_id)->first();
            $pac->infoPresupuesto         = $presupuestoSap;
            $montoSap                     = $presupuestoSap ? $presupuestoSap->monto : 0;
            $pac->total_comprometido_item = Orden::whereIn('id_proyecto', function ($q) use ($pac) {
                $q->select('id')->from('pacs')
                    ->where('codigo', $pac->codigo)
                    ->where('departamento_id', $pac->departamento_id);
            })->sum('monto');
            $pac->saldo_disponible = $montoSap - $pac->total_comprometido_item;
            return $pac;
        });

        return view('pac.index', compact(
            'pacs', 'modalidades', 'estados', 'departamentos',
            'especies', 'estados_modificacion', 'fuentes', 'clasificador'
        ));
    }

    public function create()
    {
        $user = auth()->user();

        $departamentos = $user->departamento_id === 7
            ? Departamento::all()
            : Departamento::where('id', $user->departamento_id)->get();

        $especies             = Especie::all();
        $codigos              = Codigo::all();
        $clasificadors        = Clasificador::all();
        $estados              = Estado::all();
        $estados_modificacion = EstadoModificacion::all();
        $fuentes              = FuenteFinanciamiento::all();
        $pac                  = Pac::all();

        return view('/pac.create', compact('departamentos', 'especies', 'codigos', 'clasificadors', 'estados', 'estados_modificacion', 'fuentes', 'pac'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'presupuesto' => str_replace('.', '', $request->presupuesto),
        ]);

        $request->validate([
            'year'                  => 'required|integer|min:2000|max:2100',
            'departamento'          => 'required|exists:departamentos,id',
            'especie'               => 'required|exists:especies,id',
            'cantidad'              => ['required', 'numeric'],
            'presupuesto'           => ['required', 'numeric'],
            'clasificador'          => 'required',
            'codigo_id'             => 'required|exists:codigos,codigopre',
            'estado_id'             => 'required|exists:estados,id',
            'observacion'           => 'nullable|string|max:1000',
            'estado_modificacion'   => 'required|exists:estados_modificacion,id',
            'fuente_financiamiento' => 'required|exists:fuente_financiamiento,id',
        ]);

        $user = auth()->user();
        if ($user->departamento_id !== 7 && (int) $request->departamento !== $user->departamento_id) {
            abort(403, 'No tienes permiso para crear registros en otro departamento.');
        }

        $pac                        = new Pac();
        $pac->year                  = $request->year;
        $pac->departamento_id       = $request->departamento;
        $pac->especie_id            = $request->especie;
        $pac->cantidad              = str_replace(['.', ','], ['', '.'], $request->cantidad);
        $pac->presupuesto           = str_replace(['.', ','], ['', '.'], $request->presupuesto);
        $pac->clasificador          = $request->clasificador;
        $pac->codigo                = $request->codigo_id;
        $pac->estado_id             = $request->estado_id;
        $pac->observaciones         = $request->observacion;
        $pac->estado_modificacion   = $request->estado_modificacion;
        $pac->fuente_financiamiento = $request->fuente_financiamiento;

        if ($request->filled('id_proyecto')) {
            $pac->id_proyecto = $request->id_proyecto;
        }

        $pac->save(); // El PacObserver registra automaticamente en bitacora

        return redirect()->route('pac.index')->with('success', 'Datos guardados correctamente');
    }

    public function edit($id)
    {
        $pac  = Pac::findOrFail($id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para editar este registro.');
        }

        $departamentos        = $user->departamento_id === 7
            ? Departamento::all()
            : Departamento::where('id', $user->departamento_id)->get();
        $especies             = Especie::where('departamento_id', $pac->departamento_id)->get();
        $codigos              = Codigo::all();
        $clasificadors        = Clasificador::all();
        $estados              = Estado::all();
        $estados_modificacion = EstadoModificacion::all();
        $fuentes              = FuenteFinanciamiento::all();

        return view('/pac.edit', compact('pac', 'departamentos', 'especies', 'codigos', 'clasificadors', 'estados', 'estados_modificacion', 'fuentes'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'presupuesto' => str_replace('.', '', $request->presupuesto),
        ]);

        $request->validate([
            'year'                  => 'required',
            'departamento_id'       => 'required',
            'especie_id'            => 'required',
            'cantidad'              => ['required', 'numeric'],
            'presupuesto'           => ['required', 'numeric'],
            'clasificador'          => 'required',
            'codigo_id'             => 'required',
            'estado_id'             => 'required|exists:estados,id',
            'observaciones'         => 'nullable',
            'estado_modificacion'   => 'required',
            'fuente_financiamiento' => 'required',
        ]);

        $pac  = Pac::findOrFail($id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para modificar este registro.');
        }

        $pac->year                  = $request->year;
        $pac->departamento_id       = $request->departamento_id;
        $pac->especie_id            = $request->especie_id;
        $pac->cantidad              = str_replace(['.', ','], ['', '.'], $request->cantidad);
        $pac->presupuesto           = str_replace(['.', ','], ['', '.'], $request->presupuesto);
        $pac->clasificador          = $request->clasificador;
        $pac->codigo                = $request->codigo_id;
        $pac->estado_id             = $request->estado_id;
        $pac->observaciones         = $request->observaciones;
        $pac->estado_modificacion   = $request->estado_modificacion;
        $pac->fuente_financiamiento = $request->fuente_financiamiento;
        $pac->save(); // El PacObserver registra automaticamente en bitacora

        if ($request->estado_id == 2) {
            event(new PacRechazadoEvent($pac));
        }

        return redirect()->route('pac.index')->with('success', 'Registro actualizado correctamente');
    }

    public function listarRegistros()
    {
        return DB::table('pacs')->get()->transform(function ($a) {
            $dias = Carbon::now()->diffInDays(Carbon::parse($a->updated_at));
            $a->auditoria = $dias >= 10 ? 'No se han realizado cambios al registro' : '';
            return $a;
        });
    }

    public function getCodigos(Request $request)
    {
        return response()->json(Codigo::where('codigo_id', $request->clasificador)->get());
    }

    public function getEspecies(Request $request)
    {
        return response()->json(Especie::where('departamento_id', $request->departamento)->get());
    }

    public function destroy($id)
    {
        $pac  = Pac::findOrFail($id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para eliminar este registro.');
        }

        $pac->delete(); // El PacObserver registra automaticamente en bitacora

        return redirect()->route('pac.index')->with('mensaje', 'Se elimino el registro del control de Departamento');
    }
}
