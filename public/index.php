<?php
// public/index.php

// Requerimos el autoloader de Composer oficial (ahora creado a mano)
require_once __DIR__ . '/../vendor/autoload.php';

// Iniciamos la sesión (paso obligatorio para la autenticación y tokens CSRF)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🚀 ¡Infraestructura oficial y Autoload completados con éxito!</h1>";
echo "<p>Hemos cubierto la función de Composer manualmente. Todo está listo para la lógica de la práctica.</p>";