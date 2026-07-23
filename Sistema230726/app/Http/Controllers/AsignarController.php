<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AsignarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        if ($authUser->departamento_id !== 7 && $user->departamento_id !== $authUser->departamento_id) {
            abort(403, 'No tienes permiso para gestionar usuarios de otro departamento.');
        }

        $permiso = Permission::all();
        $roles = Role::all();
        return view('asignarRol/asignarRol', compact('user', 'roles', 'permiso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->hasRole('ADMINISTRADOR')) {
            abort(403, 'Solo el administrador puede asignar roles.');
        }

        $authUser = auth()->user();
        $user = User::findOrFail($id);

        if ($authUser->departamento_id !== 7 && $user->departamento_id !== $authUser->departamento_id) {
            abort(403, 'No tienes permiso para gestionar usuarios de otro departamento.');
        }

        $request->validate([
            'roles'    => 'required|array',
            'roles.*'  => 'integer|exists:roles,id',
            'permisos' => 'nullable|json',
        ]);

        $user->roles()->sync($request->input('roles'));

        return redirect()->route('admin.usuarios.index', $user);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
