<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Consultas
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	public function comprasfecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'";
		return ejecutarConsulta($sql);
	}

	// compras

	public function listarcompras($fecha_inicio, $fecha_fin, $idproveedor)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' AND i.idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	public function listartodascomprasfecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'";
		return ejecutarConsulta($sql);
	}

	public function listartodascompras($idproveedor)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idproveedor='$idproveedor'";
		return ejecutarConsulta($sql);
	}

	public function listartodascomprasproveedores()
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idproveedor=p.idpersona";
		return ejecutarConsulta($sql);
	}

	// ventas

	public function listarventas($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente'";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'";
		return ejecutarConsulta($sql);
	}

	public function listartodasventas($idcliente)
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente='$idcliente'";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientes()
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente=p.idpersona";
		return ejecutarConsulta($sql);
	}

	// ventas usuario

	public function listarventasusuario($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariofecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin'";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuario($idusuario)
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariousuarios()
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario=u.idusuario";
		return ejecutarConsulta($sql);
	}

	// artículos más vendidos

	public function articulosmasvendidos()
	{
		$sql = "SELECT a.idcategoria as idcategoria,c.nombre as categoria,al.ubicacion as almacen,a.codigo as codigo,a.codigo_producto as codigo_producto, a.nombre as nombre,a.stock as stock,a.descripcion as descripcion,a.imagen as imagen, COUNT(dv.idarticulo) as cantidad FROM detalle_venta dv LEFT JOIN articulo a ON dv.idarticulo = a.idarticulo LEFT JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN almacen al ON a.idalmacen=al.idalmacen WHERE a.eliminado = '0' GROUP BY dv.idarticulo ORDER BY cantidad DESC";
		return ejecutarConsulta($sql);
	}

	// artículos más devueltos

	public function articulosmasdevueltos_tipo1()
	{
		$sql = "SELECT 
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  COUNT(dd.idarticulo) as cantidad
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 1
				AND a.eliminado = '0'
				GROUP BY dd.idarticulo
				ORDER BY cantidad DESC";

		return ejecutarConsulta($sql);
	}

	public function articulosmasdevueltos_tipo2()
	{
		$sql = "SELECT 
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  COUNT(dd.idarticulo) as cantidad
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 2
				AND a.eliminado = '0'
				GROUP BY dd.idarticulo
				ORDER BY cantidad DESC";

		return ejecutarConsulta($sql);
	}

	public function articulosmasdevueltos_tipo3()
	{
		$sql = "SELECT 
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  COUNT(dd.idarticulo) as cantidad
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 3
				AND a.eliminado = '0'
				GROUP BY dd.idarticulo
				ORDER BY cantidad DESC";

		return ejecutarConsulta($sql);
	}

	public function articulosmasdevueltos_tipo4()
	{
		$sql = "SELECT 
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  COUNT(dd.idarticulo) as cantidad
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 4
				AND a.eliminado = '0'
				GROUP BY dd.idarticulo
				ORDER BY cantidad DESC";

		return ejecutarConsulta($sql);
	}

	public function articulosmasdevueltos_tipo5()
	{
		$sql = "SELECT 
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  COUNT(dd.idarticulo) as cantidad
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 5
				AND a.eliminado = '0'
				GROUP BY dd.idarticulo
				ORDER BY cantidad DESC";

		return ejecutarConsulta($sql);
	}

	public function articulosmasdevueltos_tipo6()
	{
		$sql = "SELECT 
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  COUNT(dd.idarticulo) as cantidad
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 6
				AND a.eliminado = '0'
				GROUP BY dd.idarticulo
				ORDER BY cantidad DESC";

		return ejecutarConsulta($sql);
	}

	// Gráfico de ventas por usuario y artículo más vendido

	public function cantidadDelArticuloMasVendido()
	{
		$sql = "SELECT a.nombre as nombre, COUNT(dv.idarticulo) as cantidad FROM detalle_venta dv LEFT JOIN articulo a ON dv.idarticulo = a.idarticulo WHERE a.eliminado = '0' GROUP BY dv.idarticulo ORDER BY cantidad DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function articuoMasVendidoGrafico()
	{
		$sql = "SELECT a.nombre as nombre, COUNT(dv.idarticulo) as cantidad FROM detalle_venta dv LEFT JOIN articulo a ON dv.idarticulo = a.idarticulo WHERE a.eliminado = '0' GROUP BY dv.idarticulo ORDER BY cantidad DESC LIMIT 5";
		return ejecutarConsulta($sql);
	}

	public function cantidadDeTotalDeVentas()
	{
		$sql = "SELECT * FROM venta";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_3meses()
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora,'%M') as fecha, SUM(v.total_venta) as total, u.nombre as nombre FROM venta v LEFT JOIN usuario u ON v.idusuario = u.idusuario WHERE fecha_hora >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) GROUP by MONTH(fecha_hora) ORDER BY fecha_hora ASC limit 0,3;";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_1mes()
	{
		$sql = "SELECT DATE_FORMAT(v.fecha_hora,'%M') as fecha, SUM(v.total_venta) as total, u.nombre as nombre FROM venta v LEFT JOIN usuario u ON v.idusuario = u.idusuario WHERE fecha_hora >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) GROUP by MONTH(fecha_hora) ORDER BY fecha_hora ASC limit 0,1;";
		return ejecutarConsulta($sql);
	}


	// otros

	public function totalcomprahoy()
	{
		$sql = "SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate()";
		return ejecutarConsulta($sql);
	}

	public function totalventahoy()
	{
		$sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_hora)=curdate()";
		return ejecutarConsulta($sql);
	}

	public function comprasultimos_10dias()
	{
		$sql = "SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,SUM(total_compra) as total FROM ingreso GROUP by fecha_hora ORDER BY fecha_hora ASC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_12meses()
	{
		$sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,SUM(total_venta) as total FROM venta GROUP by MONTH(fecha_hora) ORDER BY fecha_hora ASC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function totalcomprahoyUsuario($idusuario)
	{
		$sql = "SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate() AND idusuario = '$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function totalventahoyUsuario($idusuario)
	{
		$sql = "SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_hora)=curdate() AND idusuario = '$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function comprasultimos_10diasUsuario($idusuario)
	{
		$sql = "SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,SUM(total_compra) as total FROM ingreso WHERE idusuario = '$idusuario' GROUP by fecha_hora ORDER BY fecha_hora ASC limit 0,10";
		return ejecutarConsulta($sql);
	}

	public function ventasultimos_12mesesUsuario($idusuario)
	{
		$sql = "SELECT DATE_FORMAT(fecha_hora,'%M') as fecha,SUM(total_venta) as total FROM venta WHERE idusuario = '$idusuario' GROUP by MONTH(fecha_hora) ORDER BY fecha_hora ASC limit 0,10";
		return ejecutarConsulta($sql);
	}
}
