<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class MetodoPago
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $nombre, $descripcion, $imagen)
	{
		$sql = "INSERT INTO metodo_pago (idusuario,nombre,descripcion,imagen,estado,eliminado)
		VALUES ('$idusuario','$nombre','$descripcion', '$imagen','Activado','0')";
		return ejecutarConsulta($sql);
	}

	public function verificarNombreExiste($nombre)
	{
		$sql = "SELECT * FROM metodo_pago WHERE nombre = '$nombre' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	public function verificarNombreEditarExiste($nombre, $idmetodopago)
	{
		$sql = "SELECT * FROM metodo_pago WHERE nombre = '$nombre' AND idmetodopago != '$idmetodopago' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idmetodopago, $nombre, $descripcion, $imagen)
	{
		$sql = "UPDATE metodo_pago SET nombre='$nombre',descripcion='$descripcion',imagen='$imagen' WHERE idmetodopago='$idmetodopago'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar método pago
	public function desactivar($idmetodopago)
	{
		$sql = "UPDATE metodo_pago SET estado='Desactivado' WHERE idmetodopago='$idmetodopago'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar método pago
	public function activar($idmetodopago)
	{
		$sql = "UPDATE metodo_pago SET estado='Activado' WHERE idmetodopago='$idmetodopago'";
		return ejecutarConsulta($sql);
	}

	public function eliminar($idmetodopago)
	{
		$sql = "UPDATE metodo_pago SET eliminado = '1' WHERE idmetodopago='$idmetodopago'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmetodopago)
	{
		$sql = "SELECT * FROM metodo_pago WHERE idmetodopago='$idmetodopago'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT m.idmetodopago, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.imagen, m.estado FROM metodo_pago m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.eliminado = '0' ORDER BY m.idmetodopago DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarActivos()
	{
		$sql = "SELECT m.idmetodopago, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.imagen, m.estado FROM metodo_pago m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.eliminado = '0' AND m.estado='Activado' ORDER BY m.idmetodopago DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idusuario)
	{
		$sql = "SELECT m.idmetodopago, u.idusuario, u.nombre as usuario, u.cargo as cargo, m.nombre, m.descripcion, m.imagen, m.estado FROM metodo_pago m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.idusuario = '$idusuario' AND m.eliminado = '0' ORDER BY m.idmetodopago DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql = "SELECT * FROM metodo_pago where estado='Activado'";
		return ejecutarConsulta($sql);
	}

	public function selectUsuario($idusuario)
	{
		$sql = "SELECT * FROM metodo_pago where estado='Activado' AND idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}
}
