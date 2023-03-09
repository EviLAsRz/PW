-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-04-2022 a las 20:10:21
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `casem`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `asignatura_codigo` int(11) NOT NULL,
  `asignatura_nombre` varchar(60) NOT NULL,
  `asignatura_coordinador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`asignatura_codigo`, `asignatura_nombre`, `asignatura_coordinador`) VALUES
(3, 'Medio Fisico', 8),
(4, 'Zoologia y Botanica', 10),
(5, 'Derecho Publico del Medio Ambiente', 8),
(6, 'Quimica', 10),
(7, 'Cambio Climatico', 8),
(8, 'Ecosistemas Marinos', 10),
(9, 'Pesquerias ', NULL),
(10, 'Economia y Legislacion', 10),
(11, 'Acuicultura', 9),
(12, 'Ingenieria Costera', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_estudiante`
--

CREATE TABLE `asignatura_estudiante` (
  `estudiante_id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `asignatura_estudiante`
--

INSERT INTO `asignatura_estudiante` (`estudiante_id`, `asignatura_id`) VALUES
(12, 3),
(12, 4),
(12, 5),
(12, 6),
(12, 7),
(13, 8),
(13, 9),
(13, 10),
(13, 11),
(13, 12),
(14, 4),
(14, 6),
(14, 8),
(14, 9),
(14, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura_tema`
--

CREATE TABLE `asignatura_tema` (
  `asignatura_codigo` int(11) NOT NULL,
  `tema_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `asignatura_tema`
--

INSERT INTO `asignatura_tema` (`asignatura_codigo`, `tema_codigo`) VALUES
(3, 7),
(3, 8),
(3, 9),
(4, 10),
(4, 11),
(4, 12),
(5, 13),
(5, 14),
(5, 15),
(6, 16),
(6, 17),
(6, 18),
(7, 19),
(7, 20),
(7, 21),
(8, 22),
(8, 23),
(8, 24),
(9, 25),
(9, 26),
(9, 27),
(10, 28),
(10, 29),
(10, 30),
(11, 31),
(11, 32),
(11, 33),
(12, 34),
(12, 35),
(12, 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `estudiante_codigo` int(11) NOT NULL,
  `estudiante_usuario` int(11) NOT NULL,
  `estudiante_grado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`estudiante_codigo`, `estudiante_usuario`, `estudiante_grado`) VALUES
(12, 26, 'Ciencias Ambientales'),
(13, 27, 'Ciencias del Mar'),
(14, 28, 'Grado en Ciencias del Mar y Ambientales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_examen`
--

CREATE TABLE `estudiante_examen` (
  `estudiante_codigo` int(11) NOT NULL,
  `examen_codigo` int(11) NOT NULL,
  `calificacion` float NOT NULL,
  `estudiante_nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiante_examen`
--

INSERT INTO `estudiante_examen` (`estudiante_codigo`, `examen_codigo`, `calificacion`, `estudiante_nombre`) VALUES
(13, 27, 2.5, 'Sara Roldan Pomino'),
(14, 27, 2.5, 'Miguel Leon Moya');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_pregunta`
--

CREATE TABLE `estudiante_pregunta` (
  `estudiante_codigo` int(11) NOT NULL,
  `examen_codigo` int(11) NOT NULL,
  `pregunta_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiante_pregunta`
--

INSERT INTO `estudiante_pregunta` (`estudiante_codigo`, `examen_codigo`, `pregunta_codigo`) VALUES
(13, 27, 3),
(13, 27, 4),
(13, 27, 6),
(13, 27, 10),
(13, 27, 11),
(13, 27, 12),
(14, 27, 3),
(14, 27, 4),
(14, 27, 7),
(14, 27, 10),
(14, 27, 18),
(14, 27, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `examen_codigo` int(11) NOT NULL,
  `examen_tema` int(11) NOT NULL,
  `examen_fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`examen_codigo`, `examen_tema`, `examen_fecha`) VALUES
(7, 7, '2022-04-04'),
(8, 8, '2022-04-04'),
(9, 9, '2022-04-04'),
(10, 10, '2022-04-04'),
(11, 11, '2022-04-04'),
(12, 12, '2022-04-04'),
(13, 13, '2022-04-04'),
(14, 14, '2022-04-04'),
(15, 15, '2022-04-04'),
(16, 16, '2022-04-04'),
(17, 17, '2022-04-04'),
(18, 18, '2022-04-04'),
(19, 19, '2022-04-04'),
(20, 20, '2022-04-04'),
(21, 21, '2022-04-04'),
(22, 22, '2022-04-04'),
(23, 23, '2022-04-04'),
(24, 24, '2022-04-04'),
(25, 25, '2022-04-04'),
(26, 26, '2022-04-04'),
(27, 27, '2022-04-02'),
(28, 28, '2022-04-04'),
(29, 29, '2022-04-04'),
(30, 30, '2022-04-04'),
(31, 31, '2022-04-04'),
(32, 32, '2022-04-04'),
(33, 33, '2022-04-04'),
(34, 34, '2022-04-04'),
(35, 35, '2022-04-04'),
(36, 36, '2022-04-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `grado_nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`grado_nombre`) VALUES
('Ciencias Ambientales'),
('Ciencias del Mar'),
('Grado en Ciencias del Mar y Ambientales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado_asignatura`
--

CREATE TABLE `grado_asignatura` (
  `grado_nombre` varchar(50) NOT NULL,
  `asignatura_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `grado_asignatura`
--

INSERT INTO `grado_asignatura` (`grado_nombre`, `asignatura_codigo`) VALUES
('Ciencias Ambientales', 3),
('Ciencias Ambientales', 4),
('Ciencias Ambientales', 5),
('Ciencias Ambientales', 6),
('Ciencias Ambientales', 7),
('Ciencias del Mar', 8),
('Ciencias del Mar', 9),
('Ciencias del Mar', 10),
('Ciencias del Mar', 11),
('Ciencias del Mar', 12),
('Grado en Ciencias del Mar y Ambientales', 4),
('Grado en Ciencias del Mar y Ambientales', 6),
('Grado en Ciencias del Mar y Ambientales', 8),
('Grado en Ciencias del Mar y Ambientales', 9),
('Grado en Ciencias del Mar y Ambientales', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `pregunta_codigo` int(11) NOT NULL,
  `pregunta_texto` varchar(200) NOT NULL,
  `pregunta_tema` int(11) NOT NULL,
  `pregunta_tipo` enum('abcd','v/f') NOT NULL,
  `pregunta_penalizacion` enum('cuarto','medio') NOT NULL,
  `pregunta_respuesta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`pregunta_codigo`, `pregunta_texto`, `pregunta_tema`, `pregunta_tipo`, `pregunta_penalizacion`, `pregunta_respuesta`) VALUES
(1, 'El choco está mejor con limón', 27, 'v/f', 'cuarto', 5),
(2, 'Cuales son los mejores gambones', 27, 'abcd', 'cuarto', 8),
(3, 'Con que se abren las almejas cerradas', 27, 'abcd', 'cuarto', 14),
(4, 'Las puntillitas son la comida favorita de Popeye', 27, 'v/f', 'cuarto', 6),
(5, 'Las patas de cangrejo están prohibidas en la noche buena de Marruecos', 27, 'v/f', 'cuarto', 5),
(6, 'Cual de estos pescados está mejor sin limón', 27, 'abcd', 'cuarto', 15),
(7, 'El pulpo gallego es de La Palma', 27, 'v/f', 'cuarto', 6),
(8, 'Cual de estas alergias es falsa', 27, 'abcd', 'cuarto', 22),
(9, 'De que color es el chubasquero del capitán merluza', 27, 'abcd', 'cuarto', 24),
(10, 'Cual de estos personajes de Bob Esponja no está basado en la realidad', 27, 'abcd', 'cuarto', 29),
(11, 'Valdelagrana aloja 7 distintas variaciones de la gamba marina', 27, 'v/f', 'cuarto', 5),
(12, 'Cual es la salsa mas buena para acompañar las almejas', 27, 'abcd', 'cuarto', 31),
(13, 'Por qué Zahara de los Atunes es de los Atunes', 27, 'abcd', 'cuarto', 36),
(14, 'Es costumbre comer sardinas en la festividad de San Juan', 27, 'v/f', 'cuarto', 5),
(15, 'Los calamares de campo son primos lejanos de los camarones', 27, 'v/f', 'cuarto', 6),
(16, 'Cual de los siguientes paises está destinado que extingan a los caballitos de mar', 27, 'abcd', 'cuarto', 41),
(17, 'Cual es el mejor plato de Fish&Chips', 27, 'abcd', 'cuarto', 45),
(18, 'El lugar donde pican más las medusas es en las pantorrillas', 27, 'v/f', 'cuarto', 6),
(19, 'Aquaman puede controlar a los crustáceos', 27, 'abcd', 'cuarto', 47);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta_respuesta`
--

CREATE TABLE `pregunta_respuesta` (
  `pregunta_codigo` int(11) NOT NULL,
  `respuesta_codigo` int(11) NOT NULL,
  `resultado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pregunta_respuesta`
--

INSERT INTO `pregunta_respuesta` (`pregunta_codigo`, `respuesta_codigo`, `resultado`) VALUES
(1, 5, 1),
(1, 6, 0),
(2, 7, 0),
(2, 8, 1),
(2, 9, 0),
(2, 10, 0),
(3, 11, 0),
(3, 12, 0),
(3, 13, 0),
(3, 14, 1),
(4, 6, 1),
(4, 5, 0),
(5, 5, 1),
(5, 6, 0),
(6, 15, 1),
(6, 16, 0),
(6, 17, 0),
(6, 18, 0),
(7, 6, 1),
(7, 5, 0),
(8, 19, 0),
(8, 20, 0),
(8, 21, 0),
(8, 22, 1),
(9, 23, 0),
(9, 24, 1),
(9, 25, 0),
(9, 26, 0),
(10, 27, 0),
(10, 28, 0),
(10, 29, 1),
(10, 30, 0),
(11, 5, 1),
(11, 6, 0),
(12, 31, 1),
(12, 32, 0),
(12, 33, 0),
(12, 34, 0),
(13, 35, 0),
(13, 36, 1),
(13, 37, 0),
(13, 38, 0),
(14, 5, 1),
(14, 6, 0),
(15, 6, 1),
(15, 5, 0),
(16, 39, 0),
(16, 40, 0),
(16, 41, 1),
(16, 42, 0),
(17, 43, 0),
(17, 44, 0),
(17, 45, 1),
(17, 46, 0),
(18, 6, 1),
(18, 5, 0),
(19, 47, 1),
(19, 48, 0),
(19, 49, 0),
(19, 50, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `profesor_codigo` int(11) NOT NULL,
  `profesor_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`profesor_codigo`, `profesor_usuario`) VALUES
(8, 23),
(9, 24),
(10, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_asignatura`
--

CREATE TABLE `profesor_asignatura` (
  `profesor_codigo` int(11) NOT NULL,
  `asignatura_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `profesor_asignatura`
--

INSERT INTO `profesor_asignatura` (`profesor_codigo`, `asignatura_codigo`) VALUES
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(9, 8),
(9, 9),
(9, 10),
(9, 11),
(9, 12),
(10, 4),
(10, 6),
(10, 8),
(10, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_pregunta`
--

CREATE TABLE `profesor_pregunta` (
  `pregunta_codigo` int(11) NOT NULL,
  `profesor_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `profesor_pregunta`
--

INSERT INTO `profesor_pregunta` (`pregunta_codigo`, `profesor_codigo`) VALUES
(1, 10),
(2, 10),
(3, 10),
(4, 10),
(5, 10),
(6, 10),
(7, 10),
(8, 10),
(9, 10),
(10, 10),
(11, 10),
(12, 10),
(13, 10),
(14, 10),
(15, 10),
(16, 10),
(17, 10),
(18, 10),
(19, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `respuesta_codigo` int(11) NOT NULL,
  `respuesta_texto` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`respuesta_codigo`, `respuesta_texto`) VALUES
(5, 'Verdadero'),
(6, 'Falso'),
(7, 'Los de Huelva'),
(8, 'A la barbacoa'),
(9, 'Los del Aldi'),
(10, 'Los de Sanlucar'),
(11, 'Palillo de dientes'),
(12, 'Con un centimo'),
(13, 'Con un clic'),
(14, 'Con los dientes'),
(15, 'mejillones con limon'),
(16, 'bieiras'),
(17, 'coquinas'),
(18, 'calamares'),
(19, 'al pescado'),
(20, 'al marisco'),
(21, 'al atún'),
(22, 'a las puntillitas'),
(23, 'fantasia'),
(24, 'amarillo'),
(25, 'negro'),
(26, 'marron'),
(27, 'Plackton'),
(28, 'Patricio'),
(29, 'Arenita'),
(30, 'La de la autoescuela'),
(31, 'marinera'),
(32, 'al ajillo'),
(33, 'al limón'),
(34, 'carbonara'),
(35, 'Es la cuna de nacimiento del atún'),
(36, 'Por la cara'),
(37, 'Se hace el mejor atún de España'),
(38, 'No se sabe'),
(39, 'China'),
(40, 'Venezuela'),
(41, 'Portugal'),
(42, 'Turquía'),
(43, 'Fish'),
(44, 'Fish but no chips'),
(45, 'Only chips'),
(46, 'Fish and chips'),
(47, 'Asies'),
(48, 'De hecho no'),
(49, 'Solo si está fuera del agua'),
(50, 'Solo a los mejillones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temas`
--

CREATE TABLE `temas` (
  `tema_codigo` int(11) NOT NULL,
  `tema_nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `temas`
--

INSERT INTO `temas` (`tema_codigo`, `tema_nombre`) VALUES
(7, 'La Patagonia'),
(8, 'Despeñaperros'),
(9, 'Peru'),
(10, 'Amapolas'),
(11, 'Pijielagartos'),
(12, 'Furros'),
(13, 'Donde orinar'),
(14, 'Donde no orinar'),
(15, 'Localizaciones de Mcdonals'),
(16, 'Opiaceos'),
(17, 'Tatuajes de formulacion organica'),
(18, 'Por que ver Breaking Bad?'),
(19, 'El Tifon Jebi'),
(20, 'Se inunda Cadiz'),
(21, 'Greta Trueno'),
(22, 'El Megalodon'),
(23, 'Bandera verde, amarilla y roja'),
(24, 'Donde se ponen las boyas'),
(25, 'Como tirar la caña'),
(26, 'Outfits para pescar'),
(27, 'Pescados con o sin limon'),
(28, 'Sigma Male Rules'),
(29, 'NFT screenshots'),
(30, 'Oposiciones a Defensor del Pueblo'),
(31, 'Marcas de agua'),
(32, 'Aqualands'),
(33, 'Gormitis de agua'),
(34, 'Yates del GTA V'),
(35, 'La Marina de One Piece'),
(36, 'Puerto Banus vs Puerto Sherry');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_codigo` int(11) NOT NULL,
  `usuario_nif` varchar(9) NOT NULL,
  `usuario_password` varchar(20) NOT NULL,
  `usuario_rol` enum('estudiante','profesor','admin') NOT NULL,
  `usuario_nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_codigo`, `usuario_nif`, `usuario_password`, `usuario_rol`, `usuario_nombre`) VALUES
(22, '11111111G', 'kalilinux', 'admin', 'Jota Santos Orellana'),
(23, '55555555G', 'redwallet', 'profesor', 'Cipriano Salas Orellana'),
(24, '66666666G', 'vectordefunciones', 'profesor', 'Alex Barba Heuristica'),
(25, '77777777G', 'ficheromaligno', 'profesor', 'Eloisa Argudo Rioja'),
(26, '22222222G', 'juevesgordo', 'estudiante', 'Gordemi Cabrales de la Torre'),
(27, '33333333G', 'nohagaeso', 'estudiante', 'Sara Roldan Pomino'),
(28, '44444444G', 'nohayminas', 'estudiante', 'Miguel Leon Moya');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`asignatura_codigo`);

--
-- Indices de la tabla `asignatura_estudiante`
--
ALTER TABLE `asignatura_estudiante`
  ADD KEY `fk_asignatura_matriculada` (`asignatura_id`),
  ADD KEY `fk_estudiante_matriculado` (`estudiante_id`);

--
-- Indices de la tabla `asignatura_tema`
--
ALTER TABLE `asignatura_tema`
  ADD KEY `fk_asignatura_en_tema` (`asignatura_codigo`),
  ADD KEY `fk_tema_en_asignatura` (`tema_codigo`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`estudiante_codigo`),
  ADD KEY `fk_usuario_en_estudiante` (`estudiante_usuario`);

--
-- Indices de la tabla `estudiante_examen`
--
ALTER TABLE `estudiante_examen`
  ADD KEY `fk_examen_en_estudiante` (`examen_codigo`);

--
-- Indices de la tabla `estudiante_pregunta`
--
ALTER TABLE `estudiante_pregunta`
  ADD KEY `fk_estudiante_pregunta` (`estudiante_codigo`),
  ADD KEY `fk_examen_pregunta` (`examen_codigo`),
  ADD KEY `fk_pregunta_pregunta` (`pregunta_codigo`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`examen_codigo`),
  ADD KEY `fk_tema_en_examen` (`examen_tema`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`grado_nombre`);

--
-- Indices de la tabla `grado_asignatura`
--
ALTER TABLE `grado_asignatura`
  ADD KEY `fk_grado_en_asignatura` (`grado_nombre`),
  ADD KEY `fk_asignatura_en_grado` (`asignatura_codigo`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`pregunta_codigo`),
  ADD UNIQUE KEY `pregunta_texto` (`pregunta_texto`),
  ADD KEY `fk_tema_en_pregunta` (`pregunta_tema`),
  ADD KEY `fk_respuesta_en_pregunta` (`pregunta_respuesta`);

--
-- Indices de la tabla `pregunta_respuesta`
--
ALTER TABLE `pregunta_respuesta`
  ADD KEY `fk_pregunta_en_respuesta` (`pregunta_codigo`),
  ADD KEY `fk_respuesta_en_pregunta2` (`respuesta_codigo`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`profesor_codigo`),
  ADD KEY `fk_usuario_en_profesor` (`profesor_usuario`);

--
-- Indices de la tabla `profesor_asignatura`
--
ALTER TABLE `profesor_asignatura`
  ADD KEY `fk_profesor_en_asignatura2` (`profesor_codigo`),
  ADD KEY `fk_asignatura_en_profesor` (`asignatura_codigo`);

--
-- Indices de la tabla `profesor_pregunta`
--
ALTER TABLE `profesor_pregunta`
  ADD KEY `fk_pregunta_profesor` (`pregunta_codigo`),
  ADD KEY `fk_profesor_pregunta` (`profesor_codigo`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`respuesta_codigo`);

--
-- Indices de la tabla `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`tema_codigo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_codigo`),
  ADD UNIQUE KEY `usuario_nif` (`usuario_nif`),
  ADD UNIQUE KEY `usuario_password` (`usuario_password`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `asignatura_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `estudiante_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `examen_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `pregunta_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `profesor_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `respuesta_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `temas`
--
ALTER TABLE `temas`
  MODIFY `tema_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignatura_estudiante`
--
ALTER TABLE `asignatura_estudiante`
  ADD CONSTRAINT `fk_asignatura_matriculada` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`asignatura_codigo`),
  ADD CONSTRAINT `fk_estudiante_matriculado` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`estudiante_codigo`);

--
-- Filtros para la tabla `asignatura_tema`
--
ALTER TABLE `asignatura_tema`
  ADD CONSTRAINT `fk_asignatura_en_tema` FOREIGN KEY (`asignatura_codigo`) REFERENCES `asignaturas` (`asignatura_codigo`),
  ADD CONSTRAINT `fk_tema_en_asignatura` FOREIGN KEY (`tema_codigo`) REFERENCES `temas` (`tema_codigo`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `fk_usuario_en_estudiante` FOREIGN KEY (`estudiante_usuario`) REFERENCES `usuarios` (`usuario_codigo`);

--
-- Filtros para la tabla `estudiante_examen`
--
ALTER TABLE `estudiante_examen`
  ADD CONSTRAINT `fk_examen_en_estudiante` FOREIGN KEY (`examen_codigo`) REFERENCES `examenes` (`examen_codigo`);

--
-- Filtros para la tabla `estudiante_pregunta`
--
ALTER TABLE `estudiante_pregunta`
  ADD CONSTRAINT `fk_estudiante_pregunta` FOREIGN KEY (`estudiante_codigo`) REFERENCES `estudiantes` (`estudiante_codigo`),
  ADD CONSTRAINT `fk_examen_pregunta` FOREIGN KEY (`examen_codigo`) REFERENCES `examenes` (`examen_codigo`),
  ADD CONSTRAINT `fk_pregunta_pregunta` FOREIGN KEY (`pregunta_codigo`) REFERENCES `preguntas` (`pregunta_codigo`);

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `fk_tema_en_examen` FOREIGN KEY (`examen_tema`) REFERENCES `temas` (`tema_codigo`);

--
-- Filtros para la tabla `grado_asignatura`
--
ALTER TABLE `grado_asignatura`
  ADD CONSTRAINT `fk_asignatura_en_grado` FOREIGN KEY (`asignatura_codigo`) REFERENCES `asignaturas` (`asignatura_codigo`),
  ADD CONSTRAINT `fk_grado_en_asignatura` FOREIGN KEY (`grado_nombre`) REFERENCES `grados` (`grado_nombre`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `fk_respuesta_en_pregunta` FOREIGN KEY (`pregunta_respuesta`) REFERENCES `respuestas` (`respuesta_codigo`),
  ADD CONSTRAINT `fk_tema_en_pregunta` FOREIGN KEY (`pregunta_tema`) REFERENCES `temas` (`tema_codigo`);

--
-- Filtros para la tabla `pregunta_respuesta`
--
ALTER TABLE `pregunta_respuesta`
  ADD CONSTRAINT `fk_respuesta_en_pregunta2` FOREIGN KEY (`respuesta_codigo`) REFERENCES `respuestas` (`respuesta_codigo`);

--
-- Filtros para la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD CONSTRAINT `fk_usuario_en_profesor` FOREIGN KEY (`profesor_usuario`) REFERENCES `usuarios` (`usuario_codigo`);

--
-- Filtros para la tabla `profesor_asignatura`
--
ALTER TABLE `profesor_asignatura`
  ADD CONSTRAINT `fk_asignatura_en_profesor` FOREIGN KEY (`asignatura_codigo`) REFERENCES `asignaturas` (`asignatura_codigo`),
  ADD CONSTRAINT `fk_profesor_en_asignatura2` FOREIGN KEY (`profesor_codigo`) REFERENCES `profesores` (`profesor_codigo`);

--
-- Filtros para la tabla `profesor_pregunta`
--
ALTER TABLE `profesor_pregunta`
  ADD CONSTRAINT `fk_pregunta_profesor` FOREIGN KEY (`pregunta_codigo`) REFERENCES `preguntas` (`pregunta_codigo`),
  ADD CONSTRAINT `fk_profesor_pregunta` FOREIGN KEY (`profesor_codigo`) REFERENCES `profesores` (`profesor_codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
