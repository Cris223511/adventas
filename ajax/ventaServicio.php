<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['ventas'] == 1) {
		require_once "../modelos/VentaServicio.php";

		$venta_servicio = new VentaServicio();

		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["idalmacen"];
		$cargo = $_SESSION["cargo"];

		$idventa_servicio = isset($_POST["idventa_servicio"]) ? limpiarCadena($_POST["idventa_servicio"]) : "";
		$idmetodopago = isset($_POST["idmetodopago"]) ? limpiarCadena($_POST["idmetodopago"]) : "";
		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
		$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
		$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
		$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
		$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
		$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

		$sunat = isset($_POST["sunat"]) ? limpiarCadena($_POST["sunat"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idventa_servicio)) {
					$numeroExiste = $venta_servicio->verificarNumeroExiste($num_comprobante, $idalmacen);
					if ($numeroExiste) {
						echo "El número correlativo que ha ingresado ya existe en el local seleccionado.";
					} else {
						$rspta = $venta_servicio->insertar($idusuario, $idmetodopago, $idalmacen, $idcliente, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total_venta, $_POST["idservicio"], $_POST["cantidad"], $_POST["precio_venta"], $_POST["descuento"]);
						if ($rspta === true) {
							echo "Venta de servicio registrado";
						} else {
							echo $rspta;
						}
					}
				} else {
				}
				break;

			case 'anular':
				$rspta = $venta_servicio->anular($idventa_servicio);
				echo $rspta ? "Venta de servicio anulada" : "Venta de servicio no se puede anular";
				break;

			case 'mostrar':
				$rspta = $venta_servicio->mostrar($idventa_servicio);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'eliminar':
				$rspta = $venta_servicio->eliminar($idventa_servicio);
				echo $rspta ? "Venta de servicio eliminada" : "Venta de servicio no se puede eliminar";
				break;

			case 'listarDetalle':
				//Recibimos el idventa_servicio
				$id = $_GET['id'];

				$rspta = $venta_servicio->listarDetalle($id);
				$rspta2 = $venta_servicio->mostrar($id);

				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio venta</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td></td><td>' . $reg->nombre . '</td><td>' . $reg->cantidad . '</td><td>' . "<nav>S/. " . number_format($reg->precio_venta, 2) . "</nav>" . '</td><td>' . "<nav>S/. $reg->descuento</nav>" . '</td><td>' . "<nav>S/. " . number_format($reg->subtotal, 2) . "</nav>" . '</td></tr>';
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
						<th><h4 id="igv">S/.' . number_format($igv, 2) . '</h4><input type="hidden" name="total_igv" id="total_igv"></th>
						</tr>
						<tr>
						<th>TOTAL</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="total">S/.' . number_format($rspta2["total_venta"], 2) . '</h4><input type="hidden" name="total_venta" id="total_venta"></th>
						</tr>
					</tfoot>';
				break;

			case 'listar':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];

				if ($cargo == "superadmin") {
					if ($fecha_inicio == "" && $fecha_fin == "") {
						$rspta = $venta_servicio->listar();
					} else {
						$rspta = $venta_servicio->listarPorFecha($fecha_inicio, $fecha_fin);
					}
				} else {
					if ($fecha_inicio == "" && $fecha_fin == "") {
						$rspta = $venta_servicio->listarPorUsuario($idalmacenSession);
					} else {
						$rspta = $venta_servicio->listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin);
					}
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
						$url = '../reportes/exTicketServicio.php?id=';
					} else {
						$url = '../reportes/exFacturaServicio.php?id=';
					}

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							(($reg->estado == 'Aceptado') ?
								(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" onclick="anular(' . $reg->idventa_servicio . ')"><i class="fa fa-close"></i></button>')) : ('')) .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" onclick="eliminar(' . $reg->idventa_servicio . ')"><i class="fa fa-trash"></i></button>') .
							(($reg->estado == 'Aceptado') ?
								('<a target="_blank" href="' . $url . $reg->idventa_servicio . '"> <button class="btn btn-secondary" style="color: black !important;"><i class="fa fa-file"></i></button></a>') : ('')) .
							'</div>',
						"1" => $reg->cliente,
						"2" => $reg->almacen,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_venta,
						"7" => $reg->usuario . ' - ' . $cargo_detalle,
						"8" => $reg->fecha,
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'selectServicio':
				require_once "../modelos/Servicio.php";
				$servicio = new Servicio();

				if ($cargo == "superadmin") {
					$rspta = $servicio->listar();
				} else {
					$rspta = $servicio->listarPorUsuario($idalmacenSession);
				}

				echo '<option value="">Busca un servicio.</option>';
				while ($reg = $rspta->fetch_object()) {
					if ($reg->estado != '0') {
						echo '<option value="' . $reg->idservicio . '">' . $reg->codigo_producto . ' - ' . $reg->nombre . '</option>';
					}
				}
				break;

			case 'listarServicios':
				require_once "../modelos/Servicio.php";
				$servicio = new Servicio();

				$idservicios = $_GET["idservicio"];

				$rspta = $servicio->listarActivosVentaPorArticulo($idservicios);

				$productos = array();
				while ($reg = $rspta->fetch_object()) {
					$producto = array(
						'idservicio' => $reg->idservicio,
						'servicio' => $reg->nombre,
						'precio_venta' => $reg->precio_venta == '' ? "0" : $reg->precio_venta,
						'codigo_producto' => $reg->codigo_producto
					);
					array_push($productos, $producto);
				}
				echo json_encode($productos);
				break;

			case 'listarArticulosVenta':
				require_once "../modelos/Servicio.php";
				$servicio = new Servicio();

				if ($cargo == "superadmin") {
					$rspta = $servicio->listar();
				} else {
					$rspta = $servicio->listarPorUsuario($idalmacenSession);
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
						"0" => ($reg->estado == '1') ? '<button class="btn btn-secondary" data-idservicio="' . $reg->idservicio . '" onclick="agregarDetalle(' . $reg->idservicio . ',\'' . $reg->nombre . '\',\'' . $reg->precio_venta . '\'); bloquearPrecios(); disableButton(this);"><span class="fa fa-plus"></span></button>' : '',
						"1" => "<img src='../files/servicios/" . $reg->imagen . "' height='50px' width='50px' >",
						"2" => $reg->nombre,
						"3" => $reg->almacen,
						"4" => $reg->codigo_producto,
						"5" => $reg->precio_venta == '' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta,
						"6" => $reg->usuario . ' - ' . $cargo_detalle,
						"7" => ($reg->estado == '1') ? '<span class="label bg-green">Activado</span>' :
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

			case 'getLastNumComprobante':
				$row = mysqli_fetch_assoc($venta_servicio->getLastNumComprobante($idalmacenSession));
				if ($row != null) {
					$last_num_comprobante = $row["last_num_comprobante"];
					echo $last_num_comprobante;
				} else {
					echo $row;
				}
				break;

			case 'getLastSerie':
				$row = mysqli_fetch_assoc($venta_servicio->getLastSerie());
				if ($row != null) {
					$last_serie_comprobante = $row["last_serie_comprobante"];
					echo $last_serie_comprobante;
				} else {
					echo $row;
				}
				break;

				/* ======================= SELECTS ======================= */

			case 'listarTodosLocalActivosPorUsuario':
				$rspta = $venta_servicio->listarTodosLocalActivosPorUsuario($idalmacen);

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
