<?php $layout = "admin"; ?>
<div class="admin-layout">
<?php include __DIR__."/../layouts/admin_sidebar.php"; ?>
<div class="admin-content">
    <div class="flex-between mb-4">
        <div><h1 class="page-title">Products</h1><p class="page-subtitle">Manage your product catalog</p></div>
        <button class="btn btn-primary" data-modal="product-modal">+ Add Product</button>
    </div>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash["type"]==="success"?"success":"error"?> alert-auto"><?=htmlspecialchars($flash["message"])?></div><?php endif; ?>

    <div class="card card-body" style="margin-bottom:16px">
        <form action="<?=APP_URL?>/admin/products" method="GET" style="display:flex;gap:10px">
            <input type="text" name="q" value="<?=htmlspecialchars($search??"")?>" class="form-control" placeholder="Search products..." style="flex:1">
            <button type="submit" class="btn btn-outline">Search</button>
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr><th>SKU</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Condition</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($products as $p): ?>
                <tr>
                    <td class="font-mono" style="font-size:11px;color:var(--text-muted)"><?=htmlspecialchars($p["sku"]??"")?></td>
                    <td>
                        <div style="font-weight:600;font-size:13px"><?=htmlspecialchars(substr($p["name"],0,45))?></div>
                        <div style="font-size:11px;color:var(--text-muted)"><?=htmlspecialchars($p["brand"]??"")?></div>
                    </td>
                    <td style="font-size:13px;color:var(--text-secondary)"><?=htmlspecialchars($p["category_name"])?></td>
                    <td class="font-mono" style="font-size:13px">PHP <?=number_format($p["price"],2)?><?php if($p["sale_price"]): ?><br><span style="color:var(--accent);font-size:11px">SALE: PHP <?=number_format($p["sale_price"],2)?></span><?php endif; ?></td>
                    <td><span class="badge <?=$p["stock_quantity"]<=0?"badge-cancelled":($p["stock_quantity"]<=$p["min_stock_alert"]?"badge-pending":"badge-delivered")?>"><?=$p["stock_quantity"]?></span></td>
                    <td><span class="badge <?=$p["condition_type"]==="brand_new"?"badge-delivered":"badge-pending"?>"><?=ucfirst(str_replace("_"," ",$p["condition_type"]))?></span></td>
                    <td><span class="badge <?=$p["is_active"]?"badge-delivered":"badge-cancelled"?>"><?=$p["is_active"]?"Active":"Inactive"?></span></td>
                    <td>
                        <button onclick="openProductModal(<?=htmlspecialchars(json_encode($p))?>')" class="btn btn-outline btn-sm">Edit</button>
                        <form action="<?=APP_URL?>/admin/products/delete" method="POST" style="display:inline" onsubmit="return confirm('Deactivate this product?')">
                            <input type="hidden" name="id" value="<?=$p["id"]?>">
                            <button type="submit" class="btn btn-danger btn-sm">Del</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- PRODUCT MODAL -->
<div class="modal-overlay" id="product-modal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add Product</h3>
            <button class="modal-close">&times;</button>
        </div>
        <form action="<?=APP_URL?>/admin/products/save" method="POST">
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Product Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Brand</label><input type="text" name="brand" class="form-control"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" required>
                            <?php foreach($categories as $c): ?><option value="<?=$c["id"]?>"><?=htmlspecialchars($c["name"])?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Select --</option>
                            <?php foreach($suppliers as $s): ?><option value="<?=$s["id"]?>"><?=htmlspecialchars($s["name"])?></option><?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Price (PHP) *</label><input type="number" name="price" class="form-control" step="0.01" required></div>
                    <div class="form-group"><label class="form-label">Sale Price</label><input type="number" name="sale_price" class="form-control" step="0.01"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Stock Qty *</label><input type="number" name="stock_quantity" class="form-control" value="0" required></div>
                    <div class="form-group"><label class="form-label">Min Stock Alert</label><input type="number" name="min_stock_alert" class="form-control" value="5"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Condition</label>
                        <select name="condition_type" class="form-control">
                            <option value="brand_new">Brand New</option>
                            <option value="used">Used</option>
                            <option value="refurbished">Refurbished</option>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Socket Type</label><input type="text" name="socket_type" class="form-control" placeholder="e.g. LGA1700, AM4"></div>
                </div>
                <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                <div style="display:flex;gap:20px">
                    <label class="form-check"><input type="checkbox" name="is_featured" value="1" style="accent-color:var(--accent)"><span>Featured</span></label>
                    <label class="form-check"><input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--accent)"><span>Active</span></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline modal-close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>