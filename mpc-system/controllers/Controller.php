<?php
// ============================================================
// Base Controller
// ============================================================
abstract class Controller {

    protected function view(string $view, array $data = [], ?string $layout = 'auto'): void {
        extract($data);

        ob_start();
        $viewFile = __DIR__ . "/../views/{$view}.php";
        if (!file_exists($viewFile)) {
            die("View not found: $view");
        }

        include $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $useLayout = ($layout === 'auto') ? 'main' : $layout;

        if (isset($data['_layout'])) {
            $useLayout = $data['_layout'];
        }

        $viewContents = file_get_contents($viewFile);
        if (preg_match('/\$layout\s*=\s*["\'](\w+)["\']/', $viewContents, $matches)) {
            $useLayout = $matches[1];
        }

        $layoutFile = __DIR__ . "/../views/layouts/{$useLayout}.php";
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void {
        header("Location: " . APP_URL . $url);
        exit;
    }

    protected function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('/auth/login');
        }
    }

    protected function requireRole(string ...$roles): void {
        $this->requireLogin();
        if (!in_array($_SESSION['user_role'] ?? '', $roles)) {
            $this->redirect('/');
        }
    }

    protected function setFlash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function getFlash(): ?array {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function post(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = null): mixed {
        return $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    protected function validate(array $data, array $rules): array {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';

            foreach (explode('|', $rule) as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
                } elseif (str_starts_with($r, 'min:') && strlen($value) < (int)substr($r, 4)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must be at least ' . substr($r, 4) . ' characters.';
                } elseif (str_starts_with($r, 'max:') && strlen($value) > (int)substr($r, 4)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must not exceed ' . substr($r, 4) . ' characters.';
                } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Invalid email address.';
                }
            }
        }

        return $errors;
    }
}