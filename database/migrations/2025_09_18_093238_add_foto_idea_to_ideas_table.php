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
        Schema::table('ideas', function (Blueprint $table) {
            if (!Schema::hasColumn('ideas', 'fotoIdea')) {
                $table->string('fotoIdea')->nullable()->after('dificultad');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            if (Schema::hasColumn('ideas', 'fotoIdea')) {
                $table->dropColumn('fotoIdea');
            }
        });
    }
};
