@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="margin-left: 33px">Nuevo usuario</h2>
    </div>
    <div class="row">
        <div class="col-md-8" style="margin-left: 30px">
            <div class="card card-outline card-primary">
                
                <div class="card-body">
                    <form action="{{route('funcionarios.store')}}" method="POST">
                    @csrf
                    <div class="col-md-12">    
                            <div class="row">                            
                                <div class="col-md-4">
                                    <div class="form-group">                                      
                                        <label for="">Rut</label>
                                        <input type="text" value="{{old('Rut')}}" name="Rut" class="form-control" maxlength="9"
                                         required oninput="this.value = this.value.toUpperCase()">
                                        @error('Rut')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                   
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Código</label>
                                        <input type="text" value="{{old('Codigo')}}" name="Codigo" class="form-control"
                                        required oninput="this.value = this.value.toUpperCase()">
                                        @error('Codigo')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        <label for="">Grado</label>
                                        <select name="Grado" id="grado" class="form-control @error('Grado') is-invalid @enderror" required> {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
                                            <option value="">-- Ingrese el grado correspondiente --</option>
                                            @foreach ($grados as $grado)
                                                <option value="{{ $grado->detalle }}" {{ old('Grado') == $grado->detalle ? 'selected' : '' }}>
                                                    {{ $grado->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                       
                                        @error('Grado')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Nombre del usuario</label>
                                        <input type="text" value="{{old('Nombres')}}" name="Nombres" class="form-control"
                                        required oninput="this.value = this.value.toUpperCase()">
                                        @error('Nombres')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Apellidos</label>
                                        <input type="text" value="{{old('Apellidos')}}" name="Apellidos" class="form-control"
                                        required oninput="this.value = this.value.toUpperCase()">
                                        @error('Apellidos')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Departamento</label>
                                    <select name="Dotacion" id="Dotacion" class="form-control"
                                        @error('Dotacion') is-invalid @enderror required> {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
                                        <option value="">-- Ingrese Departamento según dotación --</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento->detalle }}"
                                                {{ old('Dotacion') == $departamento->detalle ? 'selected' : '' }}>
                                                {{ $departamento->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="Email" value="{{old('Email')}}" name="Email" class="form-control"
                                               required oninput="this.value = this.value.toLowerCase()" onblur="this.value = this.value.toLowerCase()">
                                        @error('Email')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>                 
                           

                           
                    </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{url('funcionarios/')}}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Guardar registro</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
