<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['pagos'] == 1 || !empty($_SESSION['cargo'])) {
		require_once "../modelos/Metodo_pago.php";

		$metodo_pago = new MetodoPago();

		$idusuario = $_SESSION["idusuario"];
		$cargo = $_SESSION["cargo"];

		$idmetodopago = isset($_POST["idmetodopago"]) ? limpiarCadena($_POST["idmetodopago"]) : "";
		$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
		$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (!empty($_FILES['imagen']['name'])) {
					$uploadDirectory = "../files/metodo_pago/";
				
					$tempFile = $_FILES['imagen']['tmp_name'];
					$fileExtension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
					$newFileName = sprintf("%09d", rand(0, 999999999)) . '.' . $fileExtension;
					$targetFile = $uploadDirectory . $newFileName;
				
					// Verificar si es una imagen y mover el archivo
					$allowedExtensions = array('jpg', 'jpeg', 'png');
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

				if (empty($idmetodopago)) {
					$nombreExiste = $metodo_pago->verificarNombreExiste($nombre);
					if ($nombreExiste) {
						echo "El nombre del método de pago que ha ingresado ya existe.";
					} else {
						$rspta = $metodo_pago->insertar($idusuario, $nombre, $descripcion, $imagen);
						echo $rspta ? "Método de pago registrada" : "El método de pago no se pudo registrar";
					}
				} else {
					$nombreExiste = $metodo_pago->verificarNombreEditarExiste($nombre, $idmetodopago);
					if ($nombreExiste) {
						echo "El nombre del método de pago que ha ingresado ya existe.";
					} else {
						$rspta = $metodo_pago->editar($idmetodopago, $nombre, $descripcion, $imagen);
						echo $rspta ? "Método de pago actualizada" : "El método de pago no se pudo actualizar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $metodo_pago->desactivar($idmetodopago);
				echo $rspta ? "Método de pago desactivada" : "El método de pago no se pudo desactivar";
				break;

			case 'activar':
				$rspta = $metodo_pago->activar($idmetodopago);
				echo $rspta ? "Método de pago activada" : "El método de pago no se pudo activar";
				break;

			case 'eliminar':
				$rspta = $metodo_pago->eliminar($idmetodopago);
				echo $rspta ? "Método de pago eliminado" : "El método de pago no se pudo eliminar";
				break;

			case 'mostrar':
				$rspta = $metodo_pago->mostrar($idmetodopago);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listar':
				$rspta = $metodo_pago->listar();

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
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idmetodopago . ')"><i class="fa fa-pencil"></i></button>') .
							(($reg->estado == 'Activado') ?
								(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idmetodopago . ')"><i class="fa fa-close"></i></button>')) : (mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idmetodopago . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>'))) .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idmetodopago . ')"><i class="fa fa-trash"></i></button>') .
							'</div>',
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->nombre,
						"3" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;'' readonly>" . (($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion) . "</textarea>",
						"4" => "<img src='../files/metodo_pago/" . $reg->imagen . "' height='50px' width='50px' >",
						"5" => ($reg->estado == 'Activado') ? '<span class="label bg-green">Activado</span>' :
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

			case 'selectMetodoPago':
				$rspta = $metodo_pago->listarActivos();

				echo '<option value="">- Seleccione -</option>';
				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idmetodopago . '>' . $reg->nombre . '</option>';
				}
				break;
		}
		//Fin de las validaciones de acceso
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
