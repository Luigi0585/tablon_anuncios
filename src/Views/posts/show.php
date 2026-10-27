<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <p><a href="/index.php?controller=Post&action=index">⬅️ Volver al tablón</a></p>

    <article style="border: 1px solid #aaa; padding: 20px; background-color: #f9f9f9;">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p><strong>Categoría:</strong> <?= htmlspecialchars($post['category']) ?></p>
        <hr>
        <p style="font-size: 1.1em; line-height: 1.6;"><?= nl2br(htmlspecialchars($post['body'])) ?></p>
        <hr>
        <p><small>Publicado el <?= $post['created_at'] ?></small></p>

        <?php if (isset($_SESSION['user_id']) && (int)$post['user_id'] === (int)$_SESSION['user_id']): ?>
            <div>
                <a href="/index.php?controller=Post&action=edit&id=<?= $post['id'] ?>">✏️ Editar Anuncio</a> | 
                <a href="/index.php?controller=Post&action=destroy&id=<?= $post['id'] ?>" onclick="return confirm('¿Seguro?')">🗑️ Eliminar Anuncio</a>
            </div>
        <?php endif; ?>
    </article>

    <!-- AQUÍ SE ENLAZARÁN LOS COMENTARIOS EN EL PASO 5 -->
     <hr>
    <h2>💬 Comentarios</h2>

    <!-- Listado de comentarios existentes -->
    <section style="margin-bottom: 30px;">
        <?php if (empty($comments)): ?>
            <p>No hay comentarios todavía. ¡Sé el primero en opinar!</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div style="background-color: #f1f1f1; padding: 10px; margin-bottom: 10px; border-left: 4px solid #007BFF;">
                    <p style="margin: 0; font-size: 0.95em;"><?= nl2br(htmlspecialchars($comment['body'])) ?></p>
                    <small style="color: #666;">Por: <strong><?= htmlspecialchars($comment['autor']) ?></strong> el <?= $comment['created_at'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- Formulario para añadir un nuevo comentario (Visible solo si está autenticado) -->
    <section style="border: 1px solid #ccc; padding: 15px; background: #fff;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h3>Añadir un comentario</h3>
            <form action="/index.php?controller=Comment&action=store&post_id=<?= $post['id'] ?>" method="POST">
                <!-- Escudo CSRF obligatorio para proteger la inserción -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <textarea name="body" rows="4" style="width: 100%; max-width: 500px;" placeholder="Escribe tu comentario aquí..." required></textarea>
                <br><br>
                <button type="submit">Enviar comentario</button>
            </form>
        <?php else: ?>
            <p>Para dejar un comentario debes <a href="/index.php?controller=Auth&action=showLoginForm">iniciar sesión</a>.</p>
        <?php endif; ?>
    </section>

</body>
</html>