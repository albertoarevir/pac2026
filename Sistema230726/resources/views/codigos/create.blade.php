@extends('layouts.admin')

@section('content')
<div class="content" style="margin-left: 20px">
    <h2>Crear una nueva descripción del clasificador</h2>

@foreach ($errors->all() as $error2)
    <div class="alert alert-danger">
      <li> {{$error2}}</li>
    </div>
@endforeach

  <div class="row">
    <div class="col-md-6">
          <div class="card card-outline card-primary">
                        <div class="card-header">
                          <h3 class="card-title"><b>Ingrese la descripción del clasificador</b></h3>              
                        </div>
                        
                        <div class="card-body" style="display: block;">
                          <!-- Columna 1 - Fila 1 y 2-->       
                          
                          <form action="{{url('/codigos')}}" method="POST"> 
                                @csrf
                                <div class="row">
                                      <div class="col-md-12">

                                        <div class="row">
                                          <div class="col-md-3">
            
                                              <div class="form-group">
                                                  <label for="">Código presupuestario</label>
                                                  <select name="codigo_id" id="codigo_id" class="form-control @error('codigo_id') is-invalid @enderror" required>
                                                    <option value="">-- Ingrese código --</option>
                                                    @foreach ($clasificadors as $clasificador)
                                                        <option value="{{ $clasificador->codigo_id }}">{{ $clasificador->codigo_id }} </option> 
                                                    @endforeach
                                                </select>             
                                                  @error('codigo_id')
                                                  <small style="color: red">{{$message}}</small>
                                                  @enderror
                                              </div>
                                            </div>
                                          </div>

                                         <div class="row">
                                            <div class="col-md-6">                                            
                                                <div class="form-group">
                                                    <label for="">Código presupuestario</label>
                                                    <input type="text" name="codigopre" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>                                  
                                                                      
                                        <div class="row">
                                          <div class="col-md-12">                                         
                                                <div class="form-group">
                                                    <label for="">Descripción</label>
                                                    <input type="text" name="detalle" class="form-control" required>
                                                </div>
                                          </div>
                                        </div>      

                                    </div>                            
                              </div>                                  
                                    


<br>
<br>
<hr> 
                                    <div class="row">
                                        
                                      <div class="d-grid gap-2 d-md-block">
                                                                                                              
                                        <a href="{{ url('codigos') }}" class="btn btn-primary"><i
                                          class="bi bi-box-arrow-in-left"></i>
                                           Volver al Listado</a>    
                                          <a href="" class="btn btn-primary">Cancelar</a>
                                          <button type="submit" class="btn btn-primary">Guardar Registro</button> 
                                 </div>
                                       
                                    </div>                            
                          </form>
              </div>  
          </div> <!-- card primary-->
    </div> <!--colum 12 -->
  </div><!--row  -->
</div>
@endsection