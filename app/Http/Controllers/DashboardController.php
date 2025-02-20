<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function kpis()
    {
        // KPIs Principales
        $proyectosActivos = Proyecto::where('estado', 'En progreso')->count();
        $ingresosTotales = Proyecto::whereYear('created_at', Carbon::now()->year)
                                  ->sum('presupuesto');
        
        // Tiempo medio de desarrollo (usando updated_at como en rendimiento)
        $tiempoMedioDesarrollo = Proyecto::whereNotNull('fecha_inicio')
            ->where('estado', 'Completado')
            ->avg(DB::raw('DATEDIFF(updated_at, fecha_inicio)'));

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

        // Datos para las tendencias (últimos 6 meses)
        $tendencias = [
            'meses' => [],
            'ingresos' => [],
            'tiempos' => [],
            'tasas_exito' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $mes = $fecha->format('M Y');
            $inicio = $fecha->startOfMonth();
            $fin = $fecha->copy()->endOfMonth();

            // Ingresos mensuales
            $ingresos = Proyecto::whereBetween('created_at', [$inicio, $fin])
                ->sum('presupuesto');

            // Tiempo medio de desarrollo
            $tiempoMedio = Proyecto::whereBetween('created_at', [$inicio, $fin])
                ->whereNotNull('fecha_inicio')
                ->whereNotNull('updated_at')
                ->where('estado', 'Completado')
                ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                ->value('tiempo_medio') ?? 0;

            // Tasa de éxito
            $total = Proyecto::whereBetween('created_at', [$inicio, $fin])->count();
            $completados = Proyecto::whereBetween('created_at', [$inicio, $fin])
                ->where('estado', 'Completado')
                ->count();
            $tasaExito = $total > 0 ? round(($completados / $total) * 100, 1) : 0;

            $tendencias['meses'][] = $mes;
            $tendencias['ingresos'][] = $ingresos;
            $tendencias['tiempos'][] = round($tiempoMedio, 1);
            $tendencias['tasas_exito'][] = $tasaExito;
        }

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
            'proyectosCompletados',
            'tendencias'
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

    public function proyectosActivosDetalle(Request $request)
    {
        $query = [
            'activos' => Proyecto::with('cliente')->where('estado', 'En progreso'),
            'completados' => Proyecto::with('cliente')->where('estado', 'Completado'),
            'en_progreso' => Proyecto::with('cliente')->where('estado', 'En progreso'),
            'cancelados' => Proyecto::with('cliente')->where('estado', 'Cancelado')
        ];

        // Filtros comunes para todas las secciones
        foreach (['completados', 'en_progreso', 'cancelados'] as $estado) {
            if ($request->filled($estado . '_nombre')) {
                $query[$estado]->where('nombre_proyecto', 'LIKE', '%' . $request->input($estado . '_nombre') . '%');
            }
            if ($request->filled($estado . '_cliente')) {
                $query[$estado]->whereHas('cliente', function($q) use ($request, $estado) {
                    $q->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', '%' . $request->input($estado . '_cliente') . '%');
                });
            }
            if ($request->filled($estado . '_tipo')) {
                $query[$estado]->where('tipo', $request->input($estado . '_tipo'));
            }
            if ($request->filled($estado . '_inicio')) {
                $query[$estado]->whereDate('fecha_inicio', $request->input($estado . '_inicio'));
            }
            if ($request->filled($estado . '_fin')) {
                $query[$estado]->whereDate('fecha_finalizacion', $request->input($estado . '_fin'));
            }
        }

        // Filtros específicos para completados
        if ($request->filled('completados_completado')) {
            $query['completados']->whereDate('updated_at', $request->input('completados_completado'));
        }

        // Filtros específicos para cancelados
        if ($request->filled('cancelados_cancelado')) {
            $query['cancelados']->whereDate('updated_at', $request->input('cancelados_cancelado'));
        }

        $proyectos = [
            'activos' => $query['activos']->paginate(10, ['*'], 'activos'),
            'completados' => $query['completados']->paginate(10, ['*'], 'completados'),
            'en_progreso' => $query['en_progreso']->paginate(10, ['*'], 'en_progreso'),
            'cancelados' => $query['cancelados']->paginate(10, ['*'], 'cancelados')
        ];

        return view('dashboard.proyectos-activos-detalle', compact('proyectos'));
    }

    public function ingresosDetalle(Request $request)
    {
        // Consulta base para proyectos
        $queryProyectos = Proyecto::with('cliente');

        // Obtener el orden de los ingresos
        $ordenIngresos = $request->input('orden', 'desc');

        // Aplicar filtros si existen
        if ($request->filled('proyecto')) {
            $queryProyectos->where('nombre_proyecto', 'LIKE', '%' . $request->proyecto . '%');
        }
        if ($request->filled('cliente')) {
            $queryProyectos->whereHas('cliente', function($q) use ($request) {
                $q->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', '%' . $request->cliente . '%');
            });
        }
        if ($request->filled('tipo')) {
            $queryProyectos->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $queryProyectos->where('estado', $request->estado);
        }
        if ($request->filled('ingresos_min')) {
            $queryProyectos->where('presupuesto', '>=', $request->ingresos_min);
        }
        if ($request->filled('ingresos_max')) {
            $queryProyectos->where('presupuesto', '<=', $request->ingresos_max);
        }

        // Preparar datos para la vista
        $proyectos = [
            'total_general' => Proyecto::sum('presupuesto'),
            'por_tipo' => DB::table('proyectos')
                ->select('tipo', DB::raw('SUM(presupuesto) as total'))
                ->groupBy('tipo')
                ->get(),
            'por_mes' => DB::table('proyectos')
                ->select(
                    DB::raw('MONTH(created_at) as mes'),
                    DB::raw('YEAR(created_at) as año'),
                    DB::raw('SUM(presupuesto) as total')
                )
                ->groupBy('mes', 'año')
                ->orderBy('año', 'desc')
                ->orderBy('mes', 'desc')
                ->paginate(10, ['*'], 'pagina_mes'),
            'proyectos' => $queryProyectos
                ->orderBy('presupuesto', $ordenIngresos)
                ->paginate(10, ['*'], 'pagina_proyectos')
        ];

        return view('dashboard.ingresos-detalle', compact('proyectos', 'ordenIngresos'));
    }

    public function tiempoDesarrolloDetalle(Request $request)
    {
        $query = Proyecto::with('cliente')
            ->whereNotNull('fecha_inicio');

        // Aplicar filtros si existen
        if ($request->filled('proyecto')) {
            $query->where('nombre_proyecto', 'LIKE', '%' . $request->proyecto . '%');
        }
        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', '%' . $request->cliente . '%');
            });
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_finalizacion', '<=', $request->fecha_fin);
        }
        if ($request->filled('duracion_min')) {
            $query->whereRaw('DATEDIFF(CASE WHEN estado = "Completado" THEN updated_at ELSE CURRENT_TIMESTAMP END, fecha_inicio) >= ?', [$request->duracion_min]);
        }
        if ($request->filled('duracion_max')) {
            $query->whereRaw('DATEDIFF(CASE WHEN estado = "Completado" THEN updated_at ELSE CURRENT_TIMESTAMP END, fecha_inicio) <= ?', [$request->duracion_max]);
        }

        // Ordenar por tiempo de desarrollo
        $ordenTiempo = $request->get('orden', 'desc');
        $query->orderByRaw('DATEDIFF(CASE WHEN estado = "Completado" THEN updated_at ELSE CURRENT_TIMESTAMP END, fecha_inicio) ' . $ordenTiempo);

        $proyectos = $query->paginate(10);

        // Calcular días de desarrollo para cada proyecto
        foreach ($proyectos as $proyecto) {
            $fechaInicio = Carbon::parse($proyecto->fecha_inicio);
            $fechaFin = $proyecto->estado === 'Completado' 
                ? Carbon::parse($proyecto->updated_at)
                : now();
            $proyecto->dias_desarrollo = number_format($fechaInicio->diffInDays($fechaFin), 2);
        }

        // Calcular promedio general
        $promedio_general = number_format(Proyecto::whereNotNull('fecha_inicio')
            ->where('estado', 'Completado')
            ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as promedio_dias')
            ->first()
            ->promedio_dias, 2);

        // Calcular promedios por tipo
        $por_tipo = Proyecto::whereNotNull('fecha_inicio')
            ->where('estado', 'Completado')
            ->selectRaw('tipo, 
                        ROUND(AVG(DATEDIFF(updated_at, fecha_inicio)), 2) as promedio_dias,
                        COUNT(*) as total_proyectos')
            ->groupBy('tipo')
            ->get();

        return view('dashboard.tiempo-desarrollo-detalle', compact('proyectos', 'ordenTiempo'))
            ->with('proyectos', [
                'proyectos' => $proyectos,
                'promedio_general' => $promedio_general,
                'por_tipo' => $por_tipo
            ]);
    }

    public function tasaExitoDetalle(Request $request)
    {
        // Consulta base para proyectos
        $queryProyectos = Proyecto::with('cliente');

        // Aplicar filtros si existen
        if ($request->filled('proyecto')) {
            $queryProyectos->where('nombre_proyecto', 'LIKE', '%' . $request->proyecto . '%');
        }

        if ($request->filled('cliente')) {
            $queryProyectos->whereHas('cliente', function($q) use ($request) {
                $q->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('tipo')) {
            $queryProyectos->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $queryProyectos->where('estado', $request->estado);
        }

        if ($request->filled('fecha_fin')) {
            $queryProyectos->whereDate('updated_at', $request->fecha_fin);
        }

        // Preparar estadísticas
        $estadisticas = [
            'general' => [
                'total' => Proyecto::count(),
                'completados' => Proyecto::where('estado', 'Completado')->count(),
                'tasa_exito' => round((Proyecto::where('estado', 'Completado')->count() / Proyecto::count()) * 100, 1)
            ],
            'por_tipo' => [
                'app' => [
                    'total' => Proyecto::where('tipo', 'app')->count(),
                    'completados' => Proyecto::where('tipo', 'app')->where('estado', 'Completado')->count(),
                ],
                'web' => [
                    'total' => Proyecto::where('tipo', 'web')->count(),
                    'completados' => Proyecto::where('tipo', 'web')->where('estado', 'Completado')->count(),
                ]
            ],
            'proyectos' => $queryProyectos->orderBy('updated_at', 'desc')->paginate(10)
        ];

        // Calcular tasas de éxito por tipo
        $estadisticas['por_tipo']['app']['tasa_exito'] = $estadisticas['por_tipo']['app']['total'] > 0 
            ? round(($estadisticas['por_tipo']['app']['completados'] / $estadisticas['por_tipo']['app']['total']) * 100, 1) 
            : 0;
        
        $estadisticas['por_tipo']['web']['tasa_exito'] = $estadisticas['por_tipo']['web']['total'] > 0 
            ? round(($estadisticas['por_tipo']['web']['completados'] / $estadisticas['por_tipo']['web']['total']) * 100, 1) 
            : 0;

        return view('dashboard.tasa-exito-detalle', compact('estadisticas'));
    }
} 