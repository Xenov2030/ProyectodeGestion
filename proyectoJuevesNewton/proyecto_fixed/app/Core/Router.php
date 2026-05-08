<?php
// app/Core/Router.php
namespace app\Core;

class Router {
    private array $routes = [];

    public function get(string $uri, array $action, array $middlewares = []): void {
        $this->routes['GET'][$this->trimUri($uri)] = [
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function post(string $uri, array $action, array $middlewares = []): void {
        $this->routes['POST'][$this->trimUri($uri)] = [
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(string $uri, string $method): void {
        $uri = $this->trimUri($uri);
        $method = strtoupper($method);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            die("Error 404: La página solicitada no existe ($uri)");
        }

        $route = $this->routes[$method][$uri];
        $controllerClass = $route['action'][0];
        $methodName = $route['action'][1];
        $middlewares = $route['middlewares'];

        // Carga automática de controladores
        $this->autoload($controllerClass);

        // Ejecución de Middlewares
        foreach ($middlewares as $mwKey => $mwValue) {
            $mwName = is_string($mwKey) ? $mwKey : $mwValue;
            $rolesPermitidos = is_array($mwValue) ? $mwValue : [];
            $mwClass = "\\app\\Middlewares\\" . $mwName;

            $this->autoload($mwClass);

            if (class_exists($mwClass)) {
                $mwInstance = new $mwClass();
                $mwInstance->handle($rolesPermitidos);
            } else {
                http_response_code(500);
                die("Error 500: Middleware $mwClass no encontrado.");
            }
        }

        // Parche preventivo: Carga manual de modelos necesarios antes de instanciar controladores
        $this->loadModels();

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
                return;
            }
        }

        http_response_code(500);
        die("Error 500: No se pudo cargar el controlador $controllerClass.");
    }

    /**
     * Carga todos los modelos disponibles en la carpeta app/Models.
     */
    private function loadModels(): void {
        $modelsDir = __DIR__ . '/../Models/';
        if (is_dir($modelsDir)) {
            foreach (glob($modelsDir . "*.php") as $filename) {
                require_once $filename;
            }
        }
    }

    /**
     * Sistema de autodescubrimiento de archivos basado en Namespace
     */
    private function autoload(string $fullClassName): void {
        if (class_exists($fullClassName)) return;

        $basePath = __DIR__ . '/../';
        
        // Limpiar el prefijo app\ si existe
        $cleanName = str_replace('app\\', '', $fullClassName);
        $relativepath = str_replace('\\', '/', $cleanName);
        
        $filePath = $basePath . $relativepath . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }

    private function trimUri(string $uri): string {
        return trim($uri, '/') ?: '/';
    }
}