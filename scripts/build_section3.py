import sys
sys.stdout = open(sys.stdout.fileno(), 'w', encoding='utf-8', closefd=False)

path = 'z:/resources/views/reporte/dashboard.blade.php'
with open(path,'r',encoding='utf-8') as f:
    c = f.read()

ANCHOR_START = """    {{-- ============================================================
         SECCIÓN 3 — FRAGMENTACIÓN DE COMPRAS
    ============================================================ --}}"""

ANCHOR_END = """    {{-- ============================================================
         SECCIÓN 4 — DISTRIBUCIÓN POR MODALIDAD
    ============================================================ --}}"""

if ANCHOR_START not in c:
    print('START NOT FOUND'); exit(1)
if ANCHOR_END not in c:
    print('END NOT FOUND'); exit(1)

NEW_SECTION = """    {{-- ============================================================
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
                <div>
                    <p class="kpi-label">OCs Analizadas</p>
                    <p class="kpi-value">{{ number_format($kpiTotalOC,0,',','.') }}</p>
                    <p class="kpi-meta">con múltiples compras por especie</p>
                </div>
            </div>
            <div class="kpi-card {{ $kpiCasosSospechosos > 0 ? 'yellow' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <p class="kpi-label">Casos Sospechosos</p>
                    <p class="kpi-value">{{ $kpiCasosSospechosos }}</p>
                    <p class="kpi-meta">riesgo alto o medio detectado</p>
                </div>
            </div>
            <div class="kpi-card {{ $kpiRiesgoAlto > 0 ? 'red' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-shield-x"></i></div>
                <div>
                    <p class="kpi-label">Riesgo Alto</p>
                    <p class="kpi-value">{{ $kpiRiesgoAlto }}</p>
                    <p class="kpi-meta">casos de máxima prioridad</p>
                </div>
            </div>
            <div class="kpi-card {{ $kpiMontoFragmentado > 0 ? 'orange' : 'green' }}">
                <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <p class="kpi-label">Monto Potencial</p>
                    <p class="kpi-value" style="font-size:16px;">
                        $&nbsp;{{ number_format($kpiMontoFragmentado,0,',','.') }}
                    </p>
                    <p class="kpi-meta">en casos sospechosos</p>
                </div>
            </div>
        </div>

        {{-- ── Motor de reglas: descripción ───────────────────────────────── --}}
        <div class="info-box" style="margin-bottom:16px;">
            <div style="display:flex; align-items:flex-start; gap:20px; flex-wrap:wrap;">
                <div style="flex:1; min-width:260px;">
                    <strong>Motor de Detección Basado en Reglas — Score 0 a 100</strong><br>
                    Analiza patrones por especie, departamento y temporalidad. El score combina:
                    <strong>frecuencia de OC</strong> (35 pts),
                    <strong>monto vs. umbral 100 UTM</strong> (35 pts),
                    <strong>concentración temporal</strong> (20 pts) y
                    <strong>modalidad de menor cuantía</strong> (10 pts).<br>
                    <em>Umbral 100 UTM:
                        <strong style="color:var(--pri);">$&nbsp;{{ number_format($umbralFragmentacion,0,',','.') }}</strong>
                        &mdash; UTM: $&nbsp;{{ number_format($valorUTM,0,',','.') }}
                    </em>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:8px; align-items:flex-start;">
                    <div class="criterio-pill rojo">
                        <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                        <span>
                            ALTO &ge; 60 pts<br>
                            <small style="font-size:11px; font-weight:400; opacity:.8;">
                                &ge;{{ $reglasFragmentacion['alto']['cantidad_oc'] }} OCs
                                &middot; &le;{{ $reglasFragmentacion['alto']['dispersion_dias'] }} días
                                &middot; &ge;100 UTM
                            </small>
                        </span>
                    </div>
                    <div class="criterio-pill amarillo">
                        <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                        <span>
                            MEDIO &ge; 30 pts<br>
                            <small style="font-size:11px; font-weight:400; opacity:.8;">
                                &ge;{{ $reglasFragmentacion['medio']['cantidad_oc'] }} OCs
                                &middot; &le;{{ $reglasFragmentacion['medio']['dispersion_dias'] }} días
                                ó &ge;50 UTM
                            </small>
                        </span>
                    </div>
                    <div class="criterio-pill verde">
                        <i class="bi bi-circle-fill" style="font-size:8px;"></i>
                        <span>
                            BAJO &lt; 30 pts<br>
                            <small style="font-size:11px; font-weight:400; opacity:.8;">No cumple criterios anteriores</small>
                        </span>
                    </div>
                </div>
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

        {{-- ── FILA 1: Top especies + Riesgo por departamento ─────────────── --}}
        <div class="row g-3" style="margin-bottom:14px;">
            <div class="col-lg-6">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-bar-chart-horizontal-fill"></i>Top Especies por Score de Riesgo
                    </div>
                    <div class="chart-wrap"
                         style="height:{{ min(360, max(200, count($chartEspecieLabels) * 34)) }}px;">
                        <canvas id="chartFragEspeciesScore"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-building"></i>Concentración de Casos por Departamento
                    </div>
                    <div class="chart-wrap"
                         style="height:{{ min(360, max(200, count($chartDeptLabels) * 34)) }}px;">
                        <canvas id="chartFragDept"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── FILA 2: Evolución temporal + Modalidades ────────────────────── --}}
        <div class="row g-3" style="margin-bottom:14px;">
            <div class="col-lg-7">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-graph-up-arrow"></i>Evolución Temporal de Casos Detectados
                    </div>
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="chartFragEvolucion"></canvas>
                    </div>
                    <p style="font-size:11px; color:var(--neutral); margin:6px 0 0;">
                        <i class="bi bi-info-circle me-1"></i>
                        Casos agrupados por mes de primera OC detectada en el período.
                    </p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="chart-card h-100">
                    <div class="chart-card-title">
                        <i class="bi bi-pie-chart-fill"></i>Modalidades en Casos Sospechosos
                    </div>
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="chartFragModalidad"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── HEATMAP: Especie × Departamento ────────────────────────────── --}}
        @if(count($heatmapEspecies) > 0 && count($heatmapDeptos) > 0)
        <div class="chart-card" style="margin-bottom:14px;">
            <div class="chart-card-title">
                <i class="bi bi-grid-3x3"></i>Mapa de Calor — Concentración Especie &times; Departamento
                <span style="font-size:11px; font-weight:400; color:var(--text-dim); margin-left:8px;">
                    (valor = N° de OCs &middot; color = nivel de riesgo)
                </span>
            </div>
            <div style="overflow-x:auto;">
                <table class="heatmap-table">
                    <thead>
                        <tr>
                            <th class="th-especie">Especie</th>
                            @foreach($heatmapDeptos as $hd)
                                <th title="{{ $hd }}">
                                    {{ \Illuminate\Support\Str::limit($hd, 14) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($heatmapEspecies as $ei => $esp)
                            <tr>
                                <td style="text-align:left; font-weight:600; color:var(--text); white-space:nowrap;">
                                    {{ \Illuminate\Support\Str::limit($esp, 32) }}
                                </td>
                                @foreach($heatmapMatrix[$ei] as $cell)
                                    @php
                                        $hcls = $cell['oc'] === 0
                                            ? 'hm-0'
                                            : ($cell['score'] >= 60 ? 'hm-hi'
                                                : ($cell['score'] >= 30 ? 'hm-mid' : 'hm-low'));
                                    @endphp
                                    <td class="{{ $hcls }}"
                                        title="OCs: {{ $cell['oc'] }} — Score: {{ $cell['score'] }} — ${{ number_format($cell['monto'],0,',','.') }}">
                                        {{ $cell['oc'] > 0 ? $cell['oc'] : '—' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p style="font-size:11px; color:var(--neutral); margin-top:6px;">
                <i class="bi bi-info-circle me-1"></i>
                Color: <span style="color:var(--danger); font-weight:700;">&#9632;</span> Alto (score&ge;60) &nbsp;
                <span style="color:var(--warn); font-weight:700;">&#9632;</span> Medio (score&ge;30) &nbsp;
                <span style="color:var(--pri); font-weight:700;">&#9632;</span> Bajo &nbsp;
                <span style="color:var(--neutral);">&#9632;</span> Sin datos.
                Pasa el cursor sobre cada celda para ver el detalle.
            </p>
        </div>
        @endif

        {{-- ── TABLA PRINCIPAL: Top 20 casos con score ────────────────────── --}}
        <div style="border-radius:var(--radius); overflow:hidden; border:1px solid var(--border);
                    box-shadow:var(--shadow-sm); overflow-x:auto; margin-bottom:8px;">
            <div style="background:rgba(255,77,109,.07); color:var(--danger); padding:11px 18px;
                        font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:.1em;
                        border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px;">
                <i class="bi bi-table"></i>
                Top {{ min($casosFrag->count(), 20) }} Casos Detectados — Ordenados por Score de Riesgo
            </div>
            <table class="tabla-analitica">
                <thead>
                    <tr>
                        <th>Especie</th>
                        <th>Departamento</th>
                        <th class="td-c">Ítem</th>
                        <th>Modalidad</th>
                        <th class="th-r">OCs</th>
                        <th class="th-r">Monto Total</th>
                        <th class="th-r">Prom./OC</th>
                        <th class="th-r">Disp. días</th>
                        <th style="min-width:150px; padding-left:12px;">Score 0–100</th>
                        <th class="td-c">Riesgo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($casosFrag->take(20) as $cf)
                        @php
                            $rowCls  = $cf->nivel_riesgo === 'ALTO' ? 'frag-alta' : ($cf->nivel_riesgo === 'MEDIO' ? 'frag-media' : '');
                            $sCls    = $cf->nivel_riesgo === 'ALTO' ? 'alto' : ($cf->nivel_riesgo === 'MEDIO' ? 'medio' : 'bajo');
                            $bCls    = $cf->nivel_riesgo === 'ALTO' ? 'badge-rojo' : ($cf->nivel_riesgo === 'MEDIO' ? 'badge-amarillo' : 'badge-bajo');
                            $ocColor = $cf->cantidad_oc >= 5 ? 'var(--danger)' : ($cf->cantidad_oc >= 3 ? 'var(--warn)' : 'inherit');
                            $dColor  = $cf->dispersion_dias > 0 && $cf->dispersion_dias <= 30
                                        ? 'var(--danger)'
                                        : ($cf->dispersion_dias <= 90 ? 'var(--warn)' : 'inherit');
                        @endphp
                        <tr class="{{ $rowCls }}">
                            <td style="font-weight:600;">{{ $cf->especie }}</td>
                            <td>{{ $cf->departamento }}</td>
                            <td class="td-c" style="color:var(--text-dim); font-size:13px;">
                                {{ $cf->item_presupuestario ?? '—' }}
                            </td>
                            <td style="font-size:13px;">{{ $cf->modalidad }}</td>
                            <td class="td-r" style="font-weight:800; color:{{ $ocColor }};">
                                {{ $cf->cantidad_oc }}
                            </td>
                            <td class="td-r">$&nbsp;{{ number_format($cf->monto_total,0,',','.') }}</td>
                            <td class="td-r" style="color:var(--text-dim); font-size:13px;">
                                $&nbsp;{{ number_format($cf->promedio_oc,0,',','.') }}
                            </td>
                            <td class="td-r" style="color:{{ $dColor }}; font-weight:700;">
                                {{ $cf->dispersion_dias > 0 ? number_format($cf->dispersion_dias,0,',','.').'d' : '—' }}
                            </td>
                            <td style="padding:8px 12px;">
                                <div class="score-bar-wrap">
                                    <div class="score-bar">
                                        <div class="score-fill score-fill-{{ $sCls }}"
                                             style="width:{{ $cf->score_riesgo }}%;"></div>
                                    </div>
                                    <span class="score-num score-{{ $sCls }}">{{ $cf->score_riesgo }}</span>
                                </div>
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
            Análisis basado en especie + departamento + temporalidad. Sin datos de proveedor — revisión manual recomendada.
            Para revisión completa use el
            <a href="{{ route('reporte.index') }}" style="color:var(--pri-mid);">Reporte Detallado</a>.
        </p>

        @endif
    </div>

    {{-- ============================================================
         SECCIÓN 4 — DISTRIBUCIÓN POR MODALIDAD
    ============================================================ --}}"""

idx_start = c.find(ANCHOR_START)
idx_end   = c.find(ANCHOR_END)

new_c = c[:idx_start] + NEW_SECTION + c[idx_end + len(ANCHOR_END):]
with open(path,'w',encoding='utf-8') as f:
    f.write(new_c)
print('OK - section 3 replaced')
