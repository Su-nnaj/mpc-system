<?php $layout = "admin"; ?>
<div class="admin-layout">
<?php include __DIR__."/../layouts/admin_sidebar.php"; ?>
<div class="admin-content">
    <div class="flex-between mb-4">
        <div><h1 class="page-title">Orders</h1><p class="page-subtitle">Manage all customer orders</p></div>
    </div>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash["type"]==="success"?"success":"error"?> alert-auto"><?=htmlspecialchars($flash["message"])?></div><?php endif; ?>

    <div class="card card-body" style="margin-bottom:16px">
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <?php foreach(["","pending","confirmed","processing","shipped","delivered","cancelled"] as $s): ?>
                <a href="<?=APP_URL?>/admin/orders<?=$s?"?status=$s":""?>" class="badge <?=($status??"")===$s?"badge-delivered":"" ?>" style="padding:6px 14px;border:1px solid var(--border);font-size:13px;font-weight:500"><?=$s?ucfirst($s):"All"?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead><tr><th>Order #</th><th>Customer</th><th>Phone</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td><span class="font-mono" style="font-size:12px;color:var(--accent)"><?=htmlspecialchars($o["order_number"])?></span></td>
                    <td><div style="font-size:13px;font-weight:600"><?=htmlspecialchars($o["full_name"])?></div><div style="font-size:11px;color:var(--text-muted)"><?=htmlspecialchars($o["email"])?></div></td>
                    <td style="font-size:13px"><?=htmlspecialchars($o["phone"])?></td>
                    <td class="font-mono" style="font-size:13px;color:var(--accent)">PHP <?=number_format($o["total_amount"],2)?></td>
                    <td style="font-size:12px">COD</td>
                    <td><span class="badge badge-<?=$o["order_status"]?>"><?=ucfirst($o["order_status"])?></span></td>
                    <td style="font-size:12px;color:var(--text-muted)"><?=date("M d, Y",strtotime($o["created_at"]))?></td>
                    <td>
                        <button onclick="document.getElementById('upd-<?=$o["id"]?>').classList.add('show')" class="btn btn-outline btn-sm">Update</button>
                    </td>
                </tr>
                <!-- Update Modal -->
                <tr style="display:none" id="upd-row-<?=$o["id"]?>"></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<?php foreach($orders as $o): ?>
<div class="modal-overlay" id="upd-<?=$o["id"]?>">
    <div class="modal">
        <div class="modal-header"><h3 class="modal-title">Update Order <?=htmlspecialchars($o["order_number"])?></h3><button class="modal-close">&times;</button></div>
        <form action="<?=APP_URL?>/admin/orders/update" method="POST">
            <div class="modal-body">
                <input type="hidden" name="order_id" value="<?=$o["id"]?>">
                <div class="form-group"><label class="form-label">Order Status</label>
                    <select name="order_status" class="form-control">
                        <?php foreach(["pending","confirmed","processing","shipped","delivered","cancelled"] as $s): ?>
                            <option value="<?=$s?>" <?=$o["order_status"]===$s?"selected":""?>><?=ucfirst($s)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Staff Notes</label><textarea name="staff_notes" class="form-control"><?=htmlspecialchars($o["staff_notes"]??"")?></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline modal-close">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>