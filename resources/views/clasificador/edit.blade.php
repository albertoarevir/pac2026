@extends('layouts.admin')

@section('content')
<div class="content" style="margin-left: 20px">
    <h2>Actualizar una nueva categoría</h2>

@foreach ($errors->all() as $error2)
    <div class="alert alert-danger">
      <li> {{$error2}}</li>
    </div>
@endforeach

  <div class="row">
    <div class="col-md-6">
          <div class="card card-outline card-primary">
                        <div class="card-header">
                          <h3 class="card-title"><b>Ingrese la categoría correspondiente</b></h3>              
                        </div>
                        
                        <div class="card-body" style="display: block;">
                          <!-- Columna 1 - Fila 1 y 2-->       
                          
                          <form action="{{url('/clasificador', $clasificador->id)}}" method="POST"> 
                                @csrf
                                {{method_field('Patch')}}
                                    <div class="row">
                                        <div class="col-md-9">                                            
                                                <div class="form-group">
                                                <label for="">Código</label>
                                                <input value="{{$clasificador->codigo}}" type="text" name="detalle" class="form-control" required>
                                                </div>                                           
                                        </div>                                  
                                    </div>
                                    <div class="row">
                                      <div class="col-md-9">                                            
                                              <div class="form-group">
                                              <label for="">Descripción de la Categoría</label>
                                              <input value="{{$clasificador->detalle}}" type="text" name="detalle" class="form-control" required>
                                              </div>                                           
                                      </div>                                  
                                  </div>
<br>
<br>
<hr> 
                                    <div class="row">
                                      <div class="d-grid gap-2 d-md-block">
                                                                                                              
                                              <a href="{{ url('clasificador') }}" class="btn btn-primary"><i
                                                class="bi bi-box-arrow-in-left"></i>
                                                 Volver al Listado</a>    
                                                <a href="" class="btn btn-primary">Cancelar</a>
                                                <button type="submit" class="btn btn-primary">Actualizar Registro</button> 
                                       </div>
                                    </div>                            
                          </form>
              </div>  
          </div> <!-- card primary-->
    </div> <!--colum 12 -->
  </div><!--row  -->
</div>
@endsection