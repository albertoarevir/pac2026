import sys
sys.stdout = open(sys.stdout.fileno(), 'w', encoding='utf-8', closefd=False)

path = 'z:/app/Http/Controllers/ReporteDashboardController.php'
with open(path,'r',encoding='utf-8') as f:
    c = f.read()

old = """return view('reporte.dashboard', compact('isAdmin', 'resumenUsuarios',
            'availableYears', 'selectedYear', 'departamentos', 'selectedDepartamentoId',
            'chartBarLabels', 'chartBarPresupuesto', 'chartBarComprometido',
            'tablaDepartamentos', 'totalPresupuesto', 'totalComprometido', 'totalSaldo', 'totalPorcentaje',
            'chartDonaLicLabels', 'chartDonaLicData', 'chartDonaOcLabels', 'chartDonaOcData',
            'totalProyectos', 'totalLicitaciones', 'totalOrdenes',
            'proyectosSinLicitacion', 'proyectosSinOC', 'proyectosSaldoNegativo', 'licitacionesProblematicas',
            'sospechaNFragmentacion', 'umbralFragmentacion', 'valorUTM',
            'fragmentacionPorEspecie', 'chartFragEspecieLabels', 'chartFragEspecieOCs', 'chartFragEspecieMontos',
            'distribucionModalidad', 'totalMontoModalidad', 'chartModalidadLabels', 'chartModalidadData',
            'chartTemporalLabels', 'chartTemporalOCs', 'chartTemporalMontos',
            'topBajaEjecucion', 'topSaldoNegativo'
        ));"""

new = """return view('reporte.dashboard', compact(
            'isAdmin', 'resumenUsuarios',
            'availableYears', 'selectedYear', 'departamentos', 'selectedDepartamentoId',
            // Sección 1: KPIs generales
            'totalProyectos', 'totalLicitaciones', 'totalOrdenes',
            'totalPresupuesto', 'totalComprometido', 'totalSaldo', 'totalPorcentaje',
            // Sección 2: Alertas
            'proyectosSinLicitacion', 'proyectosSinOC', 'proyectosSaldoNegativo', 'licitacionesProblematicas',
            // Sección 3: Motor de fragmentación
            'casosFrag', 'reglasFragmentacion', 'umbralFragmentacion', 'valorUTM',
            'kpiTotalOC', 'kpiCasosSospechosos', 'kpiRiesgoAlto', 'kpiMontoFragmentado',
            'chartEspecieLabels', 'chartEspecieScores', 'chartEspecieMontos', 'chartEspecieNiveles',
            'chartDeptLabels', 'chartDeptAlto', 'chartDeptMedio', 'chartDeptBajo',
            'chartEvolucionLabels', 'chartEvolucionCasos', 'chartEvolucionMontos',
            'chartFragModLabels', 'chartFragModData',
            'heatmapEspecies', 'heatmapDeptos', 'heatmapMatrix',
            'sospechaNFragmentacion', 'fragmentacionPorEspecie',
            'chartFragEspecieLabels', 'chartFragEspecieOCs', 'chartFragEspecieMontos',
            // Sección 4: Modalidad
            'distribucionModalidad', 'totalMontoModalidad', 'chartModalidadLabels', 'chartModalidadData',
            // Sección 5-6: Temporal y departamentos
            'chartTemporalLabels', 'chartTemporalOCs', 'chartTemporalMontos',
            'chartBarLabels', 'chartBarPresupuesto', 'chartBarComprometido', 'tablaDepartamentos',
            // Sección 7: Top proyectos
            'topBajaEjecucion', 'topSaldoNegativo',
            // Sección 8: Estado licitaciones
            'chartDonaLicLabels', 'chartDonaLicData', 'chartDonaOcLabels', 'chartDonaOcData'
        ));"""

if old in c:
    c = c.replace(old, new, 1)
    with open(path,'w',encoding='utf-8') as f:
        f.write(c)
    print('OK')
else:
    print('NOT FOUND')
    idx = c.find("return view('reporte.dashboard'")
    print(repr(c[idx:idx+300]))
