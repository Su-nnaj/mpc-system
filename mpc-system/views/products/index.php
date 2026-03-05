<?php
$catIcons = ['cpu'=>'🔧','motherboard'=>'🖥','ram'=>'💾','gpu'=>'🎮','storage'=>'💿','psu'=>'⚡','case'=>'📦','cooling'=>'❄️','monitor'=>'🖵','other'=>'🖱'];
?>
<div class="page-content">
<div class="container">

    <!-- Search Bar -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:28px;position:relative">
        <form action="<?= APP_URL ?>/products" method="GET" style="display:flex;gap:10px">
            <div style="position:relative;flex:1">
                <input type="text" name="q" id="search-input" value="<?= htmlspecialchars($query) ?>"
                       class="form-control" placeholder="Search processors, GPU, RAM, monitors..." style="padding-left:38px">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:16px">🔍</span>
                <div id="search-suggestions" style="display:none;position:absolute;top:100%;left:0;right:0;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);margin-top:4px;z-index:100;overflow:hidden"></div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="layout-with-sidebar">

        <!-- SIDEBAR FILTERS -->
        <aside class="sidebar">
            <form action="<?= APP_URL ?>/products" method="GET" id="filter-form">
                <?php if ($query): ?><input type="hidden" name="q" value="<?= htmlspecialchars($query) ?>"><?php endif; ?>

                <div class="card card-body" style="margin-bottom:16px">
                    <div class="sidebar-title">Categories</div>
                    <?php foreach ($categories as $cat): ?>
                        <label style="display:flex;align-items:center;gap:8px;padding:6px 0;cursor:pointer;color:var(--text-secondary);font-size:14px;border-bottom:1px solid var(--border)">
                            <input type="radio" name="category" value="<?= $cat['slug'] ?>"
                                   <?= ($filters['category'] === $cat['slug']) ? 'checked' : '' ?>
                                   onchange="document.getElementById('filter-form').submit()"
                                   style="accent-color:var(--accent)">
                            <span><?= $catIcons[$cat['component_type']] ?? '🖥' ?></span>
                            <?= htmlspecialchars($cat['name']) ?>
                        </label>
                    <?php endforeach; ?>
                    <?php if ($filters['category']): ?>
                        <a href="<?= APP_URL ?>/products<?= $query ? '?q='.urlencode($query) : '' ?>" style="font-size:12px;color:var(--red);margin-top:8px;display:block">✕ Clear</a>
                    <?php endif; ?>
                </div>

                <div class="card card-body" style="margin-bottom:16px">
                    <div class="sidebar-title">Condition</div>
                    <?php foreach ([''=>'All','brand_new'=>'Brand New','used'=>'Used','refurbished'=>'Refurbished'] as $val => $lbl): ?>
                        <label style="display:flex;align-items:center;gap:8px;padding:6px 0;cursor:pointer;font-size:14px;color:var(--text-secondary)">
                            <input type="radio" name="condition" value="<?= $val ?>"
                                   <?= ($filters['condition'] === $val) ? 'checked' : '' ?>
                                   onchange="document.getElementById('filter-form').submit()"
                                   style="accent-color:var(--accent)"> <?= $lbl ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="card card-body" style="margin-bottom:16px">
                    <div class="sidebar-title">Price Range</div>
                    <div class="form-row" style="gap:8px">
                        <input type="number" name="min_price" value="<?= $filters['min_price'] ?>" placeholder="Min ₱" class="form-control" style="font-size:13px">
                        <input type="number" name="max_price" value="<?= $filters['max_price'] ?>" placeholder="Max ₱" class="form-control" style="font-size:13px">
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm btn-block mt-2">Apply</button>
                </div>

                <div class="card card-body">
                    <div class="sidebar-title">Sort By</div>
                    <select name="sort" class="form-control" style="font-size:13px" onchange="document.getElementById('filter-form').submit()">
                        <option value="">Featured</option>
                        <option value="price_asc"  <?= $filters['sort']==='price_asc'?'selected':'' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= $filters['sort']==='price_desc'?'selected':'' ?>>Price: High to Low</option>
                        <option value="newest"     <?= $filters['sort']==='newest'?'selected':'' ?>>Newest First</option>
                        <option value="popular"    <?= $filters['sort']==='popular'?'selected':'' ?>>Most Popular</option>
                    </select>
                </div>
            </form>
        </aside>

        <!-- PRODUCT GRID -->
        <div>
            <div class="flex-between mb-3" style="flex-wrap:wrap;gap:8px">
                <p style="color:var(--text-secondary);font-size:14px">
                    Showing <strong style="color:var(--text-primary)"><?= count($products) ?></strong> of <strong style="color:var(--text-primary)"><?= $total ?></strong> products
                    <?php if ($query): ?> for "<em><?= htmlspecialchars($query) ?></em>"<?php endif; ?>
                </p>
            </div>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h3>No Products Found</h3>
                    <p>Try different keywords or adjust your filters.</p>
                    <a href="<?= APP_URL ?>/products" class="btn btn-primary">Clear Filters</a>
                </div>
            <?php else: ?>
                <div class="grid grid-products">
                    <?php foreach ($products as $p):
                        $price = (float)($p['sale_price'] ?? $p['price']);
                        $hasOrig = !empty($p['sale_price']);
                    ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($p['image_main']): ?>
                                    <img src="<?= APP_URL ?>/public/images/uploads/<?= htmlspecialchars($p['image_main']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                                <?php else: ?>
                                    <span class="no-image"><?= $catIcons[$p['component_type']] ?? '🖥' ?></span>
                                <?php endif; ?>
                                <span class="product-badge <?= $p['condition_type']==='used'?'badge-used':($hasOrig?'badge-sale':'badge-new') ?>">
                                    <?= $p['condition_type']==='used'?'Used':($hasOrig?'SALE':'New') ?>
                                </span>
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?= htmlspecialchars($p['category_name']) ?></div>
                                <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                                <div class="product-price">
                                    <span class="price-current">₱<?= number_format($price, 2) ?></span>
                                    <?php if ($hasOrig): ?>
                                        <span class="price-original">₱<?= number_format($p['price'], 2) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php
                                $sc = $p['stock_quantity'] > 10 ? 'in-stock' : ($p['stock_quantity'] > 0 ? 'low-stock' : 'out-stock');
                                $sl = $p['stock_quantity'] > 10 ? '✓ In Stock' : ($p['stock_quantity'] > 0 ? "⚠ {$p['stock_quantity']} left" : '✕ Out of Stock');
                                ?>
                                <span class="stock-badge <?= $sc ?>"><?= $sl ?></span>
                            </div>
                            <div class="product-actions">
                                <a href="<?= APP_URL ?>/products/<?= $p['slug'] ?>" class="btn btn-outline btn-sm" style="flex:1">Details</a>
                                <?php if ($p['stock_quantity'] > 0): ?>
                                    <button class="btn btn-primary btn-sm btn-add-cart" data-id="<?= $p['id'] ?>">🛒</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- PAGINATION -->
                <?php if ($pages > 1): ?>
                    <div class="pagination">
                        <?php
                        $params = array_filter(array_merge($filters, ['q' => $query]));
                        for ($i = 1; $i <= $pages; $i++):
                            $qstr = http_build_query(array_merge($params, ['page' => $i]));
                        ?>
                            <a href="<?= APP_URL ?>/products?<?= $qstr ?>"
                               class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
