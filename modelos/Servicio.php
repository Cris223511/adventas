<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Servicio
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $idalmacen, $codigo_producto, $nombre, $precio_venta, $descripcion, $imagen)
	{
		if (empty($imagen))
			$imagen = "servicios.jpg";

		$sql = "INSERT INTO servicio (idusuario,idalmacen,codigo_producto,nombre,precio_venta,descripcion,imagen,estado)
		VALUES ('$idusuario','$idalmacen','$codigo_producto','$nombre','$precio_venta','$descripcion','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	public function verificarCodigoProductoExiste($codigo_producto)
	{
		$sql = "SELECT * FROM servicio WHERE codigo_producto = '$codigo_producto'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El código ya existe en la tabla
			return true;
		}
		// El código no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idservicio, $idalmacen, $codigo_producto, $nombre, $precio_venta, $descripcion, $imagen)
	{
		$sql = "UPDATE servicio SET idalmacen='$idalmacen',codigo_producto='$codigo_producto',nombre='$nombre',precio_venta='$precio_venta',descripcion='$descripcion',imagen='$imagen' WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	public function verificarCodigoProductoEditarExiste($codigo_producto, $idservicio)
	{
		$sql = "SELECT * FROM servicio WHERE codigo_producto = '$codigo_producto' AND idservicio != '$idservicio'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El código de artículo ya existe en la tabla
			return true;
		}
		// El código de artículo no existe en la tabla
		return false;
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idservicio)
	{
		$sql = "UPDATE servicio SET estado='0' WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idservicio)
	{
		$sql = "UPDATE servicio SET estado='1' WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar registros
	public function eliminar($idservicio)
	{
		$sql = "UPDATE servicio SET eliminado = '1' WHERE idservicio='$idservicio'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idservicio)
	{
		$sql = "SELECT * FROM servicio WHERE idservicio='$idservicio'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,al.ubicacion as almacen,a.codigo_producto,a.nombre,a.precio_venta,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN almacen al ON a.idalmacen=al.idalmacen WHERE a.eliminado = '0' ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,al.ubicacion as almacen,a.codigo_producto,a.nombre,a.precio_venta,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN almacen al ON a.idalmacen=al.idalmacen WHERE a.eliminado = '0' AND a.idalmacen = '$idalmacenSession' ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,a.codigo_producto,a.nombre,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario WHERE a.eliminado = '0' ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos
	public function listarUsuarioActivos($idalmacenSession)
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,a.codigo_producto,a.nombre,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario WHERE a.eliminado = '0' AND a.idalmacen = '$idalmacenSession' ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_servicio)
	public function listarActivosVenta()
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,a.codigo_producto,a.nombre,a.precio_venta,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN almacen al ON a.idalmacen=al.idalmacen WHERE a.eliminado = '0' ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_servicio)
	public function listarUsuarioActivosVenta($idusuario)
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,a.codigo_producto,a.nombre,a.precio_venta,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN almacen al ON a.idalmacen=al.idalmacen WHERE a.eliminado = '0' AND a.idusuario = '$idusuario' ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_servicio)
	public function listarActivosVentaPorArticulo($idservicio)
	{
		$sql = "SELECT a.idservicio,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,al.ubicacion as almacen,a.codigo_producto,a.nombre,a.precio_venta,a.descripcion,a.imagen,a.estado FROM servicio a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN almacen al ON a.idalmacen=al.idalmacen WHERE a.eliminado = '0' AND a.idservicio = $idservicio ORDER BY a.idservicio DESC";
		return ejecutarConsulta($sql);
	}

	/* ======================= SELECTS ======================= */

	public function listarTodosActivos()
	{
		$sql = "SELECT 'almacen' AS tabla, l.idalmacen AS id, l.ubicacion, u.nombre AS usuario, local_ruc AS ruc FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idusuario <> 0 AND l.estado='activado' AND l.eliminado='0'";

		return ejecutarConsulta($sql);
	}

	public function listarTodosActivosPorUsuario($idusuario, $idalmacen)
	{
		$sql = "SELECT 'almacen' AS tabla, l.idalmacen AS id, l.ubicacion, u.nombre AS usuario, local_ruc AS ruc FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen='$idalmacen' AND l.idusuario <> 0 AND l.estado='activado' AND l.eliminado='0'";

		return ejecutarConsulta($sql);
	}
}
