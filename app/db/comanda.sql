-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2022 a las 19:32:45
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
  `prefix` varchar(2) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'MS',
  `id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `id_personal` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`prefix`, `id`, `id_personal`, `estado`) VALUES
('MS', 001, 9, 'con cliente esperando pedido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `prefix` varchar(2) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'SD',
  `id` int(3) UNSIGNED ZEROFILL NOT NULL,
  `id_mesa` varchar(5) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Pendiente',
  `nombre_cliente` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `imagen` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `costo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`prefix`, `id`, `id_mesa`, `estado`, `nombre_cliente`, `imagen`, `costo`) VALUES
('SD', 003, 'MS001', 'pendiente', 'Maria', 'Orden_003.png', 730),
('SD', 004, 'MS001', 'pendiente', 'Maria', NULL, 730),
('SD', 005, 'MS001', 'pendiente', 'Maria', NULL, 730),
('SD', 006, 'MS001', 'pendiente', 'Maria', NULL, 730),
('SD', 007, 'MS001', 'pendiente', 'Maria', NULL, 730),
('SD', 008, 'MS001', 'pendiente', 'Maria', NULL, 730),
('SD', 009, 'MS001', 'pendiente', 'Maria', NULL, 730);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `area` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `id_orden_asociada` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio` float NOT NULL,
  `tiempo_inicial` timestamp NOT NULL DEFAULT current_timestamp(),
  `tiempo_entrega` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `area`, `id_orden_asociada`, `estado`, `descripcion`, `tipo`, `precio`, `tiempo_inicial`, `tiempo_entrega`) VALUES
(33, 'cocina', 'SD003', 'en preparacion', 'millanesa de caballo', 'cocinero', 200, '2022-11-18 03:00:00', '2022-11-19 18:49:53'),
(34, 'cocina', 'SD003', 'en preparacion', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-18 03:00:00', '2022-11-19 18:49:53'),
(35, 'cocina', 'SD003', 'en preparacion', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-18 03:00:00', '2022-11-19 18:49:53'),
(36, 'Barra de choperas', 'SD003', 'Listo para servir', 'corona', 'cervecero', 50, '2022-11-18 03:00:00', '2022-11-19 18:50:06'),
(37, 'Barra de tragos', 'SD003', 'en preparacion', 'daikiri', 'bartender', 80, '2022-11-18 03:00:00', '2022-11-19 18:50:00'),
(38, 'cocina', 'SD004', 'pendiente', 'millanesa de caballo', 'cocinero', 200, '2022-11-19 18:13:40', NULL),
(39, 'cocina', 'SD004', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:13:40', NULL),
(40, 'cocina', 'SD004', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:13:40', NULL),
(41, 'Barra de choperas', 'SD004', 'pendiente', 'corona', 'cervecero', 50, '2022-11-19 18:13:40', NULL),
(42, 'Barra de tragos', 'SD004', 'pendiente', 'daikiri', 'bartender', 80, '2022-11-19 18:13:40', NULL),
(43, 'cocina', 'SD005', 'pendiente', 'millanesa de caballo', 'cocinero', 200, '2022-11-19 18:14:38', NULL),
(44, 'cocina', 'SD005', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:14:38', NULL),
(45, 'cocina', 'SD005', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:14:38', NULL),
(46, 'Barra de choperas', 'SD005', 'pendiente', 'corona', 'cervecero', 50, '2022-11-19 18:14:38', NULL),
(47, 'Barra de tragos', 'SD005', 'pendiente', 'daikiri', 'bartender', 80, '2022-11-19 18:14:38', NULL),
(48, 'cocina', 'SD006', 'pendiente', 'millanesa de caballo', 'cocinero', 200, '2022-11-19 18:18:01', NULL),
(49, 'cocina', 'SD006', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:18:01', NULL),
(50, 'cocina', 'SD006', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:18:01', NULL),
(51, 'Barra de choperas', 'SD006', 'pendiente', 'corona', 'cervecero', 50, '2022-11-19 18:18:01', NULL),
(52, 'Barra de tragos', 'SD006', 'pendiente', 'daikiri', 'bartender', 80, '2022-11-19 18:18:01', NULL),
(53, 'cocina', 'SD007', 'pendiente', 'millanesa de caballo', 'cocinero', 200, '2022-11-19 18:18:42', NULL),
(54, 'cocina', 'SD007', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:18:42', NULL),
(55, 'cocina', 'SD007', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:18:42', NULL),
(56, 'Barra de choperas', 'SD007', 'pendiente', 'corona', 'cervecero', 50, '2022-11-19 18:18:42', NULL),
(57, 'Barra de tragos', 'SD007', 'pendiente', 'daikiri', 'bartender', 80, '2022-11-19 18:18:42', NULL),
(58, 'cocina', 'SD008', 'pendiente', 'millanesa de caballo', 'cocinero', 200, '2022-11-19 18:19:57', NULL),
(59, 'cocina', 'SD008', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:19:57', NULL),
(60, 'cocina', 'SD008', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:19:57', NULL),
(61, 'Barra de choperas', 'SD008', 'pendiente', 'corona', 'cervecero', 50, '2022-11-19 18:19:57', NULL),
(62, 'Barra de tragos', 'SD008', 'pendiente', 'daikiri', 'bartender', 80, '2022-11-19 18:19:57', NULL),
(63, 'cocina', 'SD009', 'pendiente', 'millanesa de caballo', 'cocinero', 200, '2022-11-19 18:23:46', NULL),
(64, 'cocina', 'SD009', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:23:46', NULL),
(65, 'cocina', 'SD009', 'pendiente', 'hamburguesa de garbanzo', 'cocinero', 200, '2022-11-19 18:23:46', NULL),
(66, 'Barra de choperas', 'SD009', 'pendiente', 'corona', 'cervecero', 50, '2022-11-19 18:23:46', NULL),
(67, 'Barra de tragos', 'SD009', 'pendiente', 'daikiri', 'bartender', 80, '2022-11-19 18:23:46', NULL);

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
(7, 3, 'socio2', '2022-11-13'),
(8, 3, 'socio2', '2022-11-18'),
(9, 9, 'mozo1', '2022-11-18'),
(10, 9, 'mozo1', '2022-11-18'),
(11, 9, 'mozo1', '2022-11-18'),
(12, 3, 'socio2', '2022-11-19'),
(13, 9, 'mozo1', '2022-11-19'),
(14, 9, 'mozo1', '2022-11-19'),
(15, 3, 'socio2', '2022-11-19'),
(16, 7, 'cervecero', '2022-11-19'),
(17, 15, 'cocinero2', '2022-11-19'),
(18, 6, 'bartender1', '2022-11-19'),
(19, 3, 'socio2', '2022-11-19'),
(20, 9, 'mozo1', '2022-11-19'),
(21, 9, 'mozo1', '2022-11-19'),
(22, 3, 'socio2', '2022-11-19'),
(23, 9, 'mozo1', '2022-11-19');

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
  ADD UNIQUE KEY `prefix` (`prefix`,`id`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prefix` (`prefix`,`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
