@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12" style="margin-left: 10px">
            <h2 style="font-size: 30px; color:rgb(9, 9, 9); margin-bottom: 3; margin-left: 7px;">
                <strong>Bitácora General del Sistema</strong>
            </h2>
            <br>
            <div class="card card-outline card-primary">
                <div class="card-body" style="font-size: 18px; color:rgb(12, 13, 14); margin-bottom: 0;">

                    <div class="card mb-4 shadow-sm">
                        <div class="card-body bg-light">
                            <form method="GET" action="{{ route('bitacora.index') }}" class="row g-2 align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Funcionario</label>
                                    <input type="text" name="usuario" class="form-control form-control-sm"
                                        placeholder="Nombre..." value="{{ request('usuario') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Departamento</label>
                                    <select name="departamento_id" class="form-control form-control-sm">
                                        <option value="">-- Todos --</option>
                                        @foreach($departamentos as $dep)
                                            <option value="{{ $dep->id }}"
                                                {{ request('departamento_id') == $dep->id ? 'selected' : '' }}>
                                                {{ $dep->detalle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label small fw-bold">Acción</label>
                                    <select name="accion" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        <option value="login"    {{ request('accion') == 'login'    ? 'selected' : '' }}>Login</option>
                                        <option value="logout"   {{ request('accion') == 'logout'   ? 'selected' : '' }}>Logout</option>
                                        <option value="crear"    {{ request('accion') == 'crear'    ? 'selected' : '' }}>Crear</option>
                                        <option value="editar"   {{ request('accion') == 'editar'   ? 'selected' : '' }}>Editar</option>
                                        <option value="eliminar" {{ request('accion') == 'eliminar' ? 'selected' : '' }}>Eliminar</option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label small fw-bold">Proyecto</label>
                                    <input type="text" name="proyecto_id" class="form-control form-control-sm"
                                        placeholder="ID" value="{{ request('proyecto_id') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control form-control-sm"
                                        value="{{ request('fecha_inicio') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Fecha Término</label>
                                    <input type="date" name="fecha_fin" class="form-control form-control-sm"
                                        value="{{ request('fecha_fin') }}">
                                </div>

                                <div class="col-md-2">
                                    <div class="d-flex gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm flex-fill">🔍 Filtrar</button>
                                        <a href="{{ route('bitacora.index') }}"
                                            class="btn btn-outline-secondary btn-sm flex-fill">Limpiar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="bitacoraTable" class="table table-striped table-hover table-bordered" style="width: 100%;">
                        <thead style="background-color: #206113">
                            <tr>
                                <th style="color: #fff; white-space: nowrap;">Fecha / Hora</th>
                                <th style="color: #fff;">Funcionario</th>
                                <th style="color: #fff;">Departamento</th>
                                <th style="color: #fff;">Módulo</th>
                                <th style="color: #fff; text-align: center;">ID Proy.</th>
                                <th style="color: #fff; text-align: center;">Acción</th>
                                <th style="color: #fff; width: 20%;">Estado Anterior</th>
                                <th style="color: #fff; width: 20%;">Estado Modificado</th>
                                <th style="color: #fff; text-align: center;">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bitacoras as $b)
                            <tr>
                                <td class="text-center small" style="white-space: nowrap;">
                                    {{ $b->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="small">{{ $b->user->name ?? 'Sistema' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark" style="font-size: 0.75rem;">
                                        {{ $b->user->departamento->detalle ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center small">{{ $b->modulo }}</td>
                                <td class="text-center"><strong>{{ $b->proyecto_id ?? 'N/A' }}</strong></td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = match(strtolower($b->accion)) {
                                            'login'    => 'bg-primary',
                                            'logout'   => 'bg-secondary',
                                            'crear'    => 'bg-success',
                                            'editar'   => 'bg-warning text-dark',
                                            'eliminar' => 'bg-danger',
                                            default    => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
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

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Mostrando {{ $bitacoras->firstItem() }} a {{ $bitacoras->lastItem() }}
                            de {{ $bitacoras->total() }} resultados
                        </div>
                        <div class="pagination-sm">
                            {{ $bitacoras->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                    <script @cspNonce>
                        $(function () {
                            var table = $("#bitacoraTable").DataTable({
                                "paging":       false,
                                "info":         false,
                                "searching":    false,
                                "ordering":     true,
                                "responsive":   true,
                                "autoWidth":    false,
                                "language": {
                                    "emptyTable":    "No hay información",
                                    "infoEmpty":     "Mostrando 0 a 0 de 0 Registros",
                                    "infoFiltered":  "(Filtrado de _MAX_ total Registros)",
                                    "thousands":     ",",
                                    "loadingRecords":"Cargando...",
                                    "processing":    "Procesando...",
                                    "zeroRecords":   "Sin resultados encontrados",
                                    "paginate": {
                                        "first":    "Primero",
                                        "last":     "Ultimo",
                                        "next":     "Siguiente",
                                        "previous": "Anterior"
                                    }
                                },
                                "buttons": [{
                                    extend: 'collection',
                                    text: 'Reportes',
                                    orientation: 'landscape',
                                    buttons: [{
                                        extend: 'excel',
                                        title: 'Bitacora General del Sistema',
                                        excelNumberFormat: '#.##0.000'
                                    }, {
                                        text: 'Imprimir',
                                        extend: 'print'
                                    }]
                                }]
                            });

                            table.buttons().container().appendTo('#bitacoraTable_wrapper .col-md-6:eq(0)');
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection
