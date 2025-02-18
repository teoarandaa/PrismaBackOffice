<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'can_read')) {
                $table->boolean('can_read')->default(true);
            }
            if (!Schema::hasColumn('users', 'can_edit')) {
                $table->boolean('can_edit')->default(false);
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['can_read', 'can_edit', 'is_admin']);
        });
    }
}; 