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
            WHERE (remitente_id = :u1 AND destinatario_id = :c1) 
               OR (remitente_id = :c2 AND destinatario_id = :u2)
            ORDER BY created_at ASC
        ");
        $stmt->execute([
            'u1' => $id1, 'c1' => $id2,
            'c2' => $id1, 'u2' => $id2
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
