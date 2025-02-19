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
            'proyectosCompletadosMes'
        ));
    }
} 