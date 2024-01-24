<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}

// si no está logeado o no tiene ningún cargo...
if (empty($_SESSION['idusuario']) || empty($_SESSION['cargo'])) {
	// opciones a las que NO pueden tener acceso... si no colocamos ninguno, quiere decir
	// que tiene acceso a todas las opciones si es que está logeado o tiene un cargo.
	if (($_GET["op"] == 'selectLocal' || $_GET["op"] == 'selectLocalUsuario' || $_GET["op"] == 'selectLocalDisponible')) {
		echo 'No está autorizado para realizar esta acción.';
		exit();
	}
}

if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html");
} else {
	if ($_SESSION['almacen'] == 1) {
		require_once "../modelos/Locales.php";

		$almacenes = new Local();

		// Variables de sesión a utilizar.
		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["idalmacen"];
		$cargo = $_SESSION["cargo"];

		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$ubicacion = isset($_POST["ubicacion"]) ? limpiarCadena($_POST["ubicacion"]) : "";
		$local_ruc = isset($_POST["local_ruc"]) ? limpiarCadena($_POST["local_ruc"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idalmacen)) {
					$nombreExiste = $almacenes->verificarNombreExiste($ubicacion);
					if ($nombreExiste) {
						echo "El nombre del local ya existe.";
					} else {
						$rspta = $almacenes->agregar($idusuario, $ubicacion, $local_ruc, $descripcion);
						echo $rspta ? "Local registrado" : "El local no se pudo registrar";
						if ($rspta) {
							$_SESSION['local'] = $ubicacion;
						}
					}
				} else {
					$nombreExiste = $almacenes->verificarNombreEditarExiste($ubicacion, $idalmacen);
					if ($nombreExiste) {
						echo "El nombre del local ya existe.";
					} else {
						$rspta = $almacenes->editar($idalmacen, $ubicacion, $local_ruc, $descripcion);
						echo $rspta ? "Local actualizado" : "El local no se pudo actualizar";
						if ($rspta) {
							$_SESSION['local'] = $ubicacion;
						}
					}
				}
				break;

			case 'desactivar':
				$rspta = $almacenes->desactivar($idalmacen);
				echo $rspta ? "Local desactivado" : "El local no se pudo desactivar";
				break;

			case 'activar':
				$rspta = $almacenes->activar($idalmacen);
				echo $rspta ? "Local activado" : "El local no se pudo activar";
				break;

			case 'eliminar':
				$rspta = $almacenes->eliminar($idalmacen);
				echo $rspta ? "Local eliminado" : "El local no se pudo eliminar";
				break;

			case 'mostrar':
				$rspta = $almacenes->mostrar($idalmacen);
				echo json_encode($rspta);
				break;

			case 'listar':
				// $fecha_inicio = $_GET["fecha_inicio"];
				// $fecha_fin = $_GET["fecha_fin"];

				// if ($cargo == "superadmin") {
				// 	if ($fecha_inicio == "" && $fecha_fin == "") {
				// 		$rspta = $almacenes->listar();
				// 	} else {
				// 		$rspta = $almacenes->listarPorFecha($fecha_inicio, $fecha_fin);
				// 	}
				// } else {
				// if ($fecha_inicio == "" && $fecha_fin == "") {
				$rspta = $almacenes->listarPorUsuario($idalmacenSession);
				// } else {
				// $rspta = $almacenes->listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin);
				// }
				// }

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

					$reg->descripcion = (strlen($reg->descripcion) > 70) ? substr($reg->descripcion, 0, 70) . "..." : $reg->descripcion;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<button class="btn btn-secondary" style="margin-right: 3px;" onclick="mostrar(' . $reg->idalmacen . ')"><i class="fa fa-pencil"></i></button>' .
							'<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar2(' . $reg->idalmacen . ')"><i class="fa fa-eye"></i></button>' .
							'<a data-toggle="modal" href="#myModal"><button class="btn btn-secondary" style="margin-right: 3px; height: 35px; color: #333333;" onclick="trabajadores(' . $reg->idalmacen . ',\'' . $reg->ubicacion . '\')"><i class="fa fa-user"></i></button></a>' .
							'</div>',
						"1" => $reg->ubicacion,
						"2" => "N° " . $reg->local_ruc,
						"3" => ($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion,
						"4" => $reg->fecha,
						"5" => ($reg->estado == 'activado') ? '<span class="label bg-green">Activado</span>' :
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
				break;

			case 'listarUsuariosLocal':

				$idalmacen2 = isset($_GET["idalmacen"]) ? limpiarCadena($_GET["idalmacen"]) : "";

				$rspta = $almacenes->listarUsuariosPorLocal($idalmacen2);

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
						"0" => $reg->login,
						"1" => $cargo_detalle,
						"2" => $reg->nombre,
						"3" => $reg->tipo_documento,
						"4" => $reg->num_documento,
						"5" => $telefono,
						"6" => $reg->email,
						"7" => $reg->local,
						"8" => "N° " . $reg->local_ruc,
						"9" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50px' width='50px' >",
						"10" => ($reg->estado) ? '<span class="label bg-green">Activado</span>' :
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
				break;

			case 'selectLocalASC':
				$rspta = $almacenes->listarPorUsuarioActivosASC($idalmacenSession);
				$result = mysqli_fetch_all($rspta, MYSQLI_ASSOC);

				$data = [];
				foreach ($result as $row) {
					$data["almacenes"][] = $row;
				}

				echo json_encode($data);
				break;

			case 'selectLocales':
				$rspta = $almacenes->listarActivosASC();
				$result = mysqli_fetch_all($rspta, MYSQLI_ASSOC);

				$data = [];
				foreach ($result as $row) {
					$data["almacenes"][] = $row;
				}

				echo json_encode($data);
				break;

			case 'selectAlmacen':
				if ($cargo == "superadmin") {
					$rspta = $almacenes->listarActivosASC();
				} else {
					$rspta = $almacenes->listarPorUsuario($idalmacenSession);
				}

				echo '<option value="">- Seleccione -</option>';
				while ($reg = $rspta->fetch_object()) {
					echo '<option value="' . $reg->idalmacen . '" data-local-ruc="' . $reg->local_ruc . '">' . $reg->ubicacion . '</option>';
				}
				break;

			case 'actualizarSession':
				$info = array(
					'local' => $_SESSION['local'],
				);
				echo json_encode($info);
				break;
		}
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
