@extends('layouts.admin')

@push('styles')
<style>
:root {
    --bg-page   : #3a3a4d;
    --bg-card   : #1e1e38;
    --bg-card2  : #252547;
    --pri       : #00d4ff;
    --pri-mid   : #00b8d9;
    --pri-lt    : rgba(0,212,255,.12);
    --success   : #00e5a0;
    --success-lt: rgba(0,229,160,.12);
    --warn      : #ffb800;
    --warn-lt   : rgba(255,184,0,.12);
    --danger    : #ff4d6d;
    --danger-lt : rgba(255,77,109,.12);
    --orange    : #ff7d3b;
    --orange-lt : rgba(255,125,59,.12);
    --neutral   : #6b6b8d;
    --text      : #ffffff;
    --text-dim  : #8888aa;
    --border    : rgba(255,255,255,.06);
    --shadow-sm : 0 2px 12px rgba(0,0,0,.4);
    --shadow    : 0 6px 28px rgba(0,0,0,.6);
    --radius    : 14px;
    --radius-sm : 8px;
}
* { box-sizing:border-box; }
.db-wrap {
    padding:6px 32px 48px;
    background:var(--bg-page);
    min-height:100vh;
    color:var(--text);
}
.db-header {
    display:flex; align-items:flex-end; justify-content:space-between;
    flex-wrap:wrap; gap:12px; padding:18px 0 14px;
    border-bottom:1px solid var(--border); margin-bottom:20px;
}
.db-title {
    font-size:22px; font-weight:700; color:var(--text);
    margin:0; letter-spacing:.01em; display:flex; align-items:center; gap:10px;
}
.db-title i { color:var(--pri); }
.db-subtitle { font-size:14px; color:var(--text-dim); margin:4px 0 0; }
.filtro-bar {
    background:var(--bg-card); border:1px solid var(--border);
    border-radius:var(--radius); padding:14px 20px;
    margin-bottom:20px; box-shadow:var(--shadow-sm);
    display:flex; align-items:flex-end; flex-wrap:wrap; gap:14px;
}
.filtro-group { display:flex; flex-direction:column; gap:4px; }
.filtro-group label {
    font-size:14px; font-weight:700; text-transform:uppercase;
    letter-spacing:.08em; color:var(--text-dim);
}
.filtro-group select {
    font-size:15px; border:1px solid rgba(255,255,255,.1);
    border-radius:var(--radius-sm); padding:7px 12px;
    background:var(--bg-card2); color:var(--text); min-width:180px;
    cursor:pointer; transition:border-color .2s;
    appearance:auto;
}
.filtro-group select:focus {
    outline:none; border-color:var(--pri);
    box-shadow:0 0 0 3px rgba(0,212,255,.15);
}
.db-section { margin-bottom:28px; }
.db-section-title {
    font-size:15px; font-weight:700; text-transform:uppercase;
    letter-spacing:.12em; color:var(--text); margin:0 0 14px;
    display:flex; align-items:center; gap:10px;
}
.db-section-title::after {
    content:""; flex:1; height:1px;
    background:var(--border); border-radius:2px;
}
/* ── KPI cards ─────────────────────────────────────────── */
.kpi-grid {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px;
}
.kpi-card {
    background:var(--bg-card); border-radius:var(--radius);
    border:1px solid var(--border); padding:20px 22px 18px;
    box-shadow:var(--shadow-sm);
    display:flex; align-items:center; gap:16px;
    transition:transform .2s,box-shadow .2s,border-color .2s;
    position:relative; overflow:hidden;
}
.kpi-card::after {
    content:""; position:absolute; top:0; left:0; right:0;
    height:2px; border-radius:14px 14px 0 0; opacity:.85;
}
.kpi-card.blue::after   { background:linear-gradient(to right,var(--pri),#6366f1); }
.kpi-card.green::after  { background:linear-gradient(to right,var(--success),#3b82f6); }
.kpi-card.yellow::after { background:linear-gradient(to right,var(--warn),var(--orange)); }
.kpi-card.red::after    { background:linear-gradient(to right,var(--danger),#c026d3); }
.kpi-card.orange::after { background:linear-gradient(to right,var(--orange),var(--warn)); }
.kpi-card::before { display:none; }
.kpi-card:hover {
    transform:translateY(-3px); box-shadow:var(--shadow);
    border-color:rgba(255,255,255,.14);
}
.kpi-icon {
    width:46px; height:46px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; flex-shrink:0;
}
.kpi-card.blue   .kpi-icon { background:var(--pri-lt);    color:var(--pri); }
.kpi-card.green  .kpi-icon { background:var(--success-lt); color:var(--success); }
.kpi-card.yellow .kpi-icon { background:var(--warn-lt);   color:var(--warn); }
.kpi-card.red    .kpi-icon { background:var(--danger-lt);  color:var(--danger); }
.kpi-card.orange .kpi-icon { background:var(--orange-lt);  color:var(--orange); }
.kpi-label {
    font-size:14px; font-weight:700; text-transform:uppercase;
    letter-spacing:.1em; color:var(--text-dim); margin:0 0 5px;
}
.kpi-value { font-size:30px; font-weight:800; color:var(--text); margin:0; line-height:1; }
.kpi-meta  { font-size:15px; color:var(--text-dim); margin:5px 0 0; }
/* ── Chart cards ────────────────────────────────────────── */
.chart-card {
    background:var(--bg-card); border-radius:var(--radius);
    border:1px solid var(--border); box-shadow:var(--shadow-sm);
    padding:18px 20px 14px;
}
.chart-card-title {
    font-size:15px; font-weight:700; color:var(--text-dim); text-transform:uppercase;
    letter-spacing:.1em; margin:0 0 14px; padding-bottom:10px;
    border-bottom:1px solid var(--border); display:flex; align-items:center; gap:7px;
}
.chart-card-title i { color:var(--pri); font-size:14px; }
.chart-wrap { position:relative; width:100%; height:300px; }
/* ── Tabla depto ─────────────────────────────────────────── */
.tabla-depto-wrap {
    background:var(--bg-card); border-radius:var(--radius);
    border:1px solid var(--border); box-shadow:var(--shadow-sm); overflow:hidden;
}
.tabla-depto-header {
    background:rgba(0,212,255,.07); color:var(--pri); padding:12px 20px;
    font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.1em;
    display:flex; align-items:center; gap:8px; border-bottom:1px solid var(--border);
}
.tabla-depto { width:100%; border-collapse:collapse; font-size:15px; }
.tabla-depto thead th {
    background:rgba(255,255,255,.03); color:var(--text-dim); font-size:14px;
    font-weight:700; text-transform:uppercase; letter-spacing:.06em;
    padding:10px 16px; border-bottom:1px solid var(--border); white-space:nowrap;
}
.tabla-depto thead th:not(:first-child) { text-align:right; }
.tabla-depto tbody td {
    padding:10px 16px; border-bottom:1px solid var(--border);
    color:var(--text); vertical-align:middle;
}
.tabla-depto tbody td:not(:first-child) { text-align:right; font-variant-numeric:tabular-nums; }
.tabla-depto tbody tr:nth-child(even) { background:rgba(255,255,255,.02); }
.tabla-depto tbody tr:hover { background:rgba(0,212,255,.04); transition:background .12s; }
.tabla-depto tfoot td {
    background:rgba(0,212,255,.08); color:var(--pri);
    font-weight:800; padding:11px 16px; font-size:15px;
    border-top:1px solid rgba(0,212,255,.15);
}
.tabla-depto tfoot td:not(:first-child) { text-align:right; }
/* ── Progress bars ──────────────────────────────────────── */
.pbar-wrap { display:flex; align-items:center; gap:8px; justify-content:flex-end; }
.pbar {
    width:80px; height:5px; border-radius:4px; overflow:hidden;
    background:rgba(255,255,255,.08);
}
.pbar-fill { height:100%; border-radius:4px; }
.fill-g { background:linear-gradient(to right,var(--success),var(--pri)); }
.fill-y { background:linear-gradient(to right,var(--warn),var(--orange)); }
.fill-r { background:linear-gradient(to right,var(--danger),#c026d3); }
.pbar-pct { font-size:14px; font-weight:700; min-width:46px; text-align:right; }
.pct-g { color:var(--success); }
.pct-y { color:var(--warn); }
.pct-r { color:var(--danger); }
/* ── Badges ─────────────────────────────────────────────── */
.badge-sema {
    display:inline-flex; align-items:center; gap:5px;
    font-size:15px; font-weight:700; padding:3px 10px; border-radius:20px;
}
.badge-verde    { background:var(--success-lt); color:var(--success); }
.badge-amarillo { background:var(--warn-lt);    color:var(--warn); }
.badge-rojo     { background:var(--danger-lt);  color:var(--danger); }
/* ── Tabla analítica ─────────────────────────────────────── */
.tabla-analitica {
    width:100%; border-collapse:collapse; font-size:14px; background:var(--bg-card);
}
.tabla-analitica thead th {
    background:rgba(0,212,255,.08); color:var(--pri); font-size:14px; font-weight:700;
    text-transform:uppercase; letter-spacing:.08em; padding:9px 12px; white-space:nowrap;
    border-bottom:1px solid rgba(0,212,255,.15);
}
.tabla-analitica thead th.th-r { text-align:right; }
.tabla-analitica tbody td {
    padding:9px 12px; border-bottom:1px solid var(--border);
    color:var(--text); vertical-align:middle;
}
.tabla-analitica tbody td.td-r { text-align:right; font-variant-numeric:tabular-nums; }
.tabla-analitica tbody td.td-c { text-align:center; }
.tabla-analitica tbody tr:nth-child(even) { background:rgba(255,255,255,.018); }
.tabla-analitica tbody tr:hover { background:rgba(0,212,255,.04); }
.frag-alta  { background:rgba(255,77,109,.1) !important; }
.frag-media { background:rgba(255,184,0,.07) !important; }
/* ── Info box ────────────────────────────────────────────── */
.info-box {
    background:rgba(0,212,255,.06); border:1px solid rgba(0,212,255,.15);
    border-radius:var(--radius-sm); padding:12px 16px;
    font-size:14px; color:var(--text-dim); line-height:1.6; margin-bottom:14px;
}
.info-box strong { color:var(--pri); }
/* ── Criterios ───────────────────────────────────────────── */
.criterio-pill {
    display:inline-flex; align-items:center; gap:7px; padding:4px 12px;
    border-radius:20px; font-size:15px; font-weight:600; border:1px solid currentColor;
}
.criterio-pill.verde    { background:var(--success-lt); color:var(--success); }
.criterio-pill.amarillo { background:var(--warn-lt);    color:var(--warn); }
.criterio-pill.rojo     { background:var(--danger-lt);  color:var(--danger); }
.criterio-dot { width:8px; height:8px; border-radius:50%; background:currentColor; }
/* ── Ver Más link ────────────────────────────────────────── */
.ver-mas-link {
    font-size:11px; font-weight:700; color:var(--pri);
    text-decoration:none; display:inline-flex; align-items:center; gap:4px;
    opacity:.75; transition:opacity .15s, color .15s; letter-spacing:.03em;
}
.ver-mas-link:hover { opacity:1; color:#fff; }
/* ── Botones ─────────────────────────────────────────────── */
.btn-link-sec {
    display:inline-flex; align-items:center; gap:6px;
    color:var(--text-dim); font-size:14px; font-weight:600;
    text-decoration:none; padding:6px 14px;
    border:1px solid var(--border); border-radius:var(--radius-sm);
    background:var(--bg-card); transition:all .15s;
}
.btn-link-sec:hover { color:var(--pri); border-color:var(--pri); background:var(--pri-lt); }
a { color:var(--pri); }
a:hover { color:#fff; }

/* ── Score bar ─────────────────────────────────────────────────────── */
.score-bar-wrap { display:flex; align-items:center; gap:8px; }
.score-bar { flex:1; height:7px; border-radius:4px; background:rgba(255,255,255,.08); min-width:60px; }
.score-fill { height:100%; border-radius:4px; }
.score-fill-alto  { background:linear-gradient(to right,var(--danger),#c026d3); }
.score-fill-medio { background:linear-gradient(to right,var(--warn),var(--orange)); }
.score-fill-bajo  { background:linear-gradient(to right,var(--pri),var(--success)); }
.score-num { font-size:12px; font-weight:800; min-width:46px; text-align:right; letter-spacing:.03em; }
.score-alto  { color:var(--danger); }
.score-medio { color:var(--warn); }
.score-bajo  { color:var(--pri); }
/* ── Badge BAJO ─────────────────────────────────────────────────────── */
.badge-bajo { background:rgba(0,212,255,.12); color:var(--pri); }
/* ── Heatmap ────────────────────────────────────────────────────────── */
.heatmap-table { border-collapse:collapse; width:100%; font-size:13px; }
.heatmap-table th {
    padding:8px 10px; background:rgba(0,212,255,.07); color:var(--pri);
    font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.05em;
    border:1px solid var(--border); white-space:nowrap;
}
.heatmap-table th.th-especie { min-width:170px; text-align:left; }
.heatmap-table td {
    padding:8px 10px; border:1px solid var(--border);
    text-align:center; font-size:13px; font-weight:700; transition:filter .15s;
}
.heatmap-table td:hover { filter:brightness(1.25); cursor:default; }
.hm-0   { background:rgba(255,255,255,.02); color:var(--neutral); }
.hm-low { background:rgba(0,212,255,.15);   color:var(--pri); }
.hm-mid { background:rgba(255,184,0,.22);   color:var(--warn); }
.hm-hi  { background:rgba(255,77,109,.28);  color:var(--danger); }
</style>
@endpush

@section('content')
<div class="db-wrap">

    {{-- ENCABEZADO --}}
    <div class="db-header">
        <div>
            <h1 class="db-title">
                <i class="bi bi-bar-chart-steps"></i>
                Dashboard Analítico de Compras
            </h1>
            <p class="db-subtitle">
                Dirección de Logística &middot; Año <strong>{{ $selectedYear }}</strong>
                &middot; Análisis de riesgo, fragmentación y ejecución
                @if(!$isAdmin && auth()->user()->departamento)
                    &middot; <span style="color:var(--pri); font-weight:700;">
                        <i class="bi bi-building"></i> {{ auth()->user()->departamento->detalle }}
                    </span>
                @endif
            </p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('reporte.index') }}" class="btn-link-sec">
                <i class="bi bi-table"></i> Reporte Detallado
            </a>
            <a href="{{ route('dashboard') }}" class="btn-link-sec">
                <i class="bi bi-speedometer2"></i> Dashboard General
            </a>
        </div>
    </div>

    {{-- FILTROS --}}
    <form action="{{ route('reporte.dashboard') }}" method="GET">
        <div class="filtro-bar">
            <div class="filtro-group">
                <label><i class="bi bi-calendar3 me-1"></i>Año</label>
                <select name="year" class="autosubmit">
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}"
                            {{ (string)$year === (string)$selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($isAdmin)
            <div class="filtro-group">
                <label><i class="bi bi-building me-1"></i>Departamento</label>
                <select name="departamento_id" class="autosubmit">
                    <option value="">— Todos —</option>
                    @foreach ($departamentos as $dep)
                        <option value="{{ $dep->id }}"
                            {{ (string)$dep->id === (string)$selectedDepartamentoId ? 'selected' : '' }}>
                            {{ $dep->detalle }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="filtro-group" style="justify-content:flex-end;">
                <label><i class="bi bi-building me-1"></i>Tu Departamento</label>
                <div style="background:var(--bg-card2); border:1px solid rgba(0,212,255,.25);
                            border-radius:var(--radius-sm); padding:7px 14px; font-size:15px;
                            color:var(--pri); font-weight:700;">
                    <i class="bi bi-lock-fill" style="font-size:12px; opacity:.6;"></i>
                    {{ auth()->user()->departamento?->detalle ?? 'Sin departamento asignado' }}
                </div>
            </div>
            @endif
        </div>
    </form>

    {{-- PANEL DE ESTADO DE USUARIOS (solo admin) 
    @if($isAdmin)
    <div style="background:rgba(255,184,0,.06); border:1px solid rgba(255,184,0,.2);
                border-radius:var(--radius); padding:14px 18px; margin-bottom:18px;">
        <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.1em;
                    color:var(--warn); margin-bottom:10px; display:flex; align-items:center; gap:7px;">
            <i class="bi bi-people-fill"></i> Estado de usuarios del sistema
            <span style="font-size:11px; color:var(--neutral); font-weight:400; text-transform:none;">(visible solo para administradores)</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding:5px 10px; color:var(--text-dim); border-bottom:1px solid var(--border);">Usuario</th>
                        <th style="text-align:left; padding:5px 10px; color:var(--text-dim); border-bottom:1px solid var(--border);">Email</th>
                        <th style="text-align:left; padding:5px 10px; color:var(--text-dim); border-bottom:1px solid var(--border);">Departamento asignado</th>
                        <th style="text-align:center; padding:5px 10px; color:var(--text-dim); border-bottom:1px solid var(--border);">Verá en dashboard</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumenUsuarios as $ru)
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:6px 10px; color:var(--text);">{{ $ru->name }}</td>
                            <td style="padding:6px 10px; color:var(--text-dim);">{{ $ru->email }}</td>
                            <td style="padding:6px 10px;">
                                @if($ru->departamento_id)
                                    <span style="color:var(--success); font-weight:700;">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $ru->departamento_nombre }} (ID: {{ $ru->departamento_id }})
                                    </span>
                                @else
                                    <span style="color:var(--danger); font-weight:700;">
                                        <i class="bi bi-exclamation-circle-fill"></i> Sin departamento — verá dashboard vacío
                                    </span>
                                @endif
                            </td>
                            <td style="padding:6px 10px; text-align:center;">
                                @if($ru->departamento_id)
                                    <span style="background:var(--success-lt); color:var(--success);
                                                padding:2px 8px; border-radius:10px; font-size:12px; font-weight:700;">
                                        Solo {{ $ru->departamento_nombre }}
                                    </span>
                                @else
                                    <span style="background:var(--danger-lt); color:var(--danger);
                                                padding:2px 8px; border-radius:10px; font-size:12px; font-weight:700;">
                                        Nada (sin asignar)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
--}}
    {{-- ============================================================
         SECCIÓN 1 — KPIs GENERALES
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-bar-chart-fill"></i> 1. Resumen General
        </div>
        <div class="kpi-grid">
            <div class="kpi-card blue">
                <div class="kpi-icon"><i class="bi bi-clipboard-data"></i></div>
                <div>
                    <p class="kpi-label">Proyectos PAC</p>
                    <p class="kpi-value">{{ number_format($totalProyectos, 0, ',', '.') }}</p>
                    <p class="kpi-meta">registrados en {{ $selectedYear }}</p>
                </div>
            </div>
            <div class="kpi-card green">
                <div class="kpi-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div>
                    <p class="kpi-label">Licitaciones</p>
                    <p class="kpi-value">{{ number_format($totalLicitaciones, 0, ',', '.') }}</p>
                    <p class="kpi-meta">procesos registrados</p>
                </div>
            </div>
            <div class="kpi-card blue">
                <div class="kpi-icon"><i class="bi bi-cart-check"></i></div>
                <div>
                    <p class="kpi-label">Órdenes de Compra</p>
                    <p class="kpi-value">{{ number_format($totalOrdenes, 0, ',', '.') }}</p>
                    <p class="kpi-meta">emitidas</p>
                </div>
            </div>
            <div class="kpi-card blue">
                <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <p class="kpi-label">Presupuesto Total</p>
                    <p class="kpi-value" style="font-size:17px;">
                        $ {{ number_format($totalPresupuesto, 0, ',', '.') }}
                    </p>
                    <p class="kpi-meta">% ejec.: <strong>{{ $totalPorcentaje }}%</strong></p>
                </div>
            </div>
            <div class="kpi-card {{ $totalSaldo >= 0 ? 'green' : 'red' }}">
                <div class="kpi-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <p class="kpi-label">Saldo Disponible</p>
                    <p class="kpi-value" style="font-size:17px; {{ $totalSaldo < 0 ? 'color:var(--danger)' : '' }}">
                        $ {{ number_format($totalSaldo, 0, ',', '.') }}
                    </p>
                    <p class="kpi-meta">comprometido: $ {{ number_format($totalComprometido, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 2 — ALERTAS RÁPIDAS
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-exclamation-triangle-fill"></i> 2. Alertas Rápidas
        </div>
        <div class="kpi-grid">
            <div class="kpi-card {{ $proyectosSinLicitacion > 0 ? 'yellow' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-file-earmark-x"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Sin Licitación</p>
                    <p class="kpi-value">{{ $proyectosSinLicitacion }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">proyectos sin proceso asociado</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalSinLicitacion" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kpi-card {{ $proyectosSinOC > 0 ? 'yellow' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-cart-x"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Sin Orden de Compra</p>
                    <p class="kpi-value">{{ $proyectosSinOC }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">proyectos sin OC emitida</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalSinOC" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kpi-card {{ $proyectosSaldoNegativo > 0 ? 'red' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-exclamation-circle"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Saldo Negativo</p>
                    <p class="kpi-value">{{ $proyectosSaldoNegativo }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">proyectos que superan presupuesto</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalSaldoNegativo" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kpi-card {{ $licitacionesProblematicas > 0 ? 'orange' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-slash-circle"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Licitac. Problemáticas</p>
                    <p class="kpi-value">{{ $licitacionesProblematicas }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">desiertas, suspendidas o canceladas</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalLicitacionesProblematicas" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 3 — MOTOR DE DETECCIÓN DE FRAGMENTACIÓN
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-shield-exclamation"></i> 3. Detección de Posible Fragmentación de Compras
        </div>

        {{-- ── KPIs del módulo ─────────────────────────────────────────── --}}
        <div class="kpi-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); margin-bottom:18px;">
            <div class="kpi-card blue">
                <div class="kpi-icon"><i class="bi bi-cart3"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">OCs Analizadas</p>
                    <p class="kpi-value">{{ number_format($kpiTotalOC,0,',','.') }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">con múltiples compras por especie</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalFragTodas" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kpi-card {{ $kpiCasosSospechosos > 0 ? 'yellow' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Casos Sospechosos</p>
                    <p class="kpi-value">{{ $kpiCasosSospechosos }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">riesgo alto o medio detectado</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalFragSospechosos" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kpi-card {{ $kpiRiesgoAlto > 0 ? 'red' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-shield-x"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Riesgo Alto</p>
                    <p class="kpi-value">{{ $kpiRiesgoAlto }}</p>
                    <p class="kpi-meta" style="margin-bottom:6px;">casos de máxima prioridad</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalFragAlto" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="kpi-card {{ $kpiMontoFragmentado > 0 ? 'orange' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                <div style="display:flex; flex-direction:column; flex:1; align-self:stretch;">
                    <p class="kpi-label">Monto Potencial</p>
                    <p class="kpi-value" style="font-size:16px;">
                        $&nbsp;{{ number_format($kpiMontoFragmentado,0,',','.') }}
                    </p>
                    <p class="kpi-meta" style="margin-bottom:6px;">en casos sospechosos</p>
                    <div style="margin-top:auto; text-align:right;">
                        <a href="#" data-toggle="modal" data-target="#modalFragMonto" class="ver-mas-link">
                            Ver Más <i class="bi bi-arrow-right-circle-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ¿Cómo detectamos la fragmentación? ─────────────────────────── --}}
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius);
                    padding:18px 22px; margin-bottom:16px;">
            <div style="font-size:20px; font-weight:700; color:var(--pri); margin-bottom:10px;">
                <i class="bi bi-question-circle-fill"></i>&nbsp;¿Qué buscamos y cómo lo detectamos?
            </div>
            <p style="color:var(--text-dim); font-size:18px; margin-bottom:14px; line-height:1.7;">
                La <strong style="color:var(--text);">fragmentación de compras</strong> ocurre cuando se divide una compra grande
                en varias compras pequeñas para eludir el proceso de licitación pública.
                Se detecta analizando tres variables simples para cada <strong style="color:var(--text);">especie de producto</strong>:
            </p>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(155px,1fr)); gap:10px; margin-bottom:16px;">
                <div style="background:rgba(0,212,255,.07); border:1px solid rgba(0,212,255,.25); border-radius:8px;
                            padding:14px; text-align:center;">
                    <div style="font-size:28px; color:var(--pri); margin-bottom:6px;"><i class="bi bi-exclamation-triangle"></i></div>
                    <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:4px;">N° de Órdenes de Compra</div>
                    <div style="font-size:15px; color:var(--text-dim); line-height:1.5;">¿Cuántas veces se compró<br>la misma especie?</div>
                </div>
                <div style="background:rgba(255,184,0,.07); border:1px solid rgba(255,184,0,.25); border-radius:8px;
                            padding:14px; text-align:center;">
                    <div style="font-size:28px; color:var(--warn); margin-bottom:6px;"><i class="bi bi-calendar-range"></i></div>
                    <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:4px;">Días entre compras</div>
                    <div style="font-size:15px; color:var(--text-dim); line-height:1.5;">¿Cuántos días hay entre<br>la 1ª y la última OC?</div>
                </div>
                <div style="background:rgba(255,77,109,.07); border:1px solid rgba(255,77,109,.25); border-radius:8px;
                            padding:14px; text-align:center;">
                    <div style="font-size:28px; color:var(--danger); margin-bottom:6px;"><i class="bi bi-cash-stack"></i></div>
                    <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:4px;">Monto total vs 100 UTM</div>
                    <div style="font-size:15px; color:var(--text-dim); line-height:1.5;">¿La suma supera<br>$&nbsp;{{ number_format($umbralFragmentacion,0,',','.') }}?</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:17px;">
                    <thead>
                        <tr style="background:rgba(255,255,255,.04);">
                            <th style="padding:8px 15px; text-align:left; color:var(--text-dim); font-weight:600; border-bottom:1px solid var(--border);">Nivel de Riesgo</th>
                            <th style="padding:8px 15px; text-align:center; color:var(--text-dim); font-weight:600; border-bottom:1px solid var(--border);">N° de OCs<br style="font-weight:400;">(misma especie)</></th>
                            <th style="padding:8px 15px; text-align:center; color:var(--text-dim); font-weight:600; border-bottom:1px solid var(--border);">Días entre<br style="font-weight:400;">1ª y última OC</></th>
                            <th style="padding:8px 15px; text-align:center; color:var(--text-dim); font-weight:600; border-bottom:1px solid var(--border);">Monto total<br style="font-weight:400;">del conjunto</></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:9px 14px; border-bottom:1px solid var(--border);">
                                <span class="badge-sema badge-rojo"><i class="bi bi-circle-fill" style="font-size:8px;"></i>&nbsp;ALTO</span>
                            </td>
                            <td style="padding:9px 14px; text-align:center; border-bottom:1px solid var(--border); color:var(--danger); font-weight:800; font-size:16px;">
                                &ge;&nbsp;{{ $reglasFragmentacion['alto']['cantidad_oc'] }} OCs
                            </td>
                            <td style="padding:9px 14px; text-align:center; border-bottom:1px solid var(--border); color:var(--danger); font-weight:800; font-size:16px;">
                                &le;&nbsp;{{ $reglasFragmentacion['alto']['dispersion_dias'] }} días
                            </td>
                            <td style="padding:9px 14px; text-align:center; border-bottom:1px solid var(--border); color:var(--danger); font-weight:700;">
                                &ge;&nbsp;100 UTM&nbsp;<small style="color:var(--text-dim); font-weight:400;">($ {{ number_format($umbralFragmentacion,0,',','.') }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px; border-bottom:1px solid var(--border);">
                                <span class="badge-sema badge-amarillo"><i class="bi bi-circle-fill" style="font-size:8px;"></i>&nbsp;MEDIO</span>
                            </td>
                            <td style="padding:9px 14px; text-align:center; border-bottom:1px solid var(--border); color:var(--warn); font-weight:800; font-size:16px;">
                                &ge;&nbsp;{{ $reglasFragmentacion['medio']['cantidad_oc'] }} OCs
                            </td>
                            <td style="padding:9px 14px; text-align:center; border-bottom:1px solid var(--border); color:var(--warn); font-weight:800; font-size:16px;">
                                &le;&nbsp;{{ $reglasFragmentacion['medio']['dispersion_dias'] }} días
                            </td>
                            <td style="padding:9px 14px; text-align:center; border-bottom:1px solid var(--border); color:var(--warn); font-weight:700;">
                                &ge;&nbsp;50 UTM&nbsp;<small style="color:var(--text-dim); font-weight:400;">($ {{ number_format($umbralFragmentacion * 0.5,0,',','.') }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:9px 14px;">
                                <span class="badge-sema badge-bajo"><i class="bi bi-circle-fill" style="font-size:8px;"></i>&nbsp;BAJO</span>
                            </td>
                            <td colspan="3" style="padding:9px 14px; color:var(--text-dim); font-size:12px;">
                                Múltiples OCs para la misma especie, pero sin alcanzar los umbrales de Alto o Medio.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($casosFrag->isEmpty())
            <div style="text-align:center; padding:2.5rem; color:var(--neutral);
                        background:var(--bg-card); border-radius:var(--radius); border:1px solid var(--border);">
                <i class="bi bi-check-circle-fill"
                   style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                No se detectaron patrones de posible fragmentación para los filtros aplicados.
            </div>
        @else

        {{-- ── GRÁFICOS: N° OCs por especie + Casos por departamento ─────────── --}}
        <div class="row g-3" style="margin-bottom:14px;">
            <div class="col-lg-6">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-bar-chart-horizontal-fill"></i>¿Cuántas veces se compró lo mismo?
                        <span style="font-size:11px; font-weight:400; color:var(--text-dim); margin-left:6px;">
                            (N° de OCs por especie —
                            <span style="color:var(--danger);">&#9632;</span>&nbsp;&ge;5 &nbsp;
                            <span style="color:var(--warn);">&#9632;</span>&nbsp;&ge;3 &nbsp;
                            <span style="color:var(--pri);">&#9632;</span>&nbsp;&lt;3)
                        </span>
                    </div>
                    <div class="chart-wrap"
                         style="height:{{ min(360, max(200, count($chartFragEspecieLabels) * 34)) }}px;">
                        <canvas id="chartFragOCsEspecie"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-building"></i>¿En qué departamentos se concentran los casos?
                        <span style="font-size:11px; font-weight:400; color:var(--text-dim); margin-left:6px;">(N° de casos por nivel)</span>
                    </div>
                    <div class="chart-wrap"
                         style="height:{{ min(360, max(200, count($chartDeptLabels) * 34)) }}px;">
                        <canvas id="chartFragDept"></canvas>
                    </div>
                </div>
            </div>
        </div>

       

        {{-- ── TABLA DETALLE DE CASOS ───────────────────────────────────── --}}
        <div style="border-radius:var(--radius); overflow:hidden; border:1px solid var(--border);
                    box-shadow:var(--shadow-sm); overflow-x:auto; margin-bottom:8px;">
            <div style="background:rgba(255,77,109,.07); color:var(--danger); padding:11px 18px;
                        font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:.1em;
                        border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px;">
                <i class="bi bi-table"></i>
                Detalle de los {{ min($casosFrag->count(), 20) }} Casos con Posible Fragmentación
            </div>
            <table class="tabla-analitica">
                <thead>
                    <tr>
                        <th class="td-c" style="width:30px;">#</th>
                        <th>Especie comprada</th>
                        <th>Departamento</th>
                        <th>Modalidad</th>
                        <th class="th-r" style="min-width:80px;">N° de OCs</th>
                        <th class="th-r" style="min-width:120px;">Días entre<br>1ª y última OC</th>
                        <th class="th-r" style="min-width:120px;">Monto total</th>
                        <th class="td-c" style="min-width:110px;">¿Supera<br>100 UTM?</th>
                        <th class="td-c" style="min-width:90px;">Nivel de<br>Riesgo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($casosFrag->take(20) as $idx => $cf)
                        @php
                            $rowCls    = $cf->nivel_riesgo === 'ALTO' ? 'frag-alta' : ($cf->nivel_riesgo === 'MEDIO' ? 'frag-media' : '');
                            $bCls      = $cf->nivel_riesgo === 'ALTO' ? 'badge-rojo' : ($cf->nivel_riesgo === 'MEDIO' ? 'badge-amarillo' : 'badge-bajo');
                            $ocColor   = $cf->cantidad_oc >= 5 ? 'var(--danger)' : ($cf->cantidad_oc >= 3 ? 'var(--warn)' : 'inherit');
                            $diasInt   = (int)round($cf->dispersion_dias);
                            $dColor    = $diasInt > 0 && $diasInt <= 30 ? 'var(--danger)' : ($diasInt <= 90 ? 'var(--warn)' : 'inherit');
                            $superaUTM = (float)$cf->monto_total >= $umbralFragmentacion;
                            $superaMed = !$superaUTM && (float)$cf->monto_total >= ($umbralFragmentacion * 0.5);
                        @endphp
                        <tr class="{{ $rowCls }}">
                            <td class="td-c" style="color:var(--text-dim); font-size:12px;">{{ $idx + 1 }}</td>
                            <td style="font-weight:600;">{{ $cf->especie }}</td>
                            <td>{{ $cf->departamento }}</td>
                            <td style="font-size:13px;">{{ $cf->modalidad }}</td>
                            <td class="td-r">
                                <span style="font-size:22px; font-weight:900; color:{{ $ocColor }}; line-height:1;">{{ $cf->cantidad_oc }}</span><br>
                                <span style="font-size:11px; color:var(--text-dim);">órdenes</span>
                            </td>
                            <td class="td-r">
                                @if($diasInt > 0)
                                    <span style="font-size:20px; font-weight:800; color:{{ $dColor }}; line-height:1;">{{ $diasInt }}</span><br>
                                    <span style="font-size:11px; color:var(--text-dim);">días</span>
                                @else
                                    <span style="color:var(--text-dim);">—</span>
                                @endif
                            </td>
                            <td class="td-r" style="font-weight:700;">
                                $&nbsp;{{ number_format($cf->monto_total,0,',','.') }}
                            </td>
                            <td class="td-c">
                                @if($superaUTM)
                                    <span class="badge-sema badge-rojo">
                                        <i class="bi bi-check-circle-fill" style="font-size:8px;"></i>&nbsp;Sí supera
                                    </span>
                                @elseif($superaMed)
                                    <span class="badge-sema badge-amarillo">
                                        <i class="bi bi-exclamation-circle-fill" style="font-size:8px;"></i>&nbsp;50-100 UTM
                                    </span>
                                @else
                                    <span class="badge-sema badge-bajo">
                                        <i class="bi bi-x-circle-fill" style="font-size:8px;"></i>&nbsp;No supera
                                    </span>
                                @endif
                            </td>
                            <td class="td-c">
                                <span class="badge-sema {{ $bCls }}">
                                    <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                                    {{ $cf->nivel_riesgo }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="font-size:11px; color:var(--neutral); margin-top:4px; margin-bottom:0;">
            <i class="bi bi-info-circle me-1"></i>
            Análisis por especie + departamento. Sin datos de proveedor — se recomienda revisión manual.
            Para más detalle use el
            <a href="{{ route('reporte.index') }}" style="color:var(--pri-mid);">Reporte Detallado</a>.
        </p>

        @endif
    </div>

    {{-- ============================================================
         SECCIÓN 4 — DISTRIBUCIÓN POR MODALIDAD
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-pie-chart-fill"></i> 4. Distribución por Modalidad de Compra
        </div>
        <div class="row g-3">
            <div class="col-md-5">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-pie-chart"></i>Monto total por modalidad
                    </div>
                    <div class="chart-wrap" style="height:280px;">
                        <canvas id="chartModalidad"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="tabla-depto-wrap h-100">
                    <div class="tabla-depto-header">
                        <i class="bi bi-table"></i> Detalle por modalidad
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="tabla-depto">
                            <thead>
                                <tr>
                                    <th>Modalidad</th>
                                    <th>Licitaciones</th>
                                    <th>OCs</th>
                                    <th>Monto Total</th>
                                    <th>% Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($distribucionModalidad as $mod)
                                    @php
                                        $pctMod = $totalMontoModalidad > 0
                                            ? round(($mod->monto_total / $totalMontoModalidad) * 100, 1) : 0;
                                        $clMod  = $pctMod > 50 ? 'r' : ($pctMod >= 20 ? 'y' : 'g');
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $mod->modalidad }}</strong></td>
                                        <td>{{ $mod->num_licitaciones }}</td>
                                        <td>{{ $mod->num_ordenes }}</td>
                                        <td>$ {{ number_format($mod->monto_total, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="pbar-wrap">
                                                <div class="pbar">
                                                    <div class="pbar-fill fill-{{ $clMod }}"
                                                         style="width:{{ min($pctMod,100) }}%"></div>
                                                </div>
                                                <span class="pbar-pct pct-{{ $clMod }}">{{ $pctMod }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center; color:var(--neutral); padding:1.5rem;">
                                            Sin datos para el período seleccionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <td>TOTAL</td>
                                <td>{{ $distribucionModalidad->sum('num_licitaciones') }}</td>
                                <td>{{ $distribucionModalidad->sum('num_ordenes') }}</td>
                                <td>$ {{ number_format($distribucionModalidad->sum('monto_total'), 0, ',', '.') }}</td>
                                <td>100%</td>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 5 — CONCENTRACIÓN TEMPORAL
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-calendar-range"></i> 5. Concentración Temporal de Órdenes de Compra
        </div>
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="bi bi-bar-chart-line"></i>
                Distribución mensual — N° de OCs y Monto comprometido ({{ $selectedYear }})
            </div>
            <div class="chart-wrap" style="height:320px;">
                <canvas id="chartTemporal"></canvas>
            </div>
            <p style="font-size:11px; color:var(--neutral); margin:8px 0 0;">
                <i class="bi bi-info-circle me-1"></i>
                Una concentración excesiva de compras en los últimos meses puede indicar ejecución
                presupuestaria apresurada. Se recomienda distribución mensual equilibrada.
            </p>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 6 — PRESUPUESTO VS COMPROMETIDO POR DEPARTAMENTO
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-bar-chart-line"></i> 6. Presupuesto vs. Comprometido por Departamento
        </div>
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="bi bi-bar-chart-fill"></i>Comparativa por Departamento
            </div>
            <div class="chart-wrap" style="height:320px;">
                <canvas id="chartBarDepto"></canvas>
            </div>
        </div>
        <div style="margin-top:14px;">
            @if(count($tablaDepartamentos) > 0)
            <div class="tabla-depto-wrap">
                <div class="tabla-depto-header">
                    <i class="bi bi-building"></i> Ejecución por Departamento — {{ $selectedYear }}
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
                            @foreach($tablaDepartamentos as $dato)
                                @php
                                    $p  = $dato['porcentaje'];
                                    $cl = $p > 75 ? 'g' : ($p >= 60 ? 'y' : 'r');
                                    $saldoD = $dato['presupuesto'] - $dato['comprometido'];
                                    $bD = $p > 75 ? 'badge-verde' : ($p >= 60 ? 'badge-amarillo' : 'badge-rojo');
                                    $eD = $p > 75 ? 'Aceptable' : ($p >= 60 ? 'Media' : 'Baja');
                                @endphp
                                <tr>
                                    <td><strong>{{ $dato['departamento'] }}</strong></td>
                                    <td>$ {{ number_format($dato['presupuesto'], 0, ',', '.') }}</td>
                                    <td>$ {{ number_format($dato['comprometido'], 0, ',', '.') }}</td>
                                    <td style="{{ $saldoD < 0 ? 'color:var(--danger);font-weight:700;' : '' }}">
                                        $ {{ number_format($saldoD, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="pbar-wrap">
                                            <div class="pbar">
                                                <div class="pbar-fill fill-{{ $cl }}"
                                                     style="width:{{ min($p,100) }}%"></div>
                                            </div>
                                            <span class="pbar-pct pct-{{ $cl }}">{{ $p }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-sema {{ $bD }}">
                                            <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                                            {{ $eD }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td>TOTAL GENERAL</td>
                            <td>$ {{ number_format($totalPresupuesto, 0, ',', '.') }}</td>
                            <td>$ {{ number_format($totalComprometido, 0, ',', '.') }}</td>
                            <td>$ {{ number_format($totalSaldo, 0, ',', '.') }}</td>
                            <td colspan="2">{{ $totalPorcentaje }}%</td>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 7 — TOP PROYECTOS CRÍTICOS
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-flag-fill"></i> 7. Proyectos Críticos
        </div>
        <div class="row g-3">

            {{-- Top baja ejecución --}}
            <div class="col-md-6">
                <div class="tabla-depto-wrap">
                    <div class="tabla-depto-header" style="background:#92400e;">
                        <i class="bi bi-arrow-down-circle"></i>&nbsp;Top 10 — Menor % de Ejecución
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="tabla-analitica">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Departamento</th>
                                    <th>Especie</th>
                                    <th class="th-r">Presupuesto</th>
                                    <th class="th-r">Comprometido</th>
                                    <th class="th-r">% Ejec.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topBajaEjecucion as $t)
                                    @php
                                        $cl = $t->porcentaje > 75 ? 'g' : ($t->porcentaje >= 60 ? 'y' : 'r');
                                    @endphp
                                    <tr>
                                        <td class="td-c" style="font-weight:700; color:var(--pri-mid);">
                                            {{ $t->id_proyecto }}
                                        </td>
                                        <td>{{ $t->departamento }}</td>
                                        <td>{{ $t->especie }}</td>
                                        <td class="td-r">$ {{ number_format($t->presupuesto, 0, ',', '.') }}</td>
                                        <td class="td-r">$ {{ number_format($t->comprometido, 0, ',', '.') }}</td>
                                        <td class="td-r">
                                            <span class="pbar-pct pct-{{ $cl }}">
                                                {{ $t->porcentaje }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center; padding:1rem; color:var(--neutral);">
                                            Sin datos con presupuesto asignado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Top saldo negativo --}}
            <div class="col-md-6">
                <div class="tabla-depto-wrap">
                    <div class="tabla-depto-header" style="background:#7f1d1d;">
                        <i class="bi bi-exclamation-octagon"></i>&nbsp;Top 10 — Mayor Exceso de Gasto
                    </div>
                    @if($topSaldoNegativo->isEmpty())
                        <div style="text-align:center; padding:2rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill"
                               style="font-size:2rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Ningún proyecto supera su presupuesto asignado.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Departamento</th>
                                        <th>Especie</th>
                                        <th class="th-r">Presupuesto</th>
                                        <th class="th-r">Exceso (+)</th>
                                        <th class="th-r">% Ejec.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topSaldoNegativo as $t)
                                        <tr>
                                            <td class="td-c"
                                                style="font-weight:700; color:var(--danger);">
                                                {{ $t->id_proyecto }}
                                            </td>
                                            <td>{{ $t->departamento }}</td>
                                            <td>{{ $t->especie }}</td>
                                            <td class="td-r">
                                                $ {{ number_format($t->presupuesto, 0, ',', '.') }}
                                            </td>
                                            <td class="td-r"
                                                style="color:var(--danger); font-weight:800;">
                                                + $ {{ number_format($t->exceso, 0, ',', '.') }}
                                            </td>
                                            <td class="td-r"
                                                style="color:var(--danger); font-weight:800;">
                                                {{ $t->porcentaje }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 8 — ESTADO LICITACIONES Y OCs
    ============================================================ --}}
    <div class="db-section">
        <div class="db-section-title">
            <i class="bi bi-file-earmark-text"></i> 8. Estado de Licitaciones y Órdenes de Compra
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-pie-chart"></i>Estado de Licitaciones
                    </div>
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="chartDonaLic"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-pie-chart-fill"></i>Estado Órdenes de Compra
          </div>
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="chartDonaOC"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         MODALES — DETALLE ALERTAS RÁPIDAS (Sección 2)
    ============================================================ --}}

    {{-- Modal 1: Sin Licitación --}}
    <div class="modal fade" id="modalSinLicitacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-file-earmark-x" style="color:var(--warn);"></i>
                        Proyectos sin Licitación — {{ $selectedYear }}
                        <span style="background:var(--warn-lt); color:var(--warn); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $proyectosSinLicitacion }}
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @if($detalleSinLicitacion->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin proyectos en esta categoría para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th class="td-c">Año</th>
                                        <th>Departamento</th>
                                        <th class="td-c">Ítem Presup.</th>
                                        <th>Especie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detalleSinLicitacion as $row)
                                        <tr>
                                            <td class="td-c" style="font-weight:700; color:var(--warn);">{{ $row->id_proyecto }}</td>
                                            <td class="td-c" style="color:var(--text-dim);">{{ $row->anio }}</td>
                                            <td>{{ $row->departamento }}</td>
                                            <td class="td-c" style="color:var(--text-dim); font-size:13px;">{{ $row->item ?? '—' }}</td>
                                            <td>{{ $row->especie }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal 2: Sin Orden de Compra --}}
    <div class="modal fade" id="modalSinOC" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-cart-x" style="color:var(--warn);"></i>
                        Proyectos sin Orden de Compra — {{ $selectedYear }}
                        <span style="background:var(--warn-lt); color:var(--warn); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $proyectosSinOC }}
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @if($detalleSinOC->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin proyectos en esta categoría para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th class="td-c">Año</th>
                                        <th>Departamento</th>
                                        <th class="td-c">Ítem Presup.</th>
                                        <th>Especie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detalleSinOC as $row)
                                        <tr>
                                            <td class="td-c" style="font-weight:700; color:var(--warn);">{{ $row->id_proyecto }}</td>
                                            <td class="td-c" style="color:var(--text-dim);">{{ $row->anio }}</td>
                                            <td>{{ $row->departamento }}</td>
                                            <td class="td-c" style="color:var(--text-dim); font-size:13px;">{{ $row->item ?? '—' }}</td>
                                            <td>{{ $row->especie }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal 3: Saldo Negativo --}}
    <div class="modal fade" id="modalSaldoNegativo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-exclamation-circle" style="color:var(--danger);"></i>
                        Proyectos con Saldo Negativo — {{ $selectedYear }}
                        <span style="background:var(--danger-lt); color:var(--danger); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $proyectosSaldoNegativo }}
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @if($detalleSaldoNegativo->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Ningún proyecto supera su presupuesto asignado.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th>Departamento</th>
                                        <th>Especie</th>
                                        <th class="th-r">Presupuesto</th>
                                        <th class="th-r">Comprometido</th>
                                        <th class="th-r">Exceso</th>
                                        <th class="th-r">% Ejec.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detalleSaldoNegativo as $row)
                                        <tr class="frag-alta">
                                            <td class="td-c" style="font-weight:700; color:var(--danger);">{{ $row->id_proyecto }}</td>
                                            <td>{{ $row->departamento }}</td>
                                            <td>{{ $row->especie }}</td>
                                            <td class="td-r">$ {{ number_format($row->presupuesto, 0, ',', '.') }}</td>
                                            <td class="td-r">$ {{ number_format($row->comprometido, 0, ',', '.') }}</td>
                                            <td class="td-r" style="color:var(--danger); font-weight:800;">
                                                + $ {{ number_format($row->exceso, 0, ',', '.') }}
                                            </td>
                                            <td class="td-r" style="color:var(--danger); font-weight:800;">{{ $row->porcentaje }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal 4: Licitaciones Problemáticas --}}
    <div class="modal fade" id="modalLicitacionesProblematicas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-slash-circle" style="color:var(--orange);"></i>
                        Licitaciones Problemáticas — {{ $selectedYear }}
                        <span style="background:var(--orange-lt); color:var(--orange); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $licitacionesProblematicas }}
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    <div class="info-box" style="margin:12px 16px 0; font-size:13px;">
                        Incluye licitaciones con estado: <strong>Desierta, Suspendida, Revocada</strong> o <strong>Proyecto no ejecutado</strong>.
                    </div>
                    @if($detalleLicitacionesProblematicas->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin licitaciones problemáticas para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto; margin-top:12px;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID Proyecto</th>
                                        <th>Departamento</th>
                                        <th>Especie</th>
                                        <th>Modalidad</th>
                                        <th class="td-c">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detalleLicitacionesProblematicas as $row)
                                        @php
                                            $estLic = strtolower($row->estado ?? '');
                                            $bCls   = str_contains($estLic, 'desert') || str_contains($estLic, 'revocad')
                                                ? 'badge-rojo'
                                                : 'badge-amarillo';
                                        @endphp
                                        <tr>
                                            <td class="td-c" style="font-weight:700; color:var(--orange);">{{ $row->id_proyecto }}</td>
                                            <td>{{ $row->departamento }}</td>
                                            <td>{{ $row->especie }}</td>
                                            <td style="font-size:13px;">{{ $row->modalidad }}</td>
                                            <td class="td-c">
                                                <span class="badge-sema {{ $bCls }}" style="font-size:12px;">
                                                    <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                                                    {{ $row->estado }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- ============================================================
         MODALES SECCIÓN 3 — FRAGMENTACIÓN DE COMPRAS
    ============================================================ --}}

    {{-- Modal Frag 1: Todas las OCs analizadas --}}
    <div class="modal fade" id="modalFragTodas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-cart3" style="color:var(--pri);"></i>
                        OCs con Múltiples Compras por Especie — {{ $selectedYear }}
                        <span style="background:rgba(0,212,255,.15); color:var(--pri); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $casosFrag->count() }} casos
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @if($casosFrag->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin casos detectados para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th>Especie</th>
                                        <th>Departamento</th>
                                        <th>Modalidad</th>
                                        <th class="th-r">N° OCs</th>
                                        <th class="th-r">Días entre<br>compras</th>
                                        <th class="th-r">Monto Total</th>
                                        <th class="td-c">¿Supera<br>100 UTM?</th>
                                        <th class="td-c">Nivel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($casosFrag as $cf)
                                        @php
                                            $bCls = $cf->nivel_riesgo === 'ALTO' ? 'badge-rojo' : ($cf->nivel_riesgo === 'MEDIO' ? 'badge-amarillo' : 'badge-bajo');
                                            $ocColor = $cf->cantidad_oc >= 5 ? 'var(--danger)' : ($cf->cantidad_oc >= 3 ? 'var(--warn)' : 'inherit');
                                            $diasInt = (int)round($cf->dispersion_dias);
                                            $superaUTM = (float)$cf->monto_total >= $umbralFragmentacion;
                                            $superaMed = !$superaUTM && (float)$cf->monto_total >= ($umbralFragmentacion * 0.5);
                                        @endphp
                                        <tr class="{{ $cf->nivel_riesgo === 'ALTO' ? 'frag-alta' : ($cf->nivel_riesgo === 'MEDIO' ? 'frag-media' : '') }}">
                                            <td class="td-c" style="font-weight:700; color:var(--pri);">{{ $cf->id_proyecto }}</td>
                                            <td style="font-weight:600;">{{ $cf->especie }}</td>
                                            <td>{{ $cf->departamento }}</td>
                                            <td style="font-size:13px; color:var(--text-dim);">{{ $cf->modalidad }}</td>
                                            <td class="td-r" style="font-weight:800; color:{{ $ocColor }};">{{ $cf->cantidad_oc }}</td>
                                            <td class="td-r" style="font-weight:700;">{{ $diasInt > 0 ? $diasInt.' días' : '—' }}</td>
                                            <td class="td-r" style="font-weight:700;">$&nbsp;{{ number_format($cf->monto_total,0,',','.') }}</td>
                                            <td class="td-c">
                                                @if($superaUTM)
                                                    <span class="badge-sema badge-rojo"><i class="bi bi-check-circle-fill" style="font-size:8px;"></i>&nbsp;Sí</span>
                                                @elseif($superaMed)
                                                    <span class="badge-sema badge-amarillo"><i class="bi bi-exclamation-circle-fill" style="font-size:8px;"></i>&nbsp;50-100</span>
                                                @else
                                                    <span class="badge-sema badge-bajo"><i class="bi bi-x-circle-fill" style="font-size:8px;"></i>&nbsp;No</span>
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <span class="badge-sema {{ $bCls }}"><i class="bi bi-circle-fill" style="font-size:8px;"></i> {{ $cf->nivel_riesgo }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Frag 2: Casos Sospechosos (Alto + Medio) --}}
    <div class="modal fade" id="modalFragSospechosos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:var(--warn);"></i>
                        Casos Sospechosos de Fragmentación — {{ $selectedYear }}
                        <span style="background:rgba(255,184,0,.15); color:var(--warn); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $kpiCasosSospechosos }} casos
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @php $sospechososFrag = $casosFrag->whereIn('nivel_riesgo', ['ALTO', 'MEDIO']); @endphp
                    @if($sospechososFrag->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin casos sospechosos para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th>Especie</th>
                                        <th>Departamento</th>
                                        <th>Modalidad</th>
                                        <th class="th-r">N° OCs</th>
                                        <th class="th-r">Días entre<br>compras</th>
                                        <th class="th-r">Monto Total</th>
                                        <th class="td-c">¿Supera<br>100 UTM?</th>
                                        <th class="td-c">Nivel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sospechososFrag as $cf)
                                        @php
                                            $bCls = $cf->nivel_riesgo === 'ALTO' ? 'badge-rojo' : 'badge-amarillo';
                                            $ocColor = $cf->cantidad_oc >= 5 ? 'var(--danger)' : ($cf->cantidad_oc >= 3 ? 'var(--warn)' : 'inherit');
                                            $diasInt = (int)round($cf->dispersion_dias);
                                            $superaUTM = (float)$cf->monto_total >= $umbralFragmentacion;
                                            $superaMed = !$superaUTM && (float)$cf->monto_total >= ($umbralFragmentacion * 0.5);
                                        @endphp
                                        <tr class="{{ $cf->nivel_riesgo === 'ALTO' ? 'frag-alta' : 'frag-media' }}">
                                            <td class="td-c" style="font-weight:700; color:var(--pri);">{{ $cf->id_proyecto }}</td>
                                            <td style="font-weight:600;">{{ $cf->especie }}</td>
                                            <td>{{ $cf->departamento }}</td>
                                            <td style="font-size:13px; color:var(--text-dim);">{{ $cf->modalidad }}</td>
                                            <td class="td-r" style="font-weight:800; color:{{ $ocColor }};">{{ $cf->cantidad_oc }}</td>
                                            <td class="td-r" style="font-weight:700;">{{ $diasInt > 0 ? $diasInt.' días' : '—' }}</td>
                                            <td class="td-r" style="font-weight:700;">$&nbsp;{{ number_format($cf->monto_total,0,',','.') }}</td>
                                            <td class="td-c">
                                                @if($superaUTM)
                                                    <span class="badge-sema badge-rojo"><i class="bi bi-check-circle-fill" style="font-size:8px;"></i>&nbsp;Sí</span>
                                                @elseif($superaMed)
                                                    <span class="badge-sema badge-amarillo"><i class="bi bi-exclamation-circle-fill" style="font-size:8px;"></i>&nbsp;50-100</span>
                                                @else
                                                    <span class="badge-sema badge-bajo"><i class="bi bi-x-circle-fill" style="font-size:8px;"></i>&nbsp;No</span>
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <span class="badge-sema {{ $bCls }}"><i class="bi bi-circle-fill" style="font-size:8px;"></i> {{ $cf->nivel_riesgo }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Frag 3: Solo Riesgo Alto --}}
    <div class="modal fade" id="modalFragAlto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-shield-x" style="color:var(--danger);"></i>
                        Casos de Riesgo Alto — {{ $selectedYear }}
                        <span style="background:rgba(255,77,109,.15); color:var(--danger); padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            {{ $kpiRiesgoAlto }} casos
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @php $altaFrag = $casosFrag->where('nivel_riesgo', 'ALTO'); @endphp
                    @if($altaFrag->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin casos de Riesgo Alto para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th>Especie</th>
                                        <th>Departamento</th>
                                        <th>Modalidad</th>
                                        <th class="th-r">N° OCs</th>
                                        <th class="th-r">Días entre<br>compras</th>
                                        <th class="th-r">Monto Total</th>
                                        <th class="td-c">¿Supera<br>100 UTM?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($altaFrag as $cf)
                                        @php
                                            $ocColor = $cf->cantidad_oc >= 5 ? 'var(--danger)' : 'var(--warn)';
                                            $diasInt = (int)round($cf->dispersion_dias);
                                            $superaUTM = (float)$cf->monto_total >= $umbralFragmentacion;
                                            $superaMed = !$superaUTM && (float)$cf->monto_total >= ($umbralFragmentacion * 0.5);
                                        @endphp
                                        <tr class="frag-alta">
                                            <td class="td-c" style="font-weight:700; color:var(--pri);">{{ $cf->id_proyecto }}</td>
                                            <td style="font-weight:600;">{{ $cf->especie }}</td>
                                            <td>{{ $cf->departamento }}</td>
                                            <td style="font-size:13px; color:var(--text-dim);">{{ $cf->modalidad }}</td>
                                            <td class="td-r" style="font-weight:800; color:{{ $ocColor }};">{{ $cf->cantidad_oc }}</td>
                                            <td class="td-r" style="font-weight:700; color:var(--danger);">{{ $diasInt > 0 ? $diasInt.' días' : '—' }}</td>
                                            <td class="td-r" style="font-weight:700;">$&nbsp;{{ number_format($cf->monto_total,0,',','.') }}</td>
                                            <td class="td-c">
                                                @if($superaUTM)
                                                    <span class="badge-sema badge-rojo"><i class="bi bi-check-circle-fill" style="font-size:8px;"></i>&nbsp;Sí</span>
                                                @elseif($superaMed)
                                                    <span class="badge-sema badge-amarillo"><i class="bi bi-exclamation-circle-fill" style="font-size:8px;"></i>&nbsp;50-100</span>
                                                @else
                                                    <span class="badge-sema badge-bajo"><i class="bi bi-x-circle-fill" style="font-size:8px;"></i>&nbsp;No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Frag 4: Monto Potencial (sospechosos ordenados por monto) --}}
    <div class="modal fade" id="modalFragMonto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="background:var(--bg-card); border:1px solid var(--border); color:var(--text);">
                <div class="modal-header" style="border-bottom:1px solid var(--border); background:var(--bg-card2);">
                    <h5 class="modal-title" style="color:var(--text); display:flex; align-items:center; gap:10px; font-size:15px;">
                        <i class="bi bi-currency-dollar" style="color:var(--warn-lt, #ff9f43);"></i>
                        Monto Potencial en Casos Sospechosos — {{ $selectedYear }}
                        <span style="background:rgba(255,125,59,.15); color:#ff7d37; padding:2px 10px; border-radius:20px; font-size:13px; font-weight:800;">
                            $&nbsp;{{ number_format($kpiMontoFragmentado,0,',','.') }}
                        </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color:#fff; opacity:.8; font-size:22px;">&times;</button>
                </div>
                <div class="modal-body" style="padding:0;">
                    @php $montoFrag = $casosFrag->whereIn('nivel_riesgo', ['ALTO', 'MEDIO'])->sortByDesc('monto_total'); @endphp
                    @if($montoFrag->isEmpty())
                        <div style="text-align:center; padding:2.5rem; color:var(--neutral);">
                            <i class="bi bi-check-circle-fill" style="font-size:2.5rem; color:var(--success); display:block; margin-bottom:.5rem;"></i>
                            Sin monto potencial para {{ $selectedYear }}.
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="tabla-analitica">
                                <thead>
                                    <tr>
                                        <th class="td-c">ID</th>
                                        <th>Especie</th>
                                        <th>Departamento</th>
                                        <th>Modalidad</th>
                                        <th class="th-r">Monto Total</th>
                                        <th class="th-r">N° OCs</th>
                                        <th class="th-r">Días entre<br>compras</th>
                                        <th class="td-c">¿Supera<br>100 UTM?</th>
                                        <th class="td-c">Nivel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($montoFrag as $cf)
                                        @php
                                            $bCls = $cf->nivel_riesgo === 'ALTO' ? 'badge-rojo' : 'badge-amarillo';
                                            $ocColor = $cf->cantidad_oc >= 5 ? 'var(--danger)' : ($cf->cantidad_oc >= 3 ? 'var(--warn)' : 'inherit');
                                            $diasInt = (int)round($cf->dispersion_dias);
                                            $superaUTM = (float)$cf->monto_total >= $umbralFragmentacion;
                                            $superaMed = !$superaUTM && (float)$cf->monto_total >= ($umbralFragmentacion * 0.5);
                                        @endphp
                                        <tr class="{{ $cf->nivel_riesgo === 'ALTO' ? 'frag-alta' : 'frag-media' }}">
                                            <td class="td-c" style="font-weight:700; color:var(--pri);">{{ $cf->id_proyecto }}</td>
                                            <td style="font-weight:600;">{{ $cf->especie }}</td>
                                            <td>{{ $cf->departamento }}</td>
                                            <td style="font-size:13px; color:var(--text-dim);">{{ $cf->modalidad }}</td>
                                            <td class="td-r" style="font-weight:800; font-size:15px;">$&nbsp;{{ number_format($cf->monto_total,0,',','.') }}</td>
                                            <td class="td-r" style="font-weight:800; color:{{ $ocColor }};">{{ $cf->cantidad_oc }}</td>
                                            <td class="td-r" style="font-weight:700;">{{ $diasInt > 0 ? $diasInt.' días' : '—' }}</td>
                                            <td class="td-c">
                                                @if($superaUTM)
                                                    <span class="badge-sema badge-rojo"><i class="bi bi-check-circle-fill" style="font-size:8px;"></i>&nbsp;Sí</span>
                                                @elseif($superaMed)
                                                    <span class="badge-sema badge-amarillo"><i class="bi bi-exclamation-circle-fill" style="font-size:8px;"></i>&nbsp;50-100</span>
                                                @else
                                                    <span class="badge-sema badge-bajo"><i class="bi bi-x-circle-fill" style="font-size:8px;"></i>&nbsp;No</span>
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <span class="badge-sema {{ $bCls }}"><i class="bi bi-circle-fill" style="font-size:8px;"></i> {{ $cf->nivel_riesgo }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border); background:var(--bg-card2); gap:8px;">
                    <a href="{{ route('reporte.index', ['year' => $selectedYear]) }}" class="btn-link-sec" style="text-decoration:none;">
                        <i class="bi bi-table"></i> Ver Reporte Completo
                    </a>
                    <button type="button" class="btn-link-sec" data-dismiss="modal">
                        <i class="bi bi-x"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>{{-- /db-wrap --}}
@endsection

@push('scripts')
<script src="{{ url('plugins/chartjs/chart.min.js') }}"></script>
<script src="{{ url('plugins/chartjs/chartjs-plugin-datalabels.min.js') }}"></script>
<script @cspNonce>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.autosubmit').forEach(function(sel) {
        sel.addEventListener('change', function() { this.form.submit(); });
    });

    if (typeof Chart === 'undefined') return;
    try { Chart.register(ChartDataLabels); } catch(e) {}

    const PALETA = [
        'rgba(0,212,255,.9)',    'rgba(255,125,59,.9)',   'rgba(255,184,0,.9)',
        'rgba(0,229,160,.9)',    'rgba(255,77,109,.9)',   'rgba(167,80,255,.9)',
        'rgba(59,180,255,.85)',  'rgba(255,220,50,.85)',  'rgba(45,212,191,.85)',
        'rgba(255,140,180,.85)',
    ];
    const fmtCLP = v => '$ ' + Number(v).toLocaleString('es-CL');

    // ── Dona: modalidad ──────────────────────────────────────────────────────
    new Chart(document.getElementById('chartModalidad').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($chartModalidadLabels),
            datasets: [{ data: @json($chartModalidadData), backgroundColor: PALETA, borderWidth: 1 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '55%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, color: '#94a3b8' } },
                datalabels: {
                    color: '#fff', font: { weight: 'bold', size: 11 },
                    formatter: (v, ctx) => {
                        const t = ctx.dataset.data.reduce((a,b) => a+b, 0);
                        const p = t > 0 ? Math.round(v/t*100) : 0;
                        return p >= 5 ? p + '%' : '';
                    }
                },
                tooltip: { callbacks: { label: ctx => ' ' + fmtCLP(ctx.raw) } }
            }
        }
    });

    // ── Barras + línea: concentración temporal ───────────────────────────────
    new Chart(document.getElementById('chartTemporal').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($chartTemporalLabels),
            datasets: [
                {
                    label: 'Monto OCs ($)',
                    data: @json($chartTemporalMontos),
                    backgroundColor: 'rgba(255,125,59,.7)',
                    borderColor: 'rgba(255,125,59,1)',
                    yAxisID: 'yMonto',
                    datalabels: { display: false }
                },
                {
                    label: 'N° Órdenes',
                    type: 'line',
                    data: @json($chartTemporalOCs),
                    borderColor: 'rgba(0,212,255,.95)',
                    backgroundColor: 'rgba(0,212,255,.12)',
                    tension: 0.35,
                    pointRadius: 4,
                    yAxisID: 'yOC',
                    datalabels: {
                        anchor: 'end', align: 'top',
                        color: '#00d4ff', font: { weight: 'bold', size: 11 },
                        formatter: v => v > 0 ? v : ''
                    }
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            layout: { padding: { top: 22 } },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 }, color: '#94a3b8' } },
                datalabels: { display: true },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? ' ' + fmtCLP(ctx.raw)
                            : ' ' + ctx.raw + ' órdenes'
                    }
                }
            },
            scales: {
                yMonto: {
                    type: 'linear', position: 'left', beginAtZero: true,
                    ticks: { font: { size: 10 }, color: '#6b6b8d', callback: v => fmtCLP(v) },
                    grid: { color: 'rgba(0,0,0,.05)' }
                },
                yOC: {
                    type: 'linear', position: 'right', beginAtZero: true,
                    ticks: { precision: 0, font: { size: 10 }, color: '#6b6b8d' },
                    grid: { display: false }
                },
                x: { ticks: { font: { size: 12 }, color: '#6b6b8d' } }
            }
        }
    });

    // ── Barras: presupuesto vs comprometido por depto ────────────────────────
    new Chart(document.getElementById('chartBarDepto').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($chartBarLabels),
            datasets: [
                {
                    label: 'Presupuesto Inicial',
                    data: @json($chartBarPresupuesto),
                    backgroundColor: 'rgba(167,80,255,.7)',
                    borderColor: 'rgba(167,80,255,1)',
                    datalabels: {
                        anchor: 'end', align: 'end',
                        color: '#8888aa', font: { weight: 'bold', size: 10 },
                        formatter: v => fmtCLP(v)
                    }
                },
                {
                    label: 'Total Comprometido',
                    data: @json($chartBarComprometido),
                    backgroundColor: 'rgba(0,212,255,.7)',
                    borderColor: 'rgba(0,212,255,1)',
                    datalabels: {
                        anchor: 'end', align: 'end',
                        color: '#8888aa', font: { weight: 'bold', size: 10 },
                        formatter: v => fmtCLP(v)
                    }
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            layout: { padding: { top: 28 } },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 }, color: '#94a3b8' } },
                datalabels: { display: true },
                tooltip: { callbacks: { label: ctx => ' ' + fmtCLP(ctx.raw) } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 10 }, color: '#6b6b8d', callback: v => fmtCLP(v) }
                },
                x: { ticks: { font: { size: 12 }, color: '#6b6b8d' } }
            }
        }
    });

    // ── Dona: estado licitaciones ────────────────────────────────────────────
    new Chart(document.getElementById('chartDonaLic').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($chartDonaLicLabels),
            datasets: [{ data: @json($chartDonaLicData), backgroundColor: PALETA, borderWidth: 1 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12, color: '#94a3b8' } },
                datalabels: {
                    color: '#fff', font: { weight: 'bold', size: 11 },
                    formatter: (v, ctx) => {
                        const t = ctx.dataset.data.reduce((a,b) => a+b, 0);
                        return t > 0 ? Math.round(v/t*100) + '%' : '';
                    }
                }
            }
        }
    });

    // ── Dona: estado órdenes de compra ───────────────────────────────────────
    new Chart(document.getElementById('chartDonaOC').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: @json($chartDonaOcLabels),
            datasets: [{ data: @json($chartDonaOcData), backgroundColor: PALETA, borderWidth: 1 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12, color: '#94a3b8' } },
                datalabels: {
                    color: '#fff', font: { weight: 'bold', size: 11 },
                    formatter: (v, ctx) => {
                        const t = ctx.dataset.data.reduce((a,b) => a+b, 0);
                        return t > 0 ? Math.round(v/t*100) + '%' : '';
                    }
                }
            }
        }
    });

    // ── Horizontal bars: fragmentación por especie ──────────────────────────
    @if(!empty($chartFragEspecieLabels))
    const ctxFE = document.getElementById('chartFragEspecie');
    if (ctxFE) {
        new Chart(ctxFE.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartFragEspecieLabels),
                datasets: [{
                    label: 'N° Órdenes de Compra',
                    data: @json($chartFragEspecieOCs),
                    backgroundColor: @json($chartFragEspecieOCs).map(v =>
                        v >= 5 ? 'rgba(255,77,109,.85)' : v >= 3 ? 'rgba(255,184,0,.85)' : 'rgba(0,212,255,.75)'
                    ),
                    borderRadius: 4,
                    borderWidth: 0,
                    datalabels: {
                        anchor: 'end', align: 'right',
                        color: '#fff', font: { weight: 'bold', size: 11 },
                        formatter: v => v
                    }
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                layout: { padding: { right: 36 } },
                plugins: {
                    legend: { display: false },
                    datalabels: { display: true },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const montos = @json($chartFragEspecieMontos);
                                return [
                                    ' ' + ctx.raw + ' órdenes',
                                    ' $ ' + Number(montos[ctx.dataIndex]).toLocaleString('es-CL')
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 10 }, color: '#6b6b8d' },
                        grid: { color: 'rgba(255,255,255,.04)' }
                    },
                    y: { ticks: { font: { size: 11 }, color: '#94a3b8' } }
                }
            }
        });
    }
    @endif

    // ── SECCIÓN 3: Gráficos de fragmentación de compras ──────────────────────

    // 3.1 — Barras horizontales: N° de OCs por especie (enteros, color por umbral)
    @if(!empty($chartFragEspecieLabels))
    const ctxOCsEspecie = document.getElementById('chartFragOCsEspecie');
    if (ctxOCsEspecie) {
        const ocsEsp = @json($chartFragEspecieOCs);
        new Chart(ctxOCsEspecie.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartFragEspecieLabels),
                datasets: [{
                    label: 'N° Órdenes de Compra',
                    data: ocsEsp,
                    backgroundColor: ocsEsp.map(v =>
                        v >= 5 ? 'rgba(255,77,109,.85)' :
                        v >= 3 ? 'rgba(255,184,0,.85)' : 'rgba(0,212,255,.75)'
                    ),
                    borderRadius: 4, borderWidth: 0,
                    datalabels: {
                        anchor: 'end', align: 'right',
                        color: '#fff', font: { weight: 'bold', size: 12 },
                        formatter: v => v + (v === 1 ? ' OC' : ' OCs')
                    }
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                layout: { padding: { right: 70 } },
                plugins: {
                    legend: { display: false },
                    datalabels: { display: true },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const montos = @json($chartFragEspecieMontos);
                                return [
                                    ' ' + ctx.raw + ' órdenes de compra',
                                    ' Total: $ ' + Number(montos[ctx.dataIndex]).toLocaleString('es-CL')
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, stepSize: 1, font: { size: 10 }, color: '#6b6b8d' },
                        grid: { color: 'rgba(255,255,255,.04)' },
                        title: { display: true, text: 'Número de Órdenes de Compra emitidas', color: '#6b6b8d', font: { size: 10 } }
                    },
                    y: { ticks: { font: { size: 11 }, color: '#94a3b8' } }
                }
            }
        });
    }
    @endif

    // 3.2 — Barras apiladas: N° de casos por departamento (Alto / Medio / Bajo)
    const ctxFragDept = document.getElementById('chartFragDept');
    if (ctxFragDept) {
        new Chart(ctxFragDept.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartDeptLabels),
                datasets: [
                    {
                        label: 'Riesgo Alto',
                        data: @json($chartDeptAlto),
                        backgroundColor: 'rgba(255,77,109,.85)',
                        borderRadius: 3,
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 11 },
                            formatter: v => v > 0 ? v : ''
                        }
                    },
                    {
                        label: 'Riesgo Medio',
                        data: @json($chartDeptMedio),
                        backgroundColor: 'rgba(255,184,0,.85)',
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 11 },
                            formatter: v => v > 0 ? v : ''
                        }
                    },
                    {
                        label: 'Bajo',
                        data: @json($chartDeptBajo),
                        backgroundColor: 'rgba(0,212,255,.65)',
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 11 },
                            formatter: v => v > 0 ? v : ''
                        }
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 }, color: '#94a3b8', boxWidth: 12 } },
                    datalabels: { display: true },
                    tooltip: { callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.raw + ' caso(s)' } }
                },
                scales: {
                    x: {
                        stacked: true, beginAtZero: true,
                        ticks: { precision: 0, color: '#6b6b8d', font: { size: 10 } },
                        title: { display: true, text: 'N° de casos detectados', color: '#6b6b8d', font: { size: 10 } }
                    },
                    y: { stacked: true, ticks: { color: '#94a3b8', font: { size: 11 } } }
                }
            }
        });
    }

});
</script>
@endpush
