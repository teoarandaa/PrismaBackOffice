<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function kpis()
    {
        // KPIs Principales
        $proyectosActivos = Proyecto::where('estado', 'En progreso')->count();
        $ingresosTotales = Proyecto::whereYear('created_at', Carbon::now()->year)
                                  ->sum('presupuesto');
        
        // Tiempo medio de desarrollo (en días)
        $tiempoMedioDesarrollo = Proyecto::whereNotNull('fecha_inicio')
            ->whereNotNull('fecha_finalizacion')
            ->where('estado', 'Completado')
            ->whereDate('fecha_finalizacion', '>=', Carbon::now()->subMonths(6))
            ->avg(DB::raw('DATEDIFF(fecha_finalizacion, fecha_inicio)'));

        // Tasa de éxito (proyectos completados vs total)
        $totalProyectos = Proyecto::count();
        $proyectosCompletados = Proyecto::where('estado', 'Completado')->count();
        $tasaExito = $totalProyectos > 0 ? round(($proyectosCompletados / $totalProyectos) * 100) : 0;

        // Estadísticas por tipo y estado
        $totalApps = Proyecto::where('tipo', 'app')->count();
        $totalWebs = Proyecto::where('tipo', 'web')->count();
        
        $estadisticas = [
            'en_progreso' => Proyecto::where('estado', 'En progreso')->count(),
            'completados' => Proyecto::where('estado', 'Completado')->count(),
            'cancelados' => Proyecto::where('estado', 'Cancelado')->count(),
        ];

        // Top Clientes
        $topClientes = Cliente::withCount('proyectos as total_proyectos')
                            ->orderBy('total_proyectos', 'desc')
                            ->limit(5)
                            ->get();

        // Presupuestos promedio
        $presupuestoPromedioApps = Proyecto::where('tipo', 'app')->avg('presupuesto') ?? 0;
        $presupuestoPromedioWebs = Proyecto::where('tipo', 'web')->avg('presupuesto') ?? 0;

        // Rendimiento mensual
        $mesActual = Carbon::now();
        $proyectosIniciadosMes = Proyecto::whereMonth('fecha_inicio', $mesActual->month)
                                        ->whereYear('fecha_inicio', $mesActual->year)
                                        ->count();
        $proyectosCompletadosMes = Proyecto::whereMonth('fecha_finalizacion', $mesActual->month)
                                          ->whereYear('fecha_finalizacion', $mesActual->year)
                                          ->where('estado', 'Completado')
                                          ->count();

        // Añadir estas líneas para los proyectos iniciados y completados
        $proyectosIniciados = Proyecto::count();
        $proyectosCompletados = Proyecto::where('estado', 'Completado')->count();

        return view('dashboard.kpis', compact(
            'proyectosActivos',
            'ingresosTotales',
            'tiempoMedioDesarrollo',
            'tasaExito',
            'totalApps',
            'totalWebs',
            'estadisticas',
            'topClientes',
            'presupuestoPromedioApps',
            'presupuestoPromedioWebs',
            'proyectosIniciadosMes',
            'proyectosCompletadosMes',
            'proyectosIniciados',
            'proyectosCompletados'
        ));
    }

    public function topClientesDetalle()
    {
        $clientes = Cliente::withCount('proyectos as total_proyectos')
            ->withSum('proyectos', 'presupuesto')
            ->withAvg('proyectos', 'presupuesto')
            ->with(['proyectos' => function($query) {
                $query->select('id', 'id_cliente', 'nombre_proyecto', 'tipo', 'estado', 'presupuesto')
                      ->orderBy('created_at', 'desc')
                      ->take(5);
            }])
            ->having('total_proyectos', '>', 0)
            ->orderBy('total_proyectos', 'desc')
            ->orderBy('proyectos_sum_presupuesto', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.top-clientes-detalle', compact('clientes'));
    }

    public function presupuestosDetalle()
    {
        // Estadísticas generales
        $estadisticas = [
            'apps' => [
                'promedio' => Proyecto::where('tipo', 'app')->avg('presupuesto'),
                'minimo' => Proyecto::where('tipo', 'app')->min('presupuesto'),
                'maximo' => Proyecto::where('tipo', 'app')->max('presupuesto'),
                'total' => Proyecto::where('tipo', 'app')->count(),
                'completados' => Proyecto::where('tipo', 'app')->where('estado', 'Completado')->count(),
            ],
            'webs' => [
                'promedio' => Proyecto::where('tipo', 'web')->avg('presupuesto'),
                'minimo' => Proyecto::where('tipo', 'web')->min('presupuesto'),
                'maximo' => Proyecto::where('tipo', 'web')->max('presupuesto'),
                'total' => Proyecto::where('tipo', 'web')->count(),
                'completados' => Proyecto::where('tipo', 'web')->where('estado', 'Completado')->count(),
            ]
        ];

        // Últimos 5 proyectos de cada tipo
        $ultimosProyectos = [
            'apps' => Proyecto::where('tipo', 'app')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'webs' => Proyecto::where('tipo', 'web')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('dashboard.presupuestos-detalle', compact('estadisticas', 'ultimosProyectos'));
    }

    public function rendimientoDetalle()
    {
        $periodos = [
            'mes' => [
                'label' => 'Último mes',
                'inicio' => now()->subMonth()->startOfDay(),
                'fin' => now()->endOfDay(),
            ],
            'trimestre' => [
                'label' => 'Últimos 3 meses',
                'inicio' => now()->subMonths(3)->startOfDay(),
                'fin' => now()->endOfDay(),
            ],
            'semestre' => [
                'label' => 'Últimos 6 meses',
                'inicio' => now()->subMonths(6)->startOfDay(),
                'fin' => now()->endOfDay(),
            ],
            'anual' => [
                'label' => 'Último año',
                'inicio' => now()->subYear()->startOfDay(),
                'fin' => now()->endOfDay(),
            ],
            'general' => [
                'label' => 'General',
                'inicio' => null,
                'fin' => null,
            ],
        ];

        $estadisticas = [];

        foreach ($periodos as $key => $periodo) {
            $query = Proyecto::query();
            
            if ($periodo['inicio'] && $periodo['fin']) {
                // Proyectos iniciados en este período (por fecha_inicio)
                $iniciados = (clone $query)
                    ->whereBetween('fecha_inicio', [$periodo['inicio'], $periodo['fin']])
                    ->count();

                // Proyectos completados en este período (por fecha en que se marcaron como completados)
                $completados = (clone $query)
                    ->where('estado', 'Completado')
                    ->whereBetween('updated_at', [$periodo['inicio'], $periodo['fin']])
                    ->count();

                // Proyectos cancelados en este período (por fecha en que se marcaron como cancelados)
                $cancelados = (clone $query)
                    ->where('estado', 'Cancelado')
                    ->whereBetween('updated_at', [$periodo['inicio'], $periodo['fin']])
                    ->count();

                // Proyectos en progreso actualmente
                $enProgreso = (clone $query)
                    ->where('estado', 'En Progreso')
                    ->count();
            } else {
                // Para estadísticas generales (todos los tiempos)
                $iniciados = (clone $query)->count();
                $completados = (clone $query)->where('estado', 'Completado')->count();
                $cancelados = (clone $query)->where('estado', 'Cancelado')->count();
                $enProgreso = (clone $query)->where('estado', 'En Progreso')->count();
            }

            // Tiempo promedio para proyectos completados en este período
            if ($periodo['inicio'] && $periodo['fin']) {
                $tiempoPromedio = (clone $query)
                    ->where('estado', 'Completado')
                    ->whereBetween('updated_at', [$periodo['inicio'], $periodo['fin']])
                    ->whereNotNull('fecha_inicio')
                    ->selectRaw('ROUND(AVG(DATEDIFF(updated_at, fecha_inicio)), 0) as promedio')
                    ->value('promedio') ?? 0;
            } else {
                $tiempoPromedio = (clone $query)
                    ->where('estado', 'Completado')
                    ->whereNotNull('fecha_inicio')
                    ->selectRaw('ROUND(AVG(DATEDIFF(updated_at, fecha_inicio)), 0) as promedio')
                    ->value('promedio') ?? 0;
            }

            $estadisticas[$key] = [
                'label' => $periodo['label'],
                'iniciados' => $iniciados,
                'completados' => $completados,
                'cancelados' => $cancelados,
                'en_progreso' => $enProgreso,
                'tasa_exito' => $iniciados > 0 ? round(($completados / $iniciados) * 100, 1) : 0,
                'tiempo_promedio' => $tiempoPromedio,
            ];
        }

        return view('dashboard.rendimiento-detalle', compact('estadisticas'));
    }
} 