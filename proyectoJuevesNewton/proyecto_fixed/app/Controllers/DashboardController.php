<?php
// app/Controllers/DashboardController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;

class DashboardController extends Controller {

    /**
     * Punto de entrada al panel de control.
     * Corregido para usar la vista dashboard/index para todos los roles.
     * Se le añade una vista de gráficos
     */
public function index(): void {
    $rol       = Session::get('rol_nombre');
    $userName  = Session::get('user_name');
    $empresaId = Session::get('empresa_id');

    if (!$rol) {
        redirect('logout');
        exit;
    }

    $db = \app\Core\Database::getInstancia();

    $totalUsuarios   = $db->query("SELECT COUNT(*) FROM usuarios WHERE estado = 'activo'")->fetchColumn();
    $totalProyectos  = $db->query("SELECT COUNT(*) FROM proyectos")->fetchColumn();
    $ticketsAbiertos = $db->query("SELECT COUNT(*) FROM tickets WHERE estado = 'abierto'")->fetchColumn();
    $ticketsCerrados = $db->query("SELECT COUNT(*) FROM tickets WHERE estado = 'cerrado'")->fetchColumn();

    $usuariosPorRol = $db->query("
        SELECT r.nombre, COUNT(u.id) as total
        FROM roles r
        LEFT JOIN usuarios u ON u.rol_id = r.id
        GROUP BY r.nombre
    ")->fetchAll(\PDO::FETCH_ASSOC);

    $this->render('dashboard/index', [
        'user_name'       => $userName,
        'rol_nombre'      => $rol,
        'empresa_id'      => $empresaId,
        'totalUsuarios'   => $totalUsuarios,
        'totalProyectos'  => $totalProyectos,
        'ticketsAbiertos' => $ticketsAbiertos,
        'ticketsCerrados' => $ticketsCerrados,
        'usuariosPorRol'  => $usuariosPorRol,
    ]);
}
    public function setLanguage() {
        $lang = $_GET['lang'] ?? 'es';
        \app\Core\I18n::setLang($lang);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('dashboard')));
        exit;
    }
}