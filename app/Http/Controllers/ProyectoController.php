<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index(Cliente $cliente)
    {
        return $cliente->proyectos()->with('cliente')->get();
    }

    public function store(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre_proyecto' => 'required',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'estado' => 'nullable|in:En progreso,Completado,Cancelado',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|string'
        ]);

        \Log::info('Datos recibidos:', $request->all());
        \Log::info('Cliente ID:', ['id' => $cliente->id]);

        try {
            $proyecto = $cliente->proyectos()->create($request->all());
            
            if (!$proyecto) {
                return response()->json(['error' => 'No se pudo crear el proyecto'], 500);
            }

            \Log::info('Proyecto creado:', $proyecto->toArray());
            return response()->json([
                'message' => 'Proyecto creado exitosamente',
                'data' => $proyecto
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error al crear proyecto:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al crear el proyecto',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Cliente $cliente, Proyecto $proyecto)
    {
        return $proyecto->load('cliente');
    }

    public function update(Request $request, Cliente $cliente, Proyecto $proyecto)
    {
        $request->validate([
            'nombre_proyecto' => 'sometimes|required',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'estado' => 'nullable|in:En progreso,Completado,Cancelado',
            'presupuesto' => 'nullable|numeric',
            'link' => 'nullable|string'
        ]);

        $proyecto->update($request->all());
        return $proyecto;
    }

    public function destroy(Cliente $cliente, Proyecto $proyecto)
    {
        $proyecto->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }
} 