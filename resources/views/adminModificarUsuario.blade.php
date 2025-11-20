<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b0b0b0;
            color: #ffffff;
            text-align: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        h1 {
            font-size: 24px;
            margin-top: 20px;
        }
        p {
            font-size: 18px;
        }
        a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }
        button {
            font-size: 15px;
            padding: 15px 30px;
            color: white;
            background-color: #86b7b2;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .form-container {
            display: inline-block;
            background-color: rgba(73, 72, 71, 0.8);
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto; /* Centra horizontalmente */
            text-align: left;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        nav {
            background-color: rgba(73, 72, 71, 0.8);
            padding: 10px 0;
            text-align: center;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        nav a {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        nav a:hover {
            background-color: #86b7b2;
            border-radius: 5px;
            transform: scale(1.1);
        }
        footer {
            background-color: rgba(73, 72, 71, 0.8);
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav>
        <a href="../Vista/administrador.php">Administración</a>
        <a href="../Vista/index.php">Página Principal</a>
        <a href="checkRecetas.php">Recetas</a>
        <a href="checkMiCuenta.php">Ir a mi cuenta</a>
        <a href="../Controlador/cerrarSesion.php">Cerrar sesión</a>
    </nav>

    <h1>Modificar perfil de ID: <?php echo htmlspecialchars($idUsuario); ?></h1>

    <div class="form-container">
        <form action="checkAdminModifUsuario.php?id=<?php echo htmlspecialchars($idUsuario); ?>" method="post" enctype="multipart/form-data">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usuarioDetalles['username']); ?>" placeholder="Sin datos">

            <label for="apodo">Apodo</label>
            <input type="text" id="apodo" name="apodo" placeholder="Apodo">

            <label for="pwd">Contraseña (dejar en blanco para no cambiar):</label>
            <input type="password" id="pwd" name="pwd" placeholder="Nueva contraseña">

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuarioDetalles['mail']); ?>" placeholder="Sin datos">

            <label for="esAdmin">Es administrador:</label>
            <input type="checkbox" id="esAdmin" name="esAdmin" <?php echo ($usuarioDetalles['esAdmin'] == 1) ? 'checked' : ''; ?>>

            <label for="fotoPerfil">Foto de perfil actual:</label>
            <?php if (!empty($usuarioDetalles['foto_Perfil'])): ?>
                <div>
                    <img src="<?php echo htmlspecialchars($usuarioDetalles['foto_Perfil']); ?>" alt="Foto de perfil" width="100" height="100">
                </div>
            <?php else: ?>
                <p>No disponible</p>
            <?php endif; ?>



            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuarioDetalles['nombre']); ?>" placeholder="Sin datos">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuarioDetalles['apellido']); ?>" placeholder="Sin datos">


            <label for="experiencia">Nivel de experiencia:</label>
            <select name="experiencia" id="experiencia">
                <option value="" disabled <?php echo empty($usuarioDetalles['experiencia']) ? 'selected' : ''; ?>>Selecciona tu experiencia</option>
                <option value="principiante" <?php echo ($usuarioDetalles['experiencia'] === 'principiante') ? 'selected' : ''; ?>>Principiante</option>
                <option value="amateur" <?php echo ($usuarioDetalles['experiencia'] === 'amateur') ? 'selected' : ''; ?>>Intermedio</option>
                <option value="profesional" <?php echo ($usuarioDetalles['experiencia'] === 'profesional') ? 'selected' : ''; ?>>Experto</option>
            </select>


            <br><br>
            <label for="foto">Subir nueva foto de perfil:</label>
            <input type="file" id="foto" name="foto" accept="image/*">



            <br><br>
            <button type="submit">Actualizar</button>
        </form>
    </div>

    <footer>
        <div>
            <p>&copy; 2025 Foroplatos La mejor página de recetas de la web. Todos los derechos reservados.</p>
            <p>Síguenos en:
                <a href="https://www.facebook.com/foroplatos" target="_blank" style="color: #fff; margin: 0 10px; text-decoration: none;">Facebook</a> |
                <a href="https://www.instagram.com/foroplatos" target="_blank" style="color: #fff; margin: 0 10px; text-decoration: none;">Instagram</a> |
                <a href="https://twitter.com/foroplatos" target="_blank" style="color: #fff; margin: 0 10px; text-decoration: none;">Twitter</a>
            </p>
        </div>
    </footer>
</body>
</html>
