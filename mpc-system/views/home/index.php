<?php $flash = $flash ?? null; ?>

<section style="background:linear-gradient(135deg,#0a0c0f 0%,#0d1f17 100%);border-bottom:1px solid var(--border);padding:0;overflow:hidden;position:relative;min-height:680px;display:flex;align-items:center;justify-content:center;text-align:center">
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(0,212,170,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(0,212,170,0.03) 1px,transparent 1px);background-size:40px 40px;pointer-events:none"></div>
    <div style="position:absolute;right:-200px;top:50%;transform:translateY(-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(0,212,170,0.08) 0%,transparent 70%);pointer-events:none"></div>
    <div style="position:absolute;left:-100px;top:50%;transform:translateY(-50%);width:500px;height:500px;background:radial-gradient(circle,rgba(0,168,255,0.06) 0%,transparent 70%);pointer-events:none"></div>

    <div class="container" style="position:relative;z-index:10;padding:80px 20px;max-width:720px">
        <div style="animation:fadeInUp 0.6s ease forwards">
            <?php if($flash): ?>
                <div class="alert alert-<?=$flash['type']==='success'?'success':'error'?> alert-auto mb-3"><?=htmlspecialchars($flash['message'])?></div>
            <?php endif; ?>
            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(0,212,170,0.1);border:1px solid rgba(0,212,170,0.3);color:var(--accent);padding:5px 16px;border-radius:99px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:24px">
                <span style="width:6px;height:6px;background:var(--accent);border-radius:50%;animation:pulse-dot 2s infinite"></span>
                Dasmariñas #1 PC Shop
            </div>
            <h1 style="font-family:var(--font-display);font-size:clamp(38px,4.5vw,62px);font-weight:700;line-height:1.05;margin-bottom:20px">
                Build Your Dream PC
                <span style="color:var(--accent);display:block;text-shadow:0 0 40px rgba(0,212,170,0.3)">Within Budget.</span>
            </h1>
            <p style="color:var(--text-secondary);font-size:16px;line-height:1.75;margin-bottom:32px;max-width:480px;margin-left:auto;margin-right:auto">
                Smart component recommendations, real-time compatibility checking, and Cash on Delivery — all in one place.
            </p>
            <div style="display:flex;gap:14px;flex-wrap:wrap;justify-content:center;margin-bottom:28px">
                <a href="<?=APP_URL?>/recommend" class="btn btn-primary btn-lg" style="box-shadow:0 0 24px rgba(0,212,170,0.25)">🤖 Get PC Recommendation</a>
                <a href="<?=APP_URL?>/products" class="btn btn-outline btn-lg">Browse Components</a>
            </div>
            <div style="display:flex;gap:28px;flex-wrap:wrap;justify-content:center;padding-top:24px;border-top:1px solid var(--border)">
                <?php foreach([['500+','In Stock'],['COD','Delivery'],['AI','Recommender'],['✓','Compatible']] as [$v,$l]): ?>
                <div>
                    <div style="font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--accent)"><?=$v?></div>
                    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px"><?=$l?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="installment-notice" style="margin-top:20px;max-width:460px;margin-left:auto;margin-right:auto">
                <span class="notice-icon">⚠</span>
                <span>Installment arrangements are available <strong>in-store only</strong>. Visit our store in Dasmariñas.</span>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section style="padding:48px 0;background:var(--bg-surface);border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="section-heading text-center" style="margin-bottom:28px"><h2>Shop by Category</h2><div class="section-line" style="margin:10px auto 0"></div></div>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(110px,1fr));gap:12px">
            <?php
            $catIcons=['cpu'=>'🔧','motherboard'=>'🖥','ram'=>'💾','gpu'=>'🎮','storage'=>'💿','psu'=>'⚡','case'=>'📦','cooling'=>'❄️','monitor'=>'🖵','other'=>'🖱'];
            foreach($categories as $cat): ?>
                <a href="<?=APP_URL?>/products?category=<?=$cat['slug']?>" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:16px 8px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);text-align:center;transition:var(--transition);color:var(--text-secondary);font-size:13px;font-weight:500"
                   onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)';this.style.transform='translateY(-3px)'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-secondary)';this.style.transform='translateY(0)'">
                    <span style="font-size:26px"><?=$catIcons[$cat['component_type']]??'🖥'?></span>
                    <?=htmlspecialchars($cat['name'])?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section style="padding:60px 0">
    <div class="container">
        <div class="flex-between mb-4">
            <div class="section-heading" style="margin-bottom:0"><h2>Featured Products</h2><div class="section-line"></div></div>
            <a href="<?=APP_URL?>/products" class="btn btn-outline btn-sm">View All →</a>
        </div>
        <div class="grid grid-products">
            <?php foreach($featured as $p):
                $price=(float)($p['sale_price']??$p['price']); $hasOrig=!empty($p['sale_price']); ?>
                <div class="product-card fade-in" style="transition:transform 0.2s ease" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="product-image">
                        <?php if($p['image_main']): ?><img src="<?=APP_URL?>/public/images/uploads/<?=htmlspecialchars($p['image_main'])?>" alt="<?=htmlspecialchars($p['name'])?>">
                        <?php else: ?><span class="no-image"><?=$catIcons[$p['component_type']]??'🖥'?></span><?php endif; ?>
                        <span class="product-badge <?=$p['condition_type']==='used'?'badge-used':($hasOrig?'badge-sale':'badge-new')?>"><?=$p['condition_type']==='used'?'Used':($hasOrig?'Sale':'New')?></span>
                    </div>
                    <div class="product-info">
                        <div class="product-category"><?=htmlspecialchars($p['category_name'])?></div>
                        <div class="product-name"><?=htmlspecialchars($p['name'])?></div>
                        <div class="product-price"><span class="price-current">₱<?=number_format($price,2)?></span><?php if($hasOrig): ?><span class="price-original">₱<?=number_format($p['price'],2)?></span><?php endif; ?></div>
                        <?php $sc=$p['stock_quantity']>10?'in-stock':($p['stock_quantity']>0?'low-stock':'out-stock'); $sl=$p['stock_quantity']>10?'✓ In Stock':($p['stock_quantity']>0?"⚠ {$p['stock_quantity']} left":'✕ Out of Stock'); ?>
                        <span class="stock-badge <?=$sc?>"><?=$sl?></span>
                    </div>
                    <div class="product-actions">
                        <a href="<?=APP_URL?>/products/<?=$p['slug']?>" class="btn btn-outline btn-sm" style="flex:1">Details</a>
                        <?php if($p['stock_quantity']>0): ?><button class="btn btn-primary btn-sm btn-add-cart" data-id="<?=$p['id']?>">🛒</button><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- WHY MPC -->
<section style="padding:60px 0;background:var(--bg-surface);border-top:1px solid var(--border)">
    <div class="container">
        <div class="section-heading text-center" style="margin-bottom:36px"><h2>Why Choose MPC Trading?</h2><div class="section-line" style="margin:10px auto 0"></div></div>
        <div class="grid grid-4">
            <?php foreach([['🤖','Smart Recommendations','AI engine picks optimal builds for your budget.'],['✅','Compatibility Check','Sockets, RAM, and PSU verified automatically.'],['🚚','Cash on Delivery','Order online, pay when your parts arrive.'],['🔧','Expert Staff','Real PC enthusiasts ready to help.']] as [$i,$t,$d]): ?>
            <div class="card" style="text-align:center;padding:28px 20px;transition:var(--transition)" onmouseover="this.style.borderColor='var(--accent)';this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                <div style="font-size:36px;margin-bottom:14px"><?=$i?></div>
                <h3 style="font-family:var(--font-display);font-size:18px;font-weight:700;margin-bottom:10px"><?=$t?></h3>
                <p style="font-size:14px;color:var(--text-secondary);line-height:1.6"><?=$d?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- COD BANNER -->
<section style="padding:48px 0">
    <div class="container">
        <div class="cod-banner" style="justify-content:space-between;flex-wrap:wrap;gap:16px">
            <div style="display:flex;align-items:center;gap:16px">
                <span style="font-size:40px">🚚</span>
                <div>
                    <strong style="font-family:var(--font-display);font-size:20px;display:block">Free Delivery on Orders Over ₱5,000!</strong>
                    <span style="color:var(--text-secondary)">Cash on Delivery throughout Dasmariñas. Standard fee: ₱150.</span>
                </div>
            </div>
            <a href="<?=APP_URL?>/products" class="btn btn-primary">Shop Now →</a>
        </div>
    </div>
</section>

<style>
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}
</style>

<!-- 3D viewer removed -->
