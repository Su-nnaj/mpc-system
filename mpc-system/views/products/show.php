<?php
$catIcons = ['cpu'=>'🔧','motherboard'=>'🖥','ram'=>'💾','gpu'=>'🎮','storage'=>'💿','psu'=>'⚡','case'=>'📦','cooling'=>'❄️','monitor'=>'🖵','other'=>'🖱'];
$price    = (float)($product['sale_price'] ?? $product['price']);
$hasOrig  = !empty($product['sale_price']);
?>
<div class="page-content"><div class="container">
    <div class="breadcrumb">
        <a href="<?= APP_URL ?>/">Home</a><span class="sep">/</span>
        <a href="<?= APP_URL ?>/products">Shop</a><span class="sep">/</span>
        <span style="color:var(--text-secondary)"><?= htmlspecialchars($product['name']) ?></span>
    </div>
    <div class="product-detail-grid">
        <div><div class="product-gallery"><div class="product-main-image">
            <?php if ($product['image_main']): ?>
                <img src="<?= APP_URL ?>/public/images/uploads/<?= htmlspecialchars($product['image_main']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="main-img">
            <?php else: ?>
                <div style="font-size:80px;color:var(--text-muted)"><?= $catIcons[$product['component_type']] ?? '🖥' ?></div>
            <?php endif; ?>
        </div></div></div>
        <div>
            <span class="product-badge <?= $product['condition_type']==='used'?'badge-used':'badge-new' ?>" style="position:static"><?= $product['condition_type'] === 'used' ? '⚠ Used' : '✓ Brand New' ?></span>
            <div style="font-size:13px;color:var(--text-muted);text-transform:uppercase;margin:8px 0 4px"><?= htmlspecialchars($product['category_name']) ?></div>
            <h1 style="font-family:var(--font-display);font-size:28px;font-weight:700;line-height:1.2;margin-bottom:12px"><?= htmlspecialchars($product['name']) ?></h1>
            <?php if ($product['brand']): ?><div style="font-size:14px;color:var(--text-secondary);margin-bottom:16px">Brand: <strong><?= htmlspecialchars($product['brand']) ?></strong></div><?php endif; ?>
            <div style="margin-bottom:20px">
                <div style="display:flex;align-items:baseline;gap:12px">
                    <span class="price-current" style="font-size:32px">₱<?= number_format($price, 2) ?></span>
                    <?php if ($hasOrig): ?><span class="price-original" style="font-size:18px">₱<?= number_format($product['price'], 2) ?></span><?php endif; ?>
                </div>
                <span class="stock-badge <?= $product['stock_quantity']>5?'in-stock':($product['stock_quantity']>0?'low-stock':'out-stock') ?>" style="margin-top:10px">
                    <?= $product['stock_quantity']>5?"✓ In Stock":($product['stock_quantity']>0?"⚠ Only {$product['stock_quantity']} left":"✕ Out of Stock") ?>
                </span>
            </div>
            <div class="cod-banner" style="margin-bottom:16px"><span class="cod-icon">🚚</span><div class="cod-text"><strong>Cash on Delivery Available</strong><span>Free delivery over ₱5,000 in Dasmariñas.</span></div></div>
            <div class="installment-notice" style="margin-bottom:20px"><span class="notice-icon">ℹ</span><span>Installment arrangements require in-store consultation.</span></div>
            <?php if ($product['stock_quantity'] > 0): ?>
                <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px">
                    <div style="display:flex;align-items:center;gap:8px;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius);padding:4px">
                        <button class="qty-btn" onclick="adjQ(-1)">−</button>
                        <input type="number" id="qty-<?= $product['id'] ?>" value="1" min="1" max="<?= $product['stock_quantity'] ?>" style="width:50px;text-align:center;background:none;border:none;color:var(--text-primary);font-size:16px;font-family:var(--font-mono)">
                        <button class="qty-btn" onclick="adjQ(1)">+</button>
                    </div>
                    <button class="btn btn-primary" style="flex:1" onclick="MPC.addToCart(<?= $product['id'] ?>,parseInt(document.getElementById('qty-<?= $product['id'] ?>').value),this)">🛒 Add to Cart</button>
                </div>
                <a href="<?= APP_URL ?>/cart" class="btn btn-outline btn-block">View Cart →</a>
            <?php else: ?><button class="btn btn-outline btn-block" disabled>Out of Stock</button><?php endif; ?>
            <?php if ($product['description']): ?>
                <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--border)">
                    <h3 style="font-family:var(--font-display);font-size:14px;margin-bottom:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px">Description</h3>
                    <p style="font-size:14px;color:var(--text-secondary);line-height:1.7"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!empty($specs)): ?>
    <div style="margin-top:40px"><h2 style="font-family:var(--font-display);font-size:22px;font-weight:700;margin-bottom:16px">Specifications</h2>
        <div class="table-wrapper"><table><tbody>
            <?php foreach ($specs as $k => $v): ?><tr><td style="color:var(--text-secondary);font-size:13px;width:40%"><?= htmlspecialchars(ucwords(str_replace('_',' ',$k))) ?></td><td style="font-family:var(--font-mono);font-size:13px"><?= htmlspecialchars(is_array($v)?implode(', ',$v):$v) ?></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </div><?php endif; ?>
    <?php if (!empty($related)): ?>
    <div style="margin-top:40px"><h2 style="font-family:var(--font-display);font-size:22px;font-weight:700;margin-bottom:20px">Related Products</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
        <?php foreach ($related as $r): if($r['id']===$product['id'])continue; $rp=(float)($r['sale_price']??$r['price']); ?>
            <div class="product-card"><div class="product-image" style="height:120px"><span class="no-image" style="font-size:32px"><?= $catIcons[$product['component_type']]??'🖥' ?></span></div>
                <div class="product-info"><div class="product-name" style="font-size:13px"><?= htmlspecialchars($r['name']) ?></div><span class="price-current" style="font-size:15px;margin-top:6px;display:block">₱<?= number_format($rp,2) ?></span></div>
                <div class="product-actions"><a href="<?= APP_URL ?>/products/<?= $r['slug'] ?>" class="btn btn-outline btn-sm btn-block">View</a></div>
            </div>
        <?php endforeach; ?>
        </div></div>
    <?php endif; ?>
</div></div>
<script>function adjQ(d){const i=document.getElementById('qty-<?= $product['id'] ?>');i.value=Math.max(1,Math.min(<?= $product['stock_quantity'] ?>,parseInt(i.value)+d));}</script>
