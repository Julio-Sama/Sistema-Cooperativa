-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-11-2022 a las 22:33:02
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_coopertiva`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id_config` int(255) NOT NULL,
  `nom_asociacion` varchar(100) DEFAULT NULL,
  `usuario_config` varchar(50) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `dir_asociacion` varchar(80) DEFAULT NULL,
  `tel_asociacion` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id_config`, `nom_asociacion`, `usuario_config`, `clave`, `dir_asociacion`, `tel_asociacion`) VALUES
(1, 'Cooperativa Don Teco', 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'El Salvador, San Vicente', '2200-5313');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota`
--

CREATE TABLE `cuota` (
  `no_cuota` int(255) NOT NULL,
  `id_prestamo` int(255) DEFAULT NULL,
  `fecha_pago_cuota` date DEFAULT NULL,
  `monto_cuota` double(255,2) DEFAULT NULL,
  `mora_cuota` double(255,2) DEFAULT NULL,
  `estado_cuota` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cuota`
--

INSERT INTO `cuota` (`no_cuota`, `id_prestamo`, `fecha_pago_cuota`, `monto_cuota`, `mora_cuota`, `estado_cuota`) VALUES
(2, 11, '2022-11-04', 22.16, 0.00, 'Pendiente'),
(3, 11, '2022-12-04', 22.16, 0.00, 'Pendiente'),
(4, 11, '2023-01-04', 22.16, 0.00, 'Pendiente'),
(5, 11, '2023-02-04', 22.16, 0.00, 'Pendiente'),
(6, 11, '2023-03-04', 22.16, 0.00, 'Pendiente'),
(7, 12, '2022-11-09', 209.28, 0.00, 'Pendiente'),
(8, 12, '2022-12-09', 209.28, 0.00, 'Pendiente'),
(9, 12, '2023-01-09', 209.28, 0.00, 'Pendiente'),
(10, 12, '2023-02-09', 209.28, 0.00, 'Pendiente'),
(11, 12, '2023-03-09', 209.28, 0.00, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destino`
--

CREATE TABLE `destino` (
  `id_destino` int(255) NOT NULL,
  `nom_destino` varchar(80) DEFAULT NULL,
  `interes_destino` float(255,0) DEFAULT NULL,
  `desde_destino` date DEFAULT NULL,
  `hasta_destino` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `destino`
--

INSERT INTO `destino` (`id_destino`, `nom_destino`, `interes_destino`, `desde_destino`, `hasta_destino`) VALUES
(1, 'Ganaderia', 5, '2022-10-12', '2022-12-31'),
(2, 'Cultivo', 10, '2022-11-02', NULL),
(3, 'Personal', 25, '2022-11-03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(255) NOT NULL,
  `cod_socio` varchar(6) DEFAULT NULL,
  `id_destino` int(255) DEFAULT NULL,
  `monto_prestamo` double(255,2) DEFAULT NULL,
  `abono_capital_prestamo` double(255,2) DEFAULT NULL,
  `seguro_prestamo` double(255,2) DEFAULT NULL,
  `fecha_emision_prestamo` date DEFAULT NULL,
  `fecha_inicio_pago` date DEFAULT NULL,
  `forma_pago_prestamo` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `cod_socio`, `id_destino`, `monto_prestamo`, `abono_capital_prestamo`, `seguro_prestamo`, `fecha_emision_prestamo`, `fecha_inicio_pago`, `forma_pago_prestamo`) VALUES
(1, 'JR0001', 1, 1000.00, 0.00, 1.00, '2022-10-12', NULL, 'Mensual'),
(11, 'MQ0003', 1, 110.80, 0.00, 5.80, '2022-11-03', '2022-11-04', 'month'),
(12, 'TR0002', 3, 1046.40, 0.00, 46.40, '2022-11-03', '2022-11-09', 'month');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socio`
--

CREATE TABLE `socio` (
  `cod_socio` varchar(6) NOT NULL,
  `nombre_socio` varchar(80) DEFAULT NULL,
  `apellido_socio` varchar(80) DEFAULT NULL,
  `tel_socio` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `socio`
--

INSERT INTO `socio` (`cod_socio`, `nombre_socio`, `apellido_socio`, `tel_socio`) VALUES
('JR0001', 'Josue Adonay', 'Aguilar Rivas', '7721-1231'),
('MQ0003', 'Walter Alejandro', 'Morales Quintanilla', '7788-3201'),
('TR0002', 'Julio Antonio', 'Torres Rodríguez', '7421-1312');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id_config`);

--
-- Indices de la tabla `cuota`
--
ALTER TABLE `cuota`
  ADD PRIMARY KEY (`no_cuota`),
  ADD KEY `fk1` (`id_prestamo`);

--
-- Indices de la tabla `destino`
--
ALTER TABLE `destino`
  ADD PRIMARY KEY (`id_destino`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `fk2` (`cod_socio`),
  ADD KEY `fk3` (`id_destino`);

--
-- Indices de la tabla `socio`
--
ALTER TABLE `socio`
  ADD PRIMARY KEY (`cod_socio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_config` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuota`
--
ALTER TABLE `cuota`
  MODIFY `no_cuota` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `destino`
--
ALTER TABLE `destino`
  MODIFY `id_destino` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuota`
--
ALTER TABLE `cuota`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamo` (`id_prestamo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`cod_socio`) REFERENCES `socio` (`cod_socio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk3` FOREIGN KEY (`id_destino`) REFERENCES `destino` (`id_destino`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
