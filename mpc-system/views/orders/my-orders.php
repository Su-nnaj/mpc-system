<div class="page-content"><div class="container">
    <h1 class="page-title">My Orders</h1>
    <p class="page-subtitle">Track your purchase history</p>
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">&#x1F4E6;</div>
            <h3>No Orders Yet</h3>
            <p>You haven't placed any orders. Start building your PC!</p>
            <div style="display:flex;gap:12px;justify-content:center">
                <a href="<?= APP_URL ?>/recommend" class="btn btn-primary">PC Recommender</a>
                <a href="<?= APP_URL ?>/products" class="btn btn-outline">Browse Shop</a>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Order Number</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><span class="font-mono" style="color:var(--accent)"><?= htmlspecialchars($o['order_number']) ?></span></td>
                            <td style="color:var(--text-secondary);font-size:13px"><?= date('M d, Y g:i A', strtotime($o['created_at'])) ?></td>
                            <td class="font-mono" style="font-weight:700;color:var(--accent)">PHP <?= number_format($o['total_amount'], 2) ?></td>
                            <td style="font-size:13px">COD</td>
                            <td><span class="badge badge-<?= $o['order_status'] ?>"><?= ucfirst($o['order_status']) ?></span></td>
                            <td><a href="<?= APP_URL ?>/orders/<?= $o['id'] ?>" class="btn btn-outline btn-sm">View Details</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div></div>
