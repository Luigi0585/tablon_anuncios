<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
</head>
<body>
    <h1>Crear una cuenta nueva</h1>
    <form action="/index.php?controller=Auth&action=register" method="POST">
        <!-- Campo oculto para el token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div>
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <br>
        <div>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <br>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Registrarse</button>
    </form>
    <p>¿Ya tienes cuenta? <a href="/index.php?controller=Auth&action=showLoginForm">Inicia sesión aquí</a>.</p>
</body>
</html>