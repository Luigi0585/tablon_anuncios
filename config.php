<?php
// config.php

return [
    'db' => [
        'host'     => 'db',               // Nombre del servicio de la BD en Docker
        'dbname'   => 'tablon_anuncios',  // Nombre de la base de datos que pusimos en el SQL
        'user'     => 'root',             // Usuario administrador por defecto
        'pass'     => 'root_password_segura', // Contraseña que pusiste en tu docker-compose.yml
        'charset'  => 'utf8mb4',          // Juego de caracteres seguro para evitar errores de codificación
    ],
];