<?php
// public/index.php

// 1. Requerimos el autoloader de Composer creado a mano
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Inicializamos el sistema global de sesiones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Core\Router;

// 3. Arrancamos el enrutador para delegar la petición al controlador correspondiente
$router = new Router();
$router->enrutar();