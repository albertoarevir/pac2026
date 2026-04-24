<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pac;
use App\Models\Orden;
use App\Models\Modalidad;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;

class ReporteDashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentYear    = now()->year;
        $availableYears = range($currentYear, $currentYear - 4);
        $selectedYear   = $request->input('year', $currentYear);

        $departamentos          = Departamento::orderBy('detalle')->get();
        $selectedDepartamentoId = $request->input('departamento_id');

        // ------------------------------------------------------------------
        // 1. Presupuesto vs Comprometido por Departamento (gráfico barras)
        // ------------------------------------------------------------------
        $presupuestoPorDepto = DB::table('departamentos')
            ->select(
                'departamentos.detalle as departamento',
                DB::raw('COALESCE(SUM(presupuestos.monto), 0) as total_presupuesto'),
                DB::raw('COALESCE((
                    SELECT SUM(o.monto)
                    FROM ordens o
                    INNER JOIN pacs p ON o.id_proyecto = p.id
                    WHERE p.departamento_id = departamentos.id
                      AND YEAR(o.fecha_seguimiento) = ' . (int)$selectedYear . '
                ), 0) as total_comprometido')
            )
            ->leftJoin('presupuestos', function ($join) use ($selectedYear) {
                $join->on('presupuestos.departamento_id', '=', 'departamentos.id')
                     ->where('presupuestos.year', '=', $selectedYear);
            })
            ->where('departamentos.detalle', '!=', 'Dirección de Logística')
            ->when($selectedDepartamentoId, fn($q) => $q->where('departamentos.id', $selectedDepartamentoId))
            ->groupBy('departamentos.id', 'departamentos.detalle')
            ->orderBy('departamentos.detalle')
            ->get();

        $chartBarLabels        = $presupuestoPorDepto->pluck('departamento')->toArray();
        $chartBarPresupuesto   = $presupuestoPorDepto->map(fn($r) => (float)$r->total_presupuesto)->toArray();
        $chartBarComprometido  = $presupuestoPorDepto->map(fn($r) => (float)$r->total_comprometido)->toArray();

        // ------------------------------------------------------------------
        // 2. Tabla resumen por departamento
        // ------------------------------------------------------------------
        $tablaDepartamentos = $presupuestoPorDepto->map(function ($row) {
            $presupuesto  = (float) $row->total_presupuesto;
            $comprometido = (float) $row->total_comprometido;
            $porcentaje   = $presupuesto > 0 ? round(($comprometido / $presupuesto) * 100, 2) : 0;
            $saldo        = $presupuesto - $comprometido;
            return [
                'departamento' => $row->departamento,
                'presupuesto'  => $presupuesto,
                'comprometido' => $comprometido,
                'saldo'        => $saldo,
                'porcentaje'   => $porcentaje,
            ];
        });

        // Totales de la tabla
        $totalPresupuesto  = $tablaDepartamentos->sum('presupuesto');
        $totalComprometido = $tablaDepartamentos->sum('comprometido');
        $totalSaldo        = $totalPresupuesto - $totalComprometido;
        $totalPorcentaje   = $totalPresupuesto > 0
            ? round(($totalComprometido / $totalPresupuesto) * 100, 2) : 0;

        // ------------------------------------------------------------------
        // 3. Estado de Licitaciones (gráfico dona)
        // ------------------------------------------------------------------
        $estadosLicitacionesQuery = DB::table('modalidads')
            ->select('estado_licitacions.detalle as estado', DB::raw('COUNT(modalidads.id) as cantidad'))
            ->join('estado_licitacions', 'modalidads.estado_id', '=', 'estado_licitacions.id')
            ->join('pacs', 'modalidads.id_proyecto', '=', 'pacs.id')
            ->where('pacs.year', $selectedYear)
            ->where('pacs.departamento_id', '!=', function ($q) {
                $q->select('id')->from('departamentos')
                  ->where('detalle', 'Dirección de Logística')->limit(1);
            });

        if ($selectedDepartamentoId) {
            $estadosLicitacionesQuery->where('pacs.departamento_id', $selectedDepartamentoId);
        }

        $estadosLicitaciones     = $estadosLicitacionesQuery->groupBy('estado_licitacions.detalle')->get();
        $chartDonaLicLabels      = $estadosLicitaciones->pluck('estado')->toArray();
        $chartDonaLicData        = $estadosLicitaciones->pluck('cantidad')->toArray();

        // ------------------------------------------------------------------
        // 4. Estado de Órdenes de Compra (gráfico dona)
        // ------------------------------------------------------------------
        $estadosOrdenesQuery = DB::table('ordens')
            ->select('estado_compras.detalle as estado', DB::raw('COUNT(ordens.id) as cantidad'))
            ->join('estado_compras', 'ordens.estado_id', '=', 'estado_compras.id')
            ->join('pacs', 'ordens.id_proyecto', '=', 'pacs.id')
            ->whereYear('ordens.fecha_seguimiento', $selectedYear)
            ->where('pacs.departamento_id', '!=', function ($q) {
                $q->select('id')->from('departamentos')
                  ->where('detalle', 'Dirección de Logística')->limit(1);
            });

        if ($selectedDepartamentoId) {
            $estadosOrdenesQuery->where('pacs.departamento_id', $selectedDepartamentoId);
        }

        $estadosOrdenes      = $estadosOrdenesQuery->groupBy('estado_compras.detalle')->get();
        $chartDonaOcLabels   = $estadosOrdenes->pluck('estado')->toArray();
        $chartDonaOcData     = $estadosOrdenes->pluck('cantidad')->toArray();

        // ------------------------------------------------------------------
        // KPIs rápidos (tarjetas superiores)
        // ------------------------------------------------------------------
        $totalProyectos      = Pac::where('year', $selectedYear)
            ->when($selectedDepartamentoId, fn($q) => $q->where('departamento_id', $selectedDepartamentoId))
            ->count();

        $totalLicitaciones   = Modalidad::whereHas('pac', fn($q) => $q->where('year', $selectedYear))
            ->when($selectedDepartamentoId, fn($q) => $q->whereHas('pac', fn($q2) =>
                $q2->where('departamento_id', $selectedDepartamentoId)))
            ->count();

        $totalOrdenes        = Orden::whereYear('fecha_seguimiento', $selectedYear)
            ->when($selectedDepartamentoId, fn($q) => $q->whereHas('pac', fn($q2) =>
                $q2->where('departamento_id', $selectedDepartamentoId)))
            ->count();

        return view('reporte.dashboard', compact(
            'availableYears',
            'selectedYear',
            'departamentos',
            'selectedDepartamentoId',
            'chartBarLabels',
            'chartBarPresupuesto',
            'chartBarComprometido',
            'tablaDepartamentos',
            'totalPresupuesto',
            'totalComprometido',
            'totalSaldo',
            'totalPorcentaje',
            'chartDonaLicLabels',
            'chartDonaLicData',
            'chartDonaOcLabels',
            'chartDonaOcData',
            'totalProyectos',
            'totalLicitaciones',
            'totalOrdenes',
        ));
    }
}
