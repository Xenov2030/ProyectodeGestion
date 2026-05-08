<?php
// app/Controllers/TicketController.php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\Ticket;
use app\Models\Project;

class TicketController extends Controller {

    public function index() {
        $empresaId = Session::get('empresa_id');
        $userId    = Session::get('user_id');
        $rolNombre = Session::get('rol_nombre');

        // El modelo ahora acepta null para empresaId (Admin Global)
        $tickets = Ticket::getAllByEmpresaAndRole($empresaId, $userId, $rolNombre);

        $this->render('tickets/index', [
            'tickets' => $tickets
        ]);
    }

    public function create() {
        $empresaId = Session::get('empresa_id');
        $proyectos = Project::getAllByEmpresa($empresaId);

        $this->render('tickets/create', [
            'proyectos' => $proyectos
        ]);
    }

    public function store() {
        $this->validateCsrf();

        $empresaId = Session::get('empresa_id');

        $data = [
            'empresa_id'  => $empresaId ? (int)$empresaId : 1, // Fallback para admin global
            'usuario_id'  => (int)Session::get('user_id'),
            'proyecto_id' => !empty($_POST['proyecto_id']) ? (int)$_POST['proyecto_id'] : null,
            'titulo'      => htmlspecialchars(trim($_POST['titulo'] ?? '')),
            'descripcion' => htmlspecialchars(trim($_POST['descripcion'] ?? '')),
            'prioridad'   => $_POST['prioridad'] ?? 'media',
            'estado'      => 'abierto'
        ];

        if (empty($data['titulo']) || empty($data['descripcion'])) {
            Session::set('flash_error', 'El título y la descripción son obligatorios.');
            redirect('tickets/crear');
            exit;
        }

        $ticketId = Ticket::create($data);

        if ($ticketId) {
            Session::set('flash_success', 'Ticket de soporte abierto correctamente.');
            redirect('tickets');
        } else {
            Session::set('flash_error', 'Error interno al intentar crear el ticket.');
            redirect('tickets/crear');
        }
        exit;
    }
}
