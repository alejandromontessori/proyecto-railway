<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            // 1) Soltar la FK anterior (a 'usuarios')
            // Si no sabes el nombre exacto: usa por columna
            $table->dropForeign(['id_autor']);

            // 2) Crear la FK nueva a 'users'
            $table->foreign('id_autor')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            // Revertir a la FK antigua (si la necesitas)
            $table->dropForeign(['id_autor']);
            $table->foreign('id_autor')
                ->references('id')->on('usuarios')
                ->onDelete('cascade');
        });
    }
};
