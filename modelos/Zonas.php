<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Zonas
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $ubicacion, $zona)
	{
		$datetime = new DateTime("", new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');
		$datetime->setTimezone(new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');

		$sql = "INSERT INTO zonas (idusuario,ubicacion,zona,fecha_hora,estado,eliminado)
		VALUES ('$idusuario','$ubicacion','$zona','$orderDate','Activado','0')";
		return ejecutarConsulta($sql);
	}

	public function verificarUbicacionExiste($ubicacion)
	{
		$sql = "SELECT * FROM zonas WHERE ubicacion = '$ubicacion' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La ubicación ya existe en la tabla
			return true;
		}
		// La ubicación no existe en la tabla
		return false;
	}

	public function verificarUbicacionEditarExiste($ubicacion, $idzona)
	{
		$sql = "SELECT * FROM zonas WHERE ubicacion = '$ubicacion' AND idzona != '$idzona' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// La ubicación ya existe en la tabla
			return true;
		}
		// La ubicación no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idzona, $ubicacion, $zona)
	{
		$datetime = new DateTime("", new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');
		$datetime->setTimezone(new DateTimeZone('America/Lima'));
		$orderDate = $datetime->format('Y-m-d H:i:s');

		$sql = "UPDATE zonas SET ubicacion='$ubicacion', zona='$zona', fecha_hora='$orderDate' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idzona)
	{
		$sql = "SELECT z.idzona,z.ubicacion,u.idusuario,z.zona,z.fecha_hora,z.estado,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM zonas z LEFT JOIN usuario u ON z.idusuario=u.idusuario WHERE z.idzona='$idzona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT z.idzona,z.ubicacion,u.idusuario,z.zona,z.fecha_hora,z.estado,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM zonas z LEFT JOIN usuario u ON z.idusuario=u.idusuario WHERE z.eliminado = '0' ORDER BY z.idzona DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idusuario)
	{
		$sql = "SELECT z.idzona,z.ubicacion,u.idusuario,z.zona,z.fecha_hora,z.estado,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM zonas z LEFT JOIN usuario u ON z.idusuario=u.idusuario WHERE z.eliminado = '0' AND z.idusuario = '$idusuario' ORDER BY z.idzona DESC";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar zonases
	public function desactivar($idzona)
	{
		$sql = "UPDATE zonas SET estado='Desactivado' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar zonases
	public function activar($idzona)
	{
		$sql = "UPDATE zonas SET estado='Activado' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar registros
	public function eliminar($idzona)
	{
		$sql = "UPDATE zonas SET eliminado = '1' WHERE idzona='$idzona'";
		return ejecutarConsulta($sql);
	}
}
