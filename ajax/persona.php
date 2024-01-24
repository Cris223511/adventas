<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['ventas'] == 1 || $_SESSION['compras'] == 1) {
		require_once "../modelos/Persona.php";

		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["idalmacen"];
		$cargo = $_SESSION["cargo"];

		$persona = new Persona();

		$idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$tipo_persona = isset($_POST["tipo_persona"]) ? limpiarCadena($_POST["tipo_persona"]) : "";
		$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
		$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
		$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
		$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
		$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
		$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idpersona)) {
					$dniExiste = $persona->verificarDniExiste($num_documento);
					if ($dniExiste && $tipo_persona == "Cliente" && $num_documento != '') {
						echo "El número de documento que ha ingresado ya existe.";
					} else {
						$rspta = $persona->insertar($idusuario, $idalmacen, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
						echo $rspta ? "Persona registrada" : "Persona no se pudo registrar";
					}
				} else {
					$dniExiste = $persona->verificarDniEditarExiste($num_documento, $idpersona);
					if ($dniExiste && $tipo_persona == "Cliente" && $num_documento != '') {
						echo "El número de documento que ha ingresado ya existe.";
					} else {
						$rspta = $persona->editar($idpersona, $idalmacen, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
						echo $rspta ? "Persona actualizada" : "Persona no se pudo actualizar";
					}
				}
				break;

			case 'eliminar':
				$rspta = $persona->eliminar($idpersona);
				echo $rspta ? "Persona eliminada" : "Persona no se puede eliminar";
				break;

			case 'mostrar':
				$rspta = $persona->mostrar($idpersona);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listarp':
				$rspta = $persona->listarp();

				$data = array();

				function mostrarBoton($reg, $cargo, $idusuario, $buttonType)
				{
					if ($reg != "superadmin" && $cargo == "admin") {
						return $buttonType;
					} elseif ($cargo == "superadmin" || (($cargo == "cliente" || $cargo == "vendedor" || $cargo == "almacenero" || $cargo == "encargado") && $idusuario == $_SESSION["idusuario"])) {
						return $buttonType;
					} else {
						return '';
					}
				}

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

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<a data-toggle="modal" href="#myModal"><button class="btn btn-secondary" style="margin-right: 3px; height: 35px; color: black !important;" onclick="mostrar(' . $reg->idpersona . ')"><i class="fa fa-pencil"></i></button></a>') .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idpersona . ')"><i class="fa fa-trash"></i></button>') .
							'</div>',
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->nombre == '' ? 'Sin registrar.' : $reg->nombre,
						"3" => $reg->tipo_documento == '' ? 'Sin registrar.' : $reg->tipo_documento,
						"4" => $reg->num_documento == '' ? 'Sin registrar.' : $reg->num_documento,
						"5" => $reg->telefono == '' ? 'Sin registrar.' : $reg->telefono,
						"6" => $reg->email == '' ? 'Sin registrar.' : $reg->email
					);
				}
				$results = array(
					"sEcho" => 1, //Información para el datatables
					"iTotalRecords" => count($data), //enviamos el total registros al datatable
					"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
					"aaData" => $data
				);
				echo json_encode($results);

				break;

			case 'listarc':

				if ($cargo == "superadmin") {
					$rspta = $persona->listarc();
				} else {
					$rspta = $persona->listarcPorUsuario($idalmacenSession);
				}

				$data = array();

				function mostrarBoton($reg, $cargo, $idusuario, $buttonType)
				{
					if ($reg != "superadmin" && $cargo == "admin") {
						return $buttonType;
					} elseif ($cargo == "superadmin" || (($cargo == "cliente" || $cargo == "vendedor" || $cargo == "almacenero" || $cargo == "encargado") && $idusuario == $_SESSION["idusuario"])) {
						return $buttonType;
					} else {
						return '';
					}
				}

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
					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<a data-toggle="modal" href="#myModal"><button class="btn btn-secondary" style="margin-right: 3px; height: 35px; color: black !important;" onclick="mostrar(' . $reg->idpersona . ')"><i class="fa fa-pencil"></i></button></a>') .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idpersona . ')"><i class="fa fa-trash"></i></button>') .
							'</div>',
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->nombre == '' ? 'Sin registrar.' : $reg->nombre,
						"3" => $reg->almacen,
						"4" => $reg->tipo_documento == '' ? 'Sin registrar.' : $reg->tipo_documento,
						"5" => $reg->num_documento == '' ? 'Sin registrar.' : $reg->num_documento,
						"6" => $reg->telefono == '' ? 'Sin registrar.' : $reg->telefono,
						"7" => $reg->email == '' ? 'Sin registrar.' : $reg->email
					);
				}
				$results = array(
					"sEcho" => 1, //Información para el datatables
					"iTotalRecords" => count($data), //enviamos el total registros al datatable
					"iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
					"aaData" => $data
				);
				echo json_encode($results);

				break;
		}
		//Fin de las validaciones de acceso
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
