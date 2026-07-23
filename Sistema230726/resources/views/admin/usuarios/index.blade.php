@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-11" style="margin-left: 30px">
        <h1>LISTADO DE USUARIOS</h1>
        <br>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Usuarios Registrados</h3>
                  <div class="card-tools">
                     <a href="{{url('admin/usuarios/create')}}" class="btn btn-primary">
                        Registrar nuevo usuario
                     </a>
                  </div>



            </div>
            <div class="card-body" style="font-size: 14px; color:rgb(12, 13, 14); margin-bottom: 0;">



                <table id="example1" class="table table-striped table-hover table-bordered" style="width: 100%;">
                    <thead style="background-color: #c0c0c0">
                        <tr>
                            <th style="width: 3%;">N°</th>
                            <th style="width: 10%">Rut</th>
                            <th style="width: 25%;">Nombres</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 20%;">Dotación</th>
                            <th style="width: 12%; text-align:center">Habilitado</th>
                            <th style="width: 10%; text-align:center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $num=1;
                      ?>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td style="text-align: center">{{ $num++ }}</td>
                                <td>{{ $usuario->Rut }}</td>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->departamento->detalle}}</td>
                                <td style="text-align: center">
                                    <form action="{{ route('admin.usuarios.toggleHabilitado', $usuario->id) }}" method="POST" class="form-habilitado d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="habilitado" value="{{ $usuario->habilitado ? '0' : '1' }}">
                                        <input type="checkbox" class="chk-habilitado" style="width: 18px; height: 18px;" {{ $usuario->habilitado ? 'checked' : '' }} title="{{ $usuario->habilitado ? 'Habilitado' : 'Inhabilitado' }}">
                                    </form>
                                </td>
                                <td style="text-align: center">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ url('asignarRol/' . $usuario->id.'/edit') }}" type="button"
                                            class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                        <a href="{{url('admin/usuarios/'.$usuario->id.'/edit')}}" type="button" class="btn btn-success btn-sm"><i class="bi bi-pencil"></i></a>
                                        <a href="{{url('admin/usuarios/'.$usuario->id.'/confirm-delete')}}" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                                      </div>
                                </td>

                                </tr>
                        @endforeach
                    </tbody>
                </table>
                <script @cspNonce>
                    $(function() {
                        $("#example1").DataTable({
                            "pageLength": 5,
                            "language": {
                                "emptyTable": "No hay información",
                                "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                                "infoEmpty": "Mostrando 0 a 0 de 0 Registros",
                                "infoFiltered": "(Filtrado de _MAX_ total Registros)",
                                "infoPostFix": "",
                                "thousands": ",",
                                "lengthMenu": "Mostrar _MENU_ Registros",
                                "loadingRecords": "Cargando...",
                                "processing": "Procesando...",
                                "search": "Buscador:",
                                "zeroRecords": "Sin resultados encontrados",
                                "paginate": {
                                    "first": "Primero",
                                    "last": "Ultimo",
                                    "next": "Siguiente",
                                    "previous": "Anterior"
                                }
                            },
                            "responsive": true,
                            "lengthChange": true,
                            "autoWidth": false,
                            buttons: [{
                                    extend: 'collection',
                                    text: 'Reportes',
                                    orientation: 'landscape',
                                    buttons: [{
                                  //      text: 'Copiar',
                                  //      extend: 'copy',
                                  //  }, {
                                  //      extend: 'pdf'
                                  //  }, {
                                  //      extend: 'csv'
                                   // }, {
                                        extend: 'excel'
                                    }, {
                                        text: 'Imprimir',
                                        extend: 'print'
                                    }]
                                },
                               // {
                                 //   extend: 'colvis',
                                   // text: 'Visor de columnas',
                                   // collectionLayout: 'fixed three-column'
                              //  }
                            ],
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    });

                    $(document).on('change', '.chk-habilitado', function() {
                        var $checkbox = $(this);
                        var $form = $checkbox.closest('form');
                        $form.find('input[name="habilitado"]').val($checkbox.is(':checked') ? '1' : '0');

                        Swal.fire({
                            title: $checkbox.is(':checked') ? '¿Habilitar usuario?' : '¿Inhabilitar usuario?',
                            text: $checkbox.is(':checked')
                                ? 'El usuario podrá ingresar nuevamente al sistema.'
                                : 'El usuario no podrá ingresar al sistema mientras esté inhabilitado.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, confirmar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.value) {
                                $form.trigger('submit');
                            } else {
                                $checkbox.prop('checked', !$checkbox.is(':checked'));
                                $form.find('input[name="habilitado"]').val($checkbox.is(':checked') ? '1' : '0');
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </div>
</div>
@endsection
