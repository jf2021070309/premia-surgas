-- ============================================================
-- Schema para Railway (base de datos: railway)
-- No hace DROP/CREATE DATABASE, usa la BD existente
-- ============================================================

-- =========================
-- TABLA USUARIOS
-- =========================

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    usuario VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    rol ENUM('admin','conductor') NOT NULL,
    estado TINYINT DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLA CLIENTES
-- =========================

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE,
    nombre VARCHAR(100),
    celular VARCHAR(20),
    direccion VARCHAR(150),
    distrito VARCHAR(100),
    token VARCHAR(255),
    puntos INT DEFAULT 0,
    creado_por INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);

-- =========================
-- TABLA VENTAS
-- =========================

CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    conductor_id INT,
    monto DECIMAL(10,2),
    puntos INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (conductor_id) REFERENCES usuarios(id)
);

-- =========================
-- TABLA PREMIOS
-- =========================

CREATE TABLE IF NOT EXISTS premios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    puntos INT,
    stock INT,
    imagen VARCHAR(255),
    estado TINYINT DEFAULT 1
);

-- =========================
-- TABLA CANJES
-- =========================

CREATE TABLE IF NOT EXISTS canjes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    premio_id INT,
    puntos_usados INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (premio_id) REFERENCES premios(id)
);

-- =========================
-- TABLA SESIONES QR
-- =========================

CREATE TABLE IF NOT EXISTS sesiones_qr (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    token VARCHAR(255),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- =========================
-- DATOS INICIALES
-- =========================

INSERT IGNORE INTO usuarios (nombre, usuario, password, rol) VALUES
    ('Administrador', 'admin',      SHA2('123456', 256), 'admin'),
    ('Conductor 1',   'conductor1', SHA2('123456', 256), 'conductor');

INSERT IGNORE INTO premios (nombre, descripcion, puntos, stock, imagen, estado) VALUES
-- Nivel Bajo
('Tazas', 'Empieza tu mañana con estilo y el mejor café.', 100, 50, 'taza.png', 1),
('Vasos', 'Elegancia y frescura en cada brindis.', 100, 50, 'vasos.png', 1),
('Platos', 'Resistencia y diseño para tu mesa diaria.', 150, 40, 'platos.png', 1),
('Juego de 2 vasos', 'El dúo perfecto para compartir momentos especiales.', 200, 30, '2 vasos.png', 1),
('Juego de 2 tazas con logo', 'Presume tu lealtad a Surgas en cada sorbo.', 250, 25, 'juegos de tazas.png', 1),
-- Nivel Medio
('Set de utensilios de cocina', 'Todo lo que necesitas para ser el chef de casa.', 800, 20, 'Set de utensilios de cocina.png', 1),
('Platos y vasos combinados', 'El set ideal para renovar tu vajilla con clase.', 1000, 15, 'Platos y vasos combinados.png', 1),
('Licuadora', 'Potencia y versatilidad para tus jugos y batidos.', 1200, 10, 'Licuadora.png', 1),
('Juego de ollas', 'Cocina con amor y la mejor calidad en cada receta.', 2500, 8, 'Juego de ollas.png', 1),
('Cocina pequeña', 'Compacta, potente y lista para cualquier espacio.', 3000, 5, 'Cocina.png', 1),
-- Nivel Alto
('Licuadora profesional', 'Rendimiento superior para los resultados más exigentes.', 4500, 5, 'Licuadora profesional.png', 1),
('Juego completo de ollas', 'La colección definitiva para los amantes de la cocina.', 5000, 5, 'Juego completo de ollas.png', 1),
('Cocina mediana / eléctrica', 'Eficiencia y estilo moderno en tu cocina.', 7000, 3, 'cocina electrica.png', 1),
('Televisor 32"', 'Disfruta de tus series favoritas con nitidez increíble.', 8000, 4, 'Televisor pequeño.png', 1),
('Refrigeradora chica', 'El frescor perfecto en el tamaño ideal.', 10000, 2, 'Refrigera chica.png', 1),
-- Nivel VIP
('Cocina moderna / horno eléctrico', 'Atrévete a hornear y cocinar como un profesional.', 15000, 2, 'horno eléctrico.png', 1),
('Televisor 50" o más', 'Tu cine en casa con la mejor resolución y tamaño.', 18000, 2, 'Televisor50.png', 1),
('Refrigeradora mediana / grande', 'Espacio de sobra para toda la frescura de tu hogar.', 20000, 1, 'Refrigera medianagrande.png', 1),
('Laptop', 'Potencia tu productividad y diversión donde prefieras.', 25000, 1, 'laptop.png', 1),
('iPhone', 'La cima de la tecnología y el diseño en tus manos.', 35000, 1, 'iPhone.png', 1);
