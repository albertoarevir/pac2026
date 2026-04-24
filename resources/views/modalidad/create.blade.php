@extends('layouts.admin')
@section('content')
    <div class="row" style="margin-left: 2px">
        <h2 style="font-size: 30px; color:rgb(218, 13, 13); margin-bottom: 3; margin-left: 35px;">
            ID Identificador del Proyecto N°: {{ $selectedPac ? str_pad($selectedPac->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}
        </h2>
    </div>
    <br>
    <div class="row" style="margin-left: 2px">
        <h2 style="font-size: 30px; color:rgb(14, 12, 12); margin-bottom: 3; margin-left: 35px;">
            <strong>Formulario de Ingreso de Licitaciones</strong>
        </h2>
    </div>
    <br>
    <div class="row">
        <div class="col-md-8" style="margin-left: 30px">
            <div class="card card-outline card-primary">

                <div class="card-body">
                    <form action="{{ url('/modalidad') }}" method="post">
                        @csrf
                        {{-- <input type="hidden" name="id_proyecto" value="{{ $pac->id }}"> --}}
                        <input type="hidden" name="id_proyecto" value={{ $selectedPac->id }}>
                        {{-- <input type="hidden" name="id_licitacion" value={{$selectedLic->id}}> --}}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Modalidad de Compra</label>
                                    <select name="modalidad" id="modalidad"
                                        class="form-control @error('modalidad') is-invalid @enderror" required>
                                        {{-- Añade la clase is-invalid para estilos de error de Bootstrap --}}
                                        <option value="">-- Ingrese Modalidad de Compra --</option>
                                        @foreach ($tipocompras as $tipocompra)
                                            <option value="{{ $tipocompra->detalle }}"
                                                {{ old('modalidad') == $tipocompra->detalle ? 'selected' : '' }}>
                                                {{ $tipocompra->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="numero">Número identificador</label>
                                    <input type="text" name="numero" id="numero" {{-- Mantiene el valor antiguo si hay error, o el valor de la base de datos si es edición --}}
                                        value="{{ old('numero', $modalidad->numero ?? '') }}" maxlength="15"
                                        {{-- Añade borde rojo automáticamente si hay un error de validación --}} class="form-control @error('numero') is-invalid @enderror"
                                        placeholder="Ej: ABC-123" required {{-- Lógica de limpieza en tiempo real --}}
                                        oninput="this.value = this.value.toUpperCase().replace(/\//g, '-').replace(/[^A-Z0-9-]/g, '')">

                                    @error('numero')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Estado del registro</label>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observacion">Observación (Registrar información para clarificar el estado
                                        del registro)</label>
                                    <textarea name="observacion" class="form-control" rows="8"></textarea>

                                </div>
                            </div>
                        </div>


                        <hr>
                        <div class="row">
                            <div class="col-md-9">
                                <a href="{{ url('modalidad/') }}" class="btn btn-success">Volver al listado de
                                    licitaciones</a>
                                <!-- <a href="{{ url('modalidad/create') }}" class="btn btn-secondary">Cancelar</a>-->
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
