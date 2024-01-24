<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class VentaServicio
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	public function insertar($idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total_venta, $idservicio, $cantidad, $precio_venta, $descuento)
	{
		// Inicializar variable de mensaje
		$mensajeError = "";

		// Primero, debemos verificar si el subtotal es negativo
		$error = $this->validarSubtotalNegativo($idservicio, $cantidad, $precio_venta, $descuento);
		if ($error) {
			// Si cumple, o sea si es verdadero, asignamos el mensaje correspondiente
			$mensajeError = "El subtotal de uno de los artículos no puede ser menor a 0.";
		}

		// Si hay un mensaje de error, retornar false y mostrar el mensaje en el script principal
		if ($mensajeError !== "") {
			return $mensajeError;
		}

		// Si no hay errores, continuamos con el registro de la venta
		$sql = "INSERT INTO venta_servicio (idusuario,idmetodopago,idalmacen,idcliente,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_venta,estado)
		VALUES ('$idusuario','$idmetodopago','$idalmacen','$idcliente','$tipo_comprobante','$serie_comprobante','$num_comprobante',SYSDATE(),'$impuesto','$total_venta','Aceptado')";
		//return ejecutarConsulta($sql);
		$idventa_servicionew = ejecutarConsulta_retornarID($sql);

		$num_elementos2 = 0;
		$sw = true;

		while ($num_elementos2 < count($idservicio)) {
			$sql_detalle = "INSERT INTO detalle_servicio(idventa_servicio, idservicio,cantidad,precio_venta,descuento) VALUES ('$idventa_servicionew', '$idservicio[$num_elementos2]','$cantidad[$num_elementos2]','$precio_venta[$num_elementos2]','$descuento[$num_elementos2]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$actualizar_serv = "UPDATE servicio SET precio_venta='$precio_venta[$num_elementos2]' WHERE idservicio='$idservicio[$num_elementos2]'";
			ejecutarConsulta($actualizar_serv) or $sw = false;

			$num_elementos2 = $num_elementos2 + 1;
		}

		return $sw;
	}

	public function validarSubtotalNegativo($idservicio, $cantidad, $precio_venta, $descuento)
	{
		for ($i = 0; $i < count($idservicio); $i++) {
			if ((($cantidad[$i] * $precio_venta[$i]) - $descuento[$i]) < 0) {
				return true;
			}
		}
		return false;
	}

	public function verificarNumeroExiste($num_comprobante, $idalmacen)
	{
		$sql = "SELECT * FROM venta_servicio WHERE num_comprobante = '$num_comprobante' AND idalmacen = '$idalmacen'";
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
		$sql = "SELECT * FROM venta_servicio WHERE serie_comprobante = '$serie_comprobante'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La serie ya existe en la tabla
			return true;
		}
		// La serie no existe en la tabla
		return false;
	}

	//Implementamos un método para anular la venta
	public function anular($idventa_servicio)
	{
		$sql = "UPDATE venta_servicio SET estado='Anulado' WHERE idventa_servicio='$idventa_servicio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($idventa_servicio)
	{
		$sql = "DELETE FROM venta_servicio WHERE idventa_servicio='$idventa_servicio'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idventa_servicio)
	{
		$sql = "SELECT v.idventa_servicio,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.idmetodopago,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa_servicio='$idventa_servicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idventa_servicio)
	{
		$sql = "SELECT dv.idventa_servicio,dv.idservicio,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_servicio dv LEFT JOIN servicio a on dv.idservicio=a.idservicio where dv.idventa_servicio='$idventa_servicio'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorFecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idalmacen = '$idalmacenSession' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT v.idventa_servicio,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta_servicio v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idalmacen = '$idalmacenSession' AND DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' ORDER by v.idventa_servicio DESC";
		return ejecutarConsulta($sql);
	}

	public function ventacabecera($idventa_servicio)
	{
		$sql = "SELECT v.idventa_servicio,v.idcliente,p.nombre as cliente,al.idalmacen,al.ubicacion as almacen,mp.nombre as metodo_pago,p.direccion,p.tipo_documento,p.num_documento,p.email,p.telefono,v.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,v.tipo_comprobante,v.serie_comprobante,v.num_comprobante,DATE_FORMAT(v.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,v.impuesto,v.total_venta FROM venta_servicio v LEFT JOIN metodo_pago mp ON v.idmetodopago = mp.idmetodopago LEFT JOIN persona p ON v.idcliente=p.idpersona LEFT JOIN almacen al ON v.idalmacen = al.idalmacen LEFT JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa_servicio='$idventa_servicio'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($idventa_servicio)
	{
		$sql = "SELECT a.nombre as servicio,a.codigo_producto,d.cantidad,d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) as subtotal FROM detalle_servicio d LEFT JOIN servicio a ON d.idservicio=a.idservicio WHERE d.idventa_servicio='$idventa_servicio'";
		return ejecutarConsulta($sql);
	}

	public function getLastNumComprobante($idalmacen)
	{
		$sql = "SELECT num_comprobante as last_num_comprobante FROM venta_servicio WHERE idalmacen = '$idalmacen' ORDER BY idventa_servicio DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function getLastSerie()
	{
		$sql = "SELECT serie_comprobante as last_serie_comprobante FROM venta_servicio ORDER BY idventa_servicio DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function listarTodosLocalActivosPorUsuario($idalmacen)
	{
		$sql = "SELECT 'cliente' AS tabla, p.idpersona AS id, p.nombre, u.nombre AS usuario FROM persona p LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.tipo_persona='Cliente' AND p.idalmacen='$idalmacen' AND p.eliminado='0'";
		return ejecutarConsulta($sql);
	}
}
