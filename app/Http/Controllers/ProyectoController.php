<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        return Proyecto::with('cliente')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'nombre_proyecto' => 'required',
            'estado' => 'in:En progreso,Completado,Cancelado'
        ]);

        return Proyecto::create($request->all());
    }

    public function show(Proyecto $proyecto)
    {
        return $proyecto->load('cliente');
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $proyecto->update($request->all());
        return $proyecto;
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }
} 