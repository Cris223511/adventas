<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}

if (empty($_SESSION['idusuario']) && empty($_SESSION['cargo']) && $_GET["op"] !== 'verificar') {
	echo json_encode(['error' => 'No está autorizado para realizar esta acción.']);
	exit();
}

require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (!isset($_SESSION["nombre"])) {
			header("Location: ../vistas/login.html");
		} else {
			if ($_SESSION['acceso'] == 1) {
				if (!empty($_FILES['imagen']['name'])) {
					$uploadDirectory = "../files/usuarios/";

					$tempFile = $_FILES['imagen']['tmp_name'];
					$fileExtension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
					$newFileName = sprintf("%09d", rand(0, 999999999)) . '.' . $fileExtension;
					$targetFile = $uploadDirectory . $newFileName;

					// Verificar si es una imagen y mover el archivo
					$allowedExtensions = array('jpg', 'jpeg', 'png', 'jfif', 'bmp');
					if (in_array($fileExtension, $allowedExtensions) && move_uploaded_file($tempFile, $targetFile)) {
						// El archivo se ha movido correctamente, ahora $newFileName contiene el nombre del archivo
						$imagen = $newFileName;
					} else {
						// Error en la subida del archivo
						echo "Error al subir la imagen.";
						exit;
					}
				} else {
					// No se ha seleccionado ninguna imagen
					$imagen = $_POST["imagenactual"];
				}

				if (empty($idusuario)) {
					$dniExiste = $usuario->verificarDniExiste($num_documento);
					$usuarioExiste = $usuario->verificarUsuarioExiste($login);

					if ($dniExiste && $num_documento != '') {
						echo "El número de documento que ha ingresado ya existe.";
					} else if ($usuarioExiste) {
						echo "El nombre del usuario que ha ingresado ya existe.";
					} else {
						$rspta = $usuario->insertar($idalmacen, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $_POST['permiso']);
						echo $rspta ? "Usuario registrado" : "Usuario no se pudo registrar.";
					}
				} else {
					$dniExiste = $usuario->verificarDniEditarExiste($num_documento, $idusuario);
					$usuarioExiste = $usuario->verificarUsuarioEditarExiste($login, $idusuario);

					if ($dniExiste && $num_documento != '') {
						echo "El número de documento que ha ingresado ya existe.";
					} else if ($usuarioExiste) {
						echo "El nombre del usuario que ha ingresado ya existe.";
					} else {
						$rspta = $usuario->editar($idusuario, $idalmacen, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $_POST['permiso']);
						echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
					}
				}
			} else {
				require 'noacceso.php';
			}
		}
		break;

	case 'desactivar':
		if (!isset($_SESSION["nombre"])) {
			header("Location: ../vistas/login.html");
		} else {
			if ($_SESSION['acceso'] == 1) {
				$rspta = $usuario->desactivar($idusuario);
				echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
			} else {
				require 'noacceso.php';
			}
		}
		break;

	case 'activar':
		if (!isset($_SESSION["nombre"])) {
			header("Location: ../vistas/login.html");
		} else {
			if ($_SESSION['acceso'] == 1) {
				$rspta = $usuario->activar($idusuario);
				echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
			} else {
				require 'noacceso.php';
			}
		}
		break;

	case 'eliminar':
		$rspta = $usuario->eliminar($idusuario);
		echo $rspta ? "Usuario eliminado" : "Usuario no se puede eliminar";
		break;

	case 'mostrar':
		if (!isset($_SESSION["nombre"])) {
			header("Location: ../vistas/login.html");
		} else {
			if ($_SESSION['acceso'] == 1) {
				$rspta = $usuario->mostrar($idusuario);
				echo json_encode($rspta);
			} else {
				require 'noacceso.php';
			}
		}
		break;

	case 'listar':
		if (!isset($_SESSION["nombre"])) {
			header("Location: ../vistas/login.html");
		} else {
			if ($_SESSION['acceso'] == 1) {
				$rspta = $usuario->listar();
				$data = array();

				while ($reg = $rspta->fetch_object()) {
					$cargo_detalle = "";
					switch ($reg->cargo) {
						case 'superadmin':
							$cargo_detalle = "Superadministrador";
							break;
						case 'admin':
							$cargo_detalle = "Administrador";
							break;
						case 'cliente':
							$cargo_detalle = "Cliente";
							break;
						case 'vendedor':
							$cargo_detalle = "Vendedor";
							break;
						case 'almacenero':
							$cargo_detalle = "Almacenero";
							break;
						case 'encargado':
							$cargo_detalle = "Encargado";
							break;
						default:
							break;
					}

					$telefono = ($reg->telefono == '') ? 'Sin registrar' : number_format($reg->telefono, 0, '', ' ');

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							((($reg->estado) ?
								(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') ? ('<button class="btn btn-secondary" style="margin-right: 3px;" onclick="mostrar(' . $reg->idusuario . '); verificarCargo(\'' . $reg->cargo . '\');"><i class="fa fa-pencil"></i></button>') : '') .
								(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') ? ('<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idusuario . ')"><i class="fa fa-close"></i></button>') : '') .
								(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') ? ('<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idusuario . ')"><i class="fa fa-trash"></i></button>') : '') : (($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') ? ('<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idusuario . '); verificarCargo(\'' . $reg->cargo . '\');"><i class="fa fa-pencil"></i></button>') : '') .
								(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') ? ('<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px; padding: 0;" onclick="activar(' . $reg->idusuario . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>') : '') .
								(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') ? ('<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idusuario . ')"><i class="fa fa-trash"></i></button>') : '')) . '</div>'),
						"1" => $reg->login,
						"2" => $cargo_detalle,
						"3" => $reg->nombre,
						"4" => $reg->tipo_documento == '' ? 'Sin registrar.' : $reg->tipo_documento,
						"5" => $reg->num_documento == '' ? 'Sin registrar.' : $reg->num_documento,
						"6" => $telefono,
						"7" => $reg->email == '' ? 'Sin registrar.' : $reg->email,
						"8" => $reg->local,
						"9" => "N° " . $reg->local_ruc,
						"10" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50px' width='50px' >",
						"11" => ($reg->estado) ? '<span class="label bg-green">Activado</span>' :
							'<span class="label bg-red">Desactivado</span>'
					);
				}
				$results = array(
					"sEcho" => 1,
					"iTotalRecords" => count($data),
					"iTotalDisplayRecords" => count($data),
					"aaData" => $data
				);
				echo json_encode($results);
			} else {
				require 'noacceso.php';
			}
		}
		break;

	case 'listarUsuariosActivos':
		$rspta = $usuario->listarUsuariosActivos();

		while ($reg = $rspta->fetch_object()) {
			$cargo_detalle = "";

			switch ($reg->cargo) {
				case 'superadmin':
					$cargo_detalle = "Superadministrador";
					break;
				case 'admin':
					$cargo_detalle = "Administrador";
					break;
				case 'cliente':
					$cargo_detalle = "Cliente";
					break;
				case 'vendedor':
					$cargo_detalle = "Vendedor";
					break;
				case 'almacenero':
					$cargo_detalle = "Almacenero";
					break;
				case 'encargado':
					$cargo_detalle = "Encargado";
					break;
				default:
					break;
			}
			echo '<option value="' . $reg->idusuario . '"> ' . $reg->nombre . ' ' . $reg->apellido  . ' - ' . $cargo_detalle . '</option>';
		}
		break;

	case 'selectUsuarios':
		$cargoSession = $_SESSION["cargo"];
		if ($cargoSession == "superadmin") {
			$rspta = $usuario->listarASCactivos();
		} else {
			$rspta = $usuario->listarPorUsuarioASCActivos($_SESSION['idusuario']);
		}

		echo '<option value="">- Seleccione -</option>';
		while ($reg = $rspta->fetch_object()) {
			$cargo_detalle = "";

			switch ($reg->cargo) {
				case 'superadmin':
					$cargo_detalle = "Superadministrador";
					break;
				case 'admin':
					$cargo_detalle = "Administrador";
					break;
				case 'cliente':
					$cargo_detalle = "Cliente";
					break;
				case 'vendedor':
					$cargo_detalle = "Vendedor";
					break;
				case 'almacenero':
					$cargo_detalle = "Almacenero";
					break;
				case 'encargado':
					$cargo_detalle = "Encargado";
					break;
				default:
					break;
			}
			echo '<option value="' . $reg->idusuario . '"> ' . $reg->nombre . ' ' . $reg->apellido  . ' - ' . $cargo_detalle . '</option>';
		}
		break;

	case 'permisos':
		require_once "../modelos/Permiso.php";
		$permiso = new Permiso();
		$rspta = $permiso->listar();

		$id = $_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		$valores = array();

		while ($per = $marcados->fetch_object()) {
			array_push($valores, $per->idpermiso);
		}

		while ($reg = $rspta->fetch_object()) {
			$esPermisoEspecial = ($reg->nombre === 'Escritorio' || $reg->nombre === 'Perfil Usuario');
			$sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';
			$disabled = $esPermisoEspecial ? 'disabled' : '';

			echo '<li> <input type="checkbox" ' . $sw . ' ' . $disabled . ' name="permiso[]" value="' . $reg->idpermiso . '">' . $reg->nombre . '</li>';
		}

		break;

	case 'getSessionId':
		$sessionIdData = array(
			'idusuario' => $_SESSION['idusuario'],
			'idalmacen' => $_SESSION['idalmacen']
		);

		echo json_encode($sessionIdData);
		break;

	case 'verificar':
		$logina = $_POST['logina'];
		$clavea = $_POST['clavea'];

		$rspta = $usuario->verificar($logina, $clavea);

		$fetch = $rspta->fetch_object();

		if (isset($fetch)) {
			if ($fetch->eliminado == "1") {
				echo 1;
				return;
			}

			if ($fetch->estado == "0") {
				echo 0;
				return;
			}

			$almacenExiste = $usuario->almacenExiste($fetch->idalmacen);
			if ($almacenExiste == 0) {
				echo 3;
				return;
			}

			if ($fetch->estadoLocal == "desactivado") {
				echo 2;
				return;
			}

			//Declaramos las variables de sesión
			$_SESSION['idusuario'] = $fetch->idusuario;
			$_SESSION['idalmacen'] = $fetch->idalmacen;
			$_SESSION['local'] = $fetch->local;
			$_SESSION['local_imagen'] = $fetch->local_imagen;
			$_SESSION['nombre'] = $fetch->nombre;
			$_SESSION['imagen'] = $fetch->imagen;
			$_SESSION['login'] = $fetch->login;
			$_SESSION['clave'] = $fetch->clave;
			$_SESSION['cargo'] = $fetch->cargo;

			switch ($_SESSION['cargo']) {
				case 'superadmin':
					$_SESSION['cargo_detalle'] = "Superadministrador";
					break;
				case 'admin':
					$_SESSION['cargo_detalle'] = "Administrador";
					break;
				case 'cliente':
					$_SESSION['cargo_detalle'] = "Cliente";
					break;
				case 'vendedor':
					$_SESSION['cargo_detalle'] = "Vendedor";
					break;
				case 'almacenero':
					$_SESSION['cargo_detalle'] = "Almacenero";
					break;
				case 'encargado':
					$_SESSION['cargo_detalle'] = "Encargado";
					break;
				default:
					break;
			}

			//Obtenemos los permisos del usuario
			$marcados = $usuario->listarmarcados($fetch->idusuario);

			//Declaramos el array para almacenar todos los permisos marcados
			$valores = array();

			//Almacenamos los permisos marcados en el array
			while ($per = $marcados->fetch_object()) {
				array_push($valores, $per->idpermiso);
			}

			//Determinamos los accesos del usuario
			in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
			in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;
			in_array(3, $valores) ? $_SESSION['servicios'] = 1 : $_SESSION['servicios'] = 0;
			in_array(4, $valores) ? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0;
			in_array(5, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;
			in_array(6, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
			in_array(7, $valores) ? $_SESSION['cuotas'] = 1 : $_SESSION['cuotas'] = 0;
			in_array(8, $valores) ? $_SESSION['proforma'] = 1 : $_SESSION['proforma'] = 0;
			in_array(9, $valores) ? $_SESSION['prestamo'] = 1 : $_SESSION['prestamo'] = 0;
			in_array(10, $valores) ? $_SESSION['perfilu'] = 1 : $_SESSION['perfilu'] = 0;
			in_array(11, $valores) ? $_SESSION['pagos'] = 1 : $_SESSION['pagos'] = 0;
			in_array(12, $valores) ? $_SESSION['reporte'] = 1 : $_SESSION['reporte'] = 0;
			in_array(13, $valores) ? $_SESSION['reporteP'] = 1 : $_SESSION['reporteP'] = 0;
			in_array(14, $valores) ? $_SESSION['transferencias'] = 1 : $_SESSION['transferencias'] = 0;
		}
		echo json_encode($fetch);
		break;

	case 'salir':
		//Limpiamos las variables de sesión   
		session_unset();
		//Destruìmos la sesión
		session_destroy();
		//Redireccionamos al login
		header("Location: ../index.php");

		break;
}

ob_end_flush();
