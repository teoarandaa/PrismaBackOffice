<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with('cliente')->get();
        
        $eventos = $proyectos->flatMap(function($proyecto) {
            // Definir colores según el estado
            $color = match($proyecto->estado) {
                'En progreso' => '#FCD34D', // amarillo
                'Completado' => '#34D399',  // verde
                'Cancelado' => '#EF4444',   // rojo
                default => '#6B7280'        // gris por defecto
            };

            // Formatear el presupuesto
            $presupuesto = number_format($proyecto->presupuesto, 2, ',', '.') . ' €';
            
            // Convertir las fechas a objetos Carbon
            $inicio = Carbon::parse($proyecto->fecha_inicio);
            $fin = Carbon::parse($proyecto->fecha_finalizacion);
            
            // Crear un evento para cada día entre fecha_inicio y fecha_fin
            $eventos = [];
            for($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
                $eventos[] = [
                    'title' => $proyecto->nombre_proyecto,
                    'start' => $fecha->format('Y-m-d'),
                    'end' => $fecha->copy()->addDay()->format('Y-m-d'),
                    'color' => $color,
                    'textColor' => $proyecto->estado === 'En progreso' ? '#000000' : '#FFFFFF', // texto negro para amarillo, blanco para los demás
                    'display' => 'block',
                    'descripcion' => "📋 Proyecto: {$proyecto->nombre_proyecto}\n" .
                                   "👤 Cliente: {$proyecto->cliente->nombre} {$proyecto->cliente->apellido}\n" .
                                   "🏢 Empresa: {$proyecto->cliente->empresa}\n" .
                                   "💰 Presupuesto: {$presupuesto}\n" .
                                   "📊 Estado: {$proyecto->estado}\n" .
                                   "🏷️ Tipo: {$proyecto->tipo}\n" .
                                   "📝 Descripción: {$proyecto->descripcion}\n" .
                                   "📅 Periodo: " . $inicio->format('d/m/Y') . " - " . $fin->format('d/m/Y'),
                    'extendedProps' => [
                        'cliente_id' => $proyecto->cliente->id,
                        'proyecto_id' => $proyecto->id,
                        'cliente_nombre' => $proyecto->cliente->nombre . ' ' . $proyecto->cliente->apellido
                    ],
                    'allDay' => true,
                    'groupId' => 'proyecto_' . $proyecto->id,
                ];
            }
            
            return $eventos;
        });

        return view('proyectos.calendario', compact('eventos'));
    }
} 