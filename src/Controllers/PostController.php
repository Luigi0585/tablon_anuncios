<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Post;

class PostController {
    private Post $postModel;

    public function __construct() {
        $db = new Database();
        $this->postModel = new Post($db);
    }

    // 1. Acción: Listar todas las publicaciones (con soporte de buscador)
    public function index() {
        $busqueda = trim($_GET['search'] ?? '');
        
        if (!empty($busqueda)) {
            $posts = $this->postModel->search($busqueda);
        } else {
            $posts = $this->postModel->getAllWithAutor();
        }

        require_once __DIR__ . '/../Views/posts/index.php';
    }

    // 2. Acción: Mostrar el formulario de creación (Protegido por Autenticación)
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?controller=Auth&action=showLoginForm');
            exit;
        }
        require_once __DIR__ . '/../Views/posts/create.php';
    }

    // 3. Acción: Guardar el anuncio enviado por el formulario
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?controller=Auth&action=showLoginForm');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $category = trim($_POST['category'] ?? '');

        if (empty($title) || empty($body) || empty($category)) {
            die("Todos los campos del anuncio son obligatorios.");
        }

        // Insertamos asociando el user_id extraído estrictamente de $_SESSION
        $this->postModel->insert([
            'user_id'  => $_SESSION['user_id'],
            'title'    => $title,
            'body'     => $body,
            'category' => $category
        ]);

        header('Location: /index.php?controller=Post&action=index');
        exit;
    }

    // 4. Acción: Ver el detalle de un anuncio específico
    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->getById($id);

        if (!$post) {
            header("HTTP/1.1 404 Not Found");
            die("<h1>Error 404</h1><p>El anuncio solicitado no existe.</p>");
        }

        // NUEVO: Instanciamos el modelo de comentarios e inyectamos sus datos en la vista
        $db = new \App\Core\Database();
        $commentModel = new \App\Models\Comment($db);
        $comments = $commentModel->getByPostId($id);

        require_once __DIR__ . '/../Views/posts/show.php';
    }

    // 5. Acción: Mostrar el formulario de edición (Protección de Autorización por Propiedad)
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?controller=Auth&action=showLoginForm');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->getById($id);

        if (!$post) {
            die("Publicación no encontrada.");
        }

        // CONTROL CRÍTICO DE AUTORIZACIÓN (Ítem 6 de la rúbrica)
        if ((int)$post['user_id'] !== (int)$_SESSION['user_id']) {
            header('HTTP/1.1 403 Forbidden');
            die("<h1>Error 403 - Prohibido</h1><p>No eres el dueño de esta publicación.</p>");
        }

        require_once __DIR__ . '/../Views/posts/edit.php';
    }

    // 6. Acción: Procesar la actualización en la BD
    public function update() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /index.php?controller=Auth&action=showLoginForm');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->getById($id);

        if (!$post || (int)$post['user_id'] !== (int)$_SESSION['user_id']) {
            header('HTTP/1.1 403 Forbidden');
            die("Acción no autorizada.");
        }

        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $category = trim($_POST['category'] ?? '');

        if (empty($title) || empty($body) || empty($category)) {
            die("Todos los campos son obligatorios.");
        }

        $this->postModel->update($id, [
            'title'    => $title,
            'body'     => $body,
            'category' => $category
        ]);

        header("Location: /index.php?controller=Post&action=show&id={$id}");
        exit;
    }

    // 7. Acción: Eliminar una publicación (Protección de Autorización por Propiedad)
    public function destroy() {
        if (!isset($_SESSION['user_id'])) {
            die("Debes iniciar sesión para borrar anuncios.");
        }

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->getById($id);

        if (!$post) {
            die("La publicación no existe.");
        }

        if ((int)$post['user_id'] !== (int)$_SESSION['user_id']) {
            header('HTTP/1.1 403 Forbidden');
            die("<h1>Error 403 - Prohibido</h1><p>No tienes permiso para borrar una publicación ajena.</p>");
        }

        $this->postModel->delete($id);

        header('Location: /index.php?controller=Post&action=index');
        exit;
    }
}