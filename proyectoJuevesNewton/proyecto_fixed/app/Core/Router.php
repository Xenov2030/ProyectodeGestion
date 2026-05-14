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

        // Carga automática ya no es necesaria aquí (se maneja en index.php)
        
        // Ejecución de Middlewares
        foreach ($middlewares as $mwKey => $mwValue) {
            $mwName = is_string($mwKey) ? $mwKey : $mwValue;
            $rolesPermitidos = is_array($mwValue) ? $mwValue : [];
            $mwClass = "\\app\\Middlewares\\" . $mwName;

            if (class_exists($mwClass)) {
                $mwInstance = new $mwClass();
                $mwInstance->handle($rolesPermitidos);
            } else {
                http_response_code(500);
                die("Error 500: Middleware $mwClass no encontrado.");
            }
        }

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


    private function trimUri(string $uri): string {
        return trim($uri, '/') ?: '/';
    }
}