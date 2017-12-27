-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-12-2017 a las 15:38:01
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
-- Estructura de tabla para la tabla `dbcategoriasespecificacion`
--

CREATE TABLE IF NOT EXISTS `dbcategoriasespecificacion` (
  `idcategoriaespecificacion` int(11) NOT NULL AUTO_INCREMENT,
  `refcategorias` int(11) NOT NULL,
  `refgrupoespecificaciones` int(11) NOT NULL,
  PRIMARY KEY (`idcategoriaespecificacion`),
  KEY `fk_proesp_categorias_idx` (`refcategorias`),
  KEY `fk_catesp_grupo_idx` (`refgrupoespecificaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `dbcategoriasespecificacion`
--

INSERT INTO `dbcategoriasespecificacion` (`idcategoriaespecificacion`, `refcategorias`, `refgrupoespecificaciones`) VALUES
(7, 1, 1),
(8, 1, 3),
(9, 2, 2),
(10, 2, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbgrupoespecificaciones`
--

CREATE TABLE IF NOT EXISTS `dbgrupoespecificaciones` (
  `idgrupoespecificaion` int(11) NOT NULL AUTO_INCREMENT,
  `grupo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idgrupoespecificaion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `dbgrupoespecificaciones`
--

INSERT INTO `dbgrupoespecificaciones` (`idgrupoespecificaion`, `grupo`) VALUES
(1, 'Marca'),
(2, 'Canillas'),
(3, 'Capacidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbproductoespecificaciones`
--

CREATE TABLE IF NOT EXISTS `dbproductoespecificaciones` (
  `iddbproductoespecificacion` int(11) NOT NULL AUTO_INCREMENT,
  `refproductos` int(11) NOT NULL,
  `refespecificacionesproducto` int(11) NOT NULL,
  `valor` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`iddbproductoespecificacion`),
  KEY `fk_pe_productos_idx` (`refproductos`),
  KEY `fk_pe_especificaciones_idx` (`refespecificacionesproducto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `dbproductoespecificaciones`
--

INSERT INTO `dbproductoespecificaciones` (`iddbproductoespecificacion`, `refproductos`, `refespecificacionesproducto`, `valor`) VALUES
(8, 4, 1, '1'),
(9, 4, 8, '1'),
(10, 5, 1, '1'),
(11, 5, 9, '1'),
(12, 6, 5, '1'),
(13, 6, 12, '1');

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
  `marca` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idproducto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `dbproductos`
--

INSERT INTO `dbproductos` (`idproducto`, `codigo`, `codigobarra`, `nombre`, `stock`, `stockmin`, `preciocosto`, `precioventa`, `preciodescuento`, `utilidad`, `imagen`, `refcategorias`, `tipoimagen`, `unidades`, `descripcion`, `activo`, `refproveedores`, `capacidad`, `marca`) VALUES
(4, 'PRO000004', '34234234', 'Barriles Euro 50 Ltrs', 20, 10, '600.00', '1500.00', '1400.00', '900.00', '', 1, '', 1, 'Vestibulum id sem facilisis, dignissim risus ac, vestibulum ante. Curabitur tincidunt eros quis porttitor pretium. Sed eleifend ut nisi eget eleifend. Maecenas sed metus sit amet nulla placerat eleifend. Vivamus non suscipit est. Vivamus quis enim tortor. Aenean in lorem eget tortor suscipit blandit', b'1', 1, '50', ''),
(5, 'PRO000005', '684984', 'Barriles Euro 30 Ltrs', 60, 20, '800.00', '1900.00', '1600.00', '1100.00', '', 1, '', 1, 'Suspendisse ac nunc mauris. Duis eleifend cursus sem id vulputate. Nam non lorem eu ligula fermentum ultrices. Curabitur rutrum justo sit amet velit hendrerit cursus. Nunc lacus orci, eleifend in feugiat at, pulvinar non massa. Sed hendrerit fringilla purus sit amet volutpat. Morbi imperdiet eu nibh', b'1', 1, '30', ''),
(6, 'PRO000006', '23423423423423', 'Minikeg 4L Con Canilla Metálica', 10, 2, '3000.00', '3950.00', '3750.00', '950.00', '', 2, '', 1, 'MINI KEG 4L\r\n\r\nCANILLA ACERO INOXIDABLE\r\nCONECTOR ACERO INOXIDABLE\r\nREGULADOR DE PRESIÓN DE PRECISIÓN\r\n\r\n* EL EQUIPO NO INCLUYE CAPSULAS DE CO2\r\n\r\nIDEAL PARA TIRADO DE CERVEZA EN TU CASA. \r\n\r\nLLEVA EL BAR A TU CASA CON EL MINIKEG!!\r\n\r\nPARA USARLO, SE ABRE LA TAPA A ROSCA, SE LLENA DE TU CERVEZA PREF', b'1', 1, '4', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `images`
--

INSERT INTO `images` (`idfoto`, `refproyecto`, `refuser`, `imagen`, `type`, `principal`) VALUES
(1, 3, 0, '2152509_orig.gif', 'image/gif', NULL),
(2, 4, 0, 'Capture0011.jpg', 'image/jpeg', NULL),
(3, 5, 0, 'Capture0053.jpg', 'image/jpeg', NULL),
(4, 6, 0, 'minikegs.jpg', 'image/jpeg', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=28 ;

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
-- Estructura de tabla para la tabla `tbespecificacionesproducto`
--

CREATE TABLE IF NOT EXISTS `tbespecificacionesproducto` (
  `idespecificacionproducto` int(11) NOT NULL AUTO_INCREMENT,
  `refgrupoespecificaiones` int(11) NOT NULL,
  `especificacion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `reftipovalor` int(11) DEFAULT NULL,
  PRIMARY KEY (`idespecificacionproducto`),
  KEY `fk_esp_grupo_idx` (`refgrupoespecificaiones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `tbespecificacionesproducto`
--

INSERT INTO `tbespecificacionesproducto` (`idespecificacionproducto`, `refgrupoespecificaiones`, `especificacion`, `reftipovalor`) VALUES
(1, 1, 'Euro Standard', 3),
(2, 1, 'Dim Standard', 3),
(3, 1, 'US Standard', 3),
(4, 1, 'Cornelius', 3),
(5, 2, 'Con canilla metalica', 3),
(6, 2, 'Con canilla plástica', 3),
(7, 2, 'Sin canilla', 3),
(8, 3, '50', 1),
(9, 3, '30', 1),
(10, 3, '20', 1),
(11, 3, '10', 1),
(12, 3, '4', 1);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipovalor`
--

CREATE TABLE IF NOT EXISTS `tbtipovalor` (
  `idtipovalor` int(11) NOT NULL AUTO_INCREMENT,
  `tipovalor` varchar(45) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idtipovalor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `tbtipovalor`
--

INSERT INTO `tbtipovalor` (`idtipovalor`, `tipovalor`) VALUES
(1, 'integer'),
(2, 'varchar'),
(3, 'bit');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dbcategoriasespecificacion`
--
ALTER TABLE `dbcategoriasespecificacion`
  ADD CONSTRAINT `fk_catesp_grupo` FOREIGN KEY (`refgrupoespecificaciones`) REFERENCES `dbgrupoespecificaciones` (`idgrupoespecificaion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_catesp_cate` FOREIGN KEY (`refcategorias`) REFERENCES `tbcategorias` (`idcategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dbdetalleventas`
--
ALTER TABLE `dbdetalleventas`
  ADD CONSTRAINT `fk_detalleventa_producto` FOREIGN KEY (`refproductos`) REFERENCES `dbproductos` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalleventa_venta` FOREIGN KEY (`refventas`) REFERENCES `dbventas` (`idventa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dbproductoespecificaciones`
--
ALTER TABLE `dbproductoespecificaciones`
  ADD CONSTRAINT `fk_pe_productos` FOREIGN KEY (`refproductos`) REFERENCES `dbproductos` (`idproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pe_especificaciones` FOREIGN KEY (`refespecificacionesproducto`) REFERENCES `tbespecificacionesproducto` (`idespecificacionproducto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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

--
-- Filtros para la tabla `tbespecificacionesproducto`
--
ALTER TABLE `tbespecificacionesproducto`
  ADD CONSTRAINT `fk_esp_grupo` FOREIGN KEY (`refgrupoespecificaiones`) REFERENCES `dbgrupoespecificaciones` (`idgrupoespecificaion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
