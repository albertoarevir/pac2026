@extends('layouts.admin')
@section('content')
    <div class="row">
        <h2 style="font-size: 25px; color:rgb(9, 9, 9); margin-bottom: 3; margin-left: 35px;">
            <strong>Editar PRESUPUESTO ID: {{ $presupuesto->id }}</strong>
        </h2>
    </div>
    <br>
    <div class="row">
        <div class="col-md-9" style="margin-left: 20px">
            <div class="card card-outline card-success">
                <div class="card-body">
                    <form id="formPresupuesto" action="{{ route('presupuesto.update', $presupuesto->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="col-md-12">
                            <div class="row">
                                {{-- Año --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="year">Año Pac:</label>
                                        <select class="form-control" name="year" id="year" required>
                                            @php
                                                $currentYear = date('Y');
                                                $years = range($currentYear, $currentYear - 1);
                                            @endphp
                                            @foreach ($years as $y)
                                                <option value="{{ $y }}" {{ (old('year', $presupuesto->year) == $y) ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Departamento --}}
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="departamento">Departamento</label>
                                        <select name="departamento" id="departamento" class="form-control" required>
                                            @foreach ($departamentos as $depto)
                                                <option value="{{ $depto->id }}" {{ (old('departamento', $presupuesto->departamento_id) == $depto->id) ? 'selected' : '' }}>
                                                    {{ $depto->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Clasificador --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="clasificador">Clasificador Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach ($clasificadors as $cl)
                                                <option value="{{ $cl->codigo_id }}" {{ (old('clasificador', $presupuesto->clasificador) == $cl->codigo_id) ? 'selected' : '' }}>
                                                    {{ $cl->codigo_id }} - {{ $cl->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Código / Item --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="codigo">Codigo - Descripción</label>
                                        <select name="codigo_id" id="codigo" class="form-control" required>
                                            <option value="{{ $presupuesto->item }}">{{ $presupuesto->item }}</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Monto --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="presupuesto">Presupuesto $$</label>
                                        <input type="text" id="presupuesto"
                                            class="form-control @error('presupuesto') is-invalid @enderror"
                                            name="presupuesto"
                                            value="{{ old('presupuesto') ? number_format((int) str_replace('.', '', old('presupuesto')), 0, ',', '.') : number_format($presupuesto->monto, 0, ',', '.') }}"
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
                                        <label for="observacion">Observación</label>
                                        <textarea name="observacion" class="form-control" rows="4">{{ old('observacion', $presupuesto->observaciones) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-9">
                                    <a href="{{ url('presupuesto/') }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-floppy2"></i> Actualizar Registro</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script @cspNonce>
    document.addEventListener('DOMContentLoaded', function () {

        var currentItem = @json($presupuesto->item);

        // -- Presupuesto: solo numeros con puntos de miles (formato chileno) --
        var presupuestoInput = document.getElementById('presupuesto');

        function formatMiles(input) {
            var raw = input.value.replace(/[^0-9]/g, '');
            if (raw === '') { input.value = ''; return; }
            input.value = parseInt(raw, 10).toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        presupuestoInput.addEventListener('input', function () { formatMiles(this); });

        // -- Quitar puntos antes de enviar --
        document.getElementById('formPresupuesto').addEventListener('submit', function () {
            presupuestoInput.value = presupuestoInput.value.replace(/\./g, '');
        });

        // -- Clasificador -> Codigo --
        function cargarCodigos(selectedItem) {
            var clasificadorId = document.getElementById('clasificador').value;
            var codigoSelect   = document.getElementById('codigo');

            if (clasificadorId) {
                fetch('{{ route("get-codigos") }}?clasificador=' + clasificadorId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        codigoSelect.innerHTML = '<option value="">Seleccione un codigo</option>';
                        data.forEach(function (codigo) {
                            var sel = (selectedItem && String(codigo.codigopre) === String(selectedItem)) ? ' selected' : '';
                            codigoSelect.innerHTML += '<option value="' + codigo.codigopre + '"' + sel + '>' +
                                codigo.codigopre + ' - ' + codigo.detalle + '</option>';
                        });
                    })
                    .catch(function (e) { console.error('Error al cargar codigos:', e); });
            }
        }

        document.getElementById('clasificador').addEventListener('change', function () {
            cargarCodigos(null);
        });

        // -- Cargar codigos al inicio con el item actual preseleccionado --
        if (document.getElementById('clasificador').value) {
            cargarCodigos(currentItem);
        }

    });
    </script>
@endsection