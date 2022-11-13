-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2022 a las 19:43:59
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `id_orden` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `mesa_calificacion` int(11) NOT NULL,
  `restaurante_calificacion` int(11) NOT NULL,
  `mozo_calificacion` int(11) NOT NULL,
  `cocinero_calificacion` int(11) NOT NULL,
  `comentario` varchar(66) COLLATE utf8_spanish2_ci NOT NULL,
  `promedio_calificacion` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo_mesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `id_personal` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo_mesa`, `id_personal`, `estado`) VALUES
(1, 'ME012', 1, 'con cliente esperando pedido'),
(2, 'ME002', 4, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) DEFAULT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Pendiente',
  `nombre_cliente` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `imagen` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `costo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `id_mesa`, `estado`, `nombre_cliente`, `imagen`, `costo`) VALUES
(1, 0, 'cancelada', 'cerrada', 'Orden_1.png', 22),
(2, 0, 'en preparacion', 'cerrada', 'Orden_2.png', 22),
(3, 0, 'cancelada', 'cerrada', 'Orden_3.png', 100.5),
(4, 0, 'Pendiente', 'cerrada', 'Orden_4.png', 22),
(5, 0, 'Pendiente', 'cerrada', 'Orden_5.png', 22),
(6, 0, 'Pendiente', 'cerrada', 'Orden_6.png', 22),
(7, 0, 'Pendiente', 'cerrada', 'Orden_7.png', 22),
(8, 0, 'Pendiente', 'cerrada', 'Orden_8.png', 22),
(9, 0, 'Pendiente', 'cerrada', 'Orden_9.png', 22),
(10, 0, 'Pendiente', 'cerrada', 'Orden_10.png', 22),
(11, 0, 'Pendiente', 'cerrada', 'Orden_11.png', 22),
(12, 0, 'Pendiente', 'cerrada', 'Orden_12.png', 22),
(13, 0, 'Pendiente', 'cerrada', 'Orden_13.png', 22),
(14, 0, 'Pendiente', 'cerrada', 'Orden_14.png', 22),
(15, 0, 'Pendiente', 'cerrada', 'Orden_15.png', 22),
(16, 0, 'Pendiente', 'cerrada', 'Orden_16.png', 22),
(17, 0, 'Pendiente', 'cerrada', 'Orden_17.png', 22),
(18, 0, 'Pendiente', 'cerrada', 'Orden_18.png', 22),
(19, 0, 'Pendiente', 'cerrada', 'Orden_19.png', 22),
(20, 0, 'Pendiente', 'manuel', 'Orden_20.png', 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `area` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `id_orden_asociada` int(11) NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` float NOT NULL,
  `tiempo_inicial` date NOT NULL DEFAULT current_timestamp(),
  `tiempo_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `area`, `id_orden_asociada`, `estado`, `descripcion`, `tipo`, `precio`, `tiempo_inicial`, `tiempo_entrega`) VALUES
(1, 'cocina', 15, 'retirado', 'Una buena cerveza', 'cerveza', 100.25, '2022-11-12', '2022-11-10'),
(2, 'cerveceria', 15, 'retirada', 'Una excelente cerveza', 'cerveza', 200, '2022-11-12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_login`
--

CREATE TABLE `registros_login` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_login` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `registros_login`
--

INSERT INTO `registros_login` (`id`, `id_usuario`, `nombre_usuario`, `fecha_login`) VALUES
(1, 3, 'socio2', '2022-11-11'),
(2, 4, 'cocinero1', '2022-11-12'),
(3, 4, 'cocinero1', '2022-11-12'),
(4, 3, 'socio2', '2022-11-12'),
(5, 3, 'socio2', '2022-11-12'),
(6, 3, 'socio2', '2022-11-12'),
(7, 3, 'socio2', '2022-11-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_code` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tables`
--

INSERT INTO `tables` (`id`, `table_code`, `employee_id`, `state`) VALUES
(2, 'ME002', 20, 'Con Cliente Esperando Pedido'),
(3, 'ME003', 12, 'Con Cliente Pagando'),
(4, 'ME004', 20, 'Cerrada'),
(5, 'ME005', NULL, 'Cerrada'),
(6, 'ME006', NULL, 'Cerrada'),
(8, 'ME008', NULL, 'Cerrada'),
(9, 'ME009', NULL, 'Cerrada'),
(10, 'ME010', NULL, 'Cerrada'),
(11, 'ME011', NULL, 'Cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `fecha_alta` date NOT NULL DEFAULT current_timestamp(),
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `clave`, `nombre`, `tipo`, `fecha_alta`, `fecha_baja`) VALUES
(3, 'socio2', '$2y$10$R7alFG9ZDXSsipK9YRSJvOaf11tqVlrjz3D.Mzm/8oJtcNkFQs47C', 'eduardo', 'administrador', '2022-11-11', NULL),
(4, 'eduardoModificado', '$2y$10$rHtjtvBWwUcTExg0j66FdeOsdGjQ/OmFgPIYoaaqfuRJcrD4lVJCy', 'modificado', 'administrador', '2022-11-10', '0000-00-00'),
(6, 'bartender1', '$2y$10$EBrcr9WtqHlhXl6dQey1ceBPw4xG5O4P/wHpSUrGHPUAyY3DJXKEa', 'dionisios', 'bartender', '2022-11-11', NULL),
(7, 'cervecero', '$2y$10$vZTTkQIiAGwJPIUExnkACuucxCeZlP4b47AEsndlg7aA.KT1dBXg2', 'cervero', 'cervecero', '2022-11-11', NULL),
(9, 'mozo1', '$2y$10$yMwV0GWZGDGs.1s42XTkZuN9soIbnLDbEsu7tBdeqIAhzAbXMJDKa', 'penelope', 'mozo', '2022-11-11', NULL),
(11, 'mozo1414', '$2y$10$odDTk.o3SI4my3c.mauBuuwbwBcz4w96o.Stj2m3a09j9slooG7Au', 'penelope', 'mozo454', '2022-11-11', NULL),
(12, 'qweqwerwe', '$2y$10$N4YkfFTVcmPLM3lIJn5HsOjQDBYfnVfQgI1pXxX.Pmlwj2ei0o3PC', 'penelope', 'mozo', '2022-11-11', NULL),
(15, 'cocinero2', '$2y$10$bfdjvqJ5fpZKI.w6YWREb.BJg0t9xC0BAyJ38huKcnpH.Nag6W.lq', 'adrian', 'cocinero', '2022-11-11', NULL),
(17, 'cocinero3', '$2y$10$kiB6Yxb/qPos/s6kCXz1JeDsLCPTTMcSqA/LMJyzRP87O5PVpYEbm', 'adriana', 'cocinero', '2022-11-12', NULL),
(18, 'cocinero4', '$2y$10$I3mVbn2/75nA4Jd3pVuUeuky1eFXVL5ovrZWDNIjMm60n8VSRZU4W', 'paula', 'cocinero', '2022-11-12', NULL),
(19, 'cocinero5', '$2y$10$xdD3qnA/MlaxoIu9mhVyKeMu3BCbkAouTd69UJ6RyVPs/fE/cPU46', 'paulina', 'cocinero', '2022-11-12', NULL),
(20, 'cocinero6', '$2y$10$KB6yV6/HzarOeP02frHFHeFupj4c2NMLBQ/4s1jQJaOUKAn3g1Jq.', 'paulina', 'cocinero', '2022-11-12', NULL),
(21, 'cocinero7', '$2y$10$ohOWjiW7a1DkkMt2sdEhNeQwAiwhX4FhcJ/UmCW9abfwLXOuwNI6W', 'paulina', 'cocinero', '2022-11-12', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_mesa` (`codigo_mesa`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_usuario_registrado` (`id_usuario`);

--
-- Indices de la tabla `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_code` (`table_code`),
  ADD KEY `FK_table_employee_id` (`employee_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `FK_id_personal` FOREIGN KEY (`id_personal`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `registros_login`
--
ALTER TABLE `registros_login`
  ADD CONSTRAINT `FK_usuario_registrado` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tables`
--
ALTER TABLE `tables`
  ADD CONSTRAINT `FK_table_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
