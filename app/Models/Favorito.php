<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorito extends Model
{
    protected $table = 'favoritos';

    // columnas que se pueden asignar en masa
    protected $fillable = ['id_usuario', 'id_idea'];

    /** Usuario dueño del favorito (users.id) */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }

    /** Idea marcada como favorita (ideas.id) */
    public function idea(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Idea::class, 'id_idea', 'id');
    }
}
