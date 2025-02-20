<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->timestamp('fecha_completado')->nullable();
        });

        // Migrar los datos existentes
        DB::statement("
            UPDATE proyectos 
            SET fecha_completado = updated_at 
            WHERE estado = 'Completado'
        ");
    }

    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('fecha_completado');
        });
    }
}; 