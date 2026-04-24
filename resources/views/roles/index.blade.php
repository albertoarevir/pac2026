@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-6" style="margin-left: 30px">
            <h3>Administración de Roles registrados</h3>
            <br>
            <div class="card card-outline card-primary">
                <div class="card-header">

                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-lg">
                            Registrar nuevo Rol
                        </button>
                    </div>

                </div>
                <div class="card-body" style="font-size: 14px; color:rgb(12, 13, 14); margin-bottom: 0;">
                    <table id="example1" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead style="background-color: #c0c0c0">
                            <tr>
                                <th style="width: 2%;">N°</th>
                                <th style="width: 25%">Rol</th>
                                <th style="width: 10%; text-align:center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $num = 1;
                            ?>
                            @foreach ($roles as $role)
                                <tr>
                                    <td style="text-align: center">{{ $num++ }}</td>
                                    <td>{{ $role->name }}</td>

                                    <td style="text-align: center">

                                        <div class="btn-group" role="group" aria-label="Basic example">
                                        {{--<a href="{{ url('roles/' . $role->id) }}" type="button"
                                                class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                        --}}
                                                <a href="{{ url('roles/' . $role->id . '/edit') }}" type="button"
                                                class="btn btn-success btn-sm"> Asignar-Permisos</i></a>

                                            <form action="{{ url('roles', $role->id) }}" method="post"
                                                class="d-inline formulario-eliminar">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-danger btn-sm" title="Eliminar de la base de datos"
                                                    type="submit">
                                                    <i class="bi bi-trash-fill"> Eliminar</i>
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
                                /*
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
                                */
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

                <div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ingresar nuevo Rol</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-10" style="margin-left: 30px">
                                                    <div class="card card-outline card-primary">

                                                            <div class="card-body">
                                                                <form action="{{ route('roles.store') }}" method="POST">
                                                                    @csrf
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-10">
                                                                                <div class="form-group">
                                                                                    <label for="">Rol</label>
                                                                                    <input type="text" value="{{ old('Rol') }}"
                                                                                        name="Rol" class="form-control" maxlength="50"
                                                                                        required
                                                                                        oninput="this.value = this.value.toUpperCase()">
                                                                                    @error('Rol')
                                                                                        <small style="color: red">{{ $message }}</small>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <hr>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <a href="{{ url('roles/') }}"
                                                                                        class="btn btn-secondary">Cerrar</a>
                                                                                    <button type="submit" class="btn btn-primary"><i
                                                                                    class="bi bi-floppy2"></i> Guardar registro</button>
                                                                                </div>
                                                                            </div>
                                                                </form>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
