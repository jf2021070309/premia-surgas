USE `railway`;
-- Volcando estructura para tabla surgas.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','conductor','afiliado') NOT NULL,
  `departamento` enum('Tacna','Moquegua','Arequipa','Ilo') DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.usuarios: ~6 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`, `departamento`, `direccion`, `celular`, `estado`, `fecha_creacion`, `session_id`) VALUES
	(1, 'Administrador', 'admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'admin', 'Tacna', NULL, NULL, 1, '2026-03-20 13:15:09', '1sogub2p0v7vmrqjcihhaqog9v'),
	(2, 'Oscar Flores', 'conductor1', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'conductor', 'Tacna', NULL, NULL, 1, '2026-03-20 13:15:09', '314qpo511sf0nph6ngpp3dqkcs'),
	(3, 'Jaime Flores ', 'Jaime', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'conductor', 'Tacna', NULL, NULL, 1, '2026-04-06 16:11:08', 'nuontuhhc2mmoqcal7544k27du'),
	(4, 'Elvis Leyva Sardon', 'Elvis', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'conductor', 'Tacna', NULL, NULL, 1, '2026-04-06 17:17:54', NULL),
	(5, 'RESTAURANTE CHITE 2', 'chite', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'afiliado', 'Tacna', NULL, NULL, 1, '2026-04-17 14:24:49', '5pdjkoi5q0r9cn3o84gvpe1njc'),
	(8, 'GRUPO EL MORADITO SOCIEDAD ANONIMA CERRADA - GRUPO EL MORADITO S.A.C.', '20533141553', 'ca8f4b4e49467c95f54df897ed9d7d462577cc114fbbad87acaaefbc878052e3', 'afiliado', 'Tacna', 'AV. CERRO CAMACHO NRO 980 INT. 03 DEP. 301B', '981489541', 1, '2026-05-05 15:48:05', 'cn5jha1a22pu4uaj31g8t90n08');


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
	(2, 'monto_por_punto', '0.1', 'Equivalencia en Soles de cada punto para canjes mixtos.'),
	(3, 'yape_qr_imagen', 'yape_qr_1776280744.png', 'Imagen QR de Yape para pagos'),
	(8, 'yape_nombre_titular', 'FUTURE STORE EIRL', 'Nombre que aparece en el botón de Yape');


-- Volcando estructura para tabla surgas.tipos_operaciones
CREATE TABLE IF NOT EXISTS `tipos_operaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `precio_estandar` decimal(10,2) DEFAULT 0.00,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `puntos` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.tipos_operaciones: ~3 rows (aproximadamente)
INSERT INTO `tipos_operaciones` (`id`, `nombre`, `precio_estandar`, `descuento`, `puntos`, `estado`) VALUES
	(1, 'Recarga gas Normal', 0.00, 0.00, 6, 1),
	(2, 'Recarga gas Premium', 54.00, 2.00, 32, 1),
	(3, 'Accesorio / Otros', 0.00, 0.00, 2, 1);

-- Volcando estructura para tabla surgas.premios
CREATE TABLE IF NOT EXISTS `premios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_base` decimal(10,2) DEFAULT 0.00,
  `puntos` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.premios: ~21 rows (aproximadamente)
INSERT INTO `premios` (`id`, `nombre`, `descripcion`, `precio_base`, `puntos`, `stock`, `imagen`, `estado`) VALUES
	(1, 'Tazas', 'Empieza tu mañana con estilo y el mejor café.', NULL, 100, 47, 'taza.png', 1),
	(2, 'Vasos', 'Elegancia y frescura en cada brindis.', NULL, 100, 48, 'vasos.png', 1),
	(3, 'Platos', 'Resistencia y diseño para tu mesa diaria.', NULL, 150, 40, 'platos.png', 1),
	(4, 'Juego de 2 vasos', 'El dúo perfecto para compartir momentos especiales.', NULL, 200, 30, '2 vasos.png', 1),
	(5, 'Juego de 2 tazas con logo', 'Presume tu lealtad a Surgas en cada sorbo.', NULL, 250, 24, 'juegos de tazas.png', 1),
	(6, 'Set de utensilios de cocina', 'Todo lo que necesitas para ser el chef de casa.', NULL, 800, 18, 'Set de utensilios de cocina.png', 1),
	(7, 'Platos y vasos combinados', 'El set ideal para renovar tu vajilla con clase.', NULL, 1000, 15, 'Platos y vasos combinados.png', 1),
	(8, 'Licuadora', 'Potencia y versatilidad para tus jugos y batidos.', NULL, 1200, 9, 'Licuadora.png', 1),
	(9, 'Juego de ollas', 'Cocina con amor y la mejor calidad en cada receta.', NULL, 2500, 8, 'Juego de ollas.png', 1),
	(10, 'Cocina pequeña', 'Compacta, potente y lista para cualquier espacio.', NULL, 3000, 5, 'Cocina.png', 1),
	(11, 'Licuadora profesional', 'Rendimiento superior para los resultados más exigentes.', NULL, 4500, 5, 'Licuadora profesional.png', 1),
	(12, 'Juego completo de ollas', 'La colección definitiva para los amantes de la cocina.', NULL, 5000, 5, 'Juego completo de ollas.png', 1),
	(13, 'Cocina mediana / eléctrica', 'Eficiencia y estilo moderno en tu cocina.', NULL, 7000, 1, 'cocina electrica.png', 1),
	(14, 'Televisor 32"', 'Disfruta de tus series favoritas con nitidez increíble.', NULL, 8000, 3, 'Televisor pequeño.png', 1),
	(15, 'Refrigeradora chica', 'El frescor perfecto en el tamaño ideal.', NULL, 10000, 2, 'Refrigera chica.png', 1),
	(17, 'Televisor 50" o más', 'Tu cine en casa con la mejor resolución y tamaño.', NULL, 18000, 2, 'Televisor50.png', 1),
	(18, 'Refrigeradora mediana / grande', '', NULL, 20000, 0, 'Refrigera medianagrande.png', 1),
	(19, 'Laptop', 'Potencia tu productividad y diversión donde prefieras.', 4000.00, 25000, 8, 'laptop.png', 1),
	(20, 'iPhone', 'nuevo de ultima generacion', NULL, 35000, 0, 'iPhone.png', 1),
	(21, 'Mouse G502', 'Mouse inalambrico', 300.00, 500, 7, '69f9f2a29bb3c.png', 1),
	(23, 'Iphone 14 PRO MAX', 'Nuevo', 2000.00, 5000, 2, '69f9f537a5099.png', 1);

-- Volcando estructura para tabla surgas.puntos_venta
CREATE TABLE IF NOT EXISTS `puntos_venta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `propietario` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.puntos_venta: ~8 rows (aproximadamente)
INSERT INTO `puntos_venta` (`id`, `nombre`, `latitud`, `longitud`, `foto`, `created_at`, `propietario`) VALUES
	(6, 'Comercial Diaz', -18.01197870, -70.23414350, 'https://i.ibb.co/mr3mZrf9/1203b567f280.jpg', '2026-05-25 18:04:21', 'Gianella Paredes'),
	(7, 'Tienda Mathias', -18.05393450, -70.23512950, 'https://i.ibb.co/BH1hKjmw/1c202b18b6d4.jpg', '2026-05-25 18:07:15', 'Deysi vargas huanacuni'),
	(8, 'Abarrotes Lidia', -18.04655700, -70.24170500, 'https://i.ibb.co/8L86Mdhx/8a3218ec8df1.jpg', '2026-05-25 18:17:59', 'Lidia Cama Polloqueri'),
	(9, 'Negocio BruBen', -18.06270780, -70.29698770, 'https://i.ibb.co/QtqsX9v/223d99458b34.jpg', '2026-05-25 18:18:50', 'Jaime Uruchi Perca'),
	(10, 'Comercial Ruth', -18.07190670, -70.25151500, 'https://i.ibb.co/gb7c1Lcq/4ea45b37ed16.jpg', '2026-05-25 18:19:41', 'Ruth Calizaya Huayhua'),
	(11, 'Minimarket "La esquina del barrio"', -18.01372266, -70.23498958, 'https://i.ibb.co/2YSGdqX4/be8cda0df339.jpg', '2026-05-25 18:22:26', 'Barrio (Pendiente)'),
	(12, 'CALLE CHORILLOS 278 POCOLLAY', -17.99770430, -70.23009720, 'https://i.ibb.co/zhLqzjkL/58d993701794.jpg', '2026-05-25 18:24:16', 'Chorrillos (Pendiente)'),
	(13, 'Tienda virgen copacabana', -17.99499870, -70.25741600, NULL, '2026-05-25 18:24:50', 'Virgen (Pendiente)');


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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.clientes: ~5 rows (aproximadamente)
INSERT INTO `clientes` (`id`, `codigo`, `dni`, `nombre`, `razon_social`, `tipo_cliente`, `ruc`, `celular`, `direccion`, `departamento`, `token`, `password`, `puntos`, `estado`, `creado_por`, `fecha_creacion`, `session_id`) VALUES
	(1, 'CLI-000001', '72883481', 'Jaime Elias Flores Quispe', NULL, 'Normal', NULL, '957084266', 'Calle San Roman 914', 'Tacna', '32286cdc9c72fe3529894442608a162ad51018ef6a1fa8c0ac3c567af767c6da', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 100, 1, NULL, '2026-03-20 17:56:01', '7745oa0lj1qrkupg0a5npfrjvc'),
	(2, 'CLI-000002', '78459612', 'Jose y Maria', NULL, 'Normal', NULL, '951486985', 'AV HUMBOLTD', 'Tacna', '55018eeb8cf1f4ad3c0828927b6bd6902c436e4faf665a467e1d634de22e8b5f', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 0, 1, 5, '2026-04-17 14:28:05', 'jifffnvs6vcpn9b0dcuonm0icl'),
	(3, 'CLI-000003', NULL, 'Surgas', 'FUTURE STORE E.I.R.L.', 'Restaurante', '20605771981', '984984898', 'AV. CERRO CAMACHO NRO 980 INT. 301B', 'Tacna', 'f6d51c3e9382bc947d119bee02072b587aa880ddb2952c3e04d8b9e3b9e5a4b6', NULL, 0, 1, 1, '2026-04-27 15:01:15', NULL),
	(4, 'CLI-000004', NULL, 'Moradito', 'GRUPO EL MORADITO SOCIEDAD ANONIMA CERRADA - GRUPO EL MORADITO S.A.C.', 'Punto de Venta', '20533141553', '981489541', 'AV. CERRO CAMACHO NRO 980 INT. 03 DEP. 301B', 'Tacna', 'cf29247cae02f16a5f3601a6f6517a1f4e093179b0cd7ba8f9ff439620620ef7', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 0, 1, 1, '2026-04-27 15:01:43', 'msv5lfu7ll9sai5la8c3cn1c0d'),
	(5, 'CLI-000005', '71489625', 'Gabriela Del Rosario Llacas Vela', NULL, 'Normal', NULL, '984512814', '', 'Tacna', 'b26527c090ed37338afe7f1751dc0c0a1cf83ccfe6fdbba69ac229d8dbe058fb', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 0, 1, NULL, '2026-04-27 15:05:58', '2fpc9sl85qerc76vijhsfov15m');

CREATE TABLE IF NOT EXISTS `afiliado_anuncios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nombre_negocio` varchar(150) NOT NULL,
  `imagen_negocio` varchar(255) DEFAULT NULL,
  `carta_pdf` varchar(255) DEFAULT NULL,
  `ubicacion` text DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `color_fondo` varchar(20) DEFAULT '#A7D8F5',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `fk_anuncio_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.afiliado_anuncios: ~0 rows (aproximadamente)
INSERT INTO `afiliado_anuncios` (`id`, `usuario_id`, `nombre_negocio`, `imagen_negocio`, `carta_pdf`, `ubicacion`, `celular`, `estado`, `fecha_creacion`, `color_fondo`) VALUES
	(1, 8, 'GRUPO EL MORADITO SOCIEDAD ANONIMA CERRADA - GRUPO EL MORADITO S.A.C.', 'https://i.ibb.co/Kpnp2JGZ/80dd2459663f.png', 'assets/uploads/anuncios/pdf_8_1777996789.pdf', 'AV. CERRO CAMACHO NRO 980 INT. 03 DEP. 301B', '981489541', 1, '2026-05-05 15:59:49', '#A7D8F5');

-- Volcando estructura para tabla surgas.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `conductor_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `detalle` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `conductor_id` (`conductor_id`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`conductor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.ventas: ~0 rows (aproximadamente)
INSERT INTO `ventas` (`id`, `cliente_id`, `conductor_id`, `monto`, `puntos`, `detalle`, `estado`, `fecha`) VALUES
	(39, 1, 2, 0.00, 100, '• Recarga gas Premium x10 (+100 pts)', 'aprobado', '2026-05-06 13:48:40');

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
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla surgas.venta_detalles: ~0 rows (aproximadamente)
INSERT INTO `venta_detalles` (`id`, `venta_id`, `nombre_item`, `cantidad`, `puntos_unitarios`, `puntos_subtotal`) VALUES
	(103, 39, 'Recarga gas Premium', 10, 10, 100);


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


-- Volcando estructura para tabla surgas.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `modalidad` varchar(50) NOT NULL,
  `producto` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `punto_venta_id` int(11) DEFAULT NULL,
  `estado` enum('pendiente','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_pedidos_cliente` (`cliente_id`),
  CONSTRAINT `fk_pedidos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.pedidos: ~0 rows (aproximadamente)
INSERT INTO `pedidos` (`id`, `cliente_id`, `modalidad`, `producto`, `cantidad`, `direccion`, `latitud`, `longitud`, `punto_venta_id`, `estado`, `fecha_creacion`) VALUES
	(1, 1, 'A Domicilio', 'Normal', 2, 'Los delfines Mz', NULL, NULL, NULL, 'pendiente', '2026-06-05 13:28:29'),
	(2, 1, 'A Domicilio', 'Normal', 2, 'Los delfines Mz', NULL, NULL, NULL, 'pendiente', '2026-06-05 13:29:29'),
	(3, 1, 'A Domicilio', 'Premium', 3, 'Promuvi', NULL, NULL, NULL, 'pendiente', '2026-06-05 13:41:57');


-- Volcando estructura para tabla surgas.canjes
CREATE TABLE IF NOT EXISTS `canjes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `premio_id` int(11) DEFAULT NULL,
  `puntos_usados` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT 0.00,
  `comprobante_url` varchar(500) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','entregado','pago_pendiente','pago_aprobado','pago_rechazado','cancelado') DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `premio_id` (`premio_id`),
  CONSTRAINT `canjes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `canjes_ibfk_2` FOREIGN KEY (`premio_id`) REFERENCES `premios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla surgas.canjes: ~17 rows (aproximadamente)
INSERT INTO `canjes` (`id`, `cliente_id`, `premio_id`, `puntos_usados`, `monto`, `comprobante_url`, `fecha`, `estado`) VALUES
	(1, 1, 19, 25000, 0.00, NULL, '2026-03-22 15:53:32', 'entregado'),
	(2, 1, 13, 7000, 0.00, NULL, '2026-03-22 15:54:29', 'entregado'),
	(3, 1, 13, 7000, 0.00, NULL, '2026-03-22 15:54:34', 'entregado'),
	(4, 1, 8, 1200, 0.00, NULL, '2026-03-25 18:16:45', 'entregado'),
	(5, 1, 6, 800, 0.00, NULL, '2026-03-25 20:13:28', 'entregado'),
	(6, 1, 20, 19600, 1540.00, NULL, '2026-04-19 15:18:17', 'pago_aprobado'),
	(7, 1, 19, 10000, 1500.00, NULL, '2026-04-19 15:38:06', 'pendiente'),
	(8, 1, 14, 7200, 80.00, NULL, '2026-04-19 16:48:23', 'pendiente'),
	(9, 1, 19, 20000, 500.00, 'https://i.ibb.co/nN1XSyc2/1335a2bb6024.jpg', '2026-04-19 16:49:06', 'pago_pendiente'),
	(10, 1, 18, 0, 2000.00, 'https://i.ibb.co/CKgLYTsc/8f38a154e493.jpg', '2026-04-19 16:50:24', 'pago_aprobado'),
	(11, 1, 1, 100, 0.00, NULL, '2026-04-19 17:21:38', 'pendiente'),
	(12, 1, 1, 100, 0.00, NULL, '2026-04-19 17:33:23', 'pendiente'),
	(13, 1, 2, 100, 0.00, NULL, '2026-04-19 17:36:04', 'pendiente'),
	(14, 1, 1, 100, 0.00, NULL, '2026-04-19 17:45:13', 'pendiente'),
	(15, 1, 6, 800, 0.00, NULL, '2026-04-19 18:23:00', 'pendiente'),
	(16, 2, 2, 60, 4.00, 'https://i.ibb.co/sdxt3LxH/312354f74e18.jpg', '2026-04-27 12:30:46', 'pago_pendiente'),
	(17, 2, 5, 125, 12.50, NULL, '2026-04-27 13:04:41', 'pendiente');


-- Volcando estructura para tabla surgas.incentivos_reglas
CREATE TABLE IF NOT EXISTS `incentivos_reglas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL COMMENT 'Ej: Promo 10 Galones ÔåÆ Vale 50%',
  `descripcion` text DEFAULT NULL COMMENT 'Texto motivacional visible al cliente',
  `tipo_cliente` enum('Todos','Normal','Restaurante','Punto de Venta') NOT NULL DEFAULT 'Todos',
  `meta_cantidad` int(11) NOT NULL DEFAULT 1 COMMENT 'Cantidad m├¡nima de operaciones en el periodo',
  `periodo` enum('semanal','mensual','trimestral') NOT NULL DEFAULT 'mensual',
  `tipo_premio` enum('vale_descuento','vale_producto','vale_dinero') NOT NULL DEFAULT 'vale_descuento',
  `valor_premio` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '50 = 50% descuento, o monto fijo',
  `descripcion_premio` varchar(255) NOT NULL DEFAULT '' COMMENT 'Ej: Vale de 50% en tu pr├│xima compra',
  `vigencia_dias` int(11) NOT NULL DEFAULT 30 COMMENT 'D├¡as de validez del vale generado',
  `estado` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla surgas.incentivos_reglas: ~3 rows (aproximadamente)
INSERT INTO `incentivos_reglas` (`id`, `nombre`, `descripcion`, `tipo_cliente`, `meta_cantidad`, `periodo`, `tipo_premio`, `valor_premio`, `descripcion_premio`, `vigencia_dias`, `estado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
	(1, 'Meta Mensual Restaurantes', 'íCompleta 10 compras en el mes y obtÚn un 50% de descuento en tu pr¾ximo bal¾n!', 'Restaurante', 10, 'mensual', 'vale_descuento', 50.00, 'Vale de 50% de Descuento', 30, 1, '2026-04-27 13:36:50', '2026-04-27 13:36:50'),
	(2, 'Bono Puntos de Venta', 'Premio especial para puntos de venta: 15 compras al mes = íVale de S/ 20 en efectivo!', 'Punto de Venta', 15, 'mensual', 'vale_dinero', 20.00, 'Vale de S/ 20 Efectivo', 30, 1, '2026-04-27 13:36:50', '2026-04-27 13:36:50'),
	(3, 'Bono de Frecuencia Semanal', 'Premio rßpido: Realiza 3 compras esta semana y llÚvate un Gas Premium gratis.', 'Todos', 3, 'semanal', 'vale_producto', 15.00, 'Vale por 1 Balón de Gas Premium', 15, 1, '2026-04-27 13:36:50', '2026-04-27 14:01:10');

-- Volcando estructura para tabla surgas.incentivos_vales
CREATE TABLE IF NOT EXISTS `incentivos_vales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(25) NOT NULL COMMENT 'C├│digo ├║nico del vale: VALE-20260427-0001',
  `cliente_id` int(11) NOT NULL,
  `regla_id` int(11) NOT NULL,
  `periodo_evaluado` varchar(20) NOT NULL COMMENT '2026-04 (a├▒o-mes evaluado)',
  `cantidad_lograda` int(11) NOT NULL DEFAULT 0 COMMENT 'Operaciones realizadas en el periodo',
  `tipo_premio` enum('vale_descuento','vale_producto','vale_dinero') NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('activo','usado','vencido','cancelado') NOT NULL DEFAULT 'activo',
  `usado_fecha` timestamp NULL DEFAULT NULL,
  `usado_por` int(11) DEFAULT NULL COMMENT 'FK ÔåÆ usuarios (qui├®n marc├│ como usado)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `regla_id` (`regla_id`),
  KEY `idx_cliente_periodo` (`cliente_id`,`periodo_evaluado`),
  KEY `idx_estado` (`estado`),
  KEY `idx_codigo` (`codigo`),
  CONSTRAINT `incentivos_vales_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `incentivos_vales_ibfk_2` FOREIGN KEY (`regla_id`) REFERENCES `incentivos_reglas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla surgas.incentivos_vales: ~4 rows (aproximadamente)
INSERT INTO `incentivos_vales` (`id`, `codigo`, `cliente_id`, `regla_id`, `periodo_evaluado`, `cantidad_lograda`, `tipo_premio`, `valor`, `descripcion`, `fecha_emision`, `fecha_vencimiento`, `estado`, `usado_fecha`, `usado_por`) VALUES
	(1, 'VALE-20260427-3494', 2, 3, '2026-W18', 3, 'vale_producto', 15.00, 'Vale por 1 Balón de Gas Premium', '2026-04-27 14:56:58', '2026-05-12', 'activo', NULL, NULL),
	(2, 'VALE-20260427-2290', 4, 3, '2026-W18', 3, 'vale_producto', 15.00, 'Vale por 1 Balón de Gas Premium', '2026-04-27 16:35:18', '2026-05-12', 'usado', '2026-04-27 16:35:43', 1),
	(3, 'VALE-20260428-6556', 1, 3, '2026-W18', 3, 'vale_producto', 15.00, 'Vale por 1 Balón de Gas Premium', '2026-04-28 15:28:46', '2026-05-13', 'activo', NULL, NULL),
	(4, 'VALE-20260506-5503', 1, 3, '2026-W19', 3, 'vale_producto', 15.00, 'Vale por 1 Balón de Gas Premium', '2026-05-06 13:29:10', '2026-05-21', 'activo', NULL, NULL);


-- Volcando estructura para tabla surgas.auditoria
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_usuario` enum('trabajador','cliente') NOT NULL DEFAULT 'trabajador',
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
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla surgas.auditoria: ~193 rows (aproximadamente)
INSERT INTO `auditoria` (`id`, `tipo_usuario`, `id_usuario`, `accion`, `descripcion`, `metadata`, `modulo`, `ip_address`, `user_agent`, `fecha_hora`) VALUES
	(1, 'trabajador', 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:14:58'),
	(2, 'trabajador', 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:15:03'),
	(3, 'trabajador', 3, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:15:48'),
	(4, 'trabajador', 3, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:19:53'),
	(5, 'trabajador', 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:36:29'),
	(6, 'trabajador', 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 16:38:48'),
	(7, 'trabajador', 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:05:23'),
	(8, 'trabajador', 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Trabajador)', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:05:28'),
	(9, 'trabajador', 1, 'CIERRE_SESION', 'El usuario cerró su sesión', NULL, 'SEGURIDAD', '::1', NULL, '2026-04-06 17:05:43'),
	(202, 'cliente', 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Cliente)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-06-05 13:27:28'),
	(203, 'cliente', 1, 'NUEVO_PEDIDO_CHATBOT', 'Se creó el pedido chatbot ID #2 a domicilio', NULL, 'CLIENTES', '::1', 'Escritorio — Chrome', '2026-06-05 13:29:29'),
	(204, 'cliente', 1, 'INICIO_SESION', 'Inicio de sesión exitoso (Cliente)', NULL, 'SEGURIDAD', '::1', 'Escritorio — Chrome', '2026-06-05 13:29:48'),
	(205, 'cliente', 1, 'NUEVO_PEDIDO_CHATBOT', 'Se creó el pedido chatbot ID #3 a domicilio', NULL, 'CLIENTES', '::1', 'Escritorio — Chrome', '2026-06-05 13:41:57');

