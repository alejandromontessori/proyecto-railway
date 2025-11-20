<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('/imagenes/carton/casapajaros.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #ffffff;
        }
        .login-container {
            background-color: transparent;
            color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-container h1 { font-size: 28px; margin-bottom: 20px; }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container input[type="submit"] {
            background-color: #86b7b2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-container input[type="submit"]:hover { background-color: #255d47; }
        .login-container a { color: #86b7b2; text-decoration: none; }
        .login-container a:hover { text-decoration: underline; }
        .register-link { margin-top: 15px; display: block; }
        .alert { margin: 10px 0 0; padding: 8px 12px; border-radius: 6px; font-size: 14px; }
        .alert-error { background:#ffe9e9; color:#a40000; border:1px solid #ffb3b3; }
    </style>
</head>
<body>
<div class="login-container">
    <img src="{{ asset('imagenes/icono1.png') }}" alt="Registro/Login" style="max-width: 100%; border-radius: 10px; margin-bottom: 20px;">
    <h1>Iniciar Sesión</h1>

    {{-- Mensaje de error de login --}}
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin:0; padding-left:18px; text-align:left;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.enviar') }}" method="post">
        @csrf
        <input type="text" id="username" name="apodo" placeholder="Nombre de usuario" value="{{ old('apodo') }}" required>
        <input type="password" id="pwd" name="password" placeholder="Contraseña" required>
        <input type="submit" name="login" value="Iniciar Sesión">
    </form>

    <a href="{{ route('registro.form') }}" class="register-link">¿Eres un usuario nuevo? Regístrate</a>
</div>
</body>
</html>
