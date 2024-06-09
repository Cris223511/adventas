<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Ingreso
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $idmetodopago, $idalmacen, $idproveedor, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta)
	{
		// Primero, debemos verificar si el precio de venta es menor al precio de compra
		$error = $this->validarPrecioCompraPrecioVenta($idarticulo, $precio_compra, $precio_venta);
		if ($error) {
			// Si cumple, o sea si es verdadero, no se puede insertar
			return false;
		}

		$sql = "INSERT INTO ingreso (idusuario,idmetodopago,idalmacen,idproveedor,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado)
		VALUES ('$idusuario','$idmetodopago','$idalmacen','$idproveedor','$tipo_comprobante','$serie_comprobante','$num_comprobante',SYSDATE(),'$impuesto','$total_compra','Aceptado')";
		//return ejecutarConsulta($sql);
		$idingresonew = ejecutarConsulta_retornarID($sql);

		$num_elementos = 0;
		$sw = true;

		while ($num_elementos < count($idarticulo)) {
			$sql_detalle = "INSERT INTO detalle_ingreso(idingreso, idarticulo,cantidad,precio_compra,precio_venta) VALUES ('$idingresonew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$actualizar_art = "UPDATE articulo SET precio_compra='$precio_compra[$num_elementos]',precio_venta='$precio_venta[$num_elementos]' WHERE idarticulo='$idarticulo[$num_elementos]'";
			ejecutarConsulta($actualizar_art) or $sw = false;

			$num_elementos = $num_elementos + 1;
		}

		return $sw;
	}

	public function validarPrecioCompraPrecioVenta($idarticulo, $precio_compra, $precio_venta)
	{
		for ($i = 0; $i < count($idarticulo); $i++) {
			if ($precio_venta[$i] < $precio_compra[$i]) {
				return true;
			}
		}
		return false;
	}

	public function verificarNumeroExiste($num_comprobante, $idalmacen)
	{
		$sql = "SELECT * FROM ingreso WHERE num_comprobante = '$num_comprobante' AND idalmacen = '$idalmacen'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El número ya existe en la tabla
			return true;
		}
		// El número no existe en la tabla
		return false;
	}

	public function verificarSerieExiste($serie_comprobante)
	{
		$sql = "SELECT * FROM ingreso WHERE serie_comprobante = '$serie_comprobante'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La serie ya existe en la tabla
			return true;
		}
		// La serie no existe en la tabla
		return false;
	}

	//Implementamos un método para anular el ingreso
	public function desactivar($idingreso)
	{
		$sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar el ingreso
	public function eliminar($idingreso)
	{
		$sql = "DELETE FROM ingreso WHERE idingreso='$idingreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idingreso)
	{
		$sql = "SELECT i.idingreso,i.idproveedor,p.nombre as proveedor,al.idalmacen,al.ubicacion as almacen,mp.idmetodopago,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON i.idalmacen = al.idalmacen LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idingreso='$idingreso'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idingreso)
	{
		$sql = "SELECT di.idingreso,di.idarticulo,a.nombre,a.stock,di.cantidad,di.precio_compra,di.precio_venta FROM detalle_ingreso di LEFT JOIN articulo a on di.idarticulo=a.idarticulo where di.idingreso='$idingreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT i.idingreso,DATE_FORMAT(i.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,i.idproveedor,p.nombre as proveedor,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON i.idalmacen = al.idalmacen LEFT JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorFecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT i.idingreso,DATE_FORMAT(i.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,i.idproveedor,p.nombre as proveedor,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON i.idalmacen = al.idalmacen LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin' ORDER BY i.idingreso DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT i.idingreso,DATE_FORMAT(i.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,i.idproveedor,p.nombre as proveedor,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON i.idalmacen = al.idalmacen LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idalmacen = '$idalmacenSession' ORDER BY i.idingreso DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT i.idingreso,DATE_FORMAT(i.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,i.idproveedor,p.nombre as proveedor,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON i.idalmacen = al.idalmacen LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idalmacen = '$idalmacenSession' AND DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin' ORDER BY i.idingreso DESC";
		return ejecutarConsulta($sql);
	}

	public function ingresocabecera($idingreso)
	{
		$sql = "SELECT i.idingreso,i.idproveedor,p.nombre as proveedor,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,i.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,DATE_FORMAT(i.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,i.impuesto,i.total_compra FROM ingreso i LEFT JOIN persona p ON i.idproveedor=p.idpersona LEFT JOIN metodo_pago mp ON i.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON i.idalmacen = al.idalmacen LEFT JOIN usuario u ON i.idusuario=u.idusuario WHERE i.idingreso='$idingreso'";
		return ejecutarConsulta($sql);
	}

	public function ingresodetalle($idingreso)
	{
		$sql = "SELECT a.nombre as articulo,a.codigo,a.codigo_producto,d.cantidad,d.precio_compra,d.precio_venta,(d.cantidad*d.precio_compra) as subtotal FROM detalle_ingreso d LEFT JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idingreso='$idingreso'";
		return ejecutarConsulta($sql);
	}

	public function getLastNumComprobante($idalmacen)
	{
		$sql = "SELECT num_comprobante as last_num_comprobante FROM ingreso WHERE idalmacen = '$idalmacen' ORDER BY idingreso DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function getLastSerie()
	{
		$sql = "SELECT serie_comprobante as last_serie_comprobante FROM ingreso ORDER BY idingreso DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}
}
