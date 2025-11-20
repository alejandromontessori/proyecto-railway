<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string("apodo");
            $table->string("email");
            $table->string("nombre");
            $table->string("apellidos");
            $table->string("contrasena");
            $table->boolean("esAdmin");
            $table->string("fotoPerfil")->nullable();
            $table->enum("experiencia",["Principiante","Amateur","Profesional"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
