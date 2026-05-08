<?php
// app/Controllers/ProjectController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\Project;

class ProjectController extends Controller {

    public function index() {
        // Obtenemos el empresa_id. Puede ser null para Administrador Global.
        $empresaId = Session::get('empresa_id');
        
        // El modelo ahora acepta null y devuelve todos los registros.
        $proyectos = Project::getAllByEmpresa($empresaId);

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
        
        // Si es Admin Global y no especificó empresa_id, tomamos 1 por defecto (o manejarlo)
        // Pero el Administrador Global usualmente gestiona todo el SaaS.
        $data = [
            'empresa_id'   => $empresaId ? (int)$empresaId : 1, // Fallback a empresa ID 1 para admin global si crea
            'cliente_id'   => (int)($_POST['cliente_id'] ?? 0),
            'nombre'       => htmlspecialchars(trim($_POST['nombre'] ?? '')),
            'descripcion'  => htmlspecialchars(trim($_POST['descripcion'] ?? '')),
            'estado'       => $_POST['estado'] ?? 'pendiente',
            'prioridad'    => $_POST['prioridad'] ?? 'media',
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin'    => $_POST['fecha_fin'] ?? null
        ];

        if (empty($data['nombre']) || empty($data['cliente_id'])) {
            Session::set('flash_error', 'El nombre y el cliente son campos obligatorios.');
            redirect('proyectos/crear');
            exit;
        }

        $projectId = Project::create($data);

        if ($projectId) {
            Session::set('flash_success', 'Proyecto creado exitosamente.');
            redirect('proyectos');
        } else {
            Session::set('flash_error', 'Ocurrió un error al crear el proyecto.');
            redirect('proyectos/crear');
        }
        exit;
    }
}