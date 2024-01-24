<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Persona
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $idalmacen, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
	{
		$sql = "INSERT INTO persona (idusuario,idalmacen,tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email,eliminado)
		VALUES ('$idusuario','$idalmacen','$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','0')";
		return ejecutarConsulta($sql);
	}

	public function verificarDniExiste($num_documento)
	{
		$sql = "SELECT * FROM persona WHERE num_documento = '$num_documento' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El número de documento ya existe en la tabla
			return true;
		}
		// El número de documento no existe en la tabla
		return false;
	}

	public function verificarDniEditarExiste($num_documento, $idpersona)
	{
		$sql = "SELECT * FROM persona WHERE num_documento = '$num_documento' AND idpersona != '$idpersona' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El número de documento ya existe en la tabla
			return true;
		}
		// El número de documento no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idpersona, $idalmacen, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
	{
		$sql = "UPDATE persona SET idalmacen='$idalmacen', tipo_persona='$tipo_persona',nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email' WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($idpersona)
	{
		$sql = "UPDATE persona SET eliminado = '1' WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpersona)
	{
		$sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarp()
	{
		$sql = "SELECT p.idpersona, p.tipo_persona, u.idusuario, p.nombre, p.tipo_documento, p.num_documento, p.direccion, p.telefono, p.email, CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM persona p LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.eliminado = '0' AND p.tipo_persona='Proveedor' ORDER BY p.idpersona DESC";
		return ejecutarConsulta($sql);
	}

	public function listarpPorUsuario($idalmacenSession)
	{
		$sql = "SELECT p.idpersona, p.tipo_persona, u.idusuario, p.nombre, p.tipo_documento, p.num_documento, p.direccion, p.telefono, p.email, CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM persona p LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.eliminado = '0' AND p.tipo_persona='Proveedor' AND p.idalmacen = '$idalmacenSession' ORDER BY p.idpersona DESC";
		return ejecutarConsulta($sql);
	}

	public function listarc()
	{
		$sql = "SELECT p.idpersona, p.tipo_persona, u.idusuario, al.ubicacion as almacen, p.nombre, p.tipo_documento, p.num_documento, p.direccion, p.telefono, p.email, CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM persona p LEFT JOIN almacen al ON p.idalmacen = al.idalmacen LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.eliminado = '0' AND p.tipo_persona='Cliente' ORDER BY p.idpersona DESC";
		return ejecutarConsulta($sql);
	}

	public function listarcPorUsuario($idalmacenSession)
	{
		$sql = "SELECT p.idpersona, p.tipo_persona, u.idusuario, al.ubicacion as almacen, p.nombre, p.tipo_documento, p.num_documento, p.direccion, p.telefono, p.email, CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo FROM persona p LEFT JOIN almacen al ON p.idalmacen = al.idalmacen LEFT JOIN usuario u ON p.idusuario = u.idusuario WHERE p.eliminado = '0' AND p.tipo_persona='Cliente' AND p.idalmacen = '$idalmacenSession' ORDER BY p.idpersona DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros 
	public function listarCliente()
	{
		$sql = "SELECT * FROM persona WHERE eliminado = '0' AND tipo_persona='Cliente' ORDER BY idpersona DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros 
	public function listarVendedor()
	{
		$sql = "SELECT * FROM persona WHERE eliminado = '0' AND tipo_persona='Proveedor' ORDER BY idpersona DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros 
	public function listarUsuarioCliente()
	{
		$sql = "SELECT * FROM usuario WHERE eliminado = '0' AND cargo='cliente' ORDER BY idusuario DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros 
	public function listarUsuarioVendedor()
	{
		$sql = "SELECT * FROM usuario WHERE eliminado = '0' AND cargo='vendedor' ORDER BY idusuario DESC";
		return ejecutarConsulta($sql);
	}
}
