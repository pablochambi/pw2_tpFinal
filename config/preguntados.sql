CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

CREATE TABLE Pais (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      nombre VARCHAR(100) NOT NULL
);

CREATE TABLE Usuarios (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         nombre_completo VARCHAR(100) NOT NULL,
                         anio_nacimiento YEAR NOT NULL,
                         sexo CHAR(1) NOT NULL,
                         id_pais INT,
                         ciudad VARCHAR(100),
                         email VARCHAR(100) NOT NULL UNIQUE,
                         password VARCHAR(100) NOT NULL,
                         username VARCHAR(50) NOT NULL UNIQUE,
                         token VARCHAR(50) NOT NULL UNIQUE,
                         foto VARCHAR(100),
                         habilitado BOOLEAN DEFAULT FALSE,
                         puntaje_acumulado INT DEFAULT 0,
                         partidas_realizadas INT DEFAULT 0,
                         nivel DECIMAL(5,2) DEFAULT 0.0, -- ratio de respuestas correctas
                         qr VARCHAR(255),
                         FOREIGN KEY (id_pais) REFERENCES Pais(id)
);


CREATE TABLE Partida (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         id_usuario INT,
                         puntaje INT DEFAULT 0,
                         fecha DATETIME DEFAULT CURRENT_TIMESTAMP, 		-- preguntar
                         FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

CREATE TABLE Rol (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Usuario_Rol (
                             id_usuario INT,
                             id_rol INT,
                             PRIMARY KEY (id_usuario, id_rol),
                             FOREIGN KEY (id_usuario) REFERENCES Usuarios(id),
                             FOREIGN KEY (id_rol) REFERENCES Rol(id)
);
CREATE TABLE Categoria (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           nombre VARCHAR(50) NOT NULL,
                           color VARCHAR(10) NOT NULL -- Código de color en formato hexadecimal
);
CREATE TABLE Pregunta (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          texto VARCHAR(255) NOT NULL,
                          id_categoria INT,
                          nivel DECIMAL(5,2) DEFAULT 0.0,
                          usuario_creador INT DEFAULT NULL,  -- si no lo creo un usuario es null
                          revisada BOOLEAN DEFAULT FALSE,
                          valida BOOLEAN DEFAULT TRUE,
                          FOREIGN KEY (id_categoria) REFERENCES Categoria(id),
                          FOREIGN KEY (usuario_creador) REFERENCES Usuarios(id)
);

CREATE TABLE Reporte_Pregunta (
                                  id_pregunta INT,
                                  id_usuario INT,
                                  descripcion VARCHAR(255),
                                  revisada BOOLEAN DEFAULT FALSE,
                                  PRIMARY KEY (id_pregunta, id_usuario),
                                  FOREIGN KEY (id_pregunta) REFERENCES Pregunta(id),
                                  FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

CREATE TABLE Respuesta (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           texto VARCHAR(255) NOT NULL,
                           es_correcta BOOLEAN DEFAULT FALSE,
                           id_pregunta INT,
                           FOREIGN KEY (id_pregunta) REFERENCES Pregunta(id)
);

-- Tabla para rastrear preguntas vistas
CREATE TABLE PreguntaVistas (
                                id_usuario INT,
                                id_pregunta INT,
                                fecha_vista DATETIME DEFAULT CURRENT_TIMESTAMP,
                                PRIMARY KEY (id_usuario, id_pregunta),
                                FOREIGN KEY (id_usuario) REFERENCES Usuarios(id),
                                FOREIGN KEY (id_pregunta) REFERENCES Pregunta(id)
);

-- Datos iniciales
INSERT INTO Rol (id,nombre) VALUES (1,'Administrador'),(2,'Editor'), (3,'Jugador');

INSERT INTO Pais(nombre) VALUES ('Argentina'), ('Uruguay'), ('Chile'), ('Paraguay'), ('Brasil'), ('Bolivia'), ('Peru'), ('Ecuador'), ('Colombia'), ('Venezuela'), ('Guyana'), ('Surinam'), ('Guyana Francesa');

INSERT INTO Usuarios(id,nombre_completo, anio_nacimiento, sexo, id_pais, ciudad, email, password, username, token, foto, habilitado, puntaje_acumulado, partidas_realizadas, nivel, qr)
VALUES (1,'ignacio', 1990, 'M', 1, 'CABA', 'ignacio@gmail.com', '123456', 'ignacio', '123456', 'foto.jpg', TRUE, 0, 0, 0.0, NULL),
        (2,'Editor', 1990, 'M', 1, 'CABA', 'editor@gmail.com', '123', 'usurioeditor', '12fdgdf', 'foto.jpg', TRUE, 0, 0, 0.0, NULL),
        (3,'Admin', 1990, 'M', 1, 'CABA', 'admin@gmail.com', '123', 'usurioadmin', '1234dfgdf56', 'foto.jpg', TRUE, 0, 0, 0.0, NULL);

INSERT INTO Usuario_Rol (id_usuario,id_rol) VALUES (1,3);
INSERT INTO Usuario_Rol (id_usuario,id_rol) VALUES (2,2);
INSERT INTO Usuario_Rol (id_usuario,id_rol) VALUES (3,1);


INSERT INTO categoria(id,nombre,color)values(1,'Espectaculo','#F5D430');
INSERT INTO categoria(id,nombre,color)values(2,'Deportes', '#da6e19');
INSERT INTO categoria(id,nombre,color)values(3,'Arte','#1eb0a6');
INSERT INTO categoria(id,nombre,color)values(4,'Ciencia','#abc52f');
INSERT INTO categoria(id,nombre,color)values(5,'Programacion','#30A7F5');


INSERT INTO pregunta(id_categoria, texto)values(1, '¿Cuál es el nombre del actor que interpreta a Tony Stark/Iron Man en el Universo Cinematográfico de Marvel?' );
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Robert Downey Jr.', 1, true);
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Chris Hemsworth', 1, false);
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mark Ruffalo', 1, false);
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Chris Evans', 1, false);

INSERT INTO pregunta(id_categoria, texto)values(1, '¿Quién es la protagonista de la película "Mujer Maravilla" (2017), basada en el personaje de DC Comics?');
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Scarlett Johansson', 2, false);
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gal Gadot', 2, true);
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Angelina Jolie', 2, false);
INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Margot Robbie', 2, false);