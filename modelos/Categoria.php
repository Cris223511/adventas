<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Categoria
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario,$nombre, $descripcion)
	{
		$sql = "INSERT INTO categoria (idusuario,nombre,descripcion,estado)
		VALUES ('$idusuario','$nombre','$descripcion','1')";
		return ejecutarConsulta($sql);
	}

	public function verificarCategoriaExiste($nombre)
	{
		$sql = "SELECT * FROM categoria WHERE nombre = '$nombre' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre de la categoría ya existe en la tabla
			return true;
		}
		// El nombre de la categoría no existe en la tabla
		return false;
	}

	public function verificarCategoriaEditarExiste($nombre, $idcategoria)
	{
		$sql = "SELECT * FROM categoria WHERE nombre = '$nombre' AND idcategoria != '$idcategoria' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre de usuario ya existe en la tabla
			return true;
		}
		// El nombre de usuario no existe en la tabla
		return false;
	}


	//Implementamos un método para editar registros
	public function editar($idcategoria, $nombre, $descripcion)
	{
		$sql = "UPDATE categoria SET nombre='$nombre',descripcion='$descripcion' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcategoria)
	{
		$sql = "UPDATE categoria SET estado='0' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcategoria)
	{
		$sql = "UPDATE categoria SET estado='1' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcategoria)
	{
		$sql = "SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function eliminar($idcategoria)
	{
		$sql = "UPDATE categoria SET eliminado = '1' WHERE idcategoria='$idcategoria'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT c.idcategoria, u.idusuario, u.nombre as usuario, u.cargo as cargo, c.nombre, c.descripcion, c.estado FROM categoria c LEFT JOIN usuario u ON c.idusuario = u.idusuario WHERE c.eliminado = '0' ORDER BY c.idcategoria DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idusuario)
	{
		$sql = "SELECT c.idcategoria, u.idusuario, u.nombre as usuario, u.cargo as cargo, c.nombre, c.descripcion, c.estado FROM categoria c LEFT JOIN usuario u ON c.idusuario = u.idusuario WHERE c.idusuario = '$idusuario' AND c.eliminado = '0' ORDER BY c.idcategoria DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql = "SELECT * FROM categoria where estado=1";
		return ejecutarConsulta($sql);
	}

	public function selectUsuario($idusuario)
	{
		$sql = "SELECT * FROM categoria where estado=1 AND idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
}
