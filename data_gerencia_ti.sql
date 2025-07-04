DROP DATABASE gerencia_ti;-- Crear base de datos

CREATE DATABASE IF NOT EXISTS gerencia_ti DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gerencia_ti;

-- 1. Tabla de roles
CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 2. Tabla de departamentos
CREATE TABLE departamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

-- 3. Tabla de usuarios
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    imagen VARCHAR(255),
    rol_id INT NOT NULL,
    departamento_id INT,
    FOREIGN KEY (rol_id) REFERENCES rol(id),
    FOREIGN KEY (departamento_id) REFERENCES departamento(id)
);

-- 4. Tabla de estados de proyecto
CREATE TABLE estado_proyecto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 5. Tabla de proyectos
CREATE TABLE proyecto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado_id INT DEFAULT 1,
    jefe_id INT NOT NULL, -- jefe de proyecto
    FOREIGN KEY (estado_id) REFERENCES estado_proyecto(id),
    FOREIGN KEY (jefe_id) REFERENCES usuario(id),
    CONSTRAINT chk_fechas_proyecto CHECK (fecha_inicio <= fecha_fin)
);

-- 6. Relación proyectos <-> empleados asignados
CREATE TABLE proyecto_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (proyecto_id) REFERENCES proyecto(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    UNIQUE (proyecto_id, usuario_id)
);

-- 7. Tabla de estados de ticket
CREATE TABLE estado_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 8. Tabla de tickets
CREATE TABLE ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    estado_id INT DEFAULT 1,
    proyecto_id INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estado_ticket(id),
    FOREIGN KEY (proyecto_id) REFERENCES proyecto(id)
);

-- 9. Relación ticket <-> usuario asignado
CREATE TABLE ticket_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    usuario_id INT NOT NULL UNIQUE, -- Solo un ticket activo por usuario
    asignado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES ticket(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- 10. Registro laboral diario
CREATE TABLE registro_laboral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    observacion TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    UNIQUE (usuario_id, fecha) -- Un registro por usuario por día
);

-- 11. Sesiones dentro del registro
CREATE TABLE sesiones_laborales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    hora_inicio DATETIME NOT NULL,
    hora_fin DATETIME DEFAULT NULL,
    FOREIGN KEY (registro_id) REFERENCES registro_laboral(id) ON DELETE CASCADE,
    CONSTRAINT chk_horas_validas CHECK (hora_fin IS NULL OR hora_fin > hora_inicio)
);

CREATE TABLE bitacora (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    rol VARCHAR(50) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    tabla_afectada VARCHAR(100) NOT NULL,
    registro_id INT DEFAULT NULL,
    datos_anteriores JSON,
    datos_nuevos JSON,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_cliente VARCHAR(50) DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- 12. Índices recomendados para rendimiento
CREATE INDEX idx_usuario_rol ON usuario(rol_id);
CREATE INDEX idx_usuario_depto ON usuario(departamento_id);
CREATE INDEX idx_ticket_estado ON ticket(estado_id);
CREATE INDEX idx_proyecto_estado ON proyecto(estado_id);

insert into rol(nombre) values
('empleado'),
('jefe de proyecto'),
('administrador');

INSERT INTO departamento (nombre) VALUES
('departamento de administracion'),
('departamento de ti');

-- INSERT INTO departamento (nombre) VALUES
-- ('Gerencia General'),
-- ('Departamento de Ingeniería'),
-- ('Departamento de Proyectos'),
-- ('Departamento de Mantenimiento'),
-- ('Departamento de Recursos Humanos'),
-- ('Departamento de Sistemas'),
-- ('Departamento Administrativo'),
-- ('Departamento Comercial');

insert into estado_proyecto (nombre) values
('pendiente'),
('en proceso'),
('terminado');

-- Asegúrate de tener roles y departamentos creados previamente

INSERT INTO usuario (nombre, correo, `password`, rol_id, imagen, departamento_id)
VALUES
 ('ricardo', 'ricardo@myd.com', 'ricardo123', 
 (SELECT id FROM rol WHERE nombre = 'administrador'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti'));
select * from usuario;
-- Administrador
INSERT INTO usuario (nombre, correo, `password`, rol_id, imagen, departamento_id)
VALUES
 ('maria', 'maria@myd.com', 'maria123', 
 (SELECT id FROM rol WHERE nombre = 'jefe de proyecto'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('jose', 'jose@myd.com', 'jose123', 
 (SELECT id FROM rol WHERE nombre = 'jefe de proyecto'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('franco', 'franco@myd.com', 'franco123', 
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('luis', 'luis@myd.com', 'luis123', 
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('sofia', 'sofia@myd.com', 'sofia123', 
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti'));

INSERT INTO usuario (nombre, correo, `password`, rol_id, imagen, departamento_id) VALUES
('juan', 'juan@myd.com', 'juan123',
 (SELECT id FROM rol WHERE nombre = 'administrador'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion'));

INSERT INTO usuario (nombre, correo, `password`, rol_id, imagen, departamento_id) VALUES
('carla flores', 'carla.flores@myd.com', 'admin456',
 (SELECT id FROM rol WHERE nombre = 'administrador'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('david quispe', 'david.quispe@myd.com', 'jefe123',
 (SELECT id FROM rol WHERE nombre = 'jefe de proyecto'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('ana martinez', 'ana.martinez@myd.com', 'jefe456',
 (SELECT id FROM rol WHERE nombre = 'jefe de proyecto'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('jorge perez', 'jorge.perez@myd.com', 'jefe789',
 (SELECT id FROM rol WHERE nombre = 'jefe de proyecto'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('lucia reyes', 'lucia.reyes@myd.com', 'emp123',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('marco rojas', 'marco.rojas@myd.com', 'emp456',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('elena lopez', 'elena.lopez@myd.com', 'emp789',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('carlos diaz', 'carlos.diaz@myd.com', 'emp321',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('valeria castro', 'valeria.castro@myd.com', 'emp654',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('diego villanueva', 'diego.villanueva@myd.com', 'emp987',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('maria ruiz', 'maria.ruiz@myd.com', 'emp000',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('renato torres', 'renato.torres@myd.com', 'emp111',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion')),
('natalia campos', 'natalia.campos@myd.com', 'emp222',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de ti')),
('sebastian galvez', 'sebastian.galvez@myd.com', 'emp333',
 (SELECT id FROM rol WHERE nombre = 'empleado'), '', 
 (SELECT id FROM departamento WHERE nombre = 'departamento de administracion'));


INSERT INTO proyecto (nombre, descripcion, fecha_inicio, fecha_fin, estado_id, jefe_id) VALUES
('Sistema de Gestión Energética', 'Plataforma para monitoreo y control de consumo eléctrico en industrias', '2024-01-10', '2024-06-30',
 (SELECT id FROM estado_proyecto WHERE nombre = 'abierto'),
 (SELECT id FROM usuario WHERE correo = 'david.quispe@myd.com')),

('App de Mantenimiento Predictivo', 'Aplicación móvil para registrar y alertar mantenimientos preventivos', '2024-02-01', '2024-07-15',
 (SELECT id FROM estado_proyecto WHERE nombre = 'en proceso'),
 (SELECT id FROM usuario WHERE correo = 'ana.martinez@myd.com')),

('Software de Inventario Eléctrico', 'Sistema para inventario de materiales y herramientas eléctricas', '2024-03-05', '2024-08-10',
 (SELECT id FROM estado_proyecto WHERE nombre = 'cerrado'),
 (SELECT id FROM usuario WHERE correo = 'jorge.perez@myd.com')),

('Panel de Indicadores Eléctricos', 'Dashboard web con reportes de indicadores clave de rendimiento (KPIs)', '2024-04-01', '2024-09-30',
 (SELECT id FROM estado_proyecto WHERE nombre = 'abierto'),
 (SELECT id FROM usuario WHERE correo = 'ana.martinez@myd.com')),

('Sistema de Registro TareoApp', 'Sistema interno para control de asistencia y tiempos laborales', '2024-05-10', '2024-10-15',
 (SELECT id FROM estado_proyecto WHERE nombre = 'en proceso'),
 (SELECT id FROM usuario WHERE correo = 'david.quispe@myd.com')),

('Plataforma de Reportes Técnicos', 'Herramienta web para generar y enviar reportes técnicos de inspección', '2024-06-01', '2024-11-01',
 (SELECT id FROM estado_proyecto WHERE nombre = 'abierto'),
 (SELECT id FROM usuario WHERE correo = 'jorge.perez@myd.com')),

('Gestor de Contratos Eléctricos', 'Sistema para gestionar contratos y convenios con proveedores eléctricos', '2024-02-15', '2024-07-30',
 (SELECT id FROM estado_proyecto WHERE nombre = 'cerrado'),
 (SELECT id FROM usuario WHERE correo = 'ana.martinez@myd.com')),

('Control de Riesgos Eléctricos', 'Aplicación para seguimiento de riesgos y medidas de seguridad eléctrica', '2024-03-20', '2024-08-25',
 (SELECT id FROM estado_proyecto WHERE nombre = 'abierto'),
 (SELECT id FROM usuario WHERE correo = 'david.quispe@myd.com')),

('Gestión de Personal Técnico', 'Sistema para registrar perfiles, horarios y actividades del personal técnico', '2024-01-25', '2024-06-10',
 (SELECT id FROM estado_proyecto WHERE nombre = 'en proceso'),
 (SELECT id FROM usuario WHERE correo = 'jorge.perez@myd.com')),

('Sistema de Capacitación Interna', 'Plataforma e-learning para cursos internos del personal técnico', '2024-03-01', '2024-08-01',
 (SELECT id FROM estado_proyecto WHERE nombre = 'abierto'),
 (SELECT id FROM usuario WHERE correo = 'ana.martinez@myd.com'));

insert into estado_ticket (nombre) values
('pendiente'),
('en proceso'),
('terminado');

-- 15 registros de tickets
INSERT INTO ticket (nombre, descripcion, estado_id, proyecto_id) VALUES
('Revisión de cableado', 'Inspección del cableado de la zona industrial', 1, 1),
('Actualización de firmware', 'Actualizar software de controladores eléctricos', 2, 1),
('Reemplazo de sensores', 'Sustituir sensores defectuosos en la planta B', 3, 2),
('Verificación de medidores', 'Medición del consumo en nuevas instalaciones', 1, 2),
('Configuración de sistema SCADA', 'Configurar paneles de monitoreo en tiempo real', 2, 3),
('Capacitación técnica', 'Preparar curso de uso del nuevo sistema', 1, 3),
('Mantenimiento preventivo', 'Lubricar y revisar equipos principales', 2, 4),
('Auditoría energética', 'Analizar registros de consumo mensual', 3, 4),
('Carga de base de datos', 'Ingresar datos históricos al sistema', 1, 5),
('Creación de reporte mensual', 'Generar y exportar informes del mes', 2, 5),
('Integración con ERP', 'Vincular sistema de inventario al ERP central', 2, 6),
('Análisis de alarmas', 'Evaluar alarmas frecuentes en el sistema', 3, 6),
('Pruebas de sensores de temperatura', 'Revisión y calibración de sensores críticos', 1, 7),
('Depuración de errores', 'Detectar bugs en módulo de reportes', 2, 8),
('Documentación técnica', 'Redactar manual de uso para usuarios finales', 3, 9);

-- Asegúrate de obtener 15 IDs únicos de empleados
-- (puedes ejecutar esto en SQL si lo necesitas)
-- SELECT id FROM usuario WHERE rol_id = (SELECT id FROM rol WHERE nombre = 'empleado') LIMIT 15;

-- Inserciones en ticket_usuario
INSERT INTO ticket_usuario (ticket_id, usuario_id) VALUES
(1, 4),
(2, 5),
(3, 6),
(4, 12),
(5, 13),
(6, 14),
(7, 15),
(8, 16),
(9, 17),
(10, 18),
(11, 19),
(12, 20),
(13, 21),
(14, 22),
(15, 23);


USE gerencia_ti;
-- REINICIO TOTAL DE TABLAS
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE sesiones_laborales;
TRUNCATE TABLE registro_laboral;

TRUNCATE TABLE ticket_usuario;
TRUNCATE TABLE ticket;

TRUNCATE TABLE proyecto_usuario;
TRUNCATE TABLE proyecto;

TRUNCATE TABLE usuario;

TRUNCATE TABLE estado_ticket;
TRUNCATE TABLE estado_proyecto;
TRUNCATE TABLE rol;
TRUNCATE TABLE departamento;
TRUNCATE TABLE bitacora;

SET FOREIGN_KEY_CHECKS = 1;

-- ELIMINACION DE TABLAS

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS sesiones_laborales;
DROP TABLE IF EXISTS registro_laboral;

DROP TABLE IF EXISTS ticket_usuario;
DROP TABLE IF EXISTS ticket;

DROP TABLE IF EXISTS proyecto_usuario;
DROP TABLE IF EXISTS proyecto;

DROP TABLE IF EXISTS usuario;

DROP TABLE IF EXISTS estado_ticket;
DROP TABLE IF EXISTS estado_proyecto;
DROP TABLE IF EXISTS rol;
DROP TABLE IF EXISTS departamento;
DROP TABLE IF EXISTS bitacora;

SET FOREIGN_KEY_CHECKS = 1;

SELECT * FROM proyecto;

SELECT * FROM proyecto
WHERE estado_id NOT IN (SELECT id FROM estado_proyecto)
   OR jefe_id NOT IN (SELECT id FROM usuario);

SELECT p.*, ep.nombre AS estado, u.nombre AS autor FROM proyecto p LEFT JOIN estado_proyecto ep ON p.estado_id = ep.id LEFT JOIN usuario u ON p.jefe_id = u.id;

DROP DATABASE IF EXISTS gerencia_ti;
CREATE DATABASE gerencia_ti DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gerencia_ti;

-- 1. Roles
CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 2. Departamentos
CREATE TABLE departamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

-- 3. Usuarios
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    imagen VARCHAR(255),
    rol_id INT NOT NULL,
    departamento_id INT,
    FOREIGN KEY (rol_id) REFERENCES rol(id),
    FOREIGN KEY (departamento_id) REFERENCES departamento(id)
);

-- 4. Estados de proyecto
CREATE TABLE estado_proyecto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 5. Proyectos
CREATE TABLE proyecto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado_id INT DEFAULT 1,
    jefe_id INT NOT NULL,
    FOREIGN KEY (estado_id) REFERENCES estado_proyecto(id),
    FOREIGN KEY (jefe_id) REFERENCES usuario(id),
    CONSTRAINT chk_fechas_proyecto CHECK (fecha_inicio <= fecha_fin)
);

-- 6. Asignación de empleados a proyectos
CREATE TABLE proyecto_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (proyecto_id) REFERENCES proyecto(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    UNIQUE (proyecto_id, usuario_id)
);

-- 7. Estados de ticket
CREATE TABLE estado_ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- 8. Tickets
CREATE TABLE ticket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    estado_id INT DEFAULT 1,
    proyecto_id INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estado_id) REFERENCES estado_ticket(id),
    FOREIGN KEY (proyecto_id) REFERENCES proyecto(id)
);

-- 9. Asignación única de tickets a empleados
CREATE TABLE ticket_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    usuario_id INT NOT NULL,
    asignado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES ticket(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- 10. Registro laboral por día
CREATE TABLE registro_laboral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    observacion TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    UNIQUE (usuario_id, fecha)
);
SELECT u.nombre, r.fecha, s.hora_inicio, s.hora_fin
FROM sesiones_laborales s
JOIN registro_laboral r ON s.registro_id = r.id
JOIN usuario u ON r.usuario_id = u.id
ORDER BY r.fecha DESC;

-- 11. Sesiones laborales (por día)
CREATE TABLE sesiones_laborales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    hora_inicio DATETIME NOT NULL,
    hora_fin DATETIME DEFAULT NULL,
    FOREIGN KEY (registro_id) REFERENCES registro_laboral(id) ON DELETE CASCADE,
    CONSTRAINT chk_horas_validas CHECK (hora_fin IS NULL OR hora_fin > hora_inicio)
);

-- 12. Bitácora (auditoría de acciones)
CREATE TABLE bitacora (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    rol VARCHAR(50) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    tabla_afectada VARCHAR(100) NOT NULL,
    registro_id INT DEFAULT NULL,
    datos_anteriores JSON,
    datos_nuevos JSON,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_cliente VARCHAR(50) DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- Roles
INSERT INTO rol(nombre) VALUES ('empleado'), ('jefe de proyecto'), ('administrador');

-- Departamentos
INSERT INTO departamento(nombre) VALUES ('departamento de administracion'), ('departamento de ti');

-- Estados de proyecto
INSERT INTO estado_proyecto(nombre) VALUES ('pendiente'), ('en proceso'), ('terminado');

-- Estados de ticket
INSERT INTO estado_ticket(nombre) VALUES ('pendiente'), ('en proceso'), ('terminado');

INSERT INTO usuario(nombre, correo, `password`, rol_id, imagen, departamento_id) VALUES
('Andrés Romero', 'andres.romero@myd.com', 'romero123', 1, '', 1),
('Luisa Mendoza', 'luisa.mendoza@myd.com', 'mendoza456', 1, '', 2),
('Jorge Acosta', 'jorge.acosta@myd.com', 'jorge789', 1, '', 1),
('Daniela Ruiz', 'daniela.ruiz@myd.com', 'daniela321', 2, '', 2),
('Esteban Salas', 'esteban.salas@myd.com', 'esteban999', 2, '', 1),
('Paola Chávez', 'paola.chavez@myd.com', 'paola555', 1, '', 2),
('Raúl Gómez', 'raul.gomez@myd.com', 'gomez111', 3, '', 1),
('Fernanda Paredes', 'fernanda.paredes@myd.com', 'fernanda333', 1, '', 2),
('Alonso Vargas', 'alonso.vargas@myd.com', 'alonso222', 1, '', 2),
('Noelia Torres', 'noelia.torres@myd.com', 'noelia000', 2, '', 1),
('Iván Carrillo', 'ivan.carrillo@myd.com', 'ivan999', 1, '', 2),
('Lucía Castillo', 'lucia.castillo@myd.com', 'lucia666', 1, '', 1),
('Gabriel Peña', 'gabriel.pena@myd.com', 'gabriel888', 2, '', 2),
('Marina Aguilar', 'marina.aguilar@myd.com', 'marina777', 1, '', 2),
('Felipe Navarro', 'felipe.navarro@myd.com', 'felipe444', 3, '', 1),
('Carmen Mejía', 'carmen.mejia@myd.com', 'carmen111', 1, '', 1),
('Oscar Delgado', 'oscar.delgado@myd.com', 'oscar222', 1, '', 2),
('Rosa Vásquez', 'rosa.vasquez@myd.com', 'rosa555', 2, '', 2),
('Héctor Córdova', 'hector.cordova@myd.com', 'hector000', 1, '', 1),
('Vanessa Quispe', 'vanessa.quispe@myd.com', 'vanessa333', 1, '', 2);

INSERT INTO proyecto(nombre, descripcion, fecha_inicio, fecha_fin, estado_id, jefe_id) VALUES
('Sistema de Control HVAC', 'Proyecto para automatización de climatización en oficinas', '2024-01-01', '2024-06-01', 1, 5),
('Rediseño de Red Eléctrica', 'Planificación de reubicación de cableado industrial', '2024-02-15', '2024-08-30', 2, 10),
('Auditoría Interna Eléctrica', 'Control de eficiencia energética y reducción de pérdidas', '2024-03-01', '2024-09-01', 1, 13),
('Tareo Digital Móvil', 'Versión móvil para tareo desde campo', '2024-04-01', '2024-10-01', 2, 4),
('Plataforma de Reclamos Técnicos', 'Sistema para seguimiento de incidencias técnicas', '2024-05-01', '2024-09-15', 3, 11),
('Gestión de Tareas Remotas', 'App para organizar tareas de técnicos externos', '2024-01-20', '2024-07-15', 2, 17),
('Monitor Energético Solar', 'Proyecto para monitoreo en tiempo real de paneles solares', '2024-02-10', '2024-08-10', 1, 13),
('Sistema de Evaluación Laboral', 'Evaluación semestral de desempeño del personal', '2024-03-25', '2024-09-25', 3, 19),
('Base de Datos Histórica', 'Migración y limpieza de datos antiguos de mantenimiento', '2024-01-05', '2024-07-30', 1, 5),
('Panel de Indicadores de Consumo', 'Visualización de consumo eléctrico en tiempo real', '2024-02-12', '2024-06-30', 2, 10);

INSERT INTO ticket(nombre, descripcion, estado_id, proyecto_id) VALUES
('Revisión de voltaje', 'Medición de líneas principales en planta A', 1, 1),
('Reprogramación de PLCs', 'Actualizar lógica de control en tableros', 2, 1),
('Verificación UPS', 'Revisión de funcionamiento de backup de energía', 1, 2),
('Instalación de sensores', 'Sensores de corriente en máquinas críticas', 2, 3),
('Documentación SCADA', 'Actualizar manuales y flujos de datos', 3, 4),
('Pruebas de backup', 'Validar copias automáticas de datos', 1, 4),
('Diseño de dashboard', 'Diseñar panel web de supervisión', 2, 5),
('Revisión de logs', 'Revisar logs de acceso al sistema', 2, 6),
('Análisis de consumos', 'Detectar picos anómalos por semana', 1, 6),
('Soporte a usuario final', 'Atención de reclamos por lentitud', 3, 7),
('Configuración de nodos', 'Agregar nuevos nodos de monitoreo', 1, 8),
('Redacción de reportes', 'Escribir informes diarios automatizados', 2, 9),
('Evaluación mensual', 'Registrar desempeño de empleados', 1, 10),
('Calibración de sensores', 'Ajustar niveles de lectura de datos', 3, 5),
('Migración de scripts', 'Llevar scripts antiguos a nueva plataforma', 2, 3),
('Test de alertas', 'Verificar sistema de alarmas en tiempo real', 1, 4),
('Sincronización de backups', 'Sincronizar respaldos entre oficinas', 3, 7),
('Capacitación SCADA', 'Formar técnicos en uso del sistema', 1, 8),
('Validación de formularios', 'Asegurar que los formularios se envíen bien', 2, 9),
('Depuración de errores', 'Resolver bugs encontrados en pruebas', 3, 10);

-- Suponiendo que usuarios del ID 4 al 23 son empleados
INSERT INTO ticket_usuario (ticket_id, usuario_id) VALUES
(1, 1),
(1, 2),
(3, 3),
(4, 6),
(5, 8);

-- (6, 9),
-- (7, 10),
-- (8, 11),
-- (9, 12),
-- (10, 13),
-- (11, 14),
-- (12, 15),
-- (13, 16),
-- (14, 17),
-- (15, 18),
-- (16, 19),
-- (17, 20),
-- (18, 21),
-- (19, 22),
-- (20, 23);

-- Relación proyecto_usuario (asignación de empleados a proyectos)

INSERT INTO proyecto_usuario (proyecto_id, usuario_id) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 6);

-- (3, 8),
-- (3, 9),
-- (4, 10),
-- (5, 11),
-- (6, 12),
-- (6, 13),
-- (7, 14),
-- (7, 15),
-- (8, 16),
-- (8, 17),
-- (9, 18),
-- (9, 19),
-- (10, 20),
-- (10, 21),
-- (5, 22),
-- (3, 23);

select * from usuario;