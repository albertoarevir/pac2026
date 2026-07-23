@extends('layouts.admin')
@section('content')
  <div class="content" style="margin-left: 20px">
    <h2>Listado de Departamentos</h2>

@if ($message = Session::get('mensaje'))
    <script @cspNonce>
      Swal.fire({
      title: "Buen trabajo !!",
      text: "{{$message}}",
      icon: "success"
    });
</script>

@endif

@if ($error = Session::get('error'))
    <script @cspNonce>
      Swal.fire({
      title: "No se pudo eliminar",
      text: "{{$error}}",
      icon: "error"
    });
</script>
@endif




    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title"><b>Departamentos registrados</b></h3>
            <div class="card-tools">
              <a href="{{url('departamentos/create')}}" class="btn btn-primary">
                <i class="bi bi-file-earmark-text"></i> Ingresar nuevo Departamento
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
                @foreach ($departamentos as $departamento)
                    <tr>
                      <td><?php echo ++$contador ?></td>
                      <td>{{$departamento->detalle}}</td>

                      <td style="text-align: center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <a href="{{route('departamentos.edit', $departamento->id)}}" class="btn btn-success" type="button"><i class="bi bi-pencil-fill"></i></a>


                          <form action="{{url('departamentos',$departamento->id)}}" method="post" CLASS="d-inline formulario-eliminar">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button class="btn btn-danger" type="submit">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>


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

<script @cspNonce>
  $(document).on('submit', '.formulario-eliminar', function (e) {
      e.preventDefault();
      var form = this;

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
              form.submit();
          }
      });
  });
</script>

@endsection
