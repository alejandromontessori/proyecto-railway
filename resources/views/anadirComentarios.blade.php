<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Comentario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b0b0b0;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        form {
            background-color: rgba(73, 72, 71, 0.8);
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 0 10px #000;
        }

        textarea, input, select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
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
            width: auto;
            text-align: center;
            border: solid;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #6aa7a2;
        }

        .error {
            color: #ffaaaa;
            font-weight: bold;
            margin-bottom: 15px;
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
    <a href="checkMiCuenta.php">Ir a mi cuenta</a>
    <a href="../Controlador/cerrarSesion.php">Cerrar sesión</a>
</nav>

<main>
    <h2>Añadir nuevo comentario</h2>

    <?php if (isset($_GET['mensaje'])): ?>
    <div style="background-color: #4CAF50; padding: 10px; color: white; border-radius: 5px; margin: 20px auto; max-width: 600px; text-align: center;">
        <?= htmlspecialchars($_GET['mensaje']) ?>
    </div>
<?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="../Controlador/checkearAnadirComentarios.php" method="POST">
        <textarea name="texto" placeholder="Escribe tu comentario" required></textarea>
        <br>
        <select name="valoracion" required>
            <option value="">Selecciona una valoración</option>
            <option value="1">1 estrella</option>
            <option value="2">2 estrellas</option>
            <option value="3">3 estrellas</option>
            <option value="4">4 estrellas</option>
            <option value="5">5 estrellas</option>
        </select>
        <br>
        <input type="number" name="id_receta" placeholder="ID de la receta" required>
        <input type="number" name="id_autor" placeholder="ID de autor" required>
        <input type="number" name="id_respondido" placeholder="ID del comentario padre (opcional)">
        <br>
        <button type="submit" class="edit-button">Enviar comentario</button>
    </form>
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
