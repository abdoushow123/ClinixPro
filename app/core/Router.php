<?php
/**
 * ClinixPro - Hospital Management System
 * Router
 * 
 * Handles URL routing to controllers and actions.
 */

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $params = [];

    /**
     * Add a route
     * 
     * @param string $route URL pattern
     * @param array $params Controller, action, and middleware
     */
    public function add(string $route, array $params = []): void
    {
        // Handle empty route (root URL)
        if ($route === '') {
            $route = '/^$/i';
        } else {
            // Convert route to regex
            $route = preg_replace('/\//', '\\/', $route);
            
            // Convert variables like {id} to named capture groups
            $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
            
            // Convert variables with custom regex like {id:\d+}
            $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
            
            // Add start and end delimiters
            $route = '/^' . $route . '$/i';
        }
        
        $this->routes[$route] = $params;
    }

    /**
     * Get all routes
     * 
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Match the URL to a route
     * 
     * @param string $url The URL to match
     * @return bool True if match found, false otherwise
     */
    public function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }
                
                $this->params = $params;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get the matched parameters
     * 
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Dispatch the route to the controller
     * 
     * @param string $url The URL to dispatch
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);
        
        // Remove leading slash for matching
        $url = ltrim($url, '/');
        
        if ($this->match($url)) {
            $params = $this->params;
            
            $controller = $params['controller'] ?? null;
            $action = $params['action'] ?? null;
            
            if ($controller && $action) {
                $controller = $this->convertToStudlyCaps($controller);
                $controller = $this->getNamespace() . $controller . 'Controller';
                
                if (class_exists($controller)) {
                    $controllerObject = new $controller();
                    $action = $this->convertToCamelCase($action);
                    
                    if (method_exists($controllerObject, $action)) {
                        // Execute middleware if defined
                        if (isset($params['middleware'])) {
                            $middleware = $params['middleware'];
                            if (is_array($middleware)) {
                                foreach ($middleware as $mw) {
                                    $middlewareClass = 'App\\Middlewares\\' . $mw;
                                    if (class_exists($middlewareClass)) {
                                        $middlewareObject = new $middlewareClass();
                                        $middlewareObject->handle();
                                    }
                                }
                            }
                        }
                        
                        // Extract route parameters (excluding controller, action, middleware, namespace)
                        $routeParams = [];
                        foreach ($params as $key => $value) {
                            if (!in_array($key, ['controller', 'action', 'middleware', 'namespace'])) {
                                $routeParams[] = $value;
                            }
                        }
                        
                        // Call the action with route parameters
                        call_user_func_array([$controllerObject, $action], $routeParams);
                    } else {
                        throw new \Exception("Method $action not found in controller $controller");
                    }
                } else {
                    throw new \Exception("Controller $controller not found");
                }
            } else {
                throw new \Exception("Controller or action not specified in route");
            }
        } else {
            throw new \Exception("No route matched for URL: $url");
        }
    }

    /**
     * Convert string to StudlyCaps
     */
    private function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert string to camelCase
     */
    private function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Get the namespace for the controller class
     */
    private function getNamespace(): string
    {
        $namespace = 'App\\Controllers\\';
        
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        
        return $namespace;
    }

    /**
     * Remove query string variables from URL
     */
    private function removeQueryStringVariables(string $url): string
    {
        $parts = explode('&', $url, 2);
        
        if (strpos($parts[0], '=') === false) {
            $url = $parts[0];
        } else {
            $url = '';
        }
        
        return $url;
    }
}
