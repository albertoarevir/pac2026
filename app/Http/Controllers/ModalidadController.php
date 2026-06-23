<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Modalidad;
use App\Models\Pac;
use App\Models\Tipocompra;
use Illuminate\Http\Request;
use App\Models\EstadoLicitacion;
use Carbon\Carbon;

class ModalidadController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $dotacion = $user->departamento_id;

        if ($dotacion === 7) {
            $modalidades = Modalidad::with('ordenes', 'pac.departamento')->get()->sortByDesc('id');
        } else {
            $modalidades = Modalidad::whereHas('pac.departamento', function ($query) use ($dotacion) {
                $query->where('id', $dotacion);
            })->with('ordenes', 'pac.departamento')->orderBy('id', 'desc')->get();
        }

        $modalidades->transform(function ($modalidad) {
            $fechaActual      = Carbon::now();
            $fechaRegistro    = Carbon::parse($modalidad->updated_at);
            $diasTranscurridos = $fechaActual->diffInDays($fechaRegistro);

            if ($modalidad->estado_id == 1 && $diasTranscurridos >= 20) {
                $modalidad->auditoria = '<span style="color: red;"><strong>Han pasado ' . $diasTranscurridos . ' dias sin actualizar</strong></span>';
            } else {
                $modalidad->auditoria = '<span style="color: green;"><strong>Sin observacion</strong></span>';
            }
            return $modalidad;
        });

        $pac  = Pac::first();
        $pacs = Pac::all();

        return view('modalidad.index', [
            'modalidades' => $modalidades->all(),
            'pac'         => $pac,
            'pacs'        => $pacs,
        ]);
    }

    public function create($pac = null, $id_mod = null)
    {
        if ($pac) {
            $selectedPac = Pac::findOrFail($pac);
            $user = auth()->user();
            if ($user->departamento_id !== 7 && $selectedPac->departamento_id !== $user->departamento_id) {
                abort(403, 'No tienes permiso para crear licitaciones en este PAC.');
            }
        } else {
            $selectedPac = null;
        }

        $modalidades = Modalidad::all();
        $modalidad   = Modalidad::find($id_mod);
        $tipocompras = Tipocompra::all();
        $estados     = EstadoLicitacion::all();

        return view('modalidad.create', compact('tipocompras', 'estados', 'modalidades', 'selectedPac', 'modalidad', 'id_mod'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        $request->validate([
            'modalidad'   => 'required',
            'numero'      => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'   => 'required|exists:estado_licitacions,id',
            'observacion' => 'nullable',
            'id_proyecto' => 'nullable|exists:pacs,id',
        ], [
            'numero.regex' => 'El numero identificador solo permite letras, numeros y guiones (-).',
            'numero.max'   => 'El numero identificador no puede superar los 15 caracteres.',
        ]);

        $modalidad              = new Modalidad();
        $modalidad->modalidad   = $request->modalidad;
        $modalidad->numero      = $request->numero;
        $modalidad->estado_id   = $request->estado_id;
        $modalidad->observacion = $request->observacion;

        if ($request->filled('id_proyecto')) {
            $modalidad->id_proyecto = $request->id_proyecto;
        }

        $modalidad->save();

        return redirect()->route('modalidad.index')->with('success', 'Modalidad guardada correctamente.');
    }

    public function show(Modalidad $modalidad) {}

    public function edit($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $user      = auth()->user();
        $pacOwner  = \App\Models\Pac::find($modalidad->id_proyecto);

        if ($user->departamento_id !== 7 && $pacOwner && $pacOwner->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para editar esta licitacion.');
        }

        $pac         = Pac::all();
        $tipocompras = Tipocompra::all();
        $estados     = EstadoLicitacion::all();

        return view('modalidad.edit', compact('modalidad', 'tipocompras', 'estados', 'pac'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        $request->validate([
            'modalidad'   => 'required',
            'numero'      => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'   => 'required|exists:estado_licitacions,id',
            'observacion' => 'nullable|string|max:1000',
            'id_proyecto' => 'required|exists:pacs,id',
        ], [
            'numero.regex' => 'El numero identificador solo permite letras, numeros y guiones (-).',
            'numero.max'   => 'El numero identificador no puede superar los 15 caracteres.',
        ]);

        $modalidad = Modalidad::findOrFail($id);
        $user      = auth()->user();
        $pac       = \App\Models\Pac::find($modalidad->id_proyecto);

        if ($user->departamento_id !== 7 && $pac && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para modificar esta modalidad.');
        }

        $anterior = 'Numero: ' . $modalidad->numero . ' | Estado: ' . $modalidad->estado_id;

        $modalidad->modalidad   = $request->modalidad;
        $modalidad->numero      = $request->numero;
        $modalidad->estado_id   = $request->estado_id;
        $modalidad->observacion = $request->observacion;
        $modalidad->id_proyecto = $request->id_proyecto;
        $modalidad->save();

        return redirect()->route('modalidad.index')->with('mensaje', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $user      = auth()->user();
        $pac       = \App\Models\Pac::find($modalidad->id_proyecto);

        if ($user->departamento_id !== 7 && $pac && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para eliminar esta modalidad.');
        }

        $descripcion = 'Eliminacion de Licitacion #' . $modalidad->numero . ' - PAC #' . $modalidad->id_proyecto;
        $proyectoId  = $modalidad->id_proyecto;
        $modalidad->delete();

        return redirect()->route('modalidad.index')->with('mensaje', 'Se elimino el registro correctamente.');
    }
}