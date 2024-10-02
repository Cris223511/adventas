<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Consultas
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	// compras

	public function listarcompras($fecha_inicio, $fecha_fin, $idproveedor)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' AND i.idproveedor='$idproveedor' ORDER by i.idingreso ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodascomprasfecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' ORDER by i.idingreso ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodascompras($idproveedor)
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idproveedor='$idproveedor' ORDER by i.idingreso DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodascomprasproveedores()
	{
		$sql = "SELECT DATE_FORMAT(i.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as proveedor,i.tipo_comprobante,mp.nombre as metodo_pago,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idproveedor=p.idpersona ORDER by i.idingreso DESC";
		return ejecutarConsulta($sql);
	}

	// ventas

	public function listarventas($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventas($idcliente)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente='$idcliente' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientes()
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente=p.idpersona ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}


	public function listarventasservicio($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfechaservicio($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasservicio($idcliente)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente='$idcliente' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientesservicio()
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente=p.idpersona ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}


	public function listarventascuotas($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfechacuotas($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventascuotas($idcliente)
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente='$idcliente' ORDER by v.idcuotas DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientescuotas()
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen, mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente=p.idusuario ORDER by v.idcuotas DESC";
		return ejecutarConsulta($sql);
	}

	// ventas usuario

	public function listarventasusuario($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariofecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuario($idusuario)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario='$idusuario' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariousuarios()
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario=u.idusuario ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}


	public function listarventasusuarioservicio($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariofechaservicio($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuarioservicio($idusuario)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario='$idusuario' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariousuariosservicio()
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario=u.idusuario ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}


	public function listarventasusuariocuotas($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariofechacuotas($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariocuotas($idusuario)
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario='$idusuario' ORDER by v.idcuotas DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuariousuarioscuotas()
	{
		$sql = "SELECT v.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo, p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM cuotas v LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idusuario=u.idusuario ORDER by v.idcuotas DESC";
		return ejecutarConsulta($sql);
	}

	// ventas y productos

	public function listarventasyproducto($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT dv.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfechayproducto($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT dv.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasyproducto($idcliente)
	{
		$sql = "SELECT dv.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE v.idcliente='$idcliente' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientesyproducto()
	{
		$sql = "SELECT dv.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE v.idcliente=p.idpersona ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function listarventasusuarioyproducto($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT dv.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario' ORDER by v.idventa ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuarioyproducto($idusuario)
	{
		$sql = "SELECT dv.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE v.idusuario='$idusuario' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}


	public function listarventasyproductoservicio($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT dv.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idservicio,a.codigo_producto,a.precio_venta,a.descripcion,a.imagen,v.estado FROM detalle_servicio dv LEFT JOIN venta_servicio v ON v.idventa_servicio=dv.idventa_servicio LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN servicio a on dv.idservicio=a.idservicio WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfechayproductoservicio($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT dv.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idservicio,a.codigo_producto,a.precio_venta,a.descripcion,a.imagen,v.estado FROM detalle_servicio dv LEFT JOIN venta_servicio v ON v.idventa_servicio=dv.idventa_servicio LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN servicio a on dv.idservicio=a.idservicio WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasyproductoservicio($idcliente)
	{
		$sql = "SELECT dv.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idservicio,a.codigo_producto,a.precio_venta,a.descripcion,a.imagen,v.estado FROM detalle_servicio dv LEFT JOIN venta_servicio v ON v.idventa_servicio=dv.idventa_servicio LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN servicio a on dv.idservicio=a.idservicio WHERE v.idcliente='$idcliente' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientesyproductoservicio()
	{
		$sql = "SELECT dv.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idservicio,a.codigo_producto,a.precio_venta,a.descripcion,a.imagen,v.estado FROM detalle_servicio dv LEFT JOIN venta_servicio v ON v.idventa_servicio=dv.idventa_servicio LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN servicio a on dv.idservicio=a.idservicio WHERE v.idcliente=p.idpersona ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function listarventasusuarioyproductoservicio($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT dv.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idservicio,a.codigo_producto,a.precio_venta,a.descripcion,a.imagen,v.estado FROM detalle_servicio dv LEFT JOIN venta_servicio v ON v.idventa_servicio=dv.idventa_servicio LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN servicio a on dv.idservicio=a.idservicio WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario' ORDER by v.idventa_servicio ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuarioyproductoservicio($idusuario)
	{
		$sql = "SELECT dv.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idservicio,a.codigo_producto,a.precio_venta,a.descripcion,a.imagen,v.estado FROM detalle_servicio dv LEFT JOIN venta_servicio v ON v.idventa_servicio=dv.idventa_servicio LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN servicio a on dv.idservicio=a.idservicio WHERE v.idusuario='$idusuario' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}


	public function listarventasyproductocuotas($fecha_inicio, $fecha_fin, $idcliente)
	{
		$sql = "SELECT dv.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_cuotas dv LEFT JOIN cuotas v ON v.idcuotas=dv.idcuotas LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idcliente='$idcliente' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasfechayproductocuotas($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT dv.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_cuotas dv LEFT JOIN cuotas v ON v.idcuotas=dv.idcuotas LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasyproductocuotas($idcliente)
	{
		$sql = "SELECT dv.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_cuotas dv LEFT JOIN cuotas v ON v.idcuotas=dv.idcuotas LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE v.idcliente='$idcliente' ORDER by v.idcuotas DESC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasclientesyproductocuotas()
	{
		$sql = "SELECT dv.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_cuotas dv LEFT JOIN cuotas v ON v.idcuotas=dv.idcuotas LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE v.idcliente=p.idusuario ORDER by v.idcuotas DESC";
		return ejecutarConsulta($sql);
	}

	public function listarventasusuarioyproductocuotas($fecha_inicio, $fecha_fin, $idusuario)
	{
		$sql = "SELECT dv.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_cuotas dv LEFT JOIN cuotas v ON v.idcuotas=dv.idcuotas LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' AND v.idusuario='$idusuario' ORDER by v.idcuotas ASC";
		return ejecutarConsulta($sql);
	}

	public function listartodasventasusuarioyproductocuotas($idusuario)
	{
		$sql = "SELECT dv.idcuotas,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y') as fecha,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,al.ubicacion as almacen,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_cuotas dv LEFT JOIN cuotas v ON v.idcuotas=dv.idcuotas LEFT JOIN usuario p ON v.idcliente=p.idusuario LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo WHERE v.idusuario='$idusuario' ORDER by v.idcuotas DESC";
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
				  dd.iddevolucion,
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  d.codigo_pedido as codigo_pedido,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  dd.cantidad_devuelta,
				  DATE_FORMAT(dd.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 1
				AND a.eliminado = '0'
                ORDER BY dd.iddevolucion DESC";

		return ejecutarConsulta($sql);
	}

	public function articulosmasdevueltos_tipo2()
	{
		$sql = "SELECT 
				  dd.iddevolucion,
				  a.idcategoria as idcategoria,
				  c.nombre as categoria,
				  m.nombre as marca,
				  al.ubicacion as almacen,
				  a.codigo as codigo,
				  a.codigo_producto as codigo_producto,
				  d.codigo_pedido as codigo_pedido,
				  a.nombre as nombre,
				  a.stock as stock,
				  a.descripcion as descripcion,
				  a.imagen as imagen,
				  dd.cantidad_devuelta,
				  DATE_FORMAT(dd.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha
				FROM detalle_devolucion dd
				LEFT JOIN articulo a ON dd.idarticulo = a.idarticulo
				LEFT JOIN categoria c ON a.idcategoria = c.idcategoria
				LEFT JOIN almacen al ON a.idalmacen = al.idalmacen
				LEFT JOIN marcas m ON a.idmarcas = m.idmarcas
				LEFT JOIN devolucion d ON dd.iddevolucion = d.iddevolucion
				WHERE d.opcion = 2
				AND a.eliminado = '0'
                ORDER BY dd.iddevolucion DESC";

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
