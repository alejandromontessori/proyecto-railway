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
        Schema::create('opiniones', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_autor")->constrained("users")->cascadeOnDelete();
            $table->foreignId("id_idea")->constrained("ideas")->cascadeOnDelete();
            $table->foreignId("id_respondido")->nullable()->constrained("opiniones")->cascadeOnDelete();
            $table->text("texto");
            $table->integer("valoracion")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opiniones');
    }
};
