<?php
// app/Controllers/AuditLogController.php
// Controlador para el módulo de Registro de Auditoría.
// Solo los roles admin y directivo pueden acceder a esta pantalla.
namespace app\Controllers;

use app\Core\Controller;
use app\Core\Session;
use app\Models\AuditLog;

class AuditLogController extends Controller {

    /**
     * Muestra la pantalla principal del Registro de Auditoría.
     * Recibe filtros por GET y los pasa al modelo para filtrar la lista.
     */
    public function index(): void {
        // Solo admin y directivo pueden ver el registro de auditoría
        Session::checkRole(['admin', 'directivo']);

        // Leer filtros desde la URL (?busqueda=xxx&accion=Login&estado=ok&rol=admin&pagina=2)
        $filtros = [
            'busqueda' => trim($_GET['busqueda'] ?? ''),
            'accion'   => trim($_GET['accion']   ?? ''),
            'estado'   => trim($_GET['estado']   ?? ''),
            'rol'      => trim($_GET['rol']      ?? ''),
        ];

        $pagina = max(1, (int)($_GET['pagina'] ?? 1));

        // Traer datos del modelo
        $resultado    = AuditLog::listar($filtros, $pagina);
        $estadisticas = AuditLog::estadisticas();
        $acciones     = AuditLog::accionesDistintas();

        // Pasar todo a la vista
        $this->render('auditlog/index', [
            'registros'    => $resultado['registros'],
            'total'        => $resultado['total'],
            'pagina'       => $pagina,
            'porPagina'    => 8,
            'filtros'      => $filtros,
            'estadisticas' => $estadisticas,
            'acciones'     => $acciones,
        ]);
    }

    /**
     * Exporta el log como CSV para descargar.
     */
    public function exportar(): void {
        Session::checkRole(['admin', 'directivo']);

        // Traer todos los registros sin paginación
        $resultado = AuditLog::listar([], 1, 99999);
        $registros = $resultado['registros'];

        // Cabeceras para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="audit_log_' . date('Y-m-d_His') . '.csv"');

        $output = fopen('php://output', 'w');

        // Encabezados del CSV
        fputcsv($output, ['Fecha/Hora', 'Usuario', 'Rol', 'Acción', 'Módulo', 'Descripción', 'IP', 'Estado']);

        // Filas de datos
        foreach ($registros as $r) {
            fputcsv($output, [
                $r['created_at'],
                $r['usuario_nombre'] ?? 'Sistema',
                $r['rol_nombre'] ?? '-',
                $r['accion'],
                $r['modulo'],
                $r['descripcion'] ?? '',
                $r['ip'],
                $r['estado'],
            ]);
        }

        fclose($output);
        exit;
    }
}
