@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mb-3 mt-3">📑 Bitácora General del Sistema</h3>

    <div class="card mb-4 shadow-sm">
        <div class="card-body bg-light">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Funcionario</label>
                    <input type="text" name="usuario" class="form-control form-control-sm" placeholder="Nombre..." value="{{ request('usuario') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Departamento</label>
                    <select name="departamento_id" class="form-control form-control-sm">
                        <option value="">-- Todos --</option>
                        @foreach($departamentos as $dep)
                            <option value="{{ $dep->id }}" {{ request('departamento_id') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->detalle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label small fw-bold">Acción</label>
                    <select name="accion" class="form-control form-control-sm">
                        <option value="">--</option>
                        <option value="crear" {{ request('accion') == 'crear' ? 'selected' : '' }}>Crear</option>
                        <option value="editar" {{ request('accion') == 'editar' ? 'selected' : '' }}>Editar</option>
                        <option value="eliminar" {{ request('accion') == 'eliminar' ? 'selected' : '' }}>Eliminar</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label small fw-bold">Proyecto</label>
                    <input type="text" name="proyecto_id" class="form-control form-control-sm" placeholder="ID" value="{{ request('proyecto_id') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Fecha Término</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">🔍 Filtrar</button>
                        <a href="{{ route('bitacora.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover w-100 bg-white">
            <thead class="table-dark text-center">
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Funcionario</th>
                    <th>Departamento</th>
                    <th>Módulo</th> 
                    <th>ID Proy.</th>
                    <th>Acción</th>
                    <th style="width: 20%;">Estado Anterior</th>
                    <th style="width: 20%;">Estado Modificado</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bitacoras as $b)
                <tr>
                    <td class="text-center small" style="white-space: nowrap;">{{ $b->created_at->format('d-m-Y H:i') }}</td>
                    <td class="small">{{ $b->user->name ?? 'Sistema' }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark" style="font-size: 0.75rem;">
                            {{ $b->user->departamento->detalle ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="text-center small">{{ $b->modulo }}</td>
                    <td class="text-center"><strong>{{ $b->proyecto_id ?? 'N/A' }}</strong></td>
                    <td class="text-center">
                        <span class="badge {{ $b->accion == 'crear' ? 'bg-success' : ($b->accion == 'editar' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ strtoupper($b->accion) }}
                        </span>
                    </td>
                    <td>
                        @if($b->campo_anterior)
                            <pre class="m-0 p-1 border shadow-sm" style="font-size: 12px; background: #fff5f5; max-height: 80px; overflow-y: auto;">{{ $b->campo_anterior }}</pre>
                        @endif
                    </td>
                    <td>
                        @if($b->campo_modificado)
                            <pre class="m-0 p-1 border shadow-sm" style="font-size: 12px; background: #f5fff5; max-height: 80px; overflow-y: auto;">{{ $b->campo_modificado }}</pre>
                        @endif
                    </td>
                    <td class="text-center small text-muted">{{ $b->ip }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   <div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">
        Mostrando {{ $bitacoras->firstItem() }} a {{ $bitacoras->lastItem() }} 
        de {{ $bitacoras->total() }} resultados
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
        

        <div class="pagination-sm">
            {{ $bitacoras->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>

</div>
</div>
@endsection