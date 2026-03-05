<?php
// ============================================================
// MPC Trading — Entry Point & Router
// ============================================================
define('ROOT', __DIR__);

// Start session
session_start();

// Load config and core classes
require_once ROOT . '/config/config.php';
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Model.php';
require_once ROOT . '/models/UserModel.php';
require_once ROOT . '/models/ProductModel.php';
require_once ROOT . '/models/OrderModel.php';
require_once ROOT . '/models/RecommendationModel.php';
require_once ROOT . '/controllers/Controller.php';
require_once ROOT . '/controllers/MainControllers.php';
require_once ROOT . '/controllers/AuthController.php';
require_once ROOT . '/controllers/AdminController.php';
require_once ROOT . '/controllers/ContactController.php';
// Get request path
$requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName    = dirname($_SERVER['SCRIPT_NAME']);
$path          = '/' . ltrim(str_replace($scriptName, '', $requestUri), '/');
$path          = rtrim($path, '/') ?: '/';
$method        = $_SERVER['REQUEST_METHOD'];
$segments      = array_values(array_filter(explode('/', $path)));

// ============================================================
// ROUTES
// ============================================================
function route(string $method, string $path, callable $handler): void {
    global $_currentMethod, $_currentPath;
    if ($_currentMethod === $method && $_currentPath === $path) {
        $handler();
        exit;
    }
}

$_currentMethod = $method;
$_currentPath   = $path;

// Helper to match dynamic segments like /products/show/{slug}
function matchRoute(string $method, string $pattern, callable $handler): bool {
    global $_currentMethod, $_currentPath;
    if ($_currentMethod !== $method) return false;
    $patternParts = array_values(array_filter(explode('/', $pattern)));
    $pathParts    = array_values(array_filter(explode('/', $_currentPath)));
    if (count($patternParts) !== count($pathParts)) return false;
    $params = [];
    foreach ($patternParts as $i => $part) {
        if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
            $params[] = $pathParts[$i];
        } elseif ($part !== $pathParts[$i]) {
            return false;
        }
    }
    $handler(...$params);
    return true;
}

// Home
if (matchRoute('GET', '/', fn() => (new HomeController())->index())) exit;

// Auth
if (matchRoute('GET',  '/auth/login',    fn() => (new AuthController())->loginForm())) exit;
if (matchRoute('POST', '/auth/login',    fn() => (new AuthController())->login())) exit;
if (matchRoute('GET',  '/auth/register', fn() => (new AuthController())->registerForm())) exit;
if (matchRoute('POST', '/auth/register', fn() => (new AuthController())->register())) exit;
if (matchRoute('GET',  '/auth/logout',   fn() => (new AuthController())->logout())) exit;

// Products
if (matchRoute('GET', '/products',           fn() => (new ProductController())->index())) exit;
if (matchRoute('GET', '/products/{slug}',    fn($s) => (new ProductController())->show($s))) exit;

// Cart
if (matchRoute('GET',  '/cart',         fn() => (new CartController())->index())) exit;
if (matchRoute('POST', '/cart/add',     fn() => (new CartController())->add())) exit;
if (matchRoute('POST', '/cart/update',  fn() => (new CartController())->update())) exit;
if (matchRoute('POST', '/cart/remove',  fn() => (new CartController())->remove())) exit;
if (matchRoute('GET',  '/cart/count',   fn() => (new CartController())->count())) exit;

// Orders / Checkout
if (matchRoute('GET',  '/checkout',              fn() => (new OrderController())->checkout())) exit;
if (matchRoute('POST', '/checkout/place',        fn() => (new OrderController())->placeOrder())) exit;
if (matchRoute('GET',  '/orders',                fn() => (new OrderController())->myOrders())) exit;
if (matchRoute('GET',  '/orders/{id}',           fn($id) => (new OrderController())->detail((int)$id))) exit;
if (matchRoute('GET',  '/orders/success/{num}',  fn($n) => (new OrderController())->success($n))) exit;

// Recommendation Engine
if (matchRoute('GET',  '/recommend',            fn() => (new RecommendationController())->index())) exit;
if (matchRoute('POST', '/recommend/generate',   fn() => (new RecommendationController())->generate())) exit;
if (matchRoute('POST', '/recommend/add-to-cart',fn() => (new RecommendationController())->addBuildToCart())) exit;

// Admin
if (matchRoute('GET',  '/admin/dashboard',      fn() => (new AdminController())->dashboard())) exit;
if (matchRoute('GET',  '/admin/products',       fn() => (new AdminController())->products())) exit;
if (matchRoute('POST', '/admin/products/save',  fn() => (new AdminController())->saveProduct())) exit;
if (matchRoute('POST', '/admin/products/delete',fn() => (new AdminController())->deleteProduct())) exit;
if (matchRoute('GET',  '/admin/orders',         fn() => (new AdminController())->orders())) exit;
if (matchRoute('POST', '/admin/orders/update',  fn() => (new AdminController())->updateOrder())) exit;
if (matchRoute('GET',  '/admin/users',          fn() => (new AdminController())->users())) exit;
if (matchRoute('POST', '/admin/users/toggle',   fn() => (new AdminController())->toggleUser())) exit;
if (matchRoute('GET',  '/admin/reports',        fn() => (new AdminController())->reports())) exit;

// Staff
if (matchRoute('GET',  '/staff/dashboard',      fn() => (new StaffController())->dashboard())) exit;
if (matchRoute('GET',  '/staff/orders',         fn() => (new StaffController())->orders())) exit;
if (matchRoute('POST', '/staff/orders/update',  fn() => (new StaffController())->updateOrder())) exit;

// Inventory
if (matchRoute('GET',  '/inventory/dashboard',      fn() => (new InventoryController())->dashboard())) exit;
if (matchRoute('GET',  '/inventory/products',       fn() => (new InventoryController())->products())) exit;
if (matchRoute('POST', '/inventory/stock/update',   fn() => (new InventoryController())->updateStock())) exit;

// About & Contact
if (matchRoute('GET',  '/about',        fn() => (new AboutController())->index())) exit;
if (matchRoute('GET',  '/contact',      fn() => (new ContactController())->index())) exit;
if (matchRoute('POST', '/contact/send', fn() => (new ContactController())->send())) exit;

// 404
http_response_code(404);
$content = '<div style="text-align:center;padding:100px;font-family:sans-serif;"><h1>404 — Page Not Found</h1><a href="' . APP_URL . '">← Go Home</a></div>';
include ROOT . '/views/layouts/main.php';
