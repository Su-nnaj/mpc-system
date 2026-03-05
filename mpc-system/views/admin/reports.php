<?php $layout = "admin"; ?>
<div class="admin-layout">
<?php include __DIR__."/../layouts/admin_sidebar.php"; ?>
<div class="admin-content">
    <h1 class="page-title">Sales Reports</h1>
    <p class="page-subtitle">Business analytics and insights</p>

    <div class="grid grid-2" style="margin-bottom:28px">
        <div class="card">
            <div class="card-header"><strong>Monthly Revenue</strong></div>
            <div class="table-wrapper" style="border:none;border-radius:0">
                <table>
                    <thead><tr><th>Month</th><th>Orders</th><th>Revenue</th></tr></thead>
                    <tbody>
                        <?php foreach($salesByMonth as $row): ?>
                        <tr>
                            <td class="font-mono" style="font-size:13px"><?=htmlspecialchars($row["month"])?></td>
                            <td><?=$row["orders"]?></td>
                            <td class="font-mono" style="color:var(--accent)">PHP <?=number_format($row["revenue"],2)?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><strong>Top Selling Products</strong></div>
            <div class="table-wrapper" style="border:none;border-radius:0">
                <table>
                    <thead><tr><th>Product</th><th>Sold</th><th>Revenue</th></tr></thead>
                    <tbody>
                        <?php foreach($topProducts as $p): ?>
                        <tr>
                            <td style="font-size:13px"><?=htmlspecialchars(substr($p["name"],0,35))?></td>
                            <td><?=$p["sold"]?></td>
                            <td class="font-mono" style="font-size:13px;color:var(--accent)">PHP <?=number_format($p["revenue"],2)?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>