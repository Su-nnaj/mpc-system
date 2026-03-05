<?php
class AuthController extends Controller {
    private UserModel $userModel;
    public function __construct() { $this->userModel = new UserModel(); }

    public function loginForm(): void {
        if ($this->isLoggedIn()) $this->redirect('/');
        $this->view('auth/login', ['flash' => $this->getFlash(), 'title' => 'Login'], null);
    }

    public function login(): void {
        $email    = trim($this->post('email', ''));
        $password = $this->post('password', '');
        $errors   = $this->validate(['email' => $email, 'password' => $password], ['email' => 'required|email', 'password' => 'required']);

        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors, 'email' => $email, 'title' => 'Login'], null);
            return;
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $this->view('auth/login', ['errors' => ['general' => 'Invalid email or password.'], 'email' => $email, 'title' => 'Login'], null);
            return;
        }

        if (!$user['is_active']) {
            $this->view('auth/login', ['errors' => ['general' => 'Account deactivated. Contact support.'], 'title' => 'Login'], null);
            return;
        }

        $sessionId = session_id();
        if ($sessionId) {
            $cartModel = new CartModel();
            $cartModel->mergeSessionCart($sessionId, $user['id']);
        }

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['email']     = $user['email'];

        $redirect = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);
        if ($redirect) { header("Location: $redirect"); exit; }

        match($user['role']) {
            'admin'             => $this->redirect('/admin/dashboard'),
            'sales_staff'       => $this->redirect('/staff/dashboard'),
            'inventory_manager' => $this->redirect('/inventory/dashboard'),
            default             => $this->redirect('/'),
        };
    }

    public function registerForm(): void {
        if ($this->isLoggedIn()) $this->redirect('/');
        $this->view('auth/register', ['title' => 'Create Account'], null);
    }

    public function register(): void {
        $data = [
            'full_name' => trim($this->post('full_name', '')),
            'username'  => trim($this->post('username', '')),
            'email'     => trim($this->post('email', '')),
            'phone'     => trim($this->post('phone', '')),
            'password'  => $this->post('password', ''),
        ];
        $confirm = $this->post('confirm_password', '');
        $errors = $this->validate($data, ['full_name' => 'required|min:2', 'username' => 'required|min:3', 'email' => 'required|email', 'password' => 'required|min:6']);
        if ($data['password'] !== $confirm) $errors['confirm_password'] = 'Passwords do not match.';
        if ($this->userModel->findByEmail($data['email'])) $errors['email'] = 'Email already registered.';
        if ($this->userModel->findByUsername($data['username'])) $errors['username'] = 'Username already taken.';

        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors, 'old' => $data, 'title' => 'Create Account'], null);
            return;
        }

        $this->userModel->register($data);
        $this->setFlash('success', 'Account created! Please log in.');
        $this->redirect('/auth/login');
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('/');
    }
}
