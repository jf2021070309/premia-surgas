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

-- ============================================================
-- REGLAS POR DEFECTO
-- ============================================================

INSERT INTO incentivos_reglas 
(nombre, descripcion, tipo_cliente, meta_cantidad, periodo, tipo_premio, valor_premio, descripcion_premio, vigencia_dias)
VALUES 
('Meta Mensual Restaurantes', '¡Completa 10 compras en el mes y obtén un 50% de descuento en tu próximo balón!', 'Restaurante', 10, 'mensual', 'vale_descuento', 50.00, 'Vale de 50% de Descuento', 30),
('Bono Puntos de Venta', 'Premio especial para puntos de venta: 15 compras al mes = ¡Vale de S/ 20 en efectivo!', 'Punto de Venta', 15, 'mensual', 'vale_dinero', 20.00, 'Vale de S/ 20 Efectivo', 30),
('Bono de Frecuencia Semanal', 'Premio rápido: Realiza 3 compras esta semana y llévate un Gas Premium gratis.', 'Todos', 3, 'semanal', 'vale_producto', 0.00, 'Vale por 1 Balón de Gas Premium', 15);
