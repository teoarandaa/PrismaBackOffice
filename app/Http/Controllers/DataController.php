<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        $csvContent .= "ID,ID Cliente,Nombre,Descripción,Fecha Inicio,Fecha Finalización,Estado,Presupuesto,Link,Fecha Completado\n";
        
        $proyectos = Proyecto::all();
        foreach ($proyectos as $proyecto) {
            $fechaCompletado = ($proyecto->estado === 'Completado') ? $proyecto->updated_at : '';
            
            $csvContent .= sprintf(
                "%d,%d,%s,%s,%s,%s,%s,%.2f,%s,%s\n",
                $proyecto->id,
                $proyecto->id_cliente,
                $this->escapeCsvField($proyecto->nombre_proyecto),
                $this->escapeCsvField($proyecto->descripcion),
                $proyecto->fecha_inicio,
                $proyecto->fecha_finalizacion,
                $this->escapeCsvField($proyecto->estado),
                $proyecto->presupuesto,
                $this->escapeCsvField($proyecto->link),
                $fechaCompletado
            );
        }
        
        // Modificar sección de usuarios para incluir contraseñas
        $csvContent .= "\nUSUARIOS\n";
        $csvContent .= "ID,Nombre,Email,Contraseña,Permisos Lectura,Permisos Edición,Es Admin\n";
        
        $users = User::where('id', '>', 1)->get(); // Excluir al admin principal
        foreach ($users as $user) {
            $csvContent .= sprintf(
                "%d,%s,%s,%s,%s,%s,%s\n",
                $user->id,
                $this->escapeCsvField($user->name),
                $this->escapeCsvField($user->email),
                $this->escapeCsvField($user->password_visible ?? ''),
                $user->can_read ? "Sí" : "No",
                $user->can_edit ? "Sí" : "No",
                $user->is_admin ? "Sí" : "No"
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
                'clientes' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0, 'errores' => []],
                'proyectos' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0, 'errores' => []],
                'usuarios' => ['total' => 0, 'nuevos' => 0, 'actualizados' => 0, 'errores' => []],
                'errores' => []
            ];
            
            DB::beginTransaction(); // Iniciar transacción

            foreach ($lineas as $numeroLinea => $linea) {
                $linea = trim($linea);
                if (empty($linea)) continue;
                
                // Detectar la sección
                if (in_array($linea, ['CLIENTES', 'PROYECTOS', 'USUARIOS'])) {
                    $modo = strtolower($linea);
                    $headers = null;
                    continue;
                }
                
                if (!$headers) {
                    $headers = str_getcsv($linea);
                    // Limpiar los headers de caracteres especiales
                    $headers = array_map(function($header) {
                        return trim($header);
                    }, $headers);
                    continue;
                }
                
                $datos = str_getcsv($linea);
                if (count($datos) <= 1) continue;
                
                try {
                    if (count($headers) !== count($datos)) {
                        throw new \Exception("El número de columnas no coincide en la línea " . ($numeroLinea + 1));
                    }
                    
                    $datosArray = array_combine($headers, $datos);
                    
                    switch ($modo) {
                        case 'clientes':
                            $resultado = $this->procesarCliente($datosArray);
                            $resumen['clientes']['total']++;
                            $resumen['clientes'][$resultado]++;
                            break;
                        case 'proyectos':
                            $resultado = $this->procesarProyecto($datosArray);
                            $resumen['proyectos']['total']++;
                            $resumen['proyectos'][$resultado]++;
                            break;
                        case 'usuarios':
                            $resultado = $this->procesarUsuario($datosArray);
                            $resumen['usuarios']['total']++;
                            $resumen['usuarios'][$resultado]++;
                            break;
                    }
                } catch (\Exception $e) {
                    $resumen[$modo]['errores'][] = "Error en línea " . ($numeroLinea + 1) . ": " . $e->getMessage();
                    \Log::error("Error procesando línea $numeroLinea en modo $modo: " . $e->getMessage());
                }
            }
            
            if (empty($resumen['errores']) && 
                empty($resumen['clientes']['errores']) && 
                empty($resumen['proyectos']['errores']) && 
                empty($resumen['usuarios']['errores'])) {
                DB::commit(); // Confirmar transacción si no hay errores
            } else {
                DB::rollBack(); // Revertir si hay errores
                throw new \Exception("Se encontraron errores durante la importación");
            }
            
            return view('importar-resultado', ['resumen' => $resumen]);
            
        } catch (\Exception $e) {
            DB::rollBack(); // Asegurar rollback en caso de error
            \Log::error("Error en importación: " . $e->getMessage());
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

        $proyecto = Proyecto::updateOrCreate(
            ['id' => $datos['ID']],
            [
                'id_cliente' => $datos['ID Cliente'],
                'nombre_proyecto' => $datos['Nombre'],
                'descripcion' => $datos['Descripción'] ?? '',
                'fecha_inicio' => $datos['Fecha Inicio'] ?? null,
                'fecha_finalizacion' => $datos['Fecha Finalización'] ?? null,
                'estado' => $datos['Estado'] ?? 'En progreso',
                'presupuesto' => $datos['Presupuesto'] ?? 0,
                'link' => $datos['Link'] ?? ''
            ]
        );

        // Actualizar fecha_completado si el estado es Completado
        if ($datos['Estado'] === 'Completado') {
            if (!empty($datos['Fecha Completado'])) {
                $proyecto->fecha_completado = $datos['Fecha Completado'];
            } else {
                // Si no hay fecha de completado en el CSV pero el estado es Completado,
                // usar la fecha actual
                $proyecto->fecha_completado = now();
            }
            $proyecto->save();
        } elseif ($datos['Estado'] !== 'Completado' && $proyecto->fecha_completado !== null) {
            // Si el estado no es Completado, asegurarse de que fecha_completado sea null
            $proyecto->fecha_completado = null;
            $proyecto->save();
        }

        return $proyecto->wasRecentlyCreated ? 'nuevos' : 'actualizados';
    }
    
    private function procesarUsuario($datos)
    {
        if (!isset($datos['Email'])) {
            throw new \Exception("El campo Email es requerido para el usuario");
        }

        // No permitir modificar al usuario admin principal
        $usuario = User::where('email', $datos['Email'])->first();
        if ($usuario && $usuario->id === 1) {
            throw new \Exception("No se puede modificar al usuario administrador principal");
        }

        $existe = User::where('email', $datos['Email'])->exists();
        
        // Convertir "Sí"/"No" a booleanos
        $canRead = strtolower($datos['Permisos Lectura'] ?? '') === 'sí';
        $canEdit = strtolower($datos['Permisos Edición'] ?? '') === 'sí';
        $isAdmin = strtolower($datos['Es Admin'] ?? '') === 'sí';
        
        $userData = [
            'name' => $datos['Nombre'] ?? '',
            'can_read' => $canRead,
            'can_edit' => $canEdit,
            'is_admin' => $isAdmin,
        ];
        
        // Usar la contraseña del CSV si existe, si no, generar una nueva
        if (!empty($datos['Contraseña'])) {
            $userData['password'] = Hash::make($datos['Contraseña']);
            $userData['password_visible'] = $datos['Contraseña'];
        } elseif (!$existe) {
            $password = Str::random(12);
            $userData['password'] = Hash::make($password);
            $userData['password_visible'] = $password;
        }

        User::updateOrCreate(
            ['email' => $datos['Email']],
            $userData
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