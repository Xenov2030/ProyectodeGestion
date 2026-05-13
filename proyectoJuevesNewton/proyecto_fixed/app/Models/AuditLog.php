<?php
// app/Models/AuditLog.php
// Modelo para el Registro de Auditoría.
// Permite guardar y consultar las acciones que hacen los usuarios en el sistema.
namespace app\Models;

use app\Core\Database;
use PDO;

class AuditLog {

    /**
     * Registra una acción en el log de auditoría.
     * Se llama desde los controladores cada vez que un usuario hace algo importante.
     *
     * @param int|null $usuarioId  ID del usuario que hizo la acción (null si es anónimo)
     * @param string   $accion     Qué hizo: Login, Crear, Editar, Eliminar, Exportar
     * @param string   $modulo     En qué parte del sistema: Auth, Proyectos, Soporte, etc.
     * @param string   $descripcion Detalle corto de lo que pasó
     * @param string   $estado     Resultado: ok, aviso, fallido
     */
    public static function registrar(
        ?int $usuarioId,
        string $accion,
        string $modulo,
        string $descripcion = '',
        string $estado = 'ok'
    ): bool {
        $db = Database::getInstance()->getConnection();

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';

        $stmt = $db->prepare(
            "INSERT INTO audit_log (usuario_id, accion, modulo, descripcion, ip, estado)
             VALUES (:usuario_id, :accion, :modulo, :descripcion, :ip, :estado)"
        );

        return $stmt->execute([
            'usuario_id'  => $usuarioId,
            'accion'      => $accion,
            'modulo'      => $modulo,
            'descripcion' => $descripcion,
            'ip'          => $ip,
            'estado'      => $estado,
        ]);
    }

    /**
     * Trae los registros del log con filtros opcionales y paginación.
     * Hace JOIN con la tabla usuarios para mostrar nombre y rol.
     *
     * @param array $filtros  Filtros opcionales: busqueda, accion, estado, rol
     * @param int   $pagina   Número de página (empieza en 1)
     * @param int   $porPagina Cuántos registros por página
     * @return array ['registros' => [...], 'total' => int]
     */
    public static function listar(array $filtros = [], int $pagina = 1, int $porPagina = 8): array {
        $db = Database::getInstance()->getConnection();

        // Consulta base con JOIN a usuarios y roles
        $sql = "SELECT a.*, u.nombre AS usuario_nombre, u.email AS usuario_email,
                       r.nombre AS rol_nombre
                FROM audit_log a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                LEFT JOIN roles r ON u.rol_id = r.id
                WHERE 1=1";

        $countSql = "SELECT COUNT(*) FROM audit_log a
                     LEFT JOIN usuarios u ON a.usuario_id = u.id
                     LEFT JOIN roles r ON u.rol_id = r.id
                     WHERE 1=1";

        $params = [];

        // Filtro: búsqueda libre (por nombre, acción, IP o módulo)
        if (!empty($filtros['busqueda'])) {
            $like = '%' . $filtros['busqueda'] . '%';
            $sql .= " AND (u.nombre LIKE :busq OR a.accion LIKE :busq2 OR a.ip LIKE :busq3 OR a.modulo LIKE :busq4)";
            $countSql .= " AND (u.nombre LIKE :busq OR a.accion LIKE :busq2 OR a.ip LIKE :busq3 OR a.modulo LIKE :busq4)";
            $params['busq']  = $like;
            $params['busq2'] = $like;
            $params['busq3'] = $like;
            $params['busq4'] = $like;
        }

        // Filtro: por tipo de acción
        if (!empty($filtros['accion'])) {
            $sql .= " AND a.accion = :accion";
            $countSql .= " AND a.accion = :accion";
            $params['accion'] = $filtros['accion'];
        }

        // Filtro: por estado
        if (!empty($filtros['estado'])) {
            $sql .= " AND a.estado = :estado";
            $countSql .= " AND a.estado = :estado";
            $params['estado'] = $filtros['estado'];
        }

        // Filtro: por rol del usuario
        if (!empty($filtros['rol'])) {
            $sql .= " AND r.nombre = :rol";
            $countSql .= " AND r.nombre = :rol";
            $params['rol'] = $filtros['rol'];
        }

        // Contar total para la paginación
        $stmtCount = $db->prepare($countSql);
        $stmtCount->execute($params);
        $total = (int) $stmtCount->fetchColumn();

        // Ordenar por fecha más reciente y paginar
        $offset = ($pagina - 1) * $porPagina;
        $sql .= " ORDER BY a.created_at DESC LIMIT $porPagina OFFSET $offset";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'registros' => $registros,
            'total'     => $total,
        ];
    }

    /**
     * Devuelve las estadísticas resumidas para las tarjetas de arriba.
     * Cuenta eventos de las últimas 24 horas.
     */
    public static function estadisticas(): array {
        $db = Database::getInstance()->getConnection();

        // Total de eventos en las últimas 24 horas
        $total = $db->query(
            "SELECT COUNT(*) FROM audit_log WHERE created_at >= NOW() - INTERVAL 1 DAY"
        )->fetchColumn();

        // Eventos críticos (eliminaciones)
        $criticos = $db->query(
            "SELECT COUNT(*) FROM audit_log WHERE accion = 'Eliminar' AND created_at >= NOW() - INTERVAL 1 DAY"
        )->fetchColumn();

        // Intentos fallidos de login
        $fallidos = $db->query(
            "SELECT COUNT(*) FROM audit_log WHERE estado = 'fallido' AND created_at >= NOW() - INTERVAL 1 DAY"
        )->fetchColumn();

        // Usuarios activos hoy (distintos usuario_id con login exitoso)
        $activos = $db->query(
            "SELECT COUNT(DISTINCT usuario_id) FROM audit_log WHERE accion = 'Login' AND estado = 'ok' AND created_at >= NOW() - INTERVAL 1 DAY"
        )->fetchColumn();

        return [
            'total'    => (int) $total,
            'criticos' => (int) $criticos,
            'fallidos' => (int) $fallidos,
            'activos'  => (int) $activos,
        ];
    }

    /**
     * Devuelve todas las acciones distintas que existen en la tabla.
     * Se usa para llenar el filtro desplegable.
     */
    public static function accionesDistintas(): array {
        $db = Database::getInstance()->getConnection();
        return $db->query("SELECT DISTINCT accion FROM audit_log ORDER BY accion")
                  ->fetchAll(PDO::FETCH_COLUMN);
    }
}
