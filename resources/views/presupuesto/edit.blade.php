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
            <div class="card card-outline card-success"> {{-- Cambié a verde para diferenciar que es edición --}}
                <div class="card-body">
                    <form action="{{ route('presupuesto.update', $presupuesto->id) }}" method="post" onsubmit="removeCommas()">
                        @csrf
                        @method('PUT') {{-- Imprescindible para el update --}}
                        
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
                                        <select name="clasificador" id="clasificador" class="form-control" onchange="cargarCodigos()" required>
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
                                            {{-- Se llenará mediante JS al cargar --}}
                                            <option value="{{ $presupuesto->item }}">{{ $presupuesto->item }}</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Monto --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="presupuesto">Presupuesto $$</label>
                                        <input type="text" id="presupuesto" class="form-control" name="presupuesto"
                                            value="{{ old('presupuesto', number_format($presupuesto->monto, 0, ',', '.')) }}" 
                                            maxlength="15" oninput="formatNumber(this)" required>
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

    <script>
        // Al cargar la página, si hay un clasificador seleccionado, cargar sus códigos
        document.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById('clasificador').value) {
                cargarCodigos("{{ $presupuesto->item }}");
            }
        });

        function cargarCodigos(selectedItem = null) {
            let clasificadorId = document.getElementById('clasificador').value;
            let codigoSelect = document.getElementById('codigo');

            if (clasificadorId) {
                fetch(`{{ route('get-codigos') }}?clasificador=${clasificadorId}`)
                    .then(response => response.json())
                    .then(data => {
                        codigoSelect.innerHTML = '<option value="">Seleccione un código</option>';
                        data.forEach(codigo => {
                            let selected = (selectedItem == codigo.codigopre) ? 'selected' : '';
                            codigoSelect.innerHTML += `<option value="${codigo.codigopre}" ${selected}>${codigo.codigopre} - ${codigo.detalle}</option>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function formatNumber(input) {
            let value = input.value.replace(/\./g, '');
            if (!isNaN(value) && value !== '') {
                input.value = Number(value).toLocaleString('es');
            } else {
                input.value = '';
            }
        }

        function removeCommas() {
            let input = document.getElementById('presupuesto');
            input.value = input.value.replace(/\./g, '');
        }
    </script>
@endsection