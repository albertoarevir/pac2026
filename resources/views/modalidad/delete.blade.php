@extends('layouts.admin')

@section('content')
    <div class="row">
        <h2  style="margin-left: 30px">Usuario: {{$usuario->email}}</h2>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-danger"  style="margin-left: 30px">
                <div class="card-header"> 
                    <h3 class="card-title">¿Esta seguro de eliminar este registro?</h3>

                </div>
                
                <div class="card-body">
                    <form action="{{ route('pac.destroy', $pac->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="col-md-12">    
                            <div class="row">   

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="year">Año:</label>
                                        <select class="form-control" name="year" id="year" @error('year') is-invalid @enderror required>
                                            <option value="">Seleccione un año</option> 
                                            @php
                                                $currentYear = date('Y');
                                                $years = range($currentYear, $currentYear - 5);
                                            @endphp
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ $pac->year == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Departamento</label>
                                        <select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror" required>
                                            <option value="">-- Ingrese Departamento --</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->detalle }}" {{ $pac->departamento == $departamento->detalle ? 'selected' : '' }}>
                                                    {{ $departamento->detalle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Especie</label>
                                        <select name="especie" id="especie" class="form-control @error('especie') is-invalid @enderror" required>
                                            <option value="">-- Ingrese la especie correspondiente --</option>
                                            @foreach($especies->sortBy('detalle') as $especie)
                                                <option value="{{ $especie->detalle }}" {{ $pac->especie == $especie->detalle ? 'selected' : '' }}>
                                                    {{ $especie->detalle }}
                                                </option>
                                            @endforeach
                                        </select>                                       
                                        @error('especie')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>                                                  
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Cantidad</label>
                                        <input type="number" value="{{ $pac->cantidad }}" name="cantidad" class="form-control" required>
                                        @error('cantidad')
                                        <small style="color: red">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Presupuesto</label>                              
                                    <input type="text" id="presupuesto" class="form-control" name="presupuesto" value="{{ number_format($pac->presupuesto, 0, ',', '.') }}" oninput="formatNumber(this)" @error('presupuesto') is-invalid @enderror required>
                                    @error('presupuesto')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>    

                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="">Clasificador Presupuestario</label>
                                    <select name="clasificador" id="clasificador" class="form-control" onchange="cargarCodigos()" @error('clasificador') is-invalid @enderror required>
                                        <option value="">-- Seleccione un clasificador --</option>
                                        @foreach ($clasificadors as $clasificador)
                                            <option value="{{ $clasificador->codigo_id }}" {{ $pac->clasificador == $clasificador->codigo_id ? 'selected' : '' }}>
                                                {{ $clasificador->codigo_id }} - {{ $clasificador->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('clasificador')
                                        <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>                                           
                            
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Codigo - Descripción</label>
                                    <select name="codigo_id" id="codigo" class="form-control" @error('codigo_id') is-invalid @enderror required>
                                        <option value="">Selecciona un clasificador primero</option>
                                        @if ($pac->codigos)
                                            <option value="{{ $pac->codigos }}" selected>
                                                {{ $pac->codigos }} - {{ optional(Codigo::where('codigopre', $pac->codigos)->first())->detalle }}
                                            </option>
                                        @endif
                                    </select>                                     
                                    @error('codigo')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>

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
                   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Estado del registro</label>
                                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                      <option value="">-- Ingrese el estado actual --</option>
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->detalle }}" {{ $pac->estado == $estado->detalle ? 'selected' : '' }}>
                                                {{ $estado->detalle }}
                                            </option>
                                        @endforeach
                                    </select>                                       
                                    @error('estado')
                                    <small style="color: red">{{$message}}</small>
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
                                                // Verificar si el código actual coincide con el valor guardado en el registro
                                                let selected = codigo.codigopre === "{{ $pac->codigo }}" ? 'selected' : '';
                                                codigoSelect.innerHTML += `<option value="${codigo.codigopre}" ${selected}>${codigo.codigopre} - ${codigo.detalle}</option>`;
                                            });
                                        })
                                        .catch(error => console.error('Error al cargar los códigos:', error));
                                } else {
                                    document.getElementById('codigo').innerHTML = '<option value="">Selecciona un clasificador primero</option>';
                                }
                            }
                        
                            // Llamar a la función cargarCodigos() al cargar la página para precargar los códigos si ya hay un clasificador seleccionado
                            document.addEventListener('DOMContentLoaded', function() {
                                let clasificadorId = document.getElementById('clasificador').value;
                                if (clasificadorId) {
                                    cargarCodigos();
                                }
                            });
                        </script>
       
                        <div class="row">
                            <div class="col-md-9">
                                
                                <div class="form-group">
                                    <label for="observacion">Observación (Registrar información para clarificar el estado del registro)</label>
                                    <textarea name="observacion" class="form-control" rows="8">{{ old('observacion', $pac->observaciones) }}</textarea>
                                </div>                               
                            </div>   
                        </div>
                        
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{url('pac/')}}" class="btn btn-success">Volver al listado</a>
                            <a href="{{url('pac/create')}}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Actualizar registro</button>
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
                            
                         


                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{url('admin/usuarios')}}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-danger"><i class="bi bi-floppy2"></i> Eliminar registro</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
