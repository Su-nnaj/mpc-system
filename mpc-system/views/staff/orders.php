<?php $layout = "admin"; ?>
<div class="admin-layout">
<aside class="admin-sidebar">
    <div class="nav-section">
        <div class="nav-section-title">Staff Menu</div>
        <a href="<?=APP_URL?>/staff/dashboard" class="admin-nav-link">Dashboard</a>
        <a href="<?=APP_URL?>/staff/orders" class="admin-nav-link active">Orders</a>
    </div>
    <div class="nav-section" style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border)">
        <a href="<?=APP_URL?>/" class="admin-nav-link">Back to Shop</a>
        <a href="<?=APP_URL?>/auth/logout" class="admin-nav-link" style="color:var(--red)">Logout</a>
    </div>
</aside>
<div class="admin-content">
    <h1 class="page-title">All Orders</h1>
    <?php if(!empty($flash)): ?><div class="alert alert-<?=$flash['type']==='success'?'success':'error'?> alert-auto"><?=htmlspecialchars($flash['message'])?></div><?php endif; ?>
    <div class="card card-body" style="margin-bottom:16px">
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <?php foreach([''=>'All','pending'=>'Pending','confirmed'=>'Confirmed','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'] as $s=>$lbl): ?>
                <a href="<?=APP_URL?>/staff/orders<?=$s?"?status=$s":""?>" class="btn <?=($status??'')===$s?'btn-primary':'btn-outline'?> btn-sm"><?=$lbl?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Order #</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th><th>Update</th></tr></thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td><span class="font-mono" style="font-size:12px;color:var(--accent)"><?=htmlspecialchars($o['order_number'])?></span></td>
                    <td style="font-size:13px;font-weight:600"><?=htmlspecialchars($o['full_name'])?></td>
                    <td style="font-size:13px"><?=htmlspecialchars($o['phone'])?></td>
                    <td class="font-mono" style="font-size:13px;color:var(--accent)">PHP <?=number_format($o['total_amount'],2)?></td>
                    <td><span class="badge badge-<?=$o['order_status']?>"><?=ucfirst($o['order_status'])?></span></td>
                    <td style="font-size:12px;color:var(--text-muted)"><?=date('M d, Y',strtotime($o['created_at']))?></td>
                    <td>
                        <form action="<?=APP_URL?>/staff/orders/update" method="POST" style="display:flex;gap:6px">
                            <input type="hidden" name="order_id" value="<?=$o['id']?>">
                            <select name="order_status" class="form-control" style="font-size:12px;padding:4px;width:110px">
                                <?php foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s): ?>
                                    <option value="<?=$s?>" <?=$o['order_status']===$s?'selected':''?>><?=ucfirst($s)?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
