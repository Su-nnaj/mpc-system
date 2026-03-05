<div class="page-content"><div class="container-sm">
    <div style="text-align:center;padding:48px 0 32px">
        <div style="font-size:64px;margin-bottom:16px">&#x2705;</div>
        <h1 style="font-family:var(--font-display);font-size:32px;font-weight:700;margin-bottom:10px">Order Placed Successfully!</h1>
        <p style="color:var(--text-secondary);font-size:16px">Thank you! We will process your order and contact you to confirm delivery.</p>
    </div>
    <?php if($order): ?>
    <div class="card" style="margin-bottom:20px">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
            <span class="font-mono" style="color:var(--accent);font-weight:700"><?=htmlspecialchars($order["order_number"])?></span>
            <span class="badge badge-pending">Pending</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div><div class="form-label">Deliver To</div><div><?=htmlspecialchars($order["full_name"])?></div><div style="color:var(--text-secondary);font-size:13px"><?=htmlspecialchars($order["delivery_address"].", ".$order["barangay"].", ".$order["city"])?></div></div>
                <div><div class="form-label">Payment</div><div>Cash on Delivery</div><div style="color:var(--text-secondary);font-size:13px">Pay upon arrival</div></div>
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <?php foreach($order["items"] as $item): ?>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:14px">
                    <span><?=htmlspecialchars($item["product_name"])?> x<?=$item["quantity"]?></span>
                    <span class="font-mono">PHP <?=number_format($item["total_price"],2)?></span>
                </div>
                <?php endforeach; ?>
                <div style="display:flex;justify-content:space-between;padding:12px 0 0;font-weight:700;font-size:16px">
                    <span>Total</span>
                    <span class="font-mono" style="color:var(--accent)">PHP <?=number_format($order["total_amount"],2)?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div style="display:flex;gap:12px;justify-content:center">
        <a href="<?=APP_URL?>/orders" class="btn btn-outline">My Orders</a>
        <a href="<?=APP_URL?>/products" class="btn btn-primary">Continue Shopping</a>
    </div>
</div></div>