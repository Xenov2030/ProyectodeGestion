<?php
// app/Models/Project.php
namespace app\Models;

use app\Core\Database;
use PDO;

class Project {

    /**
     * Obtiene todos los proyectos.
     * Si se pasa un $empresaId (int), filtra por esa empresa.
     * Si es null (Admin Global), obtiene todos los proyectos del sistema.
     */
    public static function getAllByEmpresa(?int $empresaId = null): array {
        $db = Database::getInstancia();
        
        $sql = "SELECT p.*, u.nombre AS cliente_nombre 
                FROM proyectos p 
                INNER JOIN usuarios u ON p.cliente_id = u.id";
        
        $params = [];
        if ($empresaId !== null) {
            $sql .= " WHERE p.empresa_id = :empresa_id";
            $params['empresa_id'] = $empresaId;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    /**
     * Crea un nuevo registro de proyecto.
     */
    public static function create(array $data): int|bool {
        $db = Database::getInstancia();
        
        $sql = "INSERT INTO proyectos (
                    empresa_id, cliente_id, nombre, descripcion, 
                    estado, prioridad, fecha_inicio, fecha_fin
                ) VALUES (
                    :empresa_id, :cliente_id, :nombre, :descripcion, 
                    :estado, :prioridad, :fecha_inicio, :fecha_fin
                )";
        
        $stmt = $db->prepare($sql);
        
        $success = $stmt->execute([
            'empresa_id'   => $data['empresa_id'],
            'cliente_id'   => $data['cliente_id'],
            'nombre'       => $data['nombre'],
            'descripcion'  => $data['descripcion'] ?? null,
            'estado'       => $data['estado'] ?? 'pendiente',
            'prioridad'    => $data['prioridad'] ?? 'media',
            'fecha_inicio' => $data['fecha_inicio'] ?? null,
            'fecha_fin'    => $data['fecha_fin'] ?? null
        ]);
        
        return $success ? (int)$db->lastInsertId() : false;
    }
}
