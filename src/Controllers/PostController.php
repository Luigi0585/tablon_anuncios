<?php
namespace App\Controllers;

class PostController {
    public function index() {
        echo "<h1>👋 ¡Bienvenido al Tablón de Anuncios!</h1>";
        echo "<p>El enrutador base funciona perfectamente.</p>";
        echo "<ul>";
        echo "<li><a href='/index.php?controller=Auth&action=showRegisterForm'>Ir a Registrarse</a></li>";
        echo "<li><a href='/index.php?controller=Auth&action=showLoginForm'>Ir a Iniciar Sesión</a></li>";
        echo "</ul>";
    }
} //NOTA: Este archivo sólo existe como un control de pruebas provisional.