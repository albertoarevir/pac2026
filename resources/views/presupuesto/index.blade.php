@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-11" style="margin-left: 30px">
            <h3>Presupuesto asignado</h3>
            <br>
            <div class="card card-outline card-primary">
                <div class="card-header">

                    <div class="card-tools">
                        <a href="{{ Route('presupuesto.create') }}" class="btn btn-primary">
                            Registrar nuevo presupuesto
                        </a>
                    </div>

                </div>
                <div class="card-body" style="font-size: 14px; color:rgb(12, 13, 14); margin-bottom: 0;">
                    <table id="example1" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead style="background-color: #c0c0c0">
                            <tr>
                                <th style="width: 3%;">N°</th>
                                <th style="width: 3%;">Año</th>
                                <th style="width: 3%;">Clasificador</th>
                                <th style="width: 10%">Item detallado</th>
                                <th style="width: 10%;">Monto</th>
                                <th style="width: 10%;">Departamento</th>
                                <th style="width: 10%;">Fecha actualización</th>
                                 <th style="width: 8%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $num = 1; @endphp
                            @foreach ($Presupuestos as $presupuesto)
                                <tr>
                                    <td style="text-align: center">{{ $num++ }}</td>
                                    <td>{{ $presupuesto->year }}</td> {{-- Cambié 'anio' por 'year' según tu tabla --}}
                                    <td>{{ $presupuesto->clasificador }}</td>
                                    <td>{{ $presupuesto->item }}</td>
                                    <td>{{ number_format($presupuesto->monto, 0, ',', '.') }}</td> {{-- Formato moneda --}}
                                    <td>{{ $presupuesto->departamento->detalle ?? 'Sin asignar' }}</td>
                                    <td> {{ $presupuesto->updated_at->format('d-m-Y g:i a') }}</td>          
                                    {{-- Nombre del depto --}}
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">
                                            {{-- Botón Editar --}}
                                            <a href="{{ route('presupuesto.edit', $presupuesto->id) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>

                                            {{-- Formulario Eliminar --}}
                                            <form action="{{ route('presupuesto.destroy', $presupuesto->id) }}"
                                                method="post" class="d-inline formulario-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" type="submit">
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
