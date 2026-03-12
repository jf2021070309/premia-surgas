DROP DATABASE IF EXISTS surgas;
CREATE DATABASE surgas;
USE surgas;


-- =========================
-- TABLA USUARIOS
-- admin / conductor
-- =========================

CREATE TABLE usuarios (
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
-- guarda codigo y token QR
-- =========================

CREATE TABLE clientes (
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
-- cada compra suma puntos
-- =========================

CREATE TABLE ventas (
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
-- tienda de puntos
-- =========================

CREATE TABLE premios (
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
-- historial de premios usados
-- =========================

CREATE TABLE canjes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    cliente_id INT,
    premio_id INT,

    puntos_usados INT,

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (premio_id) REFERENCES premios(id)
);


-- =========================
-- TABLA SESIONES QR (opcional)
-- login automático seguro
-- =========================

CREATE TABLE sesiones_qr (
    id INT AUTO_INCREMENT PRIMARY KEY,

    cliente_id INT,
    token VARCHAR(255),

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);



-- =========================
-- DATOS INICIALES
-- =========================


-- ADMIN

INSERT INTO usuarios (
    nombre,
    usuario,
    password,
    rol
)
VALUES (
    'Administrador',
    'admin',
    SHA2('123456', 256),
    'admin'
);


-- CONDUCTOR

INSERT INTO usuarios (
    nombre,
    usuario,
    password,
    rol
)
VALUES (
    'Conductor 1',
    'conductor1',
    SHA2('123456', 256),
    'conductor'
);



-- PREMIOS EJEMPLO

INSERT INTO premios (
    nombre,
    descripcion,
    puntos,
    stock,
    imagen
)
VALUES
('Balón gratis', 'Canje por balón de gas', 50, 10, ''),
('Descuento 5 soles', 'Descuento en compra', 20, 50, ''),
('Cocina', 'Premio especial', 200, 2, '');
