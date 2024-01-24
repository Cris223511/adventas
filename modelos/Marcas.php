<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Marcas
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $nombre, $descripcion)
	{
		$sql = "INSERT INTO marcas (idusuario,nombre,descripcion,estado,eliminado)
		VALUES ('$idusuario','$nombre','$descripcion','Activado','0')";
		return ejecutarConsulta($sql);
	}

	public function verificarNombreExiste($nombre)
	{
		$sql = "SELECT * FROM marcas WHERE nombre = '$nombre' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	public function verificarNombreEditarExiste($nombre, $idmarcas)
	{
		$sql = "SELECT * FROM marcas WHERE nombre = '$nombre' AND idmarcas != '$idmarcas' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idmarcas, $nombre, $descripcion)
	{
		$sql = "UPDATE marcas SET nombre='$nombre',descripcion='$descripcion' WHERE idmarcas='$idmarcas'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar marcas
	public function desactivar($idmarcas)
	{
		$sql = "UPDATE marcas SET estado='Desactivado' WHERE idmarcas='$idmarcas'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar marcas
	public function activar($idmarcas)
	{
		$sql = "UPDATE marcas SET estado='Activado' WHERE idmarcas='$idmarcas'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($idmarcas)
	{
		$sql = "UPDATE marcas SET eliminado = '1' WHERE idmarcas='$idmarcas'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmarcas)
	{
		$sql = "SELECT * FROM marcas WHERE idmarcas='$idmarcas'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT m.idmarcas, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.estado FROM marcas m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.eliminado = '0' ORDER BY m.idmarcas DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idusuario)
	{
		$sql = "SELECT m.idmarcas, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.estado FROM marcas m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.idusuario = '$idusuario' AND m.eliminado = '0' ORDER BY m.idmarcas DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql = "SELECT * FROM marcas where estado='Activado'";
		return ejecutarConsulta($sql);
	}

	public function selectUsuario($idusuario)
	{
		$sql = "SELECT * FROM marcas where estado='Activado' AND idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
}
