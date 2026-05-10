<?php // views/users/create.php ?>

<div class="d-flex align-items-center gap-4 mb-4">
    <a href="<?= url('users') ?>"
       class="btn btn-sm btn-light border d-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    <div>
        <h2 class="fw-bold mb-0">Nuevo Usuario</h2>
        <p class="text-muted small mb-0">Complete la información para crear un nuevo usuario.</p>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger rounded-3 border-0 mb-4">
        <i class="bi bi-exclamation-circle me-2"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form action="<?= url('users/create') ?>" method="POST">
    <?= \app\Core\Controller::csrf_field() ?>

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
                        <input type="text" name="nombre" class="form-control rounded-3"
                               placeholder="ej. María Pérez" required
                               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control rounded-3"
                               placeholder="ej. mail@empresa.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Teléfono <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <input type="text" name="telefono" class="form-control rounded-3"
                               placeholder="ej. +54 9 261 000-0000"
                               value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control rounded-3"
                               placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Confirmar contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirm" class="form-control rounded-3"
                               placeholder="Repetir contraseña" required>
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
                        <label class="form-label fw-semibold small">Rol <span class="text-danger">*</span></label>
                        <select name="rol_id" class="form-select rounded-3" required>
                            <option value="" disabled selected>Seleccione un rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>"
                                    <?= (isset($_POST['rol_id']) && $_POST['rol_id'] == $rol['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($rol['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Estado inicial</label>
                        <select name="estado" class="form-select rounded-3">
                            <option value="activo"   <?= ($_POST['estado'] ?? 'activo') === 'activo'   ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= ($_POST['estado'] ?? '')        === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <!-- Nota informativa -->
                    <div class="alert alert-light border rounded-3 small text-muted mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        El usuario se asociará automáticamente a tu empresa. Si sos administrador global, <code>empresa_id</code> quedará en NULL.
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
            <i class="bi bi-person-plus me-2"></i> Crear Usuario
        </button>
    </div>
</form>