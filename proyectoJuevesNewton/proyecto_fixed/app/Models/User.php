<?php
// app/Models/User.php
namespace app\Models;

use app\Core\Database;
use PDO;

class User
{

    /**
     * Busca un usuario por su correo electrónico.
     * Implementa INNER JOIN con roles para obtener rol_nombre y utiliza Prepared Statements.
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

    public static function findById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ create() — agregar telefono al INSERT
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
        INSERT INTO usuarios (nombre, email, password, rol_id, empresa_id, telefono)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol_id'],
            $data['empresa_id'],
            $data['telefono'] ?? null   // ← null si no se envió
        ]);
    }

    // ✅ update() — agregar telefono al UPDATE
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

    /* public static function delete($id) {
         $db = Database::getInstance()->getConnection();
         $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
         return $stmt->execute([$id]);
     } */
}