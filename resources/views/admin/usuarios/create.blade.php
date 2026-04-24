@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="margin-left: 33px">Nuevo usuario</h2>
    </div>
    <div class="row">
        <div class="col-md-6" style="margin-left: 30px">
            <div class="card card-outline card-primary">
                
                <div class="card-body">
                    <form action="{{url('/admin/usuarios/create')}}" method="post">
                        @csrf
                    <div class="col-md-12">    
                            <div class="row">                            
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Rut</label>
                                        <input type="text" value="{{old('Rut')}}" name="Rut" class="form-control" maxlength="9" required>
                                        @error('Rut')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Nombre del usuario</label>
                                        <input type="text" value="{{old('name')}}" name="name" class="form-control" required>
                                        @error('name')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="email" value="{{old('email')}}" name="email" class="form-control" required>
                                        @error('email')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Departamento</label>
                                        <select name="departamento_id" id="Dotacion" class="form-control @error('departamento_id') is-invalid @enderror required">
                                            <option value="">-- Seleccione Departamento --</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                                    {{ $departamento->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('departamento_id')
                                            <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label for="">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                        @error('password')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                         
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Repetir Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                            </div>
        
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Guardar registro</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
