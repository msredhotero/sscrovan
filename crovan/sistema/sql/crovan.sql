-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-08-2017 a las 15:16:01
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `crovan`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbclientes`
--

CREATE TABLE IF NOT EXISTS `dbclientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombrecompleto` varchar(120) NOT NULL,
  `cuil` varchar(11) DEFAULT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `observaciones` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`idcliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `dbclientes`
--

INSERT INTO `dbclientes` (`idcliente`, `nombrecompleto`, `cuil`, `dni`, `direccion`, `telefono`, `email`, `observaciones`) VALUES
(1, 'Cliente', '1111111', '1111111', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbdetallepreventas`
--

CREATE TABLE IF NOT EXISTS `dbdetallepreventas` (
  `iddetallepreventa` int(11) NOT NULL AUTO_INCREMENT,
  `refventas` int(11) NOT NULL,
  `refproductos` int(11) NOT NULL,
  `cantidad` decimal(18,2) NOT NULL,
  `costo` decimal(18,2) NOT NULL,
  `precio` decimal(18,2) NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`iddetallepreventa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbdetalleventas`
--

CREATE TABLE IF NOT EXISTS `dbdetalleventas` (
  `iddetalleventa` int(11) NOT NULL AUTO_INCREMENT,
  `refventas` int(11) NOT NULL,
  `refproductos` int(11) NOT NULL,
  `cantidad` decimal(18,2) NOT NULL,
  `costo` decimal(18,2) NOT NULL,
  `precio` decimal(18,2) NOT NULL,
  `total` decimal(18,2) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`iddetalleventa`),
  KEY `fk_detalleventa_producto_idx` (`refproductos`),
  KEY `fk_detalleventa_venta_idx` (`refventas`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbproductos`
--

CREATE TABLE IF NOT EXISTS `dbproductos` (
  `idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) DEFAULT NULL,
  `codigobarra` varchar(45) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock` smallint(6) DEFAULT NULL,
  `stockmin` smallint(6) DEFAULT NULL,
  `preciocosto` decimal(8,2) DEFAULT NULL,
  `precioventa` decimal(8,2) DEFAULT NULL,
  `preciodescuento` decimal(8,2) DEFAULT NULL,
  `utilidad` decimal(8,2) DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `refcategorias` int(11) DEFAULT NULL,
  `tipoimagen` varchar(15) DEFAULT NULL,
  `unidades` smallint(6) DEFAULT '1',
  `descripcion` varchar(300) DEFAULT NULL,
  `activo` bit(1) DEFAULT NULL,
  `refproveedores` int(11) DEFAULT NULL,
  `capacidad` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `dbproductos`
--

INSERT INTO `dbproductos` (`idproducto`, `codigo`, `codigobarra`, `nombre`, `stock`, `stockmin`, `preciocosto`, `precioventa`, `preciodescuento`, `utilidad`, `imagen`, `refcategorias`, `tipoimagen`, `unidades`, `descripcion`, `activo`, `refproveedores`, `capacidad`) VALUES
(4, 'PRO000004', '34234234', 'Barriles Euro 50 Ltrs', 20, 10, '600.00', '1500.00', '1400.00', '900.00', '', 1, '', 1, 'Barriles Euro 50 Ltrs', b'1', 1, '50'),
(5, 'PRO000005', '684984', 'Barriles Euro 30 Ltrs', 60, 20, '800.00', '1900.00', '1600.00', '1100.00', '', 1, '', 1, 'Barriles Euro 30 litros', b'1', 1, '30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbpromodetalle`
--

CREATE TABLE IF NOT EXISTS `dbpromodetalle` (
  `idpromodetalle` int(11) NOT NULL AUTO_INCREMENT,
  `refpromos` int(11) NOT NULL,
  `refproductos` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`idpromodetalle`),
  KEY `fk_promodetalle_promo_idx` (`refpromos`),
  KEY `fk_promodetalle_productos_idx` (`refproductos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbpromos`
--

CREATE TABLE IF NOT EXISTS `dbpromos` (
  `idpromo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `vigenciadesde` date NOT NULL,
  `vigenciahasta` date NOT NULL,
  `descuento` decimal(18,2) NOT NULL,
  PRIMARY KEY (`idpromo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbproveedores`
--

CREATE TABLE IF NOT EXISTS `dbproveedores` (
  `idproveedor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `cuit` varchar(11) DEFAULT NULL,
  `dni` varchar(8) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `observacionces` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idproveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `dbproveedores`
--

INSERT INTO `dbproveedores` (`idproveedor`, `nombre`, `cuit`, `dni`, `direccion`, `telefono`, `celular`, `email`, `observacionces`) VALUES
(1, 'Marcos', '20315524661', '31552466', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbusuarios`
--

CREATE TABLE IF NOT EXISTS `dbusuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `refroles` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nombrecompleto` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idusuario`),
  KEY `fk_dbusuarios_tbroles1_idx` (`refroles`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `dbusuarios`
--

INSERT INTO `dbusuarios` (`idusuario`, `usuario`, `password`, `refroles`, `email`, `nombrecompleto`) VALUES
(1, 'marcos', 'marcos', 3, 'msredhotero@msn.com', 'Saupurein Marcos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbventas`
--

CREATE TABLE IF NOT EXISTS `dbventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `reftipopago` int(11) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(18,2) DEFAULT NULL,
  `usuario` varchar(60) DEFAULT NULL,
  `cancelado` bit(1) DEFAULT NULL,
  `refclientes` int(11) DEFAULT NULL,
  `descuento` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`idventa`),
  KEY `fk_Compra_TipoDocumento1_idx` (`reftipopago`),
  KEY `fk_venta_cliente_idx` (`refclientes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `idfoto` int(11) NOT NULL AUTO_INCREMENT,
  `refproyecto` int(11) NOT NULL,
  `refuser` int(11) NOT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `principal` bit(1) DEFAULT NULL,
  PRIMARY KEY (`idfoto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `images`
--

INSERT INTO `images` (`idfoto`, `refproyecto`, `refuser`, `imagen`, `type`, `principal`) VALUES
(1, 3, 0, '2152509_orig.gif', 'image/gif', NULL),
(2, 4, 0, 'Capture0011.jpg', 'image/jpeg', NULL),
(3, 5, 0, 'Capture0053.jpg', 'image/jpeg', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predio_menu`
--

CREATE TABLE IF NOT EXISTS `predio_menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Orden` smallint(6) DEFAULT NULL,
  `hover` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `permiso` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idmenu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=30 ;

--
-- Volcado de datos para la tabla `predio_menu`
--

INSERT INTO `predio_menu` (`idmenu`, `url`, `icono`, `nombre`, `Orden`, `hover`, `permiso`) VALUES
(1, '../index.php', 'icodashboard', 'Dashboard', 1, NULL, 'Empleado, Administrador, SuperAdmin'),
(5, '../productos/', 'icoproductos', 'Productos', 2, NULL, 'Empleado, Administrador, SuperAdmin'),
(6, '../proveedores/', 'icocontratos', 'Proveedores', 6, NULL, 'Empleado, Administrador, SuperAdmin'),
(7, '../reportes/', 'icoreportes', 'Reportes', 14, NULL, 'Empleado, Administrador, SuperAdmin'),
(8, '../logout.php', 'icosalir', 'Salir', 30, NULL, 'Empleado, Administrador, SuperAdmin'),
(9, '../configuraciones/', 'icoconfiguracion', 'Configuraciones', 12, NULL, 'Empleado, Administrador, SuperAdmin'),
(15, '../categorias/', 'icozonas', 'Categorias', 8, NULL, 'Empleado, Administrador, SuperAdmin'),
(25, '../estadisticas/', 'icochart', 'Estadisticas', 10, NULL, 'Administrador'),
(27, '../promos/', 'icozonas', 'Promociones', 13, NULL, 'SuperAdmin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcategorias`
--

CREATE TABLE IF NOT EXISTS `tbcategorias` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `esegreso` bit(1) NOT NULL DEFAULT b'0',
  `activo` bit(1) DEFAULT NULL,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `tbcategorias`
--

INSERT INTO `tbcategorias` (`idcategoria`, `descripcion`, `esegreso`, `activo`) VALUES
(1, 'Barriles', b'0', b'1'),
(2, 'Minikegs', b'0', b'1'),
(3, 'Accesorios', b'0', b'1'),
(4, 'Equipos de Coccion', b'0', b'1'),
(5, 'Canillas', b'0', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbconfiguracion`
--

CREATE TABLE IF NOT EXISTS `tbconfiguracion` (
  `idconfiguracion` int(11) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(130) COLLATE utf8_spanish_ci NOT NULL,
  `cuit` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(220) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `localidad` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigopostal` varchar(6) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idconfiguracion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbestados`
--

CREATE TABLE IF NOT EXISTS `tbestados` (
  `idestado` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(29) NOT NULL,
  `icono` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`idestado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `tbestados`
--

INSERT INTO `tbestados` (`idestado`, `estado`, `icono`) VALUES
(1, 'Cargado', NULL),
(2, 'En Curso', NULL),
(3, 'Finalizado', NULL),
(4, 'Finalizado - Incompleto', NULL),
(5, 'Cancelado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbmeses`
--

CREATE TABLE IF NOT EXISTS `tbmeses` (
  `mes` int(11) NOT NULL,
  `nombremes` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`mes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbmeses`
--

INSERT INTO `tbmeses` (`mes`, `nombremes`) VALUES
(1, 'Enero'),
(2, 'Febrero'),
(3, 'Marzo'),
(4, 'Abril'),
(5, 'Mayo'),
(6, 'Julio'),
(7, 'Junio'),
(8, 'Agosto'),
(9, 'Septiembre'),
(10, 'Octubre'),
(11, 'Noviembre'),
(12, 'Diciembre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbroles`
--

CREATE TABLE IF NOT EXISTS `tbroles` (
  `idrol` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  `activo` bit(1) NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `tbroles`
--

INSERT INTO `tbroles` (`idrol`, `descripcion`, `activo`) VALUES
(1, 'Administrador', b'1'),
(2, 'Empleado', b'1'),
(3, 'SuperAdmin', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipopago`
--

CREATE TABLE IF NOT EXISTS `tbtipopago` (
  `idtipopago` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(80) NOT NULL,
  PRIMARY KEY (`idtipopago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `tbtipopago`
--

INSERT INTO `tbtipopago` (`idtipopago`, `descripcion`) VALUES
(1, 'Contado'),
(2, 'Debito'),
(3, 'Credito'),
(4, 'Cheques'),
(5, 'Cuenta');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dbdetalleventas`
--
ALTER TABLE `dbdetalleventas`
  ADD CONSTRAINT `fk_detalleventa_producto` FOREIGN KEY (`refproductos`) REFERENCES `dbproductos` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalleventa_venta` FOREIGN KEY (`refventas`) REFERENCES `dbventas` (`idventa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dbpromodetalle`
--
ALTER TABLE `dbpromodetalle`
  ADD CONSTRAINT `fk_promodetalle_productos` FOREIGN KEY (`refproductos`) REFERENCES `dbproductos` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_promodetalle_promo` FOREIGN KEY (`refpromos`) REFERENCES `dbpromos` (`idpromo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dbventas`
--
ALTER TABLE `dbventas`
  ADD CONSTRAINT `fk_compra_tipopago` FOREIGN KEY (`reftipopago`) REFERENCES `tbtipopago` (`idtipopago`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`refclientes`) REFERENCES `dbclientes` (`idcliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
