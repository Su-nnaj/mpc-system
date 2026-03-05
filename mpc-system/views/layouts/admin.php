<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?=APP_URL?>">
    <title><?=htmlspecialchars($title??APP_NAME)?> — Admin</title>
    <link rel="stylesheet" href="<?=APP_URL?>/public/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-inner">
        <a href="<?=APP_URL?>/admin/dashboard" class="navbar-brand">
            <div class="logo-icon">&#x1F5A5;</div> MPC<span>Admin</span>
        </a>
        <div class="navbar-end" style="margin-left:auto">
            <span style="font-size:13px;color:var(--text-secondary)">Logged in as <strong style="color:var(--text-primary)"><?=htmlspecialchars($_SESSION["full_name"]??"")?></strong></span>
            <a href="<?=APP_URL?>/auth/logout" class="btn btn-outline btn-sm">Logout</a>
        </div>
    </div>
</nav>
<main><?=$content?></main>
<script src="<?=APP_URL?>/public/js/main.js"></script>
</body></html>