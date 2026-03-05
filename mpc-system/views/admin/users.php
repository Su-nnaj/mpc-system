<?php $layout = "admin"; ?>
<div class="admin-layout">
<?php include __DIR__."/../layouts/admin_sidebar.php"; ?>
<div class="admin-content">
    <h1 class="page-title">Users</h1><p class="page-subtitle">Manage registered accounts</p>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Username</th><th>Role</th><th>Status</th><th>Joined</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td class="font-mono" style="font-size:12px"><?=$u["id"]?></td>
                    <td style="font-weight:600;font-size:13px"><?=htmlspecialchars($u["full_name"])?></td>
                    <td style="font-size:13px;color:var(--text-secondary)"><?=htmlspecialchars($u["email"])?></td>
                    <td class="font-mono" style="font-size:12px">@<?=htmlspecialchars($u["username"])?></td>
                    <td><span class="badge <?=$u["role"]==="admin"?"badge-shipped":($u["role"]==="customer"?"badge-confirmed":"badge-processing")?>"><?=ucfirst(str_replace("_"," ",$u["role"]))?></span></td>
                    <td><span class="badge <?=$u["is_active"]?"badge-delivered":"badge-cancelled"?>"><?=$u["is_active"]?"Active":"Inactive"?></span></td>
                    <td style="font-size:12px;color:var(--text-muted)"><?=date("M d, Y",strtotime($u["created_at"]))?></td>
                    <td>
                        <?php if($u["id"]!==$_SESSION["user_id"]): ?>
                        <form action="<?=APP_URL?>/admin/users/toggle" method="POST" style="display:inline">
                            <input type="hidden" name="user_id" value="<?=$u["id"]?>">
                            <button type="submit" class="btn <?=$u["is_active"]?"btn-danger":"btn-outline"?> btn-sm"><?=$u["is_active"]?"Deactivate":"Activate"?></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>