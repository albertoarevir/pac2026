@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card card-outline card-primary" style="margin-left: 50px;">
            <div class="card-body badge-btn" >
                <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                    <strong>ID Identificador del Proyecto N°:</strong>
                </h2>
                <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                   {{ $pac_id ? str_pad($pac_id, 4, '0', STR_PAD_LEFT) : 'N/A' }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-outline card-primary" style="margin-left: 35px">
            <div class="card-body badge-btn">
                <h2 style="font-size: 18px; color:rgb(8, 8, 8)">
                   <strong> Modalidad de Compra:</strong>
                </h2>
                @if ($modalidad)
                <h2 style="font-size: 18px; color:rgb(8, 8, 8); margin-bottom: 3;">
                    {{ $modalidad }}
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
        <div class="card card-outline card-primary" style="margin-left: 35px">
            <div class="card-body badge-btn">
                <h2 style="font-size: 18px; color:rgb(5, 5, 5)">
                    <strong>N° identificador de Licitación:</strong>
                </h2>
                @if ($numero)
                    <h2 style="font-size: 18px; color:rgb(10, 10, 10); margin-bottom: 3;">
                        {{ $numero }}
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
<h2 style="font-size: 25px; margin-left: 35px;"><strong>Formulario de ingreso Ordenes de Compras/según Id de la licitación</strong></h2>
    <div class="row">
        <div class="col-md-9" style="margin-left: 30px">
            <div class="card card-outline card-primary">

                <div class="card-body">
                    <form action="{{ route('ordenes.store', ['pac_id' => $pac_id, 'id_mod' => $id_mod]) }}" method="post">
                        @csrf
                       {{-- <input type="hidden" name="id_proyecto" value="{{ $pac_id }}">
                        <input type="hidden" name="id_licitacion" value={{ $id_mod }}> --}}


                        <div class="row">
                            {{--<div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Modalidad de Compra</label>
                                    <select name="modalidad" id="modalidad"
                                        class="form-control @error('modalidad') is-invalid @enderror" required>
                                        <option value="">-- Ingrese Modalidad de Compra --</option>
                                        @foreach ($tipocompras as $tipocompra)
                                            <option value="{{ $tipocompra->detalle }}"
                                                {{ old('modalidad') == $tipocompra->detalle ? 'selected' : '' }}>
                                                {{ $tipocompra->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>--}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Número Orden de Compra</label>
                                    <input type="texto" value="{{ old('numero') }}" name="numero" maxlength="15"
                                        class="form-control" required pattern="^[A-Z0-9-]+$" 
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('numero')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Monto $$</label>
                                    <input type="numeric" id="monto" class="form-control" name="monto"
                                        value="{{ old('monto') }}" maxlength="15" oninput="formatNumber(this)"
                                        @error('monto') is-invalid @enderror required>
                                    @error('monto')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Estado Orden de Compra</label>
                                    <select name="estado_id" id="id"
                                        class="form-control @error('estado_id') is-invalid @enderror" required>
                                        <option value="">-- Ingrese el estado actual --</option>
                                        @foreach ($estadocompras as $estado)
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


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Fecha estimativa seguimiento</label>
                                    <input type="date" value="{{ old('fecha_seguimiento') }}" name="fecha_seguimiento"
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
                                    <textarea name="observacion" class="form-control" rows="5"></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                           



                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for=""></label>
                                    <input type="hidden" id="id_licitacion" class="form-control" name="id_licitacion"
                                        value={{ $id_mod }} readonly>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-9">
                                <a href="{{ url('ordenes/') }}" class="btn btn-success">Volver al listado de
                                    licitaciones</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2"></i> Guardar
                                    registro</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find('button[type="submit"]').prop('disabled', true).text('Procesando...');
            });
        });
    </script>
@endsection
