<?php
// Controlador para gestionar los tickets de soporte y trámites
require_once __DIR__ . '/../models/TicketModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/MensajeriaController.php';

class TicketController {
    private $modelo;
    private $usuarioModel;

    public function __construct() {
        $this->modelo = new TicketModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function listar() {
        if (in_array($_SESSION['rol'], ['cliente', 'prospecto'])) {
            $tickets = $this->modelo->obtenerPorCliente($_SESSION['usuario_id']);
        } else {
            $tickets = $this->modelo->obtenerTickets();
        }

        ob_start();
        include __DIR__ . '/../views/tickets_view.php';
        $content = ob_get_clean();
        $title = 'Tickets - Gestor Profesional';
        include __DIR__ . '/../views/layout.php';
    }

    public function guardar($titulo, $descripcion, $usuario_id) {
        if (!empty($_FILES['adjunto']['name'])) {
            $this->guardarTicket(['titulo' => $titulo, 'descripcion' => $descripcion, 'usuario_id' => $usuario_id], $_FILES['adjunto']);
        } else {
            $this->modelo->crearTicket($titulo, $descripcion, $usuario_id, null);
        }
        header('Location: index.php?action=listar');
        exit();
    }

    public function guardarTicket($datos, $archivo) {
        // Validar archivo PDF
        if (!empty($archivo['name'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $archivo['tmp_name']);
            finfo_close($finfo);

            if ($mimeType !== 'application/pdf') {
                // Error: no es PDF
                header('Location: index.php?action=listar&error=archivo_invalido');
                exit();
            }

            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            if ($extension !== 'pdf') {
                header('Location: index.php?action=listar&error=archivo_invalido');
                exit();
            }
        }

        $ruta_destino = __DIR__ . '/../uploads/';
        if (!is_dir($ruta_destino)) {
            mkdir($ruta_destino, 0755, true);
        }

        $nombre_archivo = time() . '_' . basename($archivo['name']);
        $ruta_final = $ruta_destino . $nombre_archivo;
        $ruta_db = 'uploads/' . $nombre_archivo;

        if (move_uploaded_file($archivo['tmp_name'], $ruta_final)) {
            $this->modelo->crearTicket($datos['titulo'], $datos['descripcion'] ?? '', $datos['usuario_id'] ?? 0, $ruta_db);
        }
    }

    public function profesionalTramites() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'profesional') {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $tickets = $this->modelo->obtenerTicketsConCliente();
        ob_start();
        include __DIR__ . '/../views/profesional_tramites.php';
        $content = ob_get_clean();
        $title = 'Trámites Profesionales - Gestor Profesional';
        include __DIR__ . '/../views/layout.php';
    }

    public function profesionalTicketEditar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'profesional') {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=profesional_tramites');
            exit();
        }

        $ticket = $this->modelo->obtenerTicketPorId($id);
        if (!$ticket) {
            header('Location: index.php?action=profesional_tramites');
            exit();
        }

        $cliente = $this->usuarioModel->obtenerPorId($ticket['usuario_id']);
        ob_start();
        include __DIR__ . '/../views/profesional_ticket_editar.php';
        $content = ob_get_clean();
        $title = 'Actualizar Trámite - Gestor Profesional';
        include __DIR__ . '/../views/layout.php';
    }

    public function profesionalActualizarEstado() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'profesional') {
            header('Location: index.php?action=dashboard');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=profesional_tramites');
            exit();
        }

        $id_ticket = $_POST['id_ticket'] ?? null;
        $estado = $_POST['estado'] ?? 'abierto';
        $mensaje = trim($_POST['mensaje'] ?? '');

        if (!$id_ticket) {
            header('Location: index.php?action=profesional_tramites');
            exit();
        }

        $ticket = $this->modelo->obtenerTicketPorId($id_ticket);
        if (!$ticket) {
            header('Location: index.php?action=profesional_tramites');
            exit();
        }

        $this->modelo->actualizarEstado($id_ticket, $estado);

        $cliente = $this->usuarioModel->obtenerPorId($ticket['usuario_id']);
        $texto = "Su trámite #{$ticket['id_ticket']} ha sido actualizado al estado '{$estado}'.";
        if ($mensaje) {
            $texto .= " Observaciones: {$mensaje}";
        }

        $whatsappUrl = MensajeriaController::enviarAlerta($ticket['usuario_id'], $texto, $cliente['email'] ?? '', $cliente['telefono'] ?? null);
        if ($whatsappUrl) {
            $_SESSION['ultimo_whatsapp'] = $whatsappUrl;
        }

        header('Location: index.php?action=profesional_tramites&msg=actualizado');
        exit();
    }
}

