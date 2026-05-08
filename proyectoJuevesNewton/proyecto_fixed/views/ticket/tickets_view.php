<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Módulo de Tickets</h2>
        <?php if (!in_array($_SESSION['rol'], ['prospecto'])): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoTicket">Nuevo Ticket</button>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'archivo_invalido'): ?>
        <div class="alert alert-danger">Error: Solo se permiten archivos PDF.</div>
    <?php endif; ?>

    <table class="table table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>N° Ticket</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Adjunto</th>
                <th>Asignado a</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $t): ?>
            <tr>
                <td>#<?php echo $t['id_ticket']; ?></td>
                <td><?php echo $t['titulo']; ?></td>
                <td><span class="badge bg-info"><?php echo $t['estado']; ?></span></td>
                <td>
                    <?php if (!empty($t['adjunto'])): ?>
                        <a href="<?php echo htmlspecialchars($t['adjunto']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver PDF</a>
                    <?php else: ?>
                        <span class="text-muted">Sin archivo</span>
                    <?php endif; ?>
                </td>
                <td>ID: <?php echo $t['asignado_a'] ?? 'Sin asignar'; ?></td>
                <td>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Ver Seguimiento</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="nuevoTicket" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?action=guardar" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Trámite / Ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="titulo" class="form-control mb-2" placeholder="Título del problema" required>
          <textarea name="descripcion" class="form-control mb-2" placeholder="Descripción detallada"></textarea>
          <input type="file" name="adjunto" class="form-control" accept=".pdf" required>
          <small class="text-muted">Solo se permiten archivos PDF (Módulo 2)</small>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Generar Ticket</button>
        </div>
      </form>
    </div>
  </div>
</div>