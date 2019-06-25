-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-06-2019 a las 07:07:21
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
(1, 'AAA-01', 'articulo numero 1', '0.00', 0, 0, 30, '1', 22, 6, 1, '2019-06-19 09:42:34', 0),
(2, 'BBB_222', 'Descripcion A', '0.00', 1, 456, 100, '1', 22, 6, 1, '2019-06-19 09:51:17', 0),
(3, 'AAA_111_EMP6', 'asd', '0.00', 0, 0, 300, '1', 22, 6, 1, '2019-06-19 09:51:52', 0),
(4, 'CCC--08', 'DESCRIPCIÓN 66 ', '0.00', 1, 50, 100, '1', 22, 6, 1, '2019-06-19 10:25:23', 0);

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
(1, 'Desposito A', '', NULL, NULL, NULL, NULL, NULL, 6, '2019-06-19 10:29:28', 0),
(2, 'Desposito B', NULL, NULL, NULL, NULL, NULL, NULL, 6, '2019-06-19 10:29:28', 0),
(3, 'Desposito C', NULL, NULL, NULL, NULL, NULL, NULL, 6, '2019-06-19 10:29:28', 0);

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
(1, 1, 100, 4, 1, 1, 2, 6, NULL, '2019-06-19 11:11:22', 0),
(2, 2, 80, 2, 1, 3, 2, 6, NULL, '2019-06-19 11:15:28', 0);

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
(2, 100, 0, '2019-06-19', NULL, 1, 4, '2019-06-19 10:34:19', 0),
(3, 80, 40, '2019-06-19', NULL, 1, 2, '2019-06-19 10:35:23', 0);

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
(1, 250, 0, 1, 1, 1, 1, 4, '2019-06-19 10:30:59', 0),
(2, 1000, 0, 1, 2, 2, 1, 1, '2019-06-19 11:14:11', 0),
(3, 456000, 0, 1, 2, 3, 1, 2, '2019-06-19 11:14:12', 0),
(4, 1000, 0, 1, 2, 4, 1, 3, '2019-06-19 11:14:12', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_entrega_materiales`
--

CREATE TABLE `alm_entrega_materiales` (
  `enma_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `solicitante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dni` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comprobante` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `pema_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_entrega_materiales`
--

INSERT INTO `alm_entrega_materiales` (`enma_id`, `fecha`, `solicitante`, `dni`, `comprobante`, `empr_id`, `pema_id`, `fec_alta`, `eliminado`) VALUES
(1, '2019-06-19', 'eliana', '333333', '00002', 6, 1, '2019-06-19 11:11:22', 0),
(2, '2019-06-19', 'eli', '222222', '0002', 6, 1, '2019-06-19 11:15:28', 0);

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
(1, 1, 4, 2, '008', '2020-06-01', 150, 6, NULL, 1, '2019-06-19 10:30:59', 0),
(2, 1, 1, 2, '01', '2019-06-19', 1000, 6, NULL, 1, '2019-06-19 11:14:11', 0),
(3, 1, 2, 2, '02', '2019-06-19', 455920, 6, NULL, 1, '2019-06-19 11:14:11', 0),
(4, 1, 3, 2, '03', '2019-06-19', 1000, 6, NULL, 1, '2019-06-19 11:14:12', 0);

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
  `justificacion` varchar(300) COLLATE utf8_spanish_ci DEFAULT NULL,
  `empr_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_pedidos_materiales`
--

INSERT INTO `alm_pedidos_materiales` (`pema_id`, `fecha`, `motivo_rechazo`, `case_id`, `ortr_id`, `justificacion`, `empr_id`, `fec_alta`, `eliminado`) VALUES
(1, '2019-06-19', '', 61002, 0, 'Necesito probaaaaaaaaar', 6, '2019-06-19 10:32:31', 0);

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
(1, '2019-06-19 10:29:00', '000001', 6, 1, '2019-06-19 10:30:59', 0),
(2, '2019-06-19 11:12:00', '12222', 6, 1, '2019-06-19 11:14:11', 0);

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
-- Estructura de tabla para la tabla `sys_usuarios`
--

CREATE TABLE `sys_usuarios` (
  `usua_id` int(11) NOT NULL,
  `nick` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL,
  `pass` varchar(45) COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Volcado de datos para la tabla `sys_usuarios`
--

INSERT INTO `sys_usuarios` (`usua_id`, `nick`, `pass`) VALUES
(1, 'almacen', 'bpm'),
(2, 'solicitante', 'bpm');

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
-- Indices de la tabla `sys_usuarios`
--
ALTER TABLE `sys_usuarios`
  ADD PRIMARY KEY (`usua_id`);

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
  MODIFY `arti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alm_depositos`
--
ALTER TABLE `alm_depositos`
  MODIFY `depo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alm_deta_entrega_materiales`
--
ALTER TABLE `alm_deta_entrega_materiales`
  MODIFY `deen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alm_deta_pedidos_materiales`
--
ALTER TABLE `alm_deta_pedidos_materiales`
  MODIFY `depe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alm_deta_recepcion_materiales`
--
ALTER TABLE `alm_deta_recepcion_materiales`
  MODIFY `dere_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alm_entrega_materiales`
--
ALTER TABLE `alm_entrega_materiales`
  MODIFY `enma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alm_lotes`
--
ALTER TABLE `alm_lotes`
  MODIFY `lote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alm_pedidos_extraordinario`
--
ALTER TABLE `alm_pedidos_extraordinario`
  MODIFY `peex_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alm_pedidos_materiales`
--
ALTER TABLE `alm_pedidos_materiales`
  MODIFY `pema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alm_proveedores`
--
ALTER TABLE `alm_proveedores`
  MODIFY `prov_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alm_recepcion_materiales`
--
ALTER TABLE `alm_recepcion_materiales`
  MODIFY `rema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT de la tabla `sys_usuarios`
--
ALTER TABLE `sys_usuarios`
  MODIFY `usua_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `utl_tablas`
--
ALTER TABLE `utl_tablas`
  MODIFY `tabl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
