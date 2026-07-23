import sys, re
sys.stdout = open(sys.stdout.fileno(), 'w', encoding='utf-8', closefd=False)

path = 'z:/app/Http/Controllers/ReporteDashboardController.php'
with open(path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_engine = r"""        // ── MOTOR DE DETECCIÓN DE FRAGMENTACIÓN ─────────────────────────────────

        // Reglas configurables del motor (parametrizables)
        $reglasFragmentacion = [
            'alto'  => ['cantidad_oc' => 5,  'dispersion_dias' => 45, 'monto_min' => $umbralFragmentacion],
            'medio' => ['cantidad_oc' => 2,  'dispersion_dias' => 90, 'monto_min' => $umbralFragmentacion * 0.5],
        ];

        // Query base: agrupa por proyecto + modalidad, solo casos con más de 1 OC
        $rawFrag = DB::table('pacs')
            ->select([
                'pacs.id as id_proyecto',
                'departamentos.detalle as departamento',
                DB::raw("COALESCE(especies.detalle, 'Sin especie') as especie"),
                'pacs.codigo as item_presupuestario',
                'modalidads.modalidad',
                DB::raw('COUNT(DISTINCT ordens.id) as cantidad_oc'),
                DB::raw('COALESCE(SUM(ordens.monto), 0) as monto_total'),
                DB::raw('COALESCE(AVG(ordens.monto), 0) as promedio_oc'),
                DB::raw('GREATEST(COALESCE(EXTRACT(EPOCH FROM (MAX(ordens.fecha_seguimiento) - MIN(ordens.fecha_seguimiento)))/86400, 0), 0) as dispersion_dias'),
                DB::raw('MIN(ordens.fecha_seguimiento) as primera_oc'),
                DB::raw('MAX(ordens.fecha_seguimiento) as ultima_oc'),
                DB::raw('COALESCE(EXTRACT(MONTH FROM MIN(ordens.fecha_seguimiento))::int, 0) as mes_inicio'),
            ])
            ->join('departamentos', 'pacs.departamento_id', '=', 'departamentos.id')
            ->leftJoin('especies', 'pacs.especie_id', '=', 'especies.id')
            ->join('modalidads', 'modalidads.id_proyecto', '=', 'pacs.id')
            ->join('ordens', 'ordens.id_proyecto', '=', 'pacs.id')
            ->where('pacs.year', $selectedYear)
            ->whereNotNull('ordens.fecha_seguimiento')
            ->when($selectedDepartamentoId, fn($q) => $q->where('pacs.departamento_id', $selectedDepartamentoId))
            ->groupBy('pacs.id', 'departamentos.detalle', 'especies.detalle', 'pacs.codigo', 'modalidads.modalidad')
            ->having(DB::raw('COUNT(DISTINCT ordens.id)'), '>', 1)
            ->get();

        // Motor de scoring multidimensional 0-100
        $casosFrag = $rawFrag->map(function ($item) use ($umbralFragmentacion, $reglasFragmentacion) {
            $item->cantidad_oc     = (int)$item->cantidad_oc;
            $item->monto_total     = (float)$item->monto_total;
            $item->promedio_oc     = (float)$item->promedio_oc;
            $item->dispersion_dias = (float)$item->dispersion_dias;

            $score = 0;

            // Dimensión 1 — Frecuencia de OC (peso 35 pts)
            $score += match(true) {
                $item->cantidad_oc >= 10 => 35,
                $item->cantidad_oc >= 7  => 28,
                $item->cantidad_oc >= 5  => 22,
                $item->cantidad_oc >= 3  => 12,
                default                  => 5,
            };

            // Dimensión 2 — Monto relativo al umbral 100 UTM (peso 35 pts)
            $ratio = $umbralFragmentacion > 0 ? $item->monto_total / $umbralFragmentacion : 0;
            $score += match(true) {
                $ratio >= 3.0 => 35,
                $ratio >= 2.0 => 28,
                $ratio >= 1.0 => 20,
                $ratio >= 0.5 => 10,
                default       => 3,
            };

            // Dimensión 3 — Temporalidad (peso 20 pts, menor dispersión = mayor riesgo)
            $score += match(true) {
                $item->dispersion_dias <= 7  => 20,
                $item->dispersion_dias <= 15 => 17,
                $item->dispersion_dias <= 30 => 12,
                $item->dispersion_dias <= 45 => 7,
                $item->dispersion_dias <= 90 => 3,
                default                      => 0,
            };

            // Dimensión 4 — Modalidad de menor cuantía (peso 10 pts)
            $item->modalidad_baja = (bool)preg_match('/trato directo|compra.?agil|compra directa/i', $item->modalidad);
            $score += $item->modalidad_baja ? 10 : 0;

            $item->score_riesgo = min(100, $score);

            // Clasificación por reglas parametrizables
            $r = $reglasFragmentacion;
            $esAlto = $item->cantidad_oc    >= $r['alto']['cantidad_oc']
                   && $item->dispersion_dias <= $r['alto']['dispersion_dias']
                   && $item->monto_total     >= $r['alto']['monto_min'];

            $esMedio = !$esAlto && (
                $item->cantidad_oc >= $r['medio']['cantidad_oc']
                && ($item->dispersion_dias <= $r['medio']['dispersion_dias']
                    || $item->monto_total  >= $r['medio']['monto_min'])
            );

            $item->nivel_riesgo = $esAlto ? 'ALTO' : ($esMedio ? 'MEDIO' : 'BAJO');
            return $item;
        })->sortByDesc('score_riesgo')->values();

        // ── KPIs del módulo ──────────────────────────────────────────────────
        $kpiTotalOC          = $casosFrag->sum('cantidad_oc');
        $kpiCasosSospechosos = $casosFrag->whereIn('nivel_riesgo', ['ALTO', 'MEDIO'])->count();
        $kpiRiesgoAlto       = $casosFrag->where('nivel_riesgo', 'ALTO')->count();
        $kpiMontoFragmentado = $casosFrag->whereIn('nivel_riesgo', ['ALTO', 'MEDIO'])->sum('monto_total');

        // ── Chart: Top 10 especies por score ────────────────────────────────
        $chartEspeciesRiesgo = $casosFrag
            ->groupBy('especie')
            ->map(fn($g, $k) => (object)[
                'especie' => $k,
                'score'   => $g->max('score_riesgo'),
                'cantidad'=> $g->sum('cantidad_oc'),
                'monto'   => $g->sum('monto_total'),
                'nivel'   => $g->sortByDesc('score_riesgo')->first()->nivel_riesgo,
            ])
            ->sortByDesc('score')
            ->take(10)
            ->values();

        $chartEspecieLabels  = $chartEspeciesRiesgo->pluck('especie')->toArray();
        $chartEspecieScores  = $chartEspeciesRiesgo->pluck('score')->toArray();
        $chartEspecieMontos  = $chartEspeciesRiesgo->pluck('monto')->toArray();
        $chartEspecieNiveles = $chartEspeciesRiesgo->pluck('nivel')->toArray();

        // ── Chart: Riesgo por departamento (stacked) ─────────────────────────
        $chartRiesgoDept = $casosFrag
            ->groupBy('departamento')
            ->map(fn($g, $k) => (object)[
                'departamento' => $k,
                'alto'  => $g->where('nivel_riesgo', 'ALTO')->count(),
                'medio' => $g->where('nivel_riesgo', 'MEDIO')->count(),
                'bajo'  => $g->where('nivel_riesgo', 'BAJO')->count(),
            ])
            ->sortByDesc('alto')
            ->take(12)
            ->values();

        $chartDeptLabels = $chartRiesgoDept->pluck('departamento')->toArray();
        $chartDeptAlto   = $chartRiesgoDept->pluck('alto')->toArray();
        $chartDeptMedio  = $chartRiesgoDept->pluck('medio')->toArray();
        $chartDeptBajo   = $chartRiesgoDept->pluck('bajo')->toArray();

        // ── Chart: Evolución temporal mensual ────────────────────────────────
        $evolucionMeses = $casosFrag
            ->filter(fn($r) => $r->mes_inicio > 0)
            ->groupBy('mes_inicio')
            ->map(fn($g, $m) => ['mes' => (int)$m, 'casos' => $g->count(), 'monto' => $g->sum('monto_total')])
            ->keyBy('mes');

        $chartEvolucionLabels = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $chartEvolucionCasos  = [];
        $chartEvolucionMontos = [];
        for ($m = 1; $m <= 12; $m++) {
            $d = $evolucionMeses->get($m);
            $chartEvolucionCasos[]  = $d ? (int)$d['casos']   : 0;
            $chartEvolucionMontos[] = $d ? (float)$d['monto']  : 0;
        }

        // ── Chart: Modalidades en casos sospechosos ───────────────────────────
        $chartModalidadFrag = $casosFrag
            ->whereIn('nivel_riesgo', ['ALTO', 'MEDIO'])
            ->groupBy('modalidad')
            ->map(fn($g, $k) => (object)['modalidad' => $k, 'casos' => $g->count()])
            ->sortByDesc('casos')
            ->values();

        $chartFragModLabels = $chartModalidadFrag->pluck('modalidad')->toArray();
        $chartFragModData   = $chartModalidadFrag->pluck('casos')->toArray();

        // ── Heatmap: Especie × Departamento ──────────────────────────────────
        $heatmapEspecies = $casosFrag->pluck('especie')->unique()->take(8)->values()->toArray();
        $heatmapDeptos   = $casosFrag->pluck('departamento')->unique()->take(8)->values()->toArray();
        $heatmapMatrix   = [];
        foreach ($heatmapEspecies as $esp) {
            $row = [];
            foreach ($heatmapDeptos as $dep) {
                $match = $casosFrag->where('especie', $esp)->where('departamento', $dep);
                $row[] = [
                    'oc'    => $match->sum('cantidad_oc'),
                    'monto' => $match->sum('monto_total'),
                    'score' => $match->isNotEmpty() ? (int)$match->max('score_riesgo') : 0,
                ];
            }
            $heatmapMatrix[] = $row;
        }

        // Compatibilidad con variables anteriores referenciadas en vista
        $sospechaNFragmentacion  = $casosFrag->take(20);
        $fragmentacionPorEspecie = $casosFrag
            ->groupBy('especie')
            ->map(fn($g, $k) => (object)[
                'especie'     => $k,
                'num_ordenes' => $g->sum('cantidad_oc'),
                'monto_total' => $g->sum('monto_total'),
            ])
            ->filter(fn($r) => $r->num_ordenes > 1)
            ->sortByDesc('num_ordenes')
            ->take(15)->values();

        $chartFragEspecieLabels = $fragmentacionPorEspecie->pluck('especie')->toArray();
        $chartFragEspecieOCs    = $fragmentacionPorEspecie->map(fn($r) => (int)$r->num_ordenes)->toArray();
        $chartFragEspecieMontos = $fragmentacionPorEspecie->map(fn($r) => (float)$r->monto_total)->toArray();

"""

# Lines are 1-indexed; python list is 0-indexed
# Replace lines 180-234 (indices 179-233)
new_lines = lines[:179] + [new_engine] + lines[234:]
with open(path, 'w', encoding='utf-8') as f:
    f.writelines(new_lines)
print('OK')
