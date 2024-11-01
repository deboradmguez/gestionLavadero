- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-08-2024 a las 23:53:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- !40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
-- !40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
-- !40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
-- !40101 SET NAMES utf8mb4 */;

-- Base de datos: `lavadero`

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `clientes`
CREATE TABLE `clientes` (
  `idcliennte` int(11) NOT NULL,
  `dni` int(8) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `clientes`
INSERT INTO `clientes` (`idcliennte`, `dni`, `nombre`, `apellido`, `telefono`) VALUES
(14, 45325673, 'billie p', 'eilish', '5565634435'),
(15, 43711408, 'cami', 'Domínguez', '01159674508'),
(16, 43711467, 'marcel', 'Domínguez', '01159674508'),
(17, 34564654, 'debo', 'Debora Domínguez', '01159674508'),
(18, 57847584, 'marcel', 'Debora Domínguez', '01159674508'),
(19, 43711445, 'debo', 'Debora Domínguez', '01159674508'),
(20, 34564732, 'debo', 'Debora Domínguez', '01159674508'),
(21, 43711423, 'eretr', 'Debora Domínguez', '01159674508'),
(26, 43711345, 'Débora Abigail ', 'Dominguez', '3704034213'),
(28, 43711432, 'Débora Abigail ', 'Dominguez', '3704034213'),
(29, 75847839, 'francia', 'paris', '840958409039');

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `empleados`
CREATE TABLE `empleados` (
  `dni` int(8) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telefono` int(15) NOT NULL,
  `horario` int(11) NOT NULL,
  `salario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `servicios`
CREATE TABLE `servicios` (
  `idservicio` int(11) NOT NULL,
  `servicio` varchar(100) NOT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `servicios`
INSERT INTO `servicios` (`idservicio`, `servicio`, `precio`, `tiempo`) VALUES
(2, 'lavado básico', 1030, 30),
(3, 'lavado completo', 1700, 60),
(4, 'Limpieza de Interior Básica', 1500, 30),
(5, 'Limpieza de Interior Completa', 2000, 60),
(6, 'Limpieza de Alfombras', 600, 16);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `turnos`
CREATE TABLE `turnos` (
  `idturno` int(11) NOT NULL,
  `idservicio` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL,
  `idvehiculo` varchar(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('Pendiente','En Proceso','Completado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombreusuario` varchar(6) NOT NULL,
  `contraseña` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `usuarios`
INSERT INTO `usuarios` (`idusuario`, `nombreusuario`, `contraseña`) VALUES
(4, 'admin', '6e1f3a0cf687d43cef2d7f37543d0e6d');

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `vehiculos`
CREATE TABLE `vehiculos` (
  `patente` varchar(7) NOT NULL,
  `modelo` text NOT NULL,
  `tipo` varchar(12) NOT NULL,
  `idcliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `vehiculos`
INSERT INTO `vehiculos` (`patente`, `modelo`, `tipo`, `idcliente`) VALUES
('456453a', 'bmw', 'Moto', 28),
('5675676', 'bmw', 'Auto', 26),
('6439485', 'fds', 'Moto', 29);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `ingresos`
CREATE TABLE `ingresos` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `monto` int(11) NOT NULL,
  PRIMARY KEY (`idingreso`),
  KEY `idcliente` (`idcliente`),
  CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliennte`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Índices para tablas volcadas

-- Índices de la tabla `clientes`
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcliennte`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `idcliennte` (`idcliennte`);

-- Índices de la tabla `empleados`
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `dni` (`dni`);

-- Índices de la tabla `servicios`
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idservicio`),
  ADD KEY `idservicio` (`idservicio`);

-- Índices de la tabla `turnos`
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`idturno`),
  ADD KEY `idservicio` (`idservicio`),
  ADD KEY `idvehiculo` (`idvehiculo`),
  ADD KEY `idturno` (`idturno`),
  ADD KEY `idcliente` (`idcliente`);

-- Indices de la tabla `usuarios`
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`);

-- Indices de la tabla `vehiculos`
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`patente`),
  ADD KEY `patente` (`patente`),
  ADD KEY `idcliente` (`idcliente`);

-- Indices de la tabla `ingresos`
ALTER TABLE `ingresos`
  ADD KEY `idcliente` (`idcliente`);

-- AUTO_INCREMENT de las tablas volcadas

-- AUTO_INCREMENT de la tabla `clientes`
ALTER TABLE `clientes`
  MODIFY `idcliennte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

-- AUTO_INCREMENT de la tabla `servicios`
ALTER TABLE `servicios`
  MODIFY `idservicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

-- AUTO_INCREMENT de la tabla `turnos`
ALTER TABLE `turnos`
  MODIFY `idturno` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT de la tabla `usuarios`
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- AUTO_INCREMENT de la tabla `ingresos`
ALTER TABLE `ingresos`
  MODIFY `idingreso` int(11) NOT NULL AUTO_INCREMENT;

-- Restricciones para tablas volcadas

-- Filtros para la tabla `turnos`
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`idservicio`) REFERENCES `servicios` (`idservicio`),
  ADD CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`idempleado`) REFERENCES `empleados` (`dni`),
  ADD CONSTRAINT `turnos_ibfk_3` FOREIGN KEY (`idvehiculo`) REFERENCES `vehiculos` (`patente`),
  ADD CONSTRAINT `turnos_ibfk_4` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliennte`);

-- Filtros para la tabla `vehiculos`
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliennte`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
