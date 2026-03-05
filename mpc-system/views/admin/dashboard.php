<?php $layout = "admin"; ?>
<div class="admin-layout">
<?php include __DIR__."/../layouts/admin_sidebar.php"; ?>
<div class="admin-content">
    <div class="flex-between mb-4">
        <div><h1 class="page-title">Dashboard</h1><p class="page-subtitle">Welcome back, <?=htmlspecialchars($_SESSION["full_name"]??"")?></p></div>
    </div>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash["type"]==="success"?"success":"error"?> alert-auto"><?=htmlspecialchars($flash["message"])?></div><?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon green">&#x1F4E6;</div><div><div class="stat-value"><?=$stats["orders"]["total_orders"]?></div><div class="stat-label">Total Orders</div></div></div>
        <div class="stat-card"><div class="stat-icon yellow">&#x23F3;</div><div><div class="stat-value"><?=$stats["orders"]["pending_orders"]?></div><div class="stat-label">Pending Orders</div></div></div>
        <div class="stat-card"><div class="stat-icon green">&#x1F4B0;</div><div><div class="stat-value" style="font-size:18px">PHP <?=number_format($stats["orders"]["month_revenue"],2)?></div><div class="stat-label">Monthly Revenue</div></div></div>
        <div class="stat-card"><div class="stat-icon blue">&#x1F4E6;</div><div><div class="stat-value"><?=$stats["products"]?></div><div class="stat-label">Total Products</div></div></div>
        <div class="stat-card"><div class="stat-icon blue">&#x1F465;</div><div><div class="stat-value"><?=$stats["users"]?></div><div class="stat-label">Registered Users</div></div></div>
        <div class="stat-card"><div class="stat-icon red">&#x26A0;</div><div><div class="stat-value"><?=$stats["low_stock"]?></div><div class="stat-label">Low Stock Alerts</div></div></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
        <div class="card">
            <div class="card-header flex-between"><strong>Recent Orders</strong><a href="<?=APP_URL?>/admin/orders" class="btn btn-outline btn-sm">View All</a></div>
            <div class="table-wrapper" style="border:none;border-radius:0">
                <table>
                    <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach(array_slice($recentOrders,0,8) as $o): ?>
                        <tr>
                            <td><span class="font-mono" style="font-size:12px;color:var(--accent)"><?=htmlspecialchars($o["order_number"])?></span></td>
                            <td style="font-size:13px"><?=htmlspecialchars($o["full_name"])?></td>
                            <td class="font-mono" style="font-size:13px">PHP <?=number_format($o["total_amount"],2)?></td>
                            <td><span class="badge badge-<?=$o["order_status"]?>"><?=ucfirst($o["order_status"])?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header flex-between"><strong>Low Stock Alerts</strong><a href="<?=APP_URL?>/inventory/products" class="btn btn-outline btn-sm">Manage</a></div>
            <div class="table-wrapper" style="border:none;border-radius:0">
                <table>
                    <thead><tr><th>Product</th><th>Stock</th><th>Min</th></tr></thead>
                    <tbody>
                        <?php foreach(array_slice($lowStock,0,8) as $p): ?>
                        <tr>
                            <td style="font-size:13px"><?=htmlspecialchars(substr($p["name"],0,35))?>...</td>
                            <td><span class="badge <?=$p["stock_quantity"]==0?"badge-cancelled":"badge-pending"?>"><?=$p["stock_quantity"]?></span></td>
                            <td style="font-size:12px;color:var(--text-muted)"><?=$p["min_stock_alert"]?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>