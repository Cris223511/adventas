<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Proforma
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	public function insertar($idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_comprobante, $num_proforma, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_compra, $precio_venta, $descuento)
	{
		// Inicializar variable de mensaje
		$mensajeError = "";

		// Primero, debemos verificar si hay suficiente stock para cada artículo
		$error = $this->validarStock($idarticulo, $cantidad);
		if ($error) {
			// Si hay un error, no se puede insertar
			$mensajeError = "Una de las cantidades superan al stock normal del artículo.";
		}

		// Luego verificamos si el subtotal es negativo
		$error = $this->validarSubtotalNegativo($idarticulo, $cantidad, $precio_venta, $descuento);
		if ($error) {
			// Si cumple, o sea si es verdadero, asignamos el mensaje correspondiente
			$mensajeError = "El subtotal de uno de los artículos no puede ser menor a 0.";
		}

		// Luego verificamos si el precio de venta es menor al precio de compra
		$error = $this->validarPrecioCompraPrecioVenta($idarticulo, $precio_compra, $precio_venta);
		if ($error) {
			// Si cumple, o sea si es verdadero, no se puede insertar
			$mensajeError = "El precio de venta de uno de los artículos no puede ser menor al precio de compra.";
		}

		// Si hay un mensaje de error, retornar false y mostrar el mensaje en el script principal
		if ($mensajeError !== "") {
			return $mensajeError;
		}

		// Si no hay errores, continuamos con el registro de la proforma
		$sql = "INSERT INTO proforma (idusuario,idmetodopago,idalmacen,idcliente,tipo_comprobante,serie_comprobante,num_proforma,fecha_hora,impuesto,total_venta,estado)
		VALUES ('$idusuario','$idmetodopago','$idalmacen','$idcliente','$tipo_comprobante','$serie_comprobante','$num_proforma',SYSDATE(),'$impuesto','$total_venta','Pendiente')";
		//return ejecutarConsulta($sql);
		$idproformanew = ejecutarConsulta_retornarID($sql);

		$num_elementos = 0;
		$sw = true;

		while ($num_elementos < count($idarticulo)) {
			$sql_detalle = "INSERT INTO detalle_proforma(idproforma,idarticulo,cantidad,precio_venta,descuento) VALUES ('$idproformanew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$actualizar_art = "UPDATE articulo SET precio_venta='$precio_venta[$num_elementos]' WHERE idarticulo='$idarticulo[$num_elementos]'";
			ejecutarConsulta($actualizar_art) or $sw = false;

			$num_elementos = $num_elementos + 1;
		}

		return $sw;
	}

	public function insertar2($idproforma, $idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_comprobante, $num_proforma, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento)
	{
		$sql1 = "INSERT INTO venta (idusuario,idmetodopago,idalmacen,idcliente,tipo_comprobante,serie_comprobante, num_comprobante,fecha_hora,impuesto,total_venta,estado)
		VALUES ('$idusuario','$idmetodopago','$idalmacen','$idcliente','$tipo_comprobante','$serie_comprobante','$num_proforma',SYSDATE(),'$impuesto','$total_venta','Aceptado')";
		$idventanew = ejecutarConsulta_retornarID($sql1);
		$num_elementos = 0;
		$sw = true;
		while ($num_elementos < count($idarticulo)) {
			$sql_detalle = "INSERT INTO detalle_venta(idventa,idarticulo,cantidad,precio_venta,descuento) VALUES ('$idventanew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos = $num_elementos + 1;
		}

		$sql2 = "UPDATE proforma SET estado='Finalizado' WHERE idproforma='$idproforma'";
		ejecutarConsulta($sql2);

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

	public function validarStock($idarticulo, $cantidad)
	{
		for ($i = 0; $i < count($idarticulo); $i++) {
			$sql = "SELECT stock FROM articulo WHERE idarticulo = '$idarticulo[$i]'";
			$res = ejecutarConsultaSimpleFila($sql);
			$stockActual = $res['stock'];
			if ($cantidad[$i] > $stockActual) {
				return true;
			}
		}
		return false;
	}

	// Luego verificamos si el subtotal es negativo
	public function validarSubtotalNegativo($idarticulo, $cantidad, $precio_venta, $descuento)
	{
		for ($i = 0; $i < count($idarticulo); $i++) {
			if ((($cantidad[$i] * $precio_venta[$i]) - $descuento[$i]) < 0) {
				return true;
			}
		}
		return false;
	}

	public function verificarNumeroExiste($num_proforma, $idalmacen)
	{
		$sql = "SELECT * FROM proforma WHERE num_proforma = '$num_proforma' AND idalmacen = '$idalmacen'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El número ya existe en la tabla
			return true;
		}
		// El número no existe en la tabla
		return false;
	}

	public function verificarSerieExiste($num_proforma)
	{
		$sql = "SELECT * FROM proforma WHERE num_proforma = '$num_proforma'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La serie ya existe en la tabla
			return true;
		}
		// La serie no existe en la tabla
		return false;
	}

	//Implementamos un método para anular la proforma
	public function anular($idproforma)
	{
		$sql = "UPDATE proforma SET estado='Anulado' WHERE idproforma='$idproforma'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar la proforma
	public function activar($idproforma)
	{
		$sql = "UPDATE proforma SET estado='Pendiente' WHERE idproforma='$idproforma'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($idproforma)
	{
		$sql = "DELETE FROM proforma WHERE idproforma='$idproforma'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproforma)
	{
		$sql = "SELECT p.idproforma,p.idcliente,pe.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.idmetodopago,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,p.tipo_comprobante,p.serie_comprobante,p.num_proforma,p.total_venta,p.impuesto,p.estado FROM proforma p LEFT JOIN metodo_pago mp ON p.idmetodopago = mp.idmetodopago LEFT JOIN persona pe ON p.idcliente=pe.idpersona LEFT JOIN almacen al ON p.idalmacen = al.idalmacen LEFT JOIN usuario u ON p.idusuario=u.idusuario WHERE p.idproforma='$idproforma'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idproforma)
	{
		$sql = "SELECT dp.idproforma,dp.idarticulo,a.nombre,dp.cantidad,dp.precio_venta,a.precio_compra,dp.descuento,(dp.cantidad*dp.precio_venta-dp.descuento) as subtotal FROM detalle_proforma dp LEFT JOIN articulo a on dp.idarticulo=a.idarticulo WHERE dp.idproforma='$idproforma'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT p.idproforma,DATE_FORMAT(p.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,p.idcliente,pe.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,p.tipo_comprobante,p.serie_comprobante,p.num_proforma,p.total_venta,p.impuesto,p.estado FROM proforma p LEFT JOIN metodo_pago mp ON p.idmetodopago = mp.idmetodopago LEFT JOIN persona pe ON p.idcliente=pe.idpersona LEFT JOIN almacen al ON p.idalmacen = al.idalmacen LEFT JOIN usuario u ON p.idusuario=u.idusuario ORDER BY p.idproforma DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT p.idproforma,DATE_FORMAT(p.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,p.idcliente,pe.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,p.tipo_comprobante,p.serie_comprobante,p.num_proforma,p.total_venta,p.impuesto,p.estado FROM proforma p LEFT JOIN metodo_pago mp ON p.idmetodopago = mp.idmetodopago LEFT JOIN persona pe ON p.idcliente=pe.idpersona LEFT JOIN almacen al ON p.idalmacen = al.idalmacen LEFT JOIN usuario u ON p.idusuario=u.idusuario WHERE p.idalmacen = '$idalmacenSession' ORDER BY p.idproforma DESC";
		return ejecutarConsulta($sql);
	}

	public function proformacabecera($idproforma)
	{
		$sql = "SELECT p.idproforma,p.idcliente,pe.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,pe.direccion,pe.tipo_documento,pe.num_documento,pe.email,pe.telefono,p.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,p.tipo_comprobante,p.serie_comprobante,p.num_proforma,DATE_FORMAT(p.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,p.impuesto,p.total_venta FROM proforma p LEFT JOIN metodo_pago mp ON p.idmetodopago = mp.idmetodopago LEFT JOIN persona pe ON p.idcliente=pe.idpersona LEFT JOIN almacen al ON p.idalmacen = al.idalmacen LEFT JOIN usuario u ON p.idusuario=u.idusuario WHERE p.idproforma='$idproforma'";
		return ejecutarConsulta($sql);
	}

	public function proformadetalle($idproforma)
	{
		$sql = "SELECT a.nombre as articulo,a.codigo,a.codigo_producto,dp.cantidad,dp.precio_venta,dp.descuento,(dp.cantidad*dp.precio_venta-dp.descuento) as subtotal FROM detalle_proforma dp LEFT JOIN articulo a ON dp.idarticulo=a.idarticulo WHERE dp.idproforma='$idproforma'";
		return ejecutarConsulta($sql);
	}

	public function getLastNumProforma($idalmacen)
	{
		$sql = "SELECT num_proforma as last_num_proforma FROM proforma WHERE idalmacen = '$idalmacen' ORDER BY idproforma DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function getLastSerie()
	{
		$sql = "SELECT serie_comprobante as last_serie_comprobante FROM proforma ORDER BY idproforma DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function listarTodosLocalActivosPorUsuario($idalmacen)
	{
		$sql = "SELECT 'cliente' AS tabla, p.idpersona AS id, p.nombre, u.nombre AS usuario FROM persona p LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.tipo_persona='Cliente' AND p.idalmacen='$idalmacen' AND p.eliminado='0'";
		return ejecutarConsulta($sql);
	}
}
