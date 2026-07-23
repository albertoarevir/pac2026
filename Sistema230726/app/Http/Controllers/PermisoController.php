<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $permisos= Permission::all();
        return view('permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('permisos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nombrePermiso = $request->input('Permiso');
        $permission = Permission::where('name', $nombrePermiso)->first();
    
        if ($permission) {
            return redirect()->route('permisos.index')
                ->with('mensaje', 'El permiso ya existe')
                ->with('icono', 'error');
        } else {
            Permission::create(['name' => $nombrePermiso]);
            return redirect()->route('permisos.index')
                ->with('mensaje', 'Se registro al Permiso de la manera correcta')
                ->with('icono', 'success');
        }
    }
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
        $permisos = Permission::findOrFail($id);
        return view('/permisos.edit', compact('permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $permisos = Permission::find($id);
    
        $request->validate([
            'name' => 'required|max:50|unique:permissions,name,'.$permisos->id,
            ]);
    
            $permisos->name = $request->name;  
           
            $permisos->save(); // Guardar en la base de datos
        
            return redirect()->route('permisos.index')
                ->with('mensaje','Se actualizó el Permiso de la manera correcta')
                ->with('icono','success');     

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Permission::destroy($id);
        return redirect()->route('permisos.index')
            ->with('mensaje', 'Se eliminó el Permiso de la manera correcta')
            ->with('icono', 'success');
    }
}
