<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tablón de Anuncios</title>
</head>
<body>
    <header>
        <h1>📌 Tablón de Anuncios de la Clase</h1>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user_email']) ?></strong> | 
                   <a href="/index.php?controller=Post&action=create">➕ Publicar Anuncio</a> | 
                   <a href="/index.php?controller=Auth&action=logout">🚪 Cerrar Sesión</a>
                </p>
            <?php else: ?>
                <p>
                    <a href="/index.php?controller=Auth&action=showLoginForm">🔑 Iniciar Sesión</a> | 
                    <a href="/index.php?controller=Auth&action=showRegisterForm">📝 Registrarse</a>
                </p>
            <?php endif; ?>
        </div>
    </header>
    <hr>

    <!-- Buscador simple exigirido en el Paso 4 -->
    <section>
        <form action="/index.php" method="GET">
            <input type="hidden" name="controller" value="Post">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" placeholder="Buscar por título o categoría..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit">🔍 Buscar</button>
            <?php if (!empty($_GET['search'])): ?>
                <a href="/index.php?controller=Post&action=index">Limpiar filtros</a>
            <?php endif; ?>
        </form>
    </section>

    <h2>Anuncios recientes</h2>
    <section>
        <?php if (empty($posts)): ?>
            <p>No se ha encontrado ninguna publicación en el tablón. ¡Sé el primero en publicar uno!</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
                    <h3>
                        <a href="/index.php?controller=Post&action=show&id=<?= $post['id'] ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    <p><em>Categoría: <?= htmlspecialchars($post['category']) ?></em></p>
                    <p><?= nl2br(htmlspecialchars($post['body'])) ?></p>
                    <p><small>Publicado por: <strong><?= htmlspecialchars($post['autor'] ?? 'Anónimo') ?></strong></small></p>
                    
                    <?php if (isset($_SESSION['user_id']) && (int)$post['user_id'] === (int)$_SESSION['user_id']): ?>
                        <div style="margin-top: 10px;">
                            <a href="/index.php?controller=Post&action=edit&id=<?= $post['id'] ?>">✏️ Editar</a> | 
                            <a href="/index.php?controller=Post&action=destroy&id=<?= $post['id'] ?>" onclick="return confirm('¿Seguro?')">🗑️ Eliminar</a>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</body>
</html>