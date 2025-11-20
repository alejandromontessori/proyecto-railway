<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nombre de la tabla asociada
    protected $table = 'usuarios';

    protected $rememberTokenName=null;

    // Campos asignables en masa (fillable)
    protected $fillable = [
        'apodo',        // Nombre de usuario
        'contrasena',        // Contraseña
        //'registrationDate',// Fecha de registro
        'email',            // Correo electrónico
        'nombre',          // Nombre
        'apellidos',       // Apellidos
        'experiencia',     // Nivel de experiencia
        'fotoPerfil',      // Ruta de la foto de perfil
        'esAdmin'          // Si el usuario es administrador
    ];

    // Campos ocultos en respuestas JSON
    protected $hidden = [
        'contrasena',         // Contraseña hasheada
        'remember_token',   // Token de autenticación
    ];
    // Atributos con sus tipos de datos personalizados
    protected $casts = [
        'esAdmin' => 'boolean',
        'created_at' => 'datetime', // Laravel ya lo convierte automáticamente
        'updated_at' => 'datetime',
    ];

    // Relación uno a muchos con recetas (un usuario puede tener muchas recetas)
    public function ideas(): HasMany
    {
        return $this->hasMany(Receta::class, 'id_autor');
    }

    // Relación uno a muchos con comentarios (un usuario puede realizar muchos comentarios)
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'id_autor');
    }

    // Relación uno a muchos con valoraciones (un usuario puede hacer muchas valoraciones)
    public function valoraciones(): HasMany
    {
        return $this->hasMany(Valoracion::class, 'id_usuario');
    }

    // Mutador para hashear la contraseña automáticamente
    public function setContrasenaAttribute($value)
    {
        $this->attributes['contrasena'] = Hash::make($value);
    }

    // Acceso para manejar el valor predeterminado deApp\Models\Usuario::find(1)->recetas la foto de perfil
    public function getFotoPerfilAttribute($value)
    {
        return $value ?: '../Imagenes/predeterminado.png'; // Ruta por defecto si no hay imagen
    }

    // Validación de los valores del campo experiencia
    public function setExperienciaAttribute($value)
    {
        $valoresValidos = ['Principiante', 'Amateur', 'Profesional'];
        if (!in_array($value, $valoresValidos)) {
            throw new \InvalidArgumentException("El valor de experiencia debe ser 'Principiante', 'Intermedio' o 'Experto'.");
        }
        $this->attributes['experiencia'] = $value;
    }

    // Eventos para auditar cambios
    protected static function boot()
    {
        parent::boot();

        // Evento para auditar actualizaciones
        static::updating(function ($usuario) {
            \Log::info('Usuario actualizado:', [
                'id' => $usuario->id,
                'cambios' => $usuario->getDirty(),
            ]);
        });
    }
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'id_usuario');
    }
}

