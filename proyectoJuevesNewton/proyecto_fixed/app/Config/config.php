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
     * Si APP_URL no está definida en .env, la detecta automáticamente.
     * Esto hace el proyecto portable en cualquier PC con WAMP/XAMPP.
     */
    public static function baseUrl($path = '') {
        $envUrl = self::get('APP_URL', '');

        if (!empty($envUrl)) {
            // Usar la URL del .env si está configurada
            $baseUrl = rtrim($envUrl, '/');
        } else {
            // Auto-detección: funciona con cualquier ruta en WAMP/XAMPP
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

            // SCRIPT_NAME sería algo como /proyectos/proyectoJuevesNewton/public/index.php
            $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
            // Quitamos /public/index.php (o /public) para obtener la raíz del proyecto
            $basePath = preg_replace('#/public(/index\.php)?$#', '', $scriptName);

            $baseUrl = $protocol . '://' . $host . $basePath;
        }

        return $baseUrl . '/' . ltrim($path, '/');
    }
}