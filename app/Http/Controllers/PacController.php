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
use App\Models\UnidadCompra;
use Illuminate\Validation\Rules\Numeric;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\PacRechazadoEvent;
use Spatie\Permission\Models\Permission;
use App\Models\EstadoModificacion;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; 
use App\Models\FuenteFinanciamiento;

class PacController extends Controller
{  

   public function index(Request $request)
        {
            $user = auth()->user();
            $dotacion = $user->departamento_id;

            // 1. Iniciamos la consulta base con relaciones
            
            $query = Pac::with(['departamento', 'especie', 'estado', 'modalidades.ordenes', 'infoPresupuesto']);

            // 2. Filtro de seguridad por Departamento (Tu lógica original)
            if ($dotacion !== 7) {
                $query->where('departamento_id', $dotacion);
            }

            // 3. Aplicar Filtros del Buscador (Tipo Bitácora)
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }
            if ($request->filled('departamento_id')) {
                $query->where('departamento_id', $request->departamento_id);
            }
            if ($request->filled('clasificador')) {
                $query->where('clasificador', 'LIKE', '%' . $request->clasificador . '%');
            }
            if ($request->filled('codigo')) {
                $query->where('codigo', 'LIKE', '%' . $request->codigo . '%');
            }
            if ($request->filled('especie_id')) {
                $query->where('especie_id', $request->especie_id);
            }

            // 4. Obtener los resultados
            $pacs = $query->orderBy('id', 'desc')->get();

            // 5. Transformación para Auditoría y Montos (Tu lógica original corregida)
            $pacs->transform(function ($pac)
            {
                $fechaActual = \Carbon\Carbon::now();
                $fechaRegistro = \Carbon\Carbon::parse($pac->updated_at);
                $diasTranscurridos = $fechaActual->diffInDays($fechaRegistro);

                // Columna de auditoría
                if ($diasTranscurridos >= 20) {
                    $pac->auditoria = '<span style="color: red;"><strong>Han pasado ' . $diasTranscurridos . ' días sin actualizar</strong></span>';
                } else {
                    $pac->auditoria = '<span style="color: green;"><strong>Sin observación</strong></span>';
                }

            
                    // 1. Buscamos el presupuesto que coincida con el ITEM y el DEPARTAMENTO de la fila
                    $presupuestoSap = \App\Models\Presupuesto::where('item', trim($pac->codigo))
                    ->where('departamento_id', $pac->departamento_id)
                    ->first();

                    // 2. Asignamos el objeto encontrado a una propiedad temporal para la vista
                    $pac->infoPresupuesto = $presupuestoSap;
                    $montoSap = $presupuestoSap ? $presupuestoSap->monto : 0;

                    // 3. Cálculo del Total Comprometido (Filtrado por Ítem y Departamento)
                    $pac->total_comprometido_item = Orden::whereIn('id_proyecto', function($query) use ($pac) {
                        $query->select('id')
                            ->from('pacs')
                            ->where('codigo', $pac->codigo)
                            ->where('departamento_id', $pac->departamento_id);
                    })->sum('monto');

                    // 4. Saldo Disponible
                    $pac->saldo_disponible = $montoSap - $pac->total_comprometido_item;          
                    
                    return $pac;
                      });

                    // 6. Carga de datos para los Selects del buscador
                    $especies = \App\Models\Especie::all();
                    $modalidades = \App\Models\Modalidad::all();
                    $estados = \App\Models\Estado::all();
                    $departamentos = \App\Models\Departamento::all();
                    $estados_modificacion = \App\Models\EstadoModificacion::all();
                    $fuentes = \App\Models\FuenteFinanciamiento::all();
                    $clasificador = \App\Models\Codigo::with('clasificador')->get();

                    return view('pac.index', compact(
                        'pacs', 'modalidades', 'estados', 'departamentos', 
                        'especies', 'estados_modificacion', 'fuentes', 'clasificador'
                    ));
          
        }  
        
 
   
    public function create ()
    {
        $departamentos = Departamento::all(); // Obtiene todos los grados de la base de datos
        $especies = Especie::all();
        $codigos = Codigo::all();
        //$unidadcompras = UnidadCompra::all();
        $clasificadors = Clasificador::all();
        $estados = Estado::all();
        //$modalidades = Modalidad::all();
        $estados_modificacion = EstadoModificacion::all();
        $fuentes = FuenteFinanciamiento::all();
          $pac = Pac::all();
          
        return view('/pac.create', compact('departamentos', 'especies', 'codigos', 'clasificadors', 'estados', 'estados_modificacion', 'fuentes', 'pac'));
    }
  
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'departamento' => 'required',
            'especie' => 'required',
            'cantidad' => ['required', 'numeric'],
            'presupuesto' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                if (!is_numeric($value)) {
                    $fail('El campo presupuesto debe ser un número.');
                }
            }],
            'clasificador' => 'required',
            'codigo_id' => 'required',
            'estado_id' => 'required|exists:estados,id', // Validar que el estado existe
            'observacion' => 'nullable|string',
            'estado_modificacion' => 'required', // <-- Agrega la validación
            'fuente_financiamiento' => 'required|exists:fuente_financiamiento,id', // Validar que la fuente de financiamiento existe

        ]);

        $cantidad = str_replace('.', '', $request->cantidad);
        $cantidad = str_replace(',', '.', $cantidad);
        $presupuesto = str_replace('.', '', $request->presupuesto);
        $presupuesto = str_replace(',', '.', $presupuesto);

        $pac = new Pac();
        $pac->year = $request->year;
        $pac->departamento_id = $request->departamento;
        $pac->especie_id = $request->especie;
        $pac->cantidad = $request->cantidad;
        $pac->presupuesto = $presupuesto; // Usa la variable $presupuesto en lugar de $request->presupuesto
        $pac->clasificador = $request->clasificador;
        $pac->codigo = $request->codigo_id;
        // $pac->unidadcompra = $request->unidadcompra;
        $pac->estado_id = $request->estado_id;
        if ($request->has('id_proyecto') && $request->id_proyecto) {
            $pac->id_proyecto = $request->id_proyecto;
        }
        $pac->observaciones = $request->observacion;
        $pac->estado_modificacion = $request->estado_modificacion; // <-- Guarda el estado de modificación

        $pac->fuente_financiamiento = $request->fuente_financiamiento;



        $pac->save();
        return redirect()->route('pac.index')->with('success', 'Datos guardados correctamente');
    }

    public function edit($id)
    {
        $pac = Pac::findOrFail($id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para editar este registro.');
        }

        // Obtener los datos necesarios para los selectores
        $departamentos = Departamento::all();
        $especies = Especie::where('departamento_id', $pac->departamento_id)->get();
        //$especies = Especie::all();
        $codigos = Codigo::all();
        // $unidadcompras = UnidadCompra::all();
        $clasificadors = Clasificador::all();
        $estados = Estado::all();
        $estados_modificacion = EstadoModificacion::all(); // <-- Agrega esta línea
        $fuentes = FuenteFinanciamiento::all();

        // Pasar los datos a la vista de edición
        return view('/pac.edit', compact('pac', 'departamentos', 'especies', 'codigos', 'clasificadors', 'estados', 'estados_modificacion', 'fuentes'));
    }

    public function update(Request $request, $id)
    {

        // Validar los datos del formulario
        $request->validate([
            'year' => 'required',
            'departamento_id' => 'required',
            'especie_id' => 'required',
            'cantidad' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                if (!is_numeric($value)) {
                    $fail('El campo cantidad debe ser un número.');
                }
            }],
            'presupuesto' => ['required', 'numeric', function ($attribute, $value, $fail) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                if (!is_numeric($value)) {
                    $fail('El campo presupuesto debe ser un número.');
                }
            }],
            'clasificador' => 'required',
            'codigo_id' => 'required',
            //  'unidadcompra' => 'required',
            //'estado' => 'required',
            'estado_id' => 'required|exists:estados,id',
            'observaciones' => 'nullable',
            'estado_modificacion' => 'required', // <-- Agrega la validación
            'fuente_financiamiento' => 'required',

        ]);

        // Formatear los valores de cantidad y presupuesto
        $cantidad = str_replace('.', '', $request->cantidad);
        $cantidad = str_replace(',', '.', $cantidad);
        $presupuesto = str_replace('.', '', $request->presupuesto);
        $presupuesto = str_replace(',', '.', $presupuesto);

        $pac = Pac::findOrFail($id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para modificar este registro.');
        }

        // Actualizar los campos del registro
        $pac->year = $request->year;
        $pac->departamento_id = $request->departamento_id;
        $pac->especie_id = $request->especie_id;
        $pac->cantidad = $cantidad;
        $pac->presupuesto = $presupuesto;
        $pac->clasificador = $request->clasificador;
        $pac->codigo = $request->codigo_id;
        //  $pac->unidadcompra = $request->unidadcompra;
        //$pac->estado = $request->estado;
        $pac->estado_id = $request->estado_id;
        $pac->observaciones = $request->observaciones;
        $pac->estado_modificacion = $request->estado_modificacion; // <-- Asigna el valor del campo
        $pac->fuente_financiamiento = $request->fuente_financiamiento; // Asigna la fuente de financiamiento


        // Guardar los cambios en la base de datos
        $pac->save();

        // Disparar el evento PacRechazadoEvent si el estado es Rechazado
        if ($request->estado_id == 2) {
            event(new PacRechazadoEvent($pac));
        }

        // Redirigir al listado con un mensaje de éxito
        return redirect()->route('pac.index')->with('success', 'Registro actualizado correctamente');
    }

    public function listarRegistros()
    {
        // Obtener todos los registros de la tabla
        $auditorias = DB::table('pacs')->get();

        // Recorrer los registros y agregar la columna de auditoría
        $auditorias->transform(function ($auditoria) {
            $fechaActual = Carbon::now();
            $fechaRegistro = Carbon::parse($auditoria->updated_at);

            // Calcular la diferencia en días
            $diasTranscurridos = $fechaActual->diffInDays($fechaRegistro);

            // Agregar la columna de auditoría
            $auditoria->auditoria = ($diasTranscurridos >= 10)
                ? "No se han realizado cambios al registro"
                : "";

            return $auditoria;
        });

        return $auditorias;
    }
    public function getCodigos(Request $request)
    {
        $clasificadorId = $request->clasificador;
        $codigos = Codigo::where('codigo_id', $clasificadorId)->get();

        return response()->json($codigos);
    }

    public function getEspecies(Request $request)
    {
        $departamentoId = $request->departamento;
        $especies = Especie::where('departamento_id', $departamentoId)->get();

        return response()->json($especies);
    }

    public function destroy($id)
    {
        $pac = Pac::findOrFail($id);
        $user = auth()->user();
        if ($user->departamento_id !== 7 && $pac->departamento_id !== $user->departamento_id) {
            abort(403, 'No tienes permiso para eliminar este registro.');
        }
        $pac->delete();
        return redirect()->route('pac.index')->with('mensaje', 'Se eliminó el registro del control de Departamento');
    }

}


