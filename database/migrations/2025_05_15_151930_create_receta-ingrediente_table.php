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
        Schema::create('receta-ingrediente', function (Blueprint $table) {
            $table->foreignId('id_receta')->constrained('recetas');
            $table->foreignId('id_ingrediente')->constrained('ingredientes');
            $table->double('cantidad');
            $table->string('unidad');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
