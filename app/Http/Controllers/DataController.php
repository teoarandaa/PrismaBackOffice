<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function exportar()
    {
        // Obtener los datos
        $clientes = Cliente::with('proyectos')->get();
        
        // Crear el contenido CSV
        $csvContent = "CLIENTES\n";
        $csvContent .= "ID,Nombre,Apellido,Email,Teléfono,Empresa,Ciudad,Código Postal,País,Fecha Registro\n";
        
        foreach ($clientes as $cliente) {
            $csvContent .= sprintf(
                "%d,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $cliente->id,
                $this->escapeCsvField($cliente->nombre),
                $this->escapeCsvField($cliente->apellido),
                $this->escapeCsvField($cliente->email),
                $this->escapeCsvField($cliente->telefono),
                $this->escapeCsvField($cliente->empresa),
                $this->escapeCsvField($cliente->ciudad),
                $this->escapeCsvField($cliente->codigo_postal),
                $this->escapeCsvField($cliente->pais),
                $cliente->fecha_registro
            );
        }
        
        $csvContent .= "\nPROYECTOS\n";
        $csvContent .= "ID,ID Cliente,Nombre,Descripción,Fecha Inicio,Fecha Finalización,Estado,Presupuesto,Link\n";
        
        $proyectos = Proyecto::all();
        foreach ($proyectos as $proyecto) {
            $csvContent .= sprintf(
                "%d,%d,%s,%s,%s,%s,%s,%.2f,%s\n",
                $proyecto->id,
                $proyecto->id_cliente,
                $this->escapeCsvField($proyecto->nombre_proyecto),
                $this->escapeCsvField($proyecto->descripcion),
                $proyecto->fecha_inicio,
                $proyecto->fecha_finalizacion,
                $this->escapeCsvField($proyecto->estado),
                $proyecto->presupuesto,
                $this->escapeCsvField($proyecto->link)
            );
        }
        
        // Generar la respuesta
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="datos_'.date('Y-m-d_His').'.csv"',
        ];
        
        return response($csvContent, 200, $headers);
    }
    
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt'
        ]);
        
        try {
            $file = $request->file('archivo');
            $contenido = file_get_contents($file->path());
            $lineas = explode("\n", $contenido);
            
            $modo = null;
            $headers = null;
            $resumen = [
                'clientes' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0],
                'proyectos' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0],
                'errores' => []
            ];
            
            foreach ($lineas as $numeroLinea => $linea) {
                $linea = trim($linea);
                if (empty($linea)) continue;
                
                if ($linea === 'CLIENTES') {
                    $modo = 'clientes';
                    $headers = null;
                    continue;
                }
                
                if ($linea === 'PROYECTOS') {
                    $modo = 'proyectos';
                    $headers = null;
                    continue;
                }
                
                if (!$headers) {
                    $headers = str_getcsv($linea);
                    continue;
                }
                
                $datos = str_getcsv($linea);
                if (count($datos) <= 1) continue;
                
                try {
                    if (count($headers) !== count($datos)) {
                        throw new \Exception("El número de columnas no coincide en la línea " . ($numeroLinea + 1));
                    }
                    
                    $datosArray = array_combine($headers, $datos);
                    
                    if ($modo === 'clientes') {
                        $resultado = $this->procesarCliente($datosArray);
                        $resumen['clientes']['total']++;
                        $resumen['clientes'][$resultado]++;
                    } elseif ($modo === 'proyectos') {
                        $resultado = $this->procesarProyecto($datosArray);
                        $resumen['proyectos']['total']++;
                        $resumen['proyectos'][$resultado]++;
                    }
                } catch (\Exception $e) {
                    $resumen['errores'][] = "Error en línea " . ($numeroLinea + 1) . ": " . $e->getMessage();
                }
            }
            
            return view('importar-resultado', ['resumen' => $resumen]);
        } catch (\Exception $e) {
            return view('importar-resultado', [
                'resumen' => ['errores' => [$e->getMessage()]]
            ])->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }
    
    private function procesarCliente($datos)
    {
        if (!isset($datos['Email'])) {
            throw new \Exception("El campo Email es requerido");
        }

        $existe = Cliente::where('email', $datos['Email'])->exists();
        
        Cliente::updateOrCreate(
            ['email' => $datos['Email']],
            [
                'nombre' => $datos['Nombre'] ?? '',
                'apellido' => $datos['Apellido'] ?? '',
                'telefono' => $datos['Teléfono'] ?? '',
                'empresa' => $datos['Empresa'] ?? '',
                'ciudad' => $datos['Ciudad'] ?? '',
                'codigo_postal' => $datos['Código Postal'] ?? '',
                'pais' => $datos['País'] ?? '',
            ]
        );

        return $existe ? 'actualizados' : 'nuevos';
    }
    
    private function procesarProyecto($datos)
    {
        if (!isset($datos['ID Cliente'])) {
            throw new \Exception("El campo ID Cliente es requerido");
        }

        if (!Cliente::find($datos['ID Cliente'])) {
            throw new \Exception("El cliente con ID {$datos['ID Cliente']} no existe");
        }

        $existe = Proyecto::where([
            'id_cliente' => $datos['ID Cliente'],
            'nombre_proyecto' => $datos['Nombre']
        ])->exists();
        
        Proyecto::updateOrCreate(
            [
                'id_cliente' => $datos['ID Cliente'],
                'nombre_proyecto' => $datos['Nombre']
            ],
            [
                'descripcion' => $datos['Descripción'] ?? '',
                'fecha_inicio' => $datos['Fecha Inicio'] ?? null,
                'fecha_finalizacion' => $datos['Fecha Finalización'] ?? null,
                'estado' => $datos['Estado'] ?? 'En progreso',
                'presupuesto' => $datos['Presupuesto'] ?? 0,
                'link' => $datos['Link'] ?? ''
            ]
        );

        return $existe ? 'actualizados' : 'nuevos';
    }
    
    private function escapeCsvField($field)
    {
        if (empty($field)) return '';
        $field = str_replace('"', '""', $field);
        return '"' . $field . '"';
    }
} 