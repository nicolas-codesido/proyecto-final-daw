CREATE DATABASE IF NOT EXISTS proyecto CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

USE proyecto;

CREATE TABLE
    IF NOT EXISTS sucursales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL UNIQUE,
        ciudad VARCHAR(100) NOT NULL,
        direccion VARCHAR(255)
    );

INSERT INTO
    sucursales (nombre, ciudad, direccion)
VALUES
    ('Madrid Gran Vía', 'Madrid', 'Calle Gran Vía, 1'),
    (
        'Barcelona Gràcia',
        'Barcelona',
        'Passeig de Gràcia, 10'
    ),
    ('Málaga Centro', 'Málaga', 'Calle Larios, 15'),
    (
        'Sevilla Catedral',
        'Sevilla',
        'Avenida de la Constitución, 20'
    ),
    (
        'Pontevedra Centro',
        'Pontevedra',
        'Praza da Peregrina, 5'
    );

CREATE TABLE
    IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL UNIQUE,
        contraseña VARCHAR(255),
        sucursal_id INT NULL,
        rol ENUM ('asesor', 'manager', 'super') NOT NULL DEFAULT 'asesor',
        especialidad ENUM (
            'General',
            'Inversiones',
            'Patrimonial',
            'Fondos',
            'Créditos'
        ) NULL,
        token_activacion VARCHAR(64) NULL UNIQUE,
        FOREIGN KEY (sucursal_id) REFERENCES sucursales (id) ON DELETE CASCADE
    );

CREATE TABLE
    IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        dni VARCHAR(9) NOT NULL UNIQUE,
        fecha_nacimiento DATE NOT NULL,
        patrimonio DECIMAL(12, 2) DEFAULT 0.00 NOT NULL,
        comision_porcentaje DECIMAL(4, 2) DEFAULT 0.00 NOT NULL,
        perfil_riesgo ENUM ('Conservador', 'Moderado', 'Agresivo') DEFAULT 'Conservador' NOT NULL,
        usuario_id INT NOT NULL,
        notas TEXT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
    );

CREATE TABLE
    IF NOT EXISTS citas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descripcion TEXT,
        fecha_inicio DATETIME NOT NULL,
        fecha_fin DATETIME NOT NULL,
        cliente_id INT NOT NULL,
        usuario_id INT NOT NULL,
        FOREIGN KEY (cliente_id) REFERENCES clientes (id) ON DELETE CASCADE,
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
    );