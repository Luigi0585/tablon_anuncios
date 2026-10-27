<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Anuncio</title>
</head>
<body>
    <h1>✏️ Editar Anuncio</h1>
    <p><a href="/index.php?controller=Post&action=index">⬅️ Cancelar y volver</a></p>

    <form action="/index.php?controller=Post&action=update&id=<?= $post['id'] ?>" method="POST">
        <!-- Escudo CSRF obligatorio en formularios POST -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div>
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; max-width: 500px;" required>
        </div>
        <br>
        <div>
            <label for="category">Categoría:</label><br>
            <select id="category" name="category" required>
                <option value="Venta" <?= $post['category'] === 'Venta' ? 'selected' : '' ?>>Venta</option>
                <option value="Proyectos" <?= $post['category'] === 'Proyectos' ? 'selected' : '' ?>>Búsqueda de grupos</option>
                <option value="Objetos perdidos" <?= $post['category'] === 'Objetos perdidos' ? 'selected' : '' ?>>Objetos perdidos</option>
                <option value="Otros" <?= $post['category'] === 'Otros' ? 'selected' : '' ?>>Otros avisos</option>
            </select>
        </div>
        <br>
        <div>
            <label for="body">Cuerpo del anuncio:</label><br>
            <textarea id="body" name="body" rows="6" style="width: 100%; max-width: 500px;" required><?= htmlspecialchars($post['body']) ?></textarea>
        </div>
        <br>
        <button type="submit">Actualizar publicación</button>
    </form>
</body>
</html>