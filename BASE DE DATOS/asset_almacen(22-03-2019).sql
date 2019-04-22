-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-04-2019 a las 21:37:29
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
-- Base de datos: `asset_almacen_v2`
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
  `estado_id` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `unidad_id` int(11) NOT NULL,
  `empr_id` int(11) NOT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_articulos`
--

INSERT INTO `alm_articulos` (`arti_id`, `barcode`, `descripcion`, `costo`, `es_caja`, `cantidad_caja`, `punto_pedido`, `estado_id`, `unidad_id`, `empr_id`, `eliminado`) VALUES
(30, 'AAA_111', 'Descripcion A', '100.00', 1, 100, 100, '1', 19, 1, 0),
(31, 'BBB_222', 'Descripcion B', '0.00', 0, 0, 200, '1', 15, 1, 0),
(34, 'CCC_333', 'Descripcion C', '0.00', 1, 3333, 333, '1', 19, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_depositos`
--

CREATE TABLE `alm_depositos` (
  `depo_id` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `GPS` varchar(255) DEFAULT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0',
  `estado_id` int(11) DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `loca_id` varchar(255) DEFAULT NULL,
  `esta_id` varchar(255) DEFAULT NULL,
  `pais_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_depositos`
--

INSERT INTO `alm_depositos` (`depo_id`, `descripcion`, `direccion`, `GPS`, `eliminado`, `estado_id`, `empr_id`, `loca_id`, `esta_id`, `pais_id`) VALUES
(1, 'Deposito A', 'Direccion A', 'GPS A', 0, 1, 1, '1', '1', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_deta_entrega_materiales`
--

CREATE TABLE `alm_deta_entrega_materiales` (
  `deen_id` int(11) NOT NULL,
  `cantidad` double NOT NULL,
  `precio` double DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `enma_id` int(11) NOT NULL,
  `lote_id` int(11) NOT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_deta_entrega_materiales`
--

INSERT INTO `alm_deta_entrega_materiales` (`deen_id`, `cantidad`, `precio`, `empr_id`, `enma_id`, `lote_id`, `fec_alta`) VALUES
(1, 1, NULL, 1, 7, 5, '2019-04-22 09:50:27'),
(2, 10, NULL, 1, 8, 4, '2019-04-22 09:53:18'),
(3, 500, NULL, 1, 8, 6, '2019-04-22 09:53:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_deta_pedidos_materiales`
--

CREATE TABLE `alm_deta_pedidos_materiales` (
  `depe_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_entregado` date DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0',
  `pedi_id` int(11) NOT NULL,
  `arti_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  `lote_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_deta_recepcion_materiales`
--

INSERT INTO `alm_deta_recepcion_materiales` (`dere_id`, `cantidad`, `precio`, `empr_id`, `rema_id`, `lote_id`) VALUES
(1, 13, 0, 1, 17, 9),
(2, 13, 0, 1, 18, 9),
(3, 13, 0, 1, 19, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_entrega_materiales`
--

CREATE TABLE `alm_entrega_materiales` (
  `enma_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `solicitante` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `destino` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comprobante` int(255) DEFAULT NULL,
  `empr_id` int(11) NOT NULL,
  `ortr_id` int(11) DEFAULT NULL,
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_entrega_materiales`
--

INSERT INTO `alm_entrega_materiales` (`enma_id`, `fecha`, `solicitante`, `destino`, `comprobante`, `empr_id`, `ortr_id`, `fec_alta`) VALUES
(1, '2019-04-01', 'Fernando Leiva', 'Destino?', 111, 1, 1, '2019-04-22 09:22:59'),
(2, '2019-04-10', 'Fernando Leiva', '', 1111, 1, 2, '2019-04-22 09:22:59'),
(3, '2019-04-16', 'Fernando Leiva', '', 444, 1, 2, '2019-04-22 09:22:59'),
(4, '2019-04-26', 'Fernando Leiva', '', 111, 1, 1, '2019-04-22 09:22:59'),
(5, '2019-04-24', 'Fernando Leiva', '', 7777, 1, 1, '2019-04-22 09:24:16'),
(6, '2019-04-25', 'Fernando Leiva', '1', 8888, 1, 1, '2019-04-22 09:25:22'),
(7, '2019-04-27', 'Fernando Leiva', '', 999, 1, 1, '2019-04-22 09:45:30'),
(8, '2019-04-18', 'Fernando Leiva', '', 1010, 1, 1, '2019-04-22 09:53:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_lotes`
--

CREATE TABLE `alm_lotes` (
  `lote_id` int(11) NOT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `cantidad` varchar(255) DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0',
  `empr_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prov_id` int(10) NOT NULL,
  `arti_id` int(11) NOT NULL,
  `depo_id` int(11) NOT NULL,
  `estado_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_lotes`
--

INSERT INTO `alm_lotes` (`lote_id`, `codigo`, `fecha`, `cantidad`, `eliminado`, `empr_id`, `user_id`, `prov_id`, `arti_id`, `depo_id`, `estado_id`) VALUES
(4, 'COD_AAA', '2019-03-16', '1003', 0, 1, 1, 1, 30, 1, 1),
(5, 'COD_BBB', '2019-03-16', '999', 0, 1, 1, 1, 31, 1, 1),
(6, 'COD_CCC', '2019-03-16', '500', 0, 1, 1, 1, 34, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alm_pedidos_materiales`
--

CREATE TABLE `alm_pedidos_materiales` (
  `pema_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `ortr_id` int(11) NOT NULL,
  `empr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  `eliminado` tinyint(4) DEFAULT '0',
  `empr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alm_proveedores`
--

INSERT INTO `alm_proveedores` (`prov_id`, `nombre`, `cuit`, `domicilio`, `telefono`, `email`, `eliminado`, `empr_id`) VALUES
(1, 'Proveedor A', '000111', 'Domicilio A', '4211111', 'email1@', 0, 1);

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
(1, 30),
(1, 31),
(1, 34);

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
  `fec_alta` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alm_recepcion_materiales`
--

INSERT INTO `alm_recepcion_materiales` (`rema_id`, `fecha`, `comprobante`, `empr_id`, `prov_id`, `fec_alta`) VALUES
(1, '2019-04-24 11:09:10', '123', 1, 1, '2019-04-22 11:16:00'),
(2, '2019-04-24 12:50:34', '123', 1, 1, '2019-04-22 12:50:55'),
(3, '2019-04-10 12:55:37', '111', 1, 1, '2019-04-22 12:55:52'),
(4, '2019-04-26 12:55:37', '1114', 1, 1, '2019-04-22 12:59:13'),
(5, '2019-04-18 12:55:37', '555', 1, 1, '2019-04-22 13:00:28'),
(6, '2019-04-25 12:55:37', '444', 1, 1, '2019-04-22 13:05:53'),
(7, '2019-04-16 14:26:37', '666', 1, 1, '2019-04-22 14:26:49'),
(8, '2019-04-17 14:26:37', '777', 1, 1, '2019-04-22 14:30:47'),
(9, '2019-04-25 14:26:37', '999', 1, 1, '2019-04-22 14:36:06'),
(10, '2019-04-26 14:26:37', '555', 1, 1, '2019-04-22 14:50:09'),
(11, '2019-05-02 14:26:37', '123', 1, 1, '2019-04-22 14:52:28'),
(12, '2019-04-11 00:00:00', '010101', 1, 1, '2019-04-22 14:54:06'),
(13, '2019-04-27 00:00:00', '666', 1, 1, '2019-04-22 14:57:50'),
(14, '2019-04-24 00:00:00', '999', 1, 1, '2019-04-22 14:59:57'),
(15, '2019-04-26 00:00:00', '888', 1, 1, '2019-04-22 15:01:42'),
(16, '2019-05-04 00:00:00', '888123', 1, 1, '2019-04-22 15:05:32'),
(17, '2019-04-11 15:17:13', '1911', 1, 1, '2019-04-22 15:17:28'),
(18, '2019-04-11 15:17:13', '1911', 1, 1, '2019-04-22 15:19:49'),
(19, '2019-04-11 15:17:13', '1911', 1, 1, '2019-04-22 15:22:26');

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
  `lectura_programada` double DEFAULT NULL,
  `lectura_ejecutada` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `orden_trabajo`
--

INSERT INTO `orden_trabajo` (`id_orden`, `id_tarea`, `nro`, `fecha`, `fecha_program`, `fecha_inicio`, `fecha_entrega`, `fecha_terminada`, `fecha_aviso`, `fecha_entregada`, `descripcion`, `cliId`, `estado`, `id_usuario`, `id_usuario_a`, `id_usuario_e`, `id_sucursal`, `id_proveedor`, `id_solicitud`, `tipo`, `id_equipo`, `duracion`, `id_tareapadre`, `id_empresa`, `lectura_programada`, `lectura_ejecutada`) VALUES
(1, 36, '1', '2019-04-03', '2019-04-05 08:00:00', '2019-04-03 12:14:59', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 1, 'As', 1, 17, 1, 1, 0, 104, '2', 1, 60, 104, 1, 0, 0),
(2, 65, '1', '2019-04-03', '2019-04-11 14:00:00', '2019-04-03 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Cambiar Aceite de Diferencial Delantero y Trasero (cada 1000 hs según horometro útilizar aceite sae50).', 1, 'As', 1, 17, 1, 1, 0, 1, '3', 9, 50, 1, 1, 0, 0),
(3, 106, '1', '2019-04-04', '2019-04-11 14:30:00', '2019-04-04 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Sustituya el Elemento del Respiradero del Carter de Motor.', 1, 'As', 1, 17, 1, 1, 0, 3, '3', 26, 20, 3, 1, 0, 0),
(4, 1, '1', '2019-04-05', '2019-04-05 08:00:00', '2019-04-05 12:15:58', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'ruido en motor', 1, 'As', 1, 17, 1, 1, 2, 105, '2', 1, 60, 105, 1, 0, 0),
(5, 68, '1', '2019-04-05', '2019-04-05 08:00:00', '2019-04-05 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Inspección: Nivel de Aceite Hidráulico (controlar el nivel por medio del medidor visual. revisar cada 100 hs según horómetro).', 1, 'As', 1, 17, 1, 1, 0, 51, '4', 3, 10, 51, 1, 0, 0),
(6, 1, '1', '2019-04-09', '2019-04-09 13:00:00', '2019-04-09 11:40:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'DESGASTE EN CADENA - NECESITA CAMBIO', 1, 'As', 1, 17, 1, 1, 2, 108, '2', 70, 60, 108, 1, 0, 0),
(7, 1, '1', '2019-04-10', '2019-04-10 00:00:00', '2019-04-08 11:01:43', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'nueva solicitud de servicio', 1, 'C', 1, 1, 1, 1, 2, 106, '2', 5, 60, 106, 1, 0, 0),
(8, 19, '', '0000-00-00', '0000-00-00 00:00:00', '2019-04-10 00:00:00', '2019-04-10 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Apriete los Tornillos de los Pasadores de Expansión de los Cilindros.', 1, 'C', 1, 1, 0, -1, -1, 0, '1', 27, 0, NULL, 1, NULL, NULL);

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
  `f_solicitado` datetime NOT NULL,
  `f_sugerido` date NOT NULL,
  `hora_sug` time NOT NULL,
  `id_equipo` int(10) NOT NULL,
  `correctivo` int(10) DEFAULT NULL,
  `causa` varchar(255) CHARACTER SET latin1 NOT NULL,
  `observaciones` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET latin1 NOT NULL,
  `usrId` int(11) NOT NULL,
  `fecha_conformidad` date NOT NULL,
  `observ_conformidad` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `foto1` blob,
  `foto2` blob,
  `foto3` blob,
  `foto` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_empresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `solicitud_reparacion`
--

INSERT INTO `solicitud_reparacion` (`id_solicitud`, `numero`, `id_tipo`, `nivel`, `solicitante`, `f_solicitado`, `f_sugerido`, `hora_sug`, `id_equipo`, `correctivo`, `causa`, `observaciones`, `estado`, `usrId`, `fecha_conformidad`, `observ_conformidad`, `foto1`, `foto2`, `foto3`, `foto`, `id_empresa`) VALUES
(109, NULL, NULL, NULL, 'Fernando Leiva', '0000-00-00 00:00:00', '0000-00-00', '00:00:00', 0, NULL, '', NULL, '', 0, '0000-00-00', '', NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `utl_tablas`
--

CREATE TABLE `utl_tablas` (
  `tabl_id` int(11) NOT NULL,
  `tabla` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `valor` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `descripcion` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `eliminado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Volcado de datos para la tabla `utl_tablas`
--

INSERT INTO `utl_tablas` (`tabl_id`, `tabla`, `valor`, `descripcion`, `eliminado`) VALUES
(1, 'estado', 'AC', 'ACTIVO', 0),
(2, 'estado', 'AN', 'ANULADO', 0),
(3, 'estado', 'AS', 'ASIGNADO', 0),
(4, 'estado', 'CO', 'COMODATO', 0),
(5, 'estado', 'C', 'CURSO', 0),
(6, 'estado', 'E', 'ENTREGADO', 0),
(7, 'estado', 'IN', 'INACTIVO', 0),
(8, 'estado', 'P', 'PEDIDO', 0),
(9, 'estado', 'RE', 'REPARACION', 0),
(10, 'estado', 'S', 'SOLICITADO', 0),
(11, 'estado', 'RE', 'TAREA REALIZADA', 0),
(12, 'estado', 'T', 'TERMINADO', 0),
(13, 'estado', 'TE', 'TERMINADO PARCIAL', 0),
(14, 'estado', 'TR', 'TRANSITO', 0),
(15, 'unidad', 'KM', 'Kilomentros', 0),
(16, 'unidad', 'M', 'Metros', 0),
(17, 'unidad', 'CM', 'Centimentros', 0),
(19, 'unidad', 'UN', 'Unidad', 0),
(20, 'estado', 'SU', 'SUSPENDIDO', 0);

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
  ADD PRIMARY KEY (`deen_id`),
  ADD KEY `fk_alm_deta_ordeninsumos_alm_lote1` (`lote_id`),
  ADD KEY `fk_alm_deta_ordeninsumos_alm_orden_insumos1` (`enma_id`);

--
-- Indices de la tabla `alm_deta_pedidos_materiales`
--
ALTER TABLE `alm_deta_pedidos_materiales`
  ADD PRIMARY KEY (`depe_id`),
  ADD KEY `fk_alm_deta_nota_pedido_alm_articulos1` (`arti_id`),
  ADD KEY `fk_alm_deta_nota_pedido_alm_nota_pedido1` (`pedi_id`);

--
-- Indices de la tabla `alm_deta_recepcion_materiales`
--
ALTER TABLE `alm_deta_recepcion_materiales`
  ADD PRIMARY KEY (`dere_id`),
  ADD KEY `fk_alm_deta_remito_alm_remitos1` (`rema_id`);

--
-- Indices de la tabla `alm_entrega_materiales`
--
ALTER TABLE `alm_entrega_materiales`
  ADD PRIMARY KEY (`enma_id`);

--
-- Indices de la tabla `alm_lotes`
--
ALTER TABLE `alm_lotes`
  ADD PRIMARY KEY (`lote_id`),
  ADD KEY `fk_alm_lote_alm_proveedores_articulos1` (`prov_id`,`arti_id`);

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
  ADD PRIMARY KEY (`prov_id`,`arti_id`),
  ADD KEY `fk_alm_proveedores_has_alm_articulos_alm_articulos1` (`arti_id`);

--
-- Indices de la tabla `alm_recepcion_materiales`
--
ALTER TABLE `alm_recepcion_materiales`
  ADD PRIMARY KEY (`rema_id`),
  ADD KEY `fk_alm_remitos_alm_proveedores1` (`prov_id`);

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
  MODIFY `arti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `alm_depositos`
--
ALTER TABLE `alm_depositos`
  MODIFY `depo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alm_deta_entrega_materiales`
--
ALTER TABLE `alm_deta_entrega_materiales`
  MODIFY `deen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alm_deta_pedidos_materiales`
--
ALTER TABLE `alm_deta_pedidos_materiales`
  MODIFY `depe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alm_deta_recepcion_materiales`
--
ALTER TABLE `alm_deta_recepcion_materiales`
  MODIFY `dere_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `alm_entrega_materiales`
--
ALTER TABLE `alm_entrega_materiales`
  MODIFY `enma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `alm_lotes`
--
ALTER TABLE `alm_lotes`
  MODIFY `lote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `alm_pedidos_materiales`
--
ALTER TABLE `alm_pedidos_materiales`
  MODIFY `pema_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alm_proveedores`
--
ALTER TABLE `alm_proveedores`
  MODIFY `prov_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alm_recepcion_materiales`
--
ALTER TABLE `alm_recepcion_materiales`
  MODIFY `rema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `orden_trabajo`
--
ALTER TABLE `orden_trabajo`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `solicitud_reparacion`
--
ALTER TABLE `solicitud_reparacion`
  MODIFY `id_solicitud` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de la tabla `utl_tablas`
--
ALTER TABLE `utl_tablas`
  MODIFY `tabl_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alm_deta_entrega_materiales`
--
ALTER TABLE `alm_deta_entrega_materiales`
  ADD CONSTRAINT `fk_alm_deta_ordeninsumos_alm_lote1` FOREIGN KEY (`lote_id`) REFERENCES `alm_lotes` (`lote_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alm_deta_ordeninsumos_alm_orden_insumos1` FOREIGN KEY (`enma_id`) REFERENCES `alm_entrega_materiales` (`enma_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alm_deta_pedidos_materiales`
--
ALTER TABLE `alm_deta_pedidos_materiales`
  ADD CONSTRAINT `fk_alm_deta_nota_pedido_alm_articulos1` FOREIGN KEY (`arti_id`) REFERENCES `alm_articulos` (`arti_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alm_deta_nota_pedido_alm_nota_pedido1` FOREIGN KEY (`pedi_id`) REFERENCES `alm_pedidos_materiales` (`pema_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alm_deta_recepcion_materiales`
--
ALTER TABLE `alm_deta_recepcion_materiales`
  ADD CONSTRAINT `fk_alm_deta_remito_alm_remitos1` FOREIGN KEY (`rema_id`) REFERENCES `alm_recepcion_materiales` (`rema_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alm_lotes`
--
ALTER TABLE `alm_lotes`
  ADD CONSTRAINT `fk_alm_lote_alm_proveedores_articulos1` FOREIGN KEY (`prov_id`,`arti_id`) REFERENCES `alm_proveedores_articulos` (`prov_id`, `arti_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alm_proveedores_articulos`
--
ALTER TABLE `alm_proveedores_articulos`
  ADD CONSTRAINT `fk_alm_proveedores_has_alm_articulos_alm_articulos1` FOREIGN KEY (`arti_id`) REFERENCES `alm_articulos` (`arti_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alm_proveedores_has_alm_articulos_alm_proveedores1` FOREIGN KEY (`prov_id`) REFERENCES `alm_proveedores` (`prov_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alm_recepcion_materiales`
--
ALTER TABLE `alm_recepcion_materiales`
  ADD CONSTRAINT `fk_alm_remitos_alm_proveedores1` FOREIGN KEY (`prov_id`) REFERENCES `alm_proveedores` (`prov_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
