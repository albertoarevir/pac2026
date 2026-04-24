@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-11" style="margin-left: 30px">
            <h3>Administración de usuarios </h3>
            <br>
            <div class="card card-outline card-primary">
                <div class="card-header">

                    <div class="card-tools">
                        <a href="{{ Route('funcionarios.create') }}" class="btn btn-primary">
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
                                <th style="width: 10%;">Código</th>
                                <th style="width: 10%;">Grado</th>
                                <th style="width: 15%;">Nombres</th>
                                <th style="width: 10%;">Apellidos</th>
                                <th style="width: 10%;">Dotacion</th>
                                <th style="width: 15%;">Email</th>
                                <th style="width: 15%; text-align:center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $num = 1;
                            ?>
                            @foreach ($funcionarios as $funcionario)
                                <tr>
                                    <td style="text-align: center">{{ $num++ }}</td>
                                    <td>{{ $funcionario->Rut }}</td>
                                    <td>{{ $funcionario->Codigo }}</td>
                                    <td>{{ $funcionario->Grado }}</td>
                                    <td>{{ $funcionario->Nombres }}</td>
                                    <td>{{ $funcionario->Apellidos }}</td>
                                    <td>{{ $funcionario->Dotacion }}</td>
                                    <td>{{ $funcionario->Email }}</td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ url('asignarRol/' . $funcionario->id.'/edit') }}" type="button"
                                                class="btn btn-info btn-sm">Rol</i></a>

                                            <a href="{{ url('funcionarios/' . $funcionario->id . '/edit') }}"
                                                type="button" class="btn btn-success btn-sm"></i>Editar</a>

                                            <form action="{{ url('funcionarios', $funcionario->id) }}" method="post"
                                                class="d-inline formulario-eliminar">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-danger btn-sm" title="Eliminar de la base de datos"
                                                    type="submit">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
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
                    </script>

                    <script>
                        $(document).ready(function() {
                            $('.formulario-eliminar').submit(function(e) {
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
                                        $(this).unbind('submit').submit();
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection
