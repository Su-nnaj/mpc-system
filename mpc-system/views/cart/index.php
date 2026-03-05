<div class="page-content"><div class="container">
    <div class="breadcrumb"><a href="<?= APP_URL ?>/">Home</a><span class="sep">/</span><span>Shopping Cart</span></div>
    <h1 class="page-title">Shopping Cart</h1>
    <p class="page-subtitle"><?= count($items) ?> item(s) in your cart</p>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🛒</div>
            <h3>Your Cart is Empty</h3>
            <p>Start building your PC by browsing our components!</p>
            <a href="<?= APP_URL ?>/products" class="btn btn-primary">Browse Components</a>
            <a href="<?= APP_URL ?>/recommend" class="btn btn-outline" style="margin-left:10px">🤖 Try PC Recommender</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <div class="card">
                <div class="card-header flex-between">
                    <strong>Cart Items</strong>
                    <span class="text-muted" style="font-size:13px"><?= count($items) ?> items</span>
                </div>
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-img">
                            <?php if ($item['image_main']): ?>
                                <img src="<?= APP_URL ?>/public/images/uploads/<?= htmlspecialchars($item['image_main']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width:100%;height:100%;object-fit:cover">
                            <?php else: ?>
                                🖥
                            <?php endif; ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:15px;margin-bottom:4px"><?= htmlspecialchars($item['name']) ?></div>
                            <div style="font-size:12px;color:var(--text-muted);margin-bottom:8px"><?= $item['condition_type'] === 'used' ? '⚠ Used' : '✓ Brand New' ?></div>
                            <div class="cart-qty">
                                <form action="<?= APP_URL ?>/cart/update" method="POST" style="display:flex;align-items:center;gap:6px">
                                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                    <button type="button" class="qty-btn" onclick="this.nextElementSibling.stepDown();this.closest('form').submit()">−</button>
                                    <input type="number" name="quantity" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock_quantity'] ?>">
                                    <button type="button" class="qty-btn" onclick="this.previousElementSibling.stepUp();this.closest('form').submit()">+</button>
                                </form>
                            </div>
                        </div>
                        <div style="text-align:right">
                            <div class="price-current" style="font-size:18px">₱<?= number_format($item['effective_price'] * $item['quantity'], 2) ?></div>
                            <div style="font-size:12px;color:var(--text-muted)">₱<?= number_format($item['effective_price'], 2) ?> each</div>
                            <form action="<?= APP_URL ?>/cart/remove" method="POST" style="margin-top:8px">
                                <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                <button type="submit" style="background:none;border:none;color:var(--red);font-size:12px;cursor:pointer">✕ Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary">
                <h3 style="font-family:var(--font-display);font-size:18px;font-weight:700;margin-bottom:16px">Order Summary</h3>
                <div class="summary-row"><span>Subtotal</span><span class="font-mono">₱<?= number_format($total, 2) ?></span></div>
                <div class="summary-row">
                    <span>Delivery Fee</span>
                    <span class="font-mono <?= $deliveryFee == 0 ? 'text-accent' : '' ?>">
                        <?= $deliveryFee == 0 ? 'FREE' : '₱' . number_format($deliveryFee, 2) ?>
                    </span>
                </div>
                <?php if ($deliveryFee > 0): ?>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:-8px;padding-bottom:10px;border-bottom:1px solid var(--border)">
                        Add ₱<?= number_format(FREE_DELIVERY_THRESHOLD - $total, 2) ?> more for free delivery!
                    </div>
                <?php endif; ?>
                <div class="summary-row" style="font-size:17px;font-weight:700">
                    <span>Total</span><span class="summary-total">₱<?= number_format($grandTotal, 2) ?></span>
                </div>
                <div class="cod-banner" style="margin:16px 0 20px">
                    <span class="cod-icon">🚚</span>
                    <div class="cod-text"><strong>Cash on Delivery</strong><span>Pay when your order arrives.</span></div>
                </div>
                <a href="<?= APP_URL ?>/checkout" class="btn btn-primary btn-block btn-lg">Proceed to Checkout →</a>
                <a href="<?= APP_URL ?>/products" class="btn btn-outline btn-block" style="margin-top:10px">Continue Shopping</a>
                <div class="installment-notice" style="margin-top:12px">
                    <span class="notice-icon">ℹ</span>
                    <span style="font-size:12px">Installment available in-store only.</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div></div>
