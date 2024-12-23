<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Usuario
{
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idalmacen, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
	{
		// Primero, verifique si el nombre de usuario ya existe en la tabla
		$nombreExiste = $this->verificarNombreExiste($login);

		if ($nombreExiste)
			// El nombre de usuario ya existe, no se puede insertar
			return false;

		if (empty($imagen))
			$imagen = "default.png";

		$sql1 = "INSERT INTO usuario (idalmacen,nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen,estado,eliminado)
					VALUES ('$idalmacen','$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','$imagen','1','0')";
		//return ejecutarConsulta($sql1);
		$idusuarionew = ejecutarConsulta_retornarID($sql1);

		$num_elementos = 0;
		$sw = true;

		while ($num_elementos < count($permisos)) {
			$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuarionew', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos = $num_elementos + 1;
		}

		$sql2 = "UPDATE almacen SET idusuario='$idusuarionew' WHERE idalmacen='$idalmacen'";
		ejecutarConsulta($sql2);

		return $sw;
	}

	// función para verificar si el nombre de usuario ya existe en la tabla
	public function verificarUsuarioExiste($login)
	{
		$sql = "SELECT * FROM usuario WHERE login = '$login' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre de usuario ya existe en la tabla
			return true;
		}
		// El nombre de usuario no existe en la tabla
		return false;
	}

	public function verificarUsuarioEditarExiste($login, $idusuario)
	{
		$sql = "SELECT * FROM usuario WHERE login = '$login' AND idusuario != '$idusuario' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre de usuario ya existe en la tabla
			return true;
		}
		// El nombre de usuario no existe en la tabla
		return false;
	}

	public function verificarEmailExiste($email)
	{
		$sql = "SELECT * FROM usuario WHERE email = '$email' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El email ya existe en la tabla
			return true;
		}
		// El email no existe en la tabla
		return false;
	}

	public function verificarNombreExiste($nombre)
	{
		$sql = "SELECT * FROM usuario WHERE nombre = '$nombre' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El nombre ya existe en la tabla
			return true;
		}
		// El nombre no existe en la tabla
		return false;
	}

	public function verificarDniExiste($num_documento)
	{
		$sql = "SELECT * FROM usuario WHERE num_documento = '$num_documento' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El número de documento ya existe en la tabla
			return true;
		}
		// El número de documento no existe en la tabla
		return false;
	}

	public function verificarDniEditarExiste($num_documento, $idusuario)
	{
		$sql = "SELECT * FROM usuario WHERE num_documento = '$num_documento' AND idusuario != '$idusuario' AND eliminado = '0'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El número de documento ya existe en la tabla
			return true;
		}
		// El número de documento no existe en la tabla
		return false;
	}

	//Implementamos un método para editar registros
	public function editar($idusuario, $idalmacen, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
	{
		$sql1 = "UPDATE usuario SET idalmacen='$idalmacen',nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave',imagen='$imagen' WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql1);

		//Eliminamos todos los permisos asignados para volverlos a registrar
		$sql2 = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql2);

		$num_elementos = 0;
		$sw = true;

		while ($num_elementos < count($permisos)) {
			$sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES('$idusuario', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos = $num_elementos + 1;
		}

		$sql3 = "UPDATE almacen SET idusuario='$idusuario' WHERE idalmacen='$idalmacen'";
		ejecutarConsulta($sql3);

		return $sw;
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idusuario)
	{
		$sql = "UPDATE usuario SET estado='0' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idusuario)
	{
		$sql = "UPDATE usuario SET estado='1' WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	public function mostrar($idusuario)
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
	  			WHERE u.idusuario='$idusuario'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementamos un método para eliminar registros
	public function eliminar($idusuario)
	{
		$sql1 = "UPDATE usuario SET eliminado = '1' WHERE idusuario='$idusuario'";
		$sql2 = "UPDATE almacen SET idusuario = 0 WHERE idusuario='$idusuario'";
		ejecutarConsulta($sql1);
		return ejecutarConsulta($sql2);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
				WHERE u.eliminado = '0'
				ORDER BY idusuario DESC";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarASC()
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
				WHERE u.eliminado = '0'
				ORDER BY idusuario ASC";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarASCactivos()
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
				WHERE u.eliminado = '0'
				AND u.estado='1'
				ORDER BY idusuario ASC";

		return ejecutarConsulta($sql);
	}

	public function listarPorUsuarioASCActivos($idusuario)
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
				WHERE u.eliminado = '0'
				AND u.estado='1'
				AND u.idusuario = '$idusuario'
				ORDER BY idusuario ASC";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idusuarioSession)
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
				WHERE u.eliminado = '0'
				AND u.idusuario = '$idusuarioSession'";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarUsuariosActivos()
	{
		$sql = "SELECT
				  u.idusuario,
				  u.idalmacen,
				  u.nombre, u.apellido,
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
				WHERE u.eliminado = '0'
				AND u.estado='1'
				ORDER BY idusuario DESC";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idusuario)
	{
		$sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el acceso al sistema
	public function verificar($login, $clave)
	{
		$sql = "SELECT u.idusuario,u.idalmacen,l.ubicacion AS local,l.imagen AS local_imagen,l.estado AS estadoLocal,u.nombre, u.apellido,u.tipo_documento,u.num_documento,u.telefono,u.email,u.cargo,u.imagen,u.login,u.clave,u.estado,u.eliminado FROM usuario u LEFT JOIN almacen l ON u.idalmacen = l.idalmacen WHERE login='$login' AND clave='$clave'";
		return ejecutarConsulta($sql);
	}

	public function almacenExiste($idalmacen)
	{
		$sql = "SELECT COUNT(*) AS existe FROM almacen WHERE idalmacen = '$idalmacen'";
		$resultado = ejecutarConsultaSimpleFila($sql);

		return $resultado['existe'];
	}
}
