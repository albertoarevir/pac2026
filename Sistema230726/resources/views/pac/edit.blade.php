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
                    <form id="formPac" action="{{ route('pac.update', $pac->id) }}" method="post">
                        @csrf
                        @method('PUT')
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Clasificador Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control"
                                            @error('clasificador') is-invalid @enderror required>
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
                                        @error('codigo_id')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>
                                        <input type="text" id="cantidad"
                                            value="{{ old('cantidad', $pac->cantidad) }}"
                                            name="cantidad" class="form-control" maxlength="7" required>
                                        @error('cantidad')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Presupuesto</label>
                                        <input type="text" id="presupuesto"
                                            class="form-control @error('presupuesto') is-invalid @enderror"
                                            name="presupuesto"
                                            value="{{ old('presupuesto') ? number_format((int) str_replace('.', '', old('presupuesto')), 0, ',', '.') : number_format($pac->presupuesto, 0, ',', '.') }}"
                                            maxlength="15"
                                            placeholder="Ej: 1.500.000"
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
                                        @error('fuente_financiamiento')
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
                                        @error('estado_id')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
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
                                <a href="{{ route('pac.index') }}" class="btn btn-success">Volver al listado</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Actualizar
                                    registro</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script @cspNonce>
    document.addEventListener('DOMContentLoaded', function () {

        var currentCodigo = @json($pac->codigo);

        // -- Presupuesto: solo numeros con puntos de miles --
        var presupuestoInput = document.getElementById('presupuesto');

        function formatMiles(input) {
            var raw = input.value.replace(/[^0-9]/g, '');
            if (raw === '') { input.value = ''; return; }
            input.value = parseInt(raw, 10).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        presupuestoInput.addEventListener('input', function () { formatMiles(this); });

        // -- Cantidad: solo digitos --
        document.getElementById('cantidad').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // -- Quitar puntos de miles antes de enviar --
        document.getElementById('formPac').addEventListener('submit', function () {
            presupuestoInput.value = presupuestoInput.value.replace(/\./g, '');
        });

        // -- Clasificador -> Codigo --
        function cargarCodigos(selectedCodigo) {
            var clasificadorId = document.getElementById('clasificador').value;
            if (clasificadorId) {
                fetch('{{ route("get-codigos") }}?clasificador=' + clasificadorId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var sel = document.getElementById('codigo');
                        sel.innerHTML = '<option value="">Seleccione un codigo</option>';
                        data.forEach(function (codigo) {
                            var selected = (selectedCodigo && String(codigo.codigopre) === String(selectedCodigo))
                                ? ' selected' : '';
                            sel.innerHTML += '<option value="' + codigo.codigopre + '"' + selected + '>' +
                                codigo.codigopre + ' - ' + codigo.detalle + '</option>';
                        });
                    })
                    .catch(function (e) { console.error('Error al cargar codigos:', e); });
            } else {
                document.getElementById('codigo').innerHTML =
                    '<option value="">Selecciona un clasificador primero</option>';
            }
        }

        document.getElementById('clasificador').addEventListener('change', function () {
            cargarCodigos(null);
        });

        // -- Departamento -> Especie (AJAX) --
        $('#departamento_id').on('change', function () {
            var departamentoId = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get-especies") }}',
                data: { departamento: departamentoId },
                success: function (data) {
                    $('#especie_id').empty();
                    $('#especie_id').append('<option value="">Seleccione una especie</option>');
                    $.each(data, function (index, value) {
                        $('#especie_id').append('<option value="' + value.id + '">' + value.detalle + '</option>');
                    });
                }
            });
        });

        // -- Cargar codigos al inicio preseleccionando el codigo guardado --
        if (document.getElementById('clasificador').value) {
            cargarCodigos(currentCodigo);
        }

    });
    </script>
@endsection
