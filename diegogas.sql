-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-09-2024 a las 09:45:12
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
-- Base de datos: `diegogas`
--
CREATE DATABASE IF NOT EXISTS `diegogas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `diegogas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_empleados`
--

CREATE TABLE `asistencia_empleados` (
  `id_asistencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_asistencia` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL,
  `horas_trabajadas` decimal(5,2) GENERATED ALWAYS AS (timestampdiff(HOUR,`hora_entrada`,`hora_salida`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `asistencia_empleados`
--

INSERT INTO `asistencia_empleados` (`id_asistencia`, `id_empleado`, `fecha_asistencia`, `hora_entrada`, `hora_salida`) VALUES
(1, 1, '2024-09-22', '08:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'Balones de Gas'),
(2, 'Accesorios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `apellido_cliente` varchar(100) DEFAULT NULL,
  `telefono_cliente` varchar(15) DEFAULT NULL,
  `direccion_cliente` varchar(255) NOT NULL,
  `coordenadas_cliente` varchar(50) DEFAULT NULL,
  `fecha_registro_cliente` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `apellido_cliente`, `telefono_cliente`, `direccion_cliente`, `coordenadas_cliente`, `fecha_registro_cliente`) VALUES
(1, 'Sra. Julieta', '', '963258741', 'Jr. Independencia 353', '-11.994077, -77.052660', '2024-01-01'),
(2, 'I.E. 3050', 'Alberto Hurtado Abadía', '741852963', 'Av. Túpac Amaru Lote 3 Independencia', '-11.993884 -77.054400', '2024-09-02'),
(3, 'Badaracco', NULL, '963258741', 'Av. Cesar Vallejo 816, Independencia', '-11.991434, -77.051584', '2024-09-22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cobranzas`
--

CREATE TABLE `cobranzas` (
  `id_cobranza` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha_cobranza` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id_documento`, `tipo_documento`, `numero_documento`, `id_empleado`) VALUES
(1, 'DNI', '78945696', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre_empleado` varchar(100) NOT NULL,
  `apellido_empleado` varchar(100) DEFAULT NULL,
  `telefono_empleado` varchar(15) DEFAULT NULL,
  `fecha_contratacion_empleado` date NOT NULL,
  `salario_empleado` decimal(10,2) NOT NULL,
  `nacionalidad_empleado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre_empleado`, `apellido_empleado`, `telefono_empleado`, `fecha_contratacion_empleado`, `salario_empleado`, `nacionalidad_empleado`) VALUES
(1, 'Misael', 'López', '987654321', '2023-01-01', 1500.00, 'Peruano'),
(2, 'Diego', 'Ramírez', '912345678', '2023-02-15', 1800.00, 'Peruano'),
(3, 'Walter', 'García', '998877665', '2023-03-10', 2000.00, 'Peruano'),
(4, 'Alejandro', 'Fernández', '987123456', '2023-04-05', 2200.00, 'Peruano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_roles`
--

CREATE TABLE `empleados_roles` (
  `id_empleado` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empleados_roles`
--

INSERT INTO `empleados_roles` (`id_empleado`, `id_rol`) VALUES
(1, 3),
(2, 1),
(2, 3),
(3, 1),
(4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_ventas`
--

CREATE TABLE `estado_ventas` (
  `id_estado` int(11) NOT NULL,
  `descripcion_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estado_ventas`
--

INSERT INTO `estado_ventas` (`id_estado`, `descripcion_estado`) VALUES
(1, 'pendiente'),
(2, 'en_camino'),
(3, 'entregado'),
(4, 'cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_diarios`
--

CREATE TABLE `gastos_diarios` (
  `id_gasto` int(11) NOT NULL,
  `descripcion_gasto` varchar(255) NOT NULL,
  `monto_gasto` decimal(10,2) NOT NULL,
  `fecha_gasto` date NOT NULL,
  `tipo_gasto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `gastos_diarios`
--

INSERT INTO `gastos_diarios` (`id_gasto`, `descripcion_gasto`, `monto_gasto`, `fecha_gasto`, `tipo_gasto`) VALUES
(1, 'Pago de 26 días a empleado', 884.00, '2024-09-22', 'Pago a empleados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_empleados`
--

CREATE TABLE `pagos_empleados` (
  `id_pago` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `horas_trabajadas` decimal(5,2) NOT NULL,
  `monto_pago` decimal(10,2) GENERATED ALWAYS AS (case when `horas_trabajadas` = 8 then 34 when `horas_trabajadas` = 16 then 70 else `horas_trabajadas` * 4.25 end) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pagos_empleados`
--

INSERT INTO `pagos_empleados` (`id_pago`, `id_empleado`, `fecha_pago`, `horas_trabajadas`) VALUES
(1, 1, '2024-09-22', 8.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_empleados_acumulados`
--

CREATE TABLE `pagos_empleados_acumulados` (
  `id_acumulado` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `dias_trabajados` int(11) NOT NULL,
  `monto_total_acumulado` decimal(10,2) NOT NULL,
  `estado_pago` varchar(50) DEFAULT 'Pendiente',
  `fecha_acumulado_inicio` date NOT NULL,
  `fecha_acumulado_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pagos_empleados_acumulados`
--

INSERT INTO `pagos_empleados_acumulados` (`id_acumulado`, `id_empleado`, `dias_trabajados`, `monto_total_acumulado`, `estado_pago`, `fecha_acumulado_inicio`, `fecha_acumulado_fin`) VALUES
(1, 1, 26, 884.00, 'Pagado', '2024-09-01', '2024-09-22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `marca_producto` varchar(100) NOT NULL,
  `precio_compra_producto` decimal(10,2) NOT NULL,
  `precio_venta_producto` decimal(10,2) NOT NULL,
  `existencias_producto` int(11) NOT NULL,
  `id_subcategoria` int(11) NOT NULL,
  `fecha_compra_producto` date NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `marca_producto`, `precio_compra_producto`, `precio_venta_producto`, `existencias_producto`, `id_subcategoria`, `fecha_compra_producto`, `id_proveedor`) VALUES
(1, 'Costagas', 37.00, 40.00, 10, 2, '2024-09-22', 1),
(2, 'Llamagas', 36.00, 42.00, 10, 2, '2024-09-22', 4),
(3, 'Multigas', 30.00, 40.00, 15, 2, '2024-09-22', 2),
(4, 'Multigas', 20.00, 28.00, 8, 1, '2024-09-22', 2),
(5, 'Multigas', 65.00, 79.00, 6, 3, '2024-09-22', 2),
(6, 'Multigas', 150.00, 200.00, 5, 4, '2024-09-22', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(100) NOT NULL,
  `telefono_proveedor` varchar(15) DEFAULT NULL,
  `direccion_proveedor` varchar(255) DEFAULT NULL,
  `email_proveedor` varchar(100) DEFAULT NULL,
  `contacto_proveedor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_proveedor`, `telefono_proveedor`, `direccion_proveedor`, `email_proveedor`, `contacto_proveedor`) VALUES
(1, 'Costagas', NULL, NULL, NULL, NULL),
(2, 'Multigas', NULL, NULL, NULL, 'Elmer'),
(3, 'Solgas', NULL, NULL, NULL, 'Elmer'),
(4, 'Llamagas', NULL, NULL, NULL, 'Elmer'),
(5, 'Maquigas', NULL, NULL, NULL, 'Elmer'),
(6, 'Alfagas', NULL, NULL, NULL, 'Elmer'),
(7, 'Alfagas', NULL, NULL, NULL, 'Edwin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reabastecimientos`
--

CREATE TABLE `reabastecimientos` (
  `id_reabastecimiento` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_balones` int(11) NOT NULL,
  `marca_balones` varchar(100) NOT NULL,
  `peso_balones` decimal(5,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total_a_pagar` decimal(10,2) NOT NULL,
  `fecha_reabastecimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Motorizado'),
(3, 'Recepcionista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategorias`
--

CREATE TABLE `subcategorias` (
  `id_subcategoria` int(11) NOT NULL,
  `nombre_subcategoria` varchar(100) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`id_subcategoria`, `nombre_subcategoria`, `id_categoria`) VALUES
(1, 'Balón de Gas 5kg', 1),
(2, 'Balón de Gas 10kg', 1),
(3, 'Balón de Gas 15kg', 1),
(4, 'Balón de Gas 45kg', 1),
(5, 'Mangueras', 2),
(6, 'Reguladores', 2),
(7, 'Conectores', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_pago`
--

CREATE TABLE `tipos_pago` (
  `id_pago` int(11) NOT NULL,
  `descripcion_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_despacho`
--

CREATE TABLE `tipo_despacho` (
  `id_tipo_despacho` int(11) NOT NULL,
  `descripcion_despacho` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_despacho`
--

INSERT INTO `tipo_despacho` (`id_tipo_despacho`, `descripcion_despacho`) VALUES
(1, 'Entrega a domicilio'),
(2, 'Retiro en tienda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `password_usuario` varchar(255) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `fecha_creacion_usuario` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `password_usuario`, `id_empleado`, `id_rol`, `fecha_creacion_usuario`) VALUES
(1, 'dan41', '$2y$10$zsRqNQ.BVTbyCZbG1axUaebWjr2vKwuDnUQ2vV07AbImv4pbsVD9i', 2, 1, '2024-09-23'),
(2, 'diego41', '$2y$10$hZaDIXuPAvV4/OT1o28L..hbpbcaH/UmibP6EBOipdQ3qXvIh7/o2', 2, 3, '2024-09-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_tipo_despacho` int(11) NOT NULL,
  `cantidad_producto` int(11) NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `fecha_venta` date NOT NULL,
  `estado_venta` varchar(50) NOT NULL DEFAULT 'pendiente',
  `motivo_cancelacion` text DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `id_pago` int(11) DEFAULT 1,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia_empleados`
--
ALTER TABLE `asistencia_empleados`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cobranzas`
--
ALTER TABLE `cobranzas`
  ADD PRIMARY KEY (`id_cobranza`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `empleados_roles`
--
ALTER TABLE `empleados_roles`
  ADD PRIMARY KEY (`id_empleado`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `estado_ventas`
--
ALTER TABLE `estado_ventas`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `gastos_diarios`
--
ALTER TABLE `gastos_diarios`
  ADD PRIMARY KEY (`id_gasto`);

--
-- Indices de la tabla `pagos_empleados`
--
ALTER TABLE `pagos_empleados`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `pagos_empleados_acumulados`
--
ALTER TABLE `pagos_empleados_acumulados`
  ADD PRIMARY KEY (`id_acumulado`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_subcategoria` (`id_subcategoria`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `reabastecimientos`
--
ALTER TABLE `reabastecimientos`
  ADD PRIMARY KEY (`id_reabastecimiento`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD PRIMARY KEY (`id_subcategoria`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `tipos_pago`
--
ALTER TABLE `tipos_pago`
  ADD PRIMARY KEY (`id_pago`);

--
-- Indices de la tabla `tipo_despacho`
--
ALTER TABLE `tipo_despacho`
  ADD PRIMARY KEY (`id_tipo_despacho`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_tipo_despacho` (`id_tipo_despacho`),
  ADD KEY `fk_estado_venta` (`id_estado`),
  ADD KEY `ventas_ibfk_6` (`id_pago`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia_empleados`
--
ALTER TABLE `asistencia_empleados`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cobranzas`
--
ALTER TABLE `cobranzas`
  MODIFY `id_cobranza` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `gastos_diarios`
--
ALTER TABLE `gastos_diarios`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pagos_empleados`
--
ALTER TABLE `pagos_empleados`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pagos_empleados_acumulados`
--
ALTER TABLE `pagos_empleados_acumulados`
  MODIFY `id_acumulado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reabastecimientos`
--
ALTER TABLE `reabastecimientos`
  MODIFY `id_reabastecimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  MODIFY `id_subcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_pago`
--
ALTER TABLE `tipos_pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_despacho`
--
ALTER TABLE `tipo_despacho`
  MODIFY `id_tipo_despacho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia_empleados`
--
ALTER TABLE `asistencia_empleados`
  ADD CONSTRAINT `asistencia_empleados_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `cobranzas`
--
ALTER TABLE `cobranzas`
  ADD CONSTRAINT `cobranzas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `empleados_roles`
--
ALTER TABLE `empleados_roles`
  ADD CONSTRAINT `empleados_roles_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `empleados_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `pagos_empleados`
--
ALTER TABLE `pagos_empleados`
  ADD CONSTRAINT `pagos_empleados_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `pagos_empleados_acumulados`
--
ALTER TABLE `pagos_empleados_acumulados`
  ADD CONSTRAINT `pagos_empleados_acumulados_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_subcategoria`) REFERENCES `subcategorias` (`id_subcategoria`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `reabastecimientos`
--
ALTER TABLE `reabastecimientos`
  ADD CONSTRAINT `reabastecimientos_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`),
  ADD CONSTRAINT `reabastecimientos_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD CONSTRAINT `subcategorias_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_estado_venta` FOREIGN KEY (`id_estado`) REFERENCES `estado_ventas` (`id_estado`),
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `ventas_ibfk_4` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `ventas_ibfk_5` FOREIGN KEY (`id_tipo_despacho`) REFERENCES `tipo_despacho` (`id_tipo_despacho`),
  ADD CONSTRAINT `ventas_ibfk_6` FOREIGN KEY (`id_pago`) REFERENCES `tipos_pago` (`id_pago`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
