<?php
require "../config/Conexion.php";

class Local
{
	public function __construct()
	{
	}

	public function agregar($idusuario, $ubicacion, $local_ruc, $descripcion)
	{
		date_default_timezone_set("America/Lima");
		$sql = "INSERT INTO almacen (idusuario, ubicacion, local_ruc, descripcion, fecha_hora, estado, eliminado)
            VALUES ('$idusuario','$ubicacion','$local_ruc','$descripcion', SYSDATE(), 'activado', '0')";
		return ejecutarConsulta($sql);
	}

	public function verificarNombreExiste($ubicacion)
	{
		$sql = "SELECT * FROM almacen WHERE ubicacion = '$ubicacion' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El ubicacion ya existe en la tabla
			return true;
		}
		// El ubicacion no existe en la tabla
		return false;
	}

	public function verificarNombreEditarExiste($ubicacion, $idalmacen)
	{
		$sql = "SELECT * FROM almacen WHERE ubicacion = '$ubicacion' AND idalmacen != '$idalmacen' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El ubicacion ya existe en la tabla
			return true;
		}
		// El ubicacion no existe en la tabla
		return false;
	}

	public function editar($idalmacen, $ubicacion, $local_ruc, $descripcion)
	{
		$sql = "UPDATE almacen SET ubicacion='$ubicacion',local_ruc='$local_ruc',descripcion='$descripcion' WHERE idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	public function desactivar($idalmacen)
	{
		$sql = "UPDATE almacen SET estado='desactivado' WHERE idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	public function activar($idalmacen)
	{
		$sql = "UPDATE almacen SET estado='activado' WHERE idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($idalmacen)
	{
		$sql = "SELECT * FROM almacen WHERE idalmacen='$idalmacen'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function eliminar($idalmacen)
	{
		$sql = "UPDATE almacen SET eliminado = '1' WHERE idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	// todos los almacenes

	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen = '$idalmacenSession' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen = '$idalmacenSession' AND l.eliminado = '0' AND DATE(l.fecha_hora) >= '$fecha_inicio' AND DATE(l.fecha_hora) <= '$fecha_fin' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarActivos()
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarActivosASC()
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen ASC";
		return ejecutarConsulta($sql);
	}

	public function listarUsuariosPorLocal($idalmacen)
	{
		$sql = "SELECT
					u.idusuario,
					u.idalmacen,
					u.nombre,
					l.ubicacion as local,
					l.local_ruc as local_ruc,
					u.tipo_documento,
					u.num_documento,
					u.direccion,
					u.telefono,
					u.email,
					u.cargo,
					u.login,
					u.clave,
					u.imagen,
					u.estado
				FROM usuario u
				LEFT JOIN almacen l ON u.idalmacen = l.idalmacen
				WHERE u.idalmacen = '$idalmacen' AND u.eliminado = '0' ORDER BY u.idusuario DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioActivos($idalmacenSession)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen = '$idalmacenSession' AND l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioActivosASC($idalmacenSession)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen = '$idalmacenSession' AND l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen ASC";
		return ejecutarConsulta($sql);
	}

	// almacenes disponibles

	public function listarLocalesDisponibles()
	{
		$sql = "SELECT 
				  l.idalmacen,
				  u.idusuario,
				  u.nombre as nombre,
				  u.cargo as cargo,
				  l.ubicacion,
				  l.local_ruc,
				  l.descripcion,
				  DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,
				  l.estado
				FROM almacen l 
				LEFT JOIN usuario u ON l.idusuario = u.idusuario 
				WHERE l.idusuario = '0'
				AND l.eliminado = '0'
				ORDER BY l.idalmacen DESC";

		return ejecutarConsulta($sql);
	}

	public function listarLocalesDisponiblesActivos()
	{
		$sql = "SELECT 
				  l.idalmacen,
				  u.idusuario,
				  u.nombre as nombre,
				  u.cargo as cargo,
				  l.ubicacion,
				  l.local_ruc,
				  l.descripcion,
				  DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha,
				  l.estado
				FROM almacen l 
				LEFT JOIN usuario u ON l.idusuario = u.idusuario 
				WHERE l.idusuario = '0'
				AND l.estado='activado'
				AND l.eliminado = '0'
				ORDER BY l.idalmacen DESC";

		return ejecutarConsulta($sql);
	}
}
