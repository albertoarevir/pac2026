@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<style>
    /* =====================================================
       VARIABLES & RESET
    ===================================================== */
    :root {
        --pri       : #1e3a8a;
        --pri-lt    : #dbeafe;
        --pri-mid   : #2563eb;
        --success   : #16a34a;
        --success-lt: #dcfce7;
        --warn      : #d97706;
        --warn-lt   : #fef3c7;
        --danger    : #dc2626;
        --danger-lt : #fee2e2;
        --neutral   : #64748b;
        --neutral-lt: #f1f5f9;
        --border    : #e2e8f0;
        --shadow    : 0 2px 12px rgba(30,58,138,.08);
        --radius    : 12px;
    }

    /* =====================================================
       LAYOUT
    ===================================================== */
    .dash-wrap { padding: 0 28px 40px; }

    .dash-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--pri);
        margin: 0;
        letter-spacing: -.3px;
    }
    .dash-subtitle {
        font-size: 13px;
        color: var(--neutral);
        margin: 3px 0 0;
    }

    /* =====================================================
       FILTROS
    ===================================================== */
    .filtro-bar {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 20px;
        margin: 18px 0;
        box-shadow: var(--shadow);
    }
    .filtro-bar label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--neutral);
        margin-bottom: 4px;
        display: block;
    }
    .filtro-bar .form-select,
    .filtro-bar .form-control {
        font-size: 13px;
        border-color: var(--border);
        border-radius: 8px;
    }
    .filtro-bar .form-select:focus,
    .filtro-bar .form-control:focus {
        border-color: var(--pri-mid);
        box-shadow: 0 0 0 3px rgba(37,99,235,.15);
    }
    .btn-apply {
        background: var(--pri);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 13px;
        font-weight: 700;
        transition: background .2s;
    }
    .btn-apply:hover { background: var(--pri-mid); color:#fff; }
    .btn-clear {
        background: var(--neutral-lt);
        color: var(--neutral);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 600;
        transition: background .2s;
    }
    .btn-clear:hover { background: #e2e8f0; }

    /* =====================================================
       KPI CARDS
    ===================================================== */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .kpi-card {
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 16px 18px;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform .15s, box-shadow .15s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30,58,138,.13);
    }
    .kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .kpi-icon.blue   { background:var(--pri-lt);    color:var(--pri-mid); }
    .kpi-icon.green  { background:var(--success-lt); color:var(--success); }
    .kpi-icon.yellow { background:var(--warn-lt);   color:var(--warn); }
    .kpi-icon.red    { background:var(--danger-lt);  color:var(--danger); }
    .kpi-icon.gray   { background:var(--neutral-lt); color:var(--neutral); }
    .kpi-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--neutral);
        margin: 0 0 2px;
    }
    .kpi-value {
        font-size: 18px;
        font-weight: 800;
        color: var(--pri);
        margin: 0;
        line-height: 1.1;
    }
    .kpi-value.sm { font-size: 13px; }

    /* =====================================================
       CHART CARDS
    ===================================================== */
    .chart-card {
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        padding: 20px 20px 16px;
        height: 100%;
    }
    .chart-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--pri);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin: 0 0 14px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--pri-lt);
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .chart-card-title i { color: var(--pri-mid); font-size: 15px; }
    .chart-canvas-wrap {
        position: relative;
        width: 100%;
        height: 280px;
    }

    /* =====================================================
       TABLA RESUMEN
    ===================================================== */
    .tabla-wrap {
        background: #fff;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-top: 22px;
    }
    .tabla-header {
        background: var(--pri);
        color: #fff;
        padding: 14px 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tabla-resumen {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .tabla-resumen thead th {
        background: #f8faff;
        color: var(--neutral);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 10px 14px;
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
    }
    .tabla-resumen thead th:not(:first-child) { text-align: right; }
    .tabla-resumen tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
        color: #1e293b;
        vertical-align: middle;
    }
    .tabla-resumen tbody td:not(:first-child) {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }
    .tabla-resumen tbody tr:nth-child(even) { background: #f9fbff; }
    .tabla-resumen tbody tr:hover           { background: #eff4ff; }
    .tabla-resumen tfoot td {
        background: #1e3a8a;
        color: #fff;
        font-weight: 800;
        padding: 11px 14px;
        font-size: 13px;
    }
    .tabla-resumen tfoot td:not(:first-child) { text-align: right; }

    /* Barra de progreso inline */
    .progress-bar-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 160px;
    }
    .progress-mini {
        flex: 1;
        height: 7px;
        background: var(--border);
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-mini-fill {
        height: 100%;
        border-radius: 4px;
        transition: width .4s ease;
    }
    .fill-green  { background: var(--success); }
    .fill-yellow { background: var(--warn); }
    .fill-red    { background: var(--danger); }
    .pct-text {
        font-size: 11px;
        font-weight: 700;
        min-width: 38px;
        text-align: right;
    }
    .pct-green  { color: var(--success); }
    .pct-yellow { color: var(--warn); }
    .pct-red    { color: var(--danger); }
</style>

<div class="dash-wrap">

    {{-- =========================================================
         ENCABEZADO
    ========================================================= --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2" style="padding-top:18px;">
        <div>
            <h1 class="dash-title">
                <i class="bi bi-bar-chart-line-fill me-2" style="color:var(--pri-mid);"></i>
                Dashboard — Reporte de Ejecución
            </h1>
            <p class="dash-subtitle">Presupuesto, comprometido, licitaciones y órdenes de compra · Año {{ $selectedYear }}</p>
        </div>
        <a href="{{ route('reporte.index') }}" class="btn-clear" style="text-decoration:none;">
            <i class="bi bi-table me-1"></i>Ver reporte detallado
        </a>
    </div>
    <hr style="border-color:var(--border); margin: 14px 0;">

    {{-- =========================================================
         FILTROS
    ========================================================= --}}
    <div class="filtro-bar">
        <form method="GET" action="{{ route('reporte.dashboard') }}" class="row g-2 align-items-end">
            <div class="col-md-2 col-sm-6">
                <label><i class="bi bi-calendar3 me-1"></i>Año</label>
                <select name="year" class="form-select form-select-sm">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ (string)$y === (string)$selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-sm-6">
                <label><i class="bi bi-building me-1"></i>Departamento</label>
                <select name="departamento_id" class="form-select form-select-sm">
                    <option value="">— Todos los Departamentos —</option>
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}" {{ (string)$dep->id === (string)$selectedDepartamentoId ? 'selected' : '' }}>
                            {{ $dep->detalle }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2 align-items-end">
                <button type="submit" class="btn-apply"><i class="bi bi-funnel-fill me-1"></i>Aplicar</button>
                <a href="{{ route('reporte.dashboard') }}" class="btn-clear" style="text-decoration:none;">
                    <i class="bi bi-x-circle me-1"></i>Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- =========================================================
         KPI CARDS
    ========================================================= --}}
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-icon blue"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <p class="kpi-label">Presupuesto Total</p>
                <p class="kpi-value sm">$ {{ number_format($totalPresupuesto, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon green"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <p class="kpi-label">Total Comprometido</p>
                <p class="kpi-value sm">$ {{ number_format($totalComprometido, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon {{ $totalPorcentaje > 75 ? 'green' : ($totalPorcentaje >= 60 ? 'yellow' : 'red') }}">
                <i class="bi bi-percent"></i>
            </div>
            <div>
                <p class="kpi-label">% Ejecución</p>
                <p class="kpi-value">{{ $totalPorcentaje }}%</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon {{ $totalSaldo >= 0 ? 'blue' : 'red' }}"><i class="bi bi-wallet2"></i></div>
            <div>
                <p class="kpi-label">Saldo Disponible</p>
                <p class="kpi-value sm">$ {{ number_format($totalSaldo, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon gray"><i class="bi bi-clipboard-data"></i></div>
            <div>
                <p class="kpi-label">Proyectos</p>
                <p class="kpi-value">{{ number_format($totalProyectos, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon yellow"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <p class="kpi-label">Licitaciones</p>
                <p class="kpi-value">{{ number_format($totalLicitaciones, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon green"><i class="bi bi-cart-check"></i></div>
            <div>
                <p class="kpi-label">Órdenes de Compra</p>
                <p class="kpi-value">{{ number_format($totalOrdenes, 0, ',', '.') }}</p>
            </div>
        </div>

    </div>

    {{-- =========================================================
         GRÁFICOS — FILA 1
         Barras: Presupuesto vs Comprometido  |  Dona: Licitaciones
    ========================================================= --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-8 col-md-7">
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="bi bi-bar-chart-fill"></i>
                    Presupuesto vs Comprometido por Departamento
                </div>
                <div class="chart-canvas-wrap">
                    <canvas id="chartBarras"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Estado de Licitaciones
                </div>
                <div class="chart-canvas-wrap">
                    <canvas id="chartDonaLic"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         GRÁFICOS — FILA 2
         Dona: Órdenes de Compra (centrada)
    ========================================================= --}}
    <div class="row g-3 mb-3 justify-content-center">
        <div class="col-lg-5 col-md-6">
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="bi bi-pie-chart"></i>
                    Estado de Órdenes de Compra
                </div>
                <div class="chart-canvas-wrap">
                    <canvas id="chartDonaOc"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         TABLA RESUMEN POR DEPARTAMENTO
    ========================================================= --}}
    <div class="tabla-wrap">
        <div class="tabla-header">
            <i class="bi bi-table"></i>
            Resumen por Departamento — Año {{ $selectedYear }}
        </div>
        <div style="overflow-x:auto;">
            <table class="tabla-resumen">
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th>Presupuesto Inicial</th>
                        <th>Total Comprometido</th>
                        <th>Saldo Disponible</th>
                        <th>% Ejecución</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tablaDepartamentos as $fila)
                        @php
                            $pct   = $fila['porcentaje'];
                            $color = $pct > 75 ? 'green' : ($pct >= 60 ? 'yellow' : 'red');
                        @endphp
                        <tr>
                            <td><strong>{{ $fila['departamento'] }}</strong></td>
                            <td>$ {{ number_format($fila['presupuesto'], 0, ',', '.') }}</td>
                            <td>$ {{ number_format($fila['comprometido'], 0, ',', '.') }}</td>
                            <td>
                                @if($fila['saldo'] < 0)
                                    <span style="color:var(--danger); font-weight:700;">
                                        $ {{ number_format($fila['saldo'], 0, ',', '.') }}
                                    </span>
                                @else
                                    $ {{ number_format($fila['saldo'], 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                <div class="progress-bar-wrap">
                                    <div class="progress-mini">
                                        <div class="progress-mini-fill fill-{{ $color }}"
                                             style="width: {{ min($pct, 100) }}%"></div>
                                    </div>
                                    <span class="pct-text pct-{{ $color }}">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--neutral); padding:2rem;">
                                <i class="bi bi-inbox" style="font-size:1.6rem; display:block; margin-bottom:.4rem;"></i>
                                No hay datos para los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($tablaDepartamentos->count() > 0)
                <tfoot>
                    <tr>
                        <td>TOTAL GENERAL</td>
                        <td>$ {{ number_format($totalPresupuesto, 0, ',', '.') }}</td>
                        <td>$ {{ number_format($totalComprometido, 0, ',', '.') }}</td>
                        <td>$ {{ number_format($totalSaldo, 0, ',', '.') }}</td>
                        <td>{{ $totalPorcentaje }}%</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>

{{-- =====================================================
     SCRIPTS DE GRÁFICOS
===================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.register(ChartDataLabels);

    const PALETA = [
        'rgba(37,99,235,.75)',  'rgba(22,163,74,.75)',  'rgba(217,119,6,.75)',
        'rgba(220,38,38,.75)',  'rgba(124,58,237,.75)', 'rgba(14,165,233,.75)',
        'rgba(234,88,12,.75)',  'rgba(99,102,241,.75)', 'rgba(20,184,166,.75)',
        'rgba(236,72,153,.75)',
    ];
    const PALETA_BORDER = PALETA.map(c => c.replace('.75)', '1)'));

    // -------------------------------------------------------
    // 1. BARRAS: Presupuesto vs Comprometido
    // -------------------------------------------------------
    const labelsBar        = @json($chartBarLabels);
    const dataPresupuesto  = @json($chartBarPresupuesto);
    const dataComprometido = @json($chartBarComprometido);

    new Chart(document.getElementById('chartBarras').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labelsBar,
            datasets: [
                {
                    label: 'Presupuesto Inicial',
                    data: dataPresupuesto,
                    backgroundColor: 'rgba(37,99,235,.7)',
                    borderColor: 'rgba(37,99,235,1)',
                    datalabels: { display: false }
                },
                {
                    label: 'Comprometido',
                    data: dataComprometido,
                    backgroundColor: 'rgba(22,163,74,.7)',
                    borderColor: 'rgba(22,163,74,1)',
                    datalabels: { display: false }
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 } } },
                datalabels: { display: false },
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

    // -------------------------------------------------------
    // 2. DONA: Estado Licitaciones
    // -------------------------------------------------------
    const labelsLic = @json($chartDonaLicLabels);
    const dataLic   = @json($chartDonaLicData);

    new Chart(document.getElementById('chartDonaLic').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labelsLic,
            datasets: [{
                data: dataLic,
                backgroundColor: PALETA,
                borderColor: PALETA_BORDER,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 11 },
                    formatter: (val, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return total > 0 ? Math.round(val / total * 100) + '%' : '';
                    }
                }
            }
        }
    });

    // -------------------------------------------------------
    // 3. DONA: Estado Órdenes de Compra
    // -------------------------------------------------------
    const labelsOc = @json($chartDonaOcLabels);
    const dataOc   = @json($chartDonaOcData);

    new Chart(document.getElementById('chartDonaOc').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labelsOc,
            datasets: [{
                data: dataOc,
                backgroundColor: PALETA,
                borderColor: PALETA_BORDER,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold', size: 11 },
                    formatter: (val, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return total > 0 ? Math.round(val / total * 100) + '%' : '';
                    }
                }
            }
        }
    });
});
</script>

@endsection
