<?php
// app/Middlewares/AuthMiddleware.php
namespace app\Middlewares;

use app\Core\Session;

class AuthMiddleware {
    
    /**
     * Valida la sesión activa, regenera el ID por seguridad y evalúa permisos RBAC.
     * @param array $allowedRoles Roles permitidos. Si está vacío, solo exige login.
     */
    public function handle(array $allowedRoles = []) {
        Session::init();
        
        // 1. Verificación Estricta de Autenticación
        if (!Session::isLoggedIn()) {
            redirect('login?error=auth_required');
            exit;
        }

        // 2. Prevención de Session Fixation y Secuestro (Regeneración de ID)
        if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration'] > 1800)) {
            // Regenerar ID cada 30 minutos preservando la data de la sesión
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }

        // 3. Verificación Estricta de Autorización (RBAC)
        if (!empty($allowedRoles)) {
            $userRole = Session::get('rol_nombre'); // Roles: admin, directivo, administrativo, empleado, cliente
            
            if (!in_array($userRole, $allowedRoles, true)) {
                http_response_code(403);
                die("Error 403: Acceso Denegado. Tu rol carece de los privilegios requeridos.");
            }
        }
    }
}
