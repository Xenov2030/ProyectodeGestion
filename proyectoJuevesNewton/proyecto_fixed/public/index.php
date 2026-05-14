<?php
// public/index.php — Front Controller unico de la aplicacion

// ── 1. Configuracion de sesion ANTES de cualquier output ────
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// ── 2. Constante global de vistas ───────────────────────────
define('VIEWS_PATH', __DIR__ . '/../views');

// ── 3. Autoloader PSR-4 ───────────────────────────────────────
spl_autoload_register(function ($class) {
    $prefix = 'app\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

// ── 4. Cargar variables de entorno desde .env ───────────────
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath) && file_exists($envPath . '.example')) {
    copy($envPath . '.example', $envPath);
}

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$name, $value] = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
        $_ENV[trim($name)] = trim($value);
    }
}

// ── 5. Helpers globales ─────────────────────────────────────
function url(string $path = ''): string {
    return \app\Config\Config::baseUrl($path);
}

function redirect(string $path = ''): void {
    header('Location: ' . url($path));
    exit;
}

// ── 6. Iniciar sesion ───────────────────────────────────────
\app\Core\Session::init();

// ── 7. Instanciar el Router ─────────────────────────────────
$router = new \app\Core\Router();

// ── 8. Registrar rutas ──────────────────────────────────────
require_once __DIR__ . '/../routes/web.php';

// ── 9. Resolver la URI limpia ───────────────────────────────
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // e.g. /proyectos/proyecto_fixed/public/index.php

// 1. Obtener la ruta base del proyecto (quitando /public/index.php)
$projectRoot = preg_replace('#/public/index\.php$#', '', $scriptName);

// 2. Quitar la ruta del proyecto de la URI solicitada
if ($projectRoot !== '/' && !empty($projectRoot)) {
    if (strpos($requestUri, $projectRoot) === 0) {
        $requestUri = substr($requestUri, strlen($projectRoot));
    }
}

// 3. Quitar el prefijo /public si aún existe (acceso directo a public/...)
$requestUri = preg_replace('#^/public#', '', $requestUri);

// 4. Normalizar la URI
$requestUri = '/' . trim($requestUri, '/');
$requestUri = $requestUri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];


// ── 10. Despachar ───────────────────────────────────────────
$router->dispatch($requestUri, $method);

