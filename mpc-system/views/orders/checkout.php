<div class="page-content"><div class="container-sm">
    <div class="breadcrumb"><a href="<?= APP_URL ?>/">Home</a><span class="sep">/</span><a href="<?= APP_URL ?>/cart">Cart</a><span class="sep">/</span><span>Checkout</span></div>
    <h1 class="page-title">Checkout</h1>
    <p class="page-subtitle">Complete your order — Cash on Delivery only</p>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:24px">
        <form action="<?= APP_URL ?>/checkout/place" method="POST">
            <div class="card card-body" style="margin-bottom:20px">
                <h3 style="font-family:var(--font-display);font-size:16px;margin-bottom:16px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px">Delivery Information</h3>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required></div>
                    <div class="form-group"><label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="09XX-XXX-XXXX" required></div>
                </div>
                <div class="form-group"><label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></div>
                <div class="form-group"><label class="form-label">Delivery Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address'] ?? '') ?>" placeholder="House/Unit No., Street, Subdivision" required></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Barangay</label>
                        <input type="text" name="barangay" class="form-control" placeholder="Barangay name" required></div>
                    <div class="form-group"><label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="Dasmariñas" required></div>
                </div>
                <div class="form-group"><label class="form-label">Order Notes (Optional)</label>
                    <textarea name="notes" class="form-control" placeholder="Special delivery instructions..."></textarea></div>
            </div>

            <div class="card card-body" style="margin-bottom:20px">
                <h3 style="font-family:var(--font-display);font-size:16px;margin-bottom:16px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:1px">Payment Method</h3>
                <div style="padding:16px;background:var(--bg-surface);border-radius:var(--radius);border:2px solid var(--accent)">
                    <div style="display:flex;align-items:center;gap:12px">
                        <input type="radio" name="payment" value="cod" checked style="accent-color:var(--accent);width:16px;height:16px">
                        <div>
                            <strong style="color:var(--accent)">🚚 Cash on Delivery (COD)</strong>
                            <div style="font-size:13px;color:var(--text-secondary);margin-top:2px">Pay in cash when your order arrives at your doorstep.</div>
                        </div>
                    </div>
                </div>
                <div class="installment-notice" style="margin-top:12px">
                    <span class="notice-icon">⚠</span>
                    <span>Installment and financing options are <strong>in-store only</strong>. Visit our physical store for these arrangements.</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                🛍 Place Order — ₱<?= number_format($grandTotal, 2) ?>
            </button>
        </form>

        <!-- ORDER SUMMARY -->
        <div>
            <div class="card" style="position:sticky;top:80px">
                <div class="card-header"><strong>Your Order (<?= count($items) ?> items)</strong></div>
                <?php foreach ($items as $item): ?>
                    <div style="display:flex;gap:10px;padding:12px 16px;border-bottom:1px solid var(--border)">
                        <div style="width:44px;height:44px;background:var(--bg-surface);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">🖥</div>
                        <div style="flex:1;min-width:0">
                            <div style="font-size:13px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($item['name']) ?></div>
                            <div style="font-size:12px;color:var(--text-muted)">Qty: <?= $item['quantity'] ?></div>
                        </div>
                        <div class="font-mono" style="font-size:13px;color:var(--accent);white-space:nowrap">₱<?= number_format($item['effective_price'] * $item['quantity'],2) ?></div>
                    </div>
                <?php endforeach; ?>
                <div class="card-body">
                    <div class="summary-row"><span>Subtotal</span><span class="font-mono">₱<?= number_format($total, 2) ?></span></div>
                    <div class="summary-row"><span>Delivery</span><span class="font-mono <?= $deliveryFee==0?'text-accent':'' ?>"><?= $deliveryFee==0?'FREE':'₱'.number_format($deliveryFee,2) ?></span></div>
                    <div class="summary-row" style="font-size:16px;font-weight:700"><span>Total</span><span class="summary-total">₱<?= number_format($grandTotal, 2) ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div></div>
