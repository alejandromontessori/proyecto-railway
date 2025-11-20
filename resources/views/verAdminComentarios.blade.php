<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Recetas</title>
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
            *margin-top: 20px;

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





        .btn-eliminar {
            background-color: #f44336;
        }

        .btn-editar {
            background-color: #86b7b2;
        }

        .edit-button {
        display: inline-block;
        background-color: #86b7b2;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        margin: 20px auto;
        transition: background-color 0.3s ease, transform 0.3s ease;
        width: 100px;
        text-align: center;
        border: solid;
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

    <a href="../Vista/index.php">Página Principal</a>
    <a href="checkReceta.php">Recetas</a>
    <a href="checkMiCuenta.php">Ir a mi cuenta</a>
    <a href="../Controlador/cerrarSesion.php">Cerrar sesión</a>
</nav>

<h2>Lista de Comentarios</h2>

<?php if (isset($_GET['mensaje'])): ?>
    <p style="color: green; font-weight: bold; margin-top: 20px;">
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
    </p>
<?php endif; ?>

<table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <th style="width: 40%;">Texto</th>
        <th style="width: 10%;">Valoración</th>
        <th style="width: 10%;">ID Usuario</th>
        <th style="width: 10%;">ID Receta</th>
        <th style="width: 10%;">Comentario raíz</th>
        <th style="width: 10%;"></th>
    </tr>
    <?php foreach ($comentariosAlmacenados as $comentario): ?>
        <tr>
            <td style="max-width: 100%; word-wrap: break-word; white-space: normal;">
                <?php echo !empty($comentario['texto']) ? htmlspecialchars($comentario['texto']) : 'Sin datos'; ?>
            </td>
            <td><?php echo !empty($comentario['valoracion']) ? htmlspecialchars($comentario['valoracion']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($comentario['id_autor']) ? htmlspecialchars($comentario['id_autor']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($comentario['id_receta']) ? htmlspecialchars($comentario['id_receta']) : 'Sin datos'; ?></td>
            <td><?php echo !empty($comentario['id_respondido']) ? htmlspecialchars($comentario['id_respondido']) : 'Sin datos'; ?></td>
            <td style="text-align: center;">
                <?php
                    $usuarioActual = $_SESSION['id_autor'] ?? null;
                    $esAdmin = $_SESSION['administrador'] ?? false;

                    if ($comentario['id_autor'] == $usuarioActual || $esAdmin):
                ?>
                    <a href="../Controlador/eliminarComentario.php?id=<?php echo $comentario['id']; ?>"
                       class="btn btn-eliminar"
                       style="padding: 4px 8px; font-size: 13px;">
                       Eliminar
                    </a>
                    <a href="../Controlador/formEditarComentario.php?id=<?php echo $comentario['id']; ?>"
                       class="btn btn-editar"
                       style="padding: 4px 8px; font-size: 13px;">
                       Editar
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<br>
<a href="checkearAnadirComentarios.php" class="edit-button">Añadir nuevo comentario</a>
<a href="mostrarComentariosRaiz.php" class="edit-button">Comentarios recursivos</a>

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

</body>

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
</html>
