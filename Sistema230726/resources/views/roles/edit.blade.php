@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="margin-left: 30px">Modificar Rol: {{ $roles->name }}</h2>
    </div>
    <div class="row">
        <div class="col-md-8" style="margin-left: 30px">
            <div class="card card-outline card-success">

                <div class="card-body">
                    <form action="{{ url('/roles/'. $roles->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="div-col 12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Rol</label>
                                        <input type="text" value="{{ $roles->name}}" name="name"
                                            class="form-control" maxlength="50"
                                            required oninput="this.value = this.value.toUpperCase()">
                                        @error('name')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('roles') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-success"><i class="bi bi-floppy2"></i> Actualizar
                                    Rol</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
