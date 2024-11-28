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
	if ($_SESSION['perfilu'] == 1) {
		require_once "../modelos/LocalesExternos.php";

		$locales = new LocalExterno();

		// Variables de sesión a utilizar.
		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["idalmacen"];
		$cargo = $_SESSION["cargo"];

		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$idusuariolocal = isset($_POST["idusuariolocal"]) ? limpiarCadena($_POST["idusuariolocal"]) : "";
		$ubicacion = isset($_POST["ubicacion"]) ? limpiarCadena($_POST["ubicacion"]) : "";
		$local_ruc = isset($_POST["local_ruc"]) ? limpiarCadena($_POST["local_ruc"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
		$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (!empty($_FILES['imagen']['name'])) {
					$uploadDirectory = "../files/locales/";

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

				if (empty($idalmacen)) {
					$nombreExiste = $locales->verificarNombreExiste($ubicacion);
					if ($nombreExiste) {
						echo "El nombre del local ya existe.";
					} else {
						$rspta = $locales->agregar($idusuario, $ubicacion, $local_ruc, $descripcion, $imagen);
						echo $rspta ? "Local registrado" : "El local no se pudo registrar";
					}
				} else {
					$nombreExiste = $locales->verificarNombreEditarExiste($ubicacion, $idalmacen);
					if ($nombreExiste) {
						echo "El nombre del local ya existe.";
					} else {
						$rspta = $locales->editar($idalmacen, $ubicacion, $local_ruc, $descripcion, $imagen);
						echo $rspta ? "Local actualizado" : "El local no se pudo actualizar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $locales->desactivar($idalmacen);
				echo $rspta ? "Local desactivado" : "El local no se pudo desactivar";
				break;

			case 'activar':
				$rspta = $locales->activar($idalmacen);
				echo $rspta ? "Local activado" : "El local no se pudo activar";
				break;

			case 'eliminar':
				$rspta = $locales->eliminar($idalmacen);
				echo $rspta ? "Local eliminado" : "El local no se pudo eliminar";
				break;

			case 'mostrar':
				$rspta = $locales->mostrar($idalmacen);
				echo json_encode($rspta);
				break;

			case 'listar':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];

				if ($fecha_inicio == "" && $fecha_fin == "") {
					$rspta = $locales->listar($idalmacenSession);
				} else {
					$rspta = $locales->listarPorFecha($idalmacenSession, $fecha_inicio, $fecha_fin);
				}

				$data = array();

				function mostrarBoton($cargo, $idusuario, $buttonType)
				{
					if ($cargo == "superadmin" || $cargo == "admin") {
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
							mostrarBoton($cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idalmacen . ')"><i class="fa fa-pencil"></i></button>') .
							'<a data-toggle="modal" href="#myModal"><button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="trabajadores(' . $reg->idalmacen . ',\'' . $reg->ubicacion . '\')"><i class="fa fa-user" style="color: black;"></i></button></a>' .
							(($reg->estado == 'activado') ?
								(mostrarBoton($cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idalmacen . ')"><i class="fa fa-close"></i></button>')) : (mostrarBoton($cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idalmacen . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>'))) .
							'</div>',
						"1" => '<a href="../files/locales/' . $reg->imagen . '" class="galleria-lightbox" style="z-index: 10000 !important;">
									<img src="../files/locales/' . $reg->imagen . '" height="50px" width="50px" class="img-fluid">
								</a>',
						"2" => $reg->ubicacion,
						"3" => "N° " . $reg->local_ruc,
						"4" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;'' readonly>" . (($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion) . "</textarea>",
						"5" => $reg->fecha,
						"6" => ($reg->estado == 'activado') ? '<span class="label bg-green">Activado</span>' :
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

				$rspta = $locales->listarUsuariosPorLocal($idalmacen2);

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

					$telefono = ($reg->telefono == '') ? 'Sin registrar.' : number_format($reg->telefono, 0, '', ' ');

					$data[] = array(
						"0" => $reg->login,
						"1" => $cargo_detalle,
						"2" => $reg->nombre,
						"3" => $reg->tipo_documento,
						"4" => $reg->num_documento,
						"5" => $telefono,
						"6" => $reg->email,
						"7" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50px' width='50px' >",
						"8" => ($reg->estado) ? '<span class="label bg-green">Activado</span>' :
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
		}
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
