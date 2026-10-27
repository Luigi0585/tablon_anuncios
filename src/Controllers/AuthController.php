<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\User;

class AuthController {
    private User $userModel;

    //Cada vez que el Router necesita usar el AuthController y hace un new AuthController(),
    // este constructor se ejecuta automáticamente. Instancia la capa de datos 
    // (new Database()) y se la inyecta al modelo de usuario (new User($db)).
    // Gracias a esto, todas las acciones del controlador (login, registro) tienen la 
    // herramienta lista para hablar con la base de datos sin tener que repetir código de 
    // conexión en cada método.
    public function __construct() {
        $db = new Database();
        $this->userModel = new User($db);
    }

    // Muestra formulario de registro 
    public function showRegisterForm() {
        // Usamos __DIR__ para salir de Controllers e ir directo a Views/auth/
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    // Procesa el registro de un nuevo usuario
    //Tanto este método como showRegistrerForm() tienen la función de interceptar la 
    // petición del navegador e incluir (require_once) el archivo HTML correspondiente de la
    // vista.
    public function register() {
        //Los "trim" limpian los espacios en blanco que el usuario pueda meter por error al 
        // principio o al final de su correo o nombre.
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas de seguridad exigidas por la rúbrica
        if (empty($username) || empty($email) || empty($password)) {
            die("Todos los campos son obligatorios.");
        }

        // Encriptamos la contraseña obligatoriamente con password_hash()
        // Convierte la contraseña en una cadena irreversible y segura (NUNCA debe guardarse
        // en texto plano).
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Usamos el método insert() heredado de BaseModel
        // Invoca al método dinámico que heredado en BaseModel para meter los datos en MySQL
        // de forma limpia.
        $this->userModel->insert([
            'username' => $username,
            'email'    => $email,
            'password' => $passwordHash
        ]);

        // Redirigimos al formulario de login
        // Una vez registrado con éxito, redirige al usuario al Login para que pueda entrar. 
        // El exit; detiene el script inmediatamente por seguridad.
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

        //Le pasa el email y la contraseña en texto plano al modelo User.
        // Si el modelo responde con un ID válido (es decir, el usuario existe y la clave 
        // coincide), guardamos ese ID y el email en la superglobal $_SESSION.
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
    // Vacía el array de la sesión ($_SESSION = []) y destruye el archivo temporal del 
    // servidor (session_destroy()), borrando cualquier rastro de la identidad del usuario y
    // redirigiéndolo fuera
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: /index.php?controller=Auth&action=showLoginForm');
        exit;
    }
}