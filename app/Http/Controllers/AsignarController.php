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
        //
        $user = User::find($id);
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
        $request->validate([
            'roles'    => 'required|array',
            'roles.*'  => 'integer|exists:roles,id',
            'permisos' => 'nullable|json',
        ]);

        $user = User::find($id);
        $user->roles()->sync($request->input('roles'));
        $role = Role::find($request->input('roles')[0]);
        $permisos = $request->filled('permisos') ? json_decode($request->input('permisos'), true) : [];
        if (is_array($permisos) && count($permisos) > 0) {
            $role->givePermissionTo($permisos);
        }
        $roles = Role::all();
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
