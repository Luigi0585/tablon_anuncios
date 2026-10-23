<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\User;

class AuthController {
    private User $userModel;

    public function __construct() {
        $db = new Database();
        $this->userModel = new User($db);
    }

    // Muestra formulario de registro
    public function showRegisterForm() {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    // Procesa el registro de un nuevo usuario
    public function register() {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas de seguridad exigidas por la rúbrica
        if (empty($username) || empty($email) || empty($password)) {
            die("Todos los campos son obligatorios.");
        }

        // Encriptamos la contraseña obligatoriamente con password_hash()
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Usamos el método insert() heredado de BaseModel
        $this->userModel->insert([
            'username' => $username,
            'email'    => $email,
            'password' => $passwordHash
        ]);

        // Redirigimos al formulario de login
        header('Location: /index.php?controller=Auth&action=showLoginForm');
        exit;
    }

    // Muestra formulario de login
    public function showLoginForm() {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // Procesa el inicio de sesión
    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userId = $this->userModel->findByEmail($email, $password);

        if ($userId) {
            // Guardamos los datos de autenticación en la sesión
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $email;

            // Redirigimos a la página principal del tablón de anuncios
            header('Location: /index.php?controller=Post&action=index');
            exit;
        } else {
            die("Credenciales incorrectas.");
        }
    }

    // Cierra la sesión destruyendo los datos de $_SESSION
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: /index.php?controller=Auth&action=showLoginForm');
        exit;
    }
}