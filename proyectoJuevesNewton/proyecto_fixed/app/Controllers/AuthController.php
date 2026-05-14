<?php
// app/Controllers/AuthController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\User;
use app\Models\AuditLog;

class AuthController extends Controller {

    public function showLogin(): void {
        if (Session::isLoggedIn()) {
            redirect('dashboard');
        }
        $this->render('auth/login', [], 'auth');
    }

    public function login(): void {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->render('auth/login', [
                'error' => 'Por favor, completa ambos campos.'
            ], 'auth');
            return;
        }

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {

            // Guardar TODOS los campos necesarios en sesion
            // (antes solo se guardaba user_role, faltaba rol_nombre y empresa_id)
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['rol_id'];
            $_SESSION['rol_nombre'] = $user['rol_nombre']; // ← clave para RBAC
            $_SESSION['empresa_id'] = $user['empresa_id']; // ← clave para multi-tenant
            $_SESSION['last_regeneration'] = time();

            AuditLog::registrar($user['id'], 'Login', 'Acceso', 'Inicio de sesión exitoso');

            redirect('dashboard');

        } else {
            AuditLog::registrar(null, 'Login', 'Acceso', "Intento fallido con email: $email", 'fallido');
            $this->render('auth/login', [
                'error' => 'Correo o clave incorrectos.'
            ], 'auth');
        }
    }

    public function logout(): void {
        AuditLog::registrar(Session::get('user_id'), 'Logout', 'Acceso', 'Cierre de sesión');
        Session::destroy();
        redirect('login');
    }

    public function createDemoUser() {
        $db = \app\Core\Database::getInstancia();
        $pass = password_hash('123456', PASSWORD_DEFAULT);
        
        $rol = $db->query("SELECT id FROM roles WHERE nombre = 'cliente' LIMIT 1")->fetch();
        $rolId = $rol ? $rol['id'] : 5;

        // Limpiar para evitar duplicados
        $db->exec("DELETE FROM usuarios WHERE email = 'demo@cliente.com'");

        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, empresa_id, estado) 
                VALUES ('usuariodemo', 'demo@cliente.com', ?, ?, 1, 'activo')";
        
        $stmt = $db->prepare($sql);
        try {
            $success = $stmt->execute([$pass, $rolId]);
            if ($success) {
                echo "<div style='font-family:sans-serif; text-align:center; padding-top:50px;'>";
                echo "<h2 style='color:#10b981;'>✓ Usuario Demo Preparado</h2>";
                echo "<p>Usa estos datos exactos (con el arroba):</p>";
                echo "<div style='background:#f3f4f6; padding:20px; display:inline-block; border-radius:10px; text-align:left;'>";
                echo "<strong>Correo Electrónico:</strong> <code style='font-size:1.2rem;'>demo@cliente.com</code><br>";
                echo "<strong>Contraseña:</strong> <code style='font-size:1.2rem;'>123456</code>";
                echo "</div><br><br>";
                echo "<a href='".url('login')."' style='padding:12px 25px; background:#4f46e5; color:white; text-decoration:none; border-radius:8px; font-weight:bold;'>IR AL LOGIN</a>";
                echo "</div>";
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit;
    }

    public function forgotPassword(): void {
        $email = trim($_POST['email'] ?? '');
        // En un entorno real se enviaría un correo con un token
        redirect('login?msg=forgot_sent');
    }

    public function requestAccount(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (!empty($name) && !empty($email)) {
            $db = \app\Core\Database::getInstancia();
            // Asignamos la notificación al usuario ID 1 (Admin Global)
            $stmt = $db->prepare("INSERT INTO notificaciones (usuario_id, mensaje) VALUES (1, ?)");
            $mensaje = "Nueva solicitud de cuenta: $name ($email)";
            $stmt->execute([$mensaje]);
        }
        
        redirect('login?msg=request_sent');
    }
}
