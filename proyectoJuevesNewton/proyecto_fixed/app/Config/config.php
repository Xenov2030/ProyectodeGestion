<?php
// app/Config/Config.php

namespace app\Config;

class Config {
    /**
     * Obtiene una variable de entorno o un valor por defecto.
     */
    public static function get($key, $default = null) {
        $value = getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        return $value;
    }

    /**
     * Configuración de la Base de Datos
     */
    public static function db() {
        return [
            'host' => self::get('DB_HOST', 'localhost'),
            'name' => self::get('DB_NAME', 'gestor_pro'),
            'user' => self::get('DB_USER', 'root'),
            'pass' => self::get('DB_PASS', ''),
            'port' => self::get('DB_PORT', '3306'),
        ];
    }

    /**
     * Obtiene la URL base de la aplicación.
     */
    public static function baseUrl($path = '') {
        $baseUrl = self::get('APP_URL', 'http://localhost');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}