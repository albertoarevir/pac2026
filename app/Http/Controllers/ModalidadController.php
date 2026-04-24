<?php

namespace App\Http\Controllers;

use App\Models\Modalidad;
use App\Models\Pac;
use App\Models\Tipocompra;
use Illuminate\Http\Request;
use App\Models\EstadoLicitacion;
use Carbon\Carbon;

class ModalidadController extends Controller
{
    /**
     * Muestra el listado de modalidades con lógica de auditoría.
     */
    public function index()
    {
        $user = auth()->user();
        $dotacion = $user->departamento_id;

        if ($dotacion === 7) {
            $modalidades = Modalidad::with('ordenes', 'pac.departamento')->get()->sortByDesc('id');
        } else {
            $modalidades = Modalidad::whereHas('pac.departamento', function ($query) use ($dotacion) {
                $query->where('id', $dotacion);
            })->with('ordenes', 'pac.departamento')->orderBy('id', 'desc')->get();
        }

        $modalidades->transform(function ($modalidad) {
            $fechaActual = Carbon::now();
            $fechaRegistro = Carbon::parse($modalidad->updated_at);
            $diasTranscurridos = $fechaActual->diffInDays($fechaRegistro);

            if ($modalidad->estado_id == 1 && $diasTranscurridos >= 20) {
                $modalidad->auditoria = '<span style="color: red;"><strong>Han pasado ' . $diasTranscurridos . ' días sin actualizar</strong></span>';
            } else {
                $modalidad->auditoria = '<span style="color: green;"><strong>Sin observación</strong></span>';
            }
            return $modalidad;
        });

        $pac = Pac::first();
        $pacs = Pac::all();
        
        return view('modalidad.index', [
            'modalidades' => $modalidades->all(), 
            'pac' => $pac, 
            'pacs' => $pacs
        ]);
    }

    /**
     * Formulario de creación.
     */
    public function create($pac = null, $id_mod = null)
    {
        $modalidades = Modalidad::all();
        $modalidad = Modalidad::find($id_mod);
        $tipocompras = Tipocompra::all();
        $estados = EstadoLicitacion::all();

        $selectedPac = $pac ? Pac::find($pac) : null;

        return view('modalidad.create', compact('tipocompras', 'estados', 'modalidades', 'selectedPac', 'modalidad', 'id_mod'));
    }

    /**
     * Guarda una nueva modalidad con validación personalizada.
     */
    public function store(Request $request)
    {
        // 1. Limpieza: Convertir / a - y pasar a Mayúsculas antes de validar
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        // 2. Validación con mensajes personalizados
        $request->validate([
            'modalidad'   => 'required',
            'numero'      => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'   => 'required|exists:estado_licitacions,id',
            'observacion' => 'nullable',
            'id_proyecto' => 'nullable|exists:pacs,id'
        ], [
            'numero.regex' => 'El número identificador solo permite letras, números y guiones (-).',
            'numero.max'   => 'El número identificador no puede superar los 15 caracteres.'
        ]);

        $modalidad = new Modalidad();
        $modalidad->modalidad = $request->modalidad;
        $modalidad->numero = $request->numero;
        $modalidad->estado_id = $request->estado_id;
        $modalidad->observacion = $request->observacion;
        
        if ($request->filled('id_proyecto')) {
            $modalidad->id_proyecto = $request->id_proyecto;
        }
        
        $modalidad->save();

        return redirect()->route('modalidad.index')->with('success', 'Modalidad guardada correctamente.');
    }

    public function show(Modalidad $modalidad) { /* ... */ }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $pac = Pac::all();
        $tipocompras = Tipocompra::all();
        $estados = EstadoLicitacion::all();
        return view('modalidad.edit', compact('modalidad', 'tipocompras', 'estados', 'pac'));
    }

    /**
     * Actualiza la modalidad con validación personalizada.
     */
    public function update(Request $request, $id)
    {
        // 1. Limpieza de datos
        $request->merge([
            'numero' => strtoupper(str_replace('/', '-', $request->numero))
        ]);

        // 2. Validación con mensajes personalizados
        $request->validate([
            'modalidad'   => 'required',
            'numero'      => 'required|string|max:15|regex:/^[A-Z0-9-]+$/',
            'estado_id'   => 'required|exists:estado_licitacions,id',
            'observacion' => 'nullable',
            'id_proyecto' => 'required',
        ], [
            'numero.regex' => 'El número identificador solo permite letras, números y guiones (-).',
            'numero.max'   => 'El número identificador no puede superar los 15 caracteres.'
        ]);

        $modalidad = Modalidad::findOrFail($id);
        $modalidad->modalidad = $request->modalidad;
        $modalidad->numero = $request->numero;
        $modalidad->estado_id = $request->estado_id;
        $modalidad->observacion = $request->observacion;
        $modalidad->id_proyecto = $request->id_proyecto;
        $modalidad->save();

        return redirect()->route('modalidad.index')->with('mensaje', 'Registro actualizado correctamente.');
    }

    /**
     * Elimina el registro.
     */
    public function destroy($id)
    {
        Modalidad::destroy($id);
        return redirect()->route('modalidad.index')->with('mensaje', 'Se eliminó el registro correctamente.');
    }
}