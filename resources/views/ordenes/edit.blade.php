@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="card card-outline card-success" style="margin-left: 50px;">
                <div class="card-body badge-btn">
                    <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                        <strong>ID del Proyecto N°:</strong>
                    </h2>
                    <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                        {{ $orden ? str_pad($orden->id_proyecto, 4, '0', STR_PAD_LEFT) : 'N/A' }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-outline card-success" style="margin-left: 10px">
                <div class="card-body badge-btn">
                    <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                        <strong> Modalidad de Compra:</strong>
                    </h2>
                    @if ($orden->licitacion)
                        <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                            {{ $orden->licitacion->modalidad }}
                        </h2>
                    @else
                        <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                            <strong>No registra tipo de licitación</strong>
                        </h2>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card card-outline card-success" style="margin-left: 10px">
                <div class="card-body badge-btn">
                    <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                        <strong> N° de licitación:</strong>
                    </h2>
                    @if ($orden->licitacion)
                        <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                            {{ $orden->licitacion->numero }}
                        </h2>
                    @else
                        <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                            <strong>No registra tipo de licitación</strong>
                        </h2>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-outline card-success" style="margin-left: 10px">
                <div class="card-body badge-btn">
                    <h2 style="font-size: 18px; color:rgb(5, 5, 5)">
                        <strong>Especies o Servicios:</strong>
                    </h2>
                    @if ($orden->pac->especie)
                        <h2 style="font-size: 18px; color:rgb(10, 10, 10); margin-bottom: 3;">
                            {{ $orden->pac->especie->detalle }}
                        </h2>
                    @else
                        <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                            <strong>No registra tipo de licitación</strong>
                        </h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row" style="margin-left: 2px">
        <h2 style="font-size: 30px; color:rgb(12, 12, 12); margin-bottom: 3; margin-left: 35px;"><strong>Formulario de
                Modificación "ORDEN DE COMPRAS"</strong></h2>
    </div>
    {{-- <h2 style="font-size: 30px; color:rgb(218, 13, 13); margin-bottom: 3;
     margin-left: 35px;">Especie o Servicio: {{ str_pad($orden->pac->especie->detalle, 4, '0', STR_PAD_LEFT) }}</h2> --}}
    <br>



    <div class="row">
        <div class=col-md-10 style="margin-left: 30px">
            <div class="card card-outline card-success">
                <div class="card-body">
                    <form action="{{ route('ordenes.update', $orden->id) }}" method="post">
                        @csrf
                        @method('PUT') {{-- Método HTTP para actualizar --}}
                        <input type="hidden" name="id_proyecto" value="{{ $orden->id_proyecto }}">
                        <input type="hidden" name="id_licitacion" value="{{ $orden->id_licitacion }}">

                        <div class="row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Modalidad de Compra</label>
                                    <select name="modalidad" id="modalidad"
                                        class="form-control @error('modalidad') is-invalid @enderror" required>
                                        <option value="">-- Ingrese Modalidad de Compra --</option>
                                        @foreach ($tipocompras as $tipocompra)
                                            <option value="{{ $tipocompra->detalle }}"
                                                {{ $orden->orden == $tipocompra->detalle ? 'selected' : '' }}>
                                                {{ $tipocompra->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero">Número Orden de Compra</label>
                                    <input type="text" {{-- old('numero') mantiene el texto si falla la validación, $orden->numero lo carga si es edición --}}
                                        value="{{ old('numero', $orden->numero ?? '') }}" name="numero" id="numero"
                                        maxlength="15" {{-- La clase is-invalid activa el estilo de error de Bootstrap --}}
                                        class="form-control @error('numero') is-invalid @enderror"
                                        placeholder="EJ: OC-12345" required {{-- JavaScript: Convierte a mayúsculas, cambia / por - y borra caracteres prohibidos --}}
                                        oninput="this.value = this.value.toUpperCase().replace(/\//g, '-').replace(/[^A-Z0-9-]/g, '')">

                                    @error('numero')
                                        {{-- Estilo estándar de Bootstrap para mensajes de error --}}
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Monto $$</label>
                                    <input type="numeric" id="monto" class="form-control" name="monto"
                                        value="{{ $orden->monto }}" maxlength="15" oninput="formatNumber(this)"
                                        @error('monto') is-invalid @enderror required>
                                    @error('monto')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Estado del registro</label>
                                    <select name="estado_id" id="estado_id"
                                        class="form-control @error('estado_id') is-invalid @enderror" required>
                                        <option value="">-- Ingrese el estado actual --</option>
                                        @foreach ($estadocompras as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ $orden->estado_id == $estado->id ? 'selected' : '' }}>
                                                {{ $estado->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estado')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    @if ($orden->estado_id == null)
                                        <small style="color: red">No se encontró el registro de estado seleccionado.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Fecha estimativa seguimiento</label>
                                    <input type="date" value="{{ $orden->fecha_seguimiento }}" name="fecha_seguimiento"
                                        class="form-control" required>
                                    @error('fecha_seguimiento')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>





                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observacion">Observación (Registrar información para clarificar el estado
                                        del registro)</label>
                                    <textarea name="observacion" class="form-control" rows="8">{{ $orden->observacion }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-9">
                                <a href="{{ url('ordenes/') }}" class="btn btn-success">Volver al listado</a>
                                <!--<a href="{{ url('ordenes/create') }}" class="btn btn-secondary">Cancelar</a> -->
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
