<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}

// si no está logeado o no tiene ningún cargo, no accede a ninguna opción, si está logeado, accede a todas las opciones.
if ((empty($_SESSION['idusuario']) || empty($_SESSION['cargo'])) && ($_SESSION['cargo'] == "superadmin" || $_SESSION['cargo'] == "admin")) {
	echo 'No está autorizado para realizar esta acción.';
	exit();
}

if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html");
} else {
	if ($_SESSION['perfilu'] == 1) {
		require_once "../modelos/LocalesDisponibles.php";

		$almacenes = new LocalDisponible();

		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$ubicacion = isset($_POST["ubicacion"]) ? limpiarCadena($_POST["ubicacion"]) : "";
		$local_ruc = isset($_POST["local_ruc"]) ? limpiarCadena($_POST["local_ruc"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

		$idalmacen_asignar = isset($_POST["idalmacen_asignar"]) ? limpiarCadena($_POST["idalmacen_asignar"]) : "";
		$idusuario_asignar = isset($_POST["idusuario_asignar"]) ? limpiarCadena($_POST["idusuario_asignar"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idalmacen)) {
					$nombreExiste = $almacenes->verificarNombreExiste($ubicacion);
					if ($nombreExiste) {
						echo "El nombre del local ya existe.";
					} else {
						$rspta = $almacenes->agregar($ubicacion, $local_ruc, $descripcion);
						echo $rspta ? "Local registrado" : "El local no se pudo registrar";
					}
				} else {
					$nombreExiste = $almacenes->verificarNombreEditarExiste($ubicacion, $idalmacen);
					if ($nombreExiste) {
						echo "El nombre del local ya existe.";
					} else {
						$rspta = $almacenes->editar($idalmacen, $ubicacion, $local_ruc, $descripcion);
						echo $rspta ? "Local actualizado" : "El local no se pudo actualizar";
					}
				}
				break;

			case 'guardaryeditar2':
				$rspta = $almacenes->asignar($idalmacen_asignar, $idusuario_asignar);
				echo $rspta ? "Local asignado correctamente" : "El local no se pudo asignar";
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

				$rspta = $almacenes->listarLocalesDisponibles();
				$data = array();

				while ($reg = $rspta->fetch_object()) {

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							(($reg->estado == 'activado') ?
								(('<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idalmacen . ')"><i class="fa fa-pencil"></i></button>')) .
								(('<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idalmacen . ')"><i class="fa fa-close"></i></button>')) .
								(('<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idalmacen . ')"><i class="fa fa-trash"></i></button>')) :
								(('<button class="btn btn-secondary" STYLE="margin-right: 3px;" onclick="mostrar(' . $reg->idalmacen . ')"><i class="fa fa-pencil"></i></button>')) .
								(('<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idalmacen . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>')) .
								(('<button class="btn btn-secondary" style="width: 35px; height: 35px;" onclick="eliminar(' . $reg->idalmacen . ')"><i class="fa fa-trash"></i></button>'))) . '</div>',
						"1" => $reg->ubicacion,
						"2" => "N° " . $reg->local_ruc,
						"3" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion) . "</textarea>",
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

			case 'selectLocalDisponible':
				$rspta = $almacenes->listarLocalesDisponiblesActivos();

				echo '<option value="">- Seleccione -</option>';
				while ($reg = $rspta->fetch_object()) {
					echo '<option value="' . $reg->idalmacen . '"> ' . $reg->ubicacion . '</option>';
				}
				break;
		}
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
