<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\ChatMessage;
use app\Models\User;

class ChatController extends Controller {
    
    public function index() {
        $usuarios = User::all();
        $this->render('chat/index', ['usuarios' => $usuarios]);
    }

    public function getMessages() {
        $contactoId = $_GET['contacto_id'] ?? 0;
        $userId = Session::get('user_id');
        $messages = ChatMessage::obtenerConversacion($userId, $contactoId);
        header('Content-Type: application/json');
        echo json_encode($messages);
        exit;
    }

    public function send() {
        $this->validateCsrf();
        $destinatario = $_POST['destinatario_id'] ?? 0;
        $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_SPECIAL_CHARS);
        $remitente = Session::get('user_id');

        header('Content-Type: application/json');
        if (!empty($mensaje) && $destinatario) {
            ChatMessage::enviar($remitente, $destinatario, $mensaje);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
        exit;
    }
}
