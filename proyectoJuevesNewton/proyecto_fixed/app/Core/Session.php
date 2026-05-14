<?php
// app/Core/Session.php
namespace app\Core;

class Session {
    
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function checkRole($allowedRoles) {
        if (!self::isLoggedIn()) {
            header('Location: ' . url('login'));
            exit();
        }

        $userRole = self::get('rol_nombre');
        if (!in_array($userRole, (array)$allowedRoles)) {
            // Redirect to dashboard with error or unauthorized page
            header('Location: ' . url('dashboard?error=unauthorized'));
            exit();
        }
    }

    public static function redirectByRole() {
        header('Location: ' . url('dashboard'));
        exit();
    }

}
