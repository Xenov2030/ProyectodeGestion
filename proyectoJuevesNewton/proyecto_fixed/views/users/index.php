<?php // views/users/index.php ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Gestión de Usuarios</h2>
        <p class="text-muted small mb-0">Gestiona usuarios, roles y acceso al sistema.</p>
    </div>
    <?php if (in_array(\app\Core\Session::get('rol_nombre'), ['admin', 'directivo'])): ?>
        <a href="<?= url('users/create') ?>" class="btn btn-primary fw-semibold px-4 py-2 d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Nuevo Usuario
        </a>
    <?php endif; ?>
</div>

<!-- todo arreglado con bootstrap -->
<!-- le ponemos un filtro de búsqueda por rol, para que se vea prolijo y busque si es mucha cantidad de usuarios -->

<div class="mb-3">
    <ul class="nav nav-tabs border-bottom">
        <li class="nav-item">
            <a class="nav-link <?= !$rolFiltro ? 'active fw-bold' : 'text-muted' ?>" href="<?= url('users') ?>">
                Todos
            </a>
        </li>
        <?php foreach ($roles as $rol): ?>
            <li class="nav-item">
                <a class="nav-link <?= $rolFiltro === $rol['nombre'] ? 'active fw-bold' : 'text-muted' ?>"
                    href="<?= url('users?rol=' . urlencode($rol['nombre'])) ?>">
                    <?= htmlspecialchars(ucfirst($rol['nombre'])) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- card con tabla -->

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <?php if (empty($usuarios)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-people fs-2 d-block mb-2"></i>
                No hay usuarios registrados en esta categoría.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light border-bottom">
                        <tr class="text-uppercase text-muted" style="font-size: 0.72rem; letter-spacing: 0.08em;">
                            <th class="px-4 py-3 fw-semibold border-0">USUARIO</th>
                            <th class="px-4 py-3 fw-semibold border-0">EMAIL</th>
                            <th class="px-4 py-3 fw-semibold border-0 text-center">ROL</th>
                            <th class="px-4 py-3 fw-semibold border-0 text-center">ESTADO</th>
                            <th class="px-4 py-3 fw-semibold border-0 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <!-- USUARIOS -->
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center"
                                            style="width:40px; height:40px; font-size:1rem; flex-shrink:0;">
                                            <?= strtoupper(substr($user['nombre'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-semibold text-dark">
                                            <?= htmlspecialchars($user['nombre']) ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="px-4 py-3 text-muted">
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>

                                <!-- Rol -->
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary fw-semibold px-3 py-2"
                                        style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                        <?= htmlspecialchars(strtoupper($user['rol_nombre'])) ?>
                                    </span>
                                </td>

                                <!-- Estado -->
                                <td class="px-4 py-3 text-center">
                                    <?php if ($user['estado'] === 'activo'): ?>
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success fw-semibold px-3 py-2"
                                            style="font-size: 0.7rem;">
                                            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger fw-semibold px-3 py-2"
                                            style="font-size: 0.7rem;">
                                            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Acciones -->
                                <td class="px-4 py-3 text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="<?= url('users/edit?id=' . $user['id']) ?>"
                                            class="btn btn-sm btn-light border d-flex align-items-center justify-content-center"
                                            style="width:34px; height:34px; border-radius:8px;" title="Editar usuario">
                                            <i class="bi bi-pencil text-primary"></i>
                                        </a>
                                        <?php if (in_array(\app\Core\Session::get('rol_nombre'), ['admin', 'directivo'])): ?>
                                            <form action="<?= url('users/delete') ?>" method="POST"
                                                onsubmit="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer. Si cree que es una acción que requiere revertir, se sugiere desactivar el usuario en lugar de eliminarlo.')"
                                                class="d-flex">
                                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                <button type="submit"
                                                    class="btn btn-sm btn-light border d-flex align-items-center justify-content-center"
                                                    style="width:34px; height:34px; border-radius:8px;" title="Eliminar usuario">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($totalPags > 1): ?>
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                    <span class="text-muted small">
                        Página <?= $paginaAct ?> de <?= $totalPags ?>
                        · <?= $total ?> usuario<?= $total !== 1 ? 's' : '' ?>
                    </span>
                    <div class="d-flex gap-2">
                        <?php
                        $urlBase = $rolFiltro
                            ? url('users?rol=' . urlencode($rolFiltro) . '&pagina=')
                            : url('users?pagina=');
                        ?>
                        <a href="<?= $urlBase . ($paginaAct - 1) ?>"
                            class="btn btn-sm btn-light border px-3 <?= $paginaAct <= 1 ? 'disabled' : '' ?>">
                            <i class="bi bi-chevron-left"></i> Anterior
                        </a>
                        <a href="<?= $urlBase . ($paginaAct + 1) ?>"
                            class="btn btn-sm btn-primary px-3 <?= $paginaAct >= $totalPags ? 'disabled' : '' ?>">
                            Siguiente <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>