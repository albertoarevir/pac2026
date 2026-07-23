<?php

namespace App\Http\Controllers;

use App\Models\EstadoModificacion; // Asegúrate de crear este modelo
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // <-- Agrega esta línea


class EstadoModificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = EstadoModificacion::all(); // Obtener todos los registros de la tabla
        return view('estados_modificacion.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Aquí va la lógica para mostrar el formulario de creación
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Aquí va la lógica para guardar un nuevo registro
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Aquí va la lógica para mostrar un registro específico
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Aquí va la lógica para mostrar el formulario de edición
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Aquí va la lógica para actualizar un registro
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Aquí va la lógica para eliminar un registro
    }
}