<?php $layout = "admin"; ?>
<div class="admin-layout">
<aside class="admin-sidebar">
    <div class="nav-section">
        <div class="nav-section-title">Inventory</div>
        <a href="<?=APP_URL?>/inventory/dashboard" class="admin-nav-link active">Dashboard</a>
        <a href="<?=APP_URL?>/inventory/products" class="admin-nav-link">Manage Stock</a>
    </div>
    <div class="nav-section" style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border)">
        <a href="<?=APP_URL?>/" class="admin-nav-link">Back to Shop</a>
        <a href="<?=APP_URL?>/auth/logout" class="admin-nav-link" style="color:var(--red)">Logout</a>
    </div>
</aside>
<div class="admin-content">
    <h1 class="page-title">Inventory Dashboard</h1>
    <p class="page-subtitle">Monitor stock levels and manage products</p>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash["type"]==="success"?"success":"error"?> alert-auto"><?=htmlspecialchars($flash["message"])?></div><?php endif; ?>
    <div class="card">
        <div class="card-header flex-between">
            <strong>Low Stock Alerts (<?=count($lowStock)?> items)</strong>
            <a href="<?=APP_URL?>/inventory/products" class="btn btn-primary btn-sm">Manage All Stock</a>
        </div>
        <div class="table-wrapper" style="border:none;border-radius:0">
            <table>
                <thead><tr><th>Product</th><th>Category</th><th>Current Stock</th><th>Alert Threshold</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach($lowStock as $p): ?>
                    <tr>
                        <td><div style="font-weight:600;font-size:13px"><?=htmlspecialchars($p["name"])?></div><div style="font-size:11px;color:var(--text-muted)"><?=htmlspecialchars($p["sku"]??"")?></div></td>
                        <td style="font-size:13px;color:var(--text-secondary)"><?=htmlspecialchars($p["category_name"])?></td>
                        <td><span class="badge <?=$p["stock_quantity"]<=0?"badge-cancelled":"badge-pending"?>"><?=$p["stock_quantity"]?> units</span></td>
                        <td style="font-size:13px;color:var(--text-muted)"><?=$p["min_stock_alert"]?> units</td>
                        <td><span class="badge <?=$p["stock_quantity"]<=0?"badge-cancelled":"badge-pending"?>"><?=$p["stock_quantity"]<=0?"OUT OF STOCK":"LOW STOCK"?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($lowStock)): ?>
                    <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">✓ All products have sufficient stock!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>