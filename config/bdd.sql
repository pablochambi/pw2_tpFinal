CREATE SCHEMA IF NOT EXISTS questionario;
USE questionario;
CREATE TABLE user(
                     username VARCHAR(255) PRIMARY KEY,
                     name VARCHAR(255),
                     spawn VARCHAR(255),
                     sex VARCHAR(255),
                     mail VARCHAR(255),
                     password VARCHAR(255),
                     image VARCHAR(255),
                     puntaje INT,
                     partidasRealizadas INT,
                     qr VARCHAR(255)
);

CREATE TABLE partida (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         username VARCHAR(255),
                         puntaje INT

);

CREATE TABLE pregunta(
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         id_categoria INT,
                         enunciado VARCHAR(255)
);

CREATE TABLE respuesta (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           texto VARCHAR(255),
                           id_pregunta INT,
                           es_correcta BOOLEAN
);

CREATE TABLE preguntas_usadas (
                                  username VARCHAR(255),
                                  pregunta_id INT
);

CREATE TABLE categoria (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           nombre VARCHAR(255),
                           agregada BOOLEAN
);

ALTER TABLE partida
    ADD esta_activa BOOLEAN;

ALTER TABLE partida
    ADD tiempo TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE partida
    ADD tiempo_pregunta INT;

ALTER TABLE user
    ADD COLUMN latitud DECIMAL(10, 6),
    ADD COLUMN longitud DECIMAL(10, 6);

ALTER TABLE user
    ADD COLUMN esEditor BOOLEAN,
    ADD COLUMN esAdmin BOOLEAN;

ALTER TABLE user
    ADD COLUMN token_verificacion VARCHAR(100) NOT NULL,
    ADD COLUMN esta_verificado BOOLEAN DEFAULT 0;

ALTER TABLE pregunta
    ADD COLUMN reportada BOOLEAN DEFAULT false;

ALTER TABLE pregunta
    ADD COLUMN agregada BOOLEAN DEFAULT true;

ALTER TABLE pregunta
    ADD COLUMN preg_default BOOLEAN DEFAULT FALSE;

ALTER TABLE pregunta
    ADD COLUMN veces_respondida INT DEFAULT 1,
    ADD COLUMN veces_respondida_bien INT DEFAULT 1;

ALTER TABLE user
    ADD COLUMN veces_acertadas INT DEFAULT 0,
    ADD COLUMN veces_respondidas INT DEFAULT 0;

ALTER TABLE categoria
    ADD COLUMN color VARCHAR(100);

ALTER TABLE pregunta
    ADD COLUMN preguntaCreadaPorUsuario BOOLEAN DEFAULT FALSE;

ALTER TABLE pregunta
    ADD COLUMN fecha_de_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE user
    ADD COLUMN fecha_de_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE user
    ADD COLUMN trampitas INT DEFAULT 5;

    INSERT INTO categoria(id,nombre,agregada,color)values(1,'Espectaculo',false,'#F5D430');
    INSERT INTO categoria(id,nombre,agregada,color)values(2,'Deportes',false, '#da6e19');
    INSERT INTO categoria(id,nombre,agregada,color)values(3,'Arte',false,'#1eb0a6');
    INSERT INTO categoria(id,nombre,agregada,color)values(4,'Ciencia',false,'#abc52f');
    INSERT INTO categoria(id,nombre,agregada,color)values(5,'Programacion',false,'#30A7F5');

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál es el nombre del actor que interpreta a Tony Stark/Iron Man en el Universo Cinematográfico de Marvel?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Robert Downey Jr.', 1, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Chris Hemsworth', 1, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mark Ruffalo', 1, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Chris Evans', 1, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Quién es la protagonista de la película "Mujer Maravilla" (2017), basada en el personaje de DC Comics?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Scarlett Johansson', 2, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gal Gadot', 2, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Angelina Jolie', 2, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Margot Robbie', 2, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál de los siguientes programas de televisión es un "reality show" de competencia culinaria?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Juego de Tronos', 3, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('MasterChef', 3, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Stranger Things', 3, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Breaking Bad', 3, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Qué banda de rock liderada por Mick Jagger es conocida como "La banda de rock and roll más grande del mundo"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Rolling Stones', 4, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Beatles', 4, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Led Zeppelin', 4, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Queen', 4, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Quién es el creador de la exitosa franquicia de películas "Star Wars"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Steven Spielberg', 5, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('James Cameron', 5, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('George Lucas', 5, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('J.J. Abrams', 5, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿En qué película de Pixar los personajes principales son juguetes que cobran vida cuando los humanos no los ven?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Monsters, Inc.', 6, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Inside Out', 6, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Toy Story', 6, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Up', 6, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál es la comedia de situación que presenta a un grupo de amigos que viven en Nueva York y se llama "The One Where..."?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Friends', 7, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Office', 7, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('How I Met Your Mother', 7, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Seinfeld', 7, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Qué película musical de 1971 está basada en el libro de Lewis Carroll y sigue las aventuras de Alicia en un mundo mágico?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mary Poppins', 8, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La Bella y la Bestia', 8, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La Novicia Rebelde', 8, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Alicia en el País de las Maravillas', 8, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Quién es el actor que interpretó a James Bond en películas como "GoldenEye" y "Skyfall"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Sean Connery', 9, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pierce Brosnan', 9, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Roger Moore', 9, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Daniel Craig', 9, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Qué director es conocido por películas como "El Laberinto del Fauno" y "El Laberinto del Pan"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Guillermo del Toro', 10, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pedro Almodóvar', 10, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Alejandro González Iñárritu', 10, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Alfonso Cuarón', 10, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿En qué película de ciencia ficción de 2010, dirigida por Christopher Nolan, los personajes viajan a través de los sueños?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Inception', 11, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Interstellar', 11, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gravity', 11, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Martian', 11, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Quién es la actriz que interpretó a Katniss Everdeen en la serie de películas "Los Juegos del Hambre"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Shailene Woodley', 12, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Kristen Stewart', 12, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Emma Watson', 12, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Jennifer Lawrence', 12, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál es la película de Disney en la que un joven león llamado Simba debe enfrentar su destino como rey de la Sabana?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El Rey León', 13, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Aladdín', 13, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mulán', 13, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La Sirenita', 13, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿En qué película de ciencia ficción de 1984, dirigida por James Cameron, un cyborg asesino viaja desde el futuro para matar a Sarah Connor?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Blade Runner', 14, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Terminator', 14, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('RoboCop', 14, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Total Recall', 14, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál es el nombre del personaje interpretado por Johnny Depp en la franquicia de películas "Piratas del Caribe"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Captain Hook', 15, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Captain Morgan', 15, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Captain Jack Sparrow', 15, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Captain Blackbeard', 15, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿En qué película de 1973, dirigida por William Friedkin, dos detectives investigan una serie de asesinatos brutales en Nueva York?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Seven', 16, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Zodiac', 16, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Silence of the Lambs', 16, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Exorcist', 16, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál es la actriz que interpretó a Hermione Granger en la serie de películas de "Harry Potter"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Emma Stone',  17, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Emma Roberts', 17, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Emma Watson', 17, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Emma Thompson', 17, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿En qué película un científico llamado Doc Brown construye una máquina del tiempo a partir de un DeLorean?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Back to the Future', 18, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bill & Ted s Excellent Adventure', 18, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Time Machine', 18, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Timecop', 18, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿Cuál es la banda de rock británica conocida por su álbum "The Wall" y canciones como "Another Brick in the Wall"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('The Who', 19, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pink Floyd', 19, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Led Zeppelin', 19, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Queen', 19, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(1, '¿En qué película de animación un joven llamado Miguel viaja a la Tierra de los Muertos en el Día de los Muertos mexicano?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Up', 20, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Coco', 20, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Moana', 20, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Inside Out', 20, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (2, '¿Quién ostenta el récord de más títulos de Grand Slam en la historia del tenis masculino a partir de 2022?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Roger Federer', 21, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Rafael Nadal', 21, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Novak Djokovic', 21, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Pete Sampras', 21, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿En qué deporte se utiliza una red separando a dos equipos, y el objetivo es pasar la pelota sobre la red y que toque el suelo en el campo contrario?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Voleibol', 22, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fútbol americano', 22, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Balonmano', 22, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bádminton', 22, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (7, '¿Cuál fue el país ganador de la Copa Mundial de la FIFA en 1954?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Alemania Occidental', 23, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Hungría', 23, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Brasil', 23, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Italia', 23, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿En qué deporte el jugador puede marcar puntos al encestar la pelota en el aro del equipo contrario?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Baloncesto', 24, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rugby', 24, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 24, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Béisbol', 24, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte en el que los competidores deben nadar, andar en bicicleta y correr en una serie de etapas?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Maratón', 25, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Triatlón', 25, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ciclismo', 25, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Atletismo', 25, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte que se juega con un mazo y una pelota en un campo con césped y se asocia con la realeza británica?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rugby', 26, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 26, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cricket', 26, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis', 26, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál de los siguientes deportes se juega en una cancha rectangular y se golpea una pelota contra una pared frontal?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Squash', 27, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bádminton', 27, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis de mesa', 27, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Balonmano', 27, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte en el que los equipos compiten para tirar un disco de plástico en una canasta?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 28, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ultimate Frisbee', 28, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rugby', 28, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Baloncesto', 28, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte en el que los competidores deben realizar una serie de ejercicios como salto de altura, lanzamiento de bala y carreras?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Atletismo', 29, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Natación sincronizada', 29, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Esgrima', 29, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Lucha libre', 29, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál de los siguientes deportes se juega en una cancha de hielo con piedras y escobas?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bobsleigh', 30, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Patinaje artístico', 30, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hockey sobre hielo', 30, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Curling', 30, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿En qué deporte se lucha cuerpo a cuerpo en un área circular y el objetivo es hacer que el oponente toque el suelo con la espalda?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Judo', 31, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Taekwondo', 31, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Sumo', 31, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Lucha libre', 31, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte en el que los equipos compiten para tirar un objeto llamado "pelota" en una canasta?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rugby', 32, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cricket', 32, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Baloncesto', 32, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 32, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿En qué deporte los competidores deben lanzar una pelota a alta velocidad y tratar de no ser golpeados por la misma?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Béisbol', 33, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fútbol', 33, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis', 33, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Softbol', 33, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte en el que los competidores usan un palo para golpear una pequeña bola en un hoyo en el menor número de golpes?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 34, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hockey sobre césped', 34, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bádminton', 34, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis de mesa', 34, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿En qué deporte los competidores deben derribar a sus oponentes con un balón de goma y evitar ser golpeados?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rugby', 35, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Dodgeball', 35, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fútbol', 35, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Voleibol', 35, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte en el que los competidores realizan acrobacias en una tabla que se desliza por la nieve?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Surf', 36, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Esquí', 36, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Snowboarding', 36, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Skateboarding', 36, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál de los siguientes deportes se juega en un campo con un agujero y una bandera y el objetivo es golpear la pelota en el agujero en la menor cantidad de golpes?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rugby', 37, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fútbol', 37, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 37, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Béisbol', 37, false);


    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿En qué deporte se utiliza una raqueta para golpear una pelota sobre una red en un campo rectangular?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis', 38, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Squash', 38, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bádminton', 38, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ping Pong', 38, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte de invierno en el que los competidores descienden por una pista de hielo a alta velocidad en un trineo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Skeleton', 39, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Luge', 39, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Bobsleigh', 39, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Patinaje artístico', 39, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cual fue la mayor cantidad de goles que recibio en un solo partido la seleccion de Brasil?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('7', 40, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('5', 40, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('6', 40, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('8', 40, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (3, '¿Cuál de los siguientes pintores del Renacimiento italiano es conocido por la creación de la obra "La Primavera" y "El Nacimiento de Venus"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Sandro Botticelli', 41, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Leonardo da Vinci', 41, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Michelangelo Buonarroti', 41, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Raffaello Sanzio', 41, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál de las siguientes pinturas es conocida por su representación de una noche estrellada sobre un pueblo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La Mona Lisa', 42, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Guernica', 42, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Los lirios de agua', 42, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La noche estrellada', 42, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué movimiento artístico se caracteriza por la representación de escenas cotidianas y el uso de la luz natural?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Impresionismo', 43, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cubismo', 43, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Surrealismo', 43, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Renacimiento', 43, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Quién es el autor de la escultura "El Pensador"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Auguste Rodin', 44, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Michelangelo Buonarroti', 44, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pablo Picasso', 44, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Edgar Degas', 44, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (3, '¿Quién fue el artista del Renacimiento italiano conocido por su pintura "La Anunciación" y por ser un pionero en el uso de la perspectiva lineal en la pintura?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Leonardo da Vinci', 45, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Sandro Botticelli', 45, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Michelangelo Buonarroti', 45, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Raffaello Sanzio', 45, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué famoso artista pintó "La persistencia de la memoria", con relojes derretidos?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Salvador Dalí', 46, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Claude Monet', 46, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Vincent van Gogh', 46, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Frida Kahlo', 46, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál de los siguientes artistas es conocido por sus pinturas de flores, especialmente los lirios de agua?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gustav Klimt', 47, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Claude Monet', 47, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Vincent van Gogh', 47, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Georgia O Keeffe', 47, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál es el nombre de la pintura que representa a una mujer sonriente con un fondo enigmático?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La última cena', 48, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La Dama del Armiño', 48, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mujer con un sombrero', 48, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('La Mona Lisa', 48, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué movimiento artístico se caracteriza por la representación de la vida cotidiana en París, con escenas de cafés, bailes y teatros?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Rococó', 49, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Realismo', 49, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Art Nouveau', 49, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Belle Époque', 49, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál de las siguientes esculturas es conocida por su representación de un hombre desnudo sosteniendo una lanza?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El David', 50, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El Pensador', 50, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El Moisés', 50, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El Jinete', 50, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Quién es el autor de la famosa pintura "El grito"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pablo Picasso', 51, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Edvard Munch', 51, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Vincent van Gogh', 51, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Henri Matisse', 51, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué movimiento artístico se caracteriza por la representación de objetos y figuras de manera fragmentada y en múltiples perspectivas?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Renacimiento', 52, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cubismo', 52, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Barroco', 52, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Surrealismo', 52, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál de las siguientes pinturas representa un toro y un caballo en medio de una tormenta?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Los lirios de agua', 53, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Guernica', 53, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El Grito', 53, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('El nacimiento de Venus', 53, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué artista es conocido por sus autorretratos, en los que a menudo se representa con una ceja unida?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Frida Kahlo', 54, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Georgia O Keeffe', 54, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Louise Bourgeois', 54, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Yoko Ono', 54, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué famoso pintor renacentista creó la obra "El nacimiento de Venus"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Leonardo da Vinci', 55, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Michelangelo Buonarroti', 55, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Raphael', 55, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Sandro Botticelli', 55, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál de las siguientes corrientes artísticas se caracteriza por la representación de escenas misteriosas y oníricas?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Renacimiento', 56, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Impresionismo', 56, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Surrealismo', 56, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Arte abstracto', 56, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué movimiento artístico se desarrolló a principios del siglo XX y se caracteriza por la representación de objetos y figuras de manera abstracta?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Arte Pop', 57, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Arte Abstracto', 57, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fauvismo', 57, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Realismo', 57, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Quién es el autor de la famosa pintura "Noche estrellada sobre el Ródano"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Vincent van Gogh', 58, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Claude Monet', 58, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pablo Picasso', 58, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Leonardo da Vinci', 58, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Qué escultor creó la famosa obra "El beso"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Auguste Rodin', 59, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Michelangelo Buonarroti', 59, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Henry Moore', 59, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Alberto Giacometti', 59, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Quién es el pintor que se asocia con el estilo del "arte pop" y creó obras como "Latas de sopa Campbell"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Jackson Pollock', 60, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Andy Warhol', 60, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Roy Lichtenstein', 60, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Piet Mondrian', 60, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el elemento químico más abundante en la Tierra?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Oxígeno', 61, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Carbono', 61, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hierro', 61, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Silicio', 61, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la unidad básica de la materia que conserva las propiedades de un elemento químico?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Molécula', 62, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Átomo', 62, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Neutrón', 62, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Protón', 62, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el proceso mediante el cual las plantas convierten la luz solar en energía química?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fotosíntesis', 63, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Respiración', 63, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fermentación', 63, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Combustión', 63, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la fuerza que mantiene a los planetas en órbita alrededor del Sol?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gravedad', 64, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Magnetismo', 64, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Electricidad', 64, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fuerza centrífuga', 64, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la unidad básica del sistema nervioso que transmite señales eléctricas entre las células?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Neurona', 65, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Glóbulo blanco', 65, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hormona', 65, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Enzima', 65, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la partícula subatómica que tiene una carga positiva en el núcleo de un átomo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Electrón', 66, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Neutrón', 66, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Protón', 66, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Quark', 66, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la capa de la atmósfera terrestre donde se produce la mayoría de los fenómenos meteorológicos?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Estratósfera', 67, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Troposfera', 67, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mesosfera', 67, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ionosfera', 67, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la teoría científica que describe el origen y la evolución del universo a partir de un solo punto en el espacio y el tiempo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Teoría de la relatividad', 68, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Teoría del Big Bang', 68, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Teoría de la evolución', 68, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Teoría cuántica', 68, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el proceso mediante el cual un organismo pasa por etapas de desarrollo desde la fertilización hasta la madurez?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Evolución', 69, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mitosis', 69, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Meiosis', 69, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Desarrollo ontogenético', 69, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la unidad básica de herencia genética que contiene información genética?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gen', 70, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Célula', 70, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cromosoma', 70, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('ADN', 70, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el proceso mediante el cual las plantas obtienen agua y nutrientes del suelo a través de sus raíces?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fotosíntesis', 71, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Transpiración', 71, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Absorción', 71, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Evaporación', 71, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la molécula que almacena y transmite información genética en los seres vivos?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('ARN', 72, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('ATP', 72, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('ADN', 72, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Glicógeno', 72, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el proceso mediante el cual el cuerpo convierte los alimentos en energía utilizable?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Digestión', 73, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Respiración', 73, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fermentación', 73, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Circulación', 73, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el componente principal de la capa de ozono que protege la Tierra de la radiación ultravioleta?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Oxígeno', 74, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Dióxido de carbono', 74, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ozono', 74, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Nitrógeno', 74, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la partícula subatómica que tiene una carga negativa y orbita alrededor del núcleo de un átomo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Neutrón', 75, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Protón', 75, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Electrón', 75, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Positrón', 75, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el proceso de cambio de estado de un sólido a un líquido debido a la absorción de calor?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fusión', 76, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Evaporación', 76, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Sublimación', 76, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Solidificación', 76, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el planeta más grande del sistema solar?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tierra', 77, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Júpiter', 77, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Venus', 77, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Saturno', 77, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la fuerza que mantiene a los objetos en movimiento y que se opone al cambio en su velocidad?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fricción', 78, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gravedad', 78, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Inercia', 78, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tensión', 78, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es el proceso de cambio de estado de un gas a un líquido debido a la pérdida de calor?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Sublimación', 79, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Condensación', 79, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Solidificación', 79, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Evaporación', 79, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(4, '¿Cuál es la unidad de medida utilizada para medir la cantidad de sustancia en química?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Mol', 80, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Gramo', 80, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Litro', 80, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Newton', 80, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué significa la sigla "HTML" en el contexto de desarrollo web?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hyper Text Markup Language', 81, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('High-level Text Manipulation Language', 81, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hyper Transfer Markup Language', 81, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Human Technical Modern Language', 81, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Cuál de las siguientes no es un lenguaje de programación orientado a objetos?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Java', 82, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Python', 82, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('C', 82, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ruby', 82, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En programación, ¿qué es un "loop" o bucle?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un error en el código', 83, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una función recursiva', 83, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una estructura que repite un bloque de código', 83, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de variable', 83, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué lenguaje de programación se utiliza comúnmente para el desarrollo de aplicaciones móviles en el sistema operativo Android?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Java', 84, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Swift', 84, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('C#', 84, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Python', 84, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En el contexto de programación, ¿qué es un "framework" o marco de trabajo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de bucle', 85, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una herramienta de desarrollo que proporciona estructuras y funcionalidades predefinidas', 85, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un patrón de diseño', 85, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una estructura de control de flujo', 85, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Cuál de los siguientes lenguajes de programación es conocido por su uso en desarrollo web en el lado del servidor?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('HTML', 86, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('CSS', 86, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('JavaScript', 86, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('PHP', 86, true);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En programación, ¿qué es un "bug" o error de software?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un insecto que entra en la computadora', 87, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una característica no documentada', 87, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un problema o defecto en el código que causa un comportamiento no deseado', 87, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una función de depuración', 87, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Cuál de las siguientes no es una variable de tipo primitivo en programación?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Entero (int)', 88, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Flotante (float)', 88, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cadena (string)', 88, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Booleano (bool)', 88, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué es un "IDE" en programación?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Instrucción de Depuración Extendida', 89, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Entorno de Desarrollo Integrado', 89, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Intercambio de Datos Eficiente', 89, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Interfaz Dinámica de Edición', 89, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué significa la sigla "SQL" en el contexto de bases de datos?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Structured Query Language', 90, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Sequential Query Language', 90, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Simple Query Language', 90, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Standard Query Library', 90, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En programación, ¿qué es un "array" o arreglo?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un valor numérico', 91, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una variable que almacena cadenas de texto', 91, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una estructura de datos que almacena una colección de elementos del mismo tipo', 91, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una estructura de control de flujo', 91, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Cuál de las siguientes no es una plataforma de desarrollo de software?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Windows', 92, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Linux', 92, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Microsoft Office', 92, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Android', 92, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En programación, ¿qué es un "API"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un formato de archivo', 93, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una plataforma de desarrollo web', 93, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una interfaz de programación de aplicaciones', 93, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un lenguaje de marcado', 93, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué lenguaje de programación es comúnmente utilizado para el análisis y procesamiento de datos?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('R', 94, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Ruby', 94, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Perl', 94, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('HTML', 94, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En programación, ¿qué es un "booleano" o tipo de dato booleano?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de variable que almacena números enteros', 95, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de variable que almacena números decimales', 95, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de variable que almacena valores verdaderos o falsos', 95, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de bucle', 95, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Cuál de las siguientes no es una operación aritmética en programación?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Suma', 96, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Multiplicación', 96, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Concatenación', 96, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Resta', 96, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué lenguaje de programación se utiliza comúnmente en el desarrollo de aplicaciones para iOS?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Python', 97, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Swift', 97, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Java', 97, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('C#', 97, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Qué es un "algoritmo" en programación?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un lenguaje de programación',98, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una lista de instrucciones que resuelven un problema', 98, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una función matemática', 98, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un bucle infinito', 98, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, 'En programación, ¿qué es un "ciclo for"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un tipo de bucle que repite un bloque de código hasta que se cumple una condición', 99, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Un lenguaje de programación', 99, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una instrucción que detiene el programa', 99, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Una función de impresión', 99, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(5, '¿Cuál de las siguientes no es una estructura de datos en programación?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Lista (list)', 100, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Árbol (tree)', 100, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Página web (web page)', 100, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Cola (queue)', 100, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Quién pintó la famosa obra "La última cena"?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Vincent van Gogh', 101, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pablo Picasso', 101, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Leonardo da Vinci', 101, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Salvador Dalí', 101, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(3, '¿Cuál de las siguientes corrientes artísticas se caracteriza por el uso de colores brillantes y formas geométricas?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Barroco', 102, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Pop art', 102, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Renacimiento', 102, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Realismo', 102, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el deporte que se juega en la Copa Davis?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis', 103, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fútbol', 103, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Golf', 103, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Baloncesto', 103, false);

    INSERT INTO pregunta(id_categoria, enunciado)values(2, '¿Cuál es el único deporte en el que los jugadores pueden usar sus pies, excepto el portero?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Fútbol', 104, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Baloncesto', 104, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Hockey sobre hielo', 104, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta)values('Tenis', 104, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (1, '¿Cuál es la primera película en la historia del cine que ganó los cinco premios Oscar principales?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Lo que el viento se llevó', 105, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Ben-Hur', 105, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Titanic', 105, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('El Silencio de los Corderos', 105, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (1, '¿Como se llama el protagonista de la famosa serie Breaking Bad?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Walter White', 106, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Jessy Pinkman', 106, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Homero Simpson', 106, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Anthony Bruno', 106, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (4, '¿Que se encuentra en el centro de casi todas las galaxias grandes?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Una estrella', 107, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Un agujero negro', 107, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Un agujero negro supermasivo', 107, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Ninguna es correcta', 107, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (4, '¿Cual es el limite imaginario de donde es imposible escapar en un agujero negro?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Horizonte de Einstein', 108, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Horizonte relativo', 108, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Horizonte de sucesos', 108, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Horizonte de Schwarzschild', 108, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (5, '¿Que lenguaje de programación tiene como logo una taza de café?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Java', 109, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Python', 109, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('C++', 109, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('C#', 109, false);

    INSERT INTO pregunta(id_categoria, enunciado) VALUES (5, '¿Cual de estos lenguajes de programación no existe?');
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Codescript', 110, true);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Malbolge', 110, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('Shakespeare', 110, false);
    INSERT INTO respuesta(texto, id_pregunta, es_correcta) VALUES ('CheLang', 110, false);

    UPDATE pregunta SET preg_default=1 where id > 100;

    UPDATE pregunta SET agregada=0 where id <= 110;

    UPDATE pregunta
    SET veces_respondida = 30
    WHERE (id > 10 AND id < 13)
       OR (id > 28 AND id < 31)
       OR (id > 46 AND id < 49)
       OR (id > 64 AND id < 67)
       OR (id > 82 AND id < 85);

    INSERT INTO user (username, name, spawn, sex, mail, password, image, puntaje, partidasRealizadas, qr, latitud, longitud, esEditor, esAdmin, token_verificacion, esta_verificado, veces_acertadas, veces_respondidas)
    values ('admin', 'admin', null, null, null, 'admin', null, null, null, null, null, null, 0, 1, 'admin', 1, null, null);

    INSERT INTO user (username, name, spawn, sex, mail, password, image, puntaje, partidasRealizadas, qr, latitud, longitud, esEditor, esAdmin, token_verificacion, esta_verificado, veces_acertadas, veces_respondidas)
    values ('editor', 'editor', null, null, null, 'editor', null, null, null, null, null, null, 1, 0, 'editor', 1, null, null);

INSERT INTO user (username, name, spawn, sex, mail, password, image, puntaje, partidasRealizadas, qr, latitud, longitud, esEditor, esAdmin, token_verificacion, esta_verificado, veces_acertadas, veces_respondidas, fecha_de_creacion, trampitas)
VALUES
    ("user1", "user1", "1950-11-06", "masculino", "user1@gmail.com", "user1", "public/images/generica.png", 10, 10, NULL, "-22.9712", "-43.1829", 0, 0, "user1", 1, 1, 1, "2020-11-08 11:07:30", 5),
    ("user2", "user2", "2002-11-06", "masculino", "user2@gmail.com", "user2", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user2", 1, 1, 1, "2023-11-08 11:07:30", 5),
    ("user3", "user3", "1995-08-15", "masculino", "user3@gmail.com", "password3", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user3", 1, 1, 1, "2023-11-09 11:07:31", 5),
    ("user4", "user4", "1987-04-22", "masculino", "user4@gmail.com", "password4", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user4", 1, 1, 1, "2023-11-10 11:07:32", 5),
    ("user5", "user5", "2000-12-01", "masculino", "user5@gmail.com", "password5", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user5", 1, 1, 1, "2023-11-11 11:07:33", 5),
    ("user6", "user6", "1982-03-10", "masculino", "user6@gmail.com", "password6", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user6", 1, 1, 1, "2023-11-12 11:07:34", 5),
    ("user7", "user7", "1998-06-05", "femenino", "user7@gmail.com", "password7", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user7", 1, 1, 1, "2023-11-13 11:07:35", 5),
    ("user8", "user8", "1975-09-18", "femenino", "user8@gmail.com", "password8", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user8", 1, 1, 1, "2023-11-14 11:07:36", 5),
    ("user9", "user9", "1989-11-30", "femenino", "user9@gmail.com", "password9", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user9", 1, 1, 1, "2023-11-15 11:07:37", 5),
    ("user10", "user10", "1978-07-12", "femenino", "user10@gmail.com", "password10", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user10", 1, 1, 1, "2023-11-12 11:07:38", 5),
    ("user11", "user11", "1961-02-28", "femenino", "user11@gmail.com", "password11", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user11", 1, 1, 1, "2023-11-13 11:07:39", 5),
    ("user12", "user12", "1961-10-15", "femenino", "user12@gmail.com", "password12", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user12", 1, 1, 1, "2023-11-12 11:07:40", 5),
    ("user13", "user13", "1959-07-25", "x", "user13@gmail.com", "password13", "public/images/generica.png", 10, 10, NULL, "39.9042", "116.4074", 0, 0, "user13", 1, 1, 1, "2023-11-10 11:07:41", 5),
    ("user14", "user14", "1962-01-14", "x", "user14@gmail.com", "password14", "public/images/generica.png", 10, 10, NULL, "39.9042", "116.4074", 0, 0, "user14", 1, 1, 1, "2023-11-10 11:07:42", 5),
    ("user15", "user15", "1961-05-03", "x", "user15@gmail.com", "password15", "public/images/generica.png", 10, 10, NULL, "39.9042", "116.4074", 0, 0, "user15", 1, 1, 1, "2023-11-10 11:07:43", 5),
    ("user16", "user16", "1019-09-20", "x", "user16@gmail.com", "password16", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user16", 1, 1, 1, "2023-11-10 11:07:44", 5),
    ("user17", "user17", "2018-03-07", "x", "user17@gmail.com", "password17", "public/images/generica.png", 10, 10, NULL, "35.6895", "139.6917", 0, 0, "user17", 1, 1, 1, "2023-11-10 11:07:45", 5),
    ("user18", "user18", "2017-12-25", "x", "user18@gmail.com", "password18", "public/images/generica.png", 10, 10, NULL, "35.6895", "139.6917", 0, 0, "user18", 1, 1, 1, "2023-11-10 11:07:46", 5),
    ("user19", "user19", "2016-04-17", "x", "user19@gmail.com", "password19", "public/images/generica.png", 10, 10, NULL, "35.6895", "139.6917", 0, 0, "user19", 1, 1, 1, "2023-11-10 11:07:47", 5),
    ("user20", "user20", "2015-08-08", "x", "user20@gmail.com", "password20", "public/images/generica.png", 10, 10, NULL, "-34.6118", "-58.4173", 0, 0, "user20", 1, 1, 1, "2023-11-10 11:07:48", 5);
