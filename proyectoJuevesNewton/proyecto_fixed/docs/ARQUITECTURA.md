# 🏛️ Guía de Arquitectura (MVC)

Este proyecto utiliza el patrón **Modelo-Vista-Controlador (MVC)**. Es la forma estándar en la industria para separar la lógica de los datos y el diseño.

## 🧱 Las 3 Capas

### 1. El MODELO (`app/Models/`)
Es el encargado de hablar con la base de datos. Cada tabla tiene su propio Modelo.
*   **Ejemplo**: `User.php` tiene funciones como `findById()`, `all()`, `create()`.
*   **Regla**: Si necesitas hacer un `SELECT`, `INSERT` o `UPDATE`, hazlo dentro de un Modelo.

### 2. La VISTA (`views/`)
Es el HTML y CSS que ve el usuario.
*   **Layouts**: Son las plantillas que se repiten (como el menú y el footer). Están en `views/layouts/app.php`.
*   **Contenido**: Cada página tiene su propio archivo (ej: `views/dashboard/index.php`).
*   **Regla**: No pongas lógica compleja de PHP aquí, solo muestra los datos que te manda el controlador.

### 3. El CONTROLADOR (`app/Controllers/`)
Es el intermediario. Recibe la orden del usuario, le pide datos al Modelo y se los entrega a la Vista.
*   **Ejemplo**: `ProjectController.php` recibe la petición de "Ver Proyectos", pide la lista al modelo `Project` y carga la vista `projects/index.php`.

---

## 🚦 El Flujo de una Petición
Cuando alguien entra a `http://localhost/.../dashboard`:
1.  **Front Controller**: El archivo `public/index.php` recibe todo.
2.  **Router**: Revisa `routes/web.php` y ve que `/dashboard` le pertenece a `DashboardController::index()`.
3.  **Controller**: El controlador se ejecuta, verifica si el usuario está logueado y prepara los datos.
4.  **View**: El controlador llama a `$this->render('dashboard/index')` y el usuario ve la página.

---

## 🛠️ Cómo agregar una nueva funcionalidad
Imagina que quieres agregar una sección de **"Empresas"**:

1.  **Base de Datos**: Crea la tabla `empresas` en phpMyAdmin.
2.  **Modelo**: Crea `app/Models/Empresa.php` con las consultas SQL.
3.  **Controlador**: Crea `app/Controllers/EmpresaController.php` con métodos como `index()` o `crear()`.
4.  **Ruta**: Agrega `$router->get('empresas', [EmpresaController::class, 'index'])` en `routes/web.php`.
5.  **Vista**: Crea la carpeta `views/empresas/` y el archivo `index.php`.

---

## 🌐 Sistema de Idiomas (I18n)
Para mantener el sistema bilingüe:
*   **NO escribas texto directo**: En lugar de `<h1>Proyectos</h1>`, usa `<h1><?= I18n::t('projects') ?></h1>`.
*   **Agregar palabras**: Si necesitas una palabra nueva, agrégala en `app/Core/I18n.php` tanto en el array `es` como en `en`.
