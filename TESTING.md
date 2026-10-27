# 🧪 Plan de Pruebas Manuales - Tablón de Anuncios

Este documento contiene la batería de pruebas ejecutadas manualmente sobre la aplicación web para verificar el cumplimiento de los requisitos de la práctica y la robustez del sistema ante casos límite y de error.

---

## 📋 Lista de Casos de Prueba Ejecutados

### 🟢 Bloque A: Casos Normales (Flujo Feliz)

#### 1. Registro e Inicio de Sesión de un usuario nuevo
* **Descripción:** Comprobar que un visitante anónimo puede crear una cuenta e iniciar sesión con ella.
* **Pasos:** 
  1. Acceder a `?controller=Auth&action=showRegisterForm`.
  2. Introducir credenciales válidas y pulsar "Registrarse".
  3. En la pantalla de login, introducir los mismos datos y pulsar "Entrar".
* **Resultado Esperado:** El sistema registra al usuario aplicando `password_hash()`, redirige al login y, tras autenticarse, el tablón muestra el correo en la cabecera junto al enlace "Publicar Anuncio".
* **Estado:** **PASADO**

#### 2. Publicación de un anuncio en el tablón
* **Descripción:** Verificar que un usuario autenticado puede dar de alta un anuncio de forma exitosa.
* **Pasos:**
  1. Estar logueado y pulsar en "➕ Publicar Anuncio".
  2. Rellenar título, cuerpo, seleccionar una categoría y enviar.
* **Resultado Esperado:** El anuncio se inserta en la base de datos vinculándose al ID de la sesión y aparece listado cronológicamente en la página principal.
* **Estado:** **PASADO**

#### 3. Añadir un comentario a una publicación
* **Descripción:** Comprobar la relación 1:N permitiendo comentar dentro de un anuncio.
* **Pasos:**
  1. Hacer clic en el título de un anuncio para entrar en la vista de detalle (`show`).
  2. Escribir un texto en la caja inferior de comentarios y pulsar "Enviar".
* **Resultado Esperado:** El comentario se guarda asociado al anuncio y al usuario de la sesión, mostrándose abajo en orden cronológico ascendente.
* **Estado:** **PASADO**

---

### 🟡 Bloque B: Casos Límite y Validaciones

#### 4. Persistencia del término en el buscador simple
* **Descripción:** Validar que el buscador filtra las publicaciones y mantiene el texto en la caja (Ítem 8 de la rúbrica).
* **Pasos:**
  1. Introducir una palabra clave (ej: "Venta") en el buscador del tablón y pulsar Intro.
* **Resultado Esperado:** La web muestra únicamente los anuncios que contienen ese término en el título o categoría, y la caja de texto sigue mostrando la palabra escrita junto a un enlace de "Limpiar filtros".
* **Estado:** **PASADO**

#### 5. Control de campos obligatorios vacíos
* **Descripción:** Comprobar que la aplicación impide procesar registros si faltan datos obligatorios.
* **Pasos:**
  1. Entrar al formulario de creación de anuncios y borrar los atributos `required` desde el inspector del navegador para forzar el envío con el cuerpo vacío.
* **Resultado Esperado:** El backend intercepta la falta de datos mediante la validación `empty()` en el controlador y detiene la inserción lanzando un mensaje de advertencia controlado.
* **Estado:** **PASADO**

#### 6. Visibilidad condicional de elementos de gestión
* **Descripción:** Verificar que los botones de "Editar" y "Eliminar" solo son estéticamente visibles para el autor legítimo del anuncio.
* **Pasos:**
  1. Iniciar sesión con el Usuario A y publicar un anuncio.
  2. Cerrar sesión e iniciar sesión con el Usuario B.
* **Resultado Esperado:** El Usuario B ve el anuncio en el tablón, pero los enlaces de "Editar" y "Eliminar" no aparecen renderizados en su pantalla.
* **Estado:** **PASADO**

---

### 🛑 Bloque C: Casos de Error y Seguridad (Hackeos)

#### 7. Intento de inicio de sesión con credenciales incorrectas
* **Descripción:** Validar que el sistema rechaza el acceso ante contraseñas inválidas.
* **Pasos:**
  1. Introducir un correo existente pero escribir una contraseña errónea en el formulario de Login.
* **Resultado Esperado:** El método `password_verify()` detecta el desajuste del hash, deniega el ID de sesión y muestra el mensaje controlado "Credenciales incorrectas".
* **Estado:** **PASADO**

#### 8. Intento de publicación anónima forzada (Autenticación)
* **Descripción:** Validar que un usuario no logueado no puede saltarse las pantallas forzando la URL de creación.
* **Pasos:**
  1. Cerrar sesión por completo en la aplicación.
  2. Escribir manualmente en la barra de direcciones: `http://localhost:8080/index.php?controller=Post&action=create`
* **Resultado Esperado:** El controlador intercepta que no existe un `user_id` en la sesión y redirige inmediatamente al visitante hacia el Login.
* **Estado:** **PASADO**

#### 9. Intento de borrado forzado de un anuncio ajeno (Autorización por Propiedad)
* **Descripción:** Prueba crítica del Ítem 6. Validar que un usuario malicioso no puede borrar el anuncio de otro alterando el ID de la URL.
* **Pasos:**
  1. Iniciar sesión con el Usuario B.
  2. Forzar la barra de direcciones web escribiendo la acción de borrado apuntando al ID de un anuncio propiedad del Usuario A (ej: `?controller=Post&action=destroy&id=1`).
* **Resultado Esperado:** El servidor intercepta en el controlador que el `user_id` del anuncio de la BD no coincide con el `user_id` de la sesión actual, bloquea la petición devolviendo un código de estado HTTP 403 Forbidden y muestra el mensaje "No tienes permiso para borrar una publicación ajena".
* **Estado:** **PASADO**

#### 10. Manipulación maliciosa de formularios mediante ataques CSRF
* **Descripción:** Verificar la eficacia del escudo de seguridad del Paso 6.
* **Pasos:**
  1. Entrar al formulario de publicar anuncio.
  2. Abrir el inspector de elementos del navegador, localizar el campo oculto `<input type="hidden" name="csrf_token">` y borrar o alterar su valor antes de enviar.
* **Resultado Esperado:** Al procesar el POST, el Front Controller compara los tokens mediante `hash_equals()`, detecta la alteración, bloquea la ejecución con un código HTTP 403 Forbidden y destruye la solicitud impidiendo la inserción en la BD.
* **Estado:** **PASADO**