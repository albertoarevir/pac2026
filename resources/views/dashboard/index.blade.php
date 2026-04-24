@extends('layouts.admin')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<style>
/* ============================================================
   VARIABLES
============================================================ */
:root {
    --pri       : #1e3a8a;
    --pri-mid   : #2563eb;
    --pri-lt    : #dbeafe;
    --success   : #16a34a;
    --success-lt: #dcfce7;
    --warn      : #d97706;
    --warn-lt   : #fef3c7;
    --danger    : #dc2626;
    --danger-lt : #fee2e2;
    --neutral   : #64748b;
    --neutral-lt: #f8fafc;
    --border    : #e2e8f0;
    --shadow-sm : 0 1px 4px rgba(30,58,138,.07);
    --shadow    : 0 2px 12px rgba(30,58,138,.09);
    --shadow-lg : 0 4px 24px rgba(30,58,138,.13);
    --radius    : 12px;
    --radius-sm : 8px;
}

/* ============================================================
   LAYOUT BASE
============================================================ */
.db-wrap { padding: 6px 32px 48px; }

/* ============================================================
   ENCABEZADO
============================================================ */
.db-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 18px 0 10px;
    border-bottom: 2px solid var(--border);
    margin-bottom: 18px;
}
.db-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--pri);
    margin: 0;
    letter-spacing: -.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.db-title i { color: var(--pri-mid); }
.db-subtitle { font-size: 13px; color: var(--neutral); margin: 3px 0 0; }

/* ============================================================
   FILTROS
============================================================ */
.filtro-bar {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px 20px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 14px;
}
.filtro-group { display: flex; flex-direction: column; gap: 4px; }
.filtro-group label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--neutral);
}
.filtro-group select {
    font-size: 13px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 6px 10px;
    background: #fff;
    color: #1e293b;
    min-width: 180px;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.filtro-group select:focus {
    outline: none;
    border-color: var(--pri-mid);
    box-shadow: 0 0 0 3px rgba(37,99,235,.15);
}

/* ============================================================
   SECCIÓN
============================================================ */
.db-section {
    margin-bottom: 28px;
}
.db-section-title {
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--pri);
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.db-section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(to right, var(--pri-lt), transparent);
    border-radius: 2px;
}

/* ============================================================
   KPI CARDS
============================================================ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.kpi-card {
    background: #fff;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    padding: 18px 20px;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform .15s, box-shadow .15s;
    position: relative;
    overflow: hidden;
}
.kpi-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 12px 0 0 12px;
}
.kpi-card.blue::before   { background: var(--pri-mid); }
.kpi-card.green::before  { background: var(--success); }
.kpi-card.yellow::before { background: var(--warn); }
.kpi-card.red::before    { background: var(--danger); }
.kpi-card.gray::before   { background: var(--neutral); }
.kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }

.kpi-icon {
    width: 50px; height: 50px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.kpi-card.blue   .kpi-icon { background: var(--pri-lt);    color: var(--pri-mid); }
.kpi-card.green  .kpi-icon { background: var(--success-lt); color: var(--success); }
.kpi-card.yellow .kpi-icon { background: var(--warn-lt);   color: var(--warn); }
.kpi-card.red    .kpi-icon { background: var(--danger-lt);  color: var(--danger); }
.kpi-card.gray   .kpi-icon { background: #f1f5f9;           color: var(--neutral); }

.kpi-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--neutral);
    margin: 0 0 3px;
}
.kpi-value {
    font-size: 20px;
    font-weight: 800;
    color: var(--pri);
    margin: 0;
    line-height: 1.1;
}
.kpi-value.sm { font-size: 14px; }
.kpi-meta {
    font-size: 11px;
    color: var(--neutral);
    margin: 4px 0 0;
}

/* ============================================================
   SEMÁFORO KPI (general)
============================================================ */
.semaforo-kpi {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 20px;
    box-shadow: var(--shadow-sm);
}
.semaforo-dot {
    width: 18px; height: 18px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 8px 2px currentColor;
}
.dot-verde    { background: #22c55e; color: #22c55e; }
.dot-amarillo { background: #eab308; color: #eab308; }
.dot-rojo     { background: #ef4444; color: #ef4444; }
.semaforo-kpi-label { font-size: 12px; color: var(--neutral); }
.semaforo-kpi-estado { font-size: 14px; font-weight: 800; color: var(--pri); }

/* ============================================================
   CHART CARDS
============================================================ */
.chart-card {
    background: #fff;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    padding: 18px 20px 14px;
    height: 100%;
}
.chart-card-title {
    font-size: 12px;
    font-weight: 800;
    color: var(--pri);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin: 0 0 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--pri-lt);
    display: flex;
    align-items: center;
    gap: 7px;
}
.chart-card-title i { color: var(--pri-mid); font-size: 14px; }
.chart-wrap {
    position: relative;
    width: 100%;
    height: 300px;
}

/* ============================================================
   TABLA RESUMEN DEPARTAMENTOS
============================================================ */
.tabla-depto-wrap {
    background: #fff;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.tabla-depto-header {
    background: var(--pri);
    color: #fff;
    padding: 13px 20px;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .05em;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tabla-depto {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.tabla-depto thead th {
    background: #f8faff;
    color: var(--neutral);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    padding: 10px 16px;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
}
.tabla-depto thead th:not(:first-child) { text-align: right; }
.tabla-depto tbody td {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    color: #1e293b;
    vertical-align: middle;
}
.tabla-depto tbody td:not(:first-child) {
    text-align: right;
    font-variant-numeric: tabular-nums;
}
.tabla-depto tbody tr:nth-child(even) { background: #f9fbff; }
.tabla-depto tbody tr:hover { background: #eff4ff; transition: background .1s; }
.tabla-depto tfoot td {
    background: var(--pri);
    color: #fff;
    font-weight: 800;
    padding: 11px 16px;
    font-size: 13px;
}
.tabla-depto tfoot td:not(:first-child) { text-align: right; }

/* Barra de progreso */
.pbar-wrap { display: flex; align-items: center; gap: 8px; justify-content: flex-end; }
.pbar {
    width: 80px; height: 7px;
    background: var(--border);
    border-radius: 4px;
    overflow: hidden;
}
.pbar-fill { height: 100%; border-radius: 4px; }
.fill-g { background: var(--success); }
.fill-y { background: var(--warn); }
.fill-r { background: var(--danger); }
.pbar-pct { font-size: 11px; font-weight: 700; min-width: 40px; text-align: right; }
.pct-g { color: var(--success); }
.pct-y { color: var(--warn); }
.pct-r { color: var(--danger); }

/* Badge semáforo en tabla */
.badge-sema {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10.5px; font-weight: 700;
    padding: 3px 9px; border-radius: 20px;
}
.badge-verde   { background: var(--success-lt); color: var(--success); }
.badge-amarillo{ background: var(--warn-lt);   color: var(--warn); }
.badge-rojo    { background: var(--danger-lt);  color: var(--danger); }

/* ============================================================
   CRITERIOS SEMAFORIZACIÓN
============================================================ */
.criterios-grid {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.criterio-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid currentColor;
}
.criterio-pill.verde   { background: var(--success-lt); color: var(--success); }
.criterio-pill.amarillo{ background: var(--warn-lt);    color: var(--warn); }
.criterio-pill.rojo    { background: var(--danger-lt);  color: var(--danger); }
.criterio-dot { width: 10px; height: 10px; border-radius: 50%; background: currentColor; }
</style>

{{-- ============================================================
     WRAPPER
============================================================ --}}
<div class="db-wrap">

    {{-- ENCABEZADO --}}
    <div class="db-header">
        <div>
            <h1 class="db-title">
                <i class="bi bi-speedometer2"></i>
                Dashboard — Panel Administrativo
            </h1>
            <p class="db-subtitle">Dirección de Logística · Año <strong>{{ $selectedYear }}</strong></p>
        </div>
        <a href="{{ route('reporte.index') }}" style="text-decoration:none;
           display:inline-flex; align-items:center; gap:6px;
           background:var(--pri); color:#fff; border-radius:8px;
           padding:8px 16px; font-size:13px; font-weight:700;
           transition:background .2s;"
           onmouseover="this.style.background='#1d4ed8'"
           onmouseout="this.style.background='var(--pri)'">
            <i class="bi bi-table"></i> Ver Reporte Detallado
        </a>
    </div>

    {{-- FILTROS --}}
    <form action="{{ route('dashboard') }}" method="GET">
        <div class="filtro-bar">
            <div class="filtro-group">
                <label><i class="bi bi-calendar3 me-1"></i>Año</label>
                <select name="year" onchange="this.form.submit()">
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" {{ (string)$year === (string)$selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filtro-group">
                <label><i class="bi bi-building me-1"></i>Departamento</label>
                <select name="departamento_id" onchange="this.form.submit()">
                    @foreach ($availableDepartmentsForSelect as $departamento)
                        <option value="{{ $departamento->id }}"
                            {{ (string)$departamento->id === (string)$selectedDepartamentoId ? 'selected' : '' }}>
                            {{ $departamento->detalle }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- ============================================================
         SECCIÓN 1 — KPIs GENERALES
    ============================================================ --}}
    @php
        $pct      = $total_porcentaje_ejecucion_general;
        $saldo    = $total_presupuesto_general - $total_comprometido_general;
        $kpiColor = $pct > 75 ? 'green' : ($pct >= 60 ? 'yellow' : 'red');
        $estadoTexto = $pct > 75 ? 'Ejecución Aceptable' : ($pct >= 60 ? 'Ejecución Media' : 'Baja Ejecución');
        $dotClass    = $pct > 75 ? 'dot-verde' : ($pct >= 60 ? 'dot-amarillo' : 'dot-rojo');
        $badgeClass  = $pct > 75 ? 'badge-verde' : ($pct >= 60 ? 'badge-amarillo' : 'badge-rojo');
    @endphp

    <div class="db-section">
        <div class="db-section-title"><i class="bi bi-bar-chart-fill"></i> 1. Resumen General de Ejecución Presupuestaria</div>

        {{-- Criterios --}}
        <div class="criterios-grid">
            <span class="criterio-pill verde"><span class="criterio-dot"></span>Mayor a 75% → Ejecución Aceptable</span>
            <span class="criterio-pill amarillo"><span class="criterio-dot"></span>Entre 60% y 74% → Ejecución Media</span>
            <span class="criterio-pill rojo"><span class="criterio-dot"></span>Menor a 60% → Baja Ejecución</span>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card blue">
                <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <p class="kpi-label">Total Presupuesto</p>
                    <p class="kpi-value sm">$ {{ number_format($total_presupuesto_general, 0, ',', '.') }}</p>
                    <p class="kpi-meta"><a href="{{ url('/pac') }}" style="color:var(--pri-mid);">Ver detalle →</a></p>
                </div>
            </div>

            <div class="kpi-card green">
                <div class="kpi-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <p class="kpi-label">Total Comprometido</p>
                    <p class="kpi-value sm">$ {{ number_format($total_comprometido_general, 0, ',', '.') }}</p>
                    <p class="kpi-meta"><a href="{{ url('/pac') }}" style="color:var(--success);">Ver detalle →</a></p>
                </div>
            </div>

            <div class="kpi-card {{ $saldo >= 0 ? 'blue' : 'red' }}">
                <div class="kpi-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <p class="kpi-label">Saldo Disponible</p>
                    <p class="kpi-value sm" style="{{ $saldo < 0 ? 'color:var(--danger)' : '' }}">
                        $ {{ number_format($saldo, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="kpi-card {{ $kpiColor }}">
                <div class="kpi-icon"><i class="bi bi-percent"></i></div>
                <div>
                    <p class="kpi-label">% Ejecución</p>
                    <p class="kpi-value">{{ $pct }}%</p>
                </div>
            </div>

            {{-- Semáforo general --}}
            <div class="semaforo-kpi" style="grid-column: span 1;">
                <span class="semaforo-dot {{ $dotClass }}"></span>
                <div>
                    <p class="semaforo-kpi-label">Estado KPI General</p>
                    <p class="semaforo-kpi-estado">{{ $estadoTexto }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 2 — PROYECTOS
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title"><i class="bi bi-clipboard-data"></i> 2. Proyectos por Departamento</div>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="chart-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="chart-card-title"><i class="bi bi-list-task"></i>Total Proyectos</div>
                        <div style="text-align:center; padding: 10px 0;">
                            <div style="font-size:54px; font-weight:900; color:var(--pri); line-height:1;">
                                {{ number_format($total_proyectos_registrados, 0, ',', '.') }}
                            </div>
                            <p style="font-size:12px; color:var(--neutral); margin:6px 0 14px;">registros en {{ $selectedYear }}</p>
                            <a href="{{ url('pac') }}" style="
                                display:inline-block; background:var(--pri-mid); color:#fff;
                                border-radius:8px; padding:7px 18px; font-size:13px;
                                font-weight:700; text-decoration:none;">
                                <i class="bi bi-eye me-1"></i>Ver Proyectos
                            </a>
                        </div>
                    </div>
                    @if($ultima_actualizacion_pac)
                        <p style="font-size:11px; color:var(--neutral); margin:14px 0 0; border-top:1px solid var(--border); padding-top:10px;">
                            <i class="bi bi-clock me-1"></i>
                            Última actualización:
                            <strong>{{ $ultima_actualizacion_pac->updated_at->format('d/m/Y H:i') }}</strong>
                            — {{ $ultima_actualizacion_pac->departamento->detalle }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="col-md-9">
                <div class="chart-card">
                    <div class="chart-card-title"><i class="bi bi-bar-chart"></i>Cantidad de Proyectos por Departamento</div>
                    <div class="chart-wrap">
                        <canvas id="proyectosPorDepartamentoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 3 — PRESUPUESTO vs COMPROMETIDO
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title"><i class="bi bi-bar-chart-line"></i> 3. Presupuesto Asignado vs Total Devengado por Departamento</div>
        <div class="chart-card">
            <div class="chart-card-title"><i class="bi bi-bar-chart-fill"></i>Comparativa por Departamento</div>
            <div class="chart-wrap" style="height:320px;">
                <canvas id="chart2"></canvas>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 4 — LICITACIONES Y ÓRDENES
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title"><i class="bi bi-file-earmark-text"></i> 4. Licitaciones y Órdenes de Compra</div>
        <div class="row g-3 mb-3">

            {{-- Card licitaciones totales --}}
            <div class="col-md-4">
                <div class="chart-card h-100 d-flex flex-column justify-content-between">
                    <div class="chart-card-title"><i class="bi bi-file-earmark-text"></i>Licitaciones Registradas</div>
                    <div style="text-align:center; padding:8px 0;">
                        <div style="font-size:52px; font-weight:900; color:var(--pri-mid); line-height:1;">
                            {{ number_format($total_licitaciones_registradas, 0, ',', '.') }}
                        </div>
                        <p style="font-size:12px; color:var(--neutral); margin:6px 0 14px;">licitaciones en {{ $selectedYear }}</p>
                        <a href="{{ url('modalidad') }}" style="
                            display:inline-block; background:var(--pri-mid); color:#fff;
                            border-radius:8px; padding:7px 18px; font-size:13px;
                            font-weight:700; text-decoration:none;">
                            <i class="bi bi-eye me-1"></i>Ver Licitaciones
                        </a>
                    </div>
                    <div style="display:flex; gap:10px; margin-top:14px; border-top:1px solid var(--border); padding-top:12px;">
                        <div style="flex:1; text-align:center; background:var(--danger-lt); border-radius:8px; padding:8px;">
                            <div style="font-size:22px; font-weight:800; color:var(--danger);">
                                {{ number_format($total_licitaciones_sin_ordenes, 0, ',', '.') }}
                            </div>
                            <div style="font-size:10px; color:var(--danger); font-weight:700;">Sin O.C. asociada</div>
                        </div>
                        <div style="flex:1; text-align:center; background:var(--success-lt); border-radius:8px; padding:8px;">
                            <div style="font-size:22px; font-weight:800; color:var(--success);">
                                {{ number_format($total_licitaciones_registradas - $total_licitaciones_sin_ordenes, 0, ',', '.') }}
                            </div>
                            <div style="font-size:10px; color:var(--success); font-weight:700;">Con O.C. asociada</div>
                        </div>
                    </div>
                    @if($ultima_actualizacion_licitacion)
                        <p style="font-size:11px; color:var(--neutral); margin:10px 0 0;">
                            <i class="bi bi-clock me-1"></i>Últ. actualización:
                            <strong>{{ $ultima_actualizacion_licitacion->updated_at->format('d/m/Y H:i') }}</strong>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Gráfico licitaciones por estado --}}
            <div class="col-md-4">
                <div class="chart-card h-100">
                    <div class="chart-card-title"><i class="bi bi-pie-chart"></i>Estado de Licitaciones</div>
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="licitacionesEstadoChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Gráfico órdenes por estado --}}
            <div class="col-md-4">
                <div class="chart-card h-100">
                    <div class="chart-card-title"><i class="bi bi-pie-chart-fill"></i>Estado Órdenes de Compra</div>
                    <div style="text-align:center; margin-bottom:10px;">
                        <span style="font-size:28px; font-weight:900; color:var(--pri);">
                            {{ number_format($total_ordenes_registradas, 0, ',', '.') }}
                        </span>
                        <span style="font-size:12px; color:var(--neutral); margin-left:6px;">órdenes</span>
                        <div style="margin-top:4px;">
                            <a href="{{ url('ordenes') }}" style="font-size:11px; color:var(--pri-mid); font-weight:700;">
                                Ver todas →
                            </a>
                        </div>
                    </div>
                    <div class="chart-wrap" style="height:210px;">
                        <canvas id="comprasEstadoChart"></canvas>
                    </div>
                    @if($ultima_actualizacion_orden)
                        <p style="font-size:11px; color:var(--neutral); margin:8px 0 0;">
                            <i class="bi bi-clock me-1"></i>Últ. actualización:
                            <strong>{{ $ultima_actualizacion_orden->updated_at->format('d/m/Y H:i') }}</strong>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 5 — TABLA RESUMEN POR DEPARTAMENTO
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title"><i class="bi bi-table"></i> 5. Resumen por Departamento</div>

        @if(count($datos_departamentos) > 0)
        <div class="tabla-depto-wrap">
            <div class="tabla-depto-header">
                <i class="bi bi-building"></i> Detalle de ejecución por Departamento — {{ $selectedYear }}
            </div>
            <div style="overflow-x:auto;">
                <table class="tabla-depto">
                    <thead>
                        <tr>
                            <th>Departamento</th>
                            <th>Presupuesto</th>
                            <th>Comprometido</th>
                            <th>Saldo</th>
                            <th>% Ejecución</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datos_departamentos as $dato)
                            @php
                                $p   = $dato['porcentaje'];
                                $cl  = $p > 75 ? 'g' : ($p >= 60 ? 'y' : 'r');
                                $saldoDepto = $dato['presupuesto'] - $dato['comprometido'];
                                $badgeDepto = $p > 75 ? 'badge-verde' : ($p >= 60 ? 'badge-amarillo' : 'badge-rojo');
                                $estadoDepto = $p > 75 ? 'Aceptable' : ($p >= 60 ? 'Media' : 'Baja');
                            @endphp
                            <tr>
                                <td><strong>{{ $dato['departamento']->detalle }}</strong></td>
                                <td>$ {{ number_format($dato['presupuesto'], 0, ',', '.') }}</td>
                                <td>$ {{ number_format($dato['comprometido'], 0, ',', '.') }}</td>
                                <td style="{{ $saldoDepto < 0 ? 'color:var(--danger);font-weight:700;' : '' }}">
                                    $ {{ number_format($saldoDepto, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="pbar-wrap">
                                        <div class="pbar">
                                            <div class="pbar-fill fill-{{ $cl }}"
                                                 style="width:{{ min($p, 100) }}%"></div>
                                        </div>
                                        <span class="pbar-pct pct-{{ $cl }}">{{ $p }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-sema {{ $badgeDepto }}">
                                        <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                                        {{ $estadoDepto }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $totalPto  = collect($datos_departamentos)->sum('presupuesto');
                        $totalComp = collect($datos_departamentos)->sum('comprometido');
                        $totalSaldo = $totalPto - $totalComp;
                        $totalPct  = $totalPto > 0 ? round(($totalComp / $totalPto) * 100, 2) : 0;
                    @endphp
                    <tfoot>
                        <td>TOTAL GENERAL</td>
                        <td>$ {{ number_format($totalPto, 0, ',', '.') }}</td>
                        <td>$ {{ number_format($totalComp, 0, ',', '.') }}</td>
                        <td>$ {{ number_format($totalSaldo, 0, ',', '.') }}</td>
                        <td colspan="2">{{ $totalPct }}%</td>
                    </tfoot>
                </table>
            </div>
        </div>
        @else
            <div style="text-align:center; padding:2rem; color:var(--neutral); background:#fff;
                        border-radius:var(--radius); border:1px solid var(--border);">
                <i class="bi bi-inbox" style="font-size:2rem; display:block; margin-bottom:.5rem;"></i>
                No hay datos disponibles para los filtros aplicados.
            </div>
        @endif
    </div>

</div>{{-- /db-wrap --}}

{{-- ============================================================
     SCRIPTS GRÁFICOS
============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.register(ChartDataLabels);

    const PALETA = [
        'rgba(37,99,235,.75)',  'rgba(22,163,74,.75)',  'rgba(217,119,6,.75)',
        'rgba(220,38,38,.75)',  'rgba(124,58,237,.75)', 'rgba(14,165,233,.75)',
        'rgba(234,88,12,.75)',  'rgba(99,102,241,.75)', 'rgba(20,184,166,.75)',
        'rgba(236,72,153,.75)',
    ];

    // ----------------------------------------------------------
    // 1. BARRAS: Presupuesto vs Comprometido
    // ----------------------------------------------------------
    const labelsChart2        = @json(array_map(fn($d) => $d['departamento']->detalle, $datos_departamentos));
    const presupuestosChart2  = @json(array_map(fn($d) => $d['presupuesto'],  $datos_departamentos));
    const comprometidosChart2 = @json(array_map(fn($d) => $d['comprometido'], $datos_departamentos));

    new Chart(document.getElementById('chart2').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labelsChart2,
            datasets: [
                {
                    label: 'Presupuesto Inicial',
                    data: presupuestosChart2,
                    backgroundColor: 'rgba(37,99,235,.7)',
                    borderColor: 'rgba(37,99,235,1)',
                    datalabels: {
                        anchor: 'end', align: 'end',
                        color: '#1e3a8a',
                        font: { weight: 'bold', size: 10 },
                        formatter: v => '$ ' + Number(v).toLocaleString('es-CL')
                    }
                },
                {
                    label: 'Total Devengado',
                    data: comprometidosChart2,
                    backgroundColor: 'rgba(22,163,74,.7)',
                    borderColor: 'rgba(22,163,74,1)',
                    datalabels: {
                        anchor: 'end', align: 'end',
                        color: '#166534',
                        font: { weight: 'bold', size: 10 },
                        formatter: v => '$ ' + Number(v).toLocaleString('es-CL')
                    }
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            layout: { padding: { top: 28 } },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 } } },
                datalabels: { display: true },
                tooltip: {
                    callbacks: {
                        label: ctx => ' $ ' + Number(ctx.raw).toLocaleString('es-CL')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 10 },
                        callback: v => '$ ' + Number(v).toLocaleString('es-CL')
                    }
                },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // ----------------------------------------------------------
    // 2. BARRAS: Proyectos por Departamento
    // ----------------------------------------------------------
    const labelsProyectos = @json($chartProyectosLabels);
    const dataProyectos   = @json($chartProyectosData);
    const maxProy = Math.max(...dataProyectos, 0);

    new Chart(document.getElementById('proyectosPorDepartamentoChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labelsProyectos,
            datasets: [{
                label: 'Proyectos',
                data: dataProyectos,
                backgroundColor: PALETA,
                borderRadius: 4,
                datalabels: {
                    anchor: 'end', align: 'end',
                    color: '#1e293b',
                    font: { weight: 'bold', size: 12 },
                    formatter: v => v
                }
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            layout: { padding: { top: 24 } },
            plugins: {
                legend: { display: false },
                datalabels: { anchor: 'end', align: 'end', font: { weight: 'bold' } }
            },
            scales: {
                y: { beginAtZero: true, max: maxProy + 2, ticks: { precision: 0, font: { size: 10 } } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // ----------------------------------------------------------
    // 3. DONA: Estado Licitaciones
    // ----------------------------------------------------------
    const labelsLic = @json($chartLicitacionLabels);
    const dataLic   = @json($chartLicitacionData);

    new Chart(document.getElementById('licitacionesEstadoChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labelsLic,
            datasets: [{ data: dataLic, backgroundColor: PALETA, borderWidth: 1 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } },
                datalabels: {
                    color: '#fff', font: { weight: 'bold', size: 11 },
                    formatter: (v, ctx) => {
                        const t = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return t > 0 ? Math.round(v / t * 100) + '%' : '';
                    }
                }
            }
        }
    });

    // ----------------------------------------------------------
    // 4. DONA: Estado Órdenes de Compra
    // ----------------------------------------------------------
    const labelsOC = @json($chartLabels);
    const dataOC   = @json($chartData);

    new Chart(document.getElementById('comprasEstadoChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labelsOC,
            datasets: [{ data: dataOC, backgroundColor: PALETA, borderWidth: 1 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } },
                datalabels: {
                    color: '#fff', font: { weight: 'bold', size: 11 },
                    formatter: (v, ctx) => {
                        const t = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return t > 0 ? Math.round(v / t * 100) + '%' : '';
                    }
                }
            }
        }
    });
});
</script>

@endsection
