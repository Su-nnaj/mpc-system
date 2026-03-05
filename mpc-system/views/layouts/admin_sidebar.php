<aside class="admin-sidebar">
    <div class="nav-section">
        <div class="nav-section-title">Main</div>
        <a href="<?=APP_URL?>/admin/dashboard" class="admin-nav-link <?=str_contains($_SERVER["REQUEST_URI"],"dashboard")?"active":""?>">&#x1F4CA; Dashboard</a>
    </div>
    <div class="nav-section" style="margin-top:16px">
        <div class="nav-section-title">Shop</div>
        <a href="<?=APP_URL?>/admin/products" class="admin-nav-link <?=str_contains($_SERVER["REQUEST_URI"],"/admin/products")?"active":""?>">&#x1F4E6; Products</a>
        <a href="<?=APP_URL?>/admin/orders" class="admin-nav-link <?=str_contains($_SERVER["REQUEST_URI"],"/admin/orders")?"active":""?>">&#x1F6D2; Orders</a>
        <a href="<?=APP_URL?>/admin/reports" class="admin-nav-link <?=str_contains($_SERVER["REQUEST_URI"],"/admin/reports")?"active":""?>">&#x1F4C8; Reports</a>
    </div>
    <div class="nav-section" style="margin-top:16px">
        <div class="nav-section-title">System</div>
        <a href="<?=APP_URL?>/admin/users" class="admin-nav-link <?=str_contains($_SERVER["REQUEST_URI"],"/admin/users")?"active":""?>">&#x1F465; Users</a>
        <a href="<?=APP_URL?>/inventory/products" class="admin-nav-link">&#x1F4CB; Inventory</a>
    </div>
    <div class="nav-section" style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border)">
        <a href="<?=APP_URL?>/" class="admin-nav-link">&#x1F3E0; Back to Shop</a>
        <a href="<?=APP_URL?>/auth/logout" class="admin-nav-link" style="color:var(--red)">&#x1F6AA; Logout</a>
    </div>
</aside>