<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /*Schema::table('users', function (Blueprint $table) {
            $table->string('apodo')->after('id');
            $table->string('nombre')->nullable()->after('apodo');
            $table->string('apellidos')->nullable()->after('nombre');
            $table->string('foto_perfil')->nullable()->after('apellidos');
            // usa ENUM si tu BD es MySQL/MariaDB
            $table->enum('rol', ['Visitante','Autor','Administrador'])
                ->default('Visitante')
                ->after('password');*/
        //});
    }

    public function down(): void
    {
        //Schema::table('users', function (Blueprint $table) {
            //$table->dropColumn(['apodo','nombre','apellidos','foto_perfil','rol']);
        //});
    }
};
