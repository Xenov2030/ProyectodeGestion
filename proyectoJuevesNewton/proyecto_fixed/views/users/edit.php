<?php // views/users/edit.php ?>

<div class="d-flex align-items-center gap-4 mb-4">
    <a href="<?= url('users') ?>"
       class="btn btn-sm btn-light border d-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    <div>
        <h2 class="fw-bold mb-0">Editar Usuario</h2>
        <p class="text-muted small mb-0">
            Modificando información de
            <strong><?= htmlspecialchars($usuario['nombre']) ?></strong>
        </p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger rounded-3 border-0 mb-4">
        <i class="bi bi-exclamation-circle me-2"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="<?= url('users/edit?id=' . $usuario['id']) ?>" method="POST">
    <?= \app\Core\Controller::csrf_field() ?>
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

    <div class="row g-4">

        <!-- Columna izquierda -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-4"
                        style="font-size:0.7rem; letter-spacing:0.1em;">
                        Datos personales
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre completo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control rounded-3" required
                               value="<?= htmlspecialchars($usuario['nombre']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3" required
                               value="<?= htmlspecialchars($usuario['email']) ?>">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">
                            Teléfono <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <input type="text" name="telefono" class="form-control rounded-3"
                               placeholder="+54 9 261 000-0000"
                               value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-4"
                        style="font-size:0.7rem; letter-spacing:0.1em;">
                        Acceso y permisos
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Nueva Contraseña
                            <span class="text-muted fw-normal">(dejar vacío para mantener la actual)</span>
                        </label>
                        <input type="password" name="password" class="form-control rounded-3"
                               placeholder="Solo completar si desea cambiarla">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Rol <span class="text-danger">*</span></label>
                        <select name="rol_id" class="form-select rounded-3" required>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>"
                                    <?= $usuario['rol_id'] == $rol['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($rol['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Estado</label>
                        <select name="estado" class="form-select rounded-3">
                            <option value="activo"   <?= $usuario['estado'] === 'activo'   ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones -->
    <div class="d-flex justify-content-end align-items-center gap-3 mt-4">
        <a href="<?= url('users') ?>" class="btn btn-light border px-4">
            Cancelar
        </a>
        <button type="submit" class="btn btn-primary fw-semibold px-5">
            <i class="bi bi-check-lg me-2"></i> Guardar Cambios
        </button>
    </div>
</form>