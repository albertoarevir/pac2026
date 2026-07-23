@extends('layouts.admin')
@section('content')
    <div class="row">
        <h2 style="font-size: 25px color:rgb(9, 9, 9); margin-bottom: 3; margin-left: 35px;"><strong> Formulario de Ingreso
                Plan Anual de Compras </strong></h2>
    </div>
    <br>
    <div class="row">
        <div class="col-md-11" style="margin-left: 20px">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <form id="formPac" action="{{ url('/pac') }}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year">Año:</label>
                                        <select class="form-control" name="year" id="year"
                                            @error('year') is-invalid @enderror" required>
                                            <option value="">Seleccione un año</option>
                                            @php
                                                $currentYear = date('Y');
                                                $years = range($currentYear, $currentYear - 1);
                                            @endphp
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}"
                                                    {{ old('year') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Departamento</label>
                                        <select name="departamento" id="departamento" class="form-control"
                                            @error('departamento') is-invalid @enderror required>
                                            <option value="">-- Ingrese Departamento --</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}"
                                                    {{ old('departamento') == $departamento->id ? 'selected' : '' }}>
                                                    {{ $departamento->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Especie o servicio</label>
                                        <select name="especie" id="especie"
                                            class="form-control @error('especie') is-invalid @enderror" required>
                                            <option value="">-- Ingrese la especie correspondiente --</option>
                                        </select>
                                        @error('especie')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Subtitulo Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control"
                                            @error('clasificador') is-invalid @enderror" required>
                                            <option value="">-- Seleccione un clasificador --</option>
                                            @foreach ($clasificadors as $clasificador)
                                                <option value="{{ $clasificador->codigo_id }}"
                                                    {{ old('clasificador') == $clasificador->codigo_id ? 'selected' : '' }}>
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
                                        <label for="">Item - Presupuestario</label>
                                        <select name="codigo_id" id="codigo" class="form-control"
                                            @error('codigo_id') is-invalid @enderror required>
                                            <option value="">Selecciona un clasificador primero</option>
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
                                        <label for="">Cantidad/Litros/Kilos</label>
                                        <input type="text" id="cantidad" class="form-control" name="cantidad"
                                            value="{{ old('cantidad') }}" maxlength="7" required>
                                        @error('cantidad')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Asignación Inicial</label>
                                        <input type="text" id="presupuesto"
                                            class="form-control @error('presupuesto') is-invalid @enderror"
                                            name="presupuesto"
                                            value="{{ old('presupuesto') ? number_format((int) str_replace('.', '', old('presupuesto')), 0, ',', '.') : '' }}"
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
                                        <select name="fuente_financiamiento" id="fuente_financiamiento" class="form-control"
                                            @error('fuente_financiamiento') is-invalid @enderror required>
                                            <option value="">-- Ingrese Fuente de Financiamiento --</option>
                                            @foreach ($fuentes as $fuente)
                                                <option value="{{ $fuente->id }}"
                                                    {{ old('fuente_financiamiento') == $fuente->id ? 'selected' : '' }}>
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
                                                    {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
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
                                        <label for="observacion">Observacion (Registrar informacion para clarificar el
                                            estado del registro)</label>
                                        <textarea name="observacion" class="form-control" rows="8">{{ old('observacion') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estado_modificacion">Ingreso inicial Pac y/o Modificacion</label>
                                        <select name="estado_modificacion" id="estado_modificacion" class="form-control"
                                            @error('estado_modificacion') is-invalid @enderror required>
                                            <option value="">-- Ingrese Estado de Modificacion --</option>
                                            @foreach ($estados_modificacion as $estado_modificacion)
                                                <option value="{{ $estado_modificacion->id }}"
                                                    {{ old('estado_modificacion') == $estado_modificacion->id ? 'selected' : '' }}>
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
                                <a href="{{ url('pac/create') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Guardar
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

        var oldDepartamento = @json(old('departamento', ''));
        var oldEspecie      = @json(old('especie', ''));
        var oldClasificador = @json(old('clasificador', ''));
        var oldCodigo       = @json(old('codigo_id', ''));

        // ── Asignacion Inicial: solo numeros con puntos de miles (formato chileno) ──
        var presupuestoInput = document.getElementById('presupuesto');

        function formatMiles(input) {
            var raw = input.value.replace(/[^0-9]/g, '');
            if (raw === '') { input.value = ''; return; }
            input.value = parseInt(raw, 10).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        presupuestoInput.addEventListener('input', function () { formatMiles(this); });

        // ── Cantidad: solo digitos ──
        document.getElementById('cantidad').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // ── Quitar puntos de miles antes de enviar para que el servidor reciba numero limpio ──
        document.getElementById('formPac').addEventListener('submit', function () {
            presupuestoInput.value = presupuestoInput.value.replace(/\./g, '');
        });

        // ── Departamento -> Especie (AJAX) ──
        $('#departamento').on('change', function () {
            var departamentoId = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get-especies") }}',
                data: { departamento: departamentoId },
                success: function (data) {
                    $('#especie').empty();
                    $('#especie').append('<option value="">Seleccione una especie o servicio</option>');
                    $.each(data, function (index, value) {
                        var sel = (oldEspecie !== '' && String(value.id) === String(oldEspecie))
                            ? ' selected' : '';
                        $('#especie').append(
                            '<option value="' + value.id + '"' + sel + '>' + value.detalle + '</option>'
                        );
                    });
                }
            });
        });

        // ── Clasificador -> Item Presupuestario ──
        function cargarCodigos() {
            var clasificadorId = document.getElementById('clasificador').value;
            if (clasificadorId) {
                fetch('{{ route("get-codigos") }}?clasificador=' + clasificadorId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var sel = document.getElementById('codigo');
                        sel.innerHTML = '<option value="">Seleccione un codigo</option>';
                        data.forEach(function (codigo) {
                            var selected = (oldCodigo !== '' && String(codigo.codigopre) === String(oldCodigo))
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

        document.getElementById('clasificador').addEventListener('change', cargarCodigos);

        // ── Restaurar selects dinamicos si la pagina vuelve con errores de validacion ──
        if (oldDepartamento !== '') {
            $('#departamento').trigger('change');
        }
        if (oldClasificador !== '') {
            cargarCodigos();
        }

    });
    </script>
@endsection
