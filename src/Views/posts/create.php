<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Anuncio - Tablón de Anuncios</title>
</head>
<body>
    <h1>➕ Publicar un nuevo anuncio</h1>
    <p><a href="/index.php?controller=Post&action=index">⬅️ Volver al tablón</a></p>

    <form action="/index.php?controller=Post&action=store" method="POST">
        <!-- Escudo CSRF (Paso 6): Requisito indispensable para formularios de envío POST -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div>
            <label for="title">Título del anuncio:</label><br>
            <input type="text" id="title" name="title" style="width: 100%; max-width: 500px;" required>
        </div>
        <br>
        <div>
            <label for="category">Categoría:</label><br>
            <select id="category" name="category" required>
                <option value="">-- Selecciona una categoría --</option>
                <option value="Venta">Venta (libros, material...)</option>
                <option value="Proyectos">Búsqueda de grupos / proyectos</option>
                <option value="Objetos perdidos">Objetos perdidos</option>
                <option value="Otros">Otros avisos</option>
            </select>
        </div>
        <br>
        <div>
            <label for="body">Cuerpo del anuncio:</label><br>
            <textarea id="body" name="body" rows="6" style="width: 100%; max-width: 500px;" required></textarea>
        </div>
        <br>
        <button type="submit">Guardar y publicar anuncio</button>
    </form>
</body>
</html>