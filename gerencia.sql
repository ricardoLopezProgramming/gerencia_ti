DROP DATABASE IF EXISTS gerencia_ti;

CREATE DATABASE gerencia_ti DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gerencia_ti;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- 2. Departments
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- 3. Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    role_id INT NOT NULL,
    department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- 4. Project statuses
CREATE TABLE project_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- 5. Projects
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status_id INT DEFAULT 1,
    manager_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (status_id) REFERENCES project_statuses(id),
    FOREIGN KEY (manager_id) REFERENCES users(id),
    CONSTRAINT chk_project_dates CHECK (start_date <= end_date)
);

-- 6. Project-user assignments
CREATE TABLE project_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE (project_id, user_id)
);

-- 7. Ticket statuses
CREATE TABLE ticket_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- 8. Tickets
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    status_id INT DEFAULT 1,
    project_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (status_id) REFERENCES ticket_statuses(id),
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

-- 9. Ticket-user assignments
CREATE TABLE ticket_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 10. Daily work logs
CREATE TABLE work_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE (user_id, date)
);

-- 11. Work sessions
CREATE TABLE work_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME DEFAULT NULL,
    FOREIGN KEY (log_id) REFERENCES work_logs(id) ON DELETE CASCADE,
    CONSTRAINT chk_session_times CHECK (end_time IS NULL OR end_time > start_time)
);

-- 12. Audit log (bitacora)
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_name VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    affected_table VARCHAR(100) NOT NULL,
    record_id INT DEFAULT NULL,
    old_data JSON,
    new_data JSON,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    client_ip VARCHAR(50) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

USE gerencia_ti;
-- Desactivar claves foráneas
SET FOREIGN_KEY_CHECKS = 0;
-- Truncamiento de datos
TRUNCATE TABLE work_sessions;
TRUNCATE TABLE work_logs;
TRUNCATE TABLE ticket_users;
TRUNCATE TABLE tickets;
TRUNCATE TABLE project_users;
TRUNCATE TABLE projects;
TRUNCATE TABLE users;
TRUNCATE TABLE ticket_statuses;
TRUNCATE TABLE project_statuses;
TRUNCATE TABLE roles;
TRUNCATE TABLE departments;
TRUNCATE TABLE audit_logs;

SET FOREIGN_KEY_CHECKS = 1;

-- Eliminación de tablas
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS work_sessions;
DROP TABLE IF EXISTS work_logs;

DROP TABLE IF EXISTS ticket_users;
DROP TABLE IF EXISTS tickets;

DROP TABLE IF EXISTS project_users;
DROP TABLE IF EXISTS projects;

DROP TABLE IF EXISTS users;

DROP TABLE IF EXISTS ticket_statuses;
DROP TABLE IF EXISTS project_statuses;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS audit_logs;

-- Activar claves foráneas
SET FOREIGN_KEY_CHECKS = 1;


--roles
INSERT INTO roles(name) VALUES ('empleado'), ('jefe de proyecto'), ('administrador');
INSERT INTO departments(name) VALUES ('departamento de administracion'), ('departamento de ti');
INSERT INTO project_statuses(name) VALUES ('pendiente'), ('en proceso'), ('terminado');
INSERT INTO ticket_statuses(name) VALUES ('pendiente'), ('en proceso'), ('terminado');

--users
-- Admin
INSERT INTO users (name, email, password, role_id, avatar, department_id) VALUES
('ricardo', 'ricardo@myd.com', 'ricardo123',
 (SELECT id FROM roles WHERE name = 'administrador'), '', 
 (SELECT id FROM departments WHERE name = 'departamento de ti'));

-- Jefes de proyecto (IDs 2 al 7)
INSERT INTO users (name, email, password, role_id, avatar, department_id) VALUES
('maría', 'maria@myd.com', 'maria123', 
 (SELECT id FROM roles WHERE name = 'jefe de proyecto'), '', 1),
('esteban', 'esteban@myd.com', 'esteban123', 
 (SELECT id FROM roles WHERE name = 'jefe de proyecto'), '', 1),
('daniela', 'daniela@myd.com', 'daniela123', 
 (SELECT id FROM roles WHERE name = 'jefe de proyecto'), '', 2),
('gabriel', 'gabriel@myd.com', 'gabriel123', 
 (SELECT id FROM roles WHERE name = 'jefe de proyecto'), '', 2),
('noelia', 'noelia@myd.com', 'noelia123', 
 (SELECT id FROM roles WHERE name = 'jefe de proyecto'), '', 1),
('rosa', 'rosa@myd.com', 'rosa123', 
 (SELECT id FROM roles WHERE name = 'jefe de proyecto'), '', 2);

-- Empleados (IDs 8 al 17)
INSERT INTO users (name, email, password, role_id, avatar, department_id) VALUES
('luisa', 'luisa@myd.com', 'luisa123', (SELECT id FROM roles WHERE name = 'empleado'), '', 1),
('jorge', 'jorge@myd.com', 'jorge123', (SELECT id FROM roles WHERE name = 'empleado'), '', 2),
('paola', 'paola@myd.com', 'paola123', (SELECT id FROM roles WHERE name = 'empleado'), '', 1),
('fernanda', 'fernanda@myd.com', 'fernanda123', (SELECT id FROM roles WHERE name = 'empleado'), '', 2),
('iván', 'ivan@myd.com', 'ivan123', (SELECT id FROM roles WHERE name = 'empleado'), '', 1),
('lucía', 'lucia@myd.com', 'lucia123', (SELECT id FROM roles WHERE name = 'empleado'), '', 1),
('carmen', 'carmen@myd.com', 'carmen123', (SELECT id FROM roles WHERE name = 'empleado'), '', 2),
('oscar', 'oscar@myd.com', 'oscar123', (SELECT id FROM roles WHERE name = 'empleado'), '', 1),
('héctor', 'hector@myd.com', 'hector123', (SELECT id FROM roles WHERE name = 'empleado'), '', 1),
('vanessa', 'vanessa@myd.com', 'vanessa123', (SELECT id FROM roles WHERE name = 'empleado'), '', 2);

select * from users;

INSERT INTO projects (name, description, start_date, end_date, status_id, manager_id) VALUES
('Control HVAC', 'Automatización de climatización en oficinas', '2024-01-01', '2024-06-01', 
 (SELECT id FROM project_statuses WHERE name = 'pendiente'), 2),
('Red Eléctrica', 'Reubicación de cableado industrial', '2024-02-15', '2024-08-30',
(SELECT id FROM project_statuses WHERE name = 'pendiente'), 3),
('Auditoría Interna', 'Eficiencia energética y reducción de pérdidas', '2024-03-01', '2024-09-01',
(SELECT id FROM project_statuses WHERE name = 'pendiente'), 4),
('Tareo Móvil', 'Versión móvil del sistema de tareo', '2024-04-01', '2024-10-01',
(SELECT id FROM project_statuses WHERE name = 'pendiente'), 5),
('Seguimiento de Incidencias', 'Sistema para reclamos técnicos', '2024-05-01', '2024-09-15',
(SELECT id FROM project_statuses WHERE name = 'pendiente'), 6);

INSERT INTO tickets (name, description, status_id, project_id) VALUES
('Verificar voltaje', 'Revisión de líneas principales', 1, 2);
-- ('Actualizar PLC', 'Reprogramar lógica de control', 2, 1),
-- ('Revisión UPS', 'Chequeo de respaldo eléctrico', 1, 2),
-- ('Instalar sensores', 'Colocar sensores de corriente', 2, 3),
-- ('Documentar SCADA', 'Actualizar flujos del sistema', 3, 4);

USE gerencia_ti;

INSERT INTO project_users (project_id, user_id) VALUES (1, 8), (1, 9);

UPDATE projects
SET status_id = (SELECT id FROM project_statuses WHERE name = 'terminado')
WHERE name = 'Red Eléctrica';

UPDATE projects
SET status_id = (SELECT id FROM project_statuses WHERE name = 'terminado')
WHERE name = 'Control HVAC';

INSERT INTO ticket_users (ticket_id, user_id)
VALUES
  (1, 8);

INSERT INTO ticket_users (ticket_id, user_id)
VALUES
  (1, 10),  -- Paola
  (1, 12);  -- Lucía



SELECT DISTINCT u.*
FROM users u
INNER JOIN project_users pu ON u.id = pu.user_id
INNER JOIN roles r ON u.role_id = r.id
WHERE pu.project_id = :project_id
  AND LOWER(r.name) = 'empleado'
  AND u.id NOT IN (
    SELECT tu.user_id
    FROM ticket_users tu
    INNER JOIN tickets t ON tu.ticket_id = t.id
    INNER JOIN ticket_statuses ts ON t.status_id = ts.id
    WHERE t.project_id = :project_id
      AND LOWER(ts.name) IN ('pendiente', 'en proceso')
)

select * from users;