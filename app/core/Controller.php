<?php
/**
 * ClinixPro - Hospital Management System
 * Base Controller
 * 
 * All controllers extend this base class.
 */

namespace App\Core;

use App\Core\Session;
use App\Core\Security;

abstract class Controller
{
    protected array $data = [];
    protected array $middlewares = [];

    /**
     * Render a view
     * 
     * @param string $view View file path
     * @param array $data Data to pass to view
     */
    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: $view");
        }

        // Inject authentication data
        if (Session::has('user')) {
            $user = Session::get('user');
            if (!array_key_exists('authUser', $data)) $data['authUser'] = $user;
            if (!array_key_exists('user', $data)) $data['user'] = $user;
            if (!array_key_exists('sidebarUser', $data)) $data['sidebarUser'] = $user;
            if (!array_key_exists('displayName', $data)) {
                $data['displayName'] = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: ($user['full_name'] ?? $user['username'] ?? 'User');
            }
        }

        // Inject CSRF token
        if (!array_key_exists('csrf_token', $data)) {
            $data['csrf_token'] = Security::generateCsrfToken();
        }

        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        require $viewPath;
        $content = ob_get_clean();
        
        echo $content;
    }

    /**
     * Render JSON response
     * 
     * @param array $data Data to encode
     * @param int $statusCode HTTP status code
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a URL
     * 
     * @param string $url URL to redirect to
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Get POST data
     * 
     * @param string|null $key Specific key or null for all
     * @return mixed
     */
    protected function post(?string $key = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? null;
    }

    /**
     * Get GET data
     * 
     * @param string|null $key Specific key or null for all
     * @return mixed
     */
    protected function get(?string $key = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? null;
    }

    /**
     * Check if request is POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if request is GET
     */
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Get current user from session
     */
    protected function getCurrentUser(): ?array
    {
        return Session::get('user');
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Check if user has specific role
     */
    protected function hasRole(string $role): bool
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return false;
        }
        return isset($user['role_name']) && strtolower($user['role_name']) === strtolower($role);
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission(string $permission): bool
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return false;
        }
        
        // Admin has all permissions
        if (isset($user['role_name']) && strtolower($user['role_name']) === 'administrator') {
            return true;
        }
        
        // Check role permissions
        if (isset($user['permissions'])) {
            $permissions = json_decode($user['permissions'], true);
            if (is_array($permissions)) {
                if (isset($permissions['all']) && $permissions['all']) {
                    return true;
                }
                // Check specific permission
                foreach ($permissions as $key => $value) {
                    if (strpos($permission, $key) === 0) {
                        if (is_array($value) && in_array('read', $value)) {
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Require authentication
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            Session::set('redirect_after_login', $_SERVER['REQUEST_URI']);
            $this->redirect('/login');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to access this resource'
            ]);
            exit;
        }
    }

    /**
     * Require any of the specified roles
     *
     * @param array $roles Array of allowed role names (case-insensitive)
     */
    protected function requireAnyRole(array $roles): void
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $userRole = strtolower($user['role_name'] ?? '');
        
        $allowed = array_map('strtolower', $roles);
        
        if (!in_array($userRole, $allowed, true)) {
            $this->view('errors/403', [
                'message' => 'You do not have permission to access this resource'
            ]);
            exit;
        }
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $this->post('_token');
        return Security::validateCsrfToken($token);
    }

    /**
     * Sanitize input data
     */
    protected function sanitize(string $data): string
    {
        return Security::sanitizeInput($data);
    }

    /**
     * Add flash message
     */
    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }

    /**
     * Add middleware to controller
     */
    protected function middleware(string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Execute middlewares
     */
    protected function executeMiddlewares(): void
    {
        foreach ($this->middlewares as $middleware) {
            $middlewareClass = 'App\\Middlewares\\' . $middleware;
            if (class_exists($middlewareClass)) {
                $middlewareObject = new $middlewareClass();
                $middlewareObject->handle();
            }
        }
    }
}
