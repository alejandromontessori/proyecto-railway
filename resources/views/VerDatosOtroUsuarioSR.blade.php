<?php

?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <style>
  body {
    font-family: Arial, sans-serif;
    /*background-image: url('../Imagenes/fondo7.jpg');*/
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    color: #ffffff;
    margin: 0;
    padding-top: 80px;
    min-height: 70vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

nav {
    background-color: rgba(73, 72, 71, 0.8);
    padding: 12px 0;
    width: 100%;
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

nav a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    padding: 12px 25px;
    margin: 0 15px;
    display: inline-block;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

nav a:hover {
    background-color:rgb(139, 134, 183);
    border-radius: 5px;
    transform: scale(1.1);
    color: white;
}

.perfil-detalle, .recetas-detalle {
    background-color: rgba(73, 72, 71, 0.8);
    padding: 20px;
    border-radius: 15px;
    display: block;
    text-align: center;
    margin: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 700px;
    width: 100%;
}

img {
    display: block;
    margin: 0 auto 15px;
    border-radius: 50%;
    max-width: 150px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

p {
    font-size: 18px;
    margin: 8px 0;
    color: #ffffff;
}

footer {
    background-color: rgba(73, 72, 71, 0.8);
    color: white;
    text-align: center;
    padding: 15px 0;
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
    box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
}

a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.3s;
}

.recetas-lista p {
    margin: 10px 0;
}


a:hover {
    color: #86b7b2;
}

    </style>
</head>
<body>
<nav>
<?php
session_start();
    if (isset($_SESSION['nombreUsuario']) && $_SESSION['administrador'] == 1) {
        echo '<a href="../Vista/administrador.php">Administración</a>';
    }
    ?>

    <a href="/">Página Principal</a>
    <a href="/cuenta">Ir a mi cuenta</a>
    <a href="/logout">Cerrar sesión</a>


    </nav>

    <h1>Perfil de <?php echo htmlspecialchars($usuario['username']); ?></h1>
    <div class="perfil-detalle">
    <?php
        $foto = !empty($usuario['logo_usuario']) ? '../Imagenes/' . $usuario['logo_usuario'] : '../Imagenes/default_user.jpg';
    ?>
        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" width="150">
        <p><strong>Nombre de usuario:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p><strong>Experiencia:</strong> <?php echo !empty($usuario['experiencia']) ? htmlspecialchars($usuario['experiencia']) : 'Sin datos'; ?></p>
        <p><strong>Fecha de registro:</strong> <?php echo htmlspecialchars($usuario['registrationDate']); ?></p>

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




