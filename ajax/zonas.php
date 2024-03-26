<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['cuotas'] == 1 || !empty($_SESSION['cargo'])) {
		require_once "../modelos/Zonas.php";

		$zonas = new Zonas();

		$idusuario = $_SESSION["idusuario"];
		$cargo = $_SESSION["cargo"];

		$idzona = isset($_POST["idzona"]) ? limpiarCadena($_POST["idzona"]) : "";
		$ubicacion = isset($_POST["ubicacion"]) ? limpiarCadena($_POST["ubicacion"]) : "";
		$zona = isset($_POST["zona"]) ? limpiarCadena($_POST["zona"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idzona)) {
					$ubicacionExiste = $zonas->verificarUbicacionExiste($ubicacion);
					if ($ubicacionExiste) {
						echo "La ubicación que ha ingresado ya existe.";
					} else {
						$rspta = $zonas->insertar($idusuario, $ubicacion, $zona);
						echo $rspta ? "Zona registrada" : "La zona no se pudo registrar";
					}
				} else {
					$ubicacionExiste = $zonas->verificarUbicacionEditarExiste($ubicacion, $idzona);
					if ($ubicacionExiste) {
						echo "La ubicación que ha ingresado ya existe.";
					} else {
						$rspta = $zonas->editar($idzona, $ubicacion, $zona);
						echo $rspta ? "Zona actualizada" : "La zona no se pudo registrar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $zonas->desactivar($idzona);
				echo $rspta ? "Zona desactivada" : "Zona no se puede desactivar";
				break;

			case 'activar':
				$rspta = $zonas->activar($idzona);
				echo $rspta ? "Zona activada" : "Zona no se puede activar";
				break;

			case 'eliminar':
				$rspta = $zonas->eliminar($idzona);
				echo $rspta ? "Zona eliminada" : "Zona no se puede eliminar";
				break;

			case 'mostrar':
				$rspta = $zonas->mostrar($idzona);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listar':
				$rspta = $zonas->listar();

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
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idzona . ')"><i class="fa fa-pencil"></i></button>') .
							(($reg->estado == 'Activado') ?
								(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idzona . ')"><i class="fa fa-close"></i></button>')) : (mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idzona . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>'))) .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idzona . ')"><i class="fa fa-trash"></i></button>') .
							'</div>',
						"1" => $reg->ubicacion,
						"2" => $reg->zona,
						"3" => $reg->usuario . ' - ' . $cargo_detalle,
						"4" => $reg->fecha_hora,
						"5" => (($reg->estado == 'Activado')) ? '<span class="label bg-green">Activado</span>' :
							'<span class="label bg-red">Desactivado</span>',
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
