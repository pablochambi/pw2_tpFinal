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
                    nivel VARCHAR(10) DEFAULT 'BAJO',
                    preguntas_acertadas INT DEFAULT 0,
                    preguntas_entregadas INT DEFAULT 0,
                    latitud FLOAT ,
                    longitud FLOAT ,
                    qr VARCHAR(255),
                    FOREIGN KEY (id_pais) REFERENCES Pais(id)
);

CREATE TABLE Partida (
                 id INT AUTO_INCREMENT PRIMARY KEY,
                 id_usuario INT,
                 puntaje INT DEFAULT 0,
                 fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
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
                          nivel VARCHAR(50),
                          usuario_creador INT DEFAULT NULL,  -- si no lo creo un usuario es null
                          revisada BOOLEAN DEFAULT FALSE,
                          valida BOOLEAN DEFAULT TRUE,
                          vecesEntregadas INT,
                          vecesCorrectas INT,
                          activa BOOLEAN DEFAULT FALSE,
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
                           FOREIGN KEY (id_pregunta) REFERENCES Pregunta(id) ON DELETE CASCADE
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
VALUES
    (1,'ignacio', 1990, 'M', 1, 'CABA', 'ignacio@gmail.com', '123456', 'ignacio', '123456', 'foto.jpg', TRUE, 0, 0, 'BAJO', NULL),
    (2,'Editor', 1990, 'M', 1, 'CABA', 'editor@gmail.com', '123', 'usurioeditor', '12fdgdf', 'foto.jpg', TRUE, 0, 0, 'BAJO', NULL),
    (3,'Admin', 1990, 'M', 1, 'CABA', 'admin@gmail.com', '123', 'usurioadmin', '1234dfgdf56', 'foto.jpg', TRUE, 0, 0, 'BAJO', NULL);

INSERT INTO Usuario_Rol (id_usuario,id_rol) VALUES (1,3), (2,2), (3,1);

INSERT INTO Categoria(id,nombre,color) VALUES
                                           (1,'Espectaculo','#b39606'),
                                           (2,'Deportes', '#da6e19'),
                                           (3,'Arte','#1eb0a6'),
                                           (4,'Ciencia','#abc52f'),
                                           (5,'Programacion','#30A7F5'),
                                           (6, 'Historia', '#6e45e0'),
                                           (7, 'Geografía', '#e04b45'),
                                           (8, 'Literatura', '#45e07d'),
                                           (9, 'Cine', '#e0a24b'),
                                           (10, 'Música', '#4b61e0');

INSERT INTO Pregunta(id,texto, id_categoria,nivel,usuario_creador,revisada,valida, vecesEntregadas, vecesCorrectas, activa) VALUES
(1, '¿Cuál es el nombre del actor que interpreta a Tony Stark/Iron Man en el Universo Cinematográfico de Marvel?', 1, 'MEDIO', NULL, FALSE, TRUE, 10, 5, TRUE),
(2, '¿Quién es la protagonista de la película "Mujer Maravilla" (2017), basada en el personaje de DC Comics?', 1, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(3, '¿Quién pintó la obra "La Gioconda", también conocida como "La Mona Lisa"?', 3, 'DIFICIL', NULL, FALSE, TRUE, 10, 2, TRUE),
(4, '¿Cuál de las siguientes partículas subatómicas tiene carga positiva?', 4, 'DIFICIL', NULL, FALSE, TRUE, 10, 3, TRUE),
(5, '¿En qué año se fundó la empresa Apple?', 5, 'DIFICIL', NULL, FALSE, TRUE, 10, 2, TRUE),
(6, '¿Cuál es el instrumento musical principal en una orquesta sinfónica?', 10, 'DIFICIL', NULL, FALSE, TRUE, 10, 1, TRUE),
(7, '¿Cuál es la capital de Uruguay?', 7, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(8, '¿Cuántos lados tiene un triangulo?', 4, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(9, '¿Quién escribió "Cien años de soledad"?', 8, 'FACIL', NULL, FALSE, TRUE, 10, 7, TRUE),
(10, '¿Cuál es el país más grande del mundo en términos de superficie?', 7, 'FACIL', NULL, FALSE, TRUE, 10, 9, TRUE),
(11, '¿Qué elemento químico tiene el símbolo "O"?', 4, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(12, '¿En qué año terminó la Segunda Guerra Mundial?', 6, 'MEDIO', NULL, FALSE, TRUE, 10, 6, TRUE),
(13, '¿Cuál es el idioma más hablado en el mundo?', 7, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(14, '¿Quién es el autor de la teoría de la relatividad?', 4, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(15, '¿Cuál es la moneda oficial de Japón?', 7, 'FACIL', NULL, FALSE, TRUE, 10, 8, TRUE),
(16, '¿Cuál es el planeta más cercano al Sol?', 4, 'FACIL', NULL, FALSE, TRUE, 10, 9, TRUE);

INSERT INTO Respuesta(texto, id_pregunta, es_correcta) VALUES
('Robert Downey Jr.', 1, true),
('Chris Hemsworth', 1, false),
('Mark Ruffalo', 1, false),
('Chris Evans', 1, false),
('Scarlett Johansson', 2, false),
('Gal Gadot', 2, true),
('Angelina Jolie', 2, false),
('Margot Robbie', 2, false),
('Leonardo da Vinci', 3, true),
('Pablo Picasso', 3, false),
('Vincent van Gogh', 3, false),
('Claude Monet', 3, false),
('Protón', 4, true),
('Electrón', 4, false),
('Neutrón', 4, false),
('Fotón', 4, false),
('1976', 5, false),
('1984', 5, false),
('1977', 5, false),
('1975', 5, true),
('Violín', 6, false),
('Piano', 6, true),
('Flauta', 6, false),
('Trompeta', 6, false),
('París', 7, false),
('Londres', 7, false),
('Montevideo', 7, true),
('Roma', 7, false),
('Uno', 8, false),
('Dos', 8, false),
('Tres', 8, true),
('Cuatro', 8, false),
('Gabriel García Márquez', 9, true),
('Mario Vargas Llosa', 9, false),
('Julio Cortázar', 9, false),
('Isabel Allende', 9, false),
('Rusia', 10, true),
('China', 10, false),
('Canadá', 10, false),
('Estados Unidos', 10, false),
('Oxígeno', 11, true),
('Oro', 11, false),
('Plata', 11, false),
('Nitrógeno', 11, false),
('1945', 12, true),
('1939', 12, false),
('1941', 12, false),
('1947', 12, false),
('Chino Mandarín', 13, true),
('Inglés', 13, false),
('Español', 13, false),
('Hindi', 13, false),
('Albert Einstein', 14, true),
('Isaac Newton', 14, false),
('Galileo Galilei', 14, false),
('Nikola Tesla', 14, false),
('Yen', 15, true),
('Dólar', 15, false),
('Euro', 15, false),
('Won', 15, false),
('Mercurio', 16, true),
('Venus', 16, false),
('Marte', 16, false),
('Júpiter', 16, false);
