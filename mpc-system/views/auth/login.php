<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?= APP_URL ?>">
    <title>Login — MPC Trading</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">
            <a href="<?= APP_URL ?>/" style="font-family:var(--font-display);font-size:24px;font-weight:700;color:var(--text-primary);text-decoration:none;display:flex;align-items:center;gap:8px;justify-content:center">
                <span style="background:linear-gradient(135deg,var(--accent),#00a88a);border-radius:8px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:18px">&#x1F5A5;</span>
                MPC<span style="color:var(--accent)">Trading</span>
            </a>
        </div>
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Sign in to your MPC Trading account</p>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($flash['message']) ?></div>
        <?php endif; ?>

        <form action="<?= APP_URL ?>/auth/login" method="POST">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($email ?? '') ?>" placeholder="you@example.com" required autofocus>
                <?php if (!empty($errors['email'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                       placeholder="Enter your password" required>
                <?php if (!empty($errors['password'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
        </form>

        <p style="text-align:center;margin-top:20px;font-size:14px;color:var(--text-secondary)">
            No account? <a href="<?= APP_URL ?>/auth/register" style="color:var(--accent)">Create one free</a>
        </p>
        <div style="margin-top:20px;padding:14px;background:var(--bg-surface);border-radius:var(--radius);border:1px solid var(--border);font-size:12px;color:var(--text-muted)">
            <strong style="color:var(--text-secondary);display:block;margin-bottom:6px">Demo Accounts (password: password):</strong>
            admin@mpctrading.com &bull; staff@mpctrading.com &bull; customer@example.com
        </div>
    </div>
</div>
<script src="<?= APP_URL ?>/public/js/main.js"></script>
</body></html>
