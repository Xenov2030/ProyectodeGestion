<?php
// views/auth/login.php
use app\Core\I18n;
?>

<!-- Selector de Idiomas -->
<div class="position-absolute top-0 end-0 p-3">
    <div class="dropdown">
        <button class="btn btn-sm btn-light border rounded-pill px-3 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
            <i class="bi bi-translate text-primary"></i>
            <span class="small fw-bold"><?= strtoupper(I18n::getLang()) ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
            <li><a class="dropdown-item py-2 px-3 small d-flex justify-content-between" href="<?= url('lang?lang=es') ?>">Español <span>🇪🇸</span></a></li>
            <li><a class="dropdown-item py-2 px-3 small d-flex justify-content-between" href="<?= url('lang?lang=en') ?>">English <span>🇺🇸</span></a></li>
        </ul>
    </div>
</div>

<div class="text-center mb-5">
    <div class="bg-primary bg-opacity-10 d-inline-block rounded-4 p-3 mb-3 mt-3">
        <i class="bi bi-shield-lock-fill text-primary fs-1"></i>
    </div>
    <h3 class="fw-bold text-dark mb-1"><?= I18n::t('login_title') ?></h3>
    <p class="text-secondary small"><?= I18n::t('login_subtitle') ?></p>
</div>

<?php if (isset($error) && !empty($error)): ?>
    <div class="alert alert-danger border-0 small text-center mb-4 rounded-3 py-2">
        <i class="bi bi-exclamation-circle me-2"></i> <?= $error ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'forgot_sent'): ?>
    <div class="alert alert-success border-0 small text-center mb-4 rounded-3 py-2">
        <i class="bi bi-check-circle me-2"></i> <?= I18n::t('forgot_sent') ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'request_sent'): ?>
    <div class="alert alert-success border-0 small text-center mb-4 rounded-3 py-2">
        <i class="bi bi-check-circle me-2"></i> <?= I18n::t('request_sent') ?>
    </div>
<?php endif; ?>

<form action="<?= url('login') ?>" method="POST">
    <?= \app\Core\Controller::csrf_field() ?>

    <div class="mb-3">
        <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider"><?= I18n::t('email_address') ?></label>
        <input type="email" name="email" class="form-control" placeholder="admin@gestorpro.com" required autofocus>
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label small fw-bold text-secondary text-uppercase tracking-wider m-0"><?= I18n::t('password') ?></label>
            <a href="#" class="small text-primary text-decoration-none fw-medium" data-bs-toggle="modal" data-bs-target="#forgotModal"><?= I18n::t('forgot') ?></a>
        </div>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-4">
        <?= I18n::t('sign_in') ?>
    </button>
</form>

<div class="text-center">
    <p class="text-muted small m-0">
        <?= I18n::t('no_account') ?> <a href="#" class="text-primary text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#requestModal"><?= I18n::t('contact_admin') ?></a>
    </p>
</div>

<!-- Modal Forgot Password -->
<div class="modal fade" id="forgotModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 p-3">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><?= I18n::t('forgot_title') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small"><?= I18n::t('forgot_desc') ?></p>
        <form action="<?= url('login/forgot') ?>" method="POST">
            <?= \app\Core\Controller::csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary text-uppercase"><?= I18n::t('email_address') ?></label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal"><?= I18n::t('cancel') ?></button>
                <button type="submit" class="btn btn-primary rounded-3 px-4"><?= I18n::t('send_link') ?></button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Request Account -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 p-3">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold"><?= I18n::t('request_title') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small"><?= I18n::t('request_desc') ?></p>
        <form action="<?= url('login/request') ?>" method="POST">
            <?= \app\Core\Controller::csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary text-uppercase"><?= I18n::t('your_name') ?></label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary text-uppercase"><?= I18n::t('email_address') ?></label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal"><?= I18n::t('cancel') ?></button>
                <button type="submit" class="btn btn-primary rounded-3 px-4"><?= I18n::t('send_request') ?></button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>