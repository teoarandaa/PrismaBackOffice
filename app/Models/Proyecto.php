<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cliente',
        'nombre_proyecto',
        'descripcion',
        'fecha_inicio',
        'fecha_finalizacion',
        'estado',
        'presupuesto',
        'link'
    ];

    protected $table = 'proyectos';

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
} 