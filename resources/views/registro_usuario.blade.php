<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('/imagenes/tejidos/manta.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.65);
            color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        form h2 {
            grid-column: 1 / -1;
            margin-top: 30px;
            font-size: 20px;
            color: #007b8f;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
        }

        input[type="file"] { padding: 5px; }

        .full-width { grid-column: 1 / -1; }

        .submit-btn { grid-column: 1 / -1; text-align: center; }

        input[type="submit"] {
            background-color: #00cccc;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover { background-color: #0099a1; }

        .back-link { text-align: center; margin-top: 20px; grid-column: 1 / -1; }
        .back-link a { color: #007b8f; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }

        @media (max-width: 600px) { form { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="register-container">
    <h1>Formulario de Registro</h1>

    {{-- Mensajes de error simples (opcional) --}}
    @if ($errors->any())
        <div style="background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000;padding:10px;border-radius:6px;margin-bottom:12px;">
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/registro') }}" method="post" enctype="multipart/form-data">
        @csrf

        <h2>Datos de la cuenta</h2>
        <div>
            <label for="apodo">Apodo</label>
            <input type="text" id="apodo" name="apodo" value="{{ old('apodo') }}" required>
        </div>
        <div>
            <label for="pwd">Contraseña</label>
            <input type="password" id="pwd" name="password" required>
        </div>
        <div class="full-width">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <h2>Datos personales</h2>
        <div>
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>
        <div>
            <label for="apellidos">Apellido</label>
            <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
        </div>

        <div class="full-width">
            <label for="rol">Rol</label>
            <select id="rol" name="rol" required>
                <option value="">Seleccione una opción</option>
                <option value="Visitante" {{ old('rol')==='Visitante' ? 'selected' : '' }}>Visitante</option>
                <option value="Autor" {{ old('rol')==='Autor' ? 'selected' : '' }}>Autor</option>
            </select>
        </div>

        {{-- NUEVO: clave opcional para elevar a Administrador --}}
        <div class="full-width">
            <label for="admin_key">Clave de administrador (opcional)</label>
            <input type="password" id="admin_key" name="admin_key" placeholder="Déjalo vacío si no eres admin">
            <small style="display:block;color:#555;margin-top:-6px;">Si introduces la clave correcta, tu cuenta tendrá rol Administrador.</small>
        </div>

        <h2 class="full-width">Foto de perfil</h2>
        <div class="full-width">
            <input type="file" name="fotoPerfil" accept="image/*">
        </div>

        <div class="submit-btn">
            <input type="submit" name="registro" value="Registrarse">
        </div>

        <div class="back-link">
            <a href="{{ url('/') }}">← Volver al inicio</a>
        </div>
    </form>
</div>
</body>
</html>
