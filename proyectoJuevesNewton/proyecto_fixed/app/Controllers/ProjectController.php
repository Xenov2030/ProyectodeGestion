<?php
// app/Controllers/ProjectController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\Project;
use app\Models\AuditLog;

class ProjectController extends Controller {

    public function index() {
        $empresaId = Session::get('empresa_id');
        $userId    = Session::get('user_id');
        $rolNombre = Session::get('rol_nombre');
        
        $proyectos = Project::getAllByEmpresa($empresaId, (int)$userId, $rolNombre);

        $this->render('projects/index', [
            'proyectos' => $proyectos
        ]);
    }

    public function create() {
        $this->render('projects/create');
    }

    public function store() {
        $this->validateCsrf();

        $empresaId = Session::get('empresa_id');
        $rolNombre = Session::get('rol_nombre');
        $userId    = Session::get('user_id');
        
        $data = [
            'empresa_id'   => $empresaId ? (int)$empresaId : 1,
            'cliente_id'   => $rolNombre === 'cliente' ? (int)$userId : (int)($_POST['cliente_id'] ?? 0),
            'nombre'       => htmlspecialchars(trim($_POST['nombre'] ?? '')),
            'descripcion'  => htmlspecialchars(trim($_POST['descripcion'] ?? '')),
            'estado'       => 'pendiente', // Por defecto cuando solicita el cliente
            'prioridad'    => $_POST['prioridad'] ?? 'media',
            'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-d'),
            'fecha_fin'    => $_POST['fecha_fin'] ?? null
        ];

        if (empty($data['nombre'])) {
            Session::set('flash_error', 'El nombre es un campo obligatorio.');
            redirect('proyectos/crear');
            exit;
        }

        $projectId = Project::create($data);

        if ($projectId) {
            AuditLog::registrar(Session::get('user_id'), 'Crear', 'Proyectos', "Creó el proyecto: {$data['nombre']}");
            Session::set('flash_success', 'Proyecto creado exitosamente.');
            redirect('proyectos');
        } else {
            Session::set('flash_error', 'Ocurrió un error al crear el proyecto.');
            redirect('proyectos/crear');
        }
        exit;
    }
}