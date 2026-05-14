<?php
use app\Core\I18n;
$isCliente = \app\Core\Session::get('rol_nombre') === 'cliente';
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="m-0 fw-bold"><?= $isCliente ? I18n::t('request_support') : I18n::t('new_ticket') ?></h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= url('tickets/crear') ?>" method="POST">
                    <?= \app\Core\Controller::csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Proyecto Asociado (Opcional)</label>
                        <select name="proyecto_id" class="form-select">
                            <option value="">-- No asociar a un proyecto --</option>
                            <?php foreach ($proyectos as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Título del Ticket</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ej: Error al cargar facturas" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Prioridad</label>
                        <select name="prioridad" class="form-select">
                            <option value="baja">Baja</option>
                            <option value="media" selected>Media</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Crítica / Urgente</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Descripción del problema</label>
                        <textarea name="descripcion" class="form-control" rows="5" placeholder="Describe detalladamente el inconveniente..." required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm"><?= $isCliente ? I18n::t('send_request_btn') : I18n::t('send') ?></button>
                        <a href="<?= url('tickets') ?>" class="btn btn-light"><?= I18n::t('cancel') ?></a>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-3 text-center text-muted small">
            Nuestros técnicos responderán a su solicitud a la brevedad posible.
        </div>
    </div>
</div>
