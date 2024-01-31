<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Venta
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	public function insertar($idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_compra, $precio_venta, $descuento)
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

		// Si no hay errores, continuamos con el registro de la venta
		$sql = "INSERT INTO venta (idusuario,idmetodopago,idalmacen,idcliente,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado)
		VALUES ('$idusuario','$idmetodopago','$idalmacen','$idcliente','$tipo_comprobante','$serie_comprobante','$num_comprobante',SYSDATE(),'$impuesto','$total_venta','Aceptado')";
		//return ejecutarConsulta($sql);
		$idventanew = ejecutarConsulta_retornarID($sql);

		$num_elementos = 0;
		$sw = true;

		while ($num_elementos < count($idarticulo)) {
			$sql_detalle = "INSERT INTO detalle_venta(idventa, idarticulo,cantidad,precio_venta,descuento) VALUES ('$idventanew', '$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$actualizar_art = "UPDATE articulo SET precio_venta='$precio_venta[$num_elementos]' WHERE idarticulo='$idarticulo[$num_elementos]'";
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

	public function validarSubtotalNegativo($idarticulo, $cantidad, $precio_venta, $descuento)
	{
		for ($i = 0; $i < count($idarticulo); $i++) {
			if ((($cantidad[$i] * $precio_venta[$i]) - $descuento[$i]) < 0) {
				return true;
			}
		}
		return false;
	}

	public function verificarNumeroExiste($num_comprobante, $idalmacen)
	{
		$sql = "SELECT * FROM venta WHERE num_comprobante = '$num_comprobante' AND idalmacen = '$idalmacen'";
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
		$sql = "SELECT * FROM venta WHERE serie_comprobante = '$serie_comprobante'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La serie ya existe en la tabla
			return true;
		}
		// La serie no existe en la tabla
		return false;
	}

	//Implementamos un método para anular la venta
	public function anular($idventa)
	{
		$sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($idventa)
	{
		$sql = "DELETE FROM venta WHERE idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idventa)
	{
		$sql = "SELECT v.idventa,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.idmetodopago,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idventa)
	{
		$sql = "SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,a.precio_compra,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv LEFT JOIN articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	public function listarDetallePorProducto($idventa)
	{
		$sql = "SELECT dv.idventa,dv.idarticulo,CONCAT(u.nombre,' ',u.apellido) AS usuario,p.nombre as cliente,mp.nombre as metodo_pago,u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal,a.idarticulo,a.idcategoria,a.codigo,a.codigo_producto,a.stock,a.stock_minimo,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,v.estado FROM detalle_venta dv LEFT JOIN venta v ON v.idventa=dv.idventa LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario LEFT JOIN articulo a on dv.idarticulo=a.idarticulo where dv.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorFecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idalmacen = '$idalmacenSession' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idalmacen = '$idalmacenSession' AND DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' ORDER by v.idventa DESC";
		return ejecutarConsulta($sql);
	}

	public function ventacabecera($idventa)
	{
		$sql = "SELECT v.idventa,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.impuesto,v.total_venta FROM venta v LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($idventa)
	{
		$sql = "SELECT a.nombre as articulo,a.codigo,a.codigo_producto,d.cantidad,d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) as subtotal FROM detalle_venta d LEFT JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
		return ejecutarConsulta($sql);
	}

	public function getLastNumComprobante($idalmacen)
	{
		$sql = "SELECT num_comprobante as last_num_comprobante FROM venta WHERE idalmacen = '$idalmacen' ORDER BY idventa DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function getLastSerie()
	{
		$sql = "SELECT serie_comprobante as last_serie_comprobante FROM venta ORDER BY idventa DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function listarTodosLocalActivosPorUsuario($idalmacen)
	{
		$sql = "SELECT 'cliente' AS tabla, p.idpersona AS id, p.nombre, u.nombre AS usuario FROM persona p LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.tipo_persona='Cliente' AND p.idalmacen='$idalmacen' AND p.eliminado='0'";
		return ejecutarConsulta($sql);
	}
}
