<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('importar-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $resumen = [
            'clientes' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0],
            'proyectos' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0],
            'errores' => []
        ];

        try {
            DB::beginTransaction();

            $archivo = $request->file('archivo');
            $contenido = file_get_contents($archivo->getRealPath());
            $lineas = explode("\n", trim($contenido));
            
            $seccionActual = '';
            $encabezados = [];
            $esEncabezado = false;

            foreach ($lineas as $linea) {
                $linea = trim($linea);
                if (empty($linea)) continue;

                // Detectar cambio de sección
                if (in_array($linea, ['CLIENTES', 'PROYECTOS', 'USUARIOS'])) {
                    $seccionActual = $linea;
                    $esEncabezado = true; // La siguiente línea será un encabezado
                    continue;
                }

                // Procesar encabezados
                if ($esEncabezado) {
                    $encabezados = str_getcsv($linea);
                    $esEncabezado = false;
                    continue;
                }

                // Procesar datos
                $datos = str_getcsv($linea);
                if (count($datos) < 3) continue; // Ignorar líneas sin datos suficientes

                switch ($seccionActual) {
                    case 'CLIENTES':
                        if (!str_contains($linea, 'ID,Nombre,')) { // Evitar procesar línea de encabezado
                            $this->procesarCliente($datos, $encabezados, $resumen);
                        }
                        break;
                    case 'PROYECTOS':
                        if (!str_contains($linea, 'ID,ID Cliente,')) { // Evitar procesar línea de encabezado
                            $this->procesarProyecto($datos, $encabezados, $resumen);
                        }
                        break;
                    // Ignoramos la sección USUARIOS
                }
            }

            DB::commit();
            return view('importar-resultado', compact('resumen'))
                ->with('success', 'Importación completada con éxito');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    private function procesarCliente($datos, $encabezados, &$resumen)
    {
        try {
            if (count($datos) !== count($encabezados)) {
                $resumen['errores'][] = "Error: número incorrecto de campos en la línea de cliente";
                return;
            }

            $datosCliente = array_combine($encabezados, $datos);
            
            // Verificar que es una línea de datos válida y no un encabezado
            if (!is_numeric($datosCliente['ID'])) {
                return;
            }

            $cliente = Cliente::updateOrCreate(
                ['email' => $datosCliente['Email']],
                [
                    'nombre' => $datosCliente['Nombre'],
                    'apellido' => $datosCliente['Apellido'] ?? 'N/A',
                    'empresa' => $datosCliente['Empresa'],
                    'telefono' => $datosCliente['Teléfono'] ?? null,
                    'ciudad' => $datosCliente['Ciudad'] ?? null,
                    'codigo_postal' => $datosCliente['Código Postal'] ?? null,
                    'pais' => $datosCliente['País'] ?? null
                ]
            );

            $resumen['clientes']['total']++;
            if ($cliente->wasRecentlyCreated) {
                $resumen['clientes']['nuevos']++;
            } else {
                $resumen['clientes']['actualizados']++;
            }
        } catch (\Exception $e) {
            $resumen['errores'][] = "Error procesando cliente: " . implode(',', $datos);
        }
    }

    private function procesarProyecto($datos, $encabezados, &$resumen)
    {
        try {
            if (count($datos) !== count($encabezados)) {
                $resumen['errores'][] = "Error: número incorrecto de campos en la línea de proyecto";
                return;
            }

            $datosProyecto = array_combine($encabezados, $datos);
            
            // Verificar que es una línea de datos válida y no un encabezado
            if (!is_numeric($datosProyecto['ID'])) {
                return;
            }

            // Debug para ver la estructura de datos
            \Log::info('Datos del proyecto:', $datosProyecto);

            $proyecto = Proyecto::updateOrCreate(
                [
                    'id' => $datosProyecto['ID'],
                    'id_cliente' => $datosProyecto['ID Cliente']
                ],
                [
                    'nombre_proyecto' => $datosProyecto['Nombre'],
                    'descripcion' => $datosProyecto['Descripción'] ?? null,
                    'tipo' => $datosProyecto['Tipo'] ?? 'web',
                    'fecha_inicio' => !empty($datosProyecto['Fecha Inicio']) ? $datosProyecto['Fecha Inicio'] : null,
                    'fecha_finalizacion' => !empty($datosProyecto['Fecha Finalización']) ? $datosProyecto['Fecha Finalización'] : null,
                    'estado' => $datosProyecto['Estado'] ?? 'En Progreso',
                    'presupuesto' => floatval(str_replace(',', '.', $datosProyecto['Presupuesto'])) ?? 0,
                    'link' => $datosProyecto['Link'] ?? null,
                    'fecha_completado' => !empty($datosProyecto['Fecha Completado']) ? 
                        $datosProyecto['Fecha Completado'] : null
                ]
            );

            $resumen['proyectos']['total']++;
            if ($proyecto->wasRecentlyCreated) {
                $resumen['proyectos']['nuevos']++;
            } else {
                $resumen['proyectos']['actualizados']++;
            }
        } catch (\Exception $e) {
            // Añadir más detalles al error para diagnóstico
            $resumen['errores'][] = "Error procesando proyecto: " . implode(',', $datos) . 
                " - Error: " . $e->getMessage();
            \Log::error('Error procesando proyecto:', [
                'datos' => $datos,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
