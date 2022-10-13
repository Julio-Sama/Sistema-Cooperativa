-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2022 at 06:48 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_coopertiva`
--

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `id_config` int(255) NOT NULL,
  `nom_asociacion` varchar(100) DEFAULT NULL,
  `usuario_config` varchar(50) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `dir_asociacion` varchar(80) DEFAULT NULL,
  `tel_asociacion` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cuota`
--

CREATE TABLE `cuota` (
  `no_cuota` int(255) NOT NULL,
  `id_prestamo` int(255) DEFAULT NULL,
  `fecha_pago_cuota` date DEFAULT NULL,
  `monto_cuota` double(255,0) DEFAULT NULL,
  `mora_cuota` double(255,0) DEFAULT NULL,
  `estado_cuota` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `destino`
--

CREATE TABLE `destino` (
  `id_destino` int(255) NOT NULL,
  `nom_destino` varchar(80) DEFAULT NULL,
  `interes_destino` float(255,0) DEFAULT NULL,
  `desde_destino` date DEFAULT NULL,
  `hasta_destino` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(255) NOT NULL,
  `cod_socio` varchar(6) DEFAULT NULL,
  `id_destino` int(255) DEFAULT NULL,
  `monto_prestamo` double(255,0) DEFAULT NULL,
  `abono_capital_prestamo` double(255,0) DEFAULT NULL,
  `seguro_prestamo` double(255,0) DEFAULT NULL,
  `fecha_emision_prestamo` date DEFAULT NULL,
  `forma_pago_prestamo` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `socio`
--

CREATE TABLE `socio` (
  `cod_socio` varchar(6) NOT NULL,
  `nombre_socio` varchar(80) DEFAULT NULL,
  `apellido_socio` varchar(80) DEFAULT NULL,
  `tel_socio` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id_config`);

--
-- Indexes for table `cuota`
--
ALTER TABLE `cuota`
  ADD PRIMARY KEY (`no_cuota`),
  ADD KEY `fk1` (`id_prestamo`);

--
-- Indexes for table `destino`
--
ALTER TABLE `destino`
  ADD PRIMARY KEY (`id_destino`);

--
-- Indexes for table `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `fk2` (`cod_socio`),
  ADD KEY `fk3` (`id_destino`);

--
-- Indexes for table `socio`
--
ALTER TABLE `socio`
  ADD PRIMARY KEY (`cod_socio`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_config` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destino`
--
ALTER TABLE `destino`
  MODIFY `id_destino` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(255) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cuota`
--
ALTER TABLE `cuota`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamo` (`id_prestamo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `fk2` FOREIGN KEY (`cod_socio`) REFERENCES `socio` (`cod_socio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk3` FOREIGN KEY (`id_destino`) REFERENCES `destino` (`id_destino`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
