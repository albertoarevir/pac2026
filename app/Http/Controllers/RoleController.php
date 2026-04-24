<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role as ContractsRole;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $nombreRol = $request->input('Rol');
    $role = Role::where('name', $nombreRol)->first();

    if ($role) {
        return redirect()->route('roles.index')
            ->with('mensaje', 'El rol ya existe')
            ->with('icono', 'error');
    } else {
        Role::create(['name' => $nombreRol]);
        return redirect()->route('roles.index')
            ->with('mensaje', 'Se registro al Rol de la manera correcta')
            ->with('icono', 'success');
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
       /* $Role = Role::find($id);
        $permisos = Permission::all();
        return view('rolPermisos/rolePermisos', compact('Role', 'permisos'));
        */
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
        //$Role = Role::find($id);
        $permisos = Permission::all();
        return view('rolPermisos/rolePermisos', compact('role', 'permisos'));
        
        //$roles = Role::findOrFail($id);
        //return view('/roles.edit', compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permisos);
        
        return redirect()->route('roles.index', $role);

      
    }

  
    public function destroy(string $id)
    {
        //
        Role::destroy($id);
        return redirect()->route('roles.index')
            ->with('mensaje', 'Se eliminó el Rol de la manera correcta')
            ->with('icono', 'success');
    }
}
