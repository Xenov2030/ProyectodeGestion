<?php
// views/auth/login.php
?>
<div class="text-center mb-5">
    <div class="bg-primary bg-opacity-10 d-inline-block rounded-4 p-3 mb-3">
        <i class="bi bi-shield-lock-fill text-primary fs-1"></i>
    </div>
    <h3 class="fw-bold text-dark mb-1">GESTOR PRO</h3>
    <p class="text-secondary small">Log in to your account to continue</p>
</div>

<?php if (isset($error) && !empty($error)): ?>
    <div class="alert alert-danger border-0 small text-center mb-4 rounded-3 py-2">
        <i class="bi bi-exclamation-circle me-2"></i> <?= $error ?>
    </div>
<?php endif; ?>

<form action="<?= url('login') ?>" method="POST">
    <?= \app\Core\Controller::csrf_field() ?>

    <div class="mb-3">
        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="admin@gestorpro.com" required autofocus>
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider m-0">Password</label>
            <a href="#" class="small text-primary text-decoration-none fw-medium">Forgot?</a>
        </div>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-4">
        Sign In
    </button>
</form>

<div class="text-center">
    <p class="text-muted small m-0">
        Don't have an account? <a href="#" class="text-primary text-decoration-none fw-bold">Contact Admin</a>
    </p>
</div>