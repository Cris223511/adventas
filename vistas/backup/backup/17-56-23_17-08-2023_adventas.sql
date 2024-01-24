-- MySQL dump 10.13  Distrib 8.0.27, for Win64 (x86_64)
--
-- Host: localhost    Database: adventas
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.20-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `almacen`
--

DROP TABLE IF EXISTS `almacen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `almacen` (
  `idalmacen` int(11) NOT NULL AUTO_INCREMENT,
  `ubicacion` varchar(70) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idalmacen`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen`
--

LOCK TABLES `almacen` WRITE;
/*!40000 ALTER TABLE `almacen` DISABLE KEYS */;
INSERT INTO `almacen` VALUES (1,'Lima, La Molina, Perú','Almacén para los productos de construcción, los repartidores se encuentran laborando con el almacén repartiendo a los camiones de entrega.','Activado'),(2,'Chorrillos, Av Andrómeda, Lima, Perú','El almacén tiene un aforo de 250 artículos, esta capacidad está por superarse, pero los encargados y gestores del local están dispuestos a evitar esto','Activado'),(3,'Calle Mateo Pumacahua, Santa Patricia2','Nuevo local en lima2','Activado');
/*!40000 ALTER TABLE `almacen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulo`
--

DROP TABLE IF EXISTS `articulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articulo` (
  `idarticulo` int(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` int(11) NOT NULL,
  `idalmacen` int(13) NOT NULL,
  `idmarcas` varchar(50) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `codigo_producto` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `stock_minimo` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idarticulo`),
  KEY `fk_articulo_categoria_idx` (`idcategoria`),
  KEY `idalmacen` (`idalmacen`),
  KEY `idmarcas` (`idmarcas`)
) ENGINE=InnoDB AUTO_INCREMENT=1328 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulo`
--

LOCK TABLES `articulo` WRITE;
/*!40000 ALTER TABLE `articulo` DISABLE KEYS */;
INSERT INTO `articulo` VALUES (3,1,1,'1','','2344232348567','RETEN DE EMBOLO',0,10,'','1627845886.png',1),(4,1,2,'1','','234534589345','RETEN DE EMBOLO',43,10,'','1627845886.png',1),(5,1,2,'1','','6645456821243','RETEN DE EMBOLO',38,10,'','1627845886.png',1),(6,1,1,'1','','234345456645','RETEN DE EMBOLO',2,10,'','1627845886.png',1),(7,1,1,'1','','2866038989324','RETEN DE EMBOLO',3,10,'','1627845886.png',1),(1300,1,1,'1','','118939084537','BAQUELITA',2,10,'','1627845886.png',1),(1302,1,1,'1','','21238348456','BAQUELITA',82,10,'','1627845886.png',1),(1314,1,1,'1','','456756756456','RETEN DE BOMBA HIDRAULICA',114,10,'','1627845886.png',1),(1315,1,2,'1','','47657654566','RETEN DE BOMBA HIDRAULICA',74,10,'','1627845886.png',1),(1316,1,3,'1','7 75 1457 1 0035 3','7776958690795','galleta oreo',59,10,'galleta rica','1678557149.jpg',1),(1317,1,2,'1','','56744564646','galleta morocha',53,10,'galleta rica','1673666079.jpg',1),(1319,1,3,'1','7 75 4560 1 0045 1','7776958390795','leche gloria',52,10,'leche deslactosada','1678555989.jpg',1),(1324,1,3,'4','7 75 4667 9 0091 9','3333244421','zapatillas nike',64,10,'zapatillas de alta calidad','1679202269.jpg',1),(1325,2,2,'3','7776958390795','7776958390798','Jorge',0,10,'jajaja','1680753474.png',1),(1326,2,2,'3','','785685757','rellenita',56,10,'JAJAJA','1684466062.png',1),(1327,1,1,'1','','6645678756','asdads',50,10,'asdsad','',1);
/*!40000 ALTER TABLE `articulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idcategoria`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'hidraulica','materiales',1),(2,'metales','metales super resistentes',1);
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuotas`
--

DROP TABLE IF EXISTS `cuotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuotas` (
  `idcuotas` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idvendedor` int(11) NOT NULL,
  `idzona` int(11) NOT NULL,
  `idalmacen` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(10) NOT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `fecha_anulado` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `monto_pagado` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idcuotas`),
  KEY `idcliente` (`idcliente`),
  KEY `idvendedor` (`idvendedor`),
  KEY `idzona` (`idzona`),
  KEY `idalmacen` (`idalmacen`),
  KEY `idusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuotas`
--

LOCK TABLES `cuotas` WRITE;
/*!40000 ALTER TABLE `cuotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuotas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockCuotaEliminar` AFTER DELETE ON `cuotas` FOR EACH ROW BEGIN
UPDATE articulo a
JOIN detalle_cuotas dc
ON dc.idarticulo = a.idarticulo
AND dc.idcuotas = old.idcuotas
set a.stock = a.stock + dc.cantidad;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalle_cuotas`
--

DROP TABLE IF EXISTS `detalle_cuotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_cuotas` (
  `iddetalle_cuotas` int(11) NOT NULL AUTO_INCREMENT,
  `idcuotas` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_cuotas`),
  KEY `fk_detalle_cuotas_cuotas_idx` (`idcuotas`),
  KEY `fk_detalle_cuotas_articulo_idx` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_cuotas`
--

LOCK TABLES `detalle_cuotas` WRITE;
/*!40000 ALTER TABLE `detalle_cuotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_cuotas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockCuota` AFTER INSERT ON `detalle_cuotas` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock - NEW.cantidad 
 WHERE articulo.idarticulo = NEW.idarticulo AND stock > 0;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalle_devolucion`
--

DROP TABLE IF EXISTS `detalle_devolucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_devolucion` (
  `iddetalle_devolucion` int(11) NOT NULL AUTO_INCREMENT,
  `iddevolucion` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_prestada` int(11) NOT NULL,
  `cantidad_devuelta` int(11) NOT NULL,
  `cantidad_a_devolver` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle_devolucion`),
  KEY `iddevolucion` (`iddevolucion`),
  KEY `idarticulo` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_devolucion`
--

LOCK TABLES `detalle_devolucion` WRITE;
/*!40000 ALTER TABLE `detalle_devolucion` DISABLE KEYS */;
INSERT INTO `detalle_devolucion` VALUES (53,30,1324,12,12,2,0),(54,30,1319,12,12,2,0),(55,31,1324,5,2,0,0),(56,31,1319,5,3,0,0),(57,32,1324,5,5,2,0),(58,32,1319,5,4,2,0),(59,33,1324,10,10,0,0),(60,33,1319,10,10,0,0),(61,34,1324,4,4,5,10),(62,34,1319,4,4,3,6),(63,35,1324,12,12,12,0),(64,35,1326,12,10,10,0),(65,36,1319,10,10,10,10),(66,36,1324,12,12,12,12),(67,37,1327,20,20,5,5),(68,37,1326,10,10,6,6),(69,38,1319,1,0,0,0),(70,38,1324,1,0,0,0);
/*!40000 ALTER TABLE `detalle_devolucion` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockDevolucionActualizar` AFTER UPDATE ON `detalle_devolucion` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock + (NEW.cantidad_a_devolver - OLD.cantidad_a_devolver)
 WHERE articulo.idarticulo = NEW.idarticulo AND stock > 0;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalle_ingreso`
--

DROP TABLE IF EXISTS `detalle_ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_ingreso` (
  `iddetalle_ingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_ingreso`),
  KEY `fk_detalle_ingreso_ingreso_idx` (`idingreso`),
  KEY `fk_detalle_ingreso_articulo_idx` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_ingreso`
--

LOCK TABLES `detalle_ingreso` WRITE;
/*!40000 ALTER TABLE `detalle_ingreso` DISABLE KEYS */;
INSERT INTO `detalle_ingreso` VALUES (1,1,4,1,450.00,98.00),(2,2,2,1,150.00,21.00),(3,3,5,1,190.00,110.00),(4,4,5,1,1.00,1.00),(5,5,6,6,40.00,50.00),(6,0,4,12,133.00,153.00),(7,6,6,2,132.00,140.00),(8,6,5,1,114.00,150.00),(9,6,5,1,155.00,190.00),(10,0,7,2,23.00,22.00),(11,0,6,5,155.00,142.00),(12,0,1315,2,50.00,70.00),(13,7,1314,2,100.00,90.00),(14,0,1314,4,222.00,334.00),(15,8,1314,2,44.00,23.00),(16,9,5,3,12.00,12.00),(17,9,4,3,12.00,12.00),(18,0,1316,2,30.00,40.00),(19,10,1316,2,123.00,123.00),(20,10,1316,3,123.00,123.00),(21,11,1316,1,123.00,1.00),(22,11,1316,2,123.00,1.00),(23,12,1315,13,14.00,12.00),(24,12,1316,12,11.00,12.00),(25,13,1314,13,12.00,19.00),(26,13,1315,3,14.00,20.00),(27,14,1322,2,15.00,24.00),(28,0,1319,2,134.00,1.00),(29,0,1319,2,13.00,1.00),(30,0,1322,2,12.00,1.00),(31,0,1322,2,33.00,1.00),(32,15,1317,2,13.00,1.00),(33,16,1319,12,13.00,15.00),(34,16,1324,12,13.00,15.00),(35,17,1324,2,13.00,14.00),(36,18,1324,1,1.00,1.00),(37,0,1324,2,14.00,11.00),(38,0,1325,3,14.00,11.00),(39,0,1326,2,13.00,1.00),(40,0,1327,2,34.00,11.00),(41,0,1324,17,22.00,12.00),(42,0,1319,45,2.00,1.00),(43,0,1324,30,16.00,10.00),(44,0,1319,17,10.00,1.00),(45,0,1325,15,10.00,4.00);
/*!40000 ALTER TABLE `detalle_ingreso` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockIngreso` AFTER INSERT ON `detalle_ingreso` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock + NEW.cantidad 
 WHERE articulo.idarticulo = NEW.idarticulo;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalle_pagos`
--

DROP TABLE IF EXISTS `detalle_pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pagos` (
  `iddetalle_pagos` int(13) NOT NULL AUTO_INCREMENT,
  `idcuotas` int(13) NOT NULL,
  `metodo_pago` varchar(20) NOT NULL,
  `concepto` varchar(20) NOT NULL,
  `monto` decimal(11,2) NOT NULL,
  `fecha_pago` datetime NOT NULL,
  PRIMARY KEY (`iddetalle_pagos`),
  KEY `idcuotas` (`idcuotas`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pagos`
--

LOCK TABLES `detalle_pagos` WRITE;
/*!40000 ALTER TABLE `detalle_pagos` DISABLE KEYS */;
INSERT INTO `detalle_pagos` VALUES (1,5,'Efectivo','1 cuota',110.00,'2022-11-10 01:41:17'),(2,5,'Tarjeta','1 cuota',100.00,'2022-11-11 21:55:01'),(5,8,'Tarjeta','2 cuota',10.00,'2022-11-12 01:20:18'),(6,8,'Tarjeta','1 cuota',15.00,'2022-11-12 01:32:29'),(7,8,'Tarjeta','2 cuota',14.50,'2022-11-12 01:34:32'),(30,8,'Efectivo','cuota 3',70.50,'2022-11-12 18:12:57'),(31,5,'Yape','cuota 3',10.00,'2022-11-12 20:34:54'),(32,5,'Tarjeta','2 cuota',80.00,'2022-11-12 20:36:16'),(33,5,'Yape','cuota 5',20.00,'2022-11-12 20:37:59'),(34,9,'Efectivo','1 cuota',12.00,'2022-12-19 22:16:29'),(35,9,'Tarjeta','2',21.00,'2022-12-19 22:22:05'),(36,9,'Efectivo','2',32.00,'2022-12-19 22:22:20'),(37,9,'Tarjeta','1 cuota',7.00,'2022-12-19 22:23:28'),(38,9,'Tarjeta','3 cuota',31.00,'2022-12-19 22:24:16'),(39,9,'Tarjeta','2 cuota',12.00,'2022-12-19 22:27:09'),(40,9,'Tarjeta','12',12.00,'2022-12-19 22:28:15'),(41,9,'Tarjeta','1 cuota',12.00,'2022-12-19 22:29:02'),(42,9,'Otros','2 cuota',5.00,'2022-12-19 22:30:39'),(43,9,'Tarjeta','1 cuota',12.00,'2022-12-19 22:35:06');
/*!40000 ALTER TABLE `detalle_pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_proforma`
--

DROP TABLE IF EXISTS `detalle_proforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_proforma` (
  `iddetalle_proforma` int(11) NOT NULL AUTO_INCREMENT,
  `idproforma` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_proforma`),
  KEY `fk_detalle_proforma_proforma_idx` (`idproforma`),
  KEY `fk_detalle_proforma_articulo_idx` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_proforma`
--

LOCK TABLES `detalle_proforma` WRITE;
/*!40000 ALTER TABLE `detalle_proforma` DISABLE KEYS */;
INSERT INTO `detalle_proforma` VALUES (1,2,1327,20,12.00,2.00),(2,3,1319,3,50.00,2.00),(3,4,1327,5,12.00,2.00),(4,4,1326,5,10.00,2.00),(5,5,1326,3,5.00,1.00),(6,5,1327,4,10.00,2.00),(7,6,1327,5,10.00,2.00),(8,6,1326,4,10.00,2.00),(9,7,1326,5,10.00,5.00);
/*!40000 ALTER TABLE `detalle_proforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_solicitud`
--

DROP TABLE IF EXISTS `detalle_solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_solicitud` (
  `iddetalle_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `idsolicitud` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `cantidad_prestada` int(11) NOT NULL,
  PRIMARY KEY (`iddetalle_solicitud`),
  KEY `idsolicitud` (`idsolicitud`),
  KEY `idarticulo` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_solicitud`
--

LOCK TABLES `detalle_solicitud` WRITE;
/*!40000 ALTER TABLE `detalle_solicitud` DISABLE KEYS */;
INSERT INTO `detalle_solicitud` VALUES (53,30,1324,12,12),(54,30,1319,12,12),(55,31,1324,5,2),(56,31,1319,5,3),(57,32,1324,5,5),(58,32,1319,5,4),(59,33,1324,10,10),(60,33,1319,10,10),(61,34,1324,4,4),(62,34,1319,4,4),(63,35,1324,12,12),(64,35,1326,12,10),(65,36,1319,10,10),(66,36,1324,12,12),(67,37,1327,20,20),(68,37,1326,10,10),(69,38,1319,1,0),(70,38,1324,1,0);
/*!40000 ALTER TABLE `detalle_solicitud` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockSolicitudActualizar` AFTER UPDATE ON `detalle_solicitud` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock - NEW.cantidad_prestada + OLD.cantidad_prestada
 WHERE articulo.idarticulo = NEW.idarticulo AND stock > 0;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `iddetalle_venta` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  PRIMARY KEY (`iddetalle_venta`),
  KEY `fk_detalle_venta_venta_idx` (`idventa`),
  KEY `fk_detalle_venta_articulo_idx` (`idarticulo`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` VALUES (5,5,5,3,110.00,10.00),(7,6,5,1,110.00,0.00),(8,7,5,1,110.00,0.00),(9,8,5,1,110.00,0.00),(10,9,4,2,153.00,30.00),(15,11,4,2,153.00,123.00),(16,11,5,2,190.00,123.00),(17,11,5,2,190.00,123.00),(18,12,4,1,153.00,0.00),(19,12,4,1,153.00,0.00),(20,13,5,3,12.00,12.00),(21,13,5,2,12.00,11.00),(22,13,4,3,12.00,14.00),(23,0,3,4,56.00,10.00),(24,0,3,4,50.00,8.00),(25,0,4,4,12.00,0.00),(26,14,1316,3,140.00,0.00),(27,15,1316,10,180.00,12.00),(28,16,1315,4,4000.00,12.00),(29,17,1302,1,123.00,32.00),(30,18,1302,1,12.00,1.00),(31,19,1314,11,23.00,2.00),(32,20,1316,6,12.00,15.00),(33,21,1316,2,12.00,3.00),(34,22,1316,1,12.00,1.00),(35,23,1319,1,0.00,0.00),(36,0,1316,2,12.00,2.00),(37,0,1317,1,14.00,2.00),(38,0,1319,4,12.00,2.00),(39,0,1319,2,10.00,2.00),(40,0,1322,1,24.00,2.00),(41,24,1322,2,24.00,1.00),(42,24,1319,2,12.00,1.00),(43,25,1319,1,12.00,5.00),(44,25,1319,2,12.00,5.00),(45,26,1324,1,1.00,0.00),(46,26,1322,1,24.00,0.00),(47,27,1319,1,1.00,0.00),(48,27,1322,1,24.00,0.00),(49,28,1319,1,1.00,0.00),(50,28,1319,1,1.00,0.00),(51,28,1322,1,24.00,0.00),(52,29,1322,1,24.00,0.00),(53,30,1322,1,24.00,0.00),(54,30,1324,1,0.00,0.00),(55,30,1324,1,0.00,0.00),(56,31,1322,12,24.00,0.00),(57,31,1322,1,24.00,0.00),(58,0,1322,1,24.00,0.00),(59,0,1322,1,24.00,0.00),(60,32,1324,1,12.00,0.00),(61,0,1316,1,12.00,1.00),(62,33,1317,1,2.00,0.00),(63,33,1322,6,24.00,0.00),(64,34,1317,2,122.00,0.00),(65,35,1319,1,15.00,0.00),(66,36,1324,1,1.00,0.00),(67,36,1319,1,15.00,0.00),(68,36,1317,8,1.00,0.00),(69,37,1316,2,12.00,12.00),(70,37,1317,3,123.00,12.00),(71,0,1324,3,100.00,35.00),(72,0,1325,1,100.00,51.00),(73,38,1324,13,11.00,1.00),(74,39,1324,4,12.00,3.00),(75,40,1324,1,10.00,2.00),(76,41,1317,1,1.00,0.00),(77,41,1300,2,0.00,0.00),(78,0,1327,20,12.00,2.00),(79,42,1327,20,12.00,2.00),(80,43,1319,3,50.00,2.00),(81,44,1327,5,12.00,2.00),(82,44,1326,5,10.00,2.00),(83,45,1327,5,10.00,2.00),(84,45,1326,4,10.00,2.00),(85,46,1326,1,1.00,0.00),(86,47,1326,5,10.00,5.00);
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockVenta` AFTER INSERT ON `detalle_venta` FOR EACH ROW BEGIN
 UPDATE articulo SET stock = stock - NEW.cantidad 
 WHERE articulo.idarticulo = NEW.idarticulo AND stock > 0;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `devolucion`
--

DROP TABLE IF EXISTS `devolucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devolucion` (
  `iddevolucion` int(11) NOT NULL AUTO_INCREMENT,
  `idalmacenero` int(11) NOT NULL,
  `idencargado` int(11) NOT NULL,
  `codigo_pedido` varchar(10) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `comentario` text NOT NULL,
  `fecha_hora_pedido` datetime NOT NULL,
  `fecha_hora_devolucion` datetime NOT NULL,
  `opcion` int(1) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`iddevolucion`),
  KEY `idalmacenero` (`idalmacenero`),
  KEY `idencargado` (`idencargado`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devolucion`
--

LOCK TABLES `devolucion` WRITE;
/*!40000 ALTER TABLE `devolucion` DISABLE KEYS */;
INSERT INTO `devolucion` VALUES (30,1,1,'0001','sdasdsdsad','3435345','','2023-07-16 17:10:18','2000-01-01 00:00:00',1,'En curso'),(31,1,1,'0002','adaddad','4234234','','2023-07-16 17:27:07','2000-01-01 00:00:00',2,'Pendiente'),(32,1,1,'0003','asdfsdf','12123213','','2023-07-16 17:30:45','2000-01-01 00:00:00',3,'En curso'),(33,1,1,'0004','asdasdass','343233242','','2023-07-16 17:38:28','2000-01-01 00:00:00',3,'Pendiente'),(34,1,1,'0005','esta es la buena','23123213','','2023-07-16 17:42:20','2023-07-17 00:19:17',4,'Finalizado'),(35,1,1,'0006','sdasdsada','312123','asdsad','2023-07-16 17:48:35','2000-01-01 00:00:00',5,'En curso'),(36,1,1,'0007','prueba','234234432','Productos devueltos con éxito =)','2023-07-19 00:45:44','2023-07-19 01:54:24',4,'Finalizado'),(37,1,1,'0008','adasdsadasds','345435634','holaaaaaa2','2023-08-01 19:19:39','2023-08-01 19:57:35',6,'Finalizado');
/*!40000 ALTER TABLE `devolucion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingreso`
--

DROP TABLE IF EXISTS `ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingreso` (
  `idingreso` int(11) NOT NULL AUTO_INCREMENT,
  `idproveedor` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(10) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_compra` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idingreso`),
  KEY `fk_ingreso_persona_idx` (`idproveedor`),
  KEY `fk_ingreso_usuario_idx` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingreso`
--

LOCK TABLES `ingreso` WRITE;
/*!40000 ALTER TABLE `ingreso` DISABLE KEYS */;
INSERT INTO `ingreso` VALUES (1,2,1,'Boleta','','111','2022-07-03 10:41:33',0.00,450.00,'Anulado'),(3,2,1,'Ticket','','111','2022-07-10 08:42:00',0.00,190.00,'Aceptado'),(4,2,1,'Boleta','B','111','2022-07-05 09:40:37',0.00,1.00,'Aceptado'),(5,2,1,'Ticket','B','111','2022-07-05 11:17:33',0.00,240.00,'Aceptado'),(6,2,1,'Factura','','111','2022-10-13 10:27:48',18.00,533.00,'Anulado'),(7,2,1,'Factura','FF','233','2022-12-14 10:55:33',18.00,200.00,'Aceptado'),(8,2,1,'Factura','sss','3434','2022-12-16 14:43:43',18.00,88.00,'Anulado'),(9,2,1,'Factura','AJJ','2231','2023-01-27 18:46:41',18.00,72.00,'Aceptado'),(10,2,1,'Boleta','000','123','2023-01-08 08:34:50',0.00,615.00,'Aceptado'),(11,2,1,'Factura','XX','123','2023-01-08 11:15:51',18.00,369.00,'Aceptado'),(12,2,1,'Factura','XXXS','333','2023-01-11 11:24:29',18.00,314.00,'Aceptado'),(13,2,1,'Factura','TTR','2223','2023-01-13 15:30:32',18.00,198.00,'Aceptado'),(15,2,1,'Factura','11','11','2023-03-06 13:31:45',18.00,26.00,'Aceptado'),(16,2,1,'Factura','C','112','2023-04-08 14:44:53',18.00,312.00,'Aceptado'),(17,2,1,'Factura','33','10','2023-04-09 10:29:44',18.00,26.00,'Aceptado'),(18,4,1,'Ticket','QQ','9999','2023-04-09 12:29:37',0.00,1.00,'Aceptado');
/*!40000 ALTER TABLE `ingreso` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockIngresoAnular` AFTER UPDATE ON `ingreso` FOR EACH ROW BEGIN
UPDATE articulo a
JOIN detalle_ingreso di
ON di.idarticulo = a.idarticulo
AND di.idingreso = new.idingreso
set a.stock = a.stock - di.cantidad;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockIngresoEliminar` AFTER DELETE ON `ingreso` FOR EACH ROW BEGIN
UPDATE articulo a
JOIN detalle_ingreso di
ON di.idarticulo = a.idarticulo
AND di.idingreso = old.idingreso
set a.stock = a.stock - di.cantidad;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marcas` (
  `idmarcas` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idmarcas`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (1,'gloria','la mejor marca del Peru','Activado'),(2,'cielo','agua hidratante','Desactivado'),(3,'agua cielo','la mejor marca de agua del peru','Activado'),(4,'nike','zapatillas a buen precio','Activado');
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`idpermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` VALUES (1,'Escritorio'),(2,'Almacen'),(3,'Compras'),(4,'Ventas'),(5,'Acceso'),(6,'Consulta Compras'),(7,'Consulta Ventas'),(8,'Cuotas'),(9,'Consulta Usuario'),(10,'Solicitudes'),(11,'Devoluciones'),(12,'Proforma'),(13,'Consulta Devoluciones');
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_persona` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `num_documento` varchar(20) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Cliente','Christopher PS','DNI','76655698','Lima, La Molina','973182294','cris_antonio2001@hotmail.com'),(2,'Proveedor','Christopher Ant','DNI','76655698','Lima, La Molina','973182294','cris_antonio2001@hotmail.com'),(3,'Cliente','Christopher PS2','DNI','76655698','lima, lima, Perú','973182294','cris_antonio2001@hotmail.com'),(4,'Proveedor','Christopher Ant 2','DNI','76655698','lima, lima, Perú','973182294','cris_antonio2001@hotmail.com'),(6,'Cliente','publico general','DNI','','','','');
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proforma`
--

DROP TABLE IF EXISTS `proforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proforma` (
  `idproforma` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(10) NOT NULL,
  `num_proforma` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idproforma`),
  KEY `fk_proforma_persona_idx` (`idcliente`),
  KEY `fk_proforma_usuario_idx` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proforma`
--

LOCK TABLES `proforma` WRITE;
/*!40000 ALTER TABLE `proforma` DISABLE KEYS */;
INSERT INTO `proforma` VALUES (2,3,1,'Boleta','DDXGF','0001','2023-07-17 04:39:54',18.00,280.84,'Finalizado'),(3,1,1,'Boleta','004DF1','0002','2023-07-19 08:16:37',18.00,174.64,'Finalizado'),(4,1,1,'Factura','1PK8DF','0003','2023-07-19 02:14:30',18.00,125.08,'Finalizado'),(5,1,1,'Boleta','BM5BC2','0004','2023-07-19 17:15:24',18.00,61.36,'Pendiente'),(6,1,1,'Ticket','K66XCC','0005','2023-07-19 10:24:40',18.00,101.48,'Finalizado'),(7,6,1,'Factura','K66XCC','0006','2023-08-11 02:21:00',18.00,53.10,'Finalizado');
/*!40000 ALTER TABLE `proforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud`
--

DROP TABLE IF EXISTS `solicitud`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitud` (
  `idsolicitud` int(11) NOT NULL AUTO_INCREMENT,
  `idalmacenero` int(11) NOT NULL,
  `idencargado` int(11) NOT NULL,
  `codigo_pedido` varchar(10) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `comentario` text NOT NULL,
  `fecha_hora_pedido` datetime NOT NULL,
  `fecha_hora_despacho` datetime NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idsolicitud`),
  KEY `idalmacenero` (`idalmacenero`),
  KEY `idencargado` (`idencargado`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud`
--

LOCK TABLES `solicitud` WRITE;
/*!40000 ALTER TABLE `solicitud` DISABLE KEYS */;
INSERT INTO `solicitud` VALUES (30,1,1,'0001','sdasdsdsad','3435345','','2023-07-16 17:10:18','2023-07-16 17:25:06','Pendiente'),(31,1,1,'0002','adaddad','4234234','','2023-07-16 17:27:07','2023-07-16 17:27:40','Finalizado'),(32,1,1,'0003','asdfsdf','12123213','','2023-07-16 17:30:45','2023-07-16 17:34:38','Finalizado'),(33,1,1,'0004','asdasdass','343233242','','2023-07-16 17:38:28','2023-07-26 18:08:29','Finalizado'),(34,1,1,'0005','esta es la buena','23123213','','2023-07-16 17:42:20','2023-07-16 17:48:05','Finalizado'),(35,1,1,'0006','sdasdsada','312123','','2023-07-16 17:48:35','2023-07-16 17:58:54','Finalizado'),(36,1,1,'0007','prueba','234234432','','2023-07-19 00:45:44','2023-07-19 01:40:04','Finalizado'),(37,1,1,'0008','adasdsadasds','345435634','hola soy chris','2023-08-01 19:19:39','2023-08-01 19:36:26','Finalizado');
/*!40000 ALTER TABLE `solicitud` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_documento` varchar(20) NOT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `cargo` varchar(20) DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `clave` varchar(64) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'admin','PS','DNI','47715777','Jose Gálvez 1368 - Chongoyape','931742904','admin@admin.com','administrador','admin','admin','1487132068.jpg',1),(6,'chriss','PS','DNI','76655698','Lima la molina','973182294','email@email.com','cliente','admin2','admin567','1487132068.jpg',1),(7,'Pedro','PS','DNI','76655698','Ate Santa Clara','973182294','email@email.com','vendedor','admin3','admin123','1487132068.jpg',1),(8,'Javier','PS','DNI','12345678','Lima','973182294','correo@correo.com','vendedor','admin4','123','1487132068.jpg',1),(12,'Jorge','PS','DNI','444432','adasdasd','234234234','cris_antonio2001@hotmail.com','cliente','jorge123','jorge123','1487132068.jpg',1),(18,'prueba','PS','DNI','76655698','Carabayllo, Lima, Perú','973182294','admin@admin.com','vendedor','prueba','prueba','1673115073.jpeg',1),(19,'prueba2','PS','DNI','76655698','Lima','973182294','prueba2@prueba2.com','cliente','prueba2','prueba2','1673118642.jpg',1),(23,'christophr','PS','CEDULA','123123','asdasd','123123','asdasd@asdasd.com','administrador','pepe','pepe','',1),(24,'chriss2','PS','RUC','1231231','asddas','123123','asdasd@asdasd.com','administrador','chris2','123123','',1),(26,'Carlitos','PS','RUC','1231233','asdasd','12312345','','administrador','Chris22','123123','',1),(28,'Victor','PS','RUC','31234545344','Lima, San Miguel','344845834','almacenero@email.com','almacenero','victor123','victor','1688866016.jpg',1),(29,'Manuel','PS','DNI','12312312','Lima, Carabayllo','234234232','encargado@email.com','encargado','manuel123','manuel','1688867073.jpg',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_permiso`
--

DROP TABLE IF EXISTS `usuario_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_permiso` (
  `idusuario_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL,
  PRIMARY KEY (`idusuario_permiso`),
  KEY `fk_usuario_permiso_permiso_idx` (`idpermiso`),
  KEY `fk_usuario_permiso_usuario_idx` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=668 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_permiso`
--

LOCK TABLES `usuario_permiso` WRITE;
/*!40000 ALTER TABLE `usuario_permiso` DISABLE KEYS */;
INSERT INTO `usuario_permiso` VALUES (117,5,1),(118,5,2),(119,5,3),(120,5,4),(121,5,5),(122,5,6),(123,5,7),(194,9,1),(195,9,2),(196,9,3),(197,9,4),(198,9,5),(199,9,6),(200,9,7),(215,10,1),(216,10,2),(217,10,3),(218,10,4),(219,10,5),(220,10,6),(221,10,7),(236,11,1),(237,11,2),(238,11,3),(239,11,4),(240,11,5),(241,11,6),(242,11,7),(251,6,1),(252,6,2),(253,6,3),(254,6,4),(255,6,5),(256,6,6),(257,6,7),(258,6,8),(259,7,1),(260,7,2),(261,7,3),(262,7,4),(263,7,5),(264,7,6),(265,7,7),(266,7,8),(267,12,8),(268,8,1),(269,8,2),(270,8,3),(271,8,4),(272,8,5),(273,8,6),(274,8,7),(275,8,8),(276,0,8),(277,0,7),(278,0,6),(279,0,5),(280,0,4),(281,0,3),(282,0,2),(283,0,1),(284,0,8),(285,0,7),(286,0,6),(287,0,5),(288,0,4),(289,0,3),(290,0,2),(291,0,1),(292,0,8),(293,0,7),(294,0,6),(295,0,5),(296,0,4),(297,0,3),(298,0,2),(299,0,1),(364,0,1),(365,0,2),(366,0,3),(367,0,4),(368,0,5),(369,0,6),(370,0,7),(371,0,8),(372,0,9),(382,18,1),(383,18,2),(384,18,3),(385,18,4),(386,18,6),(387,18,7),(388,18,8),(389,18,9),(398,19,1),(399,19,2),(400,19,3),(401,19,4),(402,19,6),(403,19,7),(404,19,8),(405,19,9),(406,0,1),(407,0,2),(408,0,3),(409,0,4),(410,0,6),(411,0,7),(412,0,8),(413,0,9),(414,21,1),(415,21,2),(416,21,3),(417,21,4),(418,21,5),(419,21,6),(420,21,7),(421,21,8),(422,21,9),(423,0,1),(424,0,2),(425,0,3),(426,0,4),(427,0,5),(428,0,6),(429,0,7),(430,0,8),(431,0,9),(450,25,1),(451,25,2),(452,25,3),(453,25,4),(454,25,5),(455,25,6),(456,25,7),(457,25,8),(458,25,9),(459,24,1),(460,24,2),(461,24,3),(462,24,4),(463,24,5),(464,24,6),(465,24,7),(466,24,8),(467,24,9),(468,26,1),(469,26,2),(470,26,3),(471,26,4),(472,26,5),(473,26,6),(474,26,7),(475,26,8),(476,26,9),(477,27,1),(478,27,2),(479,27,3),(480,27,4),(481,27,5),(482,27,6),(483,27,7),(484,27,8),(485,27,9),(522,23,1),(523,23,2),(524,23,3),(525,23,4),(526,23,5),(527,23,6),(528,23,7),(529,23,8),(530,23,9),(627,30,1),(628,30,2),(629,30,3),(630,30,4),(631,30,5),(632,30,6),(633,30,7),(634,30,8),(635,30,9),(636,31,1),(637,31,2),(638,31,3),(639,31,4),(640,31,5),(641,31,6),(642,31,7),(643,31,8),(644,31,9),(645,28,1),(646,28,10),(647,28,11),(648,28,12),(649,28,13),(650,29,1),(651,29,10),(652,29,11),(653,29,12),(654,29,13),(655,1,1),(656,1,2),(657,1,3),(658,1,4),(659,1,5),(660,1,6),(661,1,7),(662,1,8),(663,1,9),(664,1,10),(665,1,11),(666,1,12),(667,1,13);
/*!40000 ALTER TABLE `usuario_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(10) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_venta` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idventa`),
  KEY `fk_venta_persona_idx` (`idcliente`),
  KEY `fk_venta_usuario_idx` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
INSERT INTO `venta` VALUES (5,1,1,'Boleta','B','111','2022-07-04 14:52:35',0.00,600.00,'Aceptado'),(6,1,1,'Boleta','B','111','2022-07-05 10:18:07',0.00,110.00,'Aceptado'),(7,1,1,'Factura','B','111','2022-07-05 09:42:07',18.00,110.00,'Aceptado'),(9,1,1,'Ticket','A','233','2022-08-10 05:17:20',18.00,276.00,'Aceptado'),(11,1,1,'Factura','FF','344','2022-11-24 10:23:32',18.00,697.00,'Anulado'),(12,1,1,'Boleta','AA','345','2022-11-01 19:42:51',0.00,306.00,'Aceptado'),(13,1,1,'Factura','VEN','2334','2023-01-19 06:53:32',18.00,1.00,'Aceptado'),(14,1,1,'Ticket','QQW','555','2023-01-08 05:14:25',18.00,420.00,'Aceptado'),(15,1,1,'Boleta','QQX','999','2023-01-08 06:45:41',0.00,1788.00,'Aceptado'),(17,1,1,'Factura','TTT','233','2023-01-12 08:51:07',18.00,91.00,'Aceptado'),(18,1,1,'Factura','AAAX','223','2023-01-11 08:20:44',18.00,47.00,'Aceptado'),(19,1,1,'Factura','RRRR','223','2023-01-11 20:10:17',18.00,251.00,'Aceptado'),(20,1,1,'Ticket','TTTT','223','2023-01-11 05:52:18',0.00,57.00,'Aceptado'),(21,1,1,'Factura','TTY','223','2023-01-11 08:30:24',18.00,21.00,'Aceptado'),(22,1,1,'Factura','XXQR','233','2023-01-12 02:54:52',18.00,11.00,'Aceptado'),(24,1,1,'Factura','XDD','667','2023-03-19 07:44:35',18.00,70.00,'Aceptado'),(32,1,1,'Factura','11111','1111','2023-03-26 09:34:27',0.00,12.00,'Aceptado'),(33,1,1,'Factura','1111','1111','2023-03-19 12:16:48',18.00,146.00,'Aceptado'),(34,1,1,'Factura','111','11','2023-03-19 06:14:29',18.00,244.00,'Aceptado'),(35,3,1,'Boleta','12','12','2023-04-08 20:54:24',18.00,15.00,'Aceptado'),(36,1,1,'Boleta','113DS','13','2023-04-15 10:39:21',0.00,24.00,'Aceptado'),(37,1,1,'Factura','aaaa','122232','2023-05-17 17:42:42',18.00,435.42,'Aceptado'),(38,3,1,'Boleta','sadasd','122233','2023-05-24 09:30:53',18.00,167.56,'Aceptado'),(39,3,1,'Boleta','QWQ','122234','2023-05-24 05:03:35',18.00,53.10,'Aceptado'),(40,1,1,'Boleta','JAJAJA','122235','2023-05-30 12:45:04',18.00,9.44,'Aceptado'),(41,3,1,'Boleta','asdsad','122236','2023-07-13 08:19:39',0.00,1.00,'Aceptado'),(42,3,1,'Boleta','0','0001','2023-07-17 06:37:23',18.00,238.00,'Anulado'),(45,1,1,'Boleta','QE345','0005','2023-07-19 13:54:34',18.00,101.48,'Aceptado'),(46,6,1,'Boleta','QE345','0009','2023-08-05 09:15:41',18.00,1.18,'Aceptado'),(47,6,1,'Factura','QE345','0010','2023-08-11 02:21:00',18.00,53.10,'Aceptado');
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockVentaAnular` AFTER UPDATE ON `venta` FOR EACH ROW BEGIN
UPDATE articulo a
JOIN detalle_venta dv
ON dv.idarticulo = a.idarticulo
AND dv.idventa = new.idventa
set a.stock = a.stock + dv.cantidad;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_updStockVentaEliminar` AFTER DELETE ON `venta` FOR EACH ROW BEGIN
UPDATE articulo a
JOIN detalle_venta dv
ON dv.idarticulo = a.idarticulo
AND dv.idventa = old.idventa
set a.stock = a.stock + dv.cantidad;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `zonas`
--

DROP TABLE IF EXISTS `zonas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zonas` (
  `idzona` int(13) NOT NULL AUTO_INCREMENT,
  `idusuario` int(13) NOT NULL,
  `ubicacion` varchar(80) NOT NULL,
  `zona` varchar(50) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`idzona`),
  KEY `idusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zonas`
--

LOCK TABLES `zonas` WRITE;
/*!40000 ALTER TABLE `zonas` DISABLE KEYS */;
INSERT INTO `zonas` VALUES (1,1,'Lima, La Molina, Peru','zona A','2022-10-24 04:07:56','Activado'),(2,1,'Lima, La Molina, Peru','zona B','2022-10-24 23:10:50','Activado'),(3,1,'Lima, La Molina, Peru','zona A','2022-11-01 11:20:35','Activado'),(4,1,'Lima, La Molina, Peru','Zona C','2023-01-05 09:40:03','Activado');
/*!40000 ALTER TABLE `zonas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-08-17 17:56:27
