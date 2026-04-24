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
                
                 {!! Form::model($role, ['route'=>['roles.update', $role], 'method'=>'PUT']) !!}

                    @foreach ($permisos as $permiso)
                        <div>
                            <label>
                                {!! Form::checkbox('permisos[]', $permiso->id, $role->hasPermissionTo($permiso->id) ? : false, ['class'=>'mr-1']) !!}
                                {{$permiso->name}}
                            </label>

                        </div>
                    @endforeach

                    {!! Form::submit('Asignar permisos', ['class'=>'btn btn-primary mt-3']) !!}
                    <a href="{{ url('roles/') }}" class="btn btn-success mt-3">Volver al listado</a>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
