-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2018 a las 14:11:13
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `events`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(10) NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `lugar` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `precio` int(5) DEFAULT NULL,
  `descripcion` varchar(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_usuario` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `nombre`, `fecha`, `lugar`, `precio`, `descripcion`, `id_usuario`) VALUES
(1, 'dfhftgjuh', '2018-05-06', 'fdgdf', 12, 'rydfyret', 1),
(2, 'sfsdgfdg', '2018-05-05', 'fgdfgf', 0, 'dfgdf', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen`
--

CREATE TABLE `imagen` (
  `id_evento` int(10) NOT NULL,
  `ruta` varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `imagen`
--

INSERT INTO `imagen` (`id_evento`, `ruta`) VALUES
(1, 'C:\\xampp\\htdocs\\PracticaABD\\includes\\fotos-eventos\\1.jpg'),
(2, 'C:\\xampp\\htdocs\\PracticaABD\\includes\\fotos-eventos\\2.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(10) NOT NULL,
  `nombre` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) NOT NULL,
  `nombre` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `apellidos` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(70) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_rol` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellidos`, `password`, `fecha`, `email`, `id_rol`) VALUES
(1, 'User', 'U.', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi', '2018-04-10 00:00:00', 'user@example.org', 1),
(2, 'Admin', 'A.', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi', '2018-04-10 00:00:00', 'admin@example.org', 2),
(3, 'pepe', 'garcia', '$2y$10$FfWZnLz5i/nTP1qqF.CVkeU.gBFc9Cc6hEOoi5dtGmE/J79SZUjgO', '2018-05-06 09:18:23', 'p@p.com', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD KEY `id_evento` (`id_evento`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `imagen`
--
ALTER TABLE `imagen`
  ADD CONSTRAINT `imagen_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id_evento`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
