@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="margin-left: 33px">Nuevo Permiso</h2>
    </div>
    <div class="row">
        <div class="col-md-8" style="margin-left: 30px">
            <div class="card card-outline card-primary">
                
                <div class="card-body">
                    <form action="{{route('permisos.store')}}" method="POST">
                    @csrf
                        <div class="col-md-12">    
                                <div class="row">                            
                                        <div class="col-md-4">
                                                <div class="form-group">                                      
                                                    <label for="">Permniso</label>
                                                    <input type="text" value="{{old('Permiso')}}" name="Permiso" class="form-control" maxlength="50"
                                                    required oninput="this.value = this.value.toUpperCase()">
                                                    @error('Permiso')
                                                    <small style="color: red">{{$message}}</small>
                                                    @enderror
                                                </div>                                    
                                        </div>  
                                </div>
                        </div>

                                                    <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{url('permisos/')}}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Guardar registro</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
