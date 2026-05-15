<?php
use app\Core\I18n;
$isCliente = \app\Core\Session::get('rol_nombre') === 'cliente';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-white py-3 border-0">
                <h4 class="fw-bold m-0"><?= $isCliente ? I18n::t('request_project') : I18n::t('new_project') ?></h4>
            </div>
            <div class="card-body p-4">
                <form action="<?= url('proyectos/crear') ?>" method="POST">
                    <?= \app\Core\Controller::csrf_field() ?>

                    <div class="row g-3">
                        <div class="<?= $isCliente ? 'col-12' : 'col-md-8' ?>">
                            <label class="form-label small fw-bold"><?= I18n::t('project_name') ?></label>
                            <input type="text" name="nombre" class="form-control" placeholder="..." required>
                        </div>
                        <?php if (!$isCliente): ?>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Cliente ID</label>
                            <select name="cliente_id" class="form-select">
                                <?php
                                // cantidad de proyectos en BD (por empresa si aplica)
                                $empresaId = \app\Core\Session::get('empresa_id');
                                $db = \app\Core\Database::getInstancia();
                                $sql = "SELECT COUNT(*) as total FROM proyectos";
                                $params = [];
                                if (!empty($empresaId)) {
                                    $sql .= " WHERE empresa_id = :empresa_id";
                                    $params['empresa_id'] = (int)$empresaId;
                                }
                                $stmt = $db->prepare($sql);
                                $stmt->execute($params);
                                $totalProyectos = (int)$stmt->fetchColumn();
                                // se usa para poblar opciones dinámicamente, ejemplo: 1..N (si N>=1)
                                $max = max(1, $totalProyectos);
                                for ($i = 1; $i <= $max; $i++):
                                ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <label class="form-label small fw-bold"><?= I18n::t('project_desc') ?></label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="..."></textarea>
                        </div>

                        <?php if (!$isCliente): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Estado Inicial</label>
                            <select name="estado" class="form-select">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="completado">Completado</option>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= I18n::t('priority') ?></label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= I18n::t('start_date') ?></label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><?= I18n::t('end_date') ?></label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between">
                            <a href="<?= url('proyectos') ?>" class="btn btn-light px-4"><?= I18n::t('cancel') ?></a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm"><?= $isCliente ? I18n::t('send_request_btn') : I18n::t('users_save') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>