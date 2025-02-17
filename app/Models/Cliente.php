<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'ciudad',
        'codigo_postal',
        'pais',
        'empresa',
        'fecha_registro'
    ];

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_cliente');
    }
} 