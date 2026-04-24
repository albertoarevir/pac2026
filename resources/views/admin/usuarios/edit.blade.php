@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="margin-left: 30px">Modificar Usuario: {{$usuario->name}}</h2>
    </div>
    <div class="row">
        <div class="col-md-8" style="margin-left: 30px">
            <div class="card card-outline card-success">
                
                <div class="card-body">
                    <form action="{{url('admin/usuarios', $usuario->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="div-col 12">
                                    <div class="row">                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Rut</label>
                                                    <input type="text" value="{{$usuario->Rut}}" name="Rut" class="form-control" maxlength="9" required>
                                                    @error('Rut')
                                                    <small style="color: red">{{$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                    
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="">Nombre del usuario</label>
                                                    <input type="text" value="{{$usuario->name}}" name="name" class="form-control" required>
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
                                                            <input type="email" value="{{$usuario->email}}" name="email" class="form-control" required>
                                                            @error('email')
                                                            <small style="color: red">{{$message}}</small>
                                                            @enderror
                                                        </div>
                                                </div>                        

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Departamento</label>
                                                        <select name="Dotacion" id="Dotacion" class="form-control @error('Dotacion') is-invalid @enderror" required>
                                                            <option value="">-- Ingrese Departamento --</option>
                                                            @foreach ($departamentos as $departamento)
                                                                <option value="{{ $departamento->detalle }}" {{ $usuario->Dotacion == $departamento->detalle ? 'selected' : '' }}>
                                                                    {{ $departamento->detalle }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                    </div>

                                    <div class="row">
                                                <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label for="">Password</label>
                                                                <input type="password" name="password" class="form-control">
                                                                @error('password')
                                                                <small style="color: red">{{$message}}</small>
                                                                @enderror
                                                        </div>
                                                </div>
                                          

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Repetir Password</label>
                                                <input type="password" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                         
                                </div>    
                 


                                        <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-success"><i class="bi bi-floppy2"></i> Actualizar usuario</button>
                                        </div>
                                    </div>
                        </div>            
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
