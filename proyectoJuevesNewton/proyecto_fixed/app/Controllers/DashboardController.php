<?php
// app/Controllers/DashboardController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;

class DashboardController extends Controller {

    /**
     * Punto de entrada al panel de control.
     * Corregido para usar la vista dashboard/index para todos los roles.
     */
    public function index(): void {
        $rol = Session::get('rol_nombre');
        $userName = Session::get('user_name');
        $empresaId = Session::get('empresa_id');

        $data = [
            'user_name' => $userName,
            'rol_nombre' => $rol,
            'empresa_id' => $empresaId
        ];

        // Validar que el usuario tenga un rol asignado
        if (!$rol) {
            redirect('logout');
            exit;
        }

        // Renderizar la vista principal del dashboard
        $this->render('dashboard/index', $data);
    }

    public function setLanguage() {
        $lang = $_GET['lang'] ?? 'es';
        \app\Core\I18n::setLang($lang);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('dashboard')));
        exit;
    }
}