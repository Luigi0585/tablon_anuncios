<?php
// public/index.php

// 1. Requerimos el autoloader de Composer creado a mano. 
// Va hacia la carpeta raíz del proyecto y se mete dentro de la ejecución el archivo 
// autoload.php. 
// Al llamar a la función "spl_autoload_register()", que es nativa de php, se evita tener 
// que hacer varios "require" manuales ("require" se usa para acceder a clases de otros 
// archivos). 
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Inicializamos el sistema global de sesiones
// Comprueba si la sesión ya está encendida. Si no lo está (PHP_SESSION_NONE), la arranca con
// session_start().
// El protocolo HTTP (internet) no tiene memoria; no sabe si el usuario que hace un clic es 
// el mismo que hizo el clic anterior. Las sesiones lo solucionan, guardando un archivo 
// temporal seguro en el servidor vinculado a una cookie en el navegador del 
// usuario. Gracias a que ponemos esto en el index.php, la sesión estará disponible en toda 
// la aplicación, permitiéndonos recordar quién está logueado ($_SESSION['user_id']) 
// y almacenar los tokens de seguridad.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Core\Router;

// 3. Arrancamos el enrutador para delegar la petición al controlador correspondiente
// Es un alias para no tener que escribir \App\Core\Router entero abajo. Luego, 
// crea un objeto de la clase Router y llama a su función enrutar().
// index.php no sabe pintar pantallas ni sabe registrar usuarios; su único trabajo es 
// encender la máquina y pasarle el testigo al Router para que este mire la URL y decida qué
// controlador debe ponerse a trabajar.
$router = new Router();
$router->enrutar();