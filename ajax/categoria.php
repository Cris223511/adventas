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
		require_once "../modelos/Categoria.php";

		$categoria = new Categoria();

		$idusuario = $_SESSION["idusuario"];
		$cargo = $_SESSION["cargo"];

		$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
		$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idcategoria)) {
					$categoriaExiste = $categoria->verificarCategoriaExiste($nombre);
					if ($categoriaExiste) {
						echo "El nombre de la categoría que ha ingresado ya existe.";
					} else {
						$rspta = $categoria->insertar($idusuario, $nombre, $descripcion);
						echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
					}
				} else {
					$categoriaExiste = $categoria->verificarCategoriaEditarExiste($nombre, $idcategoria);
					if ($categoriaExiste) {
						echo "El nombre de la categoría que ha ingresado ya existe.";
					} else {
						$rspta = $categoria->editar($idcategoria, $nombre, $descripcion);
						echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $categoria->desactivar($idcategoria);
				echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
				break;

			case 'activar':
				$rspta = $categoria->activar($idcategoria);
				echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
				break;

			case 'eliminar':
				$rspta = $categoria->eliminar($idcategoria);
				echo $rspta ? "Categoría eliminada" : "Categoría no se pudo eliminar";
				break;

			case 'mostrar':
				$rspta = $categoria->mostrar($idcategoria);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listar':
				$rspta = $categoria->listar();

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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idcategoria . ')"><i class="fa fa-pencil"></i></button>') .
							(($reg->estado == '1') ?
								(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="desactivar(' . $reg->idcategoria . ')"><i class="fa fa-close"></i></button>')) : (mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; width: 35px; height: 35px;" onclick="activar(' . $reg->idcategoria . ')"><i style="margin-left: -2px" class="fa fa-check"></i></button>'))) .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="height: 35px;" onclick="eliminar(' . $reg->idcategoria . ')"><i class="fa fa-trash"></i></button>') .
							'</div>',
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->nombre,
						"3" => ($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion,
						"4" => ($reg->estado == '1') ? '<span class="label bg-green">Activado</span>' :
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
