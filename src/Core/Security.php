<?php
namespace App\Core;

class Security {
    
    public static function generateCsrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkCsrfToken(): void {
        // MUY IMPORTANTE: El escudo solo debe saltar si la petición es POST (envío de formulario)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tokenRecibido = $_POST['csrf_token'] ?? '';
            $tokenSesion = $_SESSION['csrf_token'] ?? '';

            if (empty($tokenSesion) || !hash_equals($tokenSesion, $tokenRecibido)) {
                header('HTTP/1.1 403 Forbidden');
                die("<h1>Error 403 - Solicitud no válida</h1><p>El token CSRF no coincide o ha expirado.</p>");
            }
        }
        // Si la petición es GET (navegación normal), el método termina sin hacer nada y deja pasar al usuario
    }
}