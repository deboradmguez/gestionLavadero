-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-05-2024 a las 01:23:21
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lavadero`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idcliennte` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `dni` int(8) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` int(15) NOT NULL,
  `horario` int(11) NOT NULL,
  `salario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `idservicio` int(11) NOT NULL,
  `servicio` text NOT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `idturno` int(11) NOT NULL,
  `idservicio` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL,
  `idvehiculo` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `patente` varchar(7) NOT NULL,
  `modelo` text NOT NULL,
  `idcliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcliennte`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `dni` (`dni`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idservicio`),
  ADD KEY `idservicio` (`idservicio`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`idturno`),
  ADD KEY `idservicio` (`idservicio`),
  ADD KEY `idempleado` (`idempleado`),
  ADD KEY `idvehiculo` (`idvehiculo`),
  ADD KEY `idturno` (`idturno`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`patente`),
  ADD KEY `patente` (`patente`),
  ADD KEY `idcliente` (`idcliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcliennte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idservicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `idturno` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`idservicio`) REFERENCES `servicios` (`idservicio`),
  ADD CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`idempleado`) REFERENCES `empleados` (`dni`),
  ADD CONSTRAINT `turnos_ibfk_3` FOREIGN KEY (`idvehiculo`) REFERENCES `vehiculos` (`patente`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliennte`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
