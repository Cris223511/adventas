<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['almacen'] == 1 || !empty($_SESSION['cargo'])) {
		require_once "../modelos/Medidas.php";

		$medidas = new Medida();

		$idusuario = $_SESSION["idusuario"];
		$cargo = $_SESSION["cargo"];

		$idmedida = isset($_POST["idmedida"]) ? limpiarCadena($_POST["idmedida"]) : "";
		$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idmedida)) {
					$nombreExiste = $medidas->verificarNombreExiste($nombre);
					if ($nombreExiste) {
						echo "El nombre de la medida que ha ingresado ya existe.";
					} else {
						$rspta = $medidas->insertar($idusuario, $nombre, $descripcion);
						echo $rspta ? "Medida registrada" : "La medida no se pudo registrar";
					}
				} else {
					$nombreExiste = $medidas->verificarNombreEditarExiste($nombre, $idmedida);
					if ($nombreExiste) {
						echo "El nombre de la medida que ha ingresado ya existe.";
					} else {
						$rspta = $medidas->editar($idmedida, $nombre, $descripcion);
						echo $rspta ? "Medida actualizada" : "La medida no se pudo actualizar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $medidas->desactivar($idmedida);
				echo $rspta ? "Medida desactivada" : "La medida no se pudo desactivar";
				break;

			case 'activar':
				$rspta = $medidas->activar($idmedida);
				echo $rspta ? "Medida activada" : "La medida no se pudo activar";
				break;

			case 'eliminar':
				$rspta = $medidas->eliminar($idmedida);
				echo $rspta ? "Medida eliminado" : "La medida no se pudo eliminar";
				break;

			case 'mostrar':
				$rspta = $medidas->mostrar($idmedida);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listar':
				$rspta = $medidas->listar();

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
							(($reg->idmedida != "1" && $reg->idmedida != "2" && $reg->idmedida != "3") ?
								mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idmedida . ')"><i class="fa fa-pencil"></i></button>') .
								(($reg->estado == 'Activado') ?
									(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idmedida . ')"><i class="fa fa-close"></i></button>')) : (mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idmedida . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>'))) .
								mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idmedida . ')"><i class="fa fa-trash"></i></button>') : ("")) .
							'</div>',
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->nombre,
						"3" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;'' readonly>" . (($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion) . "</textarea>",
						"4" => ($reg->estado == 'Activado') ? '<span class="label bg-green">Activado</span>' :
							'<span class="label bg-red">Desactivado</span>'
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
