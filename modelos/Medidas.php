<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Medida
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $nombre, $descripcion)
	{
		$sql = "INSERT INTO medidas (idusuario,nombre,descripcion,estado,eliminado)
		VALUES ('$idusuario','$nombre','$descripcion','Activado','0')";
		return ejecutarConsulta($sql);
	}

	public function verificarNombreExiste($nombre)
	{
		$sql = "SELECT * FROM medidas WHERE nombre = '$nombre' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	public function verificarNombreEditarExiste($nombre, $idmedida)
	{
		$sql = "SELECT * FROM medidas WHERE nombre = '$nombre' AND idmedida != '$idmedida' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idmedida, $nombre, $descripcion)
	{
		$sql = "UPDATE medidas SET nombre='$nombre',descripcion='$descripcion' WHERE idmedida='$idmedida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar medida
	public function desactivar($idmedida)
	{
		$sql = "UPDATE medidas SET estado='Desactivado' WHERE idmedida='$idmedida'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar medida
	public function activar($idmedida)
	{
		$sql = "UPDATE medidas SET estado='Activado' WHERE idmedida='$idmedida'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($idmedida)
	{
		$sql = "UPDATE medidas SET eliminado = '1' WHERE idmedida='$idmedida'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmedida)
	{
		$sql = "SELECT * FROM medidas WHERE idmedida='$idmedida'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT m.idmedida, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.estado FROM medidas m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.eliminado = '0' ORDER BY m.idmedida DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idusuario)
	{
		$sql = "SELECT m.idmedida, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.estado FROM medidas m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.idusuario = '$idusuario' AND m.eliminado = '0' ORDER BY m.idmedida DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql = "SELECT * FROM medidas where estado='Activado'";
		return ejecutarConsulta($sql);
	}

	public function selectUsuario($idusuario)
	{
		$sql = "SELECT * FROM medidas where estado='Activado' AND idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
}
