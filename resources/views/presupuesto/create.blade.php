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
                    <form action="{{ url('/presupuesto') }}" method="post" onsubmit="removeCommas()">
                        @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="year">Año Pac:</label>
                                        <select class="form-control" name = "year" id="year"
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

                                <script @cspNonce>
                                    $(document).ready(function() {
                                        $('#departamento').on('change', function() {
                                            var departamentoId = $(this).val();
                                            $.ajax({
                                                type: 'GET',
                                                url: '{{ route('get-especies') }}',
                                                data: {
                                                    departamento: departamentoId
                                                },
                                                success: function(data) {
                                                    console.log(data);
                                                    $('#especie').empty();
                                                    $('#especie').append(
                                                        '<option value="">Seleccione una especie o servicio</option>');
                                                    $.each(data, function(index, value) {
                                                        $('#especie').append('<option value="' + value.id + '">' +
                                                            value.detalle + '</option>');
                                                    });
                                                }
                                            });
                                        });
                                    });
                                </script>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Clasificador Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control"
                                            @error('clasificador') is-invalid @enderror required>
                                            <option value="">-- Seleccione un clasificador --</option>
                                            @foreach ($clasificadors as $clasificador)
                                                <option value="{{ $clasificador->codigo_id }}">
                                                    {{ $clasificador->codigo_id }} - {{ $clasificador->detalle }}</option>
                                            @endforeach
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
                                        <label for="">Codigo - Descripción</label>
                                        <select name="codigo_id" id="codigo" class="form-control"
                                            @error('codigo_id') is-invalid @enderror required>
                                            <option value="">Selecciona un clasificador primero</option>
                                        </select>
                                        @error('codigo')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Presupuesto $$</label>
                                        <input type="text" id="presupuesto" class="form-control" name="presupuesto"
                                            value="{{ old('presupuesto') }}" maxlength="15" oninput="formatNumber(this)"
                                            @error('presupuesto') is-invalid @enderror required>
                                        @error('presupuesto')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <script @cspNonce>
                                document.getElementById('clasificador').addEventListener('change', cargarCodigos);

                                function cargarCodigos() {
                                    let clasificadorId = document.getElementById('clasificador').value;
                                    if (clasificadorId) {
                                        fetch(`{{ route('get-codigos') }}?clasificador=${clasificadorId}`)
                                            .then(response => response.json())
                                            .then(data => {
                                                let codigoSelect = document.getElementById('codigo');
                                                codigoSelect.innerHTML = '<option value="">Seleccione un código</option>';
                                                data.forEach(codigo => {
                                                    codigoSelect.innerHTML +=
                                                        `<option value="${codigo.codigopre}">${codigo.codigopre} - ${codigo.detalle}</option>`;
                                                });
                                            })
                                            .catch(error => console.error('Error al cargar los códigos:', error));
                                    } else {
                                        document.getElementById('codigo').innerHTML =
                                            '<option value="">Selecciona un clasificador primero</option>';
                                    }
                                }

                                document.getElementById('codigo').addEventListener('change', verificarDuplicado);
                                document.getElementById('departamento').addEventListener('change', function() {
                                    if (document.getElementById('codigo').value) verificarDuplicado();
                                });
                                document.getElementById('year').addEventListener('change', function() {
                                    if (document.getElementById('codigo').value) verificarDuplicado();
                                });

                                function verificarDuplicado() {
                                    const departamentoId = document.getElementById('departamento').value;
                                    const codigoId      = document.getElementById('codigo').value;
                                    const year          = document.getElementById('year').value;

                                    if (!departamentoId || !codigoId || !year) return;

                                    fetch(`{{ route('presupuesto.check-duplicate') }}?departamento_id=${departamentoId}&codigo_id=${encodeURIComponent(codigoId)}&year=${year}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.existe) {
                                                const codigoTexto = document.getElementById('codigo').selectedOptions[0].text;
                                                document.getElementById('modalCodigoTexto').textContent = codigoTexto;
                                                document.getElementById('modalMonto').textContent = '$ ' + data.monto;
                                                $('#modalDuplicado').modal('show');
                                            }
                                        })
                                        .catch(error => console.error('Error al verificar duplicado:', error));
                                }

                                $(document).ready(function() {
                                    $('#modalDuplicado').on('hidden.bs.modal', function() {
                                        window.location.href = '{{ route('presupuesto.index') }}';
                                    });
                                });
                            </script>

                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="observacion">Hoja de Vida Presupuesto (Registrar información para clarificar el
                                            estado del PRESUPUESTO)</label>
                                        <textarea name="observacion" class="form-control" rows="8"></textarea>
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

                            <script @cspNonce>
                                function formatNumber(input) {
                                    let value = input.value.replace(/\./g, '');
                                    if (!isNaN(value)) {
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

@endsection
