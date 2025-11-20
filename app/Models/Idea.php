<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Idea extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'id_autor',     // FK a usuarios.id
        'fotoIdea',     // opcional (nullable)
    ];

    /** Autor de la idea */
    public function autor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'id_autor', 'id');
    }

    /**
     * Comentarios asociados a la idea.
     * REQUIERE que la tabla 'comentarios' tenga la columna 'id_idea'.
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(\App\Models\Comentario::class, 'id_idea');
    }

    /**
     * Preferidos (favoritos) de esta idea.
     * REQUIERE una tabla (p.ej.) 'preferidos_ideas' con columnas: id, id_idea, id_usuario, timestamps
     */
    public function preferidos(): HasMany
    {
        return $this->hasMany(\App\Models\PreferidoIdea::class, 'id_idea');
    }

    /** Helper: ¿esta idea está marcada como preferida por un usuario? */
    /*public function esPreferidaDe(?int $usuarioId): bool
    {
        if (!$usuarioId) return false;
        return $this->preferidos()->where('id_usuario', $usuarioId)->exists();
    }*/
    public function opiniones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Opinion::class, 'id_idea');
    }
}


