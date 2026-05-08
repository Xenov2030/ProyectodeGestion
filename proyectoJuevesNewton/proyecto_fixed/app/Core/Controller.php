<?php
// app/Core/Controller.php
namespace app\Core;

class Controller {

    public function __construct() {
        Session::init();
        $this->generateCsrfToken();
    }

    /**
     * Genera un token CSRF si no existe
     */
    protected function generateCsrfToken() {
        if (!Session::get('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
    }

    /**
     * Verifica si el token recibido es válido
     */
    protected function validateCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if ($token !== Session::get('csrf_token')) {
            die("Error 403: CSRF Token mismatch. Petición bloqueada por seguridad.");
        }
    }

    /**
     * Helper estático para inyectar el campo oculto en las vistas
     */
    public static function csrf_field() {
        $token = Session::get('csrf_token');
        return "<input type='hidden' name='csrf_token' value='$token'>";
    }
    
    // Método para renderizar vistas
    protected function render($view, $data = [], $layout = 'app') {
        extract($data); 
        ob_start();
        require VIEWS_PATH . '/' . $view . '.php';
        $content = ob_get_clean();
        require VIEWS_PATH . '/layouts/' . $layout . '.php';
    }
}