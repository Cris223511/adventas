<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['almacen'] == 1) {
		require_once "../modelos/Servicio.php";

		$servicio = new Servicio();

		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["idalmacen"];
		$cargo = $_SESSION["cargo"];

		$idservicio = isset($_POST["idservicio"]) ? limpiarCadena($_POST["idservicio"]) : "";
		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$codigo_producto = isset($_POST["codigo_producto"]) ? limpiarCadena($_POST["codigo_producto"]) : "";
		$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
		$precio_venta = isset($_POST["precio_venta"]) ? limpiarCadena($_POST["precio_venta"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
		$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':

				if (!empty($_FILES['imagen']['name'])) {
					$uploadDirectory = "../files/servicios/";

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

				if (empty($idservicio)) {
					$codigoProductoExiste = $servicio->verificarCodigoProductoExiste($codigo_producto);
					if ($codigoProductoExiste) {
						echo "El código del servicio que ha ingresado ya existe.";
					} else {
						$rspta = $servicio->insertar($idusuario, $idalmacen, $codigo_producto, $nombre, $precio_venta, $descripcion, $imagen);
						echo $rspta ? "Servicio registrado" : "Servicio no se pudo registrar";
					}
				} else {
					$nombreExiste = $servicio->verificarCodigoProductoEditarExiste($codigo_producto, $idservicio);
					if ($nombreExiste) {
						echo "El código del servicio que ha ingresado ya existe.";
					} else {
						$rspta = $servicio->editar($idservicio, $idalmacen, $codigo_producto, $nombre, $precio_venta, $descripcion, $imagen);
						echo $rspta ? "Servicio actualizado" : "Servicio no se pudo actualizar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $servicio->desactivar($idservicio);
				echo $rspta ? "Servicio Desactivado" : "Servicio no se puede desactivar";
				break;

			case 'activar':
				$rspta = $servicio->activar($idservicio);
				echo $rspta ? "Servicio activado" : "Servicio no se puede activar";
				break;

			case 'eliminar':
				$rspta = $servicio->eliminar($idservicio);
				echo $rspta ? "Servicio eliminado" : "Servicio no se puede eliminar";
				break;

			case 'mostrar':
				$rspta = $servicio->mostrar($idservicio);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listar':
				// if ($cargo == "superadmin") {
				// 	$rspta = $servicio->listar();
				// } else {
				$rspta = $servicio->listarPorUsuario($idalmacenSession);
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

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idservicio . '); bloquearPrecioCompraVenta();"><i class="fa fa-pencil"></i></button>') .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary "style="height: 35px;" onclick="eliminar(' . $reg->idservicio . ')"><i class="fa fa-trash"></i></button>') .
							(($reg->estado == '1') ?
								(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idservicio . ')"><i class="fa fa-close"></i></button>')) : (mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idservicio . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>'))) .
							'</div>',
						"1" => '<a href="../files/servicios/' . $reg->imagen . '" class="galleria-lightbox" style="z-index: 10000 !important;">
									<img src="../files/servicios/' . $reg->imagen . '" height="50px" width="50px" class="img-fluid">
								</a>',
						"2" => $reg->nombre,
						"3" => $reg->almacen,
						"4" => $reg->codigo_producto,
						"5" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion) . "</textarea>",
						"6" => $reg->precio_venta == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta,
						"7" => $reg->usuario . ' - ' . $cargo_detalle,
						"8" => ($reg->estado == '1') ? '<span class="label bg-green">Activado</span>' :
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

				/* ======================= SELECTS ======================= */

			case 'listarTodosActivos':
				// if ($cargo == "superadmin") {
				// 	$rspta = $servicio->listarTodosActivos();
				// } else {
				$rspta = $servicio->listarTodosActivosPorUsuario($idusuario, $idalmacenSession);
				// }

				$result = mysqli_fetch_all($rspta, MYSQLI_ASSOC);

				$data = [];
				foreach ($result as $row) {
					$tabla = $row['tabla'];
					unset($row['tabla']);
					$data[$tabla][] = $row;
				}

				echo json_encode($data);
				break;
		}
		//Fin de las validaciones de acceso
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
