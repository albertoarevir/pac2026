@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-11" style="margin-left: 30px">            
            <h2 style="font-size: 30px; color:rgb(57, 103, 156); margin-bottom: 3; margin-left: 7px;">
                <strong>Listado de Licitaciones - continuidad del proceso</strong></h2>
            <br>
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Antecedentes registrados</h3>
                    <div class="card-tools">
                        <a href="{{ url('pac/') }}" type="button"
                        class="btn btn-info btn-sm" title="Ingresar O/C">Volver al listado del Plan Anual de Compras</a>
                    <!--<a href="{{ url('modalidad/create') }}" class="btn btn-primary">
                            Registrar licitación u otra modalidad
                        </a>-->
                    </div>

                </div>
                <div class="card-body" style="font-size: 14px; color:rgb(12, 13, 14); margin-bottom: 0;">
                   
                    <table id="example1" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead style="background-color: #44afcc">
                            <tr>
                                <th style="width: 2%;">N° Orden</th>    
                                <th style="width: 2%"> Id Proyecto</th>
                                <th style="width: 2%;">Cantidad Ordenes de Compras</th>
                                <th style="width: 5%"> Especie o Servicio</th>
                                <th style="width: 15%;">Modalidad de compra</th>    
                                <th style="width: 4%;">Numero de Licitación</th>    
                                <th style="width: 5%;">Estado</th>
                               {{-- <th style="width: 19%">Observación</th>--}}
                                <th style="width: 2%;">Fecha ingreso creación registro</th>
                                <th style="width: 2%;">Fecha última actualización</th>
                               {{-- <th style="width: 5%;">Seguimiento de Auditoría al proceso</th> --}} <!-- Nueva columna -->

                                <th style="width: 2%; text-align:center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $num = 1;
                            ?>
                            @foreach ($modalidades as $modalidad)
                                <tr>
                                    <td style="text-align: center">{{ $num++ }}</td>                                        
                                    <td><strong>{{ str_pad($modalidad->id_proyecto, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                    <td>
                                        <button class="btn btn-{{ $modalidad->ordenes->count() > 0 ? 'warning' : 'danger' }} btn-sm btn-rounded">
                                            {{ $modalidad->ordenes->count() }} Orden(es)
                                        </button>
                                    </td>
                                    <td>{{ $modalidad->pac->especie->detalle }}</td>
                                    <td>{{ $modalidad->modalidad}}</td>      
                                    <td>{{ $modalidad->numero }}</td>
                                    <td>
                                       
                                        <button class="btn btn-{{ $modalidad->estado->detalle=='Adjudicada' ?'success' :
                                            ($modalidad->estado->detalle=='Suspendida' || $modalidad->estado->detalle=='Revocada' ? 'danger' :
                                             'warning') }} btn-sm btn-rounded">
                                              {{ $modalidad->estado->detalle }}
                                        </button>
                                       
                                    </td>
                                   
                                    
                                   {{-- <td><pre style="font-size: 14px;">{{ $modalidad->observacion}}</pre></td>--}}
                                    <td>{{ $modalidad->created_at }}</td>
                                    <td>{{ $modalidad->updated_at }}</td>
                                   {{-- <td>{!! $modalidad->auditoria !!}</td> --}}  <!-- Mostrar el mensaje de auditoría -->
                                    <td style="text-align: center">
                                        <div class="btn-group float-right" role="group" aria-label="Basic example">
                                        
                                           
                                            <a href="{{ route('ordenes.create', ['pac' => $modalidad->id_proyecto, 'numero' => $modalidad->numero, 'id_mod'=> $modalidad->id ]) }}"
                                                class="btn btn-warning btn-sm" title="Ingreso de Orden de compras">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                               <!-- <a href="{{ route('modalidad.create', ['pac' => $pac->id]) }}"
                                                    class="btn btn-info btn-sm" title="Ingreso de Licitaciones">
                                                    <i class="bi bi-arrow-up-left-square"></i>
                                                </a>-->
                                           
                                            <a href="{{ url('modalidad/' . $modalidad->id . '/edit') }}" type="button"
                                                class="btn btn-success btn-sm" title="Modificar registro"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('modalidad.destroy', $modalidad->id) }}" method="post"
                                                class="d-inline formulario-eliminar">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-danger btn-sm" title="Eliminar registro" type="submit">
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
                        function formatNumber(input) {
                            let value = input.value.replace(/\./g, ''); // Elimina los puntos existentes
                            if (!isNaN(value)) {
                                // Formatea el número con puntos como separadores de miles
                                input.value = Number(value).toLocaleString('de-DE'); // Alemán usa punto como separador
                            } else {
                                input.value = '';
                            }
                        }

                        function removeCommas() {
                            let input = document.getElementById('presupuesto');
                            input.value = input.value.replace(/\./g, ''); // Elimina los puntos en lugar de las comas
                        }

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
                                "ordering": false,
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
                                            extend: 'excel',
                                            excelNumberFormat: '#.##0.000',
                                            excelNumberFormatOptions: {
                                                thousandsSeparator: '.',
                                                decimalSeparator: '.'
                                            }

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
            </div>
        </div>
    </div>
@endsection
