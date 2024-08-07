<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start();
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html");
} else {
	if ($_SESSION['reporte'] == 1 || $_SESSION['reporteP'] == 1) {
		require_once "../modelos/Consultas.php";

		$consulta = new Consultas();


		switch ($_GET["op"]) {

				// ventas

			case 'listarventas':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listarventas($fecha_inicio, $fecha_fin, $idcliente);
				$rspta2 = $consulta->listarventasservicio($fecha_inicio, $fecha_fin, $idcliente);
				// $rspta3 = $consulta->listarventascuotas($fecha_inicio, $fecha_fin, $idcliente);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => '<strong>' . $reg->cliente . '</strong>',
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => '<strong>' . $reg->cliente . '</strong>',
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				// $totalPrecioVenta3 = 0;

				// if (!empty($data)) {
				// 	$data[] = array(
				// 		"0" => "<strong>VENTAS A CRÉDITO</strong>",
				// 		"1" => "",
				// 		"2" => "",
				// 		"3" => "",
				// 		"4" => "",
				// 		"5" => "",
				// 		"6" => "",
				// 		"7" => "",
				// 		"8" => "",
				// 		"9" => "",
				// 	);
				// }

				// while ($reg = $rspta3->fetch_object()) {
				// 	$cargo_detalle = "";

				// 	switch ($reg->cargo) {
				// 		case 'superadmin':
				// 			$cargo_detalle = "Superadministrador";
				// 			break;
				// 		case 'admin':
				// 			$cargo_detalle = "Administrador";
				// 			break;
				// 		case 'cliente':
				// 			$cargo_detalle = "Cliente";
				// 			break;
				// 		case 'vendedor':
				// 			$cargo_detalle = "Vendedor";
				// 			break;
				// 		case 'almacenero':
				// 			$cargo_detalle = "Almacenero";
				// 			break;
				// 		case 'encargado':
				// 			$cargo_detalle = "Encargado";
				// 			break;
				// 		default:
				// 			break;
				// 	}

				// 	$data[] = array(
				// 		"0" => $reg->fecha,
				// 		"1" => $reg->usuario . ' - ' . $cargo_detalle,
				// 		"2" => '<strong>' . $reg->cliente . '</strong>',
				// 		"3" => $reg->local,
				// 		"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
				// 		"5" => $reg->tipo_comprobante,
				// 		"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
				// 		"7" => $reg->total_venta,
				// 		"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
				// 		"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
				// 	);

				// 	$totalPrecioVenta3 += $reg->total_venta;
				// }

				if (!empty($data)) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					// $data[] = array(
					// 	"0" => "",
					// 	"1" => "",
					// 	"2" => "",
					// 	"3" => "",
					// 	"4" => "",
					// 	"5" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
					// 	"6" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
					// 	"7" => "",
					// 	"8" => "",
					// 	"9" => "",
					// );

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2, 2) . '</strong>',
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

			case 'listartodasventasfecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodasventasfecha($fecha_inicio, $fecha_fin);
				$rspta2 = $consulta->listartodasventasfechaservicio($fecha_inicio, $fecha_fin);
				$rspta3 = $consulta->listartodasventasfechacuotas($fecha_inicio, $fecha_fin);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
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

			case 'listartodasventas':

				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listartodasventas($idcliente);
				$rspta2 = $consulta->listartodasventasservicio($idcliente);
				// $rspta3 = $consulta->listartodasventascuotas($idcliente);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => '<strong>' . $reg->cliente . '</strong>',
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => '<strong>' . $reg->cliente . '</strong>',
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				// $totalPrecioVenta3 = 0;

				// if (!empty($data)) {
				// 	$data[] = array(
				// 		"0" => "<strong>VENTAS A CRÉDITO</strong>",
				// 		"1" => "",
				// 		"2" => "",
				// 		"3" => "",
				// 		"4" => "",
				// 		"5" => "",
				// 		"6" => "",
				// 		"7" => "",
				// 		"8" => "",
				// 		"9" => "",
				// 	);
				// }

				// while ($reg = $rspta3->fetch_object()) {
				// 	$cargo_detalle = "";

				// 	switch ($reg->cargo) {
				// 		case 'superadmin':
				// 			$cargo_detalle = "Superadministrador";
				// 			break;
				// 		case 'admin':
				// 			$cargo_detalle = "Administrador";
				// 			break;
				// 		case 'cliente':
				// 			$cargo_detalle = "Cliente";
				// 			break;
				// 		case 'vendedor':
				// 			$cargo_detalle = "Vendedor";
				// 			break;
				// 		case 'almacenero':
				// 			$cargo_detalle = "Almacenero";
				// 			break;
				// 		case 'encargado':
				// 			$cargo_detalle = "Encargado";
				// 			break;
				// 		default:
				// 			break;
				// 	}

				// 	$data[] = array(
				// 		"0" => $reg->fecha,
				// 		"1" => $reg->usuario . ' - ' . $cargo_detalle,
				// 		"2" => '<strong>' . $reg->cliente . '</strong>',
				// 		"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
				// 		"4" => $reg->tipo_comprobante,
				// 		"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
				// 		"6" => $reg->total_venta,
				// 		"7" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
				// 		"8" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
				// 	);

				// 	$totalPrecioVenta3 += $reg->total_venta;
				// }

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					// $data[] = array(
					// 	"0" => "",
					// 	"1" => "",
					// 	"2" => "",
					// 	"3" => "",
					// 	"4" => "",
					// 	"5" => "",
					// 	"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
					// 	"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
					// 	"8" => "",
					// 	"9" => "",
					// );

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2, 2) . '</strong>',
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

			case 'listartodasventasclientes':

				$rspta = $consulta->listartodasventasclientes();
				$rspta2 = $consulta->listartodasventasclientesservicio();
				$rspta3 = $consulta->listartodasventasclientescuotas();
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
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

				// ventas por usuario

			case 'listarventasusuario':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listarventasusuario($fecha_inicio, $fecha_fin, $idusuario);
				$rspta2 = $consulta->listarventasusuarioservicio($fecha_inicio, $fecha_fin, $idusuario);
				$rspta3 = $consulta->listarventasusuariocuotas($fecha_inicio, $fecha_fin, $idusuario);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
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

			case 'listartodasventasusuariofecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodasventasusuariofecha($fecha_inicio, $fecha_fin);
				$rspta2 = $consulta->listartodasventasusuariofechaservicio($fecha_inicio, $fecha_fin);
				$rspta3 = $consulta->listartodasventasusuariofechacuotas($fecha_inicio, $fecha_fin);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
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

			case 'listartodasventasusuario':

				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listartodasventasusuario($idusuario);
				$rspta2 = $consulta->listartodasventasusuarioservicio($idusuario);
				$rspta3 = $consulta->listartodasventasusuariocuotas($idusuario);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
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

			case 'listartodasventasusuariousuarios':

				$rspta = $consulta->listartodasventasusuariousuarios();
				$rspta2 = $consulta->listartodasventasusuariousuariosservicio();
				$rspta3 = $consulta->listartodasventasusuariousuarioscuotas();
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->almacen,
						"4" => $reg->metodo_pago,
						"5" => $reg->tipo_comprobante,
						"6" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"7" => $reg->total_venta,
						"8" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"9" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"8" => "",
						"9" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
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

				// ventas por productos

			case 'listarventasproducto':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listarventas($fecha_inicio, $fecha_fin, $idcliente);
				$rspta2 = $consulta->listarventasservicio($fecha_inicio, $fecha_fin, $idcliente);
				// $rspta3 = $consulta->listarventascuotas($fecha_inicio, $fecha_fin, $idcliente);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => '<strong>' . $reg->cliente . '</strong>',
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal3"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar2(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => '<strong>' . $reg->cliente . '</strong>',
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				// $totalPrecioVenta3 = 0;

				// if (!empty($data)) {
				// 	$data[] = array(
				// 		"0" => "<strong>VENTAS A CRÉDITO</strong>",
				// 		"1" => "",
				// 		"2" => "",
				// 		"3" => "",
				// 		"4" => "",
				// 		"5" => "",
				// 		"6" => "",
				// 		"7" => "",
				// 		"8" => "",
				// 		"9" => "",
				// 		"10" => "",
				// 	);
				// }

				// while ($reg = $rspta3->fetch_object()) {
				// 	$cargo_detalle = "";

				// 	switch ($reg->cargo) {
				// 		case 'superadmin':
				// 			$cargo_detalle = "Superadministrador";
				// 			break;
				// 		case 'admin':
				// 			$cargo_detalle = "Administrador";
				// 			break;
				// 		case 'cliente':
				// 			$cargo_detalle = "Cliente";
				// 			break;
				// 		case 'vendedor':
				// 			$cargo_detalle = "Vendedor";
				// 			break;
				// 		case 'almacenero':
				// 			$cargo_detalle = "Almacenero";
				// 			break;
				// 		case 'encargado':
				// 			$cargo_detalle = "Encargado";
				// 			break;
				// 		default:
				// 			break;
				// 	}

				// 	$data[] = array(
				// 		"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
				// 			'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="3mostrar(' . $reg->idcuotas . ')"><i class="fa fa-eye"></i></button></a>' .
				// 			'</div>',
				// 		"1" => $reg->fecha,
				// 		"2" => $reg->usuario . ' - ' . $cargo_detalle,
				// 		"3" => '<strong>' . $reg->cliente . '</strong>',
				// 		"4" => $reg->fecha,
				// 		"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
				// 		"6" => $reg->tipo_comprobante,
				// 		"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
				// 		"8" => $reg->total_venta,
				// 		"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
				// 		"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
				// 	);

				// 	$totalPrecioVenta3 += $reg->total_venta;
				// }

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					// $data[] = array(
					// 	"0" => "",
					// 	"1" => "",
					// 	"2" => "",
					// 	"3" => "",
					// 	"4" => "",
					// 	"5" => "",
					// 	"6" => "",
					// 	"7" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
					// 	"8" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
					// 	"9" => "",
					// 	"10" => "",
					// );

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
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

			case 'listartodasventasfechaproducto':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodasventasfecha($fecha_inicio, $fecha_fin);
				$rspta2 = $consulta->listartodasventasfechaservicio($fecha_inicio, $fecha_fin);
				$rspta3 = $consulta->listartodasventasfechacuotas($fecha_inicio, $fecha_fin);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal3"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar2(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar3(' . $reg->idcuotas . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->fecha,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
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

			case 'listartodasventasproducto':

				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listartodasventas($idcliente);
				$rspta2 = $consulta->listartodasventasservicio($idcliente);
				// $rspta3 = $consulta->listartodasventascuotas($idcliente);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => '<strong>' . $reg->cliente . '</strong>',
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal3"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar2(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => '<strong>' . $reg->cliente . '</strong>',
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				// $totalPrecioVenta3 = 0;

				// if (!empty($data)) {
				// 	$data[] = array(
				// 		"0" => "<strong>VENTAS A CRÉDITO</strong>",
				// 		"1" => "",
				// 		"2" => "",
				// 		"3" => "",
				// 		"4" => "",
				// 		"5" => "",
				// 		"6" => "",
				// 		"7" => "",
				// 		"8" => "",
				// 		"9" => "",
				// 		"10" => "",
				// 	);
				// }

				// while ($reg = $rspta3->fetch_object()) {
				// 	$cargo_detalle = "";

				// 	switch ($reg->cargo) {
				// 		case 'superadmin':
				// 			$cargo_detalle = "Superadministrador";
				// 			break;
				// 		case 'admin':
				// 			$cargo_detalle = "Administrador";
				// 			break;
				// 		case 'cliente':
				// 			$cargo_detalle = "Cliente";
				// 			break;
				// 		case 'vendedor':
				// 			$cargo_detalle = "Vendedor";
				// 			break;
				// 		case 'almacenero':
				// 			$cargo_detalle = "Almacenero";
				// 			break;
				// 		case 'encargado':
				// 			$cargo_detalle = "Encargado";
				// 			break;
				// 		default:
				// 			break;
				// 	}

				// 	$data[] = array(
				// 		"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
				// 			'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="3mostrar(' . $reg->idcuotas . ')"><i class="fa fa-eye"></i></button></a>' .
				// 			'</div>',
				// 		"1" => $reg->fecha,
				// 		"2" => $reg->usuario . ' - ' . $cargo_detalle,
				// 		"3" => '<strong>' . $reg->cliente . '</strong>',
				// 		"4" => $reg->fecha,
				// 		"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
				// 		"6" => $reg->tipo_comprobante,
				// 		"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
				// 		"8" => $reg->total_venta,
				// 		"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
				// 		"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
				// 	);

				// 	$totalPrecioVenta3 += $reg->total_venta;
				// }

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					// $data[] = array(
					// 	"0" => "",
					// 	"1" => "",
					// 	"2" => "",
					// 	"3" => "",
					// 	"4" => "",
					// 	"5" => "",
					// 	"6" => "",
					// 	"7" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
					// 	"8" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
					// 	"9" => "",
					// 	"10" => "",
					// );

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
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

			case 'listartodasventasclientesproducto':

				$rspta = $consulta->listartodasventasclientes();
				$rspta2 = $consulta->listartodasventasclientesservicio();
				$rspta3 = $consulta->listartodasventasclientescuotas();
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal3"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar2(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar3(' . $reg->idcuotas . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => $reg->usuario . ' - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->fecha,
						"5" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
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

			case 'listarventasusuarioproducto':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listarventasusuario($fecha_inicio, $fecha_fin, $idusuario);
				$rspta2 = $consulta->listarventasusuarioservicio($fecha_inicio, $fecha_fin, $idusuario);
				$rspta3 = $consulta->listarventasusuariocuotas($fecha_inicio, $fecha_fin, $idusuario);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal3"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar2(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar3(' . $reg->idcuotas . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
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

			case 'listartodasventasusuarioproducto':

				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listartodasventasusuario($idusuario);
				$rspta2 = $consulta->listartodasventasusuarioservicio($idusuario);
				$rspta3 = $consulta->listartodasventasusuariocuotas($idusuario);
				$data = array();

				$totalPrecioVenta = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta += $reg->total_venta;
				}

				$totalPrecioVenta2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal3"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar2(' . $reg->idventa_servicio . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioVenta2 += $reg->total_venta;
				}

				$totalPrecioVenta3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px; justify-content: center;">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar3(' . $reg->idcuotas . ')"><i class="fa fa-eye"></i></button></a>' .
							'</div>',
						"1" => $reg->fecha,
						"2" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"3" => $reg->cliente,
						"4" => $reg->almacen,
						"5" => $reg->metodo_pago,
						"6" => $reg->tipo_comprobante,
						"7" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"8" => $reg->total_venta,
						"9" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalPrecioVenta3 += $reg->total_venta;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta2, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "<strong>TOTAL</strong>",
						"8" => '<strong>' . number_format($totalPrecioVenta + $totalPrecioVenta2 + $totalPrecioVenta3, 2) . '</strong>',
						"9" => "",
						"10" => "",
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

				// ventas y productos

			case 'listarventasyproducto':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listarventasyproducto($fecha_inicio, $fecha_fin, $idcliente);
				$rspta2 = $consulta->listarventasyproductoservicio($fecha_inicio, $fecha_fin, $idcliente);
				// $rspta3 = $consulta->listarventasyproductocuotas($fecha_inicio, $fecha_fin, $idcliente);
				$data = array();

				$lastIdVenta = null;
				$firstIteration = true;
				$totalCantidad = 0;
				$totalSubtotal = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa != $lastIdVenta) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => '<strong>' . $reg->cliente . '</strong>',
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad += $reg->cantidad;
					$totalSubtotal += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta = $reg->idventa;
				}

				$lastIdVenta2 = null;
				$firstIteration = true;
				$totalCantidad2 = 0;
				$totalSubtotal2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa_servicio != $lastIdVenta2) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => '<strong>' . $reg->cliente . '</strong>',
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad2 += $reg->cantidad;
					$totalSubtotal2 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta2 = $reg->idventa_servicio;
				}

				// $lastIdVenta3 = null;
				// $firstIteration = true;
				// $totalCantidad3 = 0;
				// $totalSubtotal3 = 0;

				// if (!empty($data)) {
				// 	$data[] = array(
				// 		"0" => "<strong>VENTAS A CRÉDITO</strong>",
				// 		"1" => "",
				// 		"2" => "",
				// 		"3" => "",
				// 		"4" => "",
				// 		"5" => "",
				// 		"6" => "",
				// 		"7" => "",
				// 		"8" => "",
				// 		"9" => "",
				// 		"10" => "",
				// 	);
				// }

				// while ($reg = $rspta3->fetch_object()) {
				// 	$cargo_detalle = "";

				// 	switch ($reg->cargo) {
				// 		case 'superadmin':
				// 			$cargo_detalle = "Superadministrador";
				// 			break;
				// 		case 'admin':
				// 			$cargo_detalle = "Administrador";
				// 			break;
				// 		case 'cliente':
				// 			$cargo_detalle = "Cliente";
				// 			break;
				// 		case 'vendedor':
				// 			$cargo_detalle = "Vendedor";
				// 			break;
				// 		case 'almacenero':
				// 			$cargo_detalle = "Almacenero";
				// 			break;
				// 		case 'encargado':
				// 			$cargo_detalle = "Encargado";
				// 			break;
				// 		default:
				// 			break;
				// 	}

				// 	// Verificar si el idventa actual es diferente al idventa del registro anterior
				// 	// Verificar si es la primera iteración
				// 	if (!$firstIteration && $reg->idcuotas != $lastIdVenta3) {
				// 		// Agregar una fila vacía al array antes de agregar el nuevo registro
				// 		$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
				// 	}

				// 	$data[] = array(
				// 		"0" => $reg->fecha,
				// 		"1" => $reg->tipo_comprobante,
				// 		"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
				// 		"3" => $reg->almacen,
				// 		"4" => $reg->usuario . ' - ' . $cargo_detalle,
				// 		"5" => '<strong>' . $reg->cliente . '</strong>',
				// 		"6" => $reg->nombre,
				// 		"7" => $reg->cantidad,
				// 		"8" => $reg->precio_venta,
				// 		"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
				// 		"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
				// 	);

				// 	$totalCantidad3 += $reg->cantidad;
				// 	$totalSubtotal3 += ($reg->precio_venta * $reg->cantidad);

				// 	$firstIteration = false; // Marcar que ya no es la primera iteración
				// 	$lastIdVenta3 = $reg->idcuotas;
				// }

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . ($totalCantidad) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . ($totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal2, 2) . '</strong>',
						"10" => "",
					);

					// $data[] = array(
					// 	"0" => "",
					// 	"1" => "",
					// 	"2" => "",
					// 	"3" => "",
					// 	"4" => "",
					// 	"5" => "",
					// 	"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
					// 	"7" => '<strong>' . ($totalCantidad3) . '</strong>',
					// 	"8" => "",
					// 	"9" => '<strong>' . number_format($totalSubtotal3, 2) . '</strong>',
					// 	"10" => "",
					// );

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"7" => "<strong>TOTAL</strong>",
						"6" => '<strong>' . ($totalCantidad + $totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal + $totalSubtotal2, 2) . '</strong>',
						"10" => "",
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

			case 'listartodasventasfechayproducto':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodasventasfechayproducto($fecha_inicio, $fecha_fin);
				$rspta2 = $consulta->listartodasventasfechayproductoservicio($fecha_inicio, $fecha_fin);
				$rspta3 = $consulta->listartodasventasfechayproductocuotas($fecha_inicio, $fecha_fin);
				$data = array();

				$lastIdVenta = null;
				$firstIteration = true;
				$totalCantidad = 0;
				$totalSubtotal = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa != $lastIdVenta) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad += $reg->cantidad;
					$totalSubtotal += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta = $reg->idventa;
				}

				$lastIdVenta2 = null;
				$firstIteration = true;
				$totalCantidad2 = 0;
				$totalSubtotal2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa_servicio != $lastIdVenta2) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad2 += $reg->cantidad;
					$totalSubtotal2 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta2 = $reg->idventa_servicio;
				}

				$lastIdVenta3 = null;
				$firstIteration = true;
				$totalCantidad3 = 0;
				$totalSubtotal3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idcuotas != $lastIdVenta3) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalCantidad3 += $reg->cantidad;
					$totalSubtotal3 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta3 = $reg->idcuotas;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . ($totalCantidad) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . ($totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal2, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . ($totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal3, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . ($totalCantidad + $totalCantidad2 + $totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal + $totalSubtotal2 + $totalSubtotal3, 2) . '</strong>',
						"10" => "",
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

			case 'listartodasventasyproducto':

				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listartodasventasyproducto($idcliente);
				$rspta2 = $consulta->listartodasventasyproductoservicio($idcliente);
				// $rspta3 = $consulta->listartodasventasyproductocuotas($idcliente);
				$data = array();

				$lastIdVenta = null;
				$firstIteration = true;
				$totalCantidad = 0;
				$totalSubtotal = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa != $lastIdVenta) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => '<strong>' . $reg->cliente . '</strong>',
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad += $reg->cantidad;
					$totalSubtotal += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta = $reg->idventa;
				}

				$lastIdVenta2 = null;
				$firstIteration = true;
				$totalCantidad2 = 0;
				$totalSubtotal2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa_servicio != $lastIdVenta2) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => '<strong>' . $reg->cliente . '</strong>',
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad2 += $reg->cantidad;
					$totalSubtotal2 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta2 = $reg->idventa_servicio;
				}

				// $lastIdVenta3 = null;
				// $firstIteration = true;
				$totalCantidad3 = 0;
				// $totalSubtotal3 = 0;

				// if (!empty($data)) {
				// 	$data[] = array(
				// 		"0" => "<strong>VENTAS A CRÉDITO</strong>",
				// 		"1" => "",
				// 		"2" => "",
				// 		"3" => "",
				// 		"4" => "",
				// 		"5" => "",
				// 		"6" => "",
				// 		"7" => "",
				// 		"8" => "",
				// 		"9" => "",
				// 		"10" => "",
				// 	);
				// }

				// while ($reg = $rspta3->fetch_object()) {
				// 	$cargo_detalle = "";

				// 	switch ($reg->cargo) {
				// 		case 'superadmin':
				// 			$cargo_detalle = "Superadministrador";
				// 			break;
				// 		case 'admin':
				// 			$cargo_detalle = "Administrador";
				// 			break;
				// 		case 'cliente':
				// 			$cargo_detalle = "Cliente";
				// 			break;
				// 		case 'vendedor':
				// 			$cargo_detalle = "Vendedor";
				// 			break;
				// 		case 'almacenero':
				// 			$cargo_detalle = "Almacenero";
				// 			break;
				// 		case 'encargado':
				// 			$cargo_detalle = "Encargado";
				// 			break;
				// 		default:
				// 			break;
				// 	}

				// 	// Verificar si el idventa actual es diferente al idventa del registro anterior
				// 	// Verificar si es la primera iteración
				// 	if (!$firstIteration && $reg->idcuotas != $lastIdVenta3) {
				// 		// Agregar una fila vacía al array antes de agregar el nuevo registro
				// 		$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
				// 	}

				// 	$data[] = array(
				// 		"0" => $reg->fecha,
				// 		"1" => $reg->tipo_comprobante,
				// 		"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
				// 		"3" => $reg->almacen,
				// 		"4" => $reg->usuario . ' - ' . $cargo_detalle,
				// 		"5" => '<strong>' . $reg->cliente . '</strong>',
				// 		"6" => $reg->nombre,
				// 		"7" => $reg->cantidad,
				// 		"8" => $reg->precio_venta,
				// 		"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
				// 		"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
				// 	);

				// 	$totalCantidad3 += $reg->cantidad;
				// 	$totalSubtotal3 += ($reg->precio_venta * $reg->cantidad);

				// 	$firstIteration = false; // Marcar que ya no es la primera iteración
				// 	$lastIdVenta3 = $reg->idcuotas;
				// }

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . ($totalCantidad) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . ($totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal2, 2) . '</strong>',
						"10" => "",
					);

					// $data[] = array(
					// 	"0" => "",
					// 	"1" => "",
					// 	"2" => "",
					// 	"3" => "",
					// 	"4" => "",
					// 	"5" => "",
					// 	"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
					// 	"7" => '<strong>' . ($totalCantidad3) . '</strong>',
					// 	"8" => ""				// 	"9" => '<strong>' . number_format($totalSubtotal3, 2) . '</strong>',
					// 	"10" => "",
					// );

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . ($totalCantidad + $totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal + $totalSubtotal2, 2) . '</strong>',
						"10" => "",
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

			case 'listartodasventasclientesyproducto':

				$rspta = $consulta->listartodasventasclientesyproducto();
				$rspta2 = $consulta->listartodasventasclientesyproductoservicio();
				$rspta3 = $consulta->listartodasventasclientesyproductocuotas();
				$data = array();

				$lastIdVenta = null;
				$firstIteration = true;
				$totalCantidad = 0;
				$totalSubtotal = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa != $lastIdVenta) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad += $reg->cantidad;
					$totalSubtotal += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta = $reg->idventa;
				}

				$lastIdVenta2 = null;
				$firstIteration = true;
				$totalCantidad2 = 0;
				$totalSubtotal2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa_servicio != $lastIdVenta2) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad2 += $reg->cantidad;
					$totalSubtotal2 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta2 = $reg->idventa_servicio;
				}

				$lastIdVenta3 = null;
				$firstIteration = true;
				$totalCantidad3 = 0;
				$totalSubtotal3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idcuotas != $lastIdVenta3) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => $reg->usuario . ' - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalCantidad3 += $reg->cantidad;
					$totalSubtotal3 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta3 = $reg->idcuotas;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . ($totalCantidad) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . ($totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal2, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . ($totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal3, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . ($totalCantidad + $totalCantidad2 + $totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal + $totalSubtotal2 + $totalSubtotal3, 2) . '</strong>',
						"10" => "",
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

			case 'listarventasusuarioyproducto':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listarventasusuarioyproducto($fecha_inicio, $fecha_fin, $idusuario);
				$rspta2 = $consulta->listarventasusuarioyproductoservicio($fecha_inicio, $fecha_fin, $idusuario);
				$rspta3 = $consulta->listarventasusuarioyproductocuotas($fecha_inicio, $fecha_fin, $idusuario);
				$data = array();

				$lastIdVenta = null;
				$firstIteration = true;
				$totalCantidad = 0;
				$totalSubtotal = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa != $lastIdVenta) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad += $reg->cantidad;
					$totalSubtotal += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta = $reg->idventa;
				}

				$lastIdVenta2 = null;
				$firstIteration = true;
				$totalCantidad2 = 0;
				$totalSubtotal2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa_servicio != $lastIdVenta2) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad2 += $reg->cantidad;
					$totalSubtotal2 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta2 = $reg->idventa_servicio;
				}

				$lastIdVenta3 = null;
				$firstIteration = true;
				$totalCantidad3 = 0;
				$totalSubtotal3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idcuotas != $lastIdVenta3) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalCantidad3 += $reg->cantidad;
					$totalSubtotal3 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta3 = $reg->idcuotas;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . ($totalCantidad) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . ($totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal2, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . ($totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal3, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . ($totalCantidad + $totalCantidad2 + $totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal + $totalSubtotal2 + $totalSubtotal3, 2) . '</strong>',
						"10" => "",
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

			case 'listartodasventasusuarioyproducto':

				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listartodasventasusuarioyproducto($idusuario);
				$rspta2 = $consulta->listartodasventasusuarioyproductoservicio($idusuario);
				$rspta3 = $consulta->listartodasventasusuarioyproductocuotas($idusuario);
				$data = array();

				$lastIdVenta = null;
				$firstIteration = true;
				$totalCantidad = 0;
				$totalSubtotal = 0;
				$hayDatos = true;

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

					if ($hayDatos) {
						$data[] = array(
							"0" => "<strong>VENTAS AL CONTADO</strong>",
							"1" => "",
							"2" => "",
							"3" => "",
							"4" => "",
							"5" => "",
							"6" => "",
							"7" => "",
							"8" => "",
							"9" => "",
							"10" => "",
						);
					}

					$hayDatos = false;

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa != $lastIdVenta) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad += $reg->cantidad;
					$totalSubtotal += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta = $reg->idventa;
				}

				$lastIdVenta2 = null;
				$firstIteration = true;
				$totalCantidad2 = 0;
				$totalSubtotal2 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS DE SERVICIO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta2->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idventa_servicio != $lastIdVenta2) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalCantidad2 += $reg->cantidad;
					$totalSubtotal2 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta2 = $reg->idventa_servicio;
				}

				$lastIdVenta3 = null;
				$firstIteration = true;
				$totalCantidad3 = 0;
				$totalSubtotal3 = 0;

				if (!empty($data)) {
					$data[] = array(
						"0" => "<strong>VENTAS A CRÉDITO</strong>",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "",
					);
				}

				while ($reg = $rspta3->fetch_object()) {
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

					// Verificar si el idventa actual es diferente al idventa del registro anterior
					// Verificar si es la primera iteración
					if (!$firstIteration && $reg->idcuotas != $lastIdVenta3) {
						// Agregar una fila vacía al array antes de agregar el nuevo registro
						$data[] = array_fill(0, 11, ''); // Esto crea una fila vacía con 9 celdas
					}

					$data[] = array(
						"0" => $reg->fecha,
						"1" => $reg->tipo_comprobante,
						"2" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"3" => $reg->almacen,
						"4" => '<strong>' . $reg->usuario . '</strong> - ' . $cargo_detalle,
						"5" => $reg->cliente,
						"6" => $reg->nombre,
						"7" => $reg->cantidad,
						"8" => $reg->precio_venta,
						"9" => number_format($reg->precio_venta * $reg->cantidad, 2),
						"10" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : ''))
					);

					$totalCantidad3 += $reg->cantidad;
					$totalSubtotal3 += ($reg->precio_venta * $reg->cantidad);

					$firstIteration = false; // Marcar que ya no es la primera iteración
					$lastIdVenta3 = $reg->idcuotas;
				}

				if (!empty($data)) {
					$data[] = array_fill(0, 11, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA AL CONTADO</strong>",
						"7" => '<strong>' . ($totalCantidad) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA DE SERVICIO</strong>",
						"7" => '<strong>' . ($totalCantidad2) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal2, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL VENTA A CRÉDITO</strong>",
						"7" => '<strong>' . ($totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal3, 2) . '</strong>',
						"10" => "",
					);

					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"6" => "<strong>TOTAL</strong>",
						"7" => '<strong>' . ($totalCantidad + $totalCantidad2 + $totalCantidad3) . '</strong>',
						"8" => "",
						"9" => '<strong>' . number_format($totalSubtotal + $totalSubtotal2 + $totalSubtotal3, 2) . '</strong>',
						"10" => "",
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

				// artículos más vendidos

			case 'articulosmasvendidos':

				$rspta = $consulta->articulosmasvendidos();
				$data = array();

				while ($reg = $rspta->fetch_object()) {
					$data[] = array(
						"0" => $reg->nombre,
						"1" => $reg->categoria,
						"2" => $reg->almacen,
						"3" => $reg->codigo_producto,
						"4" => $reg->stock,
						"5" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
						"6" => $reg->cantidad,
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

				// artículos más devueltos

			case 'articulosmasdevueltos_tipo1':

				$rspta = $consulta->articulosmasdevueltos_tipo1();
				$data = array();

				while ($reg = $rspta->fetch_object()) {
					$data[] = array(
						"0" => $reg->codigo_producto,
						"1" => $reg->nombre,
						"2" => $reg->categoria,
						"3" => $reg->marca,
						"4" => $reg->almacen,
						"5" => $reg->stock,
						"6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
						"7" => $reg->cantidad,
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

			case 'articulosmasdevueltos_tipo2':

				$rspta = $consulta->articulosmasdevueltos_tipo2();
				$data = array();

				while ($reg = $rspta->fetch_object()) {
					$data[] = array(
						"0" => $reg->codigo_producto,
						"1" => $reg->nombre,
						"2" => $reg->categoria,
						"3" => $reg->marca,
						"4" => $reg->almacen,
						"5" => $reg->stock,
						"6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
						"7" => $reg->cantidad,
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

				// compras

			case 'listarcompras':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idproveedor = $_REQUEST["idproveedor"];

				$rspta = $consulta->listarcompras($fecha_inicio, $fecha_fin, $idproveedor);
				$data = array();

				$firstIteration = true;
				$totalPrecioCompra = 0;

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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => '<strong>' . $reg->proveedor . '</strong>',
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioCompra += $reg->total_compra;
					$firstIteration = false; // Marcar que ya no es la primera iteración
				}

				if (!$firstIteration) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "<strong>TOTAL</strong>",
						"6" => '<strong>' . number_format($totalPrecioCompra, 2) . '</strong>',
						"7" => "",
						"8" => "",
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

			case 'listartodascomprasfecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodascomprasfecha($fecha_inicio, $fecha_fin);
				$data = array();

				$firstIteration = true;
				$totalPrecioCompra = 0;

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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->proveedor,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioCompra += $reg->total_compra;
					$firstIteration = false; // Marcar que ya no es la primera iteración
				}

				if (!$firstIteration) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "<strong>TOTAL</strong>",
						"6" => '<strong>' . number_format($totalPrecioCompra, 2) . '</strong>',
						"7" => "",
						"8" => "",
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

			case 'listartodascompras':

				$idproveedor = $_REQUEST["idproveedor"];

				$rspta = $consulta->listartodascompras($idproveedor);
				$data = array();

				$firstIteration = true;
				$totalPrecioCompra = 0;

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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => '<strong>' . $reg->proveedor . '</strong>',
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioCompra += $reg->total_compra;
					$firstIteration = false; // Marcar que ya no es la primera iteración
				}

				if (!$firstIteration) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "<strong>TOTAL</strong>",
						"6" => '<strong>' . number_format($totalPrecioCompra, 2) . '</strong>',
						"7" => "",
						"8" => "",
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

			case 'listartodascomprasproveedores':

				$rspta = $consulta->listartodascomprasproveedores();
				$data = array();

				$firstIteration = true;
				$totalPrecioCompra = 0;

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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->proveedor,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => ($reg->impuesto == '18.00') ? 'S/. 0.18' : 'S/. 0.00',
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
					);

					$totalPrecioCompra += $reg->total_compra;
					$firstIteration = false; // Marcar que ya no es la primera iteración
				}

				if (!$firstIteration) {
					$data[] = array_fill(0, 10, '');
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "<strong>TOTAL</strong>",
						"6" => '<strong>' . number_format($totalPrecioCompra, 2) . '</strong>',
						"7" => "",
						"8" => "",
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
