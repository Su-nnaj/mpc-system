<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="app-url" content="<?= APP_URL ?>">
    <title>Register — MPC Trading</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/public/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box" style="max-width:500px">
        <div class="auth-logo">
            <a href="<?= APP_URL ?>/" style="font-family:var(--font-display);font-size:24px;font-weight:700;color:var(--text-primary);text-decoration:none;display:flex;align-items:center;gap:8px;justify-content:center">
                <span style="background:linear-gradient(135deg,var(--accent),#00a88a);border-radius:8px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:18px">&#x1F5A5;</span>
                MPC<span style="color:var(--accent)">Trading</span>
            </a>
        </div>
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join MPC Trading — Dasmari&ntilde;as' trusted PC shop</p>

        <form action="<?= APP_URL ?>/auth/register" method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control <?= !empty($errors['full_name'])?'is-invalid':'' ?>"
                       value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" placeholder="Juan Dela Cruz" required>
                <?php if (!empty($errors['full_name'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['full_name']) ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control <?= !empty($errors['username'])?'is-invalid':'' ?>"
                           value="<?= htmlspecialchars($old['username'] ?? '') ?>" placeholder="juanpc" required>
                    <?php if (!empty($errors['username'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="09XX-XXX-XXXX">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control <?= !empty($errors['email'])?'is-invalid':'' ?>"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="you@example.com" required>
                <?php if (!empty($errors['email'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?= !empty($errors['password'])?'is-invalid':'' ?>" placeholder="Min 6 characters" required>
                    <?php if (!empty($errors['password'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?= !empty($errors['confirm_password'])?'is-invalid':'' ?>" placeholder="Repeat password" required>
                    <?php if (!empty($errors['confirm_password'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div><?php endif; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Create Account</button>
        </form>
        <p style="text-align:center;margin-top:20px;font-size:14px;color:var(--text-secondary)">
            Already have an account? <a href="<?= APP_URL ?>/auth/login" style="color:var(--accent)">Sign in</a>
        </p>
    </div>
</div>
<script src="<?= APP_URL ?>/public/js/main.js"></script>
</body></html>
