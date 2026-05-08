<?php
// app/Core/Database.php
namespace app\Core;

use PDO;
use PDOException;
use Exception;
use app\Config\Config;

class Database {
    private static ?PDO $instancia = null;

    // Patrón Singleton: Constructor privado para evitar instanciación externa
    private function __construct() {}

    // Prevenir la clonación del objeto
    private function __clone() {}

    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("No se puede unserializar un Singleton.");
    }

    /**
     * Retorna la instancia única de PDO (Singleton)
     */
    public static function getInstancia(): PDO {
        return self::getInstance()->getConnection();
    }

    public static function getInstance(): self {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }

    public function getConnection(): PDO {
        if (self::$instancia === null) {
            $host = Config::get('DB_HOST', '127.0.0.1');
            $db   = Config::get('DB_NAME', 'gestor_pro');
            $user = Config::get('DB_USER', 'root');
            $pass = Config::get('DB_PASS', '');
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
            ];

            try {
                self::$instancia = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die("Error de conectividad con la base de datos.");
            }
        }

        return self::$instancia;
    }
}