@extends('layouts.admin')

@section('content')
    <div class="content" style="margin-left: 20px">
        <h2>Actualizar Clasificador</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><b>Modifique los datos correspondientes</b></h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('/codigos', $codigos->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="codigo_id">Código Presupuestario (Base)</label>
                                        <select name="codigo_id" id="codigo_id"
                                            class="form-control @error('codigo_id') is-invalid @enderror" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach ($clasificadors as $clasificador)
                                                <option value="{{ $clasificador->codigo_id }}"
                                                    {{ old('codigo_id', $codigos->codigo_id) == $clasificador->codigo_id ? 'selected' : '' }}>
                                                    {{ $clasificador->codigo_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('codigo_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="codigopre">Identificador del Código</label>
                                        <input type="text" name="codigopre" id="codigopre"
                                            value="{{ old('codigopre', $codigos->codigopre) }}"
                                            class="form-control @error('codigopre') is-invalid @enderror" maxlength="20"
                                            required {{-- Se agregó \. a la regex de JavaScript --}}
                                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\.-]/g, '')">
                                        @error('codigopre')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="detalle">Descripción del Clasificador</label>
                                        <input type="text" name="detalle" id="detalle"
                                            value="{{ old('detalle', $codigos->detalle) }}"
                                            class="form-control @error('detalle') is-invalid @enderror" required>
                                        @error('detalle')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ url('codigos') }}" class="btn btn-secondary">
                                        <i class="bi bi-box-arrow-in-left"></i> Volver
                                    </a>
                                    <button type="submit" class="btn btn-primary">Actualizar Registro</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
