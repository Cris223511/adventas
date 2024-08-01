<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if (($_SESSION['almacen'] == 1 || !empty($_SESSION['cargo'])) && $_SESSION["cargo"] == "superadmin") {
		require_once "../modelos/ArticuloExterno.php";

		$articulo = new ArticuloExterno();

		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["idalmacen"];
		$cargo = $_SESSION["cargo"];

		$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
		$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$idmarcas = isset($_POST["idmarcas"]) ? limpiarCadena($_POST["idmarcas"]) : "";
		$idmedida = isset($_POST["idmedida"]) ? limpiarCadena($_POST["idmedida"]) : "";
		$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
		$codigo_producto = isset($_POST["codigo_producto"]) ? limpiarCadena($_POST["codigo_producto"]) : "";
		$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
		$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";
		$stock_minimo = isset($_POST["stock_minimo"]) ? limpiarCadena($_POST["stock_minimo"]) : "";
		$precio_compra = isset($_POST["precio_compra"]) ? limpiarCadena($_POST["precio_compra"]) : "";
		$precio_venta = isset($_POST["precio_venta"]) ? limpiarCadena($_POST["precio_venta"]) : "";
		$ganancia = isset($_POST["ganancia"]) ? limpiarCadena($_POST["ganancia"]) : "";
		$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
		$talla = isset($_POST["talla"]) ? limpiarCadena($_POST["talla"]) : "";
		$color = isset($_POST["color"]) ? limpiarCadena($_POST["color"]) : "";
		$peso = isset($_POST["peso"]) ? limpiarCadena($_POST["peso"]) : "";
		$posicion = isset($_POST["posicion"]) ? limpiarCadena($_POST["posicion"]) : "";
		$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
		$barra = isset($_POST["barra"]) ? limpiarCadena($_POST["barra"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':

				if (!empty($_FILES['imagen']['name'])) {
					$uploadDirectory = "../files/articulos/";

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

				if (empty($idarticulo)) {
					$codigoExiste = $articulo->verificarCodigoExiste($codigo);
					$codigoProductoExiste = $articulo->verificarCodigoProductoExiste($codigo_producto);
					if ($codigoProductoExiste) {
						echo "El código del artículo que ha ingresado ya existe.";
					} else if ($codigoExiste && $codigo != "") {
						echo "El código de barra del artículo que ha ingresado ya existe.";
					} else {
						$rspta = $articulo->insertar($idusuario, $idcategoria, $idalmacen, $idmarcas, $idmedida, $codigo, $codigo_producto, $nombre, $stock, $stock_minimo, $precio_compra, $precio_venta, $ganancia, $descripcion, $talla, $color, $peso, $posicion, $imagen);
						echo $rspta ? "Artículo registrado" : "Artículo no se pudo registrar";
					}
				} else {
					$nombreExiste = $articulo->verificarCodigoProductoEditarExiste($codigo_producto, $idarticulo);
					if ($nombreExiste) {
						echo "El código del artículo que ha ingresado ya existe.";
					} else {
						$rspta = $articulo->editar($idarticulo, $idcategoria, $idalmacen, $idmarcas, $idmedida, $codigo, $codigo_producto, $nombre, $stock, $stock_minimo, $precio_compra, $precio_venta, $ganancia, $descripcion, $talla, $color, $peso, $posicion, $imagen);
						echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
					}
				}
				break;

			case 'desactivar':
				$rspta = $articulo->desactivar($idarticulo);
				echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
				break;

			case 'activar':
				$rspta = $articulo->activar($idarticulo);
				echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
				break;

			case 'eliminar':
				$rspta = $articulo->eliminar($idarticulo);
				echo $rspta ? "Artículo eliminado" : "Artículo no se puede eliminar";
				break;

			case 'mostrar':
				$rspta = $articulo->mostrar($idarticulo);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'listar':
				$rspta = $articulo->listar($idalmacenSession);

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
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" style="margin-right: 3px; height: 35px;" onclick="mostrar(' . $reg->idarticulo . '); bloquearPrecioCompraVenta();"><i class="fa fa-pencil"></i></button>') .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary "style="height: 35px;" onclick="eliminar(' . $reg->idarticulo . ')"><i class="fa fa-trash"></i></button>') .
							'</div>',
						"1" => '<a href="../files/articulos/' . $reg->imagen . '" class="galleria-lightbox" style="z-index: 10000 !important;">
									<img src="../files/articulos/' . $reg->imagen . '" height="50px" width="50px" class="img-fluid">
								</a>',
						"2" => $reg->nombre,
						"3" => ($reg->medida == "") ? 'Sin registrar.' : $reg->medida,
						"4" => $reg->categoria,
						"5" => $reg->almacen,
						"6" => $reg->marca,
						"7" => $reg->codigo_producto,
						"8" => ($reg->codigo == '') ? 'Sin registrar.' : $reg->codigo,
						"9" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span style="color: #Ea9900; font-weight: bold">' . $reg->stock . '</span>' : (($reg->stock != '0') ? '<span>' . $reg->stock . '</span>' : '<span style="color: red; font-weight: bold">' . $reg->stock . '</span>'),
						"10" => $reg->stock_minimo,
						"11" => $reg->precio_compra == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_compra,
						"12" => $reg->precio_venta == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta,
						"13" => $reg->ganancia == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->ganancia,
						"14" => ($reg->talla == "") ? 'Sin registrar.' : $reg->talla,
						"15" => ($reg->color == "") ? 'Sin registrar.' : $reg->color,
						"16" => ($reg->peso == "0.00") ? 'Sin registrar.' : $reg->peso,
						"17" => ($reg->posicion == "") ? 'Sin registrar.' : $reg->posicion,
						"18" => $reg->usuario . ' - ' . $cargo_detalle,
						"19" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span class="label bg-orange">agotandose</span>' : (($reg->stock != '0') ? '<span class="label bg-green">Disponible</span>' : '<span class="label bg-red">agotado</span>'),
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
				if ($cargo == "superadmin") {
					$rspta = $articulo->listarTodosActivos();
				} else {
					$rspta = $articulo->listarTodosActivosPorUsuario($idusuario, $idalmacenSession);
				}

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
