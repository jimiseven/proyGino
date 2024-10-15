-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-10-2024 a las 03:27:59
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bc_vac`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id` bigint(20) NOT NULL,
  `tipo` enum('A','B','C') NOT NULL,
  `dosis` int(11) NOT NULL CHECK (`dosis` > 0),
  `fecha_vacunacion` date NOT NULL,
  `nino_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `vacunas`
--

INSERT INTO `vacunas` (`id`, `tipo`, `dosis`, `fecha_vacunacion`, `nino_id`) VALUES
(1, 'A', 1, '2020-02-15', 1),
(2, 'A', 2, '2020-03-15', 1),
(3, 'A', 3, '2020-04-15', 1),
(4, 'B', 1, '2020-05-15', 1),
(5, 'B', 2, '2020-06-15', 1),
(6, 'C', 1, '2020-07-15', 1),
(7, 'C', 2, '2020-08-15', 1),
(8, 'C', 3, '2020-09-15', 1),
(9, 'C', 4, '2020-10-15', 1),
(10, 'A', 1, '2021-06-22', 2),
(11, 'A', 2, '2021-07-22', 2),
(12, 'B', 1, '2021-08-22', 2),
(13, 'A', 1, '2024-10-13', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nino_id` (`nino_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD CONSTRAINT `vacunas_ibfk_1` FOREIGN KEY (`nino_id`) REFERENCES `ninos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
