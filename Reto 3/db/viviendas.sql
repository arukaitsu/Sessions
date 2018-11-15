-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2018 a las 11:17:34
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `viviendas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `voto` enum('si','no') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`id`, `voto`) VALUES
(1, 'no'),
(3, 'si'),
(4, 'no'),
(6, 'no');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `nombre` varchar(32) NOT NULL,
  `clave` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `login`, `nombre`, `clave`) VALUES
(1, 'Botxan', 'Oihan', '123oihan'),
(3, 'xmarijo', 'mari jose', '123marijose'),
(4, 'drakkisnev', 'Mikel Mendizabal', '123mikel'),
(6, 'Maria', 'Maria Berasaluze', 'nopongaisextras'),
(7, 'Galleta', 'Galleta', 'Galleta123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viviendas`
--

CREATE TABLE `viviendas` (
  `vivienda_id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `texto` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `viviendas`
--

INSERT INTO `viviendas` (`vivienda_id`, `titulo`, `texto`, `categoria`, `fecha`, `imagen`) VALUES
(3, 'Equitativa', 'Casa en Donostia con vistas al rio Urumea.', 'costas', '2018-10-13 20:18:25', 'img/ico-fichero.gif'),
(4, 'Nueva promoción en Nervión', '145 viviendas de lujo en urbanización ajardinada situadas en un entorno privilegiado.', 'promociones', '2018-10-13 20:21:21', 'img/apartamento8.jpg'),
(5, 'Promoción en Costa Ballena', 'Con vistas al campo de gold, magíficas calidades, entorno ajardinado con piscina y servicio de vigilancia.', 'costas', '2018-10-13 20:20:48', 'img/ico-fichero.gif'),
(6, 'Últimas viviendas junto al río', 'Apartamentos de 1 y 2 dormitorios, con fantásticas vistas. Excelentes condiciones de financiación.', 'ofertas', '2018-10-13 20:21:21', 'img/apartamento9.jpg'),
(34, 'Apartamentos en el Puerto de Sta María', 'En la playa de Valdelagrana, en primera línea de playa. Pisos reformados y completamente amueblados. ', 'costas', '2018-10-29 10:15:15', 'img/ico-fichero.gif');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viviendas_historica`
--

CREATE TABLE `viviendas_historica` (
  `vivienda_id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `texto` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_borrado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `viviendas_historica`
--

INSERT INTO `viviendas_historica` (`vivienda_id`, `titulo`, `texto`, `categoria`, `fecha`, `imagen`, `fecha_borrado`) VALUES
(29, 'Hola, soy Luis, y esta es mi choza.', 'Pues eso, una chabola hermosa con vistas a un muro. Como no tengo fotos de la vivienda, os dejo una foto mía. Habladme por favor.', 'costas', '2018-10-29 09:14:31', 'img/5bd6cf77697a24.07901320.png', '2018-10-29 09:35:45'),
(30, 'fhd', 'dh', 'costas', '2018-10-29 09:41:30', 'img/ico-fichero.gif', '2018-10-29 10:12:59'),
(31, 'rdf', 'gdhh', 'costas', '2018-10-29 09:41:32', 'img/ico-fichero.gif', '2018-10-29 09:42:08'),
(32, 'hrt', 'htrvc', 'costas', '2018-10-29 09:41:34', 'img/ico-fichero.gif', '2018-10-29 09:41:48'),
(33, 'lkjhjklh', 'kjlhlkj', 'costas', '2018-10-29 10:12:14', 'img/5bd6dcfee33743.03905114.png', '2018-10-29 10:12:27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Indices de la tabla `viviendas`
--
ALTER TABLE `viviendas`
  ADD PRIMARY KEY (`vivienda_id`);

--
-- Indices de la tabla `viviendas_historica`
--
ALTER TABLE `viviendas_historica`
  ADD PRIMARY KEY (`vivienda_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `viviendas`
--
ALTER TABLE `viviendas`
  MODIFY `vivienda_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD CONSTRAINT `viviendas_id_encuesta_id` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
