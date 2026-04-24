@extends('layouts.admin')
@section('content')
  <div class="content" style="margin-left: 20px">
    <h2>Listado de Fuentes de Financiamiento</h2>

@if ($message = Session::get('mensaje'))
    <script>
      Swal.fire({
      title: "Buen trabajo !!",
      text: "{{$message}}",
      icon: "success"
    });
</script>
    
@endif




    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title"><b>Fuentes de Financiamiento</b></h3>           
            <div class="card-tools">
              <a href="{{url('fuentefinanciamiento/create')}}" class="btn btn-primary">
                <i class="bi bi-file-earmark-text"></i> Ingresar nuevo Financiamiento
              </a>
            </div>
          </div>
       
          <!-- /.card-header -->
          <div class="card-body" style="display: block;">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Nº</th>
                  <th>Detalle</th>                  
                  <th>Acción</th>
  
                </tr>
              </thead>
              <tbody>
                <?php $contador=0;?>
                                  
                @foreach ($fuentes as $fuente)
                    <tr>
                      <td><?php echo ++$contador ?></td>
                      <td>{{$fuente->detalle}}</td>    
                     
                      <td style="text-align: center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <a href="{{route('fuentefinanciamiento.edit', $fuente->id)}}" class="btn btn-success" type="button"><i class="bi bi-pencil-fill"></i></a>  
                          

                          <form action="{{route('fuentefinanciamiento.destroy',$fuente->id)}}" method="post" CLASS="d-inline formulario-eliminar">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                         
                        <script>
                         
                          $('.formulario-eliminar').submit(function(e){
                               e.preventDefault();

                               Swal.fire({
                                  title: "¿Estas seguro ?",
                                  text: "Eliminarás el registro de la base de datos",
                                  icon: "warning",
                                  showCancelButton: true,
                                  confirmButtonColor: "#3085d6",
                                  cancelButtonColor: "#d33",
                                  confirmButtonText: "Sí, eliminarlo.",
                                  cancelButtonText: "Cancelar"
                                  }).then((result) => {
                                  if (result.value) {
                                   /*
                                     Swal.fire({
                                     title: "Deleted!",
                                     text: "Your file has been deleted.",
                                     icon: "success"
                                  });*/
                                  this.submit(); 

                                  }
                               });




                          }); 


                          /*
                          function confirmDelete() {
                             
                           }
                           */
                       </script>


                      </div>
                    </td>
                  </tr>
                  
              @endforeach
              </tbody>
            </table>
           
            
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>

    </div>
   
  </div>
   


@endsection

