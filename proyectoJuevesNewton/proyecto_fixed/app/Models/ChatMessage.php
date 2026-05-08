<?php
namespace app\Models;

use app\Core\Database;
use PDO;

class ChatMessage {
    public static function enviar($remitente, $destinatario, $mensaje) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO mensajes_chat (remitente_id, destinatario_id, mensaje) VALUES (?, ?, ?)");
        return $stmt->execute([$remitente, $destinatario, $mensaje]);
    }

    public static function obtenerConversacion($id1, $id2) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT * FROM mensajes_chat 
            WHERE (remitente_id = :u AND destinatario_id = :c) 
               OR (remitente_id = :c AND destinatario_id = :u)
            ORDER BY created_at ASC
        ");
        $stmt->execute(['u' => $id1, 'c' => $id2]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
