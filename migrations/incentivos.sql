-- ============================================================
-- SISTEMA DE INCENTIVOS POR METAS DE COMPRA
-- Tablas independientes del sistema de puntos
-- ============================================================

-- Reglas de incentivos configurables por el admin
CREATE TABLE IF NOT EXISTS incentivos_reglas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL COMMENT 'Ej: Promo 10 Galones → Vale 50%',
    descripcion TEXT NULL COMMENT 'Texto motivacional visible al cliente',
    tipo_cliente ENUM('Todos','Normal','Restaurante','Punto de Venta') NOT NULL DEFAULT 'Todos',
    meta_cantidad INT NOT NULL DEFAULT 1 COMMENT 'Cantidad mínima de operaciones en el periodo',
    periodo ENUM('semanal','mensual','trimestral') NOT NULL DEFAULT 'mensual',
    tipo_premio ENUM('vale_descuento','vale_producto','vale_dinero') NOT NULL DEFAULT 'vale_descuento',
    valor_premio DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '50 = 50% descuento, o monto fijo',
    descripcion_premio VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Ej: Vale de 50% en tu próxima compra',
    vigencia_dias INT NOT NULL DEFAULT 30 COMMENT 'Días de validez del vale generado',
    estado TINYINT NOT NULL DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vales generados automáticamente al cumplir metas
CREATE TABLE IF NOT EXISTS incentivos_vales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(25) NOT NULL UNIQUE COMMENT 'Código único del vale: VALE-20260427-0001',
    cliente_id INT NOT NULL,
    regla_id INT NOT NULL,
    periodo_evaluado VARCHAR(20) NOT NULL COMMENT '2026-04 (año-mes evaluado)',
    cantidad_lograda INT NOT NULL DEFAULT 0 COMMENT 'Operaciones realizadas en el periodo',
    tipo_premio ENUM('vale_descuento','vale_producto','vale_dinero') NOT NULL,
    valor DECIMAL(10,2) NOT NULL DEFAULT 0,
    descripcion VARCHAR(255) NOT NULL DEFAULT '',
    fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_vencimiento DATE NOT NULL,
    estado ENUM('activo','usado','vencido','cancelado') NOT NULL DEFAULT 'activo',
    usado_fecha TIMESTAMP NULL DEFAULT NULL,
    usado_por INT NULL DEFAULT NULL COMMENT 'FK → usuarios (quién marcó como usado)',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (regla_id) REFERENCES incentivos_reglas(id) ON DELETE CASCADE,
    INDEX idx_cliente_periodo (cliente_id, periodo_evaluado),
    INDEX idx_estado (estado),
    INDEX idx_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
