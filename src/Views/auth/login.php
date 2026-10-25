<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Acceso al Tablón de Anuncios</h1>
    <form action="/index.php?controller=Auth&action=login" method="POST">
        <!-- Campo oculto para el token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

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
        <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes una cuenta todavía? <a href="/index.php?controller=Auth&action=showRegisterForm">Regístrate aquí</a>.</p>
</body>
</html>
