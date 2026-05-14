<?php
// views/layouts/auth.php
use app\Core\I18n;
?>
<!DOCTYPE html>
<html lang="<?= I18n::getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor System | Authentication</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            position: relative;
        }
        .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.2s;
        }
        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <?= $content ?>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>