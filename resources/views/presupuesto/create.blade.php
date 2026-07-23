@extends('layouts.admin')
@section('content')
    <div class="row">
        <h2 style="font-size: 25px color:rgb(9, 9, 9); margin-bottom: 3; margin-left: 35px;"><strong> Ingreso
                PRESUPUESTO según datos ERP-SAP</strong></h2>
    </div>
    <br>
    <div class="row">
        <div class="col-md-9" style="margin-left: 20px">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <form id="formPresupuesto" action="{{ url('/presupuesto') }}" method="post">
                        @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="year">Año Pac:</label>
                                        <select class="form-control" name="year" id="year"
                                            @error('year') is-invalid @enderror required>
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

                                <div class="col-3">
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

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Clasificador Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control"
                                            @error('clasificador') is-invalid @enderror required>
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Codigo - Descripción</label>
                                        <select name="codigo_id" id="codigo" class="form-control"
                                            @error('codigo_id') is-invalid @enderror required>
                                            <option value="">Selecciona un clasificador primero</option>
                                        </select>
                                        @error('codigo_id')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Presupuesto $$</label>
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
                            </div>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="observacion">Hoja de Vida Presupuesto (Registrar información para clarificar el
                                            estado del PRESUPUESTO)</label>
                                        <textarea name="observacion" class="form-control" rows="8">{{ old('observacion') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <a href="{{ url('presupuesto/') }}" class="btn btn-success">Volver al listado</a>
                                    <a href="{{ url('presupuesto/create') }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Guardar
                                        registro</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: ítem con presupuesto ya asignado -->
    <div class="modal fade" id="modalDuplicado" tabindex="-1" role="dialog" aria-labelledby="modalDuplicadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalDuplicadoLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Presupuesto ya asignado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>El ítem <strong id="modalCodigoTexto"></strong> ya tiene presupuesto asignado a este departamento para el año seleccionado.</p>
                    <p class="mb-0">Monto asignado: <strong id="modalMonto" class="text-danger" style="font-size:1.1em;"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Entendido, volver al listado</button>
                </div>
            </div>
        </div>
    </div>

    <script @cspNonce>
    document.addEventListener('DOMContentLoaded', function () {

        var oldClasificador = @json(old('clasificador', ''));
        var oldCodigo       = @json(old('codigo_id', ''));

        // ── Presupuesto: solo numeros con puntos de miles (formato chileno) ──
        var presupuestoInput = document.getElementById('presupuesto');

        function formatMiles(input) {
            var raw = input.value.replace(/[^0-9]/g, '');
            if (raw === '') { input.value = ''; return; }
            input.value = parseInt(raw, 10).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        presupuestoInput.addEventListener('input', function () { formatMiles(this); });

        // ── Quitar puntos de miles antes de enviar ──
        document.getElementById('formPresupuesto').addEventListener('submit', function () {
            presupuestoInput.value = presupuestoInput.value.replace(/\./g, '');
        });

        // ── Clasificador -> Codigo ──
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
                        // Si se restauro un codigo, verificar duplicado automaticamente
                        if (oldCodigo !== '' && sel.value) { verificarDuplicado(); }
                    })
                    .catch(function (e) { console.error('Error al cargar codigos:', e); });
            } else {
                document.getElementById('codigo').innerHTML =
                    '<option value="">Selecciona un clasificador primero</option>';
            }
        }

        document.getElementById('clasificador').addEventListener('change', function () {
            oldCodigo = '';
            cargarCodigos();
        });

        // ── Verificacion de duplicado ──
        function verificarDuplicado() {
            var departamentoId = document.getElementById('departamento').value;
            var codigoId       = document.getElementById('codigo').value;
            var year           = document.getElementById('year').value;

            if (!departamentoId || !codigoId || !year) return;

            fetch('{{ route("presupuesto.check-duplicate") }}?departamento_id=' + departamentoId +
                '&codigo_id=' + encodeURIComponent(codigoId) + '&year=' + year)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.existe) {
                        var codigoTexto = document.getElementById('codigo').selectedOptions[0].text;
                        document.getElementById('modalCodigoTexto').textContent = codigoTexto;
                        document.getElementById('modalMonto').textContent = '$ ' + data.monto;
                        $('#modalDuplicado').modal('show');
                    }
                })
                .catch(function (e) { console.error('Error al verificar duplicado:', e); });
        }

        document.getElementById('codigo').addEventListener('change', verificarDuplicado);
        document.getElementById('departamento').addEventListener('change', function () {
            if (document.getElementById('codigo').value) verificarDuplicado();
        });
        document.getElementById('year').addEventListener('change', function () {
            if (document.getElementById('codigo').value) verificarDuplicado();
        });

        $('#modalDuplicado').on('hidden.bs.modal', function () {
            window.location.href = '{{ route("presupuesto.index") }}';
        });

        // ── Restaurar select dinamico si vuelve con errores de validacion ──
        if (oldClasificador !== '') { cargarCodigos(); }

    });
    </script>

@endsection
