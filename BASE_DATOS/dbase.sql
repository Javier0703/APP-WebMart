-- CREAR LA DB Y USARLA
CREATE DATABASE WEBMART;
USE WEBMART;

-- CREAR LAS TABLAS
CREATE TABLE categorias(
    ID_CAT TINYINT AUTO_INCREMENT,
    NOMBRE VARCHAR(30) NOT NULL,
    CONSTRAINT categorias_PK PRIMARY KEY(ID_CAT)
);

CREATE TABLE subcategorias(
    ID_SUB SMALLINT AUTO_INCREMENT,
    ID_CAT TINYINT NOT NULL,
    NOMBRE VARCHAR(30) NOT NULL,
    CONSTRAINT subcategorias_PK PRIMARY KEY(ID_SUB),
    CONSTRAINT subcategorias_FK FOREIGN KEY(ID_CAT) REFERENCES categorias (ID_CAT)
);

CREATE TABLE usuarios(
    ID_USU SMALLINT AUTO_INCREMENT,
    USUARIO VARCHAR(30) NOT NULL,
    CONTRASEÑA VARCHAR(255) NOT NULL,
    NOMBRE VARCHAR(30),
    APELLIDO1 VARCHAR(30),
    APELLIDO2 VARCHAR(30),
    DESCRIPCION VARCHAR(400),
    CORREO VARCHAR(40),
    DIRECCION VARCHAR(60),
    ICONO MEDIUMBLOB,
    ROL BOOLEAN NOT NULL,
    ESTADO BOOLEAN NOT NULL,
    CONSTRAINT usuarios_PK PRIMARY KEY(ID_USU),
    CONSTRAINT usuarios_UK UNIQUE(USUARIO),
    CONSTRAINT usuarios_UK2 UNIQUE(CORREO)
);

CREATE TABLE productos(
    ID_PROD MEDIUMINT AUTO_INCREMENT,
    ID_SUB SMALLINT NOT NULL,
    TITULO VARCHAR(50) NOT NULL,
    DESCRIPCION VARCHAR(500),
    PESO TINYINT,
    PRECIO MEDIUMINT(6) NOT NULL,
    FECHA_SUBIDA DATETIME,
    ID_RESERVA SMALLINT,
    ID_COMPRADOR SMALLINT,
    ID_USU SMALLINT NOT NULL,
    CONSTRAINT productos_FK FOREIGN KEY(ID_SUB) REFERENCES subcategorias(ID_SUB),
    CONSTRAINT productos_PK PRIMARY KEY(ID_PROD),
    CONSTRAINT productos_FK2 FOREIGN KEY(ID_RESERVA) REFERENCES usuarios(ID_USU),
    CONSTRAINT productos_FK3 FOREIGN KEY(ID_COMPRADOR) REFERENCES usuarios(ID_USU),
    CONSTRAINT productos_FK4 FOREIGN KEY(ID_USU) REFERENCES usuarios(ID_USU),
    CONSTRAINT productos_CH CHECK (PRECIO>=1 and PRECIO<=999999)
);

CREATE TABLE contras_antiguas(
    ID_CONTRA MEDIUMINT AUTO_INCREMENT,
    ID_USU SMALLINT NOT NULL,
    CONTRASEÑA VARCHAR(255) NOT NULL,
    CONSTRAINT contras_antiguas_PK PRIMARY KEY(ID_CONTRA),
    CONSTRAINT contras_antiguas_FK FOREIGN KEY(ID_USU) REFERENCES usuarios(ID_USU)
);

CREATE TABLE reservas(
    ID_PROD MEDIUMINT,
    ID_USU SMALLINT,
    CONSTRAINT reservas_PK PRIMARY KEY(ID_USU,ID_PROD),
    CONSTRAINT reservas_FK FOREIGN KEY(ID_PROD) REFERENCES productos(ID_PROD),
    CONSTRAINT reservas_FK2 FOREIGN KEY(ID_USU) REFERENCES usuarios(ID_USU)
);

CREATE TABLE favoritos(
    ID_PROD MEDIUMINT,
    ID_USU SMALLINT,
    CONSTRAINT favoritos_PK PRIMARY KEY(ID_USU,ID_PROD),
    CONSTRAINT favoritos_FK FOREIGN KEY(ID_PROD) REFERENCES productos(ID_PROD),
    CONSTRAINT favoritos_FK2 FOREIGN KEY(ID_USU) REFERENCES usuarios(ID_USU)
);

CREATE TABLE fotos(
    ID_FOTO INT AUTO_INCREMENT,
    ID_PROD MEDIUMINT NOT NULL,
    FOTO MEDIUMBLOB NOT NULL,
    CONSTRAINT fotos_PK PRIMARY KEY(ID_FOTO),
    CONSTRAINT fotos_FK FOREIGN KEY(ID_PROD) REFERENCES productos(ID_PROD)
);

CREATE TABLE opiniones(
    ID_PROD MEDIUMINT,
    ID_USU SMALLINT,
    VALORACION TINYINT NOT NULL,
    MENSAJE VARCHAR(400) NOT NULL,
    CONSTRAINT opiniones_PK PRIMARY KEY(ID_PROD,ID_USU),
    CONSTRAINT opiniones_FK FOREIGN KEY(ID_PROD) REFERENCES productos(ID_PROD),
    CONSTRAINT opiniones_FK2 FOREIGN KEY(ID_USU) REFERENCES usuarios(ID_USU)
);

CREATE TABLE chats(
    ID_CHAT MEDIUMINT AUTO_INCREMENT,
    ID_PROD MEDIUMINT NOT NULL,
    ID_USU SMALLINT NOT NULL,
    ULTIMA_CONEX_PROD DATETIME NOT NULL,
    ULTIMA_CONEX_USU DATETIME NOT NULL,
    CONSTRAINT chats_PK PRIMARY KEY(ID_CHAT),
    CONSTRAINT chats_FK FOREIGN KEY(ID_PROD) REFERENCES productos(ID_PROD),
    CONSTRAINT chats_FK2 FOREIGN KEY(ID_USU) REFERENCES usuarios(ID_USU)
);

CREATE TABLE mensajes(
    ID_MENSAJE INT AUTO_INCREMENT,
    ID_CHAT MEDIUMINT NOT NULL,
    ID_ENVIADOR SMALLINT NOT NULL,
    MENSAJE VARCHAR(400) NOT NULL,
    HORA DATETIME NOT NULL,
    CONSTRAINT mensajes_PK PRIMARY KEY(ID_MENSAJE),
    CONSTRAINT mensajes_FK FOREIGN KEY(ID_CHAT) REFERENCES chats(ID_CHAT),
    CONSTRAINT mensajes_FK2 FOREIGN KEY(ID_ENVIADOR) REFERENCES usuarios(ID_USU)
);

-- CREACION DE usuarios DE LA DB

-- W_ADMIN PRINCIPAL
CREATE USER WEBMART IDENTIFIED BY 'WebMart12345+';
CREATE USER WEBMART@localhost IDENTIFIED BY 'WebMart12345+';
GRANT ALL ON WEBMART.* TO WEBMART@localhost WITH GRANT OPTION;


-- USUARIO W_ADMIN PARA LA DB (usuarios ADMINS DE LA APP)
CREATE USER WEBMARTADMIN@localhost IDENTIFIED BY '12345+WebMartAdmin';
GRANT INSERT,DELETE,UPDATE,SELECT ON WEBMART.* TO WEBMARTADMIN@localhost;


-- USUARIO NORMAL PARA LA DB (usuarios NORMALES DE LA APP)
CREATE USER WEBMARTUSER@localhost IDENTIFIED BY '12345+WebMartUser';

-- PERMISOS PARA CADA TABLA:
-- subcategorias Y categorias
GRANT SELECT ON WEBMART.categorias TO WEBMARTUSER@localhost;
GRANT SELECT ON WEBMART.subcategorias TO WEBMARTUSER@localhost;

-- chats
GRANT SELECT,INSERT ON WEBMART.chats TO  WEBMARTUSER@localhost;
GRANT UPDATE (ULTIMA_CONEX_PROD,ULTIMA_CONEX_USU) ON WEBMART.chats TO WEBMARTUSER@localhost;

-- contras_antiguas
GRANT SELECT,INSERT ON WEBMART.contras_antiguas TO WEBMARTUSER@localhost;

-- favoritos
GRANT SELECT,INSERT, DELETE ON WEBMART.favoritos TO WEBMARTUSER@localhost;

-- fotos
GRANT SELECT, INSERT, DELETE ON WEBMART.fotos TO WEBMARTUSER@localhost;
GRANT UPDATE (FOTO) ON WEBMART.fotos TO WEBMARTUSER@localhost;

-- mensajes
GRANT SELECT, INSERT ON WEBMART.mensajes TO WEBMARTUSER@localhost;

-- opiniones
GRANT SELECT, INSERT, DELETE ON WEBMART.opiniones TO WEBMARTUSER@localhost;
GRANT UPDATE (VALORACION,MENSAJE) ON WEBMART.opiniones TO WEBMARTUSER@localhost;

-- productos
GRANT SELECT, INSERT, DELETE ON WEBMART.productos TO WEBMARTUSER@localhost;
GRANT UPDATE (ID_SUB,TITULO,DESCRIPCION,PESO,PRECIO,ID_RESERVA,ID_COMPRADOR) ON WEBMART.productos TO WEBMARTUSER@localhost;

-- reservas
GRANT SELECT, INSERT, DELETE ON WEBMART.reservas TO WEBMARTUSER@localhost;

-- usuarios
GRANT INSERT, SELECT ON WEBMART.usuarios TO WEBMARTUSER@localhost;
GRANT UPDATE (USUARIO,CONTRASEÑA,NOMBRE,APELLIDO1,APELLIDO2,ICONO,CORREO,DIRECCION,DESCRIPCION) ON WEBMART.usuarios TO WEBMARTUSER@localhost;
FLUSH PRIVILEGES;

-- TABLA DE categorias
INSERT INTO categorias(NOMBRE) VALUE('Motor');
INSERT INTO categorias(NOMBRE) VALUE('Moda y Accesorios');
INSERT INTO categorias(NOMBRE) VALUE('Hogar y Jardín');
INSERT INTO categorias(NOMBRE) VALUE('TV, Audio y Foto');
INSERT INTO categorias(NOMBRE) VALUE('Telefonía');
INSERT INTO categorias(NOMBRE) VALUE('Informática');
INSERT INTO categorias(NOMBRE) VALUE('Inmobiliaria');
INSERT INTO categorias(NOMBRE) VALUE('Empleo');
INSERT INTO categorias(NOMBRE) VALUE('Formación y libros');
INSERT INTO categorias(NOMBRE) VALUE('Servicios');
INSERT INTO categorias(NOMBRE) VALUE('Juegos');
INSERT INTO categorias(NOMBRE) VALUE('Videojuegos y Consolas');
INSERT INTO categorias(NOMBRE) VALUE('Bebes');
INSERT INTO categorias(NOMBRE) VALUE('Aficiones y Ocio');
INSERT INTO categorias(NOMBRE) VALUE('Colecciones');
INSERT INTO categorias(NOMBRE) VALUE('Deportes');
INSERT INTO categorias(NOMBRE) VALUE('Mascota');
INSERT INTO categorias(NOMBRE) VALUE('Otros');

-- TABLA DE SUBCATEGORÍAS
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Coches');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Motos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Caravanas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Furgonetas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Remolques');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Piezas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Accesorios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (1,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Camisas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Pantalones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Camisetas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Sudaderas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Jerseys');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Chaquetas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Abrigos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Calzados');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Accesorios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (2,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Sillas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Mesas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Armarios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Sofas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Somieres');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Cajones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Estanterías');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Hamacas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Decoraciones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Piscinas hinchables');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Bricolaje');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (3,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Televisiones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Altavoces');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Radios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Cámaras');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Proyectores');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Piezas / recambios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (4,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Smartphones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Tablets');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Cargadores');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Fundas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Teléfonos fijo');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Fax');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (5,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Portátiles');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Ordenadores de sobremesa');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Impresoras');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Monitores');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Componentes');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Periféricos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Software');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (6,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Alquiler/venta de viviendas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Alquiler/venta de locales');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Alquiler/venta de oficinas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Alquiler/venta de garajes');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Alquiler/venta de trasteros');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Alquiler vacacional');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (7,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (8,'Necesidad de personal');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (8,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Clases particulares');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Autoescuela');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Cursos de idiomas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Libros');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Libros escolares');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Apuntes univerisatios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (9,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (10,'Para la persona');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (10,'Para el hogar');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (10,'Para el trabajo');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (10,'Para el motor');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (10,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (11,'Juguetes');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (11,'Juegos de mesa');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (11,'Juegos de bar');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (11,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (12,'Videojuegos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (12,'Consolas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (12,'Mandos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (12,'Accesorios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (12,'Productos exclusivos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (12,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Cunas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Tronas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Accesorios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Juguetes');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Silla de paseo');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Cochecitos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Higiene y cuidado');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (13,'Otros');


INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Casa rural');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Puenting');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Airsoft');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Surf');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Montañismo');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Drones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Caza');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Pesca');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Coches teledirigidos');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (14,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Cuadros');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Cartas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Dedales');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Monedas');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Billetes');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Decoraciones');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (15,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (16,'Ropa deportiva');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (16,'Maquinaria deportiva');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (16,'Raquetas, balones, pelotas...');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (16,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (17,'Accesorios');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (17,'Higiene');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (17,'Comida');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (17,'Juguetes');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (17,'Servicios de cuidado');
INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (17,'Otros');

INSERT INTO subcategorias(ID_CAT, NOMBRE) VALUE (18,'Otro tipo de productos');
