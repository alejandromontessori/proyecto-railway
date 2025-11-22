<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();

            // Usuario que marca como favorito
            $table->unsignedBigInteger('id_usuario');

            // Idea marcada como favorita
            $table->unsignedBigInteger('id_idea');

            // Si quieres, puedes añadir las FK (no es obligatorio para que funcione):
            // $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('id_idea')->references('id')->on('ideas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};

