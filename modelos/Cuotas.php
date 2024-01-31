<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Cuotas
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $idmetodopago, $idcliente, $idvendedor, $idzona, $idalmacen, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_compra, $precio_venta, $descuento)
	{
		$datetime = new DateTime("", new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');
		$datetime->setTimezone(new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');

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

		$sql = "INSERT INTO cuotas (idusuario,idmetodopago,idcliente,idvendedor,idzona,idalmacen,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,fecha_anulado,impuesto,total_venta,estado)
		VALUES ('$idusuario','$idmetodopago','$idcliente','$idvendedor','$idzona','$idalmacen','$tipo_comprobante','$serie_comprobante','$num_comprobante','$orderDate','0000-00-00 00:00:00.000000','$impuesto','$total_venta','Deuda')";
		//return ejecutarConsulta($sql);
		$idcuotasnew = ejecutarConsulta_retornarID($sql);

		$num_elementos = 0;
		$sw = true;

		while ($num_elementos < count($idarticulo)) {
			$sql_detalle = "INSERT INTO detalle_cuotas(idcuotas, idarticulo,cantidad,precio_venta,descuento) VALUES ('$idcuotasnew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
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
		$sql = "SELECT * FROM cuotas WHERE num_comprobante = '$num_comprobante' AND idalmacen = '$idalmacen'";
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
		$sql = "SELECT * FROM cuotas WHERE serie_comprobante = '$serie_comprobante'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La serie ya existe en la tabla
			return true;
		}
		// La serie no existe en la tabla
		return false;
	}

	//Implementamos un método para insertar registros
	public function insertarpagos($idcuotasparam, $metodo_pago, $concepto, $monto)
	{
		$datetime = new DateTime("", new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');
		$datetime->setTimezone(new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');

		$sql = "INSERT INTO detalle_pagos(idcuotas, metodo_pago,concepto,monto,fecha_pago) VALUES ('$idcuotasparam', '$metodo_pago','$concepto','$monto','$orderDate')";
		return ejecutarConsulta($sql);
	}

	public function actualizarEstadoPagado($id)
	{
		$sql = "UPDATE cuotas SET estado='Pagado' WHERE idcuotas='$id'";
		return ejecutarConsulta($sql);
	}

	public function actualizarEstadoDebe($id)
	{
		$sql = "UPDATE cuotas SET estado='Deuda' WHERE idcuotas='$id'";
		return ejecutarConsulta($sql);
	}

	public function actualizarTotalPago($total, $id)
	{
		$sql = "UPDATE cuotas SET monto_pagado='$total' WHERE idcuotas='$id'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para anular las cuotas
	public function desactivar($idcuotas)
	{
		$datetime = new DateTime("", new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');
		$datetime->setTimezone(new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');

		$sql = "UPDATE cuotas SET fecha_anulado='$orderDate', estado='Anulado' WHERE idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar las cuotas
	public function eliminar($idcuotas)
	{
		$sql = "DELETE FROM cuotas WHERE idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcuotas)
	{
		$sql = "SELECT cu.idcuotas, cu.fecha_hora as fecha, cu.fecha_anulado as anulado, z.idzona as idzona, al.idalmacen as idalmacen, cu.idcliente, CONCAT(ucl.nombre,' ',ucl.apellido) as cliente, CONCAT(ucv.nombre,' ',ucv.apellido) as vendedor, ucl.idusuario as idcliente, ucv.idusuario as idvendedor, u.idusuario, CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo, z.ubicacion as ubicacion, z.zona as zona, al.ubicacion as almacen, mp.idmetodopago, mp.nombre as metodo_pago, cu.tipo_comprobante, cu.serie_comprobante, cu.num_comprobante, cu.total_venta, cu.monto_pagado, cu.impuesto, cu.estado 
		FROM cuotas cu 
		LEFT JOIN usuario ucl ON cu.idcliente = ucl.idusuario
		LEFT JOIN usuario ucv ON cu.idvendedor = ucv.idusuario
		LEFT JOIN usuario u ON cu.idusuario = u.idusuario
		LEFT JOIN zonas z ON cu.idzona = z.idzona
		LEFT JOIN almacen al ON cu.idalmacen = al.idalmacen
		LEFT JOIN metodo_pago mp ON cu.idmetodopago = mp.idmetodopago
		WHERE cu.idcuotas = '$idcuotas'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT cu.idcuotas,
                   DATE_FORMAT(cu.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,
                   DATE_FORMAT(cu.fecha_anulado, '%d-%m-%Y %H:%i:%s') AS anulado,
                   z.idzona AS idzona,
                   al.idalmacen AS idalmacen,
                   al.ubicacion AS almacen,
				   mp.nombre as metodo_pago,
                   CONCAT(u.nombre,' ',u.apellido) AS usuario,
				   u.idusuario, u.cargo AS cargo,
				   u.apellido AS apellido,
                   z.ubicacion AS ubicacion,
                   z.zona AS zona,
				   CONCAT(ucl.nombre,' ',ucl.apellido) as cliente,
				   CONCAT(ucv.nombre,' ',ucv.apellido) as vendedor,
				   ucl.apellido as cliente_apellido,
				   ucv.apellido as vendedor_apellido,
                   cu.tipo_comprobante,
                   cu.serie_comprobante,
                   cu.num_comprobante,
                   cu.total_venta,
                   cu.monto_pagado,
                   cu.impuesto,
                   cu.estado
            FROM cuotas cu
            LEFT JOIN usuario u ON cu.idusuario = u.idusuario
            LEFT JOIN zonas z ON cu.idzona = z.idzona
            LEFT JOIN almacen al ON cu.idalmacen = al.idalmacen
			LEFT JOIN usuario ucl ON cu.idcliente = ucl.idusuario
			LEFT JOIN usuario ucv ON cu.idvendedor = ucv.idusuario
			LEFT JOIN metodo_pago mp ON cu.idmetodopago = mp.idmetodopago
			ORDER by cu.idcuotas DESC";

		return ejecutarConsulta($sql);
	}

	public function listarPorFecha($fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT cu.idcuotas,
                   DATE_FORMAT(cu.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,
                   DATE_FORMAT(cu.fecha_anulado, '%d-%m-%Y %H:%i:%s') AS anulado,
                   z.idzona AS idzona,
                   al.idalmacen AS idalmacen,
                   al.ubicacion AS almacen,
				   mp.nombre as metodo_pago,
                   CONCAT(u.nombre,' ',u.apellido) AS usuario,
				   u.idusuario, u.cargo AS cargo,
				   u.apellido AS apellido,
                   z.ubicacion AS ubicacion,
                   z.zona AS zona,
				   CONCAT(ucl.nombre,' ',ucl.apellido) as cliente,
				   CONCAT(ucv.nombre,' ',ucv.apellido) as vendedor,
				   ucl.apellido as cliente_apellido,
				   ucv.apellido as vendedor_apellido,
                   cu.tipo_comprobante,
                   cu.serie_comprobante,
                   cu.num_comprobante,
                   cu.total_venta,
                   cu.monto_pagado,
                   cu.impuesto,
                   cu.estado
            FROM cuotas cu
            LEFT JOIN usuario u ON cu.idusuario = u.idusuario
            LEFT JOIN zonas z ON cu.idzona = z.idzona
            LEFT JOIN almacen al ON cu.idalmacen = al.idalmacen
			LEFT JOIN usuario ucl ON cu.idcliente = ucl.idusuario
			LEFT JOIN usuario ucv ON cu.idvendedor = ucv.idusuario
			LEFT JOIN metodo_pago mp ON cu.idmetodopago = mp.idmetodopago
			WHERE DATE(cu.fecha_hora) >= '$fecha_inicio' AND DATE(cu.fecha_hora) <= '$fecha_fin'
			ORDER by cu.idcuotas DESC";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT cu.idcuotas,
                   DATE_FORMAT(cu.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,
                   DATE_FORMAT(cu.fecha_anulado, '%d-%m-%Y %H:%i:%s') AS anulado,
                   z.idzona AS idzona,
                   al.idalmacen AS idalmacen,
                   al.ubicacion AS almacen,
				   mp.nombre as metodo_pago,
                   CONCAT(u.nombre,' ',u.apellido) AS usuario,
				   u.idusuario, u.cargo AS cargo,
				   u.apellido AS apellido,
                   z.ubicacion AS ubicacion,
                   z.zona AS zona,
				   CONCAT(ucl.nombre,' ',ucl.apellido) as cliente,
				   CONCAT(ucv.nombre,' ',ucv.apellido) as vendedor,
				   ucl.apellido as cliente_apellido,
				   ucv.apellido as vendedor_apellido,
                   cu.tipo_comprobante,
                   cu.serie_comprobante,
                   cu.num_comprobante,
                   cu.total_venta,
                   cu.monto_pagado,
                   cu.impuesto,
                   cu.estado
            FROM cuotas cu
            LEFT JOIN usuario u ON cu.idusuario = u.idusuario
            LEFT JOIN zonas z ON cu.idzona = z.idzona
            LEFT JOIN almacen al ON cu.idalmacen = al.idalmacen
			LEFT JOIN usuario ucl ON cu.idcliente = ucl.idusuario
			LEFT JOIN usuario ucv ON cu.idvendedor = ucv.idusuario
			LEFT JOIN metodo_pago mp ON cu.idmetodopago = mp.idmetodopago
			WHERE cu.idalmacen = '$idalmacenSession'
			ORDER by cu.idcuotas DESC";

		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT cu.idcuotas,
					   DATE_FORMAT(cu.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,
					   DATE_FORMAT(cu.fecha_anulado, '%d-%m-%Y %H:%i:%s') AS anulado,
					   z.idzona AS idzona,
					   al.idalmacen AS idalmacen,
					   al.ubicacion AS almacen, mp.nombre as metodo_pago,
					   CONCAT(u.nombre,' ',u.apellido) AS usuario,
					   u.idusuario, u.cargo AS cargo,
					   u.apellido AS apellido,
					   z.ubicacion AS ubicacion,
					   z.zona AS zona,
					   CONCAT(ucl.nombre,' ',ucl.apellido) as cliente,
					   CONCAT(ucv.nombre,' ',ucv.apellido) as vendedor,
					   ucl.apellido as cliente_apellido,
					   ucv.apellido as vendedor_apellido,
					   cu.tipo_comprobante,
					   cu.serie_comprobante,
					   cu.num_comprobante,
					   cu.total_venta,
					   cu.monto_pagado,
					   cu.impuesto,
					   cu.estado
				FROM cuotas cu
				LEFT JOIN usuario u ON cu.idusuario = u.idusuario
				LEFT JOIN zonas z ON cu.idzona = z.idzona
				LEFT JOIN almacen al ON cu.idalmacen = al.idalmacen
				LEFT JOIN usuario ucl ON cu.idcliente = ucl.idusuario
				LEFT JOIN usuario ucv ON cu.idvendedor = ucv.idusuario
				LEFT JOIN metodo_pago mp ON cu.idmetodopago = mp.idmetodopago
				WHERE cu.idalmacen = '$idalmacenSession'
				AND DATE(cu.fecha_hora) >= '$fecha_inicio' AND DATE(cu.fecha_hora) <= '$fecha_fin'
				ORDER by cu.idcuotas DESC";

		return ejecutarConsulta($sql);
	}

	public function listarDetalle($idcuotas)
	{
		$sql = "SELECT dc.idcuotas,dc.idarticulo,a.nombre,dc.cantidad,dc.precio_venta,a.precio_compra,dc.descuento,(dc.cantidad*dc.precio_venta-dc.descuento) as subtotal FROM detalle_cuotas dc LEFT JOIN articulo a on dc.idarticulo=a.idarticulo where dc.idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	public function listarDetalleCuota($idcuotas)
	{
		$sql = "SELECT dc.idcuotas,a.codigo,dc.idarticulo,a.nombre,dc.cantidad,dc.precio_venta,dc.descuento,(dc.cantidad*dc.precio_venta-dc.descuento) as subtotal FROM detalle_cuotas dc LEFT JOIN articulo a on dc.idarticulo=a.idarticulo where dc.idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	public function listarDetallePago($idcuotas)
	{
		$sql = "SELECT * FROM detalle_pagos dp where dp.idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	public function contarDetallePago($idcuotas)
	{
		$sql = "SELECT COUNT(*) FROM detalle_pagos dp where dp.idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarAlmacen()
	{
		$sql = "SELECT * FROM almacen";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros 
	public function listarZona()
	{
		$sql = "SELECT * FROM zonas";
		return ejecutarConsulta($sql);
	}

	public function ventacabecera($idcuotas)
	{
		$sql = "SELECT cu.idcuotas,cu.idcliente,DATE_FORMAT(cu.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,z.idzona as idzona,al.idalmacen as idalmacen,CONCAT(ucl.nombre,' ',ucl.apellido) as cliente,CONCAT(ucv.nombre,' ',ucv.apellido) as vendedor,ucl.idusuario as idcliente,ucv.idusuario as idvendedor,ucl.tipo_documento as tipo_documentoc,ucl.num_documento as num_documentoc,ucl.direccion as direccionc,ucl.email as emailc,ucl.telefono as telefonoc,ucv.tipo_documento as tipo_documentov,ucv.num_documento as num_documentov,ucv.direccion as direccionv,ucv.email as emailv,ucv.telefono as telefonov,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,z.ubicacion as ubicacion,z.zona as zona,al.ubicacion as almacen, mp.nombre as metodo_pago,cu.tipo_comprobante,cu.serie_comprobante,cu.num_comprobante,cu.total_venta,cu.impuesto,cu.estado FROM cuotas cu LEFT JOIN metodo_pago mp ON cu.idmetodopago = mp.idmetodopago LEFT JOIN usuario ucl ON cu.idcliente=ucl.idusuario LEFT JOIN usuario ucv ON cu.idvendedor=ucv.idusuario LEFT JOIN usuario u ON cu.idusuario=u.idusuario LEFT JOIN zonas z ON cu.idzona=z.idzona LEFT JOIN almacen al ON cu.idalmacen=al.idalmacen WHERE cu.idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	public function ventadetalle($idcuotas)
	{
		$sql = "SELECT a.nombre as articulo,a.codigo,a.codigo_producto,dc.cantidad,dc.precio_venta,dc.descuento,(dc.cantidad*dc.precio_venta-dc.descuento) as subtotal FROM detalle_cuotas dc LEFT JOIN articulo a ON dc.idarticulo=a.idarticulo WHERE dc.idcuotas='$idcuotas'";
		return ejecutarConsulta($sql);
	}

	public function getLastNumComprobante($idalmacen)
	{
		$sql = "SELECT num_comprobante as last_num_comprobante FROM cuotas WHERE idalmacen = '$idalmacen' ORDER BY idcuotas DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}

	public function getLastSerie()
	{
		$sql = "SELECT serie_comprobante as last_serie_comprobante FROM cuotas ORDER BY idcuotas DESC LIMIT 1";
		return ejecutarConsulta($sql);
	}
}
