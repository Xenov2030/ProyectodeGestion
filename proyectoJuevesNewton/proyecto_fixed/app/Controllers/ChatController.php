<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\ChatMessage;
use app\Models\User;

class ChatController extends Controller {
    
    public function __construct() {
        // Asegurar que la tabla de chat existe
        try {
            $db = \app\Core\Database::getInstance()->getConnection();
            $db->exec("CREATE TABLE IF NOT EXISTS mensajes_chat (
                id INT AUTO_INCREMENT PRIMARY KEY,
                remitente_id INT NOT NULL,
                destinatario_id INT NOT NULL,
                mensaje TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                visto TINYINT(1) DEFAULT 0
            )");
        } catch (\Exception $e) {
            // Silencioso o log
        }
    }

    public function index() {
        $usuarios = User::all();
        $this->render('chat/index', ['usuarios' => $usuarios]);
    }

    public function getMessages() {
        try {
            $contactoId = $_GET['contacto_id'] ?? 0;
            $userId = Session::get('user_id');
            
            if (!$userId) {
                throw new \Exception("Usuario no autenticado en la sesión.");
            }

            $messages = ChatMessage::obtenerConversacion($userId, $contactoId);
            header('Content-Type: application/json');
            echo json_encode($messages);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
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
