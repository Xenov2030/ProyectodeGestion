<?php
// app/Models/User.php
namespace app\Models;

use app\Core\Database;
use PDO;

class User
{

    /**
     * Busca un usuario por su correo electrónico.
     * Implementa INNER JOIN con roles para obtener rol_nombre, lo que evita tener que hacer consultas adicionales para mostrar el rol del usuario.
     */
    public static function findByEmail(string $email): ?array
    {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = :email 
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function all()
    {
        $db = Database::getInstance()->getConnection();
        return $db->query("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id")->fetchAll(PDO::FETCH_ASSOC);
    }

    // acá se agrega el método de paginación para aplicar una manera de visualizar el listado de empleados, sin necesidad de cargar tantos a la vez, o scrollear infinitamente:
    public static function paginados(int $porPagina, int $pagina, ?string $rol = null): array
    {
        $db = Database::getInstance()->getConnection();
        $offset = ($pagina - 1) * $porPagina;

        $sql = "SELECT u.*, r.nombre AS rol_nombre
            FROM usuarios u
            INNER JOIN roles r ON u.rol_id = r.id";

        // Si se elige un rol específico, agregamos la condición WHERE para filtrar por ese rol
        if ($rol) {
            $sql .= " WHERE r.nombre = :rol";
        }

        $sql .= " LIMIT :limite OFFSET :offset";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':limite', $porPagina, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);

        if ($rol) {
            $stmt->bindValue(':rol', $rol);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function contar(?string $rol = null): int
    {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) FROM usuarios u
            INNER JOIN roles r ON u.rol_id = r.id";

        if ($rol) {
            $sql .= " WHERE r.nombre = :rol";
        }

        $stmt = $db->prepare($sql);

        if ($rol) {
            $stmt->bindValue(':rol', $rol);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function findById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ create() — agregar telefono y estado al INSERT
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
        INSERT INTO usuarios (nombre, email, password, rol_id, empresa_id, telefono, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol_id'],
            $data['empresa_id'],
            $data['telefono'] ?: null,
            $data['estado']
        ]);
    }

    // le sumamos el teléfono al update, y el estado (activo/inactivo) para poder desactivar usuarios sin eliminarlos de la Base de datos (modo seguro de eliminación)
    public static function update($id, $data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
        UPDATE usuarios
        SET nombre = ?, email = ?, rol_id = ?, estado = ?, telefono = ?
        WHERE id = ?
    ");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['rol_id'],
            $data['estado'],
            $data['telefono'] ?? null,  // ← null si no se envió
            $id
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}