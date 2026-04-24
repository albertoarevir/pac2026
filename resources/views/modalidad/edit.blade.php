@extends('layouts.admin')
@section('content')
    <div class="row" style="margin-left: 2px">
        <h2 style="font-size: 30px; color:rgb(12, 12, 12); margin-bottom: 3; margin-left: 35px;"><strong>Formulario de Modificación "LICITACIONES"</strong></h2>
    </div>
    <h2 style="font-size: 30px; color:rgb(218, 13, 13); margin-bottom: 3; margin-left: 35px;">ID Identificador del Proyecto
        N°: {{ str_pad($modalidad->id_proyecto, 4, '0', STR_PAD_LEFT) }}</h2>
    <br>

    

    <div class="row">
        <div class="col-md-9" style="margin-left: 30px">
            <div class="card card-outline card-success">
                <div class="card-body">
                    <form action="{{ route('modalidad.update', $modalidad->id) }}" method="post">
                        @csrf
                        @method('PUT') {{-- Método HTTP para actualizar --}}
                        <input type="hidden" name="id_proyecto" value="{{ $modalidad->id_proyecto}}">
                      
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Modalidad de Compra</label>
                                    <select name="modalidad" id="modalidad"
                                        class="form-control @error('modalidad') is-invalid @enderror" required>
                                        <option value="">-- Ingrese Modalidad de Compra --</option>
                                        @foreach ($tipocompras as $tipocompra)
                                            <option value="{{ $tipocompra->detalle }}"
                                                {{ $modalidad->modalidad == $tipocompra->detalle ? 'selected' : '' }}>
                                                {{ $tipocompra->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Número identificador</label>
                                    <input type="text" value="{{ $modalidad->numero }}" name="numero" maxlength="15" class="form-control"
                                    required oninput="this.value = this.value.toUpperCase().replace('/', '-')">
                                    @error('numero')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Estado del registro</label>
                                    <select name="estado_id" id="estado_id" class="form-control @error('estado_id') is-invalid @enderror" required>
                                        <option value="">-- Ingrese el estado actual --</option>
                                        @foreach ($estados as $estado)                                      
                                        <option value="{{ $estado->id }}" {{ $modalidad->estado_id == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->detalle }}
                                        </option>
                                        @endforeach
                                    </select>                                       
                                    @error('estado')
                                    <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    @if($modalidad->estado_id == null)
                                        <small style="color: red">No se encontró el registro de estado seleccionado.</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observacion">Observación (Registrar información para clarificar el estado
                                        del registro)</label>
                                    <textarea name="observacion" class="form-control" rows="8">{{ $modalidad->observacion }}</textarea> 
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-9">
                                <a href="{{ url('modalidad/') }}" class="btn btn-success">Volver al listado</a>
                                <!--<a href="{{ url('modalidad/create') }}" class="btn btn-secondary">Cancelar</a> -->
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Actualizar
                                    registro</ button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

@endsection