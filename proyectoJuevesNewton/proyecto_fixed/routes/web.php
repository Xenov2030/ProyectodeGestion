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

// ─── Dashboard ────────────────────────────────────────────────
$router->get('/',          [DashboardController::class, 'index']);
$router->get('dashboard',  [DashboardController::class, 'index']);

// ─── Idioma ───────────────────────────────────────────────────
$router->get('lang',       [LanguageController::class, 'switch']);

// ─── Autenticacion ────────────────────────────────────────────
$router->get('login',      [AuthController::class, 'showLogin']);
$router->post('login',     [AuthController::class, 'login']);
$router->get('logout',     [AuthController::class, 'logout']);

// ─── Usuarios ─────────────────────────────────────────────────
$router->get('users',          [UserController::class, 'index']);
$router->get('users/create',   [UserController::class, 'create']);
$router->post('users/create',  [UserController::class, 'store']);
$router->get('users/edit',     [UserController::class, 'edit']);
$router->post('users/edit',    [UserController::class, 'update']);
$router->post('users/delete',  [UserController::class, 'delete']);

// ─── Proyectos ────────────────────────────────────────────────
$router->get('proyectos',          [ProjectController::class, 'index']);
$router->get('proyectos/crear',    [ProjectController::class, 'create']);
$router->post('proyectos/crear',   [ProjectController::class, 'store']);
$router->get('proyectos/editar',   [ProjectController::class, 'edit']);
$router->post('proyectos/update',  [ProjectController::class, 'update']);

// ─── Tickets ──────────────────────────────────────────────────
$router->get('tickets',        [TicketController::class, 'index']);
$router->get('tickets/crear',  [TicketController::class, 'create']);
$router->post('tickets/crear', [TicketController::class, 'store']);
$router->get('tickets/ver',    [TicketController::class, 'show']);

// ─── Chat ─────────────────────────────────────────────────────
$router->get('chat', [ChatController::class, 'index']);
