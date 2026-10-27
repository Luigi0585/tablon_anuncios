<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Comment;

class CommentController {
    private Comment $commentModel;

    public function __construct() {
        $db = new Database();
        $this->commentModel = new Comment($db);
    }

    // Acción: Procesar y guardar un nuevo comentario enviado por POST
    public function store() {
        // 1. Control de Autenticación: Debe estar logueado para comentar (Ítem 7 de la rúbrica)
        if (!isset($_SESSION['user_id'])) {
            die("Debes iniciar sesión para comentar.");
        }

        $postId = (int)($_GET['post_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');

        if (empty($body) || $postId === 0) {
            die("El comentario no puede estar vacío.");
        }

        // 2. Persistencia asociando el user_id de la sesión (Nunca de campos ocultos del formulario)
        $this->commentModel->insert([
            'post_id' => $postId,
            'user_id' => $_SESSION['user_id'], // Extraído de $_SESSION de forma segura
            'body'    => $body
        ]);

        // Redirigimos de vuelta a la vista de detalle del anuncio
        header("Location: /index.php?controller=Post&action=show&id={$postId}");
        exit;
    }
}