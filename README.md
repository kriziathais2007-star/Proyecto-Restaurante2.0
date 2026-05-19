# Proyecto-Restaurante2.0
``` mysql
CREATE DATABASE IF NOT EXISTS restaurante_db;
USE restaurante_db;

CREATE TABLE usuario (
Id_Usuario INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL,
rol VARCHAR(20) NOT NULL,
contrasena VARCHAR(100) NOT NULL,
telefono INT NOT NULL,
CONSTRAINT chk_rol CHECK(rol IN('administrador','mozo','cocina'))
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE asistencia (
Id_Asistencia INT AUTO_INCREMENT PRIMARY KEY,
fecha DATE NOT NULL,
hora_entrada TIME NOT NULL,
hora_salida TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
estado VARCHAR(20) NOT NULL,
Id_Usuario INT NOT NULL,
FOREIGN KEY (Id_Usuario) REFERENCES usuario(Id_Usuario),
CONSTRAINT chk_estado_asistencia CHECK(estado IN('asistio','tarde','falta'))
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE plato (
Id_Plato INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL,
precio DECIMAL(10,2) NOT NULL,
disponibilidad BOOLEAN NOT NULL DEFAULT TRUE,
CONSTRAINT chk_precio CHECK(precio > 0)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE pedido (
Id_Pedido INT AUTO_INCREMENT PRIMARY KEY,
mesa INT NOT NULL,
fecha DATE NOT NULL,
hora_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
estado_pedido VARCHAR(20) NOT NULL,
Id_Usuario INT NOT NULL,
FOREIGN KEY (Id_Usuario) REFERENCES usuario(Id_Usuario),
CONSTRAINT chk_estado_pedido CHECK(estado_pedido IN('pendiente','en preparación','servido','entregado'))
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE detalle_pedido (
Id_Detalle INT AUTO_INCREMENT PRIMARY KEY,
cantidad INT NOT NULL,
estado_plato VARCHAR(20) NOT NULL,
Id_Pedido INT NOT NULL,
Id_Plato INT NOT NULL,
FOREIGN KEY (Id_Pedido) REFERENCES pedido(Id_Pedido),
FOREIGN KEY (Id_Plato) REFERENCES plato(Id_Plato),
CONSTRAINT chk_cantidad CHECK (cantidad > 0),
CONSTRAINT chk_estado_plato CHECK(estado_plato IN('falta servir','servido'))
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

ALTER TABLE usuario MODIFY telefono VARCHAR(20) NOT NULL;

USE restaurante_db;

-- ========================================================
-- 1. REGISTROS PARA: usuario
-- (El Id_Usuario se generará automáticamente: 1, 2, 3...)
-- ========================================================
INSERT INTO usuario (nombre, rol, contrasena, telefono) VALUES 
('Thais', 'mozo', '0305', 987654321),
('Ana Gómez', 'mozo', 'anaG_pass', 912345678),
('Luis Peralta', 'administrador', 'admin_luis', 955443322),
('Kevin Silva', 'cocina', 'kevin_chef', 966778899);

-- ========================================================
-- 2. REGISTROS PARA: plato
-- (El Id_Plato se generará automáticamente: 1, 2, 3...)
-- ========================================================
INSERT INTO plato (nombre, precio, disponibilidad) VALUES 
('Ceviche Clásico', 35.00, TRUE),
('Lomo Saltado', 42.50, TRUE),
('Arroz con Pollo', 28.00, FALSE);

-- ========================================================
-- 3. REGISTROS PARA: pedido
-- (Usamos las IDs 1 y 2 que MySQL le asignará a Carlos y Ana)
-- ========================================================
INSERT INTO pedido (mesa, fecha, hora_pedido, estado_pedido, Id_Usuario) VALUES 
(4, '2026-05-19', '2026-05-19 12:15:00', 'entregado', 1), -- Pedido de Carlos
(2, '2026-05-19', '2026-05-19 12:40:00', 'en preparación', 2); -- Pedido de Ana

-- ========================================================
-- 4. REGISTROS PARA: asistencia
-- (Registramos la asistencia usando las IDs automáticas de los usuarios)
-- ========================================================
INSERT INTO asistencia (fecha, hora_entrada, hora_salida, estado, Id_Usuario) VALUES 
('2026-05-18', '08:00:00', '2026-05-18 16:00:00', 'asistio', 1),
('2026-05-18', '08:15:00', '2026-05-18 16:15:00', 'tarde', 2),
('2026-05-19', '07:55:00', '2026-05-19 15:55:00', 'asistio', 1),
('2026-05-19', '00:00:00', '2026-05-19 00:00:00', 'falta', 4);

-- ========================================================
-- 5. REGISTROS PARA: detalle_pedido
-- (Relacionamos los pedidos [1 y 2] con los platos [1 y 2] generados)
-- ========================================================
INSERT INTO detalle_pedido (cantidad, estado_plato, Id_Pedido, Id_Plato) VALUES 
(2, 'servido', 1, 1), -- 2 Ceviches para el primer pedido (Mesa 4)
(1, 'servido', 1, 2), -- 1 Lomo Saltado para el primer pedido (Mesa 4)
(1, 'falta servir', 2, 1); -- 1 Ceviche para el segundo pedido (Mesa 2)
````
