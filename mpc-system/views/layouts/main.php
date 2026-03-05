<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?= APP_URL ?>">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🖥</text></svg>">
    <?php if (isset($extraHead)) echo $extraHead; ?>
</head>
<body>

<!-- ============================================================ NAVBAR -->
<nav class="navbar">
    <div class="navbar-inner">
        <a href="<?= APP_URL ?>/" class="navbar-brand">
            <div class="logo-icon">🖥</div>
            MPC<span>Trading</span>
        </a>

        <div class="navbar-nav">
            <a href="<?= APP_URL ?>/" class="nav-link <?= (($_SERVER['REQUEST_URI'] ?? '') === '/' || (parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) === parse_url(APP_URL.'/',PHP_URL_PATH))) ? 'active' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/products" class="nav-link <?= str_contains($_SERVER['REQUEST_URI']??'', '/products') ? 'active' : '' ?>">Shop</a>
            <a href="<?= APP_URL ?>/recommend" class="nav-link <?= str_contains($_SERVER['REQUEST_URI']??'', '/recommend') ? 'active' : '' ?>">🤖 PC Builder</a>
            <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin','sales_staff','inventory_manager'])): ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?= APP_URL ?>/admin/dashboard" class="nav-link">Admin</a>
                <?php elseif ($_SESSION['user_role'] === 'sales_staff'): ?>
                    <a href="<?= APP_URL ?>/staff/dashboard" class="nav-link">Staff</a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/inventory/dashboard" class="nav-link">Inventory</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="navbar-end">
            <a href="<?= APP_URL ?>/cart" class="cart-btn" title="Shopping Cart">
                🛒
                <span class="cart-badge" id="cart-badge" style="display:none">0</span>
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-menu">
                    <button class="user-btn" data-toggle="dropdown" data-target="user-dropdown">
                        👤 <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>
                        <span style="font-size:10px;color:var(--text-muted)">▼</span>
                    </button>
                    <div class="dropdown" id="user-dropdown">
                        <a href="<?= APP_URL ?>/orders">📦 My Orders</a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= APP_URL ?>/auth/logout">🚪 Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= APP_URL ?>/auth/login" class="btn-auth btn-login">Log In</a>
                <a href="<?= APP_URL ?>/auth/register" class="btn-auth btn-register">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- ============================================================ CONTENT -->
<main>
    <?= $content ?>
</main>

<!-- ============================================================ FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?= APP_URL ?>/" class="navbar-brand" style="font-size:18px">
                    <div class="logo-icon" style="width:30px;height:30px;font-size:15px">🖥</div>
                    MPC<span>Trading</span>
                </a>
                <p>Your trusted PC shop in Dasmariñas, Cavite. Budget-aware builds, intelligent recommendations, and Cash on Delivery available.</p>
                <div style="margin-top:14px;font-size:13px;color:var(--text-muted)">
                    📍 Dasmariñas, Cavite &nbsp;|&nbsp; 📞 0917-123-4567 &nbsp;|&nbsp; 💬 Facebook: MPC Trading
                </div>
            </div>
            <div class="footer-col">
                <h4>Shop</h4>
                <a href="<?= APP_URL ?>/products?category=cpu">Processors</a>
                <a href="<?= APP_URL ?>/products?category=gpu">Graphics Cards</a>
                <a href="<?= APP_URL ?>/products?category=ram">Memory</a>
                <a href="<?= APP_URL ?>/products?category=motherboard">Motherboards</a>
                <a href="<?= APP_URL ?>/products?condition=used">Used Components</a>
            </div>
            <div class="footer-col">
                <h4>Tools</h4>
                <a href="<?= APP_URL ?>/recommend">PC Build Recommender</a>
                <a href="<?= APP_URL ?>/products">Browse All Products</a>
                <a href="<?= APP_URL ?>/cart">Shopping Cart</a>
            </div>
            <div class="footer-col">
                <h4>Info</h4>
                <a href="<?= APP_URL ?>/about">About MPC Trading</a>
                <a href="#">Payment & Delivery</a>
                <a href="#">Installment Policy</a>
                <a href="<?= APP_URL ?>/contact">Contact Us</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?= date('Y') ?> MPC Trading PC Shop — Dasmariñas, Cavite. All rights reserved.</p>
            <p style="color:var(--text-muted);font-size:12px">Installment available in-store only.</p>
        </div>
    </div>
</footer>

<script src="<?= APP_URL ?>/public/js/main.js"></script>
<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
