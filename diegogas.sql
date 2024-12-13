-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-12-2024 a las 00:46:14
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
CREATE DATABASE IF NOT EXISTS `diegogas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `diegogas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletas`
--

CREATE TABLE `boletas` (
  `id_boleta` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `numero_boleta` varchar(20) NOT NULL,
  `fecha_boleta` datetime NOT NULL,
  `total_boleta` decimal(10,2) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `boletas`
--

INSERT INTO `boletas` (`id_boleta`, `id_pedido`, `numero_boleta`, `fecha_boleta`, `total_boleta`, `id_cliente`, `id_empleado`) VALUES
(1, 13, 'BOL-20241213210957-4', '2024-12-13 21:09:57', 55.00, 23, 2),
(2, 14, 'BOL-20241213211853-1', '2024-12-13 21:18:53', 90.00, 1, 2),
(3, 16, 'BOL-20241213214419-2', '2024-12-13 21:44:19', 170.00, 1, 4),
(4, 19, 'BOL-20241213235508-3', '2024-12-13 23:55:08', 55.00, 2, 4),
(5, 20, 'BOL-20241214001831-2', '2024-12-14 00:18:31', 60.00, 1, 2),
(6, 21, 'BOL-20241214003801-6', '2024-12-14 00:38:01', 55.00, 23, 4),
(7, 22, 'BOL-20241214004225-8', '2024-12-14 00:42:25', 55.00, 23, 4),
(8, 23, 'BOL-20241214004416-5', '2024-12-14 00:44:16', 42.00, 44, 4);

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
  `fecha_registro_cliente` date DEFAULT NULL,
  `email_cliente` varchar(100) DEFAULT NULL,
  `descripcion_cliente` varchar(255) DEFAULT NULL,
  `dni_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre_cliente`, `apellido_cliente`, `fecha_registro_cliente`, `email_cliente`, `descripcion_cliente`, `dni_cliente`) VALUES
(1, 'Juliete', 'Pérez', '2024-01-01', 'juliete.perez@example.com', 'Cliente frecuente', 71526049),
(2, 'Alberto', 'Hurtado', '2024-09-02', 'alberto.hurtado@example.com', 'Cliente nuevo', 12345678),
(3, 'Carlos', 'Lopez', '2024-09-22', 'carlos.lopez@example.com', 'Cliente regular', 23456789),
(22, 'Dan', 'Martinez', '2024-11-04', 'dan.martinez@example.com', 'Cliente curado', 98765432),
(23, 'Diego', 'Gomez', '2024-11-04', 'diego.gomez@example.com', 'Prueba', 87654321),
(24, 'Milton', 'Ramirez', '2024-11-04', 'milton.ramirez@example.com', 'Solo lleva Solgas', 76543210),
(25, 'Arnaldo', 'Infante Grados', '2024-11-04', 'arnaldo.infante@example.com', '', 65432109),
(26, 'Pepito', 'Perez', '2024-11-07', 'pepito.perez@example.com', 'Cliente ocasional', 54321098),
(29, 'Julieta', 'Wotot', '2024-11-08', 'julieta.wotot@example.com', 'Sin descripción', 71526042),
(30, 'Juanito', 'El Molientero', '2024-11-08', 'juanito.molientero@example.com', '', 324332432),
(32, 'Diego', 'Prueba', '2024-11-11', 'diego.prueba@example.com', 'Sin descripción', 789654123),
(34, 'Lolita', 'Pérez', '2024-12-11', 'lolita.perez@example.com', '', 963225425),
(38, 'Luciana', 'Gomez', '2024-12-12', 'luciana.gomez@example.com', '', 77862144),
(39, 'Julian', 'Doe', '2024-12-12', 'julian.doe@example.com', '', 71526043),
(40, 'Jose', 'Quesada', '2024-12-12', 'jose.quesada@example.com', '', 2147483647),
(41, 'Irene', 'Perlado', '2024-12-12', 'irene.perlado@example.com', '', 78965412),
(42, 'Joaquin', 'Oneto', '2024-12-12', 'joaquin.oneto@example.com', '', 65189496),
(44, 'Julio', 'Torres', '2024-12-12', 'julio.torres@example.com', '', 96156454),
(45, 'Martina', 'Juanjo', '2024-12-12', 'martina.juanjo@example.com', '', 84986161),
(48, 'Pepito', 'Infante Grados', '2024-12-12', 'pepito.infante@example.com', '', 9865),
(49, 'Costa', 'Lloll', '2024-12-12', 'costa.lloll@example.com', '', 71526041),
(50, 'Prueba', 'Uno', '2024-12-12', 'prueba.uno@example.com', '', 23312321),
(51, 'Prueba', 'Dos', '2024-12-12', 'prueba.dos@example.com', '', 986153261),
(56, 'Prueba', 'Cuatro', '2024-12-12', 'prueba.cuatro@example.com', '', 125896347),
(59, 'Prueba', 'Cinco', '2024-12-12', 'prueba.cinco@example.com', '', 96312),
(60, 'Prueba', 'Seis', '2024-12-12', 'prueba.seis@example.com', '', 1),
(61, 'Prueba', 'Direccion', '2024-12-12', 'prueba.direccion@example.com', '', 720),
(62, 'Prueba', 'Numero', '2024-12-12', 'prueba.numero@example.com', '', 721),
(63, 'Prueba', 'Correo', '2024-12-12', 'prueba.correo@example.com', '', 722),
(64, 'Prueba', 'CorreoDOS', '2024-12-12', 'prueba.correodos@example.com', '', 723),
(65, 'Prueba', 'Ocho', '2024-12-12', 'prueba.ocho@example.com', '', 724),
(66, 'Prueba', 'Test', '2024-12-12', 'prueba.test@example.com', '', 725),
(67, 'Prueba', 'Ejemplo', '2024-12-12', 'prueba.ejemplo@example.com', '', 726),
(68, 'Prueba', 'Prueba', '2024-12-12', 'prueba.prueba@example.com', '', 727),
(69, 'Julieta', 'Marquez', '2024-12-13', 'julieta.marquez@example.com', '', 728);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_reabastecimiento`
--

CREATE TABLE `detalles_reabastecimiento` (
  `id_detalle_reabastecimiento` int(11) NOT NULL,
  `id_reabastecimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalles_reabastecimiento`
--

INSERT INTO `detalles_reabastecimiento` (`id_detalle_reabastecimiento`, `id_reabastecimiento`, `id_producto`, `cantidad`, `precio_compra`, `subtotal`) VALUES
(1, 1, 1, 20, 37.00, 740.00),
(2, 1, 4, 10, 10.00, 100.00),
(3, 3, 2, 16, 36.00, 576.00),
(4, 3, 2, 19, 36.00, 684.00),
(5, 4, 2, 16, 36.00, 576.00),
(6, 4, 2, 19, 36.00, 684.00),
(7, 5, 2, 16, 36.00, 576.00),
(8, 5, 2, 19, 36.00, 684.00),
(9, 6, 1, 7, 37.00, 259.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle_pedido`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(2, 1, 31, 2, 55.00, 110.00),
(3, 2, 31, 3, 10.00, 30.00),
(4, 3, 31, 1, 55.00, 55.00),
(5, 4, 31, 1, 55.00, 55.00),
(6, 5, 35, 1, 60.00, 60.00),
(7, 6, 6, 1, 45.00, 45.00),
(8, 7, 31, 1, 55.00, 55.00),
(9, 8, 31, 2, 55.00, 110.00),
(10, 9, 35, 1, 60.00, 60.00),
(11, 10, 2, 1, 42.00, 42.00),
(12, 11, 35, 1, 60.00, 60.00),
(13, 12, 31, 1, 55.00, 55.00),
(14, 13, 31, 1, 55.00, 55.00),
(15, 14, 28, 1, 90.00, 90.00),
(16, 15, 31, 2, 55.00, 110.00),
(17, 15, 35, 1, 60.00, 60.00),
(18, 15, 29, 1, 120.00, 120.00),
(19, 16, 31, 2, 55.00, 110.00),
(20, 16, 35, 1, 60.00, 60.00),
(21, 17, 31, 2, 55.00, 110.00),
(22, 18, 31, 1, 55.00, 55.00),
(23, 19, 31, 1, 55.00, 55.00),
(24, 20, 35, 1, 60.00, 60.00),
(25, 21, 31, 1, 55.00, 55.00),
(26, 22, 31, 1, 55.00, 55.00),
(27, 23, 2, 1, 42.00, 42.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_cliente`
--

CREATE TABLE `direcciones_cliente` (
  `id_direccion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `coordenadas` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `es_principal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `direcciones_cliente`
--

INSERT INTO `direcciones_cliente` (`id_direccion`, `id_cliente`, `direccion`, `coordenadas`, `descripcion`, `es_principal`) VALUES
(1, 1, 'Av. Principal 123, Ciudad', '-11.992263, -77.059620', 'Cerca al mercado central, puerta azul', 1),
(2, 1, 'Calle Secundaria 45, Ciudad', '-11.992511, -77.059870', 'Frente al parque principal', 0),
(3, 2, 'Av. Central 678, Ciudad', '-12.002263, -77.064620', 'Segundo piso de una casa con rejas verdes', 1),
(4, 2, 'Calle Lateral 90, Ciudad', '-12.003511, -77.061870', 'Al lado de la bodega San Juan', 0),
(5, 3, 'Av. Alameda 101, Ciudad', '-11.994510, -77.058743', 'En una esquina, casa amarilla', 1),
(6, 3, 'Calle Peatonal 202, Ciudad', '-11.997856, -77.055231', 'Frente al colegio María Auxiliadora', 0),
(7, 22, 'Av. El Bosque 303, Ciudad', '-11.995321, -77.062114', 'Cerca al parque Industrial', 1),
(8, 22, 'Calle del Sol 404, Ciudad', '-11.996874, -77.061547', 'Tercer piso de un edificio azul', 0),
(9, 23, 'Av. Los Pinos 505, Ciudad', '-11.998263, -77.059621', 'Casa con techo rojo al fondo de la cuadra', 1),
(10, 23, 'Calle del Río 606, Ciudad', '-11.997784, -77.058333', 'A una cuadra de la Av. Túpac Amaru', 0),
(11, 24, 'Av. La Esperanza 707, Ciudad', '-11.999123, -77.060587', 'Frente al grifo San Pedro', 1),
(12, 24, 'Calle de la Luna 808, Ciudad', '-11.993741, -77.058420', 'Cerca del puente peatonal', 0),
(13, 25, 'Av. Los Álamos 909, Ciudad', '-12.001411, -77.063123', 'A una cuadra del parque zonal', 1),
(14, 25, 'Calle del Valle 1001, Ciudad', '-12.002987, -77.064876', 'En la esquina frente a la farmacia', 0),
(15, 26, 'Av. Los Laureles 1102, Ciudad', '-12.003145, -77.060112', 'Cerca de la iglesia central', 1),
(16, 26, 'Calle del Parque 1203, Ciudad', '-12.003976, -77.061870', 'Tercer piso de un edificio blanco', 0),
(17, 29, 'Av. Principal 1304, Ciudad', '-11.991753, -77.059630', 'A la espalda del mercado Independencia', 1),
(18, 29, 'Calle de la Colina 1405, Ciudad', '-11.994112, -77.057341', 'Cerca del paradero inicial de combis', 0),
(19, 30, 'Av. Norte 1506, Ciudad', NULL, NULL, 1),
(20, 30, 'Calle del Sur 1607, Ciudad', NULL, NULL, 0),
(21, 32, 'Av. Centro 1708, Ciudad', NULL, NULL, 1),
(22, 32, 'Calle del Este 1809, Ciudad', NULL, NULL, 0),
(23, 34, 'Av. del Mar 1901, Ciudad', NULL, NULL, 1),
(24, 34, 'Calle de la Playa 2002, Ciudad', NULL, NULL, 0),
(25, 38, 'Av. Los Andes 2103, Ciudad', NULL, NULL, 1),
(26, 38, 'Calle del Camino 2204, Ciudad', NULL, NULL, 0),
(27, 39, 'Av. del Sol 2305, Ciudad', NULL, NULL, 1),
(28, 39, 'Calle del Bosque 2406, Ciudad', NULL, NULL, 0),
(29, 40, 'Av. del Centro 2507, Ciudad', NULL, NULL, 1),
(30, 40, 'Calle del Norte 2608, Ciudad', NULL, NULL, 0),
(31, 41, 'Av. del Sur 2709, Ciudad', NULL, NULL, 1),
(32, 41, 'Calle del Oeste 2801, Ciudad', NULL, NULL, 0),
(33, 42, 'Av. del Valle 2902, Ciudad', NULL, NULL, 1),
(34, 42, 'Calle del Bosque 3003, Ciudad', NULL, NULL, 0),
(35, 44, 'Av. del Río 3104, Ciudad', NULL, NULL, 1),
(36, 44, 'Calle del Sol 3205, Ciudad', NULL, NULL, 0),
(37, 45, 'Av. de las Flores 3306, Ciudad', NULL, NULL, 1),
(38, 45, 'Calle de los Pinos 3407, Ciudad', NULL, NULL, 0),
(39, 48, 'Av. del Parque 3508, Ciudad', NULL, NULL, 1),
(40, 48, 'Calle de la Esperanza 3609, Ciudad', NULL, NULL, 0),
(41, 49, 'Av. de los Álamos 3701, Ciudad', NULL, NULL, 1),
(42, 49, 'Calle de los Laureles 3802, Ciudad', NULL, NULL, 0),
(43, 50, 'Av. del Norte 3903, Ciudad', NULL, NULL, 1),
(44, 50, 'Calle del Sur 4004, Ciudad', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre_empleado` varchar(100) NOT NULL,
  `apellido_empleado` varchar(100) DEFAULT NULL,
  `telefono_empleado` varchar(15) DEFAULT NULL,
  `fecha_registro_empleado` date NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `numero_documento` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre_empleado`, `apellido_empleado`, `telefono_empleado`, `fecha_registro_empleado`, `tipo_documento`, `numero_documento`) VALUES
(1, 'Misael', 'López', '987654321', '2023-01-01', 'DNI', '12345678'),
(2, 'Diego', 'Ramírez', '912345678', '2023-02-15', 'DNI', '87654321'),
(3, 'Walter', 'García', '998877665', '2023-03-10', 'DNI', '45678912'),
(4, 'Alejandro', 'Fernández', '987123456', '2023-04-05', 'DNI', '78945612');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedido`
--

CREATE TABLE `estado_pedido` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estado_pedido`
--

INSERT INTO `estado_pedido` (`id_estado`, `nombre_estado`, `descripcion`) VALUES
(1, 'Pendiente', 'El pedido ha sido recibido, pero aún no se ha procesado.'),
(2, 'Confirmado', 'El pedido ha sido revisado y confirmado por el sistema o un operador.'),
(3, 'Preparado', 'El pedido está listo para ser recogido por el motorizado.'),
(4, 'En Ruta', 'El pedido está en camino hacia la dirección indicada.'),
(5, 'Llegó a Domicilio', 'El motorizado ha llegado al domicilio del cliente.'),
(6, 'Entregado', 'El pedido ha sido recibido satisfactoriamente por el cliente.'),
(7, 'Cancelado', 'El pedido ha sido cancelado por el cliente o el sistema.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_estado_pedidos`
--

CREATE TABLE `historial_estado_pedidos` (
  `id_historial` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_cambio` datetime NOT NULL DEFAULT current_timestamp(),
  `id_empleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `historial_estado_pedidos`
--

INSERT INTO `historial_estado_pedidos` (`id_historial`, `id_pedido`, `id_estado`, `fecha_cambio`, `id_empleado`) VALUES
(2, 1, 1, '2024-12-13 12:31:43', 1),
(3, 2, 1, '2024-12-13 12:48:10', 1),
(4, 2, 2, '2024-12-13 13:15:50', 1),
(5, 2, 3, '2024-12-13 13:19:39', 1),
(7, 2, 4, '2024-12-13 13:24:56', 2),
(8, 1, 2, '2024-12-13 13:39:20', 1),
(9, 1, 3, '2024-12-13 13:39:22', 1),
(10, 1, 4, '2024-12-13 13:39:53', 2),
(11, 3, 1, '2024-12-13 13:43:36', 1),
(12, 3, 4, '2024-12-13 13:46:27', 2),
(13, 4, 1, '2024-12-13 13:49:25', 1),
(14, 4, 2, '2024-12-13 13:57:45', 1),
(15, 4, 3, '2024-12-13 13:57:47', 1),
(16, 4, 4, '2024-12-13 13:57:50', 2),
(17, 4, 6, '2024-12-13 14:05:17', NULL),
(18, 3, 5, '2024-12-13 14:06:12', NULL),
(19, 3, 6, '2024-12-13 14:06:15', NULL),
(20, 4, 6, '2024-12-13 14:06:19', NULL),
(21, 2, 5, '2024-12-13 14:13:56', NULL),
(22, 2, 6, '2024-12-13 14:14:00', NULL),
(23, 1, 7, '2024-12-13 14:14:02', NULL),
(24, 5, 1, '2024-12-13 14:14:37', 1),
(25, 5, 2, '2024-12-13 14:14:51', NULL),
(26, 5, 3, '2024-12-13 14:15:36', NULL),
(27, 5, 4, '2024-12-13 14:15:42', 2),
(28, 5, 5, '2024-12-13 14:15:46', NULL),
(29, 5, 6, '2024-12-13 14:15:50', NULL),
(30, 5, 7, '2024-12-13 14:15:57', NULL),
(31, 6, 1, '2024-12-13 14:18:17', 1),
(35, 7, 1, '2024-12-13 14:24:33', 1),
(36, 7, 2, '2024-12-13 14:25:28', NULL),
(37, 6, 2, '2024-12-13 14:25:33', NULL),
(38, 7, 3, '2024-12-13 14:25:34', NULL),
(39, 7, 4, '2024-12-13 14:25:38', 2),
(40, 7, 5, '2024-12-13 14:25:41', NULL),
(41, 7, 6, '2024-12-13 14:25:43', NULL),
(42, 8, 1, '2024-12-13 14:28:19', 1),
(43, 8, 2, '2024-12-13 14:29:47', NULL),
(44, 9, 1, '2024-12-13 14:30:44', 1),
(45, 9, 2, '2024-12-13 14:36:53', NULL),
(46, 10, 1, '2024-12-13 14:40:53', 1),
(48, 9, 3, '2024-12-13 14:47:17', NULL),
(49, 9, 4, '2024-12-13 14:47:20', 2),
(50, 10, 2, '2024-12-13 14:48:42', NULL),
(52, 11, 1, '2024-12-13 14:52:55', 1),
(59, 11, 2, '2024-12-13 14:59:44', NULL),
(60, 8, 3, '2024-12-13 15:01:58', NULL),
(61, 8, 4, '2024-12-13 15:02:01', 2),
(62, 9, 5, '2024-12-13 15:02:02', NULL),
(63, 8, 5, '2024-12-13 15:02:04', NULL),
(64, 11, 3, '2024-12-13 15:02:10', NULL),
(65, 10, 3, '2024-12-13 15:02:11', NULL),
(66, 11, 4, '2024-12-13 15:02:14', 2),
(67, 11, 5, '2024-12-13 15:02:15', NULL),
(68, 10, 4, '2024-12-13 15:03:06', 2),
(69, 6, 3, '2024-12-13 15:03:07', NULL),
(70, 6, 4, '2024-12-13 15:03:09', 2),
(71, 10, 5, '2024-12-13 15:03:10', NULL),
(72, 6, 5, '2024-12-13 15:03:10', NULL),
(73, 11, 6, '2024-12-13 15:03:12', NULL),
(74, 10, 6, '2024-12-13 15:03:13', NULL),
(75, 9, 6, '2024-12-13 15:03:14', NULL),
(76, 8, 6, '2024-12-13 15:03:15', NULL),
(77, 6, 6, '2024-12-13 15:03:16', NULL),
(78, 12, 1, '2024-12-13 15:06:03', 1),
(79, 12, 2, '2024-12-13 15:06:05', NULL),
(80, 12, 3, '2024-12-13 15:06:06', NULL),
(81, 12, 4, '2024-12-13 15:06:07', 2),
(82, 12, 5, '2024-12-13 15:06:08', NULL),
(86, 12, 6, '2024-12-13 15:07:16', NULL),
(87, 13, 1, '2024-12-13 15:09:49', 1),
(88, 13, 2, '2024-12-13 15:09:51', NULL),
(89, 13, 3, '2024-12-13 15:09:52', NULL),
(90, 13, 4, '2024-12-13 15:09:54', 2),
(91, 13, 5, '2024-12-13 15:09:55', 2),
(92, 13, 6, '2024-12-13 15:09:57', 2),
(93, 14, 1, '2024-12-13 15:14:54', 1),
(96, 14, 2, '2024-12-13 15:18:34', NULL),
(97, 14, 3, '2024-12-13 15:18:41', NULL),
(98, 14, 4, '2024-12-13 15:18:43', 2),
(99, 14, 5, '2024-12-13 15:18:52', 2),
(100, 14, 6, '2024-12-13 15:18:53', 2),
(101, 15, 1, '2024-12-13 15:42:35', 1),
(102, 15, 2, '2024-12-13 15:42:37', NULL),
(103, 15, 3, '2024-12-13 15:42:39', NULL),
(104, 15, 4, '2024-12-13 15:42:42', 4),
(105, 15, 5, '2024-12-13 15:42:43', NULL),
(106, 15, 6, '2024-12-13 15:42:44', NULL),
(107, 16, 1, '2024-12-13 15:44:11', 1),
(108, 16, 2, '2024-12-13 15:44:13', NULL),
(109, 16, 3, '2024-12-13 15:44:14', NULL),
(110, 16, 4, '2024-12-13 15:44:16', 4),
(111, 16, 5, '2024-12-13 15:44:18', 4),
(112, 16, 6, '2024-12-13 15:44:19', 4),
(113, 17, 1, '2024-12-13 16:17:47', 1),
(114, 17, 7, '2024-12-13 16:17:49', NULL),
(115, 18, 1, '2024-12-13 17:50:51', 1),
(116, 18, 2, '2024-12-13 17:50:53', NULL),
(117, 18, 3, '2024-12-13 17:50:54', NULL),
(118, 18, 4, '2024-12-13 17:50:56', 2),
(119, 19, 1, '2024-12-13 17:51:11', 1),
(120, 19, 2, '2024-12-13 17:51:13', NULL),
(121, 19, 3, '2024-12-13 17:51:14', NULL),
(122, 19, 4, '2024-12-13 17:51:17', 4),
(123, 19, 5, '2024-12-13 17:55:06', 4),
(124, 19, 6, '2024-12-13 17:55:08', 4),
(125, 20, 1, '2024-12-13 18:05:50', 1),
(126, 20, 2, '2024-12-13 18:18:25', NULL),
(127, 20, 3, '2024-12-13 18:18:26', NULL),
(128, 20, 4, '2024-12-13 18:18:29', 2),
(129, 20, 5, '2024-12-13 18:18:30', 2),
(130, 20, 6, '2024-12-13 18:18:31', 2),
(131, 21, 1, '2024-12-13 18:22:06', 1),
(132, 21, 2, '2024-12-13 18:22:09', NULL),
(133, 21, 3, '2024-12-13 18:22:10', NULL),
(134, 21, 4, '2024-12-13 18:22:15', 4),
(135, 21, 5, '2024-12-13 18:23:47', 4),
(136, 21, 6, '2024-12-13 18:38:01', 4),
(137, 22, 1, '2024-12-13 18:41:05', 1),
(138, 22, 2, '2024-12-13 18:41:08', NULL),
(139, 22, 3, '2024-12-13 18:41:09', NULL),
(140, 22, 4, '2024-12-13 18:41:13', 4),
(141, 22, 5, '2024-12-13 18:41:20', 4),
(142, 22, 6, '2024-12-13 18:42:25', 4),
(143, 23, 1, '2024-12-13 18:43:55', 1),
(144, 23, 2, '2024-12-13 18:43:58', NULL),
(145, 23, 3, '2024-12-13 18:43:59', NULL),
(146, 23, 4, '2024-12-13 18:44:01', 4),
(147, 23, 5, '2024-12-13 18:44:08', 4),
(148, 23, 6, '2024-12-13 18:44:16', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_pedido` datetime NOT NULL,
  `total_pedido` decimal(10,2) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_pago` int(11) NOT NULL,
  `id_tipo_despacho` int(11) NOT NULL,
  `fecha_entrega` datetime NOT NULL DEFAULT current_timestamp(),
  `id_direccion_envio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `id_usuario`, `fecha_pedido`, `total_pedido`, `id_estado`, `id_empleado`, `id_pago`, `id_tipo_despacho`, `fecha_entrega`, `id_direccion_envio`) VALUES
(1, 1, 5, '2024-12-13 12:31:43', 110.00, 7, 2, 1, 1, '2024-12-13 12:31:43', 2),
(2, 1, 5, '2024-12-13 12:48:10', 30.00, 6, 2, 1, 1, '2024-12-13 12:48:10', 2),
(3, 1, 5, '2024-12-13 13:43:36', 55.00, 6, 2, 1, 1, '2024-12-13 13:43:36', 2),
(4, 1, 5, '2024-12-13 13:49:25', 55.00, 6, 2, 2, 1, '2024-12-13 13:49:25', 2),
(5, 2, 5, '2024-12-13 14:14:37', 60.00, 7, NULL, 1, 1, '2024-12-13 14:14:37', 4),
(6, 23, 5, '2024-12-13 14:18:17', 45.00, 6, NULL, 2, 1, '2024-12-13 14:18:17', 10),
(7, 1, 5, '2024-12-13 14:24:33', 55.00, 6, NULL, 1, 1, '2024-12-13 14:24:33', 2),
(8, 23, 5, '2024-12-13 14:28:19', 110.00, 6, NULL, 1, 1, '2024-12-13 14:28:19', 10),
(9, 1, 5, '2024-12-13 14:30:44', 60.00, 6, NULL, 2, 1, '2024-12-13 14:30:44', 2),
(10, 1, 5, '2024-12-13 14:40:53', 42.00, 6, NULL, 1, 1, '2024-12-13 14:40:53', 2),
(11, 1, 5, '2024-12-13 14:52:55', 60.00, 6, NULL, 1, 1, '2024-12-13 14:52:55', 2),
(12, 23, 5, '2024-12-13 15:06:03', 55.00, 6, NULL, 1, 1, '2024-12-13 15:06:03', 10),
(13, 23, 5, '2024-12-13 15:09:49', 55.00, 6, 2, 2, 1, '2024-12-13 15:09:49', 10),
(14, 1, 5, '2024-12-13 15:14:54', 90.00, 6, 2, 1, 1, '2024-12-13 15:14:54', 2),
(15, 1, 5, '2024-12-13 15:42:35', 290.00, 6, NULL, 2, 1, '2024-12-13 15:42:35', 2),
(16, 1, 5, '2024-12-13 15:44:11', 170.00, 6, 4, 1, 1, '2024-12-13 15:44:11', 2),
(17, 1, 5, '2024-12-13 16:17:47', 110.00, 7, NULL, 1, 1, '2024-12-13 16:17:47', 2),
(18, 1, 5, '2024-12-13 17:50:51', 55.00, 4, 2, 1, 1, '2024-12-13 17:50:51', 2),
(19, 2, 5, '2024-12-13 17:51:11', 55.00, 6, 4, 1, 1, '2024-12-13 17:51:11', 4),
(20, 1, 5, '2024-12-13 18:05:50', 60.00, 6, 2, 2, 1, '2024-12-13 18:05:50', 2),
(21, 23, 5, '2024-12-13 18:22:06', 55.00, 6, 4, 1, 1, '2024-12-13 18:22:06', 10),
(22, 23, 5, '2024-12-13 18:41:05', 55.00, 6, 4, 1, 1, '2024-12-13 18:41:05', 10),
(23, 44, 5, '2024-12-13 18:43:55', 42.00, 6, 4, 1, 1, '2024-12-13 18:43:55', 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `existencias` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `id_subcategoria` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `precio_compra`, `precio_venta`, `existencias`, `activo`, `id_subcategoria`, `id_proveedor`) VALUES
(1, 'Costagas', 37.00, 22.00, 0, 0, 1, 1),
(2, 'Llamagas', 36.00, 42.00, 20, 1, 2, 2),
(3, 'Multigas', 65.00, 20.00, 0, 1, 3, 3),
(4, 'Válvula de gas', 10.00, 15.00, 22, 1, 5, 1),
(5, 'Manguera de gas', 8.00, 12.00, 32, 1, 6, 2),
(6, 'Multigas', 37.50, 45.00, 8, 1, 2, 3),
(27, 'Solgas', 0.00, 38.00, 93, 0, 1, 3),
(28, 'Solgas', 0.00, 90.00, 149, 1, 2, 1),
(29, 'Solgas', 0.00, 120.00, 119, 1, 3, 1),
(30, 'Solgas', 0.00, 250.00, 80, 1, 4, 1),
(31, 'Costagas', 0.00, 55.00, 169, 1, 1, 2),
(32, 'Costagas', 0.00, 95.00, 180, 1, 2, 2),
(33, 'Costagas', 0.00, 130.00, 160, 1, 3, 2),
(34, 'Costagas', 0.00, 270.00, 70, 1, 4, 2),
(35, 'Llamagas', 0.00, 60.00, 292, 1, 1, 3),
(36, 'Llamagas', 0.00, 100.00, 250, 1, 2, 3),
(37, 'Llamagas', 0.00, 135.00, 198, 1, 3, 3),
(38, 'Llamagas', 0.00, 280.00, 50, 1, 4, 3),
(39, 'Manguera de Gas', 0.00, 15.00, 300, 1, 6, 1),
(40, 'Válvula de Gas', 0.00, 30.00, 200, 1, 5, 1),
(41, 'Abrazadera', 0.00, 10.00, 400, 1, 7, 1),
(42, 'Manguera de Gas', 0.00, 18.00, 250, 1, 6, 2),
(43, 'Válvula de Gas', 0.00, 35.00, 220, 1, 5, 2),
(44, 'Abrazadera', 0.00, 12.00, 350, 1, 7, 2),
(45, 'Manguera de Gas', 0.00, 20.00, 100, 1, 6, 3),
(46, 'Válvula de Gas', 0.00, 32.00, 130, 1, 5, 3),
(47, 'Abrazadera', 0.00, 14.00, 150, 1, 7, 3);

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
(1, 'Costagas', '987654321', 'Av. Gas del Sur 123', 'contacto@costagas.com', 'Juan Pérez'),
(2, 'Llamagas', '912345678', 'Av. Gas del Norte 456', 'ventas@llamagas.com', 'Luis Fernández'),
(3, 'Multigas', '963258741', 'Av. Gas del Este 789', 'soporte@multigas.com', 'Carlos López');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reabastecimientos`
--

CREATE TABLE `reabastecimientos` (
  `id_reabastecimiento` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_reabastecimiento` date NOT NULL,
  `total_reabastecimiento` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reabastecimientos`
--

INSERT INTO `reabastecimientos` (`id_reabastecimiento`, `id_proveedor`, `fecha_reabastecimiento`, `total_reabastecimiento`, `id_usuario`) VALUES
(1, 1, '2024-09-10', 850.00, 1),
(2, 2, '2024-11-04', 600.00, 2),
(3, 1, '2024-11-04', 1260.00, 1),
(4, 1, '2024-11-04', 1260.00, 1),
(5, 1, '2024-11-04', 1260.00, 1),
(6, 1, '2024-11-04', 259.00, 1);

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
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`id_subcategoria`, `nombre_subcategoria`, `id_categoria`) VALUES
(1, 'Balón de Gas 5 kg', 1),
(2, 'Balón de Gas 10 kg', 1),
(3, 'Balón de Gas 15 kg', 1),
(4, 'Balón de Gas 45 kg', 1),
(5, 'Válvulas', 2),
(6, 'Mangueras', 2),
(7, 'Abrazaderas', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos_cliente`
--

CREATE TABLE `telefonos_cliente` (
  `id_telefono` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `es_principal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `telefonos_cliente`
--

INSERT INTO `telefonos_cliente` (`id_telefono`, `id_cliente`, `telefono`, `es_principal`) VALUES
(1, 1, '987654321', 1),
(2, 1, '987654322', 0),
(3, 2, '987654323', 1),
(4, 2, '987654324', 0),
(5, 3, '987654325', 1),
(6, 3, '987654326', 0),
(7, 22, '987654327', 1),
(8, 22, '987654328', 0),
(9, 23, '987654329', 1),
(10, 23, '987654330', 0),
(11, 24, '987654331', 1),
(12, 24, '987654332', 0),
(13, 25, '987654333', 1),
(14, 25, '987654334', 0),
(15, 26, '987654335', 1),
(16, 26, '987654336', 0),
(17, 29, '987654337', 1),
(18, 29, '987654338', 0),
(19, 30, '987654339', 1),
(20, 30, '987654340', 0),
(21, 32, '987654341', 1),
(22, 32, '987654342', 0),
(23, 34, '987654343', 1),
(24, 34, '987654344', 0),
(25, 38, '987654345', 1),
(26, 38, '987654346', 0),
(27, 39, '987654347', 1),
(28, 39, '987654348', 0),
(29, 40, '987654349', 1),
(30, 40, '987654350', 0),
(31, 41, '987654351', 1),
(32, 41, '987654352', 0),
(33, 42, '987654353', 1),
(34, 42, '987654354', 0),
(35, 44, '987654355', 1),
(36, 44, '987654356', 0),
(37, 45, '987654357', 1),
(38, 45, '987654358', 0),
(39, 48, '987654359', 1),
(40, 48, '987654360', 0),
(41, 49, '987654361', 1),
(42, 49, '987654362', 0),
(43, 50, '987654363', 1),
(44, 50, '987654364', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_pago`
--

CREATE TABLE `tipos_pago` (
  `id_pago` int(11) NOT NULL,
  `descripcion_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipos_pago`
--

INSERT INTO `tipos_pago` (`id_pago`, `descripcion_pago`) VALUES
(1, 'Efectivo'),
(2, 'Visa'),
(3, 'Yape/Plin');

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
(2, 'Recojo en tienda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `password_usuario` varchar(255) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_registro_usuario` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `password_usuario`, `id_empleado`, `fecha_registro_usuario`) VALUES
(1, 'ambos', '123', 2, '2024-09-23'),
(2, 'admin', '123', 3, '2024-09-23'),
(5, 'recepcionista', '123', 1, '2024-10-24'),
(6, 'motorizado', '123', 4, '2024-10-25'),
(9, 'motorizadoprueba', '123', 2, '2024-12-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id_usuario`, `id_rol`) VALUES
(1, 1),
(1, 3),
(2, 1),
(5, 3),
(6, 2),
(9, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boletas`
--
ALTER TABLE `boletas`
  ADD PRIMARY KEY (`id_boleta`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`),
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
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `dni_cliente` (`dni_cliente`);

--
-- Indices de la tabla `detalles_reabastecimiento`
--
ALTER TABLE `detalles_reabastecimiento`
  ADD PRIMARY KEY (`id_detalle_reabastecimiento`),
  ADD KEY `id_reabastecimiento` (`id_reabastecimiento`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `direcciones_cliente`
--
ALTER TABLE `direcciones_cliente`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `historial_estado_pedidos`
--
ALTER TABLE `historial_estado_pedidos`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_pago` (`id_pago`),
  ADD KEY `id_tipo_despacho` (`id_tipo_despacho`),
  ADD KEY `fk_direccion_envio` (`id_direccion_envio`),
  ADD KEY `fk_pedidos_empleados` (`id_empleado`);

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
  ADD KEY `fk_reabastecimientos_usuario` (`id_usuario`);

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
-- Indices de la tabla `telefonos_cliente`
--
ALTER TABLE `telefonos_cliente`
  ADD PRIMARY KEY (`id_telefono`),
  ADD KEY `id_cliente` (`id_cliente`);

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
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boletas`
--
ALTER TABLE `boletas`
  MODIFY `id_boleta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `detalles_reabastecimiento`
--
ALTER TABLE `detalles_reabastecimiento`
  MODIFY `id_detalle_reabastecimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `direcciones_cliente`
--
ALTER TABLE `direcciones_cliente`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `historial_estado_pedidos`
--
ALTER TABLE `historial_estado_pedidos`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reabastecimientos`
--
ALTER TABLE `reabastecimientos`
  MODIFY `id_reabastecimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT de la tabla `telefonos_cliente`
--
ALTER TABLE `telefonos_cliente`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `tipos_pago`
--
ALTER TABLE `tipos_pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_despacho`
--
ALTER TABLE `tipo_despacho`
  MODIFY `id_tipo_despacho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boletas`
--
ALTER TABLE `boletas`
  ADD CONSTRAINT `boletas_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `boletas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `boletas_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `detalles_reabastecimiento`
--
ALTER TABLE `detalles_reabastecimiento`
  ADD CONSTRAINT `detalles_reabastecimiento_ibfk_1` FOREIGN KEY (`id_reabastecimiento`) REFERENCES `reabastecimientos` (`id_reabastecimiento`),
  ADD CONSTRAINT `detalles_reabastecimiento_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `direcciones_cliente`
--
ALTER TABLE `direcciones_cliente`
  ADD CONSTRAINT `direcciones_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Filtros para la tabla `historial_estado_pedidos`
--
ALTER TABLE `historial_estado_pedidos`
  ADD CONSTRAINT `historial_estado_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `historial_estado_pedidos_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado_pedido` (`id_estado`),
  ADD CONSTRAINT `historial_estado_pedidos_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_direccion_envio` FOREIGN KEY (`id_direccion_envio`) REFERENCES `direcciones_cliente` (`id_direccion`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pedidos_empleados` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado_pedido` (`id_estado`),
  ADD CONSTRAINT `pedidos_ibfk_4` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`),
  ADD CONSTRAINT `pedidos_ibfk_5` FOREIGN KEY (`id_pago`) REFERENCES `tipos_pago` (`id_pago`),
  ADD CONSTRAINT `pedidos_ibfk_6` FOREIGN KEY (`id_tipo_despacho`) REFERENCES `tipo_despacho` (`id_tipo_despacho`);

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
  ADD CONSTRAINT `fk_reabastecimientos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `reabastecimientos_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD CONSTRAINT `subcategorias_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `telefonos_cliente`
--
ALTER TABLE `telefonos_cliente`
  ADD CONSTRAINT `telefonos_cliente_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
