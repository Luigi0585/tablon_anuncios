<?php
namespace App\Core;

class Router {
    public function enrutar() {
        // 1. Detectar el controlador y la acción solicitada por la URL (con valores por defecto)
        $controllerName = $_GET['controller'] ?? 'Post'; // Si no hay, va al tablón de anuncios
        $actionName = $_GET['action'] ?? 'index';

        // 2. Construir el nombre completo de la clase con su Namespace legítimo
        $claseControlador = "App\\Controllers\\" . $controllerName . "Controller";

        // 3. Comprobar de forma segura si la clase física existe gracias al autoloader
        if (!class_exists($claseControlador)) {
            header("HTTP/1.1 404 Not Found");
            echo "<h1>Error 404</h1><p>El controlador '{$controllerName}Controller' no existe.</p>";
            exit;
        }

        // 4. Instanciar dinámicamente el controlador correspondiente
        $controlador = new $claseControlador();

        // 5. Comprobar si el método (acción) existe dentro de esa clase
        if (!method_exists($controlador, $actionName)) {
            header("HTTP/1.1 404 Not Found");
            echo "<h1>Error 404</h1><p>La acción '{$actionName}' no se encuentra en el controlador.</p>";
            exit;
        }

        // 6. Ejecutar la acción
        $controlador->$actionName();
    }
}