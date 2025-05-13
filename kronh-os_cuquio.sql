-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 23:17:41
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
-- Base de datos: `kronh-os_cuquio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `id` int(11) NOT NULL,
  `proveedor` text DEFAULT NULL,
  `producto` text NOT NULL,
  `cantidad` decimal(10,4) NOT NULL,
  `precio` float NOT NULL,
  `observaciones` text DEFAULT NULL,
  `ubicacion` text DEFAULT NULL,
  `referencia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bajas_op`
--

CREATE TABLE `bajas_op` (
  `id` int(11) NOT NULL,
  `id_op` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `accion` text NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargas_diesel`
--

CREATE TABLE `cargas_diesel` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `unidad` int(11) NOT NULL,
  `operador` text DEFAULT NULL,
  `kilometraje` int(11) NOT NULL,
  `folio_contado` text NOT NULL,
  `diesel_contado` float NOT NULL,
  `importe_diesel_contado` float NOT NULL,
  `adblue_contado` float NOT NULL,
  `importe_adblue_contado` float NOT NULL,
  `folio_credito` text NOT NULL,
  `diesel_credito` float NOT NULL,
  `importe_diesel_credito` float NOT NULL,
  `adblue_credito` float NOT NULL,
  `importe_adblue_credito` float NOT NULL,
  `usuario_registra` text NOT NULL,
  `fecha_registra` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `error` text DEFAULT NULL,
  `tarifa` float DEFAULT NULL,
  `precio_diesel` float DEFAULT NULL,
  `precio_adblue` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`error`, `tarifa`, `precio_diesel`, `precio_adblue`) VALUES
('0', 0, 24.4, 50.3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deshabilitacion`
--

CREATE TABLE `deshabilitacion` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_unidad` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `hora_registro` time DEFAULT NULL,
  `visual` int(11) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `division` text DEFAULT NULL,
  `prefijo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `division`
--

INSERT INTO `division` (`id`, `division`, `prefijo`) VALUES
(1, 'GENERAL', 'G'),
(2, 'CUQUIO', 'C'),
(3, 'YAHUALICA', 'Y');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `empresa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `empresa`) VALUES
(1, 'SERVICIO EXPRESS S.A. DE C.V.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exec_bitacora`
--

CREATE TABLE `exec_bitacora` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `accion` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_memory` int(11) DEFAULT NULL,
  `id_unidad` int(11) DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nombre_grupo` text NOT NULL,
  `nombre_menu` text NOT NULL,
  `directorio` text NOT NULL,
  `sub_directorio` text DEFAULT NULL,
  `archivo` text NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `nombre_grupo`, `nombre_menu`, `directorio`, `sub_directorio`, `archivo`, `descripcion`) VALUES
(1, 'Administrador', 'Menú y permisos', 'menu', NULL, 'menu', 'Permite crear elementos del menu y asignarlos a los usuarios'),
(2, 'Administrador', 'SQL', 'menu', 'forms', 'ejecucion', 'Permite la realización de consultas mediante códigos SOL'),
(3, 'Operacion', 'Control de diésel', 'operacion', NULL, 'diesel', ''),
(4, 'Operacion', 'Constantes', 'operacion', NULL, 'constantes', 'Permite definir las constantes para la plataforma (precio de diésel, adblue, etc.).'),
(5, 'Unidad', 'Control de unidades', 'unidad', NULL, 'control_unidades', ''),
(6, 'Unidad', 'Historial de unidades fuera de ruta', 'unidad', 'forms', 'bitacora_inhabilitadas', ''),
(7, 'Administrador', 'Agregar un puesto', 'usuario', 'forms', 'puesto', ''),
(8, 'Administrador', 'Control de usuarios', 'usuario', NULL, 'control_usuarios', ''),
(9, 'Administrador', 'Logs de errores', 'menu', 'queries', 'listado_logs', 'Permite visualizar y leer los logs de errores'),
(10, 'Operador', 'Control de operadores', 'operador', NULL, 'control_operador', 'Permite el registro y gestión de los operadores, así como la consulta de sus datos específicos'),
(11, 'Almacen', 'Control de almacen', 'almacen', NULL, 'almacen', 'Permite registrar elementos e interactuar con ellos'),
(12, 'Reporte', 'Reporte de almacen', 'reporte', 'forms', 'almacen', 'Permite consultar el reporte de almacen de una fecha especifica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_almacen`
--

CREATE TABLE `movimientos_almacen` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nota` text NOT NULL COMMENT 'nota o folio de entrada o salida',
  `cantidad` decimal(11,4) NOT NULL,
  `precio` float NOT NULL,
  `destino` text NOT NULL,
  `observacion` text NOT NULL,
  `f_movimiento` date NOT NULL,
  `f_registro` date NOT NULL,
  `h_registro` time NOT NULL,
  `tipo_movimiento` int(11) NOT NULL COMMENT '1:entrada, 2:salida',
  `usuario` text NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tipo` text DEFAULT NULL,
  `r_social` text DEFAULT NULL,
  `importe` float DEFAULT NULL,
  `concepto` text DEFAULT NULL,
  `unidad` text DEFAULT NULL,
  `t_referencia` text DEFAULT NULL,
  `token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores`
--

CREATE TABLE `operadores` (
  `id` int(11) NOT NULL,
  `nombre` text DEFAULT NULL,
  `apellido` text DEFAULT NULL,
  `clave` text DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `division` int(11) DEFAULT NULL,
  `f_ingreso` date DEFAULT NULL,
  `visual` int(11) DEFAULT 1,
  `photo` text DEFAULT NULL,
  `f_reingreso` date DEFAULT NULL,
  `telefono` text DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `cuenta_siniestralidad` float DEFAULT 0,
  `num_siniestros` int(11) DEFAULT 0,
  `cobro_infraccion` float DEFAULT 0,
  `recuperacion_infraccion` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id` int(11) NOT NULL,
  `puesto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`id`, `puesto`) VALUES
(1, 'PROGRAMADOR'),
(2, 'COORDINADOR ADMINISTRATIVO'),
(3, 'OPERADOR'),
(4, 'CONTABILIDAD'),
(5, 'RECURSOS HUMANOS'),
(6, 'ALMACEN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `id` int(11) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `n_motor` text DEFAULT NULL,
  `niv` text DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `division` int(11) DEFAULT NULL,
  `f_ingreso` date DEFAULT NULL,
  `f_baja` date DEFAULT NULL,
  `placas` text DEFAULT NULL,
  `ano` text DEFAULT NULL,
  `visual` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text DEFAULT NULL,
  `apellido` text DEFAULT NULL,
  `correo` text DEFAULT NULL,
  `clave` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `telefono` text DEFAULT NULL,
  `puesto` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `division` int(11) DEFAULT NULL,
  `f_ingreso` date DEFAULT NULL,
  `token` text DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `conexion` text DEFAULT NULL,
  `permisos` text NOT NULL,
  `visual` int(11) DEFAULT NULL,
  `f_registro` text NOT NULL,
  `usuario_registra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `correo`, `clave`, `password`, `telefono`, `puesto`, `admin`, `empresa`, `division`, `f_ingreso`, `token`, `photo`, `conexion`, `permisos`, `visual`, `f_registro`, `usuario_registra`) VALUES
(1, 'HECTOR MANUEL', 'GARCIA ESCOBAR', 'hmge6696@gmail.com', 'hmge', 'd1ff1ec86b62cd5f3903ff19c3a326b2', '4811286240', 1, 1, 1, 1, '2019-09-21', 'c31667074eccbba1fdec49f22804df1b', 'common.png', '0', '11||1!!2!!7!!8!!9!!11!!3!!4!!10!!12!!5!!6||1', 1, '', 0),
(2, 'ERICK RICARDO', 'TRUJILLO PEREZ', 'N/A', 'LTRUJILLO', '87ab0c1fa84903695ba2315d9b36c9d8', '3315328514', 2, NULL, 1, 1, '2023-11-08', '627771a890cbe52829c2cb156c91187f', 'common.png', NULL, '6||||0', 1, '2023-11-08', 1),
(3, 'ANA KAREN', 'PADILLA MENDEZ', 'N/A', 'karenpadi', 'fccb4dd286c74b400a130cb5a055e29e', '3339036414', 2, NULL, 1, 1, '2024-07-09', '42be6ad125c79d322c46abaf2106bf77', 'common.png', NULL, '18||||0', 1, '2024-07-09', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bajas_op`
--
ALTER TABLE `bajas_op`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargas_diesel`
--
ALTER TABLE `cargas_diesel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deshabilitacion`
--
ALTER TABLE `deshabilitacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `exec_bitacora`
--
ALTER TABLE `exec_bitacora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_almacen`
--
ALTER TABLE `movimientos_almacen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bajas_op`
--
ALTER TABLE `bajas_op`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargas_diesel`
--
ALTER TABLE `cargas_diesel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deshabilitacion`
--
ALTER TABLE `deshabilitacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `exec_bitacora`
--
ALTER TABLE `exec_bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `movimientos_almacen`
--
ALTER TABLE `movimientos_almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operadores`
--
ALTER TABLE `operadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `unidades`
--
ALTER TABLE `unidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
