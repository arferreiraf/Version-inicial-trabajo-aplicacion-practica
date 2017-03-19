-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2016 a las 00:20:44
-- Versión del servidor: 10.1.10-MariaDB
-- Versión de PHP: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bodega`
--
CREATE database bodega;
use bodega;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `idEstado` int(11) NOT NULL,
  `estados` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`idEstado`, `estados`) VALUES
(1, 'activo'),
(2, 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id_ingreso` int(11) NOT NULL,
  `fecha_ingreso` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion_ingreso` varchar(250) DEFAULT NULL,
  `id_producto` int(20) NOT NULL,
  `cantidad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `fecha_ingreso`, `descripcion_ingreso`, `id_producto`, `cantidad`) VALUES
(4, '2016-10-26 03:31:37', 'ingreso pedido ccu', 66, 1000),
(5, '2016-10-26 03:35:58', 'ccu', 66, 1000),
(6, '2016-10-27 04:56:47', 'recepción ok factura vence 13-10', 63, 2),
(7, '2016-10-27 05:12:48', 'recepcion ok productos i', 67, 10),
(8, '2016-10-27 05:19:03', 'productos ingresados ok', 66, 28700),
(9, '2016-10-27 05:21:03', 'Sin novedad', 66, 2),
(10, '2016-10-27 05:31:53', 'Situado en estanteria e1', 66, 2),
(12, '2016-10-28 00:11:32', 'se recibio producto', 29, 5),
(13, '2016-10-28 02:36:13', 'entrega pedido jb ok', 63, 2),
(14, '2016-10-28 03:16:40', 'factura pagada', 3, 5),
(15, '2016-10-28 15:03:37', 'se recibepedido ok', 63, 1),
(16, '2016-10-28 17:40:39', 'se recibe pedido jb', 63, 5);

--
-- Disparadores `ingresos`
--
DELIMITER $$
CREATE TRIGGER `actualizar_stock` BEFORE UPDATE ON `ingresos` FOR EACH ROW UPDATE productos SET stock_producto = (stock_producto - OLD.cantidad)+ NEW.cantidad WHERE idProductos = OLD.id_producto
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `suma_stock` AFTER INSERT ON `ingresos` FOR EACH ROW UPDATE productos set stock_producto = stock_producto+ NEW.cantidad WHERE idProductos = NEW.id_producto
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privilegios`
--

CREATE TABLE `privilegios` (
  `idprivilegios` int(11) NOT NULL,
  `nombre_privilegios` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `privilegios`
--

INSERT INTO `privilegios` (`idprivilegios`, `nombre_privilegios`) VALUES
(1, 'administrador'),
(2, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProductos` int(11) NOT NULL,
  `nombre_producto` varchar(45) DEFAULT NULL,
  `stock_producto` int(11) DEFAULT NULL,
  `Precio` int(11) DEFAULT NULL,
  `descripcion_producto` varchar(250) DEFAULT NULL,
  `Estado_idEstado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProductos`, `nombre_producto`, `stock_producto`, `Precio`, `descripcion_producto`, `Estado_idEstado`) VALUES
(2, 'Pan ideal integral', 2, 1600, 'Pan de molde 800gramos', 1),
(3, 'Coca Cola Zero 2L', 1, 1180, 'Bebida azucarada carbonatada de fantasia', 1),
(8, 'queso colun gauda 250grs', 1, 2000, 'laminado al vacío', 1),
(11, 'Mermelada Watts', 2, 3, '250cc', 1),
(26, 'Mayonesa Jb', 1, 1200, 'Light', 1),
(27, 'Zuko', 2, 120, 'Jugo de manzana sobre', 1),
(28, 'Sprim', 2, 230, 'Sobre 30grms sabor manzana', 1),
(29, 'Arroz Tucapel', 1, 700, '500grs', 1),
(30, 'Harina', 1, 2345, '500k', 1),
(31, 'yogurt', 1, 130, 'colun light', 1),
(34, 'globos', 2, 123, 'inflables', 1),
(47, 'Palta', 50, 3000, 'Hass ', 1),
(49, 'Ketchup', 11, 1000, 'Malloa 1k', 1),
(50, 'Fanta', 12, 1222, '3l', 1),
(52, 'Mayonesa Kraft', 1, 2000, '800Grs', 1),
(59, 'manzana', 1, 1000, 'kilo', 1),
(60, 'colun', 444, 3, 'leche 900', 2),
(61, 'Pure', 1, 1000, 'Maggi 250grs', 2),
(62, 'Papas fritas', 22, 12121, 'ded', 1),
(63, 'Aji', 1, 100, 'Jb de 200grs', 1),
(66, 'Queso ', 26, 2000, 'Rallado', 1),
(67, 'Margarina Bonella ', 54, 554, 'Pan de 125grs', 2),
(68, 'fideos cabello', 12, 1000, 'fideos carozzi', 1),
(71, 'pan ', 2, 1111, 'pan ideal', 1),
(72, 'pan ideal', 232, 2323, 'pan ideal', 2),
(73, 'pure hohfman', 10, 1500, 'pure instantaneo 150grs', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `id_salida` int(11) NOT NULL,
  `fecha_salida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion_salida` varchar(250) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `salidas`
--

INSERT INTO `salidas` (`id_salida`, `fecha_salida`, `descripcion_salida`, `id_producto`, `cantidad`) VALUES
(2, '2016-10-26 03:59:38', 'ccu', 66, 30000),
(3, '2016-10-28 00:23:31', 'pedido 0k', 29, 4),
(6, '2016-10-28 01:45:27', 'se abastece cooler coca cola', 3, 3),
(7, '2016-10-28 01:46:42', 'se retira de bodega para consumo personal', 3, 1),
(8, '2016-10-28 04:56:09', 'devoluciones', 63, 2),
(9, '2016-10-28 04:58:55', 'devolucion', 29, 1),
(10, '2016-10-28 05:00:55', 'de', 29, 3),
(11, '2016-10-28 05:02:19', 'devoluciones', 29, 4),
(12, '2016-10-28 05:06:44', 'productos en mal estado', 29, 1),
(13, '2016-10-28 15:08:56', 'se retira producto para consumo personal', 63, 2),
(14, '2016-10-28 15:11:32', 'se retiran para consumo ', 3, 4),
(15, '2016-10-28 15:24:37', 'ventas', 3, 3),
(16, '2016-10-28 17:47:05', 'venta directa', 63, 3),
(17, '2016-10-28 17:52:46', 'retiro personal', 63, 2),
(18, '2016-10-28 18:02:29', 'venta clientes', 29, 2);

--
-- Disparadores `salidas`
--
DELIMITER $$
CREATE TRIGGER `actualiza_salida` AFTER UPDATE ON `salidas` FOR EACH ROW UPDATE productos SET stock_producto = (stock_producto + OLD.cantidad)- NEW.cantidad WHERE idProductos = OLD.id_producto
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `resta_stock` AFTER INSERT ON `salidas` FOR EACH ROW UPDATE productos set stock_producto = stock_producto- NEW.cantidad WHERE idProductos = NEW.id_producto
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `userName` varchar(45) DEFAULT NULL,
  `contrasena` varchar(45) DEFAULT NULL,
  `privilegios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `userName`, `contrasena`, `privilegios`) VALUES
(1, 'jaime1', '1234', 1),
(3, 'alvaro', '1234', 2),
(4, 'ss', 'sw21', 2),
(5, 'camila', '3214', 1),
(12, 'claudio', '222', 2),
(16, 'root', '123', 1),
(18, 'user', '123', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`idEstado`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `privilegios`
--
ALTER TABLE `privilegios`
  ADD PRIMARY KEY (`idprivilegios`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProductos`),
  ADD UNIQUE KEY `valor_unico` (`nombre_producto`,`descripcion_producto`),
  ADD KEY `fk_Productos_Estado1_idx` (`Estado_idEstado`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id_salida`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD KEY `fk_Usuario_privilegios1_idx` (`privilegios`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `idEstado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `privilegios`
--
ALTER TABLE `privilegios`
  MODIFY `idprivilegios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id_salida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idProductos`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_Productos_Estado1` FOREIGN KEY (`Estado_idEstado`) REFERENCES `estados` (`idEstado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `salidas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idProductos`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_Usuario_privilegios1` FOREIGN KEY (`privilegios`) REFERENCES `privilegios` (`idprivilegios`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
