CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

CREATE TABLE Usuarios (
                    id INT AUTO_INCREMENT PRIMARY KEY ,
                    nombre_completo VARCHAR(100) NOT NULL,
                    anio_nacimiento YEAR NOT NULL,
                    sexo CHAR(1) NOT NULL,
                    ciudad VARCHAR(100),
                    pais VARCHAR(100),
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
                    latitud FLOAT,
                    longitud FLOAT,
                    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
                    qr VARCHAR(255),
                    trampita INT
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
                             FOREIGN KEY (id_usuario) REFERENCES Usuarios(id) ON DELETE CASCADE,
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
                          fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
                          revisada BOOLEAN DEFAULT FALSE,
                          valida BOOLEAN DEFAULT TRUE,
                          vecesEntregadas INT,
                          vecesCorrectas INT,
                          activa BOOLEAN DEFAULT FALSE,
                        fecha_comienzoActivo DATETIME DEFAULT NULL,
                        fehca_finActivo DATETIME DEFAULT NULL,
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
                           fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
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

INSERT INTO Usuarios(id, nombre_completo, anio_nacimiento, sexo, ciudad, pais, email, password, username, token, foto, habilitado, puntaje_acumulado, partidas_realizadas, nivel, preguntas_acertadas, preguntas_entregadas, qr, fecha_registro, latitud, longitud)
VALUES
    (1, 'ignacio', 1990, 'M', 'CABA', 'Argentina', 'ignacio@gmail.com', '123456', 'ignacio', '123456', 'foto.jpg', TRUE, 0, 0, 'BAJO', 1, 3, 'public/qrs/ignacio.png', '2024-06-18 15:30:00', -34.603722, -58.381592),
    (2, 'Editor', 1990, 'M', 'CABA', 'Argentina', 'editor@gmail.com', '123', 'usurioeditor', '12fdgdf', 'foto.jpg', TRUE, 0, 0, 'BAJO', 1, 3, 'public/qrs/usurioeditor.png', '2024-06-20 15:30:00', -34.603722, -58.381592),
    (3, 'Admin', 1990, 'M', 'CABA', 'Argentina', 'admin@gmail.com', '123', 'usurioadmin', '1234dfgdf56', 'foto.jpg', TRUE, 0, 0, 'MEDIO', 2, 3, 'public/qrs/usurioadmin.png', '2024-06-20 15:30:00', -34.603722, -58.381592),
    (4, 'Mora', 2002, 'F', 'Paris', 'Francia', 'moavalos@gmail.com', '123', 'momo', '1234dfgtdf56', 'foto.jpg', TRUE, 0, 0, 'ALTO', 3, 4, 'public/qrs/momo.png', '2022-06-14 11:20:00', 48.856613, 2.352222),
    (5, 'Maria Garcia', 1995, 'F', 'Madrid', 'España', 'maria@gmail.com', 'password123', 'maria_garcia', 'token1223', 'foto_maria.jpg', TRUE, 400, 5, 'ALTO', 4, 5, 'public/qrs/maria_garcia.png', '2024-06-21 10:45:00', 40.416775, -3.703790),
    (6, 'John Smith', 1988, 'M', 'New York', 'Estados Unidos', 'john.smith@gmail.com', 'qwerty456', 'john_smith', 'token456', 'john_photo.jpg', TRUE, 2, 100, 'ALTO', 8, 10, 'public/qrs/john_smith.png', '2024-06-20 08:15:00', 40.712776, -74.005974),
    (7, 'Sophie Dupont', 1993, 'F', 'Paris', 'Francia', 'sophie.dupont@gmail.com', 'pass123', 'sophie_dupont', 'token7889', 'sophie_pic.jpg', TRUE, 1, 5, 'ALTO', 9, 10, 'public/qrs/sophie_dupont.png', '2024-06-19 14:20:00', 48.856613, 2.352222),
    (8, 'Anna Müller', 1992, 'F', 'Berlín', 'Alemania', 'anna.mueller@gmail.com', 'abc123', 'anna_mueller', 'token789', 'anna_photo.jpg', TRUE, 3, 20, 'MEDIO', 5, 10, 'public/qrs/anna_mueller.png', '2024-06-22 11:00:00', 52.520008, 13.404954),
    (9, 'Luca Rossi', 1985, 'M', 'Roma', 'Italia', 'luca.rossi@gmail.com', 'pass456', 'luca_rossi', 'token1011', 'luca_pic.jpg', TRUE, 1, 5, 'MEDIO', 6, 10, 'public/qrs/luca_rossi.png', '2024-06-21 09:30:00', 41.902782, 12.496366),
    (10, 'Chen Wei', 1990, 'M', 'Beijing', 'China', 'chen.wei@gmail.com', 'hello123', 'chen_wei', 'token1213', 'chen_pic.jpg', TRUE, 4, 50, 'MEDIO', 6, 10, 'public/qrs/chen_wei.png', '2024-06-20 13:45:00', 39.904202, 116.407394),
    (11, 'Javier Martínez', 1987, 'M', 'Madrid', 'España', 'javier.martinez@gmail.com', 'clave123', 'javier_martinez', 'token14185', 'javier_foto.jpg', TRUE, 7, 4, 'MEDIO', 6, 10, 'public/qrs/javier_martinez.png', '2024-06-19 16:00:00', 40.416775, -3.703790),
    (12, 'Emily Johnson', 1994, 'F', 'London', 'Reino Unido', 'emily.johnson@gmail.com', 'password789', 'emily_johnson', 'token1617', 'emily_pic.jpg', TRUE, 6, 3, 'MEDIO', 5, 10, 'public/qrs/javier_martinez.png', '2024-06-18 17:30:00', 51.507351, -0.127758),
    (13, 'Ahmed Hassan', 1980, 'M', 'Cairo', 'Egipto', 'ahmed.hassan@gmail.com', 'egypt123', 'ahmed_hassan', 'toke3n1819', 'ahmed_photo.jpg', TRUE, 3, 2, 'BAJO', 2, 10, NULL, '2024-06-17 12:15:00', 30.044420, 31.235712);

INSERT INTO Partida(id_usuario, puntaje, fecha)
VALUES
    (1, 0, '2024-06-22 14:00:00'),
    (2, 150, '2024-06-22 13:30:00'),
    (3, 200, '2024-06-22 12:45:00'),
    (4, 50, '2024-06-21 18:00:00'),
    (5, 300, '2024-06-21 17:30:00'),
    (6, 80, '2024-06-21 16:45:00'),
    (7, 150, '2024-06-21 16:00:00'),
    (8, 400, '2024-06-21 15:15:00'),
    (9, 250, '2024-06-21 14:30:00'),
    (10, 150, '2024-06-21 13:45:00'),
    (11, 500, '2024-06-21 13:00:00'),
    (12, 350, '2024-06-20 12:15:00'),
    (13, 230, '2024-06-20 11:30:00');

INSERT INTO Usuario_Rol (id_usuario,id_rol)
VALUES (1,3), (2,2), (3,1),(4,3), (5,3), (6,3),(7,3),
        (8,3), (9,3),(10,3), (11,3), (12,3),(13,3);

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

INSERT INTO Pregunta(id,texto, id_categoria,nivel,usuario_creador,fecha_creacion,revisada,valida, vecesEntregadas, vecesCorrectas, activa, fecha_comienzoActivo) VALUES
(1, '¿Cuál es el nombre del actor que interpreta a Tony Stark/Iron Man en el Universo Cinematográfico de Marvel?', 1, 'MEDIO', NULL,now() ,FALSE, TRUE, 10, 5, TRUE, NOW()),
(2, '¿Quién es la protagonista de la película "Mujer Maravilla" (2017), basada en el personaje de DC Comics?', 1, 'FACIL', NULL,now() , FALSE, TRUE, 10, 8, TRUE, now()),
(3, '¿Quién pintó la obra "La Gioconda", también conocida como "La Mona Lisa"?', 3, 'DIFICIL', NULL,now() , FALSE, TRUE, 10, 2, true, NOW()),
(4, '¿Cuál de las siguientes partículas subatómicas tiene carga positiva?', 4, 'DIFICIL', NULL,now() , FALSE, TRUE, 10, 3, TRUE, NOW()),
(5, '¿En qué año se fundó la empresa Apple?', 5, 'DIFICIL', NULL,now() , FALSE, TRUE, 10, 2, TRUE, NOW()),
(6, '¿Cuál es el instrumento musical principal en una orquesta sinfónica?', 10, 'DIFICIL', NULL, now() ,FALSE, TRUE, 10, 1, TRUE, NOW()),
(7, '¿Cuál es la capital de Uruguay?', 7, 'FACIL', NULL,now() , FALSE, TRUE, 10, 8, TRUE, NOW()),

(8, '¿Cuántos lados tiene un triangulo?', 4, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-17 15:30:00'),
(9, '¿Quién escribió "Cien años de soledad"?', 8, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 7, TRUE, '2024-06-17 15:30:00'),
(10, '¿Cuál es el país más grande del mundo en términos de superficie?', 7, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 9, TRUE, '2024-06-17 15:30:00'),
(11, '¿Qué elemento químico tiene el símbolo "O"?', 4, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-17 15:30:00'),
(12, '¿En qué año terminó la Segunda Guerra Mundial?', 6, 'MEDIO', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 6, TRUE, '2024-06-17 15:30:00'),
(13, '¿Cuál es el idioma más hablado en el mundo?', 7, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-17 15:30:00'),
(14, '¿Quién es el autor de la teoría de la relatividad?', 4, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-17 15:30:00'),
(15, '¿Cuál es la moneda oficial de Japón?', 7, 'FACIL', 3,'2024-06-17 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-17 15:30:00'),
(16, '¿Cuál es el planeta más cercano al Sol?', 4, 'FACIL', 3,'2024-06-18 15:30:00', FALSE, TRUE, 10, 9, TRUE, '2024-06-18 15:30:00'),
(17, '¿Quién fue el primer presidente de los Estados Unidos?', 7, 'FACIL', 3,'2024-06-18 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-18 15:30:00'),
(18, '¿Cuál es el río más largo del mundo?', 7, 'FACIL', 3,'2024-06-18 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-18 15:30:00'),
(19, '¿En qué año se firmó la Declaración de Independencia de los Estados Unidos?', 6, 'MEDIO', 3,'2024-06-18 15:30:00', FALSE, TRUE, 10, 6, TRUE, '2024-06-18 15:30:00'),
(20, '¿Quién escribió la obra "Don Quijote de la Mancha"?', 8, 'FACIL', 3,'2024-06-19 15:30:00', FALSE, TRUE, 10, 7, TRUE, '2024-06-19 15:30:00'),
(21, '¿Cuál es el océano más grande del mundo?', 7, 'FACIL', 3,'2024-06-19 15:30:00', FALSE, TRUE, 10, 9, TRUE, '2024-06-19 15:30:00'),
(22, '¿Cuál es el símbolo químico del oro?', 4, 'FACIL', 3, '2024-06-19 15:30:00',FALSE, TRUE, 10, 8, TRUE, '2024-06-19 15:30:00'),
(23, '¿En qué continente está ubicado el desierto del Sahara?', 7, 'FACIL', 3,'2024-06-19 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-19 15:30:00'),
(24, '¿Quién pintó "La noche estrellada"?', 3, 'MEDIO', 3,'2024-06-19 15:30:00', FALSE, TRUE, 10, 5, TRUE, '2024-06-19 15:30:00'),
(25, '¿Cuántos planetas tienen anillos en nuestro sistema solar?', 4, 'MEDIO', 3,'2024-06-19 15:30:00', FALSE, TRUE, 10, 7, TRUE, '2024-06-19 15:30:00'),
(26, '¿En qué ciudad se encuentra la Torre Eiffel?', 7, 'FACIL', 3,'2024-06-19 15:30:00', FALSE, TRUE, 10, 8, TRUE, '2024-06-19 15:30:00'),
(27, '¿Quién fue el primer ser humano en viajar al espacio exterior?', 6, 'MEDIO', 3,'2024-06-20 15:30:00', FALSE, TRUE, 10, 6, TRUE, '2024-06-20 15:30:00'),
(28, '¿Qué famoso inventor desarrolló la bombilla eléctrica?', 4, 'MEDIO', 3,'2024-06-20 15:30:00', FALSE, TRUE, 10, 5, TRUE, '2024-06-20 15:30:00'),
(29, '¿Cuál es la montaña más alta del mundo?', 7, 'MEDIO', 3,'2024-06-20 15:30:00', FALSE, TRUE, 10, 7, TRUE, '2024-06-20 15:30:00');



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
('Júpiter', 16, false),
('George Washington', 17, true),
('Thomas Jefferson', 17, false),
('Abraham Lincoln', 17, false),
('John Adams', 17, false),
('Amazonas', 18, false),
('Nilo', 18, true),
('Yangtsé', 18, false),
('Misisipi', 18, false),
('1776', 19, true),
('1789', 19, false),
('1804', 19, false),
('1812', 19, false),
('Miguel de Cervantes', 20, true),
('Gabriel García Márquez', 20, false),
('Jorge Luis Borges', 20, false),
('William Shakespeare', 20, false),
('Pacífico', 21, true),
('Atlántico', 21, false),
('Índico', 21, false),
('Ártico', 21, false),
('Au', 22, false),
('Ag', 22, false),
('Fe', 22, false),
('Au', 22, true),
('África', 23, true),
('América', 23, false),
('Asia', 23, false),
('Europa', 23, false),
('Vincent van Gogh', 24, true),
('Pablo Picasso', 24, false),
('Salvador Dalí', 24, false),
('Leonardo da Vinci', 24, false),
('4', 25, true),
('2', 25, false),
('6', 25, false),
('8', 25, false),
('París', 26, true),
('Londres', 26, false),
('Roma', 26, false),
('Berlín', 26, false),
('Yuri Gagarin', 27, true),
('Neil Armstrong', 27, false),
('Buzz Aldrin', 27, false),
('Alan Shepard', 27, false),
('Thomas Edison', 28, true),
('Nikola Tesla', 28, false),
('Alexander Graham Bell', 28, false),
('Guglielmo Marconi', 28, false);
