<?php $layout = "admin"; ?>
<div class="admin-layout">
<aside class="admin-sidebar">
    <div class="nav-section">
        <div class="nav-section-title">Staff Menu</div>
        <a href="<?=APP_URL?>/staff/dashboard" class="admin-nav-link active">Dashboard</a>
        <a href="<?=APP_URL?>/staff/orders" class="admin-nav-link">Orders</a>
    </div>
    <div class="nav-section" style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border)">
        <a href="<?=APP_URL?>/" class="admin-nav-link">Back to Shop</a>
        <a href="<?=APP_URL?>/auth/logout" class="admin-nav-link" style="color:var(--red)">Logout</a>
    </div>
</aside>
<div class="admin-content">
    <h1 class="page-title">Staff Dashboard</h1>
    <p class="page-subtitle">Manage customer orders</p>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash["type"]==="success"?"success":"error"?> alert-auto"><?=htmlspecialchars($flash["message"])?></div><?php endif; ?>
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon yellow">⏳</div><div><div class="stat-value"><?=$stats["pending_orders"]?></div><div class="stat-label">Pending Orders</div></div></div>
        <div class="stat-card"><div class="stat-icon green">💰</div><div><div class="stat-value" style="font-size:18px">PHP <?=number_format($stats["today_revenue"],2)?></div><div class="stat-label">Today Revenue</div></div></div>
        <div class="stat-card"><div class="stat-icon blue">📦</div><div><div class="stat-value"><?=$stats["total_orders"]?></div><div class="stat-label">Total Orders</div></div></div>
    </div>
    <div class="card">
        <div class="card-header"><strong>Pending Orders — Action Required</strong></div>
        <div class="table-wrapper" style="border:none;border-radius:0">
            <table>
                <thead><tr><th>Order #</th><th>Customer</th><th>Phone</th><th>Address</th><th>Total</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach(array_slice($orders,0,20) as $o): ?>
                    <tr>
                        <td><span class="font-mono" style="font-size:12px;color:var(--accent)"><?=htmlspecialchars($o["order_number"])?></span></td>
                        <td style="font-size:13px;font-weight:600"><?=htmlspecialchars($o["full_name"])?></td>
                        <td style="font-size:13px"><?=htmlspecialchars($o["phone"])?></td>
                        <td style="font-size:12px;color:var(--text-secondary);max-width:180px"><?=htmlspecialchars(substr($o["delivery_address"]."  ".$o["barangay"],0,60))?></td>
                        <td class="font-mono" style="font-size:13px;color:var(--accent)">PHP <?=number_format($o["total_amount"],2)?></td>
                        <td>
                            <form action="<?=APP_URL?>/staff/orders/update" method="POST" style="display:flex;gap:6px">
                                <input type="hidden" name="order_id" value="<?=$o["id"]?>">
                                <select name="order_status" class="form-control" style="font-size:12px;padding:4px 8px">
                                    <?php foreach(["pending","confirmed","processing","shipped","delivered"] as $s): ?><option value="<?=$s?>" <?=$o["order_status"]===$s?"selected":""?>><?=ucfirst($s)?></option><?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>