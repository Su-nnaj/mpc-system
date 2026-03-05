<?php $layout = "admin"; ?>
<div class="admin-layout">
<aside class="admin-sidebar">
    <div class="nav-section">
        <div class="nav-section-title">Inventory</div>
        <a href="<?=APP_URL?>/inventory/dashboard" class="admin-nav-link">Dashboard</a>
        <a href="<?=APP_URL?>/inventory/products" class="admin-nav-link active">Manage Stock</a>
    </div>
    <div class="nav-section" style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border)">
        <a href="<?=APP_URL?>/" class="admin-nav-link">Back to Shop</a>
        <a href="<?=APP_URL?>/auth/logout" class="admin-nav-link" style="color:var(--red)">Logout</a>
    </div>
</aside>
<div class="admin-content">
    <h1 class="page-title">Stock Management</h1>
    <p class="page-subtitle">Update product stock levels</p>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash["type"]==="success"?"success":"error"?> alert-auto"><?=htmlspecialchars($flash["message"])?></div><?php endif; ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Product</th><th>SKU</th><th>Current Stock</th><th>Price</th><th>Condition</th><th>Update Stock</th></tr></thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr>
                    <td><div style="font-weight:600;font-size:13px"><?=htmlspecialchars(substr($p["name"],0,50))?></div><div style="font-size:11px;color:var(--text-muted)"><?=htmlspecialchars($p["brand"]??"")?></div></td>
                    <td class="font-mono" style="font-size:11px;color:var(--text-muted)"><?=htmlspecialchars($p["sku"]??"")?></td>
                    <td><span class="badge <?=$p["stock_quantity"]<=0?"badge-cancelled":($p["stock_quantity"]<=$p["min_stock_alert"]?"badge-pending":"badge-delivered")?>"><?=$p["stock_quantity"]?></span></td>
                    <td class="font-mono" style="font-size:13px">PHP <?=number_format($p["price"],2)?></td>
                    <td><span class="badge <?=$p["condition_type"]==="brand_new"?"badge-delivered":"badge-pending"?>"><?=ucfirst(str_replace("_"," ",$p["condition_type"]))?></span></td>
                    <td>
                        <form action="<?=APP_URL?>/inventory/stock/update" method="POST" style="display:flex;gap:6px">
                            <input type="hidden" name="product_id" value="<?=$p["id"]?>">
                            <input type="number" name="quantity" value="<?=$p["stock_quantity"]?>" class="form-control" style="width:80px;padding:4px 8px;font-size:13px">
                            <select name="action" class="form-control" style="font-size:12px;padding:4px;width:100px">
                                <option value="adjust">Adjust</option>
                                <option value="add">Add</option>
                                <option value="remove">Remove</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>