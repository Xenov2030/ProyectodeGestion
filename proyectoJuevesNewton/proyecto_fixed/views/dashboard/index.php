<?php
// views/dashboard/index.php
use app\Core\Session;
use app\Core\I18n;
use app\Models\Project;

$userId = Session::get('user_id');
$rolNombre = Session::get('rol_nombre');
$empresaId = Session::get('empresa_id');
?>
<div class="row g-4">
    <?php if (Session::get('rol_nombre') === 'cliente'): ?>
        <!-- Vista para Clientes -->
        <div class="col-md-4">
            <div class="card border-0 p-4 h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <div class="position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 p-2" style="background-color: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-plus-circle text-white fs-4" style="line-height: 1;"></i>
                        </div>
                    </div>
                    <h6 class="text-white text-opacity-75 small fw-bold mb-1 text-uppercase letter-spacing-1">
                        <?= I18n::t('projects') ?>
                    </h6>
                    <h3 class="text-white fw-bold mb-2"><?= I18n::t('request_project') ?></h3>
                    <p class="text-white text-opacity-50 small mb-0"><?= I18n::t('send_request_desc') ?></p>
                </div>
                <a href="<?= url('proyectos/crear') ?>" class="stretched-link"></a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 p-4 h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                <div class="position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 p-2" style="background-color: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-headset text-white fs-4" style="line-height: 1;"></i>
                        </div>
                    </div>
                    <h6 class="text-white text-opacity-75 small fw-bold mb-1 text-uppercase letter-spacing-1">
                        <?= I18n::t('tickets') ?>
                    </h6>
                    <h3 class="text-white fw-bold mb-2"><?= I18n::t('request_ticket') ?></h3>
                    <p class="text-white text-opacity-50 small mb-0"><?= I18n::t('request_ticket_desc') ?></p>
                </div>
                <a href="<?= url('tickets/crear') ?>" class="stretched-link"></a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 p-4 h-100 shadow-sm" style="background: #ffffff;">
                <div class="position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                            <i class="bi bi-chat-left-dots text-primary fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold mb-1 text-uppercase letter-spacing-1"><?= I18n::t('chat') ?></h6>
                    <h3 class="text-dark fw-bold mb-2"><?= I18n::t('my_messages') ?></h3>
                    <p class="text-muted small mb-0"><?= I18n::t('check_chats_desc') ?></p>
                </div>
                <a href="<?= url('chat') ?>" class="stretched-link"></a>
            </div>
        </div>
    <?php else: ?>
        <!-- Vista para Staff / Admin (Original) -->
        <div class="col-md-4">
            <div class="card border-0 p-4 h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <div class="position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 p-2" style="background-color: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-layers text-white fs-4" style="line-height: 1;"></i>
                        </div>
                        <span class="badge rounded-pill px-3"
                            style="background-color: rgba(255, 255, 255, 0.2); color: white;">Live</span>
                    </div>
                    <h6 class="text-white text-opacity-75 small fw-bold mb-1 text-uppercase letter-spacing-1">
                        <?= I18n::t('projects') ?>
                    </h6>
                    <h3 class="text-white fw-bold mb-2"><?= I18n::t('active_tasks') ?></h3>
                    <p class="text-white text-opacity-50 small mb-0"><?= I18n::t('click_to_manage') ?></p>
                </div>
                <a href="<?= url('proyectos') ?>" class="stretched-link"></a>
                <div class="position-absolute" style="bottom: -20px; right: -20px; opacity: 0.1;">
                    <i class="bi bi-layers text-white" style="font-size: 120px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 p-4 h-100 position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                <div class="position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 p-2" style="background-color: rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-headset text-white fs-4" style="line-height: 1;"></i>
                        </div>
                    </div>
                    <h6 class="text-white text-opacity-75 small fw-bold mb-1 text-uppercase letter-spacing-1">
                        <?= I18n::t('tickets') ?>
                    </h6>
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
                    <p class="text-muted small mb-0"><?= I18n::t('role') ?>: <span
                            class="badge bg-light text-dark border"><?= strtoupper(Session::get('rol_nombre')) ?></span></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (Session::get('rol_nombre') !== 'cliente'): ?>

        <!-- Tarjetas de resumen -->
        <div class="col-12">
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people text-primary fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">Usuarios activos</div>
                                <div class="fw-bold fs-4"><?= $totalUsuarios ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-layers text-success fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">Proyectos</div>
                                <div class="fw-bold fs-4"><?= $totalProyectos ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-ticket text-warning fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">Tickets abiertos</div>
                                <div class="fw-bold fs-4"><?= $ticketsAbiertos ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-secondary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-check-circle text-secondary fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">Tickets cerrados</div>
                                <div class="fw-bold fs-4"><?= $ticketsCerrados ?></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Gráfico usuarios por rol -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold mb-4">Distribución de usuarios por rol</h6>
                <div class="d-flex justify-content-center">
                    <canvas id="graficoRoles" style="max-height: 260px;"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('graficoRoles').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_column($usuariosPorRol, 'nombre')) ?>,
                    datasets: [{
                        data: <?= json_encode(array_column($usuariosPorRol, 'total')) ?>,
                        backgroundColor: [
                            '#6366f1', '#10b981', '#f59e0b', '#3b82f6', '#ef4444'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        </script>

    <?php endif; ?>

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