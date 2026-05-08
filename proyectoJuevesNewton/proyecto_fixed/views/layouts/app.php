<?php
// views/layouts/app.php
use app\Core\Session;
use app\Core\I18n;
?>
<!DOCTYPE html>
<html lang="<?= I18n::getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Pro | <?= I18n::t('dashboard') ?></title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= url('public/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bg-primary rounded-3 p-1">
                    <i class="bi bi-grid-fill text-white fs-4"></i>
                </div>
                <h4 class="m-0 fw-bold tracking-tight">GESTOR PRO</h4>
            </div>
            <span class="text-secondary opacity-50 small fw-medium"><?= I18n::t('system_active') ?></span>
        </div>

        <div class="mt-2">
            <a href="<?= url('dashboard') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                <i class="bi bi-house-door"></i> <?= I18n::t('dashboard') ?>
            </a>
            <a href="<?= url('proyectos') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'proyectos') !== false ? 'active' : '' ?>">
                <i class="bi bi-layers"></i> <?= I18n::t('projects') ?>
            </a>
            <a href="<?= url('tickets') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'tickets') !== false ? 'active' : '' ?>">
                <i class="bi bi-shield-check"></i> <?= I18n::t('tickets') ?>
            </a>
            <a href="<?= url('chat') ?>" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'chat') !== false ? 'active' : '' ?>">
                <i class="bi bi-chat-left-dots"></i> <?= I18n::t('chat') ?>
            </a>
        </div>

        <div class="position-absolute bottom-0 w-100 p-4 border-top border-secondary border-opacity-10">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                <span class="small text-secondary fw-medium">v2.4.0 Online</span>
            </div>
            <a href="<?= url('logout') ?>" class="text-decoration-none text-danger small fw-bold">
                <i class="bi bi-box-arrow-left me-2"></i> <?= I18n::t('logout') ?>
            </a>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div id="content-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1 border">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" class="border-0 bg-transparent small outline-none" style="outline: none;" placeholder="<?= I18n::t('search') ?>">
            </div>

            <div class="d-flex align-items-center gap-4">
                <!-- Language Toggle -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border rounded-pill px-3 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <i class="bi bi-translate text-primary"></i>
                        <span class="small fw-bold"><?= strtoupper(I18n::getLang()) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                        <li><a class="dropdown-item py-2 px-3 small d-flex justify-content-between" href="<?= url('lang?lang=es') ?>">Español <span>🇪🇸</span></a></li>
                        <li><a class="dropdown-item py-2 px-3 small d-flex justify-content-between" href="<?= url('lang?lang=en') ?>">English <span>🇺🇸</span></a></li>
                    </ul>
                </div>

                <div class="vr text-secondary opacity-20 my-2"></div>

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

<!-- Floating IA Assistant Widget -->
<div id="bot-widget">
    <div id="bot-panel">
        <div class="bot-header">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-20 rounded-circle p-2">
                    <i class="bi bi-robot fs-5"></i>
                </div>
                <div>
                    <div class="small fw-bold"><?= I18n::t('bot_title') ?></div>
                    <div class="text-white-50" style="font-size: 0.6rem;">Online 24/7</div>
                </div>
            </div>
            <button class="btn btn-sm text-white p-0 border-0" onclick="toggleBot()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div id="bot-content" class="p-4 d-flex flex-column gap-3 overflow-auto">
            <div class="msg bot"><?= I18n::t('bot_welcome') ?></div>
        </div>
        <div class="bot-footer p-3 bg-white border-top">
            <div class="d-flex gap-2">
                <input type="text" id="bot-input" class="form-control border-0 bg-light rounded-pill px-4 py-2 small" placeholder="<?= I18n::t('bot_placeholder') ?>">
                <button id="bot-send" class="btn btn-primary rounded-circle p-0" style="width: 40px; height: 40px;">
                    <i class="bi bi-send-fill text-white"></i>
                </button>
            </div>
        </div>
    </div>
    <button id="bot-btn" onclick="toggleBot()">
        <i class="bi bi-robot"></i>
    </button>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleBot() { 
        const p = document.getElementById('bot-panel');
        const isOpen = p.style.display === 'flex';
        p.style.display = isOpen ? 'none' : 'flex';
        if (!isOpen) document.getElementById('bot-input').focus();
    }
    const KNOWLEDGE_PATH = "<?= url('botchat/js/bot_conocimiento.json') ?>";
</script>
<script src="<?= url('botchat/js/bot.js') ?>"></script>
</body>
</html>