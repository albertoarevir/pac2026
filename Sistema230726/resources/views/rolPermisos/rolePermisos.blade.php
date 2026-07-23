@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="margin-left: 33px">Asignación de Permisos</h2>
        <div class="row">
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8" style="margin-left: 30px">
            <div class="card card-outline card-primary">
                <div class="card-header">
                   <h4> <strong>Rol:</strong></h4><h4 style="color: red"> {{$role->name}}</h4>
                </div>
                <div class="card-body">
                <h4><strong>Permisos asociados a este Rol</strong></h4>
                <br>
                
                 <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @foreach ($permisos as $permiso)
                        <div>
                            <label>
                                <input type="checkbox" name="permisos[]" value="{{ $permiso->id }}" class="mr-1" {{ $role->hasPermissionTo($permiso->id) ? 'checked' : '' }}>
                                {{$permiso->name}}
                            </label>

                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Asignar permisos</button>
                    <a href="{{ url('roles/') }}" class="btn btn-success mt-3">Volver al listado</a>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
