@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2 style="font-size: 20px; color:rgb(218, 13, 13); margin-bottom: 3; margin-left: 35px;">ID Identificador del
            Proyecto N°: <strong>{{ str_pad($pac->id, 4, '0', STR_PAD_LEFT) }}</strong></h2>

    </div>
    <br>
    <div class="row">
        <div class="col-md-11" style="margin-left: 0px">
            <div class="card card-outline card-primary">

                <div class="card-body">
                    <form action="{{ url('/pac/' . $pac->id) }}" method="post" onsubmit="removeCommas()">
                        @csrf
                        @method('PUT') <!-- Cambiar el método a PUT para actualización -->
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year">Año Pac:</label>
                                        <select class="form-control" name="year" id="year"
                                            @error('year') is-invalid @enderror required>
                                            <option value="">Seleccione un año</option>
                                            @php
                                                $currentYear = date('Y');
                                                $years = range($currentYear, $currentYear - 5);
                                            @endphp
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}"
                                                    {{ $pac->year == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Departamento</label>
                                        <select name="departamento_id" id="departamento_id"
                                            class="form-control @error('departamento_id') is-invalid @enderror" required>
                                            <option value="">-- Ingrese Departamento --</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}"
                                                    {{ $pac->departamento_id == $departamento->id ? 'selected' : '' }}>
                                                    {{ $departamento->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Especie o Servicio</label>
                                        <select name="especie_id" id="especie_id"
                                            class="form-control @error('especie_id') is-invalid @enderror" required>
                                            <option value="">-- Ingrese la especie correspondiente --</option>
                                            @foreach ($especies->sortBy('detalle') as $especie)
                                                <option value="{{ $especie->id }}"
                                                    {{ $pac->especie_id == $especie->id ? 'selected' : '' }}>
                                                    {{ $especie->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('especie_id')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>


                                <script>
                                    $(document).ready(function() {
                                        $('#departamento_id').on('change', function() {
                                            var departamentoId = $(this).val();
                                            $.ajax({
                                                type: 'GET',
                                                url: '{{ route('get-especies') }}',
                                                data: {
                                                    departamento: departamentoId
                                                },
                                                success: function(data) {
                                                    $('#especie_id').empty();
                                                    $('#especie_id').append(
                                                        '<option value="">Seleccione una especie</option>');
                                                    $.each(data, function(index, value) {
                                                        $('#especie_id').append('<option value="' + value.id +
                                                            '">' + value.detalle + '</option>');
                                                    });
                                                }
                                            });
                                        });


                                        // Actualizar el select de especies al cargar la página
                                        var departamentoId = $('#departamento_id').val();
                                        var especieId = $('#especie_id').val();
                                        if (especieId == '') {
                                            $.ajax({
                                                type: 'GET',
                                                url: '{{ route('get-especies') }}',
                                                data: {
                                                    departamento: departamentoId
                                                },
                                                success: function(data) {
                                                    $('#especie_id').empty();
                                                    $('#especie_id').append('<option value="">Seleccione una especie</option>');
                                                    $.each(data, function(index, value) {
                                                        if (value.id == especieId) {
                                                            $('#especie_id').append('<option value="' + value.id +
                                                                '" selected>' + value.detalle + '</option>');
                                                        } else {
                                                            $('#especie_id').append('<option value="' + value.id + '">' +
                                                                value.detalle + '</option>');
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                </script>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Clasificador Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control"
                                            onchange="cargarCodigos()" @error('clasificador') is-invalid @enderror required>
                                            <option value="">-- Seleccione un clasificador --</option>
                                            @foreach ($clasificadors as $clasificador)
                                                <option value="{{ $clasificador->codigo_id }}"
                                                    {{ $pac->clasificador == $clasificador->codigo_id ? 'selected' : '' }}>
                                                    {{ $clasificador->codigo_id }} - {{ $clasificador->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('clasificador')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Codigo - Descripción</label>
                                        <select name="codigo_id" id="codigo" class="form-control"
                                            @error('codigo_id') is-invalid @enderror required>
                                            <option value="">Selecciona un clasificador primero</option>
                                            @if ($pac->codigos)
                                                <option value="{{ $pac->codigos }}" selected>
                                                    {{ $pac->codigos }} -
                                                    {{ optional(Codigo::where('codigopre', $pac->codigos)->first())->detalle }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('codigo')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>
                                        <input type="text" value="{{ $pac->cantidad }}" name="cantidad"
                                            class="form-control" maxlength="7"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                        @error('cantidad')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Presupuesto</label>
                                        <input type="text" id="presupuesto" class="form-control" name="presupuesto"
                                            value="{{ number_format($pac->presupuesto, 0, ',', '.') }}" maxlength="15"
                                            oninput="formatNumber(this)" @error('presupuesto') is-invalid @enderror
                                            required>
                                        @error('presupuesto')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fuente_financiamiento">Fuente de Financiamiento</label>
                                        <select name="fuente_financiamiento" id="fuente_financiamiento_id"
                                            class="form-control" @error('fuente_financiamiento') is-invalid @enderror
                                            required>
                                            <option value="">-- Ingrese Fuente de Financiamiento --</option>
                                            @foreach ($fuentes as $fuente)
                                                <option value="{{ $fuente->id }}"
                                                    {{ $pac->fuente_financiamiento == $fuente->id ? 'selected' : '' }}>
                                                    {{ $fuente->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('fuente_financiamiento_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Estado del proyecto</label>
                                        <select name="estado_id" id="estado_id"
                                            class="form-control @error('estado_id') is-invalid @enderror" required>
                                            <option value="">-- Ingrese el estado actual --</option>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}"
                                                    {{ $pac->estado_id == $estado->id ? 'selected' : '' }}>
                                                    {{ $estado->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('estado')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                {{--
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Unidad de Compra</label>
                                    <select name="unidadcompra" id="unidadcompra" class="form-control @error('unidadcompra') is-invalid @enderror" required>
                                       <option value="">-- Seleccione Unidad --</option>
                                        @foreach ($unidadcompras as $unidad)
                                            <option value="{{ $unidad->detalle }}" {{ $pac->unidadcompra == $unidad->detalle ? 'selected' : '' }}>
                                                {{ $unidad->detalle }}
                                            </option>
                                        @endforeach
                                    </select>                                       
                                    @error('unidadcompra')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
           --}}

                            </div>



                            <div class="row">
                                <div class="col-md-5">

                                    <div class="form-group">
                                        <label for="observaciones">Observación (Registrar información para clarificar el
                                            estado del registro)</label>
                                        <textarea name="observaciones" class="form-control" rows="8">{{ old('observaciones', $pac->observaciones) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estado_modificacion">Ingreso inicial Pac y/o Modificación</label>
                                        <select name="estado_modificacion" id="estado_modificacion" class="form-control"
                                            @error('estado_modificacion') is-invalid @enderror required>
                                            <option value="">-- Ingrese Estado de Modificación --</option>
                                            @foreach ($estados_modificacion as $estado_modificacion)
                                                <option value="{{ $estado_modificacion->id }}"
                                                    {{ $pac->estado_modificacion == $estado_modificacion->id ? 'selected' : '' }}>
                                                    {{ $estado_modificacion->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('estado_modificacion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                


                            </div>

                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('pac/') }}" class="btn btn-success">Volver al listado</a>

                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Actualizar
                                    registro</button>
                            </div>
                        </div>


                    </form>

                    <script>
                        function cargarCodigos() {
                            let clasificadorId = document.getElementById('clasificador').value;
                            if (clasificadorId) {
                                fetch(`{{ route('get-codigos') }}?clasificador=${clasificadorId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        let codigoSelect = document.getElementById('codigo');
                                        codigoSelect.innerHTML = '<option value="">Seleccione un código</option>';
                                        data.forEach(codigo => {
                                            // Verificar si el código actual coincide con el valor guardado en el registro
                                            let selected = codigo.codigopre === "{{ $pac->codigo }}" ? 'selected' : '';
                                            codigoSelect.innerHTML +=
                                                `<option value="${codigo.codigopre}" ${selected}>${codigo.codigopre} - ${codigo.detalle}</option>`;
                                        });
                                    })
                                    .catch(error => console.error('Error al cargar los códigos:', error));
                            } else {
                                document.getElementById('codigo').innerHTML =
                                    '<option value="">Selecciona un clasificador primero</option>';
                            }
                        }

                        // Llamar a la función cargarCodigos() al cargar la página para precargar los códigos si ya hay un clasificador seleccionado
                        document.addEventListener('DOMContentLoaded', function() {
                            let clasificadorId = document.getElementById('clasificador').value;
                            if (clasificadorId) {
                                cargarCodigos();
                            }
                        });

                        function formatNumber(input) {
                            let value = input.value.replace(/\./g, ''); // Elimina los puntos existentes
                            if (!isNaN(value)) {
                                // Formatea el número con puntos como separadores de miles
                                input.value = Number(value).toLocaleString('es'); // Alemán usa punto como separador
                            } else {
                                input.value = '';
                            }
                        }

                        function removeCommas() {
                            let input = document.getElementById('presupuesto');
                            input.value = input.value.replace(/\./g, ''); // Elimina los puntos en lugar de las comas
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
