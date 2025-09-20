-- ========================================
-- Base de datos completa para app de proformas
-- ========================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS presupuestostecno;
USE presupuestostecno;

-- ========================================
-- Tabla clientes
-- ========================================
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(50),
    direccion VARCHAR(255),
    cuit_cuil VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registros de ejemplo
INSERT INTO clientes(nombre,email,telefono,direccion,cuit_cuil) VALUES
('Juan Pérez','juan.perez@email.com','123456789','Calle Falsa 123','20-12345678-9'),
('María López','maria.lopez@email.com','987654321','Av. Siempre Viva 456','27-87654321-0'),
('Carlos Gómez','carlos.gomez@email.com','5551234','Calle del Sol 789','23-11223344-5');

-- ========================================
-- Tabla empresa
-- ========================================
CREATE TABLE IF NOT EXISTS empresa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    cuit VARCHAR(20),
    telefono VARCHAR(50),
    ing_br VARCHAR(50),         
    inicio_act VARCHAR(50),     
    logo VARCHAR(255) DEFAULT 'logo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registro de ejemplo
INSERT INTO empresa (nombre, direccion, cuit, telefono, ing_br, inicio_act, logo)
VALUES 
('Mi Empresa S.A.','Calle Falsa 123, Ciudad','20-12345678-9','011-1234-5678','12345','01/01/2010','logo.png');

-- ========================================
-- Tabla proformas
-- ========================================
CREATE TABLE IF NOT EXISTS proformas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    fecha DATE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registro de ejemplo
INSERT INTO proformas(cliente_id, fecha, total) VALUES
(1, CURDATE(), 1500.00),
(2, CURDATE(), 2300.50);

-- ========================================
-- Tabla items de proforma
-- ========================================
CREATE TABLE IF NOT EXISTS items_proforma (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proforma_id INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (proforma_id) REFERENCES proformas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Registros de ejemplo
INSERT INTO items_proforma(proforma_id, descripcion, cantidad, precio, total) VALUES
(1, 'Producto A', 2, 500.00, 1000.00),
(1, 'Producto B', 1, 500.00, 500.00),
(2, 'Servicio X', 3, 500.00, 1500.00),
(2, 'Producto Y', 2, 400.25, 800.50);
