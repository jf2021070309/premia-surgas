-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.16.0.7229
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para surgas
CREATE DATABASE IF NOT EXISTS `surgas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `surgas`;

-- Volcando estructura para tabla surgas.auditoria
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `modulo` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `modulo` (`modulo`),
  KEY `fecha_hora` (`fecha_hora`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla surgas.auditoria: ~51 rows (aproximadamente)
INSERT INTO `auditoria` (`id`, `id_usuario`, `accion`, `descripcion`, `metadata`, `modulo`, `ip_address`, `user_agent`, `fecha_hora`) VALUES
	(1, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:14:58'),
	(2, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:15:03'),
	(3, 3, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:15:48'),
	(4, 3, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:19:53'),
	(5, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:36:29'),
	(6, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:38:48'),
	(7, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:05:23'),
	(8, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:05:28'),
	(9, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:05:43'),
	(10, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:09:53'),
	(11, 1, 'NUEVO_CONDUCTOR', 'Registró al conductor: Elvis Leyva', NULL, 'CONDUCTORES', '::1', NULL, '2026-04-06 17:17:54'),
	(12, 1, 'ACTUALIZAR_CONDUCTOR', 'Actualizó datos del conductor: Elvis Leyva Sardon (1 campos modificados)', NULL, 'CONDUCTORES', '::1', NULL, '2026-04-06 17:18:14'),
	(13, 1, 'BAJA_CONDUCTOR', 'Inactivó al conductor: Elvis Leyva Sardon', NULL, 'CONDUCTORES', '::1', NULL, '2026-04-06 17:18:28'),
	(14, 1, 'ACTUALIZAR_CONDUCTOR', 'Actualizó datos del conductor: Jaime Flores  (2 campos modificados)', '{"nombre":{"ant":"Jaime Flores Quispe","des":"Jaime Flores "},"departamento":{"ant":"Tacna","des":null}}', 'CONDUCTORES', '::1', 'Escritorio — Chrome', '2026-04-06 17:22:48'),
	(15, 1, 'ACTUALIZAR_CONDUCTOR', 'Actualizó datos del conductor: Elvis Leyva Sardon (1 campos modificados)', '{"departamento":{"ant":null,"des":"Tacna"}}', 'CONDUCTORES', '::1', 'Escritorio — Chrome', '2026-04-06 17:27:48'),
	(16, 1, 'ACTUALIZAR_PRODUCTO', 'Actualizó producto: Refrigeradora mediana / grande (2 campos modificados)', '{"descripcion":{"ant":"Espacio de sobra para toda la frescura de tu hogar.","des":""},"estado":{"ant":0,"des":1}}', 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-06 18:21:35'),
	(17, 1, 'ACTUALIZAR_PRODUCTO', 'Actualizó producto: iPhone (2 campos modificados)', '{"descripcion":{"ant":"La cima de la tecnolog\\u00eda y el dise\\u00f1o en tus manos.","des":""},"estado":{"ant":1,"des":0}}', 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-06 18:22:02'),
	(18, 1, 'ACTUALIZAR_PRODUCTO', 'Actualizó producto: iPhone (1 campos modificados)', '{"descripcion":{"ant":"","des":"nuevo de ultima generacion"}}', 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-06 18:36:54'),
	(19, 1, 'BAJA_PRODUCTO', 'Inactivó el producto: iPhone', NULL, 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-06 18:37:10'),
	(20, 1, 'BAJA_PRODUCTO', 'Inactivó el producto: iPhone', NULL, 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-06 18:37:17'),
	(21, 1, 'ACTUALIZAR_PRODUCTO', 'Actualizó producto: iPhone (1 campos modificados)', '{"estado":{"ant":0,"des":1}}', 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-06 18:37:25'),
	(22, 1, 'ACTUALIZAR_REGLA_PUNTOS', 'Actualizó regla de puntos: Recarga gas Normal (1 campos modificados)', '{"puntos":{"ant":5,"des":6}}', 'LOGISTICA', '::1', 'Escritorio — Chrome', '2026-04-06 18:44:47'),
	(23, 1, 'ACTUALIZAR_CLIENTE', 'Editó datos del cliente: Jaime Elias Flores Quispe (1 campos modificados)', '{"direccion":{"ant":"Calle Snan Roman 914","des":"Calle Snan Roman 914 Urb San Jose"}}', 'CLIENTES', '::1', 'Escritorio — Chrome', '2026-04-06 19:59:16'),
	(24, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-06 19:59:46'),
	(25, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-07 13:19:51'),
	(26, 2, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-07 13:20:41'),
	(27, 2, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-07 13:20:57'),
	(28, 1, 'ELIMINAR_PRODUCTO', 'Eliminó definitivamente el producto: Cocina moderna / horno eléctrico', NULL, 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-07 13:40:43'),
	(29, 1, 'ACTUALIZAR_CLIENTE', 'Editó datos del cliente: Jaime Elias Flores Quispe (1 campos modificados)', '{"direccion":{"ant":"Calle Snan Roman 914 Urb San Jose","des":"Calle San Roman 914"}}', 'CLIENTES', '::1', 'Escritorio — Chrome', '2026-04-07 13:50:48'),
	(30, 1, 'ACTUALIZAR_CONDUCTOR', 'Actualizó datos del conductor: Elvis Leyva Sardon (1 campos modificados)', '{"estado":{"ant":0,"des":1}}', 'CONDUCTORES', '::1', 'Escritorio — Chrome', '2026-04-07 14:16:38'),
	(31, 1, 'ACTUALIZAR_PRODUCTO', 'Actualizó producto: Laptop (1 campos modificados)', '{"stock":{"ant":0,"des":10}}', 'PRODUCTOS', '::1', 'Escritorio — Chrome', '2026-04-07 14:42:01'),
	(32, 1, 'CARGA_PUNTOS', 'Cargó 114 puntos a Jaime Elias Flores Quispe (Recarga gas Premium x8 (+80 pts), Recarga gas Normal x5 (+30 pts), Accesorio / Otros x2 (+4 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-07 14:43:32'),
	(33, 1, 'CARGA_PUNTOS', 'Cargó 64 puntos a Jaime Elias Flores Quispe (Recarga gas Premium x5 (+50 pts), Accesorio / Otros x7 (+14 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-07 14:47:46'),
	(34, 1, 'ESTADO_CANJE', 'Cambió a ENTREGADO el canje de Set de utensilios de cocina para Jaime Elias Flores Quispe', NULL, 'CANJES', '::1', 'Escritorio — Chrome', '2026-04-07 14:59:16'),
	(35, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-08 13:23:02'),
	(36, 1, 'CARGA_PUNTOS', 'Cargó 90 puntos a Jaime Elias Flores Quispe (Recarga gas Premium x9 (+90 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-08 13:24:07'),
	(37, 1, 'ESTADO_CANJE', 'Cambió a ENTREGADO el canje de Cocina mediana / eléctrica para Jaime Elias Flores Quispe', NULL, 'CANJES', '::1', 'Escritorio — Chrome', '2026-04-08 13:25:47'),
	(38, 1, 'ESTADO_CANJE', 'Cambió a ENTREGADO el canje de Laptop para Jaime Elias Flores Quispe', NULL, 'CANJES', '::1', 'Escritorio — Chrome', '2026-04-08 13:25:52'),
	(39, 3, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-08 14:25:40'),
	(40, 3, 'CARGA_PUNTOS', 'Cargó 300 puntos a Jaime Elias Flores Quispe (Recarga gas Premium x10 (+100 pts), Recarga gas Premium x10 (+100 pts), Recarga gas Premium x10 (+100 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-08 14:26:02'),
	(41, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-09 13:15:01'),
	(42, 3, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-09 13:20:43'),
	(43, 3, 'CARGA_PUNTOS', 'Cargó 70 puntos a Jaime Elias Flores Quispe (• Recarga gas Premium x7 (+70 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-09 17:29:42'),
	(44, 3, 'CARGA_PUNTOS', 'Cargó 64 puntos a Jaime Elias Flores Quispe (• Recarga gas Normal x8 (+48 pts)\n• Accesorio / Otros x8 (+16 pts)\n──────────\nTOTAL: 64 pts)', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-09 17:30:36'),
	(45, 3, 'CARGA_PUNTOS', 'Cargó 114 puntos a Jaime Elias Flores Quispe (• Recarga gas Normal x9 (+54 pts)\n• Recarga gas Premium x6 (+60 pts)\n──────────\nTOTAL: 114 pts)', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-09 17:35:31'),
	(46, 3, 'CARGA_PUNTOS', 'Cargó 2 puntos a Jaime Elias Flores Quispe (• Accesorio / Otros x1 (+2 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-09 17:40:40'),
	(47, 3, 'CARGA_PUNTOS', 'Cargó 24 puntos a Jaime Elias Flores Quispe (• Accesorio / Otros x1 (+2 pts)\n• Recarga gas Normal x1 (+6 pts)\n• Recarga gas Normal x1 (+6 pts)\n• Recarga gas Premium x1 (+10 pts)\n──────────\nTOTAL: 24 pts)', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-09 17:41:06'),
	(48, 3, 'CARGA_PUNTOS', 'Cargó 18 puntos a Jaime Elias Flores Quispe (• Recarga gas Premium x1 (+10 pts)\n• Recarga gas Normal x1 (+6 pts)\n• Accesorio / Otros x1 (+2 pts)\n──────────\nTOTAL: 18 pts)', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-09 17:42:28'),
	(49, 3, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-10 13:19:54'),
	(50, 3, 'CARGA_PUNTOS', 'Cargó 160 puntos a Jaime Elias Flores Quispe (• Recarga gas Normal x8 (+48 pts)\n• Recarga gas Premium x8 (+80 pts)\n• Accesorio / Otros x8 (+16 pts)\n• Accesorio / Otros x8 (+16 pts)\n──────────\nTOTAL: 160 pts)', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-10 14:19:24'),
	(51, 3, 'CARGA_PUNTOS', 'Cargó 10 puntos a Jaime Elias Flores Quispe (• Recarga gas Premium x1 (+10 pts))', NULL, 'RECARGAS', '::1', 'Escritorio — Chrome', '2026-04-10 14:20:04'),
	(52, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-10 18:57:27'),
	(53, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-15 15:34:12'),
	(54, 1, 'ACTUALIZAR_QR_PAGO', 'Actualizó el código QR de Yape y nombre titular (FUTURE STORE EIRL)', NULL, 'RECARGAS', '::1', 'Móvil — Safari', '2026-04-15 19:17:43'),
	(55, 1, 'ACTUALIZAR_QR_PAGO', 'Actualizó el código QR de Yape y nombre titular (FUTURE STORE EIRL)', NULL, 'RECARGAS', '::1', 'Móvil — Safari', '2026-04-15 19:19:04'),
	(56, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-16 14:28:24'),
	(57, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-04-17 13:29:39'),
	(58, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', 'Móvil — Safari', '2026-04-17 13:29:53'),
	(59, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Móvil — Safari', '2026-04-17 13:32:29'),
	(60, 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', 'Móvil — Safari', '2026-04-17 13:45:23'),
	(61, 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', 'Móvil — Safari', '2026-04-17 13:45:27');

-- Volcando estructura para tabla surgas.canjes
CREATE TABLE IF NOT EXISTS `canjes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `premio_id` int(11) DEFAULT NULL,
  `puntos_usados` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT 0.00,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','entregado') DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `premio_id` (`premio_id`),
  CONSTRAINT `canjes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `canjes_ibfk_2` FOREIGN KEY (`premio_id`) REFERENCES `premios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.canjes: ~5 rows (aproximadamente)
INSERT INTO `canjes` (`id`, `cliente_id`, `premio_id`, `puntos_usados`, `monto`, `fecha`, `estado`) VALUES
	(1, 1, 19, 25000, 0.00, '2026-03-22 15:53:32', 'entregado'),
	(2, 1, 13, 7000, 0.00, '2026-03-22 15:54:29', 'entregado'),
	(3, 1, 13, 7000, 0.00, '2026-03-22 15:54:34', 'entregado'),
	(4, 1, 8, 1200, 0.00, '2026-03-25 18:16:45', 'entregado'),
	(5, 1, 6, 800, 0.00, '2026-03-25 20:13:28', 'entregado');

-- Volcando estructura para tabla surgas.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) DEFAULT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `tipo_cliente` enum('Normal','Restaurante','Punto de Venta') DEFAULT 'Normal',
  `ruc` varchar(15) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `departamento` enum('Tacna','Moquegua','Arequipa','Ilo') DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `puntos` int(11) DEFAULT 0,
  `estado` tinyint(4) DEFAULT 1,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `dni` (`dni`),
  KEY `creado_por` (`creado_por`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.clientes: ~0 rows (aproximadamente)
INSERT INTO `clientes` (`id`, `codigo`, `dni`, `nombre`, `razon_social`, `tipo_cliente`, `ruc`, `celular`, `direccion`, `departamento`, `token`, `password`, `puntos`, `estado`, `creado_por`, `fecha_creacion`, `session_id`) VALUES
	(1, 'CLI-000001', '72883481', 'Jaime Elias Flores Quispe', NULL, 'Normal', NULL, '957084266', 'Calle San Roman 914', 'Tacna', '32286cdc9c72fe3529894442608a162ad51018ef6a1fa8c0ac3c567af767c6da', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 56095, 1, NULL, '2026-03-20 17:56:01', '2935d1cb53c4360d3212a83e5b7f5382');

-- Volcando estructura para tabla surgas.configuraciones
CREATE TABLE IF NOT EXISTS `configuraciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.configuraciones: ~4 rows (aproximadamente)
INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `descripcion`) VALUES
	(1, 'puntos_por_sol', '1', 'Cantidad de puntos otorgados por cada Sol de compra.'),
	(2, 'monto_por_punto', '0.05', 'Equivalencia en Soles de cada punto para canjes mixtos.'),
	(3, 'yape_qr_imagen', 'yape_qr_1776280744.png', 'Imagen QR de Yape para pagos'),
	(8, 'yape_nombre_titular', 'FUTURE STORE EIRL', 'Nombre que aparece en el botón de Yape');

-- Volcando estructura para tabla surgas.premios
CREATE TABLE IF NOT EXISTS `premios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.premios: ~20 rows (aproximadamente)
INSERT INTO `premios` (`id`, `nombre`, `descripcion`, `puntos`, `stock`, `imagen`, `estado`) VALUES
	(1, 'Tazas', 'Empieza tu mañana con estilo y el mejor café.', 100, 50, 'taza.png', 1),
	(2, 'Vasos', 'Elegancia y frescura en cada brindis.', 100, 50, 'vasos.png', 1),
	(3, 'Platos', 'Resistencia y diseño para tu mesa diaria.', 150, 40, 'platos.png', 1),
	(4, 'Juego de 2 vasos', 'El dúo perfecto para compartir momentos especiales.', 200, 30, '2 vasos.png', 1),
	(5, 'Juego de 2 tazas con logo', 'Presume tu lealtad a Surgas en cada sorbo.', 250, 25, 'juegos de tazas.png', 1),
	(6, 'Set de utensilios de cocina', 'Todo lo que necesitas para ser el chef de casa.', 800, 19, 'Set de utensilios de cocina.png', 1),
	(7, 'Platos y vasos combinados', 'El set ideal para renovar tu vajilla con clase.', 1000, 15, 'Platos y vasos combinados.png', 1),
	(8, 'Licuadora', 'Potencia y versatilidad para tus jugos y batidos.', 1200, 9, 'Licuadora.png', 1),
	(9, 'Juego de ollas', 'Cocina con amor y la mejor calidad en cada receta.', 2500, 8, 'Juego de ollas.png', 1),
	(10, 'Cocina pequeña', 'Compacta, potente y lista para cualquier espacio.', 3000, 5, 'Cocina.png', 1),
	(11, 'Licuadora profesional', 'Rendimiento superior para los resultados más exigentes.', 4500, 5, 'Licuadora profesional.png', 1),
	(12, 'Juego completo de ollas', 'La colección definitiva para los amantes de la cocina.', 5000, 5, 'Juego completo de ollas.png', 1),
	(13, 'Cocina mediana / eléctrica', 'Eficiencia y estilo moderno en tu cocina.', 7000, 1, 'cocina electrica.png', 1),
	(14, 'Televisor 32"', 'Disfruta de tus series favoritas con nitidez increíble.', 8000, 4, 'Televisor pequeño.png', 1),
	(15, 'Refrigeradora chica', 'El frescor perfecto en el tamaño ideal.', 10000, 2, 'Refrigera chica.png', 1),
	(17, 'Televisor 50" o más', 'Tu cine en casa con la mejor resolución y tamaño.', 18000, 2, 'Televisor50.png', 1),
	(18, 'Refrigeradora mediana / grande', '', 20000, 1, 'Refrigera medianagrande.png', 1),
	(19, 'Laptop', 'Potencia tu productividad y diversión donde prefieras.', 25000, 10, 'laptop.png', 1),
	(20, 'iPhone', 'nuevo de ultima generacion', 35000, 1, 'iPhone.png', 1);

-- Volcando estructura para tabla surgas.recargas
CREATE TABLE IF NOT EXISTS `recargas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `comprobante` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  `validado_por` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `validado_por` (`validado_por`),
  CONSTRAINT `recargas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `recargas_ibfk_2` FOREIGN KEY (`validado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.recargas: ~43 rows (aproximadamente)
INSERT INTO `recargas` (`id`, `cliente_id`, `puntos`, `monto`, `comprobante`, `estado`, `fecha`, `fecha_validacion`, `validado_por`) VALUES
	(1, 1, 1000, 50.00, 'recarga_1774289701_1.jpeg', 'aprobado', '2026-03-23 18:15:01', NULL, NULL),
	(2, 1, 1000, 50.00, 'recarga_1774289711_1.jpeg', 'aprobado', '2026-03-23 18:15:11', NULL, NULL),
	(3, 1, 20000, 850.00, 'recarga_1774289872_1.jpeg', 'aprobado', '2026-03-23 18:17:52', NULL, NULL),
	(4, 1, 10000, 450.00, 'recarga_1774290086_1.jpeg', 'aprobado', '2026-03-23 18:21:26', NULL, NULL),
	(5, 1, 5000, 230.00, 'recarga_1774290722_1.jpeg', 'aprobado', '2026-03-23 18:32:02', '2026-03-23 13:32:42', 1),
	(6, 1, 2500, 120.00, 'recarga_1774291041_1.jpeg', 'aprobado', '2026-03-23 18:37:21', '2026-03-23 13:37:37', 1),
	(7, 1, 1000, 50.00, 'recarga_1774291497_1.jpeg', 'aprobado', '2026-03-23 18:44:57', '2026-03-23 13:45:23', 1),
	(8, 1, 1000, 50.00, 'recarga_1774291939_1.jpeg', 'aprobado', '2026-03-23 18:52:19', '2026-03-23 13:52:30', 1),
	(9, 1, 2500, 120.00, 'recarga_1774292472_1.jpeg', 'aprobado', '2026-03-23 19:01:12', '2026-03-23 14:01:28', 1),
	(10, 1, 1000, 50.00, 'recarga_1774292570_1.jpeg', 'aprobado', '2026-03-23 19:02:50', '2026-03-23 14:03:04', 1),
	(11, 1, 1000, 50.00, 'recarga_1774363162_1.jpeg', 'aprobado', '2026-03-24 14:39:22', '2026-03-24 14:40:41', 1),
	(12, 1, 1000, 50.00, 'recarga_1774363200_1.jpeg', 'aprobado', '2026-03-24 14:40:00', '2026-03-24 14:40:19', 1),
	(13, 1, 2500, 120.00, 'recarga_1774364230_1.jpeg', 'aprobado', '2026-03-24 14:57:10', '2026-03-24 14:57:29', 1),
	(14, 1, 20000, 850.00, 'recarga_1774364270_1.jpeg', 'rechazado', '2026-03-24 14:57:50', '2026-03-24 14:58:13', 1),
	(15, 1, 2500, 120.00, 'recarga_1774365523_1.jpeg', 'aprobado', '2026-03-24 15:18:43', '2026-03-24 15:20:01', 1),
	(16, 1, 50000, 2000.00, 'recarga_1774365684_1.png', 'rechazado', '2026-03-24 15:21:24', '2026-03-24 16:20:21', 1),
	(17, 1, 50000, 2000.00, 'recarga_1774365685_1.png', 'rechazado', '2026-03-24 15:21:25', '2026-03-24 16:20:16', 1),
	(18, 1, 2500, 120.00, 'recarga_1774369241_1.jpeg', 'aprobado', '2026-03-24 16:20:41', '2026-03-24 16:26:26', 1),
	(19, 1, 2500, 120.00, 'recarga_1774369712_1.png', 'rechazado', '2026-03-24 16:28:32', '2026-03-24 16:32:33', 1),
	(20, 1, 1000, 50.00, 'recarga_1774369734_1.jpg', 'rechazado', '2026-03-24 16:28:54', '2026-03-24 16:32:28', 1),
	(21, 1, 2500, 120.00, 'recarga_1774370013_1.png', 'rechazado', '2026-03-24 16:33:33', '2026-03-24 16:40:11', 1),
	(22, 1, 5000, 230.00, 'recarga_1774370050_1.jpeg', 'aprobado', '2026-03-24 16:34:10', '2026-03-24 16:35:17', 1),
	(23, 1, 1000, 50.00, 'recarga_1774370425_1.png', 'rechazado', '2026-03-24 16:40:25', '2026-03-24 16:44:04', 1),
	(24, 1, 2500, 120.00, 'recarga_1774370450_1.png', 'rechazado', '2026-03-24 16:40:50', '2026-03-24 16:43:54', 1),
	(25, 1, 1000, 50.00, 'recarga_1774370474_1.png', 'rechazado', '2026-03-24 16:41:14', '2026-03-24 16:44:09', 1),
	(26, 1, 2500, 120.00, 'recarga_1774370492_1.jpeg', 'rechazado', '2026-03-24 16:41:32', '2026-03-24 16:43:59', 1),
	(27, 1, 1000, 50.00, 'recarga_1774370673_1.png', 'rechazado', '2026-03-24 16:44:33', '2026-03-24 16:47:46', 1),
	(28, 1, 2500, 120.00, 'recarga_1774370910_1.png', 'rechazado', '2026-03-24 16:48:30', '2026-03-24 16:54:27', 1),
	(29, 1, 1000, 50.00, 'recarga_1774370928_1.jpeg', 'rechazado', '2026-03-24 16:48:48', '2026-03-24 16:54:22', 1),
	(30, 1, 1000, 50.00, 'recarga_1774370965_1.png', 'rechazado', '2026-03-24 16:49:25', '2026-03-24 16:54:16', 1),
	(31, 1, 1000, 50.00, 'recarga_1774371343_1.png', 'aprobado', '2026-03-24 16:55:43', '2026-03-24 16:57:54', 1),
	(32, 1, 1000, 50.00, 'recarga_1774371428_1.jpeg', 'aprobado', '2026-03-24 16:57:08', '2026-03-24 16:57:44', 1),
	(33, 1, 2500, 120.00, 'recarga_1774374588_1.png', 'aprobado', '2026-03-24 17:49:48', '2026-03-24 17:59:15', 1),
	(34, 1, 1000, 50.00, 'recarga_1774374617_1.jpg', 'aprobado', '2026-03-24 17:50:17', '2026-03-24 17:58:55', 1),
	(35, 1, 1000, 50.00, 'recarga_1774375181_1.jpg', 'rechazado', '2026-03-24 17:59:41', '2026-03-24 18:05:03', 1),
	(36, 1, 1000, 50.00, 'recarga_1774375526_1.png', 'rechazado', '2026-03-24 18:05:26', '2026-03-25 18:18:16', 1),
	(37, 1, 10000, 450.00, 'recarga_1774375561_1.jpeg', 'rechazado', '2026-03-24 18:06:01', '2026-03-25 18:18:12', 1),
	(38, 1, 5000, 230.00, 'recarga_1774375588_1.jpeg', 'rechazado', '2026-03-24 18:06:28', '2026-03-25 18:18:04', 1),
	(39, 1, 1000, 50.00, 'recarga_1774375625_1.jpg', 'rechazado', '2026-03-24 18:07:05', '2026-03-25 18:18:08', 1),
	(40, 1, 2500, 120.00, 'recarga_1774375639_1.jpeg', 'rechazado', '2026-03-24 18:07:19', '2026-03-25 18:18:00', 1),
	(41, 1, 1000, 50.00, 'recarga_1774463130_1.jpg', 'aprobado', '2026-03-25 18:25:30', '2026-03-25 18:26:38', 1),
	(42, 1, 1000, 50.00, 'recarga_1774463254_1.jpg', 'aprobado', '2026-03-25 18:27:34', '2026-03-25 18:27:46', 1),
	(43, 1, 1000, 50.00, 'recarga_1774469676_1.jpg', 'aprobado', '2026-03-25 20:14:36', '2026-03-25 20:15:07', 1);

-- Volcando estructura para tabla surgas.tipos_operaciones
CREATE TABLE IF NOT EXISTS `tipos_operaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.tipos_operaciones: ~3 rows (aproximadamente)
INSERT INTO `tipos_operaciones` (`id`, `nombre`, `puntos`, `estado`) VALUES
	(1, 'Recarga gas Normal', 6, 1),
	(2, 'Recarga gas Premium', 10, 1),
	(3, 'Accesorio / Otros', 2, 1);

-- Volcando estructura para tabla surgas.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','conductor', "aliado") NOT NULL,
  `departamento` enum('Tacna','Moquegua','Arequipa','Ilo') DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.usuarios: ~4 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `departamento`, `estado`, `fecha_creacion`, `session_id`) VALUES
	(1, 'Administrador', 'admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'admin', 'Tacna', 1, '2026-03-20 13:15:09', 'a7efcjgumngu1mi5gtg525esl7'),
	(2, 'Oscar Flores', 'conductor1', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'conductor', 'Tacna', 1, '2026-03-20 13:15:09', 'osb6mmcv0pv4k8ca8vdi57tm29'),
	(3, 'Jaime Flores ', 'Jaime', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'conductor', 'Tacna', 1, '2026-04-06 16:11:08', 'nuontuhhc2mmoqcal7544k27du'),
	(4, 'Elvis Leyva Sardon', 'Elvis', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'conductor', 'Tacna', 1, '2026-04-06 17:17:54', NULL);

-- Volcando estructura para tabla surgas.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `conductor_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `detalle` varchar(255) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `conductor_id` (`conductor_id`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`conductor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.ventas: ~14 rows (aproximadamente)
INSERT INTO `ventas` (`id`, `cliente_id`, `conductor_id`, `monto`, `puntos`, `detalle`, `fecha`) VALUES
	(2, 1, 1, 0.00, 1000, 'PRUEBA HACK 1000', '2026-03-22 15:51:32'),
	(3, 1, 1, 0.00, 1500, 'PRUEBA HACK 1000', '2026-03-22 15:52:06'),
	(4, 1, 1, 0.00, 30000, 'PRUEBA HACK 1000', '2026-03-22 15:53:01'),
	(5, 1, 1, 0.00, 65, 'Recarga gas Premium x4 (+40 pts), Recarga gas Normal x5 (+25 pts)', '2026-03-24 18:57:20'),
	(6, 1, 1, 0.00, 114, 'Recarga gas Premium x8 (+80 pts), Recarga gas Normal x5 (+30 pts), Accesorio / Otros x2 (+4 pts)', '2026-04-07 14:43:32'),
	(7, 1, 1, 0.00, 64, 'Recarga gas Premium x5 (+50 pts), Accesorio / Otros x7 (+14 pts)', '2026-04-07 14:47:46'),
	(8, 1, 1, 0.00, 90, 'Recarga gas Premium x9 (+90 pts)', '2026-04-08 13:24:07'),
	(9, 1, 3, 0.00, 300, 'Recarga gas Premium x10 (+100 pts), Recarga gas Premium x10 (+100 pts), Recarga gas Premium x10 (+100 pts)', '2026-04-08 14:26:02'),
	(10, 1, 3, 0.00, 70, '• Recarga gas Premium x7 (+70 pts)', '2026-04-09 17:29:42'),
	(11, 1, 3, 0.00, 64, '• Recarga gas Normal x8 (+48 pts)\n• Accesorio / Otros x8 (+16 pts)\n──────────\nTOTAL: 64 pts', '2026-04-09 17:30:36'),
	(12, 1, 3, 0.00, 114, '• Recarga gas Normal x9 (+54 pts)\n• Recarga gas Premium x6 (+60 pts)\n──────────\nTOTAL: 114 pts', '2026-04-09 17:35:31'),
	(13, 1, 3, 0.00, 2, '• Accesorio / Otros x1 (+2 pts)', '2026-04-09 17:40:40'),
	(14, 1, 3, 0.00, 24, '• Accesorio / Otros x1 (+2 pts)\n• Recarga gas Normal x1 (+6 pts)\n• Recarga gas Normal x1 (+6 pts)\n• Recarga gas Premium x1 (+10 pts)\n──────────\nTOTAL: 24 pts', '2026-04-09 17:41:06'),
	(15, 1, 3, 0.00, 18, '• Recarga gas Premium x1 (+10 pts)\n• Recarga gas Normal x1 (+6 pts)\n• Accesorio / Otros x1 (+2 pts)\n──────────\nTOTAL: 18 pts', '2026-04-09 17:42:28'),
	(16, 1, 3, 0.00, 160, '• Recarga gas Normal x8 (+48 pts)\n• Recarga gas Premium x8 (+80 pts)\n• Accesorio / Otros x8 (+16 pts)\n• Accesorio / Otros x8 (+16 pts)\n──────────\nTOTAL: 160 pts', '2026-04-10 14:19:24'),
	(17, 1, 3, 0.00, 10, '• Recarga gas Premium x1 (+10 pts)', '2026-04-10 14:20:04');
 
-- Volcando estructura para tabla surgas.venta_detalles
CREATE TABLE IF NOT EXISTS `venta_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `nombre_item` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `puntos_unitarios` int(11) NOT NULL,
  `puntos_subtotal` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  CONSTRAINT `fk_venta_detalles_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
