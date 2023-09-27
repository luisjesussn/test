CREATE TABLE IF NOT EXISTS formulario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    alias VARCHAR(50),
    rut VARCHAR(12),
    email VARCHAR(100),
    state VARCHAR(50),
    province VARCHAR(50),
    candidate VARCHAR(50),
    with_us VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT,
    name VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_region) REFERENCES states(id)
);


INSERT INTO states (name)
SELECT 'Region de Valparaíso' WHERE NOT EXISTS (SELECT * FROM states WHERE id = 1 LIMIT 1);


INSERT INTO provinces (id_region, name)
SELECT r.id, c.name
FROM states r
JOIN (
    SELECT 'Valparaiso' AS name UNION ALL
    SELECT 'Viña del Mar' UNION ALL
    SELECT 'Quillota' UNION ALL
    SELECT 'San Antonio'
) c ON r.name = 'Región de Valparaiso';

INSERT INTO states (name)
SELECT 'Región Metropolitana de Santiago' WHERE NOT EXISTS (SELECT * FROM states WHERE id = 2 LIMIT 1);

INSERT INTO provinces (id_region, name)
SELECT r.id, c.name
FROM states r
JOIN (
    SELECT 'Santiago' AS name UNION ALL
    SELECT 'Maipu' UNION ALL
    SELECT 'Las Condes' UNION ALL
    SELECT 'Puente Alto'
) c ON r.name = 'Región Metropolitana de Santiago';

INSERT INTO candidates (name)
SELECT 'Candidato A' WHERE NOT EXISTS (SELECT * FROM candidates WHERE name = 'Candidato A' LIMIT 1);
INSERT INTO candidates (name)
SELECT 'Candidato B' WHERE NOT EXISTS (SELECT * FROM candidates WHERE name = 'Candidato B' LIMIT 1);
INSERT INTO candidates (name)
SELECT 'Candidato C' WHERE NOT EXISTS (SELECT * FROM candidates WHERE name = 'Candidato C' LIMIT 1);