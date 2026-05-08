-- ============================================================
-- GESTOR PRO - Schema SQL v2.0 (Producción)
-- DBA: Revisión completa de integridad relacional y multi-tenancy
-- ============================================================

-- Configuración inicial del entorno
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;  -- Desactivar temporalmente para crear y borrar sin bloqueos
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================================================
-- LIMPIEZA: Eliminar tablas para evitar conflictos
-- ============================================================
DROP TABLE IF EXISTS `notificaciones`;
DROP TABLE IF EXISTS `ticket_mensajes`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `proyecto_usuarios`;
DROP TABLE IF EXISTS `proyecto_archivos`; 
DROP TABLE IF EXISTS `proyectos_documentos`;
DROP TABLE IF EXISTS `proyectos`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `empresas`;

-- ============================================================
-- TABLA 1: empresas
-- ============================================================
CREATE TABLE `empresas` (
  `id`               INT            NOT NULL AUTO_INCREMENT,
  `nombre_comercial` VARCHAR(150)   NOT NULL,
  `cuit`             VARCHAR(20)    NOT NULL,
  `email`            VARCHAR(100)   DEFAULT NULL,
  `telefono`         VARCHAR(30)    DEFAULT NULL,
  `direccion`        TEXT           DEFAULT NULL,
  `plan`             ENUM('basic','pro','enterprise') NOT NULL DEFAULT 'basic',
  `estado`           ENUM('activo','suspendido')      NOT NULL DEFAULT 'activo',
  `created_at`       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_empresa_cuit` (`cuit`),
  KEY `idx_empresa_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 2: roles
-- ============================================================
CREATE TABLE `roles` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(50)  NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_rol_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'admin',          'Superadministrador global del sistema SaaS'),
(2, 'directivo',      'Directivo o dueño de la empresa cliente'),
(3, 'administrativo', 'Gestor operativo: crea proyectos y asigna tareas'),
(4, 'empleado',       'Personal técnico que ejecuta tareas asignadas'),
(5, 'cliente',        'Usuario final que solicita servicios y abre tickets');

-- ============================================================
-- TABLA 3: usuarios
-- ============================================================
CREATE TABLE `usuarios` (
  `id`              INT          NOT NULL AUTO_INCREMENT,
  `empresa_id`      INT          DEFAULT NULL,
  `rol_id`          INT          NOT NULL,
  `nombre`          VARCHAR(100) NOT NULL,
  `email`           VARCHAR(100) NOT NULL,
  `password`        VARCHAR(255) NOT NULL,
  `telefono`        VARCHAR(30)  DEFAULT NULL,
  `estado`          ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_email` (`email`),
  KEY `idx_usuario_empresa` (`empresa_id`),
  KEY `idx_usuario_rol`     (`rol_id`),
  KEY `idx_usuario_estado`  (`estado`),
  CONSTRAINT `fk_usuario_empresa`
    FOREIGN KEY (`empresa_id`) REFERENCES `empresas`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_rol`
    FOREIGN KEY (`rol_id`) REFERENCES `roles`(`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 4: proyectos
-- ============================================================
CREATE TABLE `proyectos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 5: proyecto_usuarios
-- ============================================================
CREATE TABLE `proyecto_usuarios` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 6: proyecto_archivos
-- ============================================================
CREATE TABLE `proyecto_archivos` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 7: tickets
-- ============================================================
CREATE TABLE `tickets` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 8: ticket_mensajes
-- ============================================================
CREATE TABLE `ticket_mensajes` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 9: notificaciones
-- ============================================================
CREATE TABLE `notificaciones` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 10: mensajes_chat
-- ============================================================
CREATE TABLE `mensajes_chat` (
  `id`              INT          NOT NULL AUTO_INCREMENT,
  `remitente_id`    INT          NOT NULL,
  `destinatario_id` INT          NOT NULL,
  `mensaje`         TEXT         NOT NULL,
  `leido`           TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_chat_remitente`    (`remitente_id`),
  KEY `idx_chat_destinatario` (`destinatario_id`),
  CONSTRAINT `fk_chat_remitente`
    FOREIGN KEY (`remitente_id`) REFERENCES `usuarios`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_chat_destinatario`
    FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Reactivar verificación de claves foráneas
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DATOS SEMILLA PARA DESARROLLO/TESTING
-- ============================================================
INSERT INTO `empresas` (`id`, `nombre_comercial`, `cuit`, `email`, `plan`, `estado`) VALUES
(1, 'Empresa Demo SaaS', '20-12345678-9', 'demo@gestorpro.com', 'pro', 'activo');

INSERT INTO `usuarios` (`id`, `empresa_id`, `rol_id`, `nombre`, `email`, `password`, `estado`) VALUES
(1, NULL, 1, 'Administrador Global', 'admin@gestorpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo'),
(2, 1,    2, 'Director Demo',        'director@demo.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo');