<?php
// views/projects/create.php
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-white py-3 border-0">
                <h4 class="fw-bold m-0">Nuevo Proyecto</h4>
            </div>
            <div class="card-body p-4">
                <form action="/proyectos/crear" method="POST">
                    <?= \app\Core\Controller::csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Nombre del Proyecto</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Rediseño Web Corporativa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Cliente ID</label>
                            <input type="number" name="cliente_id" class="form-control" placeholder="ID Cliente" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Detalles del proyecto..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Estado Inicial</label>
                            <select name="estado" class="form-select">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="completado">Completado</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Prioridad</label>
                            <select name="prioridad" class="form-select">
                                <option value="baja">Baja</option>
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha de Fin Estimada</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between">
                            <a href="<?= url('proyectos') ?>" class="btn btn-light px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Guardar Proyecto</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>