<?php
require "../config/Conexion.php";

class LocalDisponible
{
	public function __construct()
	{
	}

	public function agregar($ubicacion, $local_ruc, $descripcion)
	{
		date_default_timezone_set("America/Lima");
		$sql = "INSERT INTO almacen (idusuario, ubicacion, local_ruc, descripcion, fecha_hora, estado, eliminado)
            VALUES (0,'$ubicacion','$local_ruc','$descripcion', SYSDATE(), 'activado', '0')";
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

	public function asignar($idalmacen_asignar, $idusuario_asignar)
	{
		$sql = "UPDATE almacen SET idusuario='$idusuario_asignar' WHERE idalmacen='$idalmacen_asignar'";
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

	public function eliminar($idalmacen)
	{
		$sql = "UPDATE almacen SET eliminado = '1' WHERE idalmacen='$idalmacen'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($idalmacen)
	{
		$sql = "SELECT * FROM almacen WHERE idalmacen='$idalmacen'";
		return ejecutarConsultaSimpleFila($sql);
	}

	// almacenes disponibles

	public function listarLocalesDisponibles()
	{
		$sql = "SELECT 
				  l.idalmacen,
				  u.idusuario,
				  u.nombre AS nombre,
				  u.cargo AS cargo,
				  l.ubicacion,
				  l.local_ruc,
				  l.descripcion,
				  DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,
				  l.estado
				FROM almacen l 
				LEFT JOIN usuario u ON l.idusuario = u.idusuario 
				WHERE (NOT EXISTS (SELECT 1 FROM usuario WHERE idalmacen = l.idalmacen))
				AND l.eliminado = '0'
				ORDER BY l.idalmacen DESC";

		return ejecutarConsulta($sql);
	}

	public function listarLocalesDisponiblesActivos()
	{
		$sql = "SELECT 
				  l.idalmacen,
				  u.idusuario,
				  u.nombre AS nombre,
				  u.cargo AS cargo,
				  l.ubicacion,
				  l.local_ruc,
				  l.descripcion,
				  DATE_FORMAT(l.fecha_hora, '%d-%m-%Y %H:%i:%s') AS fecha,
				  l.estado
				FROM almacen l 
				LEFT JOIN usuario u ON l.idusuario = u.idusuario 
				WHERE (NOT EXISTS (SELECT 1 FROM usuario WHERE idalmacen = l.idalmacen))
				AND l.eliminado = '0'
				AND l.estado = 'activado'
            	ORDER BY l.idalmacen DESC";

		return ejecutarConsulta($sql);
	}
}
