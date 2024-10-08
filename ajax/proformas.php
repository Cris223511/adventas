<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['proforma'] == 1) {
		require_once "../modelos/Proformas.php";

		$proforma = new Proforma();

		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["almacen"];
		$cargo = $_SESSION["cargo"];

		$idproforma = isset($_POST["idproforma"]) ? limpiarCadena($_POST["idproforma"]) : "";
		$idmetodopago = isset($_POST["idmetodopago"]) ? limpiarCadena($_POST["idmetodopago"]) : "";
		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
		$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
		$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
		$num_proforma = isset($_POST["num_proforma"]) ? limpiarCadena($_POST["num_proforma"]) : "";

		$serie_venta = isset($_POST["lastNumSerieV"]) ? limpiarCadena($_POST["lastNumSerieV"]) : "";
		$num_venta = isset($_POST["lastNumCompV"]) ? limpiarCadena($_POST["lastNumCompV"]) : "";

		$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
		$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

		$sunat = isset($_POST["sunat"]) ? limpiarCadena($_POST["sunat"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				$numeroExiste = $proforma->verificarNumeroExiste($num_proforma, $idalmacen);
				if ($numeroExiste) {
					echo "El número de proforma que ha ingresado ya existe en el local seleccionado.";
				} else {
					$rspta = $proforma->insertar($idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_comprobante, $num_proforma, $impuesto, $total_venta, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_compra"], $_POST["precio_venta"], $_POST["descuento"]);
					if ($rspta === true) {
						echo "Proforma registrada";
					} else {
						echo $rspta;
					}
				}
				break;

			case 'guardaryeditar2':
				$rspta = $proforma->insertar2($idproforma, $idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_venta, $num_venta, $impuesto, $total_venta, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_venta"], $_POST["descuento"]);
				echo $rspta ? "Proforma enviada" : "Proforma no se pudo registrar.";
				break;

			case 'anular':
				$rspta = $proforma->anular($idproforma);
				echo $rspta ? "Proforma anulada" : "Proforma no se puede anular";
				break;

			case 'activar':
				$rspta = $proforma->activar($idproforma);
				echo $rspta ? "Proforma activada" : "Proforma no se puede activar";
				break;

			case 'mostrar':
				$rspta = $proforma->mostrar($idproforma);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'eliminar':
				$rspta = $proforma->eliminar($idproforma);
				echo $rspta ? "Proforma eliminada" : "Proforma no se puede eliminar";
				break;

			case 'listarDetalle':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $proforma->listarDetalle($id);
				$rspta2 = $proforma->mostrar($id);

				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Stock</th>
                                    <th>Cantidad</th>
                                    <th>Precio compra</th>
                                    <th>Precio venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td></td><td>' . $reg->nombre . '</td><td>' . $reg->stock . '</td><td>' . $reg->cantidad . '</td><td>' . "<nav>S/. " . number_format($reg->precio_compra, 2) . "</nav>" . '</td><td>' . "<nav>S/. " . number_format($reg->precio_venta, 2) . "</nav>" . '</td><td>' . "<nav>S/. $reg->descuento</nav>" . '</td><td>' . "<nav>S/. " . number_format($reg->subtotal, 2) . "</nav>" . '</td></tr>';
					$igv = $igv + ($rspta2["impuesto"] == 18 ? $reg->subtotal * 0.18 : $reg->subtotal * 0);
				}

				echo '
					<tfoot>
						<tr>
						<th>IGV</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="igv">S/.' . number_format($igv, 2) . '</h4><input type="hidden" name="total_igv" id="total_igv"></th>
						</tr>
						<tr>
						<th>TOTAL</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="total">S/.' . number_format($rspta2["total_venta"], 2) . '</h4><input type="hidden" name="total_venta" id="total_venta"></th>
						</tr>
					</tfoot>';
				break;

			case 'listarDetalle2':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $proforma->listarDetalle($id);
				$rspta2 = $proforma->mostrar($id);

				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
									<th>Opciones</th>
									<th>Artículo</th>
									<th>Stock</th>
									<th>Cantidad</th>
									<th>Precio compra</th>
									<th>Precio venta</th>
									<th>Descuento</th>
									<th>Subtotal</th>
								</thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas">
							<td></td>
							<td><input type="hidden" name="idarticulo[]" value="' . $reg->idarticulo . '">' . $reg->nombre . '</td>
							<td>' . $reg->stock . '</td>
							<td><input type="number" name="cantidad[]" id="cantidad[]" value="' . $reg->cantidad . '" readonly></td>
							<td>' . "<nav>S/. " . number_format($reg->precio_compra, 2) . "</nav>" . '</td>
							<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="' . $reg->precio_venta . '" readonly></td>
							<td><input type="number" name="descuento[]" id="descuento[]" value="' . "$reg->descuento" . '" readonly></td>
							<td>' . "<nav>S/. " . number_format($reg->subtotal, 2) . "</nav>" . '</td>
						  </tr>';

					$igv = $igv + ($rspta2["impuesto"] == 18 ? $reg->subtotal * 0.18 : $reg->subtotal * 0);
				}
				echo '
					<tfoot>
						<tr>
						<th>IGV</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="igv2">S/.' . number_format($igv, 2) . '</h4><input type="hidden" name="total_igv" id="total_igv2" value="' . number_format($igv, 2) . '"></th>
						</tr>
						<tr>
						<th>TOTAL</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="total2">S/.' . number_format($rspta2["total_venta"], 2) . '</h4><input type="hidden" name="total_venta" id="total_venta2" value="' . number_format($rspta2["total_venta"], 2) . '"></th>
						</tr>
					</tfoot>';
				break;

			case 'listar':
				if ($cargo == "superadmin") {
					$rspta = $proforma->listar();
				} else {
					$rspta = $proforma->listarPorUsuario($idalmacenSession);
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

				$firstIteration = true;
				$totalPrecioVenta = 0;

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

					if ($reg->tipo_comprobante == 'Ticket') {
						$url = '../reportes/exTicketProforma.php?id=';
					} else {
						$url = '../reportes/exFacturaProforma.php?id=';
					}

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							(($reg->estado == 'Pendiente') ?
								('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de proforma" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->idproforma . ');"><i class="fa fa-eye"></i></button></a>' .
									mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" title="Anular proforma" style="color: black" onclick="anular(' . $reg->idproforma . ')"><i class="fa fa-close"></i></button>') .
									mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" title="Eliminar proforma" onclick="eliminar(' . $reg->idproforma . ')"><i class="fa fa-trash"></i></button>') .
									('<a target="_blank" href="' . $url . $reg->idproforma . '"> <button class="btn btn-secondary" style="color: black;"><i class="fa fa-file"></i></button></a>')) .
								('<a data-toggle="modal" href="#myModal3" title="Enviar y aceptar proforma" style="color: black" onclick="enviar(' . $reg->idproforma . ')"><button class="btn btn-secondary"><i class="fa fa-sign-in"></i></button></a>')
								: (($reg->estado == 'Finalizado') ?
									('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de proforma" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->idproforma . ')"><i class="fa fa-eye"></i></button></a>' .
										(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" title="Eliminar proforma" onclick="eliminar(' . $reg->idproforma . ')"><i class="fa fa-trash"></i></button>')) .
										('<a target="_blank" href="' . $url . $reg->idproforma . '"> <button class="btn btn-secondary" style="color: black;"><i class="fa fa-file"></i></button></a>'))
									: ('<button class="btn btn-secondary" title="Mirar detalles de proforma" onclick="mostrar(' . $reg->idproforma . ')"><i class="fa fa-eye"></i></button>' .
										(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" title="Activar proforma" style="color: black; width: 36px" onclick="activar(' . $reg->idproforma . ')"><i class="fa fa-check"></i></button>')) .
										(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" title="Eliminar proforma" onclick="eliminar(' . $reg->idproforma . ')"><i class="fa fa-trash"></i></button>'))))) . '</div>',
						"1" => $reg->cliente,
						"2" => $reg->almacen,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_proforma,
						"6" => $reg->total_venta,
						"7" => $reg->usuario . ' - ' . $cargo_detalle,
						"8" => $reg->fecha,
						"9" => ($reg->estado == 'Pendiente') ? '<span class="label bg-orange">Pendiente</span>' : (($reg->estado == 'Finalizado') ? '<span class="label bg-green">Enviado</span>' : '<span class="label bg-red">Anulado</span>')
					);

					$totalPrecioVenta += $reg->total_venta;
					$firstIteration = false; // Marcar que ya no es la primera iteración
				}

				if (!$firstIteration) {
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "<strong>TOTAL</strong>",
						"6" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"7" => "",
						"8" => "",
						"9" => "",
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

			case 'selectCliente':
				require_once "../modelos/Persona.php";
				$persona = new Persona();

				if ($cargo == "superadmin") {
					$rspta = $persona->listarc();
				} else {
					$rspta = $persona->listarcPorUsuario($idalmacenSession);
				}

				echo '<option value="">- Seleccione -</option>';
				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . ' - ' . $reg->tipo_persona . '</option>';
				}
				break;

			case 'selectProducto':
				require_once "../modelos/Articulo.php";
				$articulo = new Articulo();

				if ($cargo == "superadmin") {
					$rspta = $articulo->listar();
				} else {
					$rspta = $articulo->listarPorUsuario($idalmacenSession);
				}

				echo '<option value="">Busca un producto.</option>';
				while ($reg = $rspta->fetch_object()) {
					if (!empty($reg->codigo) && $reg->stock != '0') {
						echo '<option value="' . $reg->idarticulo . '">' . $reg->codigo . ' - ' . $reg->nombre . '</option>';
					}
				}
				break;

			case 'listarProductos':
				require_once "../modelos/Articulo.php";
				$articulo = new Articulo();

				$idarticulos = $_GET["idarticulo"];

				$rspta = $articulo->listarActivosVentaPorArticulo($idarticulos);

				$productos = array();
				while ($reg = $rspta->fetch_object()) {
					$producto = array(
						'idarticulo' => $reg->idarticulo,
						'articulo' => $reg->nombre,
						'stock' => $reg->stock,
						'precio_compra' => $reg->precio_compra == '' ? "0" : $reg->precio_compra,
						'precio_venta' => $reg->precio_venta == '' ? "0" : $reg->precio_venta,
						'codigo_producto' => $reg->codigo_producto
					);
					array_push($productos, $producto);
				}
				echo json_encode($productos);
				break;

			case 'listarArticulosVenta':
				require_once "../modelos/Articulo.php";
				$articulo = new Articulo();

				if ($cargo == "superadmin") {
					$rspta = $articulo->listar();
				} else {
					$rspta = $articulo->listarPorUsuario($idalmacenSession);
				}

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

					$data[] = array(
						"0" => ($reg->stock != '0') ? '<button class="btn btn-secondary" data-idarticulo="' . $reg->idarticulo . '" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . $reg->nombre . '\',\'' . $reg->stock . '\',\'' . $reg->precio_compra . '\',\'' . (($reg->medida != "Paquetes") ? ($reg->precio_venta) : ($reg->precio_venta_mayor)) . '\'); bloquearPrecios(); disableButton(this);"><span class="fa fa-plus"></span></button>' : '',
						"1" => '<a href="../files/articulos/' . $reg->imagen . '" class="galleria-lightbox" style="z-index: 10000 !important;">
									<img src="../files/articulos/' . $reg->imagen . '" height="50px" width="50px" class="img-fluid">
								</a>',
						"2" => $reg->nombre,
						"3" => $reg->medida,
						"4" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->descripcion == '') ? 'Sin registrar.' : $reg->descripcion) . "</textarea>",
						"5" => (($reg->categoria != "") ? $reg->categoria : "Sin registrar."),
						"6" => $reg->almacen,
						"7" => (($reg->marca != "") ? $reg->marca : "Sin registrar."),
						"8" => $reg->codigo_producto,
						"9" => ($reg->codigo == '') ? 'Sin registrar.' : $reg->codigo,
						"10" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span style="color: #Ea9900; font-weight: bold">' . $reg->stock . '</span>' : (($reg->stock != '0') ? '<span>' . $reg->stock . '</span>' : '<span style="color: red; font-weight: bold">' . $reg->stock . '</span>'),
						"11" => $reg->stock_minimo,
						"12" => $reg->precio_compra == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_compra,
						"13" => $reg->precio_venta == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta,
						"14" => $reg->precio_venta_mayor == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta_mayor,
						"15" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->talla == "") ? 'Sin registrar.' : $reg->talla) . "</textarea>",
						"16" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->color == "") ? 'Sin registrar.' : $reg->color) . "</textarea>",
						"17" => ($reg->peso == "0.00") ? 'Sin registrar.' : $reg->peso,
						"18" => ($reg->posicion == "") ? 'Sin registrar.' : $reg->posicion,
						"19" => ($reg->fecha_emision == '00-00-0000') ? 'Sin registrar.' : $reg->fecha_emision,
						"20" => ($reg->fecha_vencimiento == '00-00-0000') ? 'Sin registrar.' : $reg->fecha_vencimiento,
						"21" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->nota_1 == "") ? 'Sin registrar.' : $reg->nota_1) . "</textarea>",
						"22" => "<textarea type='text' class='form-control' rows='2' style='background-color: white !important; cursor: default; height: 60px !important;' readonly>" . (($reg->nota_2 == "") ? 'Sin registrar.' : $reg->nota_2) . "</textarea>",
						"23" => $reg->usuario . ' - ' . $cargo_detalle,
						"24" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span class="label bg-orange">agotandose</span>' : (($reg->stock != '0') ? '<span class="label bg-green">Disponible</span>' : '<span class="label bg-red">agotado</span>'),
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

			case 'getLastNumProforma':
				$result = $proforma->getLastNumProforma($idalmacenSession);
				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_assoc($result);
					$last_num_proforma = $row["last_num_proforma"];
				} else {
					$last_num_proforma = 0;
				}
				echo $last_num_proforma;
				break;

			case 'getLastSerie':
				$row = mysqli_fetch_assoc($proforma->getLastSerie());
				if ($row != null) {
					$last_serie_comprobante = $row["last_serie_comprobante"];
					echo $last_serie_comprobante;
				} else {
					echo $row;
				}
				break;

			case 'verificarStockMinimo':
				$idarticulo = $_GET['id'];
				$nombre = $_GET['nombre'];
				$cantidad = $_GET['cantidad'];

				require_once "../modelos/Articulo.php";
				$articulo = new Articulo();
				// saber su stock mínimo
				$row = mysqli_fetch_assoc($articulo->identificarStockMinimo($idarticulo));
				$stock_minimo = $row['stock_minimo'];

				// validar su stock mínimo
				$validar_stock_minimo = $articulo->verificarStockMinimo($idarticulo, $cantidad);

				if ($validar_stock_minimo) {
					echo "La cantidad de tu artículo <strong>" . $nombre . "</strong> es menor a su stock mínimo, que es <strong>" . $stock_minimo . "</strong>.";
				} else {
				}
				break;

				/* ======================= SELECTS ======================= */

			case 'listarTodosLocalActivosPorUsuario':
				$rspta = $proforma->listarTodosLocalActivosPorUsuario($idalmacen);

				$result = mysqli_fetch_all($rspta, MYSQLI_ASSOC);

				$data = [];
				foreach ($result as $row) {
					$tabla = $row['tabla'];
					unset($row['tabla']);
					$data[$tabla][] = $row;
				}

				echo json_encode($data);
				break;

				/* ======================= SUNAT ======================= */

			case 'consultaSunat':
				// Token para la API
				$token = 'apis-token-8814.1Tq4Gy-yKM7ZSWPx6eQC0feuDpVKbuEZ';

				$data = "";
				$curl = curl_init();

				try {
					if (strlen($sunat) == 8) {
						// DNI
						$url = 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $sunat;
						$referer = 'https://apis.net.pe/consulta-dni-api';
					} elseif (strlen($sunat) == 11) {
						// RUC
						$url = 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $sunat;
						$referer = 'http://apis.net.pe/api-ruc';
					} elseif (strlen($sunat) < 8) {
						// Mensaje para DNI no válido
						$data = "El DNI debe tener 8 caracteres.";
						echo $data;
						break;
					} elseif (strlen($sunat) > 8 && strlen($sunat) < 11) {
						// Mensaje para RUC no válido
						$data = "El RUC debe tener 11 caracteres.";
						echo $data;
						break;
					}

					// configuración de cURL
					curl_setopt_array($curl, array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_SSL_VERIFYPEER => 0,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 2,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_CUSTOMREQUEST => 'GET',
						CURLOPT_HTTPHEADER => array(
							'Referer: ' . $referer,
							'Authorization: Bearer ' . $token
						),
					));

					$response = curl_exec($curl);

					if ($response === false) {
						throw new Exception(curl_error($curl));
					}

					if (stripos($response, 'Not Found') !== false || stripos($response, '{"message":"ruc no valido"}') !== false) {
						$data = (strlen($sunat) == 8) ? "DNI no valido" : "RUC no valido";
					} else {
						$data = $response;
					}
				} catch (Exception $e) {
					$data = "Error al procesar la solicitud: " . $e->getMessage();
				} finally {
					curl_close($curl);
				}

				echo $data;
				break;
		}
		//Fin de las validaciones de acceso
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
