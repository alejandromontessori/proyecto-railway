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
        Schema::create('ideas', function (Blueprint $table) {
            $table->id();

            // Ahora apuntamos a la tabla 'users', que es la que usas con el modelo User
            $table->foreignId('id_autor')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->text('descripcion')->nullable(); // pon nullable si a veces la dejas vacía

            // Campo que te falta en Railway y que el modelo sí usa
            $table->string('tipo'); // o ->nullable() si quieres ir más seguro

            // Campo opcional de foto
            $table->string('fotoIdea')->nullable();

            // Si quieres conservar dificultad y la usas en la web, puedes dejarla:
            // $table->enum('dificultad', ['facil','media','dificil'])->default('media');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas');
    }
};
