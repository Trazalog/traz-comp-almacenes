-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2019 a las 02:40:41
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asset_almacen_v3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_articulos`
--

CREATE TABLE `alm_articulos` (
  `arti_id` int(11) NOT NULL,
  `barcode` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `costo` decimal(14,2) NOT NULL,
  `es_caja` tinyint(4) NOT NULL,
  `cantidad_caja` int(11) DEFAULT NULL,
  `punto_pedido` int(11) DEFAULT NULL,
  `estado_id` varchar(45) COLLATE utf8_spanish_ci DEFAULT '1',
  `unidad_id` int(11) NOT NULL,
  `empr_id` int(11) NOT NULL,
  `es_loteado` tinyint(4) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_articulos`
--

INSERT INTO `alm_articulos` (`arti_id`, `barcode`, `descripcion`, `costo`, `es_caja`, `cantidad_caja`, `punto_pedido`, `estado_id`, `unidad_id`, `empr_id`, `es_loteado`, `fec_alta`, `eliminado`) VALUES
(1, 'AAA_111_EMP6', 'duplicado', '0.00', 0, 0, 200, '1', 22, 6, 0, '2019-06-13 11:48:57', 0),
(35, 'AAA_111', 'Descripcion A', '0.00', 1, 10, 111, '1', 21, 1, 1, '2019-04-23 18:26:07', 0),
(36, 'BBB_222', 'Descripcion B', '0.00', 0, 0, 222, '1', 21, 1, 1, '2019-04-23 18:29:26', 0),
(37, 'CCC_333', 'Descripcion C', '0.00', 0, 0, 333, '1', 21, 1, 1, '2019-04-23 18:30:04', 0),
(38, '7790463000114', 'Ceramicol', '0.00', 1, 6, 100, '1', 22, 1, 0, '2019-05-24 00:56:58', 0),
(39, 'AAA_111_EMP6', 'Descripcion A', '0.00', 0, 0, 100, '1', 22, 6, 1, '2019-06-04 18:43:52', 1),
(40, 'BBB_222_EMP6', 'Descripcion B', '0.00', 0, 0, 200, '1', 22, 6, 1, '2019-06-04 18:44:19', 0),
(41, 'CCC_333_EMP6', 'Descripcion C', '0.00', 1, 10, 300, '1', 22, 6, 0, '2019-06-04 18:45:18', 0),
(42, 'ART_TEST', 'Descripcion Test', '0.00', 0, 0, 500, '1', 22, 6, 1, '2019-06-06 15:44:55', 0),
(43, 'AAA_111_EMP6', 'articulo duplicado', '0.00', 0, 0, 200, '1', 21, 6, 0, '2019-06-13 11:44:20', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_depositos`
--

CREATE TABLE `alm_depositos` (
  `depo_id` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `GPS` varchar(255) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `loca_id` varchar(255) DEFAULT NULL,
  `esta_id` varchar(255) DEFAULT NULL,
  `pais_id` varchar(255) DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_depositos`
--

INSERT INTO `alm_depositos` (`depo_id`, `descripcion`, `direccion`, `GPS`, `estado_id`, `loca_id`, `esta_id`, `pais_id`, `empr_id`, `fec_alta`, `eliminado`) VALUES
(2, 'Deposito A', 'Direccion A', 'AAA', 1, '1', '1', '1', 6, '2019-05-16 05:45:23', 0),
(3, 'Desposito B', 'DIreccion B', 'BBB', 1, NULL, NULL, NULL, 6, '2019-05-24 01:01:33', 0),
(4, 'Deposito C', 'Direccion C', 'CCC', 1, '1', '1', '1', 1, '2019-06-05 22:25:58', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_deta_entrega_materiales`
--

CREATE TABLE `alm_deta_entrega_materiales` (
  `deen_id` int(11) NOT NULL,
  `enma_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `arti_id` int(11) NOT NULL,
  `prov_id` int(10) DEFAULT NULL,
  `lote_id` int(11) NOT NULL,
  `depo_id` int(11) DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `precio` double DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_deta_entrega_materiales`
--

INSERT INTO `alm_deta_entrega_materiales` (`deen_id`, `enma_id`, `cantidad`, `arti_id`, `prov_id`, `lote_id`, `depo_id`, `empr_id`, `precio`, `fec_alta`, `eliminado`) VALUES
(1, 1, 25, 39, 1, 1, 2, 6, NULL, '2019-06-06 16:33:37', 0),
(2, 1, 25, 39, 1, 15, 2, 6, NULL, '2019-06-06 16:33:37', 0),
(3, 1, 100, 40, 1, 2, 2, 6, NULL, '2019-06-06 16:33:37', 0),
(4, 1, 100, 40, 1, 3, 3, 6, NULL, '2019-06-06 16:33:37', 0),
(5, 2, 111, 39, 1, 1, 2, 6, NULL, '2019-06-11 19:56:37', 0),
(6, 2, 200, 40, 1, 3, 3, 6, NULL, '2019-06-11 19:56:37', 0),
(7, 2, 22, 40, 1, 17, 2, 6, NULL, '2019-06-11 19:56:37', 0),
(8, 3, 300, 42, 1, 16, 2, 6, NULL, '2019-06-12 02:10:31', 0),
(9, 4, 50, 42, 1, 16, 2, 6, NULL, '2019-06-12 02:17:27', 0),
(10, 5, 50, 42, 1, 16, 2, 6, NULL, '2019-06-12 02:19:54', 0),
(11, 6, 100, 39, 1, 1, 2, 6, NULL, '2019-06-12 03:07:47', 0),
(12, 6, 11, 39, 1, 15, 2, 6, NULL, '2019-06-12 03:07:47', 0),
(13, 6, 222, 40, 1, 2, 2, 6, NULL, '2019-06-12 03:07:47', 0),
(14, 7, 200, 40, 1, 17, 2, 6, NULL, '2019-06-12 03:23:58', 0),
(15, 7, 200, 42, 1, 16, 2, 6, NULL, '2019-06-12 03:23:58', 0),
(16, 8, 100, 42, 1, 19, 2, 6, NULL, '2019-06-12 03:44:38', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_deta_pedidos_materiales`
--

CREATE TABLE `alm_deta_pedidos_materiales` (
  `depe_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `resto` int(11) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_entregado` date DEFAULT NULL,
  `pema_id` int(11) NOT NULL,
  `arti_id` int(11) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_deta_pedidos_materiales`
--

INSERT INTO `alm_deta_pedidos_materiales` (`depe_id`, `cantidad`, `resto`, `fecha_entrega`, `fecha_entregado`, `pema_id`, `arti_id`, `fec_alta`, `eliminado`) VALUES
(1, 100, 100, '2019-06-12', NULL, 1, 39, '2019-06-12 01:21:42', 0),
(2, 200, 200, '2019-06-12', NULL, 1, 40, '2019-06-12 01:21:50', 0),
(3, 300, 300, '2019-06-12', NULL, 1, 41, '2019-06-12 01:21:55', 0),
(4, 50, 50, '2019-06-12', NULL, 1, 42, '2019-06-12 01:22:03', 0),
(5, 400, 0, '2019-06-12', NULL, 2, 42, '2019-06-12 01:25:10', 0),
(6, 23, 23, '2019-06-12', NULL, 3, 40, '2019-06-12 02:50:26', 0),
(7, 23, 23, '2019-06-12', NULL, 4, 40, '2019-06-12 02:50:32', 0),
(8, 23, 23, '2019-06-12', NULL, 4, 41, '2019-06-12 02:50:58', 0),
(9, 111, 0, '2019-06-12', NULL, 5, 39, '2019-06-12 02:55:27', 0),
(10, 222, 0, '2019-06-12', NULL, 5, 40, '2019-06-12 02:55:37', 0),
(11, 333, 333, '2019-06-12', NULL, 5, 41, '2019-06-12 02:55:53', 0),
(12, 23, 23, '2019-06-12', NULL, 6, 41, '2019-06-12 02:56:47', 0),
(13, 200, 200, '2019-06-12', NULL, 7, 40, '2019-06-12 03:17:59', 0),
(14, 200, 0, '2019-06-12', NULL, 8, 40, '2019-06-12 03:19:20', 0),
(15, 300, 0, '2019-06-12', NULL, 8, 42, '2019-06-12 03:20:48', 0),
(16, 111, 111, '2019-06-12', NULL, 9, 39, '2019-06-12 04:00:46', 0),
(17, 100, 100, '2019-06-12', NULL, 10, 40, '2019-06-12 04:02:11', 0),
(18, 1111, 1111, '2019-06-12', NULL, 11, 40, '2019-06-12 04:03:57', 0),
(19, 123, 123, '2019-06-12', NULL, 12, 39, '2019-06-12 04:06:02', 0),
(20, 100, 100, '2019-06-12', NULL, 13, 39, '2019-06-12 04:06:48', 0),
(21, 100, 100, '2019-06-12', NULL, 14, 39, '2019-06-12 04:09:39', 0),
(22, 100, 100, '2019-06-14', NULL, 15, 1, '2019-06-13 21:16:24', 0),
(23, 100, 100, '2019-06-14', NULL, 16, 1, '2019-06-13 21:17:54', 0),
(24, 100, 100, '2019-06-14', NULL, 17, 1, '2019-06-13 21:18:34', 0),
(25, 100, 100, '2019-06-14', NULL, 18, 1, '2019-06-13 21:22:27', 0),
(26, 100, 100, '2019-06-14', NULL, 19, 1, '2019-06-13 21:25:46', 0),
(27, 100, 100, '2019-06-14', NULL, 20, 1, '2019-06-13 21:26:22', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_deta_recepcion_materiales`
--

CREATE TABLE `alm_deta_recepcion_materiales` (
  `dere_id` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `precio` double NOT NULL,
  `empr_id` int(11) NOT NULL,
  `rema_id` int(11) NOT NULL,
  `lote_id` int(11) NOT NULL,
  `prov_id` int(10) NOT NULL,
  `arti_id` int(11) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_deta_recepcion_materiales`
--

INSERT INTO `alm_deta_recepcion_materiales` (`dere_id`, `cantidad`, `precio`, `empr_id`, `rema_id`, `lote_id`, `prov_id`, `arti_id`, `fec_alta`, `eliminado`) VALUES
(1, 1000, 0, 1, 1, 15, 1, 39, '2019-06-06 15:54:43', 0),
(2, 1000, 0, 1, 2, 15, 1, 39, '2019-06-06 15:57:26', 0),
(3, 500, 0, 1, 2, 16, 1, 42, '2019-06-06 15:57:27', 0),
(4, 100, 0, 1, 3, 15, 1, 39, '2019-06-06 16:00:40', 0),
(5, 100, 0, 1, 3, 16, 1, 42, '2019-06-06 16:00:41', 0),
(6, 100, 0, 1, 4, 1, 1, 39, '2019-06-10 00:35:42', 0),
(7, 123, 0, 1, 5, 1, 1, 39, '2019-06-11 08:31:09', 0),
(8, 222, 0, 1, 6, 17, 1, 40, '2019-06-11 08:31:57', 0),
(9, 500, 0, 1, 7, 2, 1, 40, '2019-06-12 03:32:21', 0),
(10, 300, 0, 1, 15, 18, 1, 40, '2019-06-12 03:35:49', 0),
(11, 300, 0, 1, 16, 19, 1, 42, '2019-06-12 03:41:04', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_entrega_materiales`
--

CREATE TABLE `alm_entrega_materiales` (
  `enma_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `solicitante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comprobante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `pema_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_entrega_materiales`
--

INSERT INTO `alm_entrega_materiales` (`enma_id`, `fecha`, `solicitante`, `comprobante`, `empr_id`, `pema_id`, `fec_alta`, `eliminado`) VALUES
(1, '2019-06-20', 'Fernando Leiva', 'ENT_001', 6, 2, '2019-06-06 16:33:37', 0),
(2, '2019-06-12', 'Fernando Leiva', 'comp_tes', 6, 10, '2019-06-11 19:56:37', 0),
(3, '2019-06-12', 'Fernando Leiva', 'comp1', 6, 2, '2019-06-12 02:10:31', 0),
(4, '2019-06-13', 'Fernando Leiva', 'comp2', 6, 2, '2019-06-12 02:17:27', 0),
(5, '2019-06-26', 'Fernando Leiva', 'comp3', 6, 2, '2019-06-12 02:19:54', 0),
(6, '2019-06-12', 'Fernando Leiva', 'comprobante-0001', 6, 5, '2019-06-12 03:07:47', 0),
(7, '2019-06-12', 'Mauri Per', 'mautest', 6, 8, '2019-06-12 03:23:58', 0),
(8, '2019-06-13', 'Fernando Leiva', 'asdasdasdsad', 6, 8, '2019-06-12 03:44:38', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_lotes`
--

CREATE TABLE `alm_lotes` (
  `lote_id` int(11) NOT NULL,
  `prov_id` int(10) NOT NULL,
  `arti_id` int(11) NOT NULL,
  `depo_id` int(11) NOT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `fec_vencimiento` date DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `estado_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_lotes`
--

INSERT INTO `alm_lotes` (`lote_id`, `prov_id`, `arti_id`, `depo_id`, `codigo`, `fec_vencimiento`, `cantidad`, `empr_id`, `user_id`, `estado_id`, `fec_alta`, `eliminado`) VALUES
(1, 1, 39, 2, 'LOTE_AAA', '2019-06-01', 687, 6, NULL, 1, '2019-06-05 22:10:56', 0),
(2, 1, 40, 2, 'LOTE_BBB', '2019-06-02', 528, 6, NULL, 1, '2019-06-05 22:10:56', 0),
(3, 1, 40, 3, 'LOTE_BBB', '2019-06-20', 550, 6, NULL, 1, '2019-06-05 22:10:56', 0),
(4, 1, 41, 2, '1', '2019-06-13', 2850, 6, NULL, 1, '2019-06-05 22:14:58', 0),
(12, 2, 35, 4, 'LOTE_AAA', '2019-06-20', 1110, 1, NULL, 1, '2019-06-05 23:16:42', 0),
(13, 2, 36, 4, 'LOTE_BBB', '2019-06-21', 222, 1, NULL, 1, '2019-06-05 23:16:42', 0),
(14, 2, 37, 4, 'LOTE_CCC', '2019-06-26', 333, 1, NULL, 1, '2019-06-05 23:16:42', 0),
(15, 1, 39, 2, 'lote_test', '2019-06-20', 2064, 6, NULL, 1, '2019-06-06 15:54:43', 0),
(16, 1, 42, 2, 'dartest', '2019-06-26', 0, 6, NULL, 1, '2019-06-06 15:57:26', 0),
(17, 1, 40, 2, '123', '0000-00-00', 0, 6, NULL, 1, '2019-06-11 08:31:57', 0),
(18, 1, 40, 2, 'lote_333', '2019-06-14', 300, 6, NULL, 1, '2019-06-12 03:35:49', 0),
(19, 1, 42, 2, 'arte_lote', '2019-06-12', 200, 6, NULL, 1, '2019-06-12 03:41:04', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_pedidos_extraordinario`
--

CREATE TABLE `alm_pedidos_extraordinario` (
  `peex_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `detalle` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `motivo_rechazo` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `case_id` int(11) DEFAULT NULL,
  `pema_id` int(11) DEFAULT NULL,
  `ortr_id` int(11) DEFAULT NULL,
  `empr_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Volcado de datos para la tabla `alm_pedidos_extraordinario`
--

INSERT INTO `alm_pedidos_extraordinario` (`peex_id`, `fecha`, `detalle`, `motivo_rechazo`, `case_id`, `pema_id`, `ortr_id`, `empr_id`, `fec_alta`, `eliminado`) VALUES
(1, '2019-06-06', 'Soy un Pedido', '', 50001, 18, 1, 1, '2019-06-06 16:02:43', 0),
(2, '2019-06-06', 'Soy un Pedido', '', 50004, 12, 1, 1, '2019-06-06 16:17:25', 0),
(3, '2019-06-06', 'Soy un Pedido', 'por forro', 50006, NULL, 1, 1, '2019-06-06 17:06:36', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_pedidos_materiales`
--

CREATE TABLE `alm_pedidos_materiales` (
  `pema_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo_rechazo` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `case_id` int(11) DEFAULT NULL,
  `ortr_id` int(11) DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8_spanish_ci DEFAULT 'Solicitado',
  `justificacion` varchar(300) COLLATE utf8_spanish_ci DEFAULT NULL,
  `empr_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_pedidos_materiales`
--

INSERT INTO `alm_pedidos_materiales` (`pema_id`, `fecha`, `motivo_rechazo`, `case_id`, `ortr_id`, `estado`, `justificacion`, `empr_id`, `fec_alta`, `eliminado`) VALUES
(1, '2019-06-12', NULL, 55005, 0, 'Solicitado', 'Justificacion 1', 6, '2019-06-12 01:21:41', 0),
(2, '2019-06-12', '', 54005, 0, 'Entregado', 'Justificacion 2', 6, '2019-06-12 01:25:06', 0),
(3, '2019-06-12', NULL, 55001, 0, 'Solicitado', 'Fff', 6, '2019-06-12 02:50:26', 0),
(4, '2019-06-12', NULL, 55002, 0, 'Solicitado', 'Fff', 6, '2019-06-12 02:50:32', 0),
(5, '2019-06-12', '', 55003, 0, 'Ent. Parcial', 'Test 3', 6, '2019-06-12 02:55:27', 0),
(6, '2019-06-12', NULL, 55004, 0, 'Solicitado', '', 6, '2019-06-12 02:56:47', 0),
(7, '2019-06-12', NULL, 55006, 0, 'Solicitado', 'Pedido Mau', 6, '2019-06-12 03:17:59', 0),
(8, '2019-06-12', '', 55007, 0, 'Entregado', 'Pedido Mau 2', 6, '2019-06-12 03:19:20', 0),
(9, '2019-06-12', NULL, 55008, 0, 'Solicitado', 'asd', 6, '2019-06-12 04:00:46', 0),
(10, '2019-06-12', NULL, 55009, 0, 'Solicitado', 'Soy una Justificacion', 6, '2019-06-12 04:02:11', 0),
(11, '2019-06-12', NULL, 55010, 0, 'Solicitado', 'Test 2', 6, '2019-06-12 04:03:56', 0),
(12, '2019-06-12', NULL, 55011, 0, 'Solicitado', 'asd', 6, '2019-06-12 04:06:01', 0),
(13, '2019-06-12', NULL, 55012, 0, 'Solicitado', '', 6, '2019-06-12 04:06:48', 0),
(14, '2019-06-12', 'por forro', 55013, 0, 'Rechazado', 'Hlalalala', 6, '2019-06-12 04:09:39', 0),
(15, '2019-06-14', NULL, NULL, 0, 'Solicitado', 'Soy un pedido', 6, '2019-06-13 21:16:24', 0),
(16, '2019-06-14', NULL, NULL, 0, 'Solicitado', 'Soy un pedido', 6, '2019-06-13 21:17:54', 0),
(17, '2019-06-14', NULL, NULL, 0, 'Solicitado', 'Soy un pedido', 6, '2019-06-13 21:18:34', 0),
(18, '2019-06-14', NULL, NULL, 0, 'Solicitado', 'Soy un pedido', 6, '2019-06-13 21:21:41', 0),
(19, '2019-06-14', NULL, 56001, 0, 'Solicitado', 'asd', 6, '2019-06-13 21:25:46', 0),
(20, '2019-06-14', NULL, 56002, 0, 'Solicitado', 'asd', 6, '2019-06-13 21:26:21', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_proveedores`
--

CREATE TABLE `alm_proveedores` (
  `prov_id` int(10) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `cuit` varchar(50) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_proveedores`
--

INSERT INTO `alm_proveedores` (`prov_id`, `nombre`, `cuit`, `domicilio`, `telefono`, `email`, `empr_id`, `fec_alta`, `eliminado`) VALUES
(1, 'Proveedor A', '111', '-', '-', '-', 6, '2019-04-23 18:44:14', 0),
(2, 'Proveedor B', '222', '-', '-', '-', 1, '2019-06-05 22:24:05', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_proveedores_articulos`
--

CREATE TABLE `alm_proveedores_articulos` (
  `prov_id` int(10) NOT NULL,
  `arti_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_proveedores_articulos`
--

INSERT INTO `alm_proveedores_articulos` (`prov_id`, `arti_id`) VALUES
(1, 35),
(1, 36),
(1, 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_recepcion_materiales`
--

CREATE TABLE `alm_recepcion_materiales` (
  `rema_id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `comprobante` varchar(255) CHARACTER SET latin1 NOT NULL,
  `empr_id` int(11) NOT NULL,
  `prov_id` int(10) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_recepcion_materiales`
--

INSERT INTO `alm_recepcion_materiales` (`rema_id`, `fecha`, `comprobante`, `empr_id`, `prov_id`, `fec_alta`, `eliminado`) VALUES
(1, '2019-06-06 15:47:31', 'COMP_TEST', 6, 1, '2019-06-06 15:54:43', 0),
(2, '2019-06-11 15:55:27', 'comp_2', 6, 1, '2019-06-06 15:57:26', 0),
(3, '2019-07-02 15:59:34', 'comp3', 6, 1, '2019-06-06 16:00:40', 0),
(4, '2019-06-11 00:34:06', 'COMP_RECEP_003', 6, 1, '2019-06-10 00:35:42', 0),
(5, '0000-00-00 00:00:00', 'asd', 6, 1, '2019-06-11 08:31:09', 0),
(6, '0000-00-00 00:00:00', 'recep_test', 6, 1, '2019-06-11 08:31:57', 0),
(7, '2019-06-12 03:25:00', 'mautest', 6, 1, '2019-06-12 03:32:21', 0),
(15, '2019-06-12 03:34:00', 'asdqwe', 6, 1, '2019-06-12 03:35:48', 0),
(16, '2019-06-12 03:40:00', 'casjdajkdsa', 6, 1, '2019-06-12 03:41:04', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_trabajo`
--

CREATE TABLE `orden_trabajo` (
  `id_orden` int(11) NOT NULL,
  `id_tarea` int(11) DEFAULT NULL,
  `nro` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_program` datetime NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_entrega` datetime NOT NULL,
  `fecha_terminada` datetime NOT NULL,
  `fecha_aviso` datetime NOT NULL,
  `fecha_entregada` datetime NOT NULL,
  `descripcion` text NOT NULL,
  `cliId` int(11) NOT NULL DEFAULT '1',
  `estado` varchar(2) NOT NULL,
  `id_usuario` int(11) NOT NULL DEFAULT '1',
  `id_usuario_a` int(11) NOT NULL,
  `id_usuario_e` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL DEFAULT '1',
  `id_proveedor` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `tipo` varchar(2) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `duracion` double NOT NULL,
  `id_tareapadre` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `lectura_programada` double NOT NULL,
  `lectura_ejecutada` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id_orden`, `id_tarea`, `nro`, `fecha`, `fecha_program`, `fecha_inicio`, `fecha_entrega`, `fecha_terminada`, `fecha_aviso`, `fecha_entregada`, `descripcion`, `cliId`, `estado`, `id_usuario`, `id_usuario_a`, `id_usuario_e`, `id_sucursal`, `id_proveedor`, `id_solicitud`, `tipo`, `id_equipo`, `duracion`, `id_tareapadre`, `id_empresa`, `lectura_programada`, `lectura_ejecutada`) VALUES
(1, 1, '1', '2019-03-22', '2019-03-22 11:11:00', '2019-03-22 15:24:56', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'fffffffff', 1, 'C', 102, 102, 1, 1, 0, 26, '2', 7, 60, 26, 1, 0, 0),
(33, 1, '1', '2019-03-22', '2019-03-22 14:22:00', '2019-03-22 15:52:35', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'ggggggg', 1, 'Ej', 102, 102, 1, 1, 0, 27, '2', 7, 60, 27, 1, 0, 0),
(34, 1, '1', '2019-03-22', '2019-03-22 00:00:00', '2019-03-22 16:17:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'ggggggg', 1, 'Ej', 102, 102, 1, 1, 0, 28, '2', 3, 60, 28, 1, 0, 0),
(35, 1, '1', '2019-03-22', '2019-03-23 11:11:00', '2019-03-22 16:22:37', '2018-01-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'hhhhhh', 1, 'As', 102, 102, 1, 1, 0, 29, '2', 3, 60, 29, 1, 0, 0),
(36, 1, '1', '2019-03-22', '2019-03-23 11:11:00', '2019-03-22 16:40:47', '2019-01-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'jjjjjjj', 1, 'As', 102, 101, 1, 1, 0, 31, '2', 7, 60, 31, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_reparacion`
--

CREATE TABLE `solicitud_reparacion` (
  `id_solicitud` int(100) NOT NULL,
  `numero` int(100) DEFAULT NULL,
  `id_tipo` int(10) DEFAULT NULL,
  `nivel` int(10) DEFAULT NULL,
  `solicitante` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `f_solicitado` datetime DEFAULT NULL,
  `f_sugerido` date DEFAULT NULL,
  `hora_sug` time DEFAULT NULL,
  `id_equipo` int(10) DEFAULT NULL,
  `correctivo` int(10) DEFAULT NULL,
  `causa` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `observaciones` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET latin1 DEFAULT NULL,
  `usrId` int(11) DEFAULT NULL,
  `fecha_conformidad` date DEFAULT NULL,
  `observ_conformidad` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `foto1` blob,
  `foto2` blob,
  `foto3` blob,
  `foto` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `solicitud_reparacion`
--

INSERT INTO `solicitud_reparacion` (`id_solicitud`, `numero`, `id_tipo`, `nivel`, `solicitante`, `f_solicitado`, `f_sugerido`, `hora_sug`, `id_equipo`, `correctivo`, `causa`, `observaciones`, `estado`, `usrId`, `fecha_conformidad`, `observ_conformidad`, `foto1`, `foto2`, `foto3`, `foto`, `id_empresa`) VALUES
(1, NULL, NULL, NULL, 'Fernando Leiva', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `utl_tablas`
--

CREATE TABLE `utl_tablas` (
  `tabl_id` int(11) NOT NULL,
  `tabla` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `valor` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Volcado de datos para la tabla `utl_tablas`
--

INSERT INTO `utl_tablas` (`tabl_id`, `tabla`, `valor`, `descripcion`, `fec_alta`, `eliminado`) VALUES
(1, 'estado', 'AC', 'ACTIVO', '2019-04-23 18:28:29', 0),
(21, 'unidad', 'KM', 'KILOMETROS', '2019-04-23 18:25:47', 0),
(22, 'unidad', 'UN', 'UNIDAD', '2019-05-24 00:56:16', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alm_articulos`
--
ALTER TABLE `alm_articulos`
  ADD PRIMARY KEY (`arti_id`);

--
-- Indices de la tabla `alm_depositos`
--
ALTER TABLE `alm_depositos`
  ADD PRIMARY KEY (`depo_id`);

--
-- Indices de la tabla `alm_deta_entrega_materiales`
--
ALTER TABLE `alm_deta_entrega_materiales`
  ADD PRIMARY KEY (`deen_id`);

--
-- Indices de la tabla `alm_deta_pedidos_materiales`
--
ALTER TABLE `alm_deta_pedidos_materiales`
  ADD PRIMARY KEY (`depe_id`);

--
-- Indices de la tabla `alm_deta_recepcion_materiales`
--
ALTER TABLE `alm_deta_recepcion_materiales`
  ADD PRIMARY KEY (`dere_id`);

--
-- Indices de la tabla `alm_entrega_materiales`
--
ALTER TABLE `alm_entrega_materiales`
  ADD PRIMARY KEY (`enma_id`);

--
-- Indices de la tabla `alm_lotes`
--
ALTER TABLE `alm_lotes`
  ADD PRIMARY KEY (`lote_id`,`prov_id`,`arti_id`,`depo_id`);

--
-- Indices de la tabla `alm_pedidos_extraordinario`
--
ALTER TABLE `alm_pedidos_extraordinario`
  ADD PRIMARY KEY (`peex_id`);

--
-- Indices de la tabla `alm_pedidos_materiales`
--
ALTER TABLE `alm_pedidos_materiales`
  ADD PRIMARY KEY (`pema_id`);

--
-- Indices de la tabla `alm_proveedores`
--
ALTER TABLE `alm_proveedores`
  ADD PRIMARY KEY (`prov_id`);

--
-- Indices de la tabla `alm_proveedores_articulos`
--
ALTER TABLE `alm_proveedores_articulos`
  ADD PRIMARY KEY (`prov_id`,`arti_id`);

--
-- Indices de la tabla `alm_recepcion_materiales`
--
ALTER TABLE `alm_recepcion_materiales`
  ADD PRIMARY KEY (`rema_id`),
  ADD UNIQUE KEY `comprobante_UNIQUE` (`comprobante`);

--
-- Indices de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `orden_trabajo_ibfk_1` (`cliId`) USING BTREE,
  ADD KEY `id_usuario` (`id_usuario`) USING BTREE,
  ADD KEY `id_usuariosolicitante` (`id_usuario_a`) USING BTREE,
  ADD KEY `usuario_entrega` (`id_usuario_e`) USING BTREE,
  ADD KEY `id_sucursal` (`id_sucursal`) USING BTREE;

--
-- Indices de la tabla `solicitud_reparacion`
--
ALTER TABLE `solicitud_reparacion`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `utl_tablas`
--
ALTER TABLE `utl_tablas`
  ADD PRIMARY KEY (`tabl_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alm_articulos`
--
ALTER TABLE `alm_articulos`
  MODIFY `arti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `alm_depositos`
--
ALTER TABLE `alm_depositos`
  MODIFY `depo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alm_deta_entrega_materiales`
--
ALTER TABLE `alm_deta_entrega_materiales`
  MODIFY `deen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `alm_deta_pedidos_materiales`
--
ALTER TABLE `alm_deta_pedidos_materiales`
  MODIFY `depe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `alm_deta_recepcion_materiales`
--
ALTER TABLE `alm_deta_recepcion_materiales`
  MODIFY `dere_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `alm_entrega_materiales`
--
ALTER TABLE `alm_entrega_materiales`
  MODIFY `enma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `alm_lotes`
--
ALTER TABLE `alm_lotes`
  MODIFY `lote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `alm_pedidos_extraordinario`
--
ALTER TABLE `alm_pedidos_extraordinario`
  MODIFY `peex_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alm_pedidos_materiales`
--
ALTER TABLE `alm_pedidos_materiales`
  MODIFY `pema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `alm_proveedores`
--
ALTER TABLE `alm_proveedores`
  MODIFY `prov_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alm_recepcion_materiales`
--
ALTER TABLE `alm_recepcion_materiales`
  MODIFY `rema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `solicitud_reparacion`
--
ALTER TABLE `solicitud_reparacion`
  MODIFY `id_solicitud` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `utl_tablas`
--
ALTER TABLE `utl_tablas`
  MODIFY `tabl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
