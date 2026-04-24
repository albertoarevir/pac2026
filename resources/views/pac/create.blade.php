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
                    <form action="{{ url('/pac') }}" method="post" onsubmit="removeCommas()">
                        @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year">Año Pac:</label>
                                        <select class="form-control" name = "year" id="year"
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
                                            @error('departamento') is-invalid @enderror required> {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
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
                                            {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
                                            <option value="">-- Ingrese la especie correspondiente --</option>
                                        </select>
                                        @error('especie')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <script>
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
                                                    console.log(
                                                        data
                                                    ); // Verifica que los datos estén siendo devueltos correctamente
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
                            </div>
                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Clasificador Presupuestario</label>
                                        <select name="clasificador" id="clasificador" class="form-control"
                                            onchange="cargarCodigos()" @error('clasificador') is-invalid @enderror"
                                            required>
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
                            </div>

                            <div class="row">

                                {{--
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Unidad de Compra</label>
                                        <select name="unidadcompra" id="unidadcompra" class="form-control @error('unidadcompra') is-invalid @enderror" required> {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
                                {{-- }}
                                            <option value="">-- Seleccione Unidad --</option>
                                          
                                            @foreach ($unidadcompras as $unidad)
                                                <option value="{{ $unidad->detalle }}" {{ old('unidad') == $unidad->detalle ? 'selected' : '' }}>
                                                    {{ $unidad->detalle }}
                                                </option>
                                            @endforeach
                                        </select>                                       
                                        @error('unidad')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                 --}}

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>

                                        <input type="text" id="cantidad" class="form-control" name="cantidad"
                                            value="{{ old('cantidad') }}" maxlength="7" {{-- oninput="formatNumber(this)" --}}
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                        @error('cantidad')
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fuente_financiamiento">Fuente de Financiamiento</label>
                                        <select name="fuente_financiamiento" id="fuente_financiamiento" class="form-control"
                                            @error('fuente_financiamiento') is-invalid @enderror required>
                                            <option value="">-- Ingrese Fuente de Financiamiento --</option>
                                            {{-- Usa la variable $fuente_financiamiento --}}
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
                                            {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
                                            <option value="">-- Ingrese el estado actual --</option>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}"
                                                    {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
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
                                                    // codigoSelect.innerHTML += `<option value="${codigo.id}">${codigo.codigopre} - ${codigo.detalle}</option>`;
                                                    // codigoSelect.innerHTML += `<option value="<span class="math-inline">\{codigo\.id\}"\</span>{codigo.codigopre} - ${codigo.detalle}</option>`;
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
                            </script>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="observacion">Observación (Registrar información para clarificar el
                                            estado del registro)</label>
                                        <textarea name="observacion" class="form-control" rows="8"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estado_modificacion">Ingreso inicial Pac y/o Modificación</label>
                                        {{-- Corrige el atributo 'name' y 'id' --}}
                                        <select name="estado_modificacion" id="estado_modificacion" class="form-control"
                                            @error('estado_modificacion') is-invalid @enderror required>
                                            <option value="">-- Ingrese Estado de Modificación --</option>
                                            {{-- Itera sobre la variable correcta y usa el alias correcto --}}
                                            @foreach ($estados_modificacion as $estado_modificacion)
                                                <option value="{{ $estado_modificacion->id }}" {{-- Corrige la validación para `old()` --}}
                                                    {{ old('estado_modificacion') == $estado_modificacion->id ? 'selected' : '' }}>
                                                    {{-- Muestra el valor de la columna 'detalle' --}}
                                                    {{ $estado_modificacion->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- Muestra el mensaje de error para 'estado_modificacion' --}}
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
                        <script>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
