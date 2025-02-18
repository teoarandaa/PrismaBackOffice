<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password_visible')->nullable();
            $table->boolean('can_read')->default(true);
            $table->boolean('can_edit')->default(false);
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_visible');
            $table->dropColumn('can_read');
            $table->dropColumn('can_edit');
            $table->dropColumn('is_admin');
        });
    }
}; 