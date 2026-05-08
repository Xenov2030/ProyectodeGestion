<?php
// views/dashboard/index.php
use app\Core\Session;
use app\Core\I18n;
?>
<div class="row g-4">
    <!-- Stat Cards -->
    <div class="col-md-4">
        <div class="card border-0 p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            <div class="position-relative z-1">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-white bg-opacity-20 rounded-3 p-2">
                        <i class="bi bi-layers text-white fs-4"></i>
                    </div>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3">Live</span>
                </div>
                <h6 class="text-white text-opacity-75 small fw-bold mb-1 text-uppercase letter-spacing-1"><?= I18n::t('projects') ?></h6>
                <h3 class="text-white fw-bold mb-2"><?= I18n::t('active_tasks') ?></h3>
                <p class="text-white text-opacity-50 small mb-0"><?= I18n::t('click_to_manage') ?></p>
            </div>
            <a href="<?= url('proyectos') ?>" class="stretched-link"></a>
            <!-- Decoration -->
            <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.1;">
                <i class="bi bi-layers text-white" style="font-size: 120px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 p-4 h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
            <div class="position-relative z-1">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-white bg-opacity-20 rounded-3 p-2">
                        <i class="bi bi-headset text-white fs-4"></i>
                    </div>
                </div>
                <h6 class="text-white text-opacity-75 small fw-bold mb-1 text-uppercase letter-spacing-1"><?= I18n::t('tickets') ?></h6>
                <h3 class="text-white fw-bold mb-2"><?= I18n::t('support_box') ?></h3>
                <p class="text-white text-opacity-50 small mb-0"><?= I18n::t('check_pending') ?></p>
            </div>
            <a href="<?= url('tickets') ?>" class="stretched-link"></a>
            <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.1;">
                <i class="bi bi-headset text-white" style="font-size: 120px;"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 p-4 h-100 position-relative overflow-hidden" style="background: #ffffff;">
            <div class="position-relative z-1">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-person-circle text-primary fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted small fw-bold mb-1 text-uppercase letter-spacing-1"><?= I18n::t('welcome') ?></h6>
                <h3 class="text-dark fw-bold mb-2"><?= Session::get('user_name') ?></h3>
                <p class="text-muted small mb-0"><?= I18n::t('role') ?>: <span class="badge bg-light text-dark border"><?= strtoupper(Session::get('rol_nombre')) ?></span></p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-12 mt-4">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><?= I18n::t('recent_activity') ?></h5>
                <button class="btn btn-sm btn-light border rounded-pill px-3"><?= I18n::t('projects') ?></button>
            </div>
            
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                <small><?= I18n::t('no_recent_activity') ?? 'No hay actividad reciente para mostrar' ?></small>
            </div>
        </div>
    </div>
</div>