<?php
// routes/web.php
// Solo define rutas. El $router viene de public/index.php.
// NO instancia Router. NO despacha.

use app\Controllers\DashboardController;
use app\Controllers\AuthController;
use app\Controllers\UserController;
use app\Controllers\ProjectController;
use app\Controllers\TicketController;
use app\Controllers\ChatController;
use app\Controllers\LanguageController;
use app\Controllers\AuditLogController;

// ─── Middlewares Predefinidos ──────────────────────────────────
$auth = ['AuthMiddleware'];
$adminOnly = ['AuthMiddleware' => ['admin']];
$staffOnly = ['AuthMiddleware' => ['admin', 'directivo', 'administrativo']];

// ─── Dashboard ────────────────────────────────────────────────
$router->get('/',          [DashboardController::class, 'index'], $auth);
$router->get('dashboard',  [DashboardController::class, 'index'], $auth);

// ─── Idioma ───────────────────────────────────────────────────
$router->get('lang',       [LanguageController::class, 'switch']);

// ─── Autenticacion ────────────────────────────────────────────
$router->get('login',          [AuthController::class, 'showLogin']);
$router->post('login',         [AuthController::class, 'login']);
$router->post('login/forgot',  [AuthController::class, 'forgotPassword']);
$router->post('login/request', [AuthController::class, 'requestAccount']);
$router->get('logout',         [AuthController::class, 'logout']);
$router->get('crear-demo',     [AuthController::class, 'createDemoUser']);

// ─── Usuarios ─────────────────────────────────────────────────
$router->get('users',          [UserController::class, 'index'],  $staffOnly);
$router->get('users/create',   [UserController::class, 'create'], $adminOnly);
$router->post('users/create',  [UserController::class, 'store'],  $adminOnly);
$router->get('users/edit',     [UserController::class, 'edit'],   $adminOnly);
$router->post('users/edit',    [UserController::class, 'update'], $adminOnly);
$router->post('users/delete',  [UserController::class, 'delete'], $adminOnly);

// ─── Proyectos ────────────────────────────────────────────────
$router->get('proyectos',          [ProjectController::class, 'index'],  $auth);
$router->get('proyectos/crear',    [ProjectController::class, 'create'], $auth);
$router->post('proyectos/crear',   [ProjectController::class, 'store'],  $auth);
$router->get('proyectos/editar',   [ProjectController::class, 'edit'],   $staffOnly);
$router->post('proyectos/update',  [ProjectController::class, 'update'], $staffOnly);

// ─── Tickets ──────────────────────────────────────────────────
$router->get('tickets',        [TicketController::class, 'index'],  $auth);
$router->get('tickets/crear',  [TicketController::class, 'create'], $auth);
$router->post('tickets/crear', [TicketController::class, 'store'],  $auth);
$router->get('tickets/ver',    [TicketController::class, 'show'],   $auth);

// ─── Chat ─────────────────────────────────────────────────────
$router->get('chat',             [ChatController::class, 'index'],       $auth);
$router->get('chat/getMessages', [ChatController::class, 'getMessages'], $auth);
$router->post('chat/send',       [ChatController::class, 'send'],        $auth);

// ─── Registro de Auditoría ────────────────────────────────────
$router->get('auditoria',          [AuditLogController::class, 'index'],    $adminOnly);

// ─── Ruta Temporal para Crear Usuario Demo ─────────────────────
$router->get('crear-demo', [AuthController::class, 'createDemoUser']);
$router->get('auditoria/exportar', [AuditLogController::class, 'exportar'], $adminOnly);

