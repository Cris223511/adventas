CREATE TABLE `detalle_devolucion` (
  `iddetalle_devolucion` int(11) NOT NULL,
  `iddevolucion` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_prestada` int(11) NOT NULL,
  `cantidad_devuelta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
CREATE TRIGGER `tr_updStockDevolucionActualizar` AFTER UPDATE ON `detalle_devolucion` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock + NEW.cantidad_prestada - OLD.cantidad_prestada
 WHERE articulo.idarticulo = NEW.idarticulo AND stock > 0;
END
$$
DELIMITER ;

ALTER TABLE `detalle_devolucion`
  ADD PRIMARY KEY (`iddetalle_devolucion`),
  ADD KEY `iddevolucion` (`iddevolucion`),
  ADD KEY `idarticulo` (`idarticulo`);

ALTER TABLE `detalle_devolucion`
  MODIFY `iddetalle_devolucion` int(11) NOT NULL AUTO_INCREMENT;
