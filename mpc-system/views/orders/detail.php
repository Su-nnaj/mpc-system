<div class="page-content"><div class="container-sm">
    <div class="breadcrumb"><a href="<?=APP_URL?>/">Home</a><span class="sep">/</span><a href="<?=APP_URL?>/orders">My Orders</a><span class="sep">/</span><span><?=htmlspecialchars($order["order_number"])?></span></div>
    <div class="flex-between mb-4">
        <h1 class="page-title">Order Details</h1>
        <span class="badge badge-<?=$order["order_status"]?>" style="font-size:14px;padding:6px 16px"><?=ucfirst($order["order_status"])?></span>
    </div>
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><strong class="font-mono" style="color:var(--accent)"><?=htmlspecialchars($order["order_number"])?></strong></div>
        <div class="card-body">
            <div class="form-row">
                <div><div class="form-label">Deliver To</div><div style="font-weight:600"><?=htmlspecialchars($order["full_name"])?></div><div style="color:var(--text-secondary);font-size:13px"><?=htmlspecialchars($order["phone"])?></div><div style="color:var(--text-secondary);font-size:13px;margin-top:4px"><?=htmlspecialchars($order["delivery_address"].", ".$order["barangay"].", ".$order["city"])?></div></div>
                <div><div class="form-label">Payment Method</div><div>🚚 Cash on Delivery</div><div class="form-label" style="margin-top:12px">Date Ordered</div><div><?=date("F d, Y g:i A",strtotime($order["created_at"]))?></div></div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><strong>Items Ordered</strong></div>
        <?php foreach($order["items"] as $item): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-bottom:1px solid var(--border)">
            <div><div style="font-weight:600;font-size:14px"><?=htmlspecialchars($item["product_name"])?></div><div style="font-size:12px;color:var(--text-muted)">Qty: <?=$item["quantity"]?> x PHP <?=number_format($item["unit_price"],2)?></div></div>
            <div class="font-mono" style="color:var(--accent);font-weight:700">PHP <?=number_format($item["total_price"],2)?></div>
        </div>
        <?php endforeach; ?>
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;font-size:14px;padding:6px 0"><span>Subtotal</span><span class="font-mono">PHP <?=number_format($order["subtotal"],2)?></span></div>
            <div style="display:flex;justify-content:space-between;font-size:14px;padding:6px 0"><span>Delivery Fee</span><span class="font-mono"><?=$order["delivery_fee"]>0?"PHP ".number_format($order["delivery_fee"],2):"FREE"?></span></div>
            <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:700;padding:10px 0;border-top:1px solid var(--border);margin-top:6px"><span>Total</span><span class="font-mono" style="color:var(--accent)">PHP <?=number_format($order["total_amount"],2)?></span></div>
        </div>
    </div>
    <a href="<?=APP_URL?>/orders" class="btn btn-outline">← Back to Orders</a>
</div></div>