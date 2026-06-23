import sys
sys.stdout = open(sys.stdout.fileno(), 'w', encoding='utf-8', closefd=False)

path = 'z:/resources/views/reporte/dashboard.blade.php'
with open(path,'r',encoding='utf-8') as f:
    c = f.read()

# ── 1. CSS: añadir antes de </style> ──────────────────────────────────────────
NEW_CSS = """
/* ── Score bar ─────────────────────────────────────────────────────── */
.score-bar-wrap { display:flex; align-items:center; gap:8px; }
.score-bar { flex:1; height:7px; border-radius:4px; background:rgba(255,255,255,.08); min-width:60px; }
.score-fill { height:100%; border-radius:4px; }
.score-fill-alto  { background:linear-gradient(to right,var(--danger),#c026d3); }
.score-fill-medio { background:linear-gradient(to right,var(--warn),var(--orange)); }
.score-fill-bajo  { background:linear-gradient(to right,var(--pri),var(--success)); }
.score-num { font-size:13px; font-weight:800; min-width:28px; text-align:right; }
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
</style>"""

c = c.replace('</style>', NEW_CSS, 1)

# ── 2. JS: añadir 4 nuevos gráficos antes del cierre del DOMContentLoaded ─────
NEW_JS = r"""
    // ── SECCIÓN 3: Gráficos del motor de fragmentación ──────────────────────

    // 3.1 — Horizontal bar: Top especies por score
    const ctxEspeciesScore = document.getElementById('chartFragEspeciesScore');
    if (ctxEspeciesScore) {
        const nivelesEsp = @json($chartEspecieNiveles);
        new Chart(ctxEspeciesScore.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartEspecieLabels),
                datasets: [{
                    label: 'Score de Riesgo',
                    data: @json($chartEspecieScores),
                    backgroundColor: nivelesEsp.map(n =>
                        n === 'ALTO'  ? 'rgba(255,77,109,.85)' :
                        n === 'MEDIO' ? 'rgba(255,184,0,.85)'  : 'rgba(0,212,255,.75)'
                    ),
                    borderRadius: 4,
                    borderWidth: 0,
                    datalabels: {
                        anchor: 'end', align: 'right',
                        color: '#fff', font: { weight: 'bold', size: 11 },
                        formatter: v => v + ' pts'
                    }
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                layout: { padding: { right: 50 } },
                plugins: {
                    legend: { display: false },
                    datalabels: { display: true },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const montos = @json($chartEspecieMontos);
                                return [
                                    ' Score: ' + ctx.raw + ' / 100',
                                    ' Monto: $ ' + Number(montos[ctx.dataIndex]).toLocaleString('es-CL')
                                ];
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true, max: 100,
                        ticks: { font: { size: 10 }, color: '#6b6b8d' },
                        grid: { color: 'rgba(255,255,255,.04)' }
                    },
                    y: { ticks: { font: { size: 11 }, color: '#94a3b8' } }
                }
            }
        });
    }

    // 3.2 — Stacked bar: Riesgo por departamento
    const ctxFragDept = document.getElementById('chartFragDept');
    if (ctxFragDept) {
        new Chart(ctxFragDept.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartDeptLabels),
                datasets: [
                    {
                        label: 'Alto',
                        data: @json($chartDeptAlto),
                        backgroundColor: 'rgba(255,77,109,.85)',
                        borderRadius: 3,
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 10 },
                            formatter: v => v > 0 ? v : ''
                        }
                    },
                    {
                        label: 'Medio',
                        data: @json($chartDeptMedio),
                        backgroundColor: 'rgba(255,184,0,.85)',
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 10 },
                            formatter: v => v > 0 ? v : ''
                        }
                    },
                    {
                        label: 'Bajo',
                        data: @json($chartDeptBajo),
                        backgroundColor: 'rgba(0,212,255,.65)',
                        datalabels: {
                            color: '#fff', font: { weight: 'bold', size: 10 },
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
                    x: { stacked: true, beginAtZero: true, ticks: { precision:0, color:'#6b6b8d', font:{size:10} } },
                    y: { stacked: true, ticks: { color: '#94a3b8', font: { size: 11 } } }
                }
            }
        });
    }

    // 3.3 — Bar + Line: Evolución temporal mensual
    const ctxFragEvol = document.getElementById('chartFragEvolucion');
    if (ctxFragEvol) {
        new Chart(ctxFragEvol.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($chartEvolucionLabels),
                datasets: [
                    {
                        label: 'Monto casos ($)',
                        data: @json($chartEvolucionMontos),
                        backgroundColor: 'rgba(255,77,109,.6)',
                        borderColor: 'rgba(255,77,109,1)',
                        yAxisID: 'yMonto',
                        datalabels: { display: false }
                    },
                    {
                        label: 'N° Casos',
                        type: 'line',
                        data: @json($chartEvolucionCasos),
                        borderColor: 'rgba(255,184,0,.95)',
                        backgroundColor: 'rgba(255,184,0,.12)',
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(255,184,0,1)',
                        yAxisID: 'yCasos',
                        datalabels: {
                            anchor: 'end', align: 'top',
                            color: '#ffb800', font: { weight: 'bold', size: 11 },
                            formatter: v => v > 0 ? v : ''
                        }
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                layout: { padding: { top: 20 } },
                plugins: {
                    legend: { position: 'top', labels: { color:'#94a3b8', font:{size:11}, boxWidth:12 } },
                    datalabels: { display: true },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.datasetIndex === 0
                                ? ' $ ' + Number(ctx.raw).toLocaleString('es-CL')
                                : ' ' + ctx.raw + ' caso(s)'
                        }
                    }
                },
                scales: {
                    yMonto: {
                        type: 'linear', position: 'left', beginAtZero: true,
                        ticks: { color:'#6b6b8d', font:{size:10}, callback: v => '$ ' + Number(v).toLocaleString('es-CL') },
                        grid: { color: 'rgba(255,255,255,.04)' }
                    },
                    yCasos: {
                        type: 'linear', position: 'right', beginAtZero: true,
                        ticks: { precision:0, color:'#6b6b8d', font:{size:10} },
                        grid: { display: false }
                    },
                    x: { ticks: { color:'#6b6b8d', font:{size:11} } }
                }
            }
        });
    }

    // 3.4 — Donut: Modalidades en casos sospechosos
    const ctxFragMod = document.getElementById('chartFragModalidad');
    if (ctxFragMod) {
        new Chart(ctxFragMod.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($chartFragModLabels),
                datasets: [{ data: @json($chartFragModData), backgroundColor: PALETA, borderWidth: 1 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '55%',
                plugins: {
                    legend: { position:'bottom', labels:{ color:'#94a3b8', font:{size:10}, boxWidth:12 } },
                    datalabels: {
                        color: '#fff', font: { weight:'bold', size:11 },
                        formatter: (v, ctx) => {
                            const t = ctx.dataset.data.reduce((a,b)=>a+b,0);
                            const p = t > 0 ? Math.round(v/t*100) : 0;
                            return p >= 8 ? p + '%' : '';
                        }
                    },
                    tooltip: { callbacks: { label: ctx => ' ' + ctx.raw + ' caso(s)' } }
                }
            }
        });
    }

});
</script>
@endsection"""

c = c.replace('});\n</script>\n@endsection', NEW_JS, 1)

with open(path,'w',encoding='utf-8') as f:
    f.write(c)
print('OK - CSS and JS added')
