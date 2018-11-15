-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2018 a las 00:14:19
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
(11, 'no'),
(12, 'si');

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
(11, 'Oihan', 'Oihan Irastorza', 'bc0f8ac8c8b38a7aea5c07e466167e81'),
(12, 'María', 'María Berasaluze', 'c2cdb6275dc542e4d0f1506fc8d92d54'),
(13, 'Botxan', 'Botxan Pipastorza', 'ca94af2819934190d1f6c338dd8ba108');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viviendas`
--

CREATE TABLE `viviendas` (
  `vivienda_id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `texto` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `metros_cuadrados` decimal(6,2) DEFAULT NULL,
  `precio` decimal(9,2) DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `viviendas`
--

INSERT INTO `viviendas` (`vivienda_id`, `titulo`, `texto`, `metros_cuadrados`, `precio`, `categoria`, `fecha`, `imagen`) VALUES
(1, 'Equitativa', 'Casa en Donostia con vistas al rio Urumea.', '60.00', '240000.00', 'costas', '2018-10-13 20:18:25', 'img/ico-fichero.gif'),
(2, 'Promoción en Costa Ballena', 'Con vistas al campo de golf, magníficas calidades, entorno ajardinado con piscina y servicio de vigilancia.', '55.00', '220000.00', 'costas', '2018-10-13 20:20:48', 'img/ico-fichero.gif'),
(3, 'Apartamento en el Puerto de Sta María', 'En la playa de Valdegrana, en primera línea de playa. Pisos reformados y completamente amueblados.', '50.00', '200000.00', 'costas', '2018-11-10 16:48:09', 'img/ico-fichero.gif'),
(4, 'Últimas viviendas junto al río', 'Apartamentos de 1 y 2 dormitorios, con fantásticas vistas. Excelentes condiciones de financiación.', '50.00', '200000.00', 'ofertas', '2018-10-13 20:21:21', 'img/apartamento9.jpg'),
(5, 'Nueva promoción en Nervión', '145 viviendas de lujo en urbanización ajardinada situadas en un entorno privilegiado.', '70.00', '175000.00', 'promociones', '2018-10-13 20:21:21', 'img/apartamento8.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viviendas_historica`
--

CREATE TABLE `viviendas_historica` (
  `vivienda_id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `texto` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `metros_cuadrados` decimal(6,2) DEFAULT NULL,
  `precio` decimal(9,2) DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_borrado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
