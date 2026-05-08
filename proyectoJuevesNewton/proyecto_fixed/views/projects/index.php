<?php
// views/projects/index.php
use app\Core\Session;
$rol = Session::get('rol_nombre');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold m-0">Proyectos</h2>
        <p class="text-muted small mb-0">Gestión de tareas y flujo de trabajo</p>
    </div>
    <?php if ($rol !== 'cliente'): ?>
        <a href="<?= url('proyectos/crear') ?>" class="btn text-white fw-bold px-4 py-2 border-0 shadow-sm" 
           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
            <i class="bi bi-plus-lg me-2"></i> NUEVO PROYECTO
        </a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <?php if (empty($proyectos)): ?>
            <div class="p-5 text-center text-muted">No hay proyectos activos actualmente.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle m-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <tr>
                            <th class="py-4 ps-4 border-0">NOMBRE</th>
                            <th class="py-4 border-0">CLIENTE</th>
                            <th class="py-4 border-0">ESTADO</th>
                            <th class="py-4 border-0">PRIORIDAD</th>
                            <th class="py-4 pe-4 text-end border-0">GESTIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $p): ?>
                        <tr class="border-top" style="border-color: rgba(0,0,0,0.03) !important;">
                            <td class="py-4 ps-4 fw-bold text-dark"><?= htmlspecialchars($p['nombre']) ?></td>
                            <td class="py-4 text-muted"><?= htmlspecialchars($p['cliente_nombre']) ?></td>
                            <td class="py-4">
                                <?php 
                                    $styles = match($p['estado']) {
                                        'finalizado' => 'background: #e6fffa; color: #234e52;',
                                        'en_progreso' => 'background: #ebf8ff; color: #2c5282;',
                                        'pendiente' => 'background: #fffaf0; color: #744210;',
                                        default => 'background: #edf2f7; color: #2d3748;'
                                    };
                                ?>
                                <span class="badge px-3 py-2 rounded-pill text-uppercase" style="<?= $styles ?>; font-size: 0.7rem;">
                                    <?= str_replace('_', ' ', $p['estado']) ?>
                                </span>
                            </td>
                            <td class="py-4 text-muted">
                                <span class="opacity-75"><?= ucfirst($p['prioridad']) ?></span>
                            </td>
                            <td class="py-4 pe-4 text-end">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <button class="btn btn-white border-0 py-2"><i class="bi bi-eye text-primary"></i></button>
                                    <button class="btn btn-white border-0 py-2"><i class="bi bi-pencil text-secondary"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>