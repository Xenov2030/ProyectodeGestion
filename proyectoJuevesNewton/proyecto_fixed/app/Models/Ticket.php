<?php
// app/Models/Ticket.php
namespace app\Models;

use app\Core\Database;
use PDO;

class Ticket {

    /**
     * Obtiene tickets filtrados.
     * Si $empresaId es null (Admin Global), ignora el filtro de empresa.
     */
    public static function getAllByEmpresaAndRole(?int $empresaId, int $userId, string $rolNombre): array {
        $db = Database::getInstancia();
        
        $sql = "SELECT t.*, p.nombre AS proyecto_nombre, u.nombre AS autor_nombre 
                FROM tickets t 
                LEFT JOIN proyectos p ON t.proyecto_id = p.id 
                INNER JOIN usuarios u ON t.usuario_id = u.id 
                WHERE 1=1";
        
        $params = [];

        // Filtro por empresa (solo si no es admin global)
        if ($empresaId !== null) {
            $sql .= " AND t.empresa_id = :empresa_id";
            $params['empresa_id'] = $empresaId;
        }

        // RBAC: El cliente solo ve sus propios tickets
        if ($rolNombre === 'cliente') {
            $sql .= " AND t.usuario_id = :user_id";
            $params['user_id'] = $userId;
        }

        $sql .= " ORDER BY t.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    /**
     * Inserta un nuevo ticket.
     */
    public static function create(array $data): int|bool {
        $db = Database::getInstancia();
        
        $sql = "INSERT INTO tickets (
                    empresa_id, usuario_id, proyecto_id, titulo, 
                    descripcion, prioridad, estado
                ) VALUES (
                    :empresa_id, :usuario_id, :proyecto_id, :titulo, 
                    :descripcion, :prioridad, :estado
                )";
        
        $stmt = $db->prepare($sql);
        
        $success = $stmt->execute([
            'empresa_id'  => $data['empresa_id'],
            'usuario_id'  => $data['usuario_id'],
            'proyecto_id' => $data['proyecto_id'] ?: null,
            'titulo'      => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'prioridad'   => $data['prioridad'] ?? 'media',
            'estado'      => $data['estado'] ?? 'abierto'
        ]);
        
        return $success ? (int)$db->lastInsertId() : false;
    }
}
