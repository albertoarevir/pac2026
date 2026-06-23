<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        }

        Role::create(['name' => $nombreRol]);

        Bitacora::create([
            'user_id'     => auth()->id(),
            'modulo'      => 'Roles',
            'accion'      => 'CREAR',
            'descripcion' => 'Creacion de rol: ' . $nombreRol,
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()->route('roles.index')
            ->with('mensaje', 'Se registro al Rol de la manera correcta')
            ->with('icono', 'success');
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
        $permisosIds = $request->input('permisos', []);
        $permisos = Permission::whereIn('id', $permisosIds)->get();
        $role->syncPermissions($permisos);

        Bitacora::create([
            'user_id'     => auth()->id(),
            'modulo'      => 'Roles',
            'accion'      => 'MODIFICAR',
            'descripcion' => 'Modificacion de permisos del rol: ' . $role->name,
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()->route('roles.index');
    }


    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        Bitacora::create([
            'user_id'     => auth()->id(),
            'modulo'      => 'Roles',
            'accion'      => 'ELIMINAR',
            'descripcion' => 'Eliminacion de rol: ' . $role->name,
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $role->delete();

        return redirect()->route('roles.index')
            ->with('mensaje', 'Se eliminó el Rol de la manera correcta')
            ->with('icono', 'success');
    }
}
