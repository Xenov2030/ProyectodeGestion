<?php
// views/tickets/index.php
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold m-0">Tickets</h2>
        <p class="text-muted small mb-0">Centro de soporte y asistencia</p>
    </div>
    <a href="<?= url('tickets/crear') ?>" class="btn text-white fw-bold px-4 py-2 border-0 shadow-sm" 
       style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #000 !important; border-radius: 10px;">
        <i class="bi bi-chat-dots me-2"></i> NUEVO TICKET
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <?php if (empty($tickets)): ?>
            <div class="p-5 text-center text-muted">No tienes tickets abiertos.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle m-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <tr>
                            <th class="py-4 ps-4 border-0">TÍTULO / PROYECTO</th>
                            <th class="py-4 border-0">ESTADO</th>
                            <th class="py-4 border-0">PRIORIDAD</th>
                            <th class="py-4 pe-4 text-end border-0">CREACIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $t): ?>
                        <tr class="border-top" style="border-color: rgba(0,0,0,0.03) !important;">
                            <td class="py-4 ps-4">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($t['titulo']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($t['proyecto_nombre'] ?? 'General') ?></div>
                            </td>
                            <td class="py-4">
                                <?php 
                                    $styles = match($t['estado']) {
                                        'abierto' => 'background: #e6fffa; color: #234e52;',
                                        'cerrado' => 'background: #fff5f5; color: #c53030;',
                                        default => 'background: #ebf8ff; color: #2c5282;'
                                    };
                                ?>
                                <span class="badge px-3 py-2 rounded-pill text-uppercase" style="<?= $styles ?>; font-size: 0.7rem;">
                                    <?= $t['estado'] ?>
                                </span>
                            </td>
                            <td class="py-4 text-muted">
                                <span class="opacity-75"><?= ucfirst($t['prioridad']) ?></span>
                            </td>
                            <td class="py-4 pe-4 text-end text-muted small">
                                <?= date('d M, Y', strtotime($t['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
