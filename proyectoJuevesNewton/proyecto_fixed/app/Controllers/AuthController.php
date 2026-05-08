<?php
// app/Controllers/AuthController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\User;

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
                'error' => 'Por favor completa ambos campos.'
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

            redirect('dashboard');

        } else {
            $this->render('auth/login', [
                'error' => 'Correo o contrasena incorrectos.'
            ], 'auth');
        }
    }

    public function logout(): void {
        Session::destroy();
        redirect('login');
    }
}
