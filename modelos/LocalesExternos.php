<?php
require "../config/Conexion.php";

class LocalExterno
{
	public function __construct() {}

	public function agregar($idusuario, $ubicacion, $local_ruc, $descripcion, $imagen)
	{
		date_default_timezone_set("America/Lima");
		$sql = "INSERT INTO almacen (idusuario, ubicacion, local_ruc, descripcion, imagen, fecha_hora, estado, eliminado)
            VALUES ('$idusuario','$ubicacion','$local_ruc','$descripcion', '$imagen', SYSDATE(), 'activado', '0')";
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

	public function editar($idalmacen, $ubicacion, $local_ruc, $descripcion, $imagen)
	{
		$sql = "UPDATE almacen SET ubicacion='$ubicacion',local_ruc='$local_ruc',descripcion='$descripcion',imagen='$imagen' WHERE idalmacen='$idalmacen'";
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

	// todos los almacen

	public function listar($idalmacen)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen <> '$idalmacen' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorFecha($idalmacen, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen <> '$idalmacen' AND l.eliminado = '0' AND DATE(l.fecha_hora) >= '$fecha_inicio' AND DATE(l.fecha_hora) <= '$fecha_fin' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuario($idalmacen)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen <> '$idalmacen' AND u.idalmacen = '$idalmacen' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioFecha($idalmacen, $fecha_inicio, $fecha_fin)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen <> '$idalmacen' AND u.idalmacen = '$idalmacen' AND l.eliminado = '0' AND DATE(l.fecha_hora) >= '$fecha_inicio' AND DATE(l.fecha_hora) <= '$fecha_fin' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarActivos($idalmacen)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen <> '$idalmacen' AND l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarActivosASC($idalmacen)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen <> '$idalmacen' AND l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen ASC";
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

	public function listarPorUsuarioActivos($idusuario, $idalmacen)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idusuario = '$idusuario' AND l.idalmacen <> '$idalmacen' AND l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen DESC";
		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioActivosASC($idusuario, $idalmacen)
	{
		$sql = "SELECT l.idalmacen, u.idusuario, u.nombre as nombre, u.cargo as cargo, l.ubicacion, l.local_ruc, l.descripcion, l.imagen, DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha, l.estado FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idusuario = '$idusuario' AND l.idalmacen <> '$idalmacen' AND l.estado='activado' AND l.eliminado = '0' ORDER BY l.idalmacen ASC";
		return ejecutarConsulta($sql);
	}
}
