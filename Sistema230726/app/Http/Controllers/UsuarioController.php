<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    private function verificarDepartamento(User $usuario): void
    {
        $authUser = auth()->user();
        if ($authUser->departamento_id !== 7 && $usuario->departamento_id !== $authUser->departamento_id) {
            abort(403, 'No tienes permiso para gestionar usuarios de otro departamento.');
        }
    }

    public function index(){
        $authUser = auth()->user();
        $usuarios = $authUser->departamento_id === 7
            ? User::all()
            : User::where('departamento_id', $authUser->departamento_id)->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create(){
        $departamentos = Departamento::all();
        return view('admin.usuarios.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Rut'            => ['required', 'max:12', Rule::unique('users', 'Rut')->whereNull('deleted_at')],
            'name'           => 'required|max:100',
            'email'          => ['required', 'email', 'max:200', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'departamento_id'=> 'required|exists:departamentos,id',
        ]);

        $usuario = new User();
        $usuario->Rut            = $request->input('Rut');
        $usuario->name           = $request->input('name');
        $usuario->email          = $request->input('email');
        $usuario->departamento_id = $request->input('departamento_id');
        $usuario->password       = \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32));
        $usuario->save();

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Se registró al usuario de la manera correcta')
            ->with('icono', 'success');
    }

    public function show($id){
        $usuario = User::findOrFail($id);
        $this->verificarDepartamento($usuario);
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit($id){
        $usuario = User::findOrFail($id);
        $this->verificarDepartamento($usuario);
        $departamentos = Departamento::all();
        return view('admin.usuarios.edit', compact('usuario', 'departamentos'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'Rut'            => ['required', 'max:12', Rule::unique('users', 'Rut')->ignore($usuario->id)->whereNull('deleted_at')],
            'name'           => 'required|max:100',
            'email'          => ['required', 'email', 'max:200', Rule::unique('users', 'email')->ignore($usuario->id)->whereNull('deleted_at')],
            'departamento_id'=> 'required|exists:departamentos,id',
            'password'       => ['nullable', 'confirmed', 'min:10', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'],
        ]);

        $usuario->Rut            = $request->Rut;
        $usuario->name           = $request->name;
        $usuario->email          = $request->email;
        $usuario->departamento_id = $request->departamento_id;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Se actualizó el usuario de la manera correcta')
            ->with('icono', 'success');
    }

    public function toggleHabilitado(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $this->verificarDepartamento($usuario);

        $request->validate([
            'habilitado' => 'required|boolean',
        ]);

        $usuario->habilitado = $request->boolean('habilitado');
        $usuario->save();

        Bitacora::create([
            'user_id'     => auth()->id(),
            'modulo'      => 'Usuarios',
            'accion'      => $usuario->habilitado ? 'HABILITAR' : 'INHABILITAR',
            'descripcion' => ($usuario->habilitado ? 'Habilitacion' : 'Inhabilitacion') . ' de usuario: ' . $usuario->name . ' (RUT: ' . $usuario->Rut . ')',
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Se actualizo el estado del usuario correctamente')
            ->with('icono', 'success');
    }

    public function confirmDelete($id){
        $usuario = User::findOrFail($id);
        $this->verificarDepartamento($usuario);
        return view('admin.usuarios.delete', compact('usuario'));
    }

    public function destroy($id){
        $usuario = User::findOrFail($id);
        $this->verificarDepartamento($usuario);

        Bitacora::create([
            'user_id'     => auth()->id(),
            'modulo'      => 'Usuarios',
            'accion'      => 'ELIMINAR',
            'descripcion' => 'Eliminacion de usuario: ' . $usuario->name . ' (RUT: ' . $usuario->Rut . ')',
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Se eliminó el usuario de la manera correcta')
            ->with('icono', 'success');
    }
}
