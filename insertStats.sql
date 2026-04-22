-- ============================================
-- USUARIOS (IDs desde 10)
-- Contraseña para todos: "password123"
-- ============================================
INSERT INTO
    usuarios (
        id,
        nombre,
        contraseña,
        sucursal_id,
        rol,
        especialidad,
        token_activacion
    )
VALUES
    -- Madrid Gran Vía (sucursal 1)
    (
        10,
        'Carlos Martínez',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1,
        'manager',
        'General',
        NULL
    ),
    (
        11,
        'Ana García',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1,
        'asesor',
        'Inversiones',
        NULL
    ),
    (
        12,
        'Luis Fernández',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        1,
        'asesor',
        'Patrimonial',
        NULL
    ),
    -- Barcelona Gràcia (sucursal 2)
    (
        13,
        'Laura Sánchez',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        2,
        'manager',
        'General',
        NULL
    ),
    (
        14,
        'David López',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        2,
        'asesor',
        'Fondos',
        NULL
    ),
    (
        15,
        'María Torres',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        2,
        'asesor',
        'Inversiones',
        NULL
    ),
    -- Málaga Centro (sucursal 3)
    (
        16,
        'Pedro Ramírez',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        3,
        'manager',
        'General',
        NULL
    ),
    (
        17,
        'Carmen Ruiz',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        3,
        'asesor',
        'Créditos',
        NULL
    ),
    (
        18,
        'Javier Moreno',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        3,
        'asesor',
        'Patrimonial',
        NULL
    ),
    -- Sevilla Catedral (sucursal 4)
    (
        19,
        'Isabel Díaz',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        4,
        'manager',
        'General',
        NULL
    ),
    (
        20,
        'Roberto Jiménez',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        4,
        'asesor',
        'Fondos',
        NULL
    ),
    (
        21,
        'Elena Romero',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        4,
        'asesor',
        'Inversiones',
        NULL
    ),
    -- Pontevedra Centro (sucursal 5)
    (
        22,
        'Miguel Herrera',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        5,
        'manager',
        'General',
        NULL
    ),
    (
        23,
        'Lucía Castro',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        5,
        'asesor',
        'Créditos',
        NULL
    ),
    (
        24,
        'Alberto Vargas',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        5,
        'asesor',
        'Patrimonial',
        NULL
    );

-- ============================================
-- CLIENTES (IDs desde 10)
-- 50 clientes distribuidos para gráficos óptimos
-- ============================================
INSERT INTO
    clientes (
        id,
        nombre,
        telefono,
        dni,
        fecha_nacimiento,
        patrimonio,
        comision_porcentaje,
        perfil_riesgo,
        usuario_id,
        notas
    )
VALUES
    -- MADRID (12 clientes - Patrimonio ALTO para liderar gráfico)
    (
        10,
        'Alejandro Ruiz Gómez',
        '611223344',
        '12345678A',
        '1975-03-15',
        850000.00,
        1.20,
        'Agresivo',
        11,
        'Cliente VIP - Cartera diversificada'
    ),
    (
        11,
        'Beatriz Morales Silva',
        '622334455',
        '23456789B',
        '1982-07-22',
        620000.00,
        1.00,
        'Moderado',
        11,
        'Interesada en fondos internacionales'
    ),
    (
        12,
        'Fernando Vega Ortiz',
        '633445566',
        '34567890C',
        '1968-11-08',
        1200000.00,
        1.50,
        'Agresivo',
        12,
        'Empresario - Alta liquidez'
    ),
    (
        13,
        'Gloria Navarro Pérez',
        '644556677',
        '45678901D',
        '1990-05-12',
        280000.00,
        0.80,
        'Conservador',
        11,
        'Perfil conservador - Busca seguridad'
    ),
    (
        14,
        'Héctor Campos Ramos',
        '655667788',
        '56789012E',
        '1985-09-30',
        450000.00,
        0.90,
        'Moderado',
        12,
        'Ejecutivo tecnológico'
    ),
    (
        15,
        'Irene Molina Castro',
        '666778899',
        '67890123F',
        '1978-01-18',
        720000.00,
        1.10,
        'Agresivo',
        11,
        'Inversora experimentada'
    ),
    (
        16,
        'Jorge Prieto Delgado',
        '677889900',
        '78901234G',
        '1992-12-03',
        180000.00,
        0.70,
        'Conservador',
        12,
        'Primer inversión importante'
    ),
    (
        17,
        'Cristina Rubio Herrera',
        '688990011',
        '89012345H',
        '1973-06-25',
        950000.00,
        1.30,
        'Agresivo',
        11,
        'Cartera de alto rendimiento'
    ),
    (
        18,
        'Manuel Santos Lara',
        '699001122',
        '90123456I',
        '1988-04-14',
        340000.00,
        0.85,
        'Moderado',
        12,
        'Busca equilibrio riesgo-rentabilidad'
    ),
    (
        19,
        'Patricia Iglesias Mora',
        '600112233',
        '01234567J',
        '1980-08-07',
        510000.00,
        0.95,
        'Moderado',
        11,
        'Profesional sanitario'
    ),
    (
        20,
        'Ricardo Fuentes Gil',
        '611223355',
        '11234567K',
        '1995-02-28',
        125000.00,
        0.60,
        'Conservador',
        12,
        'Joven emprendedor'
    ),
    (
        21,
        'Sandra Méndez Cruz',
        '622334466',
        '21234567L',
        '1986-10-19',
        680000.00,
        1.05,
        'Agresivo',
        11,
        'Deportista profesional retirada'
    ),
    -- BARCELONA (15 clientes - Patrimonio MUY ALTO para destacar)
    (
        22,
        'Antonio Serrano Vidal',
        '633445577',
        '31234567M',
        '1970-05-11',
        1500000.00,
        1.80,
        'Agresivo',
        14,
        'Empresario textil - Top cliente'
    ),
    (
        23,
        'Blanca Reyes Pascual',
        '644556688',
        '41234567N',
        '1983-09-23',
        890000.00,
        1.25,
        'Moderado',
        14,
        'Arquitecta - Cartera balanceada'
    ),
    (
        24,
        'César Domínguez Marín',
        '655667799',
        '51234567O',
        '1977-01-05',
        1100000.00,
        1.45,
        'Agresivo',
        15,
        'Inversor tecnológico'
    ),
    (
        25,
        'Diana Carrasco Núñez',
        '666778800',
        '61234567P',
        '1991-07-17',
        420000.00,
        0.88,
        'Moderado',
        14,
        'Consultora internacional'
    ),
    (
        26,
        'Eduardo Parra Soto',
        '677889911',
        '71234567Q',
        '1965-11-29',
        1800000.00,
        2.00,
        'Agresivo',
        15,
        'Patrimonio familiar - VIP Premium'
    ),
    (
        27,
        'Francisca León Cortés',
        '688990022',
        '81234567R',
        '1989-03-08',
        560000.00,
        1.00,
        'Moderado',
        14,
        'Médica especialista'
    ),
    (
        28,
        'Guillermo Rojas Benítez',
        '699001133',
        '91234567S',
        '1974-12-20',
        980000.00,
        1.35,
        'Agresivo',
        15,
        'Inversor inmobiliario'
    ),
    (
        29,
        'Helena Vargas Cabrera',
        '600112244',
        '02345678T',
        '1987-06-02',
        650000.00,
        1.08,
        'Moderado',
        14,
        'Abogada corporativa'
    ),
    (
        30,
        'Ignacio Flores Medina',
        '611223366',
        '12345679U',
        '1993-04-15',
        290000.00,
        0.75,
        'Conservador',
        15,
        'Ingeniero junior'
    ),
    (
        31,
        'Julia Crespo Romero',
        '622334477',
        '22345678V',
        '1979-08-27',
        1350000.00,
        1.65,
        'Agresivo',
        14,
        'Ejecutiva bancaria'
    ),
    (
        32,
        'Leonardo Giménez Torres',
        '633445588',
        '32345678W',
        '1984-02-10',
        470000.00,
        0.92,
        'Moderado',
        15,
        'Comercio internacional'
    ),
    (
        33,
        'Mónica Peña Aguilar',
        '644556699',
        '42345678X',
        '1972-10-03',
        1050000.00,
        1.40,
        'Agresivo',
        14,
        'Empresaria hostelería'
    ),
    (
        34,
        'Nicolás Ortega Luna',
        '655667700',
        '52345678Y',
        '1996-12-16',
        195000.00,
        0.68,
        'Conservador',
        15,
        'Freelance tecnológico'
    ),
    (
        35,
        'Olivia Ramírez Blanco',
        '666778811',
        '62345678Z',
        '1981-05-30',
        780000.00,
        1.18,
        'Agresivo',
        14,
        'Diseñadora gráfica senior'
    ),
    (
        36,
        'Pablo Sanz Ferrer',
        '677889922',
        '72345678A',
        '1988-09-12',
        520000.00,
        0.98,
        'Moderado',
        15,
        'Piloto comercial'
    ),
    -- MÁLAGA (8 clientes - Patrimonio MEDIO)
    (
        37,
        'Raquel Márquez Gallego',
        '688990033',
        '82345678B',
        '1976-03-25',
        380000.00,
        0.82,
        'Moderado',
        17,
        'Profesora universitaria'
    ),
    (
        38,
        'Sergio Herrero Jiménez',
        '699001144',
        '92345678C',
        '1985-07-07',
        240000.00,
        0.72,
        'Conservador',
        17,
        'Funcionario público'
    ),
    (
        39,
        'Teresa Garrido Santos',
        '600112255',
        '03456789D',
        '1990-11-19',
        310000.00,
        0.78,
        'Moderado',
        18,
        'Farmacéutica'
    ),
    (
        40,
        'Ulises Bravo Muñoz',
        '611223377',
        '13456789E',
        '1982-01-31',
        450000.00,
        0.90,
        'Moderado',
        17,
        'Dentista - Consulta propia'
    ),
    (
        41,
        'Vanesa Cortés Ibáñez',
        '622334488',
        '23456789F',
        '1973-06-14',
        190000.00,
        0.65,
        'Conservador',
        18,
        'Administrativa'
    ),
    (
        42,
        'Walter Núñez Salas',
        '633445599',
        '33456789G',
        '1987-09-26',
        520000.00,
        0.95,
        'Agresivo',
        17,
        'Promotor inmobiliario'
    ),
    (
        43,
        'Yolanda Mendoza Ríos',
        '644556600',
        '43456789H',
        '1994-04-08',
        155000.00,
        0.62,
        'Conservador',
        18,
        'Fisioterapeuta'
    ),
    (
        44,
        'Zacarías Vázquez Peña',
        '655667711',
        '53456789I',
        '1980-12-21',
        410000.00,
        0.88,
        'Moderado',
        17,
        'Veterinario'
    ),
    -- SEVILLA (10 clientes - Patrimonio MEDIO-ALTO)
    (
        45,
        'Adriana Cabrera Montero',
        '666778822',
        '63456789J',
        '1975-05-03',
        670000.00,
        1.12,
        'Agresivo',
        20,
        'Notaria'
    ),
    (
        46,
        'Bruno Castillo Ramos',
        '677889933',
        '73456789K',
        '1989-08-15',
        320000.00,
        0.80,
        'Moderado',
        20,
        'Periodista'
    ),
    (
        47,
        'Claudia Hidalgo Martín',
        '688990044',
        '83456789L',
        '1983-02-27',
        540000.00,
        1.00,
        'Moderado',
        21,
        'Contable senior'
    ),
    (
        48,
        'Damián Rosales Vargas',
        '699001155',
        '93456789M',
        '1971-10-09',
        890000.00,
        1.28,
        'Agresivo',
        20,
        'Cirujano'
    ),
    (
        49,
        'Elisa Santiago Cruz',
        '600112266',
        '04567890N',
        '1992-06-21',
        265000.00,
        0.75,
        'Conservador',
        21,
        'Psicóloga clínica'
    ),
    (
        50,
        'Fabio Gutiérrez Prado',
        '611223388',
        '14567890O',
        '1986-03-13',
        480000.00,
        0.92,
        'Moderado',
        20,
        'Ingeniero civil'
    ),
    (
        51,
        'Gemma Navarro León',
        '622334499',
        '24567890P',
        '1978-11-05',
        725000.00,
        1.15,
        'Agresivo',
        21,
        'Farmacéutica - Cadena propia'
    ),
    (
        52,
        'Hugo Esteban Campos',
        '633445500',
        '34567890Q',
        '1995-07-28',
        175000.00,
        0.68,
        'Conservador',
        20,
        'Desarrollador web'
    ),
    (
        53,
        'Inés Ruiz Fernández',
        '644556611',
        '44567890R',
        '1981-01-17',
        590000.00,
        1.05,
        'Moderado',
        21,
        'Directora marketing'
    ),
    (
        54,
        'Jaime Morales Díaz',
        '655667722',
        '54567890S',
        '1974-09-30',
        820000.00,
        1.22,
        'Agresivo',
        20,
        'Asesor fiscal'
    ),
    -- PONTEVEDRA (5 clientes - Patrimonio BAJO-MEDIO para contraste)
    (
        55,
        'Karina López Gil',
        '666778833',
        '64567890T',
        '1988-04-22',
        185000.00,
        0.65,
        'Conservador',
        23,
        'Profesora secundaria'
    ),
    (
        56,
        'Lorenzo Benítez Soto',
        '677889944',
        '74567890U',
        '1993-12-05',
        220000.00,
        0.70,
        'Conservador',
        23,
        'Técnico informático'
    ),
    (
        57,
        'Marina Pascual Ortiz',
        '688990055',
        '84567890V',
        '1979-08-18',
        305000.00,
        0.78,
        'Moderado',
        24,
        'Bióloga marina'
    ),
    (
        58,
        'Néstor Delgado Mora',
        '699001166',
        '94567890W',
        '1986-02-11',
        260000.00,
        0.73,
        'Moderado',
        23,
        'Arquitecto técnico'
    ),
    (
        59,
        'Olga Serrano Cabrera',
        '600112277',
        '05678901X',
        '1991-10-24',
        340000.00,
        0.82,
        'Moderado',
        24,
        'Enfermera quirúrgica'
    );

-- ============================================
-- CITAS (IDs desde 10)
-- Algunas citas de ejemplo
-- ============================================
INSERT INTO
    citas (
        id,
        descripcion,
        fecha_inicio,
        fecha_fin,
        cliente_id,
        usuario_id
    )
VALUES
    (
        10,
        'Revisión cartera anual',
        '2025-11-10 10:00:00',
        '2025-11-10 11:00:00',
        10,
        11
    ),
    (
        11,
        'Análisis fondos internacionales',
        '2025-11-10 12:00:00',
        '2025-11-10 13:00:00',
        22,
        14
    ),
    (
        12,
        'Propuesta inversión inmobiliaria',
        '2025-11-11 09:30:00',
        '2025-11-11 10:30:00',
        26,
        15
    ),
    (
        13,
        'Apertura cuenta patrimonial',
        '2025-11-11 16:00:00',
        '2025-11-11 17:00:00',
        12,
        12
    ),
    (
        14,
        'Seguimiento trimestral',
        '2025-11-12 11:00:00',
        '2025-11-12 12:00:00',
        45,
        20
    ),
    (
        15,
        'Asesoramiento fiscal',
        '2025-11-12 15:00:00',
        '2025-11-12 16:00:00',
        31,
        14
    ),
    (
        16,
        'Consulta fondos conservadores',
        '2025-11-13 10:00:00',
        '2025-11-13 11:00:00',
        38,
        17
    ),
    (
        17,
        'Diversificación cartera',
        '2025-11-13 12:30:00',
        '2025-11-13 13:30:00',
        48,
        21
    ),
    (
        18,
        'Planificación jubilación',
        '2025-11-14 09:00:00',
        '2025-11-14 10:00:00',
        17,
        12
    ),
    (
        19,
        'Rebalanceo portfolio',
        '2025-11-14 11:00:00',
        '2025-11-14 12:00:00',
        28,
        15
    ),
    (
        20,
        'Primera inversión',
        '2025-11-15 10:30:00',
        '2025-11-15 11:30:00',
        55,
        23
    );