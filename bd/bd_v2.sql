-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-10-2024 a las 23:25:36
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_v2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nino`
--

CREATE TABLE `nino` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `carnet_identidad` text NOT NULL,
  `id_tutor` bigint(20) NOT NULL,
  `sexo` enum('niño','niña') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nino`
--

INSERT INTO `nino` (`id`, `nombre`, `apellido`, `fecha_nacimiento`, `carnet_identidad`, `id_tutor`, `sexo`) VALUES
(1, 'Ana', 'Pérez', '2015-06-15', 'CI123456', 1, 'niña'),
(2, 'Luis', 'González', '2016-08-20', 'CI654321', 2, 'niño'),
(3, 'Sofía', 'López', '2014-11-30', 'CI789012', 3, 'niña'),
(5, 'Camila', 'Torrico', '2024-10-01', '', 4, 'niña'),
(6, 'Gino', 'Cesto', '2024-10-02', '5218888', 5, 'niño'),
(7, 'jhon ', 'mons', '2024-07-02', '5168546516', 6, 'niño');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `nombre`, `apellido`) VALUES
(1, 'Laura', 'Martínez'),
(2, 'Pedro', 'Ramírez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutor`
--

CREATE TABLE `tutor` (
  `id` bigint(20) NOT NULL,
  `nombre` text NOT NULL,
  `apellidos` text NOT NULL,
  `carnet_identidad` text NOT NULL,
  `celular` text NOT NULL,
  `relacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tutor`
--

INSERT INTO `tutor` (`id`, `nombre`, `apellidos`, `carnet_identidad`, `celular`, `relacion`) VALUES
(1, 'Juan', 'Pérez', '12345678', '555-1234', 'padre'),
(2, 'María', 'González', '87654321', '555-5678', 'madre'),
(3, 'Carlos', 'López', '11223344', '555-8765', 'tutor'),
(4, 'sandra', 'peredo', '5218456', '76888888', 'Padre'),
(5, 'Jaime', 'Torrico', '5219999', '76999999', 'abuelo/abuela'),
(6, 'dante', 'mons', '654654796', '767854545', 'padre/madre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id` bigint(20) NOT NULL,
  `dosis` text NOT NULL,
  `fecha_vacunacion` date NOT NULL,
  `nino_id` bigint(20) NOT NULL,
  `tipo_id` bigint(20) NOT NULL,
  `personal_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacunas`
--

INSERT INTO `vacunas` (`id`, `dosis`, `fecha_vacunacion`, `nino_id`, `tipo_id`, `personal_id`) VALUES
(1, 'Primera', '2023-01-10', 1, 1, 1),
(2, 'Segunda', '2023-02-15', 2, 2, 2),
(3, 'Primera', '2023-03-20', 3, 3, 1),
(5, '1', '2024-10-10', 6, 1, 1),
(6, '2', '2024-10-10', 6, 1, 2),
(7, '1', '2024-10-02', 6, 3, 1),
(8, '2', '2024-10-12', 6, 3, 1),
(9, '3', '2024-10-11', 6, 3, 1),
(10, '4', '2024-10-18', 6, 3, 1),
(11, '5', '2024-10-24', 6, 3, 2),
(12, '6', '2024-10-10', 6, 3, 1),
(13, '1', '2024-10-01', 7, 3, 1),
(14, '2', '2024-10-16', 7, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacuna_tipo`
--

CREATE TABLE `vacuna_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` text NOT NULL,
  `dosis_requeridas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vacuna_tipo`
--

INSERT INTO `vacuna_tipo` (`id`, `tipo`, `dosis_requeridas`) VALUES
(1, 'MMR', 2),
(2, 'Polio', 3),
(3, 'Hepatitis B', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `nino`
--
ALTER TABLE `nino`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carnet_identidad` (`carnet_identidad`) USING HASH,
  ADD KEY `id_tutor` (`id_tutor`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carnet_identidad` (`carnet_identidad`) USING HASH;

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nino_id` (`nino_id`),
  ADD KEY `tipo_id` (`tipo_id`),
  ADD KEY `personal_id` (`personal_id`);

--
-- Indices de la tabla `vacuna_tipo`
--
ALTER TABLE `vacuna_tipo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo` (`tipo`) USING HASH;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `nino`
--
ALTER TABLE `nino`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tutor`
--
ALTER TABLE `tutor`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `vacuna_tipo`
--
ALTER TABLE `vacuna_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `nino`
--
ALTER TABLE `nino`
  ADD CONSTRAINT `nino_ibfk_1` FOREIGN KEY (`id_tutor`) REFERENCES `tutor` (`id`);

--
-- Filtros para la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD CONSTRAINT `vacunas_ibfk_1` FOREIGN KEY (`nino_id`) REFERENCES `nino` (`id`),
  ADD CONSTRAINT `vacunas_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `vacuna_tipo` (`id`),
  ADD CONSTRAINT `vacunas_ibfk_3` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
