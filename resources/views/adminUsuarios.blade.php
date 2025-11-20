<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #b0b0b0;
            color: #ffffff;
            text-align: center;
            padding: 0;
        }

        nav {
            background-color: rgba(73, 72, 71, 0.8);
            padding: 10px 0;
            text-align: center;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        main {
            flex: 1;
            padding: 50px;
        }

        h2 {
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .btn {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }

        .btn-modificar {
            background-color: #4CAF50;
        }

        .btn-eliminar {
            background-color: #f44336;
        }

        footer {
            background-color: rgba(73, 72, 71, 0.8);
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto; /* Empuja el footer al final */
        }
    </style>
</head>
<body>
<nav>
    <a href="../Vista/administrador.php">Administracion</a>
    <a href="../Vista/index.php">Página Principal</a>
    <a href="../Controlador/checkMiCuenta.php">Ir a mi cuenta</a>
    <a href="../Controlador/cerrarSesion.php">Cerrar sesión</a>
</nav>

<main>
    <h2>Lista de Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Apodo</th>
            <th>Fecha de Registro</th>
            <th>Email</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Experiencia</th>
            <th>Foto de Perfil</th>
            <th>Es Administrador</th>

        </tr>
        <?php foreach ($usuariosAlmacenados as $usuario): ?>
        <tr>
            <td><?php echo !empty($usuario['id']) ? ($usuario['id']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['apodo']) ? ($usuario['apodo']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['username']) ? htmlspecialchars($usuario['username']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['registrationDate']) ? htmlspecialchars($usuario['registrationDate']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['mail']) ? htmlspecialchars($usuario['mail']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['nombre']) ? htmlspecialchars($usuario['nombre']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['apellido']) ? htmlspecialchars($usuario['apellido']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($usuario['experiencia']) ? htmlspecialchars($usuario['experiencia']) : 'Sin datos'; ?></td>
            <td>
                <?php if (!empty($usuario['fotoPerfil'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['fotoPerfil']); ?>" alt="Foto de perfil" width="50" height="50">
                <?php else: ?>
                    No disponible
                <?php endif; ?>
            </td>
            <td><?php echo $usuario['esAdmin'] ? 'Sí' : 'No'; ?></td>
            <td>
                <a href="checkAdminModifUsuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-modificar">Modificar</a>
                <a href="eliminarPerfilAdministrador.php?id=<?php echo $usuario['id']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                <a href="checkUsuarioReceta.php?id=<?php echo $usuario['id']; ?>" class="btn btn-modificar">Ver Recetas</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <!-- Paginación -->
<div style="margin-top: 20px;">
    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>"
           style="margin-right: 10px; text-decoration: none; font-weight: bold; color: white; background-color: #86b7b2; padding: 5px 10px; border-radius: 5px;">
            ← Anterior
        </a>
    <?php endif; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>"
           style="text-decoration: none; font-weight: bold; color: white; background-color: #86b7b2; padding: 5px 10px; border-radius: 5px;">
            Siguiente →
        </a>
    <?php endif; ?>
</div>

</main>

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
