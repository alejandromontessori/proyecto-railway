<?php

use App\Models\Comentario;
use App\Models\Receta;
use App\Models\User;
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
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_autor")->constrained("users");
            $table->foreignId("id_receta")->constrained("recetas");
            $table->foreignId("id_respondido")->nullable()->constrained("comentarios");
            $table->text("texto");
            $table->integer("valoracion");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
