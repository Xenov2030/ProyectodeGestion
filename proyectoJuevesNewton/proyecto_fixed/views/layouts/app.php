<?php
// views/layouts/app.php
use app\Core\Session;
use app\Core\I18n;

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = parse_url(url('/'), PHP_URL_PATH);
$route = trim(str_replace($basePath ?? '', '', $currentPath), '/');

$isDashboard = ($route === '' || $route === 'dashboard');
$isProyectos = (strpos($route, 'proyectos') === 0);
$isTickets   = (strpos($route, 'tickets') === 0);
$isChat      = (strpos($route, 'chat') === 0);
$isUsers     = (strpos($route, 'users') === 0);
?>
<!DOCTYPE html>
<html lang="<?= I18n::getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Pro | <?= I18n::t('dashboard') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= url('public/css/style.css') ?>" rel="stylesheet">
    <style>
        /* Ajustes críticos para el bot */
        #bot-widget { position: fixed; bottom: 30px; right: 30px; z-index: 9999; }
        #bot-panel { 
            display: none; position: absolute; bottom: 80px; right: 0; 
            width: 350px; height: 500px; background: #fff; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); border-radius: 20px; 
            overflow: hidden; flex-direction: column; 
        }
        .bot-header { background: #4f46e5; color: #fff; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; }
        #bot-content { flex-grow: 1; overflow-y: auto; padding: 1.5rem; background: #f9fafb; display: flex; flex-direction: column; gap: 1rem; }
        .msg { padding: 0.75rem 1rem; border-radius: 15px; font-size: 0.85rem; max-width: 85%; line-height: 1.4; }
        .msg.bot { background: #eef2ff; color: #3730a3; align-self: flex-start; border-bottom-left-radius: 2px; }
        .msg.user { background: #4f46e5; color: #fff; align-self: flex-end; border-bottom-right-radius: 2px; }
        .bot-options { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
        .btn-option { 
            background: #fff; border: 1px solid #e5e7eb; color: #4b5563; 
            padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.75rem; 
            cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .btn-option:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }
        #bot-btn { width: 60px; height: 60px; border-radius: 50%; background: #4f46e5; color: #fff; border: none; font-size: 1.5rem; box-shadow: 0 4px 15px rgba(79,70,229,0.4); cursor: pointer; }
    </style>
</head>
<body>

<div class="d-flex">
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bg-primary rounded-3 p-1"><i class="bi bi-grid-fill text-white fs-4"></i></div>
                <h4 class="m-0 fw-bold tracking-tight">GESTOR PRO</h4>
            </div>
            <span class="text-secondary opacity-50 small fw-medium"><?= I18n::t('system_active') ?></span>
        </div>
        <div class="mt-2">
            <a href="<?= url('dashboard') ?>" class="nav-link <?= $isDashboard ? 'active' : '' ?>"><i class="bi bi-house-door"></i> <?= I18n::t('dashboard') ?></a>
            <a href="<?= url('proyectos') ?>" class="nav-link <?= $isProyectos ? 'active' : '' ?>"><i class="bi bi-layers"></i> <?= I18n::t('projects') ?></a>
            <a href="<?= url('tickets') ?>" class="nav-link <?= $isTickets ? 'active' : '' ?>"><i class="bi bi-shield-check"></i> <?= I18n::t('tickets') ?></a>
            <a href="<?= url('chat') ?>" class="nav-link <?= $isChat ? 'active' : '' ?>"><i class="bi bi-chat-left-dots"></i> <?= I18n::t('chat') ?></a>
            <a href="<?= url('users') ?>" class="nav-link <?= $isUsers ? 'active' : '' ?>"><i class="bi bi-person"></i> <?= I18n::t('users') ?></a>
        </div>
        <div class="position-absolute bottom-0 w-100 p-4 border-top border-secondary border-opacity-10">
            <a href="<?= url('logout') ?>" class="text-decoration-none text-danger small fw-bold"><i class="bi bi-box-arrow-left me-2"></i> <?= I18n::t('logout') ?></a>
        </div>
    </nav>

    <div id="content-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1 border">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" class="border-0 bg-transparent small outline-none" placeholder="<?= I18n::t('search') ?>">
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border rounded-pill px-3 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <i class="bi bi-translate text-primary"></i><span class="small fw-bold"><?= strtoupper(I18n::getLang()) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                        <li><a class="dropdown-item py-2 px-3 small d-flex justify-content-between" href="<?= url('lang?lang=es') ?>">Español <span>🇪🇸</span></a></li>
                        <li><a class="dropdown-item py-2 px-3 small d-flex justify-content-between" href="<?= url('lang?lang=en') ?>">English <span>🇺🇸</span></a></li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-bold text-dark small"><?= Session::get('user_name') ?></div>
                        <div class="text-muted" style="font-size: 0.65rem;"><?= strtoupper(Session::get('rol_nombre')) ?></div>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                        <?= strtoupper(substr(Session::get('user_name'), 0, 1)) ?>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 p-md-5">
            <?= $content ?>
        </main>
    </div>
</div>

<div id="bot-widget">
    <div id="bot-panel">
        <div class="bot-header">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-20 rounded-circle p-2"><i class="bi bi-robot fs-5"></i></div>
                <div><div class="small fw-bold"><?= I18n::t('bot_title') ?></div><div class="text-white-50" style="font-size: 0.6rem;">Online</div></div>
            </div>
            <button class="btn btn-sm text-white p-0 border-0" onclick="toggleBot()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div id="bot-content">
            <div class="msg bot"><?= I18n::t('bot_welcome') ?></div>
            <div class="bot-options">
                <button class="btn-option" onclick="handleUserInput('nav_crear_proyecto')">🚀 <?= I18n::getLang()==='en'?'New Project':'Nuevo Proyecto' ?></button>
                <button class="btn-option" onclick="handleUserInput('nav_crear_ticket')">🎫 <?= I18n::getLang()==='en'?'New Ticket':'Nuevo Ticket' ?></button>
                <button class="btn-option" onclick="handleUserInput('estado')">📍 <?= I18n::getLang()==='en'?'Status':'Estado' ?></button>
            </div>
        </div>
        <div class="bot-footer p-3 bg-white border-top">
            <div class="d-flex gap-2">
                <input type="text" id="bot-input" class="form-control border-0 bg-light rounded-pill px-4" placeholder="...">
                <button id="bot-send" class="btn btn-primary rounded-circle p-0" style="width: 40px; height: 40px;"><i class="bi bi-send-fill"></i></button>
            </div>
        </div>
    </div>
    <button id="bot-btn" onclick="toggleBot()"><i class="bi bi-robot"></i></button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const KNOWLEDGE_PATH = "<?= url('botchat/js/bot_conocimiento.json') ?>";
    const BASE_URL = "<?= url('') ?>";
    
    function toggleBot() { 
        const p = document.getElementById('bot-panel');
        p.style.display = p.style.display === 'flex' ? 'none' : 'flex';
        if (p.style.display === 'flex') document.getElementById('bot-input').focus();
    }

    // Funciones globales del bot integradas para máxima fiabilidad
    window.handleUserInput = function(text) {
        if (!text || text.trim() === "") return;
        if (typeof botProcess === 'function') {
            botProcess(text);
        } else {
            console.warn("Bot logic not loaded yet.");
        }
    }
</script>
<script src="<?= url('botchat/js/bot.js?v=' . time()) ?>"></script>
</body>
</html>