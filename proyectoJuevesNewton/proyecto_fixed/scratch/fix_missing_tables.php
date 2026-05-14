<?php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Core/Database.php';

use app\Core\Database;

$queries = [
    // 1. proyectos
    "CREATE TABLE IF NOT EXISTS `proyectos` (
      `id`          INT          NOT NULL AUTO_INCREMENT,
      `empresa_id`  INT          NOT NULL,
      `cliente_id`  INT          NOT NULL,
      `nombre`      VARCHAR(150) NOT NULL,
      `descripcion` TEXT         DEFAULT NULL,
      `estado`      ENUM('pendiente','en_progreso','finalizado','cancelado') NOT NULL DEFAULT 'pendiente',
      `prioridad`   ENUM('baja','media','alta','urgente')                   NOT NULL DEFAULT 'media',
      `fecha_inicio` DATE        DEFAULT NULL,
      `fecha_fin`    DATE        DEFAULT NULL,
      `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_proyecto_empresa`  (`empresa_id`),
      KEY `idx_proyecto_cliente`  (`cliente_id`),
      KEY `idx_proyecto_estado`   (`estado`),
      CONSTRAINT `fk_proyecto_empresa`
        FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      CONSTRAINT `fk_proyecto_cliente`
        FOREIGN KEY (`cliente_id`) REFERENCES `usuarios`(`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // 2. proyecto_usuarios
    "CREATE TABLE IF NOT EXISTS `proyecto_usuarios` (
      `proyecto_id` INT NOT NULL,
      `usuario_id`  INT NOT NULL,
      `asignado_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`proyecto_id`, `usuario_id`),
      KEY `idx_pu_usuario` (`usuario_id`),
      CONSTRAINT `fk_pu_proyecto`
        FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      CONSTRAINT `fk_pu_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // 3. proyecto_archivos
    "CREATE TABLE IF NOT EXISTS `proyecto_archivos` (
      `id`             INT          NOT NULL AUTO_INCREMENT,
      `empresa_id`     INT          NOT NULL,
      `proyecto_id`    INT          NOT NULL,
      `ruta_archivo`   VARCHAR(255) NOT NULL,
      `nombre_original`VARCHAR(100) NOT NULL,
      `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_archivo_empresa` (`empresa_id`),
      KEY `idx_archivo_proyecto` (`proyecto_id`),
      CONSTRAINT `fk_archivo_empresa`
        FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      CONSTRAINT `fk_archivo_proyecto`
        FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // 4. tickets
    "CREATE TABLE IF NOT EXISTS `tickets` (
      `id`          INT          NOT NULL AUTO_INCREMENT,
      `empresa_id`  INT          NOT NULL,
      `usuario_id`  INT          NOT NULL,
      `proyecto_id` INT          DEFAULT NULL,
      `titulo`      VARCHAR(150) NOT NULL,
      `descripcion` TEXT         NOT NULL,
      `prioridad`   ENUM('baja','media','alta','urgente') NOT NULL DEFAULT 'media',
      `estado`      ENUM('abierto','en_proceso','cerrado') NOT NULL DEFAULT 'abierto',
      `asignado_a`  INT          DEFAULT NULL,
      `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_ticket_empresa`   (`empresa_id`),
      KEY `idx_ticket_usuario`   (`usuario_id`),
      KEY `idx_ticket_proyecto`  (`proyecto_id`),
      KEY `idx_ticket_asignado`  (`asignado_a`),
      KEY `idx_ticket_estado`    (`estado`),
      CONSTRAINT `fk_ticket_empresa`
        FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      CONSTRAINT `fk_ticket_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
      CONSTRAINT `fk_ticket_proyecto`
        FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos`(`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
      CONSTRAINT `fk_ticket_asignado`
        FOREIGN KEY (`asignado_a`) REFERENCES `usuarios`(`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // 5. ticket_mensajes
    "CREATE TABLE IF NOT EXISTS `ticket_mensajes` (
      `id`         INT      NOT NULL AUTO_INCREMENT,
      `ticket_id`  INT      NOT NULL,
      `usuario_id` INT      NOT NULL,
      `mensaje`    TEXT     NOT NULL,
      `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_tm_ticket`  (`ticket_id`),
      KEY `idx_tm_usuario` (`usuario_id`),
      CONSTRAINT `fk_tm_ticket`
        FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
      CONSTRAINT `fk_tm_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // 6. notificaciones
    "CREATE TABLE IF NOT EXISTS `notificaciones` (
      `id`         INT        NOT NULL AUTO_INCREMENT,
      `usuario_id` INT        NOT NULL,
      `mensaje`    TEXT       NOT NULL,
      `leido`      TINYINT(1) NOT NULL DEFAULT 0,
      `created_at` DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_notif_usuario` (`usuario_id`),
      CONSTRAINT `fk_notif_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];

try {
    $db = Database::getInstance()->getConnection();
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    foreach ($queries as $i => $sql) {
        $db->exec($sql);
        echo "Query " . ($i+1) . " executed successfully.\n";
    }
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "All missing tables have been created.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
