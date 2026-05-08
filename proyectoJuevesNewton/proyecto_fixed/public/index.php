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

// ── 3. Carga del framework ──────────────────────────────────
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Core/Session.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/I18n.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Router.php';

// ── 4. Cargar variables de entorno desde .env ───────────────
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$name, $value] = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
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

// ── 7. Instanciar el Router — UNA SOLA VEZ ──────────────────
// (web.php solo registra rutas, NO crea nuevo Router ni despacha)
$router = new \app\Core\Router();

// ── 8. Registrar rutas ──────────────────────────────────────
require_once __DIR__ . '/../routes/web.php';

// ── 9. Resolver la URI limpia ───────────────────────────────
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir  = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseDir    = dirname($scriptDir);

if ($baseDir !== '/' && strpos($requestUri, $baseDir) === 0) {
    $requestUri = substr($requestUri, strlen($baseDir));
}

$requestUri = preg_replace('#^/public#', '', $requestUri);
$requestUri = $requestUri ?: '/';

$method = $_SERVER['REQUEST_METHOD'];

// ── 10. Despachar ───────────────────────────────────────────
$router->dispatch($requestUri, $method);
