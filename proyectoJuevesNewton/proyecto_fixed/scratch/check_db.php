<?php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Core/Database.php';

use app\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    echo "Users and Roles:\n";
    $stmt = $db->query("SELECT u.email, r.nombre as rol FROM usuarios u JOIN roles r ON u.rol_id = r.id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['email']}: {$row['rol']}\n";
    }
    
    echo "\nSession data (if I could see it, but I'm CLI):\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
