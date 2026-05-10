<?php
// views/auditlog/index.php
// Vista del Registro de Auditoría.
// Muestra tarjetas de resumen, filtros y la tabla de eventos.
use app\Core\Session;
use app\Core\I18n;

// Datos que vienen del controlador
$registros    = $registros    ?? [];
$total        = $total        ?? 0;
$pagina       = $pagina       ?? 1;
$porPagina    = $porPagina    ?? 8;
$filtros      = $filtros      ?? [];
$estadisticas = $estadisticas ?? ['total' => 0, 'criticos' => 0, 'fallidos' => 0, 'activos' => 0];
$acciones     = $acciones     ?? [];

$totalPaginas = max(1, ceil($total / $porPagina));
$inicio       = ($pagina - 1) * $porPagina + 1;
$fin          = min($pagina * $porPagina, $total);

// Colores para las badges de acción
$accionColores = [
    'Login'    => 'badge-accion-login',
    'Crear'    => 'badge-accion-crear',
    'Editar'   => 'badge-accion-editar',
    'Eliminar' => 'badge-accion-eliminar',
    'Exportar' => 'badge-accion-exportar',
];

// Colores para los estados
$estadoConfig = [
    'ok'      => ['clase' => 'audit-estado-ok',      'icono' => 'bi-check-circle-fill',   'texto' => 'OK'],
    'aviso'   => ['clase' => 'audit-estado-aviso',    'icono' => 'bi-exclamation-triangle-fill', 'texto' => I18n::t('audit_warning')],
    'fallido' => ['clase' => 'audit-estado-fallido',  'icono' => 'bi-x-circle-fill',       'texto' => I18n::t('audit_failed')],
];

// Colores para los avatares (se asigna según las iniciales)
$avatarColores = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];
?>

<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock text-primary"></i>
            <?= I18n::t('audit_title') ?>
        </h2>
        <p class="text-muted mb-0 small"><?= I18n::t('audit_subtitle') ?></p>
    </div>
    <a href="<?= url('auditlog/exportar') ?>" class="btn btn-dark rounded-pill px-4 py-2 d-flex align-items-center gap-2 fw-bold text-decoration-none audit-btn-export">
        <i class="bi bi-download"></i>
        <?= I18n::t('audit_export') ?>
    </a>
</div>

<!-- Tarjetas de Estadísticas (clickeables = filtran la tabla) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <a href="<?= url('auditlog') ?>" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 audit-stat-card audit-stat-clickable">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="audit-stat-dot" style="background: #10b981;"></span>
                    <span class="text-muted small fw-semibold"><?= I18n::t('audit_total_events') ?></span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?= number_format($estadisticas['total']) ?></h3>
                <span class="text-muted small"><?= I18n::t('audit_last_24h') ?></span>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= url('auditlog?accion=Eliminar') ?>" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 audit-stat-card audit-stat-clickable">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="audit-stat-dot" style="background: #ef4444;"></span>
                    <span class="text-muted small fw-semibold"><?= I18n::t('audit_critical') ?></span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?= number_format($estadisticas['criticos']) ?></h3>
                <span class="text-muted small"><?= I18n::t('audit_need_review') ?></span>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= url('auditlog?estado=fallido') ?>" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 audit-stat-card audit-stat-clickable">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="audit-stat-dot" style="background: #f59e0b;"></span>
                    <span class="text-muted small fw-semibold"><?= I18n::t('audit_failed_attempts') ?></span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?= number_format($estadisticas['fallidos']) ?></h3>
                <span class="text-muted small">Login / <?= I18n::t('audit_access') ?></span>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?= url('auditlog?accion=Login&estado=ok') ?>" class="text-decoration-none">
            <div class="card border-0 p-3 h-100 audit-stat-card audit-stat-clickable">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="audit-stat-dot" style="background: #6366f1;"></span>
                    <span class="text-muted small fw-semibold"><?= I18n::t('audit_active_users') ?></span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?= number_format($estadisticas['activos']) ?></h3>
                <span class="text-muted small"><?= I18n::t('audit_sessions_today') ?></span>
            </div>
        </a>
    </div>
</div>

<!-- Filtros -->
<form method="GET" action="<?= url('auditlog') ?>" id="audit-filters-form">
    <div class="row g-3 mb-4">
        <!-- Búsqueda -->
        <div class="col-12">
            <div class="audit-filter-input">
                <i class="bi bi-search text-muted"></i>
                <input type="text" name="busqueda" class="form-control border-0 bg-transparent"
                       placeholder="<?= I18n::t('audit_search_placeholder') ?>"
                       value="<?= htmlspecialchars($filtros['busqueda'] ?? '') ?>">
            </div>
        </div>
        <!-- Filtro por acción -->
        <div class="col-12 col-md-4">
            <select name="accion" class="form-select audit-filter-select" onchange="this.form.submit()">
                <option value=""><?= I18n::t('audit_all_actions') ?></option>
                <?php foreach ($acciones as $acc): ?>
                    <option value="<?= htmlspecialchars($acc) ?>" <?= ($filtros['accion'] ?? '') === $acc ? 'selected' : '' ?>>
                        <?= htmlspecialchars($acc) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Filtro por estado -->
        <div class="col-12 col-md-4">
            <select name="estado" class="form-select audit-filter-select" onchange="this.form.submit()">
                <option value=""><?= I18n::t('audit_all_statuses') ?></option>
                <option value="ok"      <?= ($filtros['estado'] ?? '') === 'ok'      ? 'selected' : '' ?>>OK</option>
                <option value="aviso"   <?= ($filtros['estado'] ?? '') === 'aviso'   ? 'selected' : '' ?>><?= I18n::t('audit_warning') ?></option>
                <option value="fallido" <?= ($filtros['estado'] ?? '') === 'fallido' ? 'selected' : '' ?>><?= I18n::t('audit_failed') ?></option>
            </select>
        </div>
        <!-- Filtro por rol -->
        <div class="col-12 col-md-4">
            <select name="rol" class="form-select audit-filter-select" onchange="this.form.submit()">
                <option value=""><?= I18n::t('audit_all_roles') ?></option>
                <option value="admin"          <?= ($filtros['rol'] ?? '') === 'admin'          ? 'selected' : '' ?>>Admin</option>
                <option value="directivo"      <?= ($filtros['rol'] ?? '') === 'directivo'      ? 'selected' : '' ?>>Directivo</option>
                <option value="administrativo" <?= ($filtros['rol'] ?? '') === 'administrativo' ? 'selected' : '' ?>>Administrativo</option>
                <option value="empleado"       <?= ($filtros['rol'] ?? '') === 'empleado'       ? 'selected' : '' ?>>Empleado</option>
                <option value="cliente"        <?= ($filtros['rol'] ?? '') === 'cliente'        ? 'selected' : '' ?>>Cliente</option>
            </select>
        </div>
    </div>
</form>

<!-- Tabla de Registros -->
<div class="card border-0 shadow-sm overflow-hidden audit-table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 audit-table">
            <thead>
                <tr>
                    <th class="ps-4"><?= I18n::t('audit_col_datetime') ?></th>
                    <th><?= I18n::t('audit_col_user') ?></th>
                    <th><?= I18n::t('audit_col_action') ?></th>
                    <th><?= I18n::t('audit_col_module') ?></th>
                    <th>IP</th>
                    <th class="text-center"><?= I18n::t('audit_col_status') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($registros)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            <small><?= I18n::t('audit_no_records') ?></small>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($registros as $i => $reg): ?>
                        <?php
                        $nombre   = $reg['usuario_nombre'] ?? 'Sistema';
                        $iniciales = '';
                        $partes = explode(' ', $nombre);
                        foreach ($partes as $p) {
                            $iniciales .= strtoupper(mb_substr($p, 0, 1));
                        }
                        $iniciales = mb_substr($iniciales, 0, 2);
                        $colorIdx  = crc32($nombre) % count($avatarColores);
                        $colorIdx  = abs($colorIdx);
                        $avatarBg  = $avatarColores[$colorIdx];

                        $rolCorto  = $reg['rol_nombre'] ?? '-';
                        $rolCorto  = ucfirst(mb_substr($rolCorto, 0, 3));

                        $badgeClase = $accionColores[$reg['accion']] ?? 'badge-accion-default';
                        $estadoCfg  = $estadoConfig[$reg['estado']]  ?? $estadoConfig['ok'];

                        $fecha = date('d/m H:i:s', strtotime($reg['created_at']));
                        ?>
                        <tr class="audit-row audit-row-clickable" onclick="toggleDetail(this)" style="cursor:pointer;">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-chevron-right audit-chevron small"></i>
                                    <span class="text-dark fw-semibold small"><?= $fecha ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="audit-avatar" style="background: <?= $avatarBg ?>;">
                                        <?= $iniciales ?>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-semibold small text-truncate" style="max-width: 120px;">
                                            <?= htmlspecialchars($nombre) ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.65rem;">
                                            <?= htmlspecialchars(ucfirst($reg['rol_nombre'] ?? '-')) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill <?= $badgeClase ?>">
                                    <?= htmlspecialchars($reg['accion']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="text-dark small"><?= htmlspecialchars($reg['modulo']) ?></span>
                            </td>
                            <td>
                                <code class="audit-ip"><?= htmlspecialchars($reg['ip']) ?></code>
                            </td>
                            <td class="text-center">
                                <span class="<?= $estadoCfg['clase'] ?>">
                                    <i class="bi <?= $estadoCfg['icono'] ?> me-1"></i><?= $estadoCfg['texto'] ?>
                                </span>
                            </td>
                        </tr>
                        <!-- Fila de detalle expandible (oculta por defecto) -->
                        <tr class="audit-detail-row" style="display:none;">
                            <td colspan="6" class="p-0">
                                <div class="audit-detail-panel">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="audit-detail-label"><?= I18n::t('audit_detail_entity') ?></div>
                                            <div class="audit-detail-value"><?= htmlspecialchars($reg['modulo']) ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="audit-detail-label"><?= I18n::t('audit_detail_user') ?></div>
                                            <div class="audit-detail-value"><?= htmlspecialchars($nombre) ?> (<?= htmlspecialchars(ucfirst($reg['rol_nombre'] ?? '-')) ?>)</div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="audit-detail-label"><?= I18n::t('audit_detail_description') ?></div>
                                            <div class="audit-detail-value"><?= htmlspecialchars($reg['descripcion'] ?? I18n::t('audit_no_detail')) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <?php if ($total > 0): ?>
    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top audit-pagination-bar">
        <span class="text-muted small">
            <?= I18n::t('audit_showing') ?> <?= $inicio ?>–<?= $fin ?> <?= I18n::t('audit_of') ?> <?= $total ?> <?= I18n::t('audit_events') ?>
        </span>
        <div class="d-flex gap-2">
            <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                <?php
                // Construir URL con filtros actuales + nueva página
                $queryParams = $filtros;
                $queryParams['pagina'] = $p;
                $queryStr = http_build_query(array_filter($queryParams));
                ?>
                <a href="<?= url('auditlog?' . $queryStr) ?>"
                   class="audit-page-btn <?= $p === $pagina ? 'active' : '' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- JavaScript para expandir/colapsar filas de detalle -->
<script>
function toggleDetail(row) {
    var detailRow = row.nextElementSibling;
    var chevron = row.querySelector('.audit-chevron');
    if (detailRow && detailRow.classList.contains('audit-detail-row')) {
        if (detailRow.style.display === 'none') {
            detailRow.style.display = 'table-row';
            chevron.classList.remove('bi-chevron-right');
            chevron.classList.add('bi-chevron-down');
            row.classList.add('audit-row-expanded');
        } else {
            detailRow.style.display = 'none';
            chevron.classList.remove('bi-chevron-down');
            chevron.classList.add('bi-chevron-right');
            row.classList.remove('audit-row-expanded');
        }
    }
}
</script>
