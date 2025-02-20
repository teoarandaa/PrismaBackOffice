<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function kpis(Request $request)
    {
        $rango = $request->get('rango', 'mes');
        
        // Obtener las tendencias según el rango seleccionado
        $tendencias = $this->obtenerDatosTendencias($rango);

        // Estadísticas de estado de proyectos
        $proyectosActivos = Proyecto::where('estado', 'En Desarrollo')->count();
        $proyectosCompletados = Proyecto::where('estado', 'Completado')->count();
        $proyectosEnDesarrollo = Proyecto::where('estado', 'En Desarrollo')->count();
        $proyectosCancelados = Proyecto::where('estado', 'Cancelado')->count();
        $proyectosIniciados = Proyecto::whereNotNull('fecha_inicio')->count();
        
        // Cálculo de totales y tasas
        $totalProyectos = Proyecto::count();
        $ingresosTotales = Proyecto::sum('presupuesto');
        $tasaExito = $totalProyectos > 0 ? round(($proyectosCompletados / $totalProyectos) * 100, 1) : 0;

        // Tiempo medio de desarrollo
        $tiempoMedioDesarrollo = round(Proyecto::whereNotNull('fecha_inicio')
            ->whereNotNull('updated_at')
            ->where('estado', 'Completado')
            ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as promedio')
            ->value('promedio') ?? 0);

        // Estadísticas por tipo
        $totalApps = Proyecto::where('tipo', 'app')->count();
        $totalWebs = Proyecto::where('tipo', 'web')->count();

        // Estadísticas de presupuestos
        $presupuestoPromedioApps = Proyecto::where('tipo', 'app')->avg('presupuesto') ?? 0;
        $presupuestoPromedioWebs = Proyecto::where('tipo', 'web')->avg('presupuesto') ?? 0;
        $presupuestoMaximoApps = Proyecto::where('tipo', 'app')->max('presupuesto') ?? 0;
        $presupuestoMaximoWebs = Proyecto::where('tipo', 'web')->max('presupuesto') ?? 0;
        $presupuestoMinimoApps = Proyecto::where('tipo', 'app')->min('presupuesto') ?? 0;
        $presupuestoMinimoWebs = Proyecto::where('tipo', 'web')->min('presupuesto') ?? 0;

        // Estadísticas por estado
        $estadisticas = [
            'en_progreso' => $proyectosEnDesarrollo,
            'completados' => $proyectosCompletados,
            'cancelados' => $proyectosCancelados
        ];

        // Top Clientes
        $topClientes = Cliente::withCount('proyectos')
            ->withSum('proyectos as total_ingresos', 'presupuesto')
            ->orderByDesc('total_ingresos')
            ->take(5)
            ->get();

        return view('dashboard.kpis', compact(
            'tendencias',
            'rango',
            'proyectosActivos',
            'proyectosCompletados',
            'proyectosEnDesarrollo',
            'proyectosCancelados',
            'proyectosIniciados',
            'ingresosTotales',
            'tiempoMedioDesarrollo',
            'tasaExito',
            'totalProyectos',
            'totalApps',
            'totalWebs',
            'estadisticas',
            'topClientes',
            'presupuestoPromedioApps',
            'presupuestoPromedioWebs',
            'presupuestoMaximoApps',
            'presupuestoMaximoWebs',
            'presupuestoMinimoApps',
            'presupuestoMinimoWebs'
        ));
    }

    // Nuevo método auxiliar para obtener los datos de tendencias
    private function obtenerDatosTendencias($rango)
    {
        $tendencias = [
            'meses' => [],
            'ingresos' => [],
            'tiempos' => [],
            'tasas_exito' => []
        ];

        switch($rango) {
            case 'mes':
                // Datos diarios del último mes
                $inicio = now()->startOfMonth();
                $fin = now()->endOfMonth();
                
                for ($fecha = $inicio->copy(); $fecha <= $fin; $fecha->addDay()) {
                    $tendencias['meses'][] = $fecha->format('d M');
                    
                    $datosDelDia = Proyecto::whereDate('created_at', $fecha);
                    $tendencias['ingresos'][] = $datosDelDia->sum('presupuesto');
                    
                    $tiempoMedio = $datosDelDia->whereNotNull('fecha_inicio')
                        ->whereNotNull('updated_at')
                        ->where('estado', 'Completado')
                        ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                        ->value('tiempo_medio') ?? 0;
                    $tendencias['tiempos'][] = round($tiempoMedio, 1);
                    
                    $total = $datosDelDia->count();
                    $completados = $datosDelDia->where('estado', 'Completado')->count();
                    $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                }
                break;

            case 'trimestre':
                // Datos semanales del último trimestre
                $inicio = now()->startOfWeek()->subWeeks(11);
                $fin = now()->endOfWeek();
                
                for ($fecha = $inicio->copy(); $fecha <= $fin; $fecha->addWeek()) {
                    $finSemana = $fecha->copy()->endOfWeek();
                    $tendencias['meses'][] = $fecha->format('d M') . ' - ' . $finSemana->format('d M');
                    
                    $datosSemana = Proyecto::whereBetween('created_at', [$fecha, $finSemana]);
                    $tendencias['ingresos'][] = $datosSemana->sum('presupuesto');
                    
                    $tiempoMedio = $datosSemana->whereNotNull('fecha_inicio')
                        ->whereNotNull('updated_at')
                        ->where('estado', 'Completado')
                        ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                        ->value('tiempo_medio') ?? 0;
                    $tendencias['tiempos'][] = round($tiempoMedio, 1);
                    
                    $total = $datosSemana->count();
                    $completados = $datosSemana->where('estado', 'Completado')->count();
                    $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                }
                break;

            case 'anio':
                // Datos mensuales del último año
                for ($i = 11; $i >= 0; $i--) {
                    $fecha = now()->subMonths($i);
                    $tendencias['meses'][] = $fecha->format('M Y');
                    
                    $datosMes = Proyecto::whereYear('created_at', $fecha->year)
                        ->whereMonth('created_at', $fecha->month);
                    $tendencias['ingresos'][] = $datosMes->sum('presupuesto');
                    
                    $tiempoMedio = $datosMes->whereNotNull('fecha_inicio')
                        ->whereNotNull('updated_at')
                        ->where('estado', 'Completado')
                        ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                        ->value('tiempo_medio') ?? 0;
                    $tendencias['tiempos'][] = round($tiempoMedio, 1);
                    
                    $total = $datosMes->count();
                    $completados = $datosMes->where('estado', 'Completado')->count();
                    $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                }
                break;

            case 'general':
                // Datos anuales desde el inicio
                $primerProyecto = Proyecto::oldest('created_at')->first();
                if ($primerProyecto) {
                    $añoInicio = $primerProyecto->created_at->year;
                    $añoFin = now()->year;
                    
                    for ($año = $añoInicio; $año <= $añoFin; $año++) {
                        $tendencias['meses'][] = (string)$año;
                        
                        $datosAño = Proyecto::whereYear('created_at', $año);
                        $tendencias['ingresos'][] = $datosAño->sum('presupuesto');
                        
                        $tiempoMedio = $datosAño->whereNotNull('fecha_inicio')
                            ->whereNotNull('updated_at')
                            ->where('estado', 'Completado')
                            ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                            ->value('tiempo_medio') ?? 0;
                        $tendencias['tiempos'][] = round($tiempoMedio, 1);
                        
                        $total = $datosAño->count();
                        $completados = $datosAño->where('estado', 'Completado')->count();
                        $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                    }
                }
                break;
        }

        return $tendencias;
    }

    public function topClientesDetalle()
    {
        $clientes = Cliente::with(['proyectos' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->withCount('proyectos as total_proyectos')
        ->withSum('proyectos as proyectos_sum_presupuesto', 'presupuesto')
        ->withAvg('proyectos as proyectos_avg_presupuesto', 'presupuesto')
        ->orderByDesc('proyectos_sum_presupuesto')
        ->get();

        // La ordenación por presupuesto se hace en la vista para mantener
        // la colección original de proyectos intacta para cada cliente

        return view('dashboard.top-clientes-detalle', compact('clientes'));
    }

    public function presupuestosDetalle(Request $request)
    {
        $estadisticas = [
            'apps' => [
                'promedio' => Proyecto::where('tipo', 'app')->avg('presupuesto'),
                'maximo' => Proyecto::where('tipo', 'app')->max('presupuesto'),
                'minimo' => Proyecto::where('tipo', 'app')->min('presupuesto'),
                'completados' => Proyecto::where('tipo', 'app')->where('estado', 'Completado')->count(),
                'total' => Proyecto::where('tipo', 'app')->count(),
            ],
            'webs' => [
                'promedio' => Proyecto::where('tipo', 'web')->avg('presupuesto'),
                'maximo' => Proyecto::where('tipo', 'web')->max('presupuesto'),
                'minimo' => Proyecto::where('tipo', 'web')->min('presupuesto'),
                'completados' => Proyecto::where('tipo', 'web')->where('estado', 'Completado')->count(),
                'total' => Proyecto::where('tipo', 'web')->count(),
            ],
        ];

        $ordenApps = $request->input('orden_apps', 'desc');
        $ordenWebs = $request->input('orden_webs', 'desc');

        $ultimosProyectos = [
            'apps' => Proyecto::where('tipo', 'app')
                             ->orderBy('presupuesto', $ordenApps)
                             ->paginate(10, ['*'], 'pagina_apps'),
            'webs' => Proyecto::where('tipo', 'web')
                             ->orderBy('presupuesto', $ordenWebs)
                             ->paginate(10, ['*'], 'pagina_webs'),
        ];

        return view('dashboard.presupuestos-detalle', compact('estadisticas', 'ultimosProyectos', 'ordenApps', 'ordenWebs'));
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
                    ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as promedio')
                    ->value('promedio') ?? 0;
            } else {
                $tiempoPromedio = (clone $query)
                    ->where('estado', 'Completado')
                    ->whereNotNull('fecha_inicio')
                    ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as promedio')
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
        // Consultas base para cada tipo de proyecto
        $query = [
            'completados' => Proyecto::with('cliente')->where('estado', 'Completado'),
            'en_progreso' => Proyecto::with('cliente')->where('estado', 'En Desarrollo'),
            'cancelados' => Proyecto::with('cliente')->where('estado', 'Cancelado')
        ];

        // Aplicar filtros para cada tipo
        foreach ($query as $tipo => $queryBuilder) {
            // Filtro por nombre de proyecto
            if ($request->filled($tipo . '_nombre')) {
                $queryBuilder->where('nombre_proyecto', 'LIKE', '%' . $request->input($tipo . '_nombre') . '%');
            }

            // Filtro por cliente
            if ($request->filled($tipo . '_cliente')) {
                $queryBuilder->whereHas('cliente', function($q) use ($request, $tipo) {
                    $q->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', '%' . $request->input($tipo . '_cliente') . '%');
                });
            }

            // Filtro por tipo de proyecto
            if ($request->filled($tipo . '_tipo')) {
                $queryBuilder->where('tipo', $request->input($tipo . '_tipo'));
            }

            // Filtro por rango de fechas
            if ($request->filled($tipo . '_inicio') || $request->filled($tipo . '_fin')) {
                $queryBuilder->where(function($q) use ($request, $tipo) {
                    $fechaInicio = $request->input($tipo . '_inicio');
                    $fechaFin = $request->input($tipo . '_fin');

                    if ($fechaInicio && $fechaFin) {
                        // Buscar proyectos que se solapen con el rango de fechas (inclusive)
                        $q->where(function($query) use ($fechaInicio, $fechaFin) {
                            $query->where(function($q) use ($fechaInicio, $fechaFin) {
                                // El proyecto empieza dentro o en el límite del rango
                                $q->whereDate('fecha_inicio', '>=', $fechaInicio)
                                  ->whereDate('fecha_inicio', '<=', $fechaFin);
                            })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                                // El proyecto termina dentro o en el límite del rango
                                $q->whereDate('fecha_finalizacion', '>=', $fechaInicio)
                                  ->whereDate('fecha_finalizacion', '<=', $fechaFin);
                            })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                                // El proyecto abarca todo el rango
                                $q->whereDate('fecha_inicio', '<=', $fechaInicio)
                                  ->whereDate('fecha_finalizacion', '>=', $fechaFin);
                            });
                        });
                    } elseif ($fechaInicio) {
                        $q->whereDate('fecha_finalizacion', '>=', $fechaInicio);
                    } elseif ($fechaFin) {
                        $q->whereDate('fecha_inicio', '<=', $fechaFin);
                    }
                });
            }

            // Filtro adicional para fecha de completado/cancelado
            if ($tipo === 'completados' && $request->filled('completados_completado')) {
                $queryBuilder->where(function($q) use ($request) {
                    if ($request->filled('completados_inicio')) {
                        // Incluir las fechas límite en el rango
                        $q->whereDate('updated_at', '>=', $request->completados_inicio)
                          ->whereDate('updated_at', '<=', $request->completados_completado);
                    } else {
                        $q->whereDate('updated_at', '<=', $request->completados_completado);
                    }
                });
            }
        }

        // Paginar resultados
        $proyectos = [
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

        // Filtro por rango de fechas de inicio/fin
        if ($request->filled('fecha_inicio') || $request->filled('fecha_fin')) {
            $query->where(function($q) use ($request) {
                $fechaInicio = $request->fecha_inicio;
                $fechaFin = $request->fecha_fin;

                if ($fechaInicio && $fechaFin) {
                    // Buscar proyectos que se solapen con el rango de fechas (inclusive)
                    $q->where(function($query) use ($fechaInicio, $fechaFin) {
                        $query->where(function($q) use ($fechaInicio, $fechaFin) {
                            // El proyecto empieza dentro o en el límite del rango
                            $q->whereDate('fecha_inicio', '>=', $fechaInicio)
                              ->whereDate('fecha_inicio', '<=', $fechaFin);
                        })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                            // El proyecto termina dentro o en el límite del rango
                            $q->whereDate('fecha_finalizacion', '>=', $fechaInicio)
                              ->whereDate('fecha_finalizacion', '<=', $fechaFin);
                        })->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                            // El proyecto abarca todo el rango
                            $q->whereDate('fecha_inicio', '<=', $fechaInicio)
                              ->whereDate('fecha_finalizacion', '>=', $fechaFin);
                        });
                    });
                } elseif ($fechaInicio) {
                    $q->whereDate('fecha_finalizacion', '>=', $fechaInicio);
                } elseif ($fechaFin) {
                    $q->whereDate('fecha_inicio', '<=', $fechaFin);
                }
            });
        }

        // Filtro por fecha de completado
        if ($request->filled('fecha_completado')) {
            $query->where('estado', 'Completado')
                  ->whereDate('updated_at', '<=', $request->fecha_completado);
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

    public function obtenerTendencias(Request $request)
    {
        $rango = $request->get('rango', 'mes');
        $grafico = $request->get('grafico');
        
        $tendencias = [
            'meses' => [],
            'ingresos' => [],
            'tiempos' => [],
            'tasas_exito' => []
        ];

        switch($rango) {
            case 'mes':
                // Datos diarios del último mes
                $inicio = now()->startOfMonth();
                $fin = now()->endOfMonth();
                
                for ($fecha = $inicio->copy(); $fecha <= $fin; $fecha->addDay()) {
                    $tendencias['meses'][] = $fecha->format('d M');
                    
                    $datosDelDia = Proyecto::whereDate('created_at', $fecha);
                    
                    switch($grafico) {
                        case 'ingresosTendencia':
                            $tendencias['ingresos'][] = $datosDelDia->sum('presupuesto');
                            break;
                        case 'tiempoTendencia':
                            $tiempoMedio = $datosDelDia->whereNotNull('fecha_inicio')
                                ->whereNotNull('updated_at')
                                ->where('estado', 'Completado')
                                ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                                ->value('tiempo_medio') ?? 0;
                            $tendencias['tiempos'][] = round($tiempoMedio, 1);
                            break;
                        case 'exitoTendencia':
                            $total = $datosDelDia->count();
                            $completados = $datosDelDia->where('estado', 'Completado')->count();
                            $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                            break;
                    }
                }
                break;

            case 'trimestre':
                // Datos semanales del último trimestre
                $inicio = now()->startOfWeek()->subWeeks(11);
                $fin = now()->endOfWeek();
                
                for ($fecha = $inicio->copy(); $fecha <= $fin; $fecha->addWeek()) {
                    $finSemana = $fecha->copy()->endOfWeek();
                    $tendencias['meses'][] = $fecha->format('d M') . ' - ' . $finSemana->format('d M');
                    
                    $datosSemana = Proyecto::whereBetween('created_at', [$fecha, $finSemana]);
                    
                    switch($grafico) {
                        case 'ingresosTendencia':
                            $tendencias['ingresos'][] = $datosSemana->sum('presupuesto');
                            break;
                        case 'tiempoTendencia':
                            $tiempoMedio = $datosSemana->whereNotNull('fecha_inicio')
                                ->whereNotNull('updated_at')
                                ->where('estado', 'Completado')
                                ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                                ->value('tiempo_medio') ?? 0;
                            $tendencias['tiempos'][] = round($tiempoMedio, 1);
                            break;
                        case 'exitoTendencia':
                            $total = $datosSemana->count();
                            $completados = $datosSemana->where('estado', 'Completado')->count();
                            $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                            break;
                    }
                }
                break;

            case 'anio':
                // Datos mensuales del último año
                for ($i = 11; $i >= 0; $i--) {
                    $fecha = now()->subMonths($i);
                    $tendencias['meses'][] = $fecha->format('M Y');
                    
                    $datosMes = Proyecto::whereYear('created_at', $fecha->year)
                        ->whereMonth('created_at', $fecha->month);
                    
                    switch($grafico) {
                        case 'ingresosTendencia':
                            $tendencias['ingresos'][] = $datosMes->sum('presupuesto');
                            break;
                        case 'tiempoTendencia':
                            $tiempoMedio = $datosMes->whereNotNull('fecha_inicio')
                                ->whereNotNull('updated_at')
                                ->where('estado', 'Completado')
                                ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                                ->value('tiempo_medio') ?? 0;
                            $tendencias['tiempos'][] = round($tiempoMedio, 1);
                            break;
                        case 'exitoTendencia':
                            $total = $datosMes->count();
                            $completados = $datosMes->where('estado', 'Completado')->count();
                            $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                            break;
                    }
                }
                break;

            case 'general':
                // Datos anuales desde el inicio
                $primerProyecto = Proyecto::oldest('created_at')->first();
                if ($primerProyecto) {
                    $añoInicio = $primerProyecto->created_at->year;
                    $añoFin = now()->year;
                    
                    for ($año = $añoInicio; $año <= $añoFin; $año++) {
                        $tendencias['meses'][] = (string)$año;
                        
                        $datosAño = Proyecto::whereYear('created_at', $año);
                        
                        switch($grafico) {
                            case 'ingresosTendencia':
                                $tendencias['ingresos'][] = $datosAño->sum('presupuesto');
                                break;
                            case 'tiempoTendencia':
                                $tiempoMedio = $datosAño->whereNotNull('fecha_inicio')
                                    ->whereNotNull('updated_at')
                                    ->where('estado', 'Completado')
                                    ->selectRaw('AVG(DATEDIFF(updated_at, fecha_inicio)) as tiempo_medio')
                                    ->value('tiempo_medio') ?? 0;
                                $tendencias['tiempos'][] = round($tiempoMedio, 1);
                                break;
                            case 'exitoTendencia':
                                $total = $datosAño->count();
                                $completados = $datosAño->where('estado', 'Completado')->count();
                                $tendencias['tasas_exito'][] = $total > 0 ? round(($completados / $total) * 100, 1) : 0;
                                break;
                        }
                    }
                }
                break;
        }

        return response()->json($tendencias);
    }
} 