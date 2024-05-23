CREATE DATABASE IF NOT EXISTS preguntados;
USE preguntados;

CREATE TABLE usuarios (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nombre_completo VARCHAR(50) NOT NULL,
      anio_nacimiento INT NOT NULL,
      sexo CHAR(1) NOT NULL,
      pais VARCHAR(50) NOT NULL,
      ciudad VARCHAR(50) NOT NULL,
      email VARCHAR(50) NOT NULL,
      password VARCHAR(50) NOT NULL,
      username VARCHAR(50) NOT NULL,
      foto VARCHAR(100),
      token VARCHAR(100) NOT NULL,
      habilitado INT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

