@extends('layouts.admin')

@section('content')
<br>
<div class="row">
    <h2 style="margin-left: 33px"><strong>Antecedentes del Usuario:</strong></h2>
    <div class="row">
        <br>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="card card-outline card-primary" style="margin-left: 33px;">
            <div class="card-body badge-btn" >
                <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                    <strong>Rut Identificador:</strong>
                </h2>
                <h2 style="font-size: 16px; color:rgb(8, 8, 8); margin-bottom: 0;">
                    
                    {{$user->Rut}}
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-outline card-primary" style="margin-left: 0px;">
            <div class="card-body badge-btn" >
                <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                    <strong>Grado:</strong>
                </h2>
                <h2 style="font-size: 16px; color:rgb(8, 8, 8); margin-bottom: 0;">
                    {{$user->Grado}}
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-primary" style="margin-left: 0px;">
            <div class="card-body badge-btn" >
                <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                    <strong>Apellidos y Nombres:</strong>
                </h2>
                <h2 style="font-size: 16px; color:rgb(8, 8, 8); margin-bottom: 0;">
                    {{$user->Apellidos." ".$user->Nombres}}
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-outline card-primary" style="margin-left: 0px;">
            <div class="card-body badge-btn" >
                <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                    <strong>Dotación:</strong>
                </h2>
                <h2 style="font-size: 16px; color:rgb(8, 8, 8); margin-bottom: 0;">
                    {{$user->Dotacion}}
                </h2>
            </div>
        </div>
    </div>

</div>



<br>

<br>
    <div class="row">
        <h2 style="margin-left: 35px"><strong>Asignación de Rol:</strong></h2>
        <div class="row">
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10" style="margin-left: 30px">
            <div class="card card-outline card-primary">
                <div class="card-header">
                   
                </div>
                <div class="card-body">
                <h4>Seleccionar opción:</h4>
                <br>
                
                 <form action="{{ route('asignar.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @foreach ($roles as $role)
                        <div>
                            <label>
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mr-1" {{ $user->hasAnyRole($role->id) ? 'checked' : '' }}>
                                {{$role->name}}
                            </label>

                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Asignar Roles</button>
                         <a href="{{ url('admin/usuarios') }}" class="btn btn-success mt-3">Volver al listado</a>
                </form>

                  
                </div>
            </div>
        </div>
    </div>
@endsection
