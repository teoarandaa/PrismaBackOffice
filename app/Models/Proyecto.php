<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_proyecto',
        'descripcion',
        'fecha_inicio',
        'fecha_finalizacion',
        'presupuesto',
        'estado',
        'tipo',
        'link',
        'id_cliente',
        'fecha_completado',
    ];

    protected $dates = [
        'fecha_completado',
    ];

    protected $table = 'proyectos';

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
} 