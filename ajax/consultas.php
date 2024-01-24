<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start();
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html");
} else {
	if ($_SESSION['consultac'] == 1 || $_SESSION['consultav'] == 1) {
		require_once "../modelos/Consultas.php";

		$consulta = new Consultas();


		switch ($_GET["op"]) {
			case 'comprasfecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->comprasfecha($fecha_inicio, $fecha_fin);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->proveedor,
						"3" => $reg->tipo_comprobante,
						"4" => $reg->serie_comprobante . ' ' . $reg->num_comprobante,
						"5" => $reg->total_compra,
						"6" => $reg->impuesto,
						"7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

				// ventas

			case 'listarventas':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listarventas($fecha_inicio, $fecha_fin, $idcliente);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => "<nav>S/. $reg->total_venta</nav>",
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listarventastotales':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];
				$idcliente = $_GET["idcliente"];

				$rspta = $consulta->listarventas($fecha_inicio, $fecha_fin, $idcliente);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodasventasfecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodasventasfecha($fecha_inicio, $fecha_fin);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => "<nav>S/. $reg->total_venta</nav>",
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodasventasfechatotales':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];

				$rspta = $consulta->listartodasventasfecha($fecha_inicio, $fecha_fin);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodasventas':

				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listartodasventas($idcliente);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => "<nav>S/. $reg->total_venta</nav>",
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodasventastotales':
				$idcliente = $_REQUEST["idcliente"];

				$rspta = $consulta->listartodasventas($idcliente);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodasventasclientes':

				$rspta = $consulta->listartodasventasclientes();
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => "<nav>S/. $reg->total_venta</nav>",
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodasventasclientestotales':

				$rspta = $consulta->listartodasventasclientes();
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

				// ventas por usuario

			case 'listarventasusuario':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];
				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listarventasusuario($fecha_inicio, $fecha_fin, $idusuario);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->metodo_pago,
						"3" => $reg->tipo_comprobante,
						"4" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"5" => "<nav>S/. $reg->total_venta</nav>",
						"6" => $reg->impuesto,
						"7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listarventasusuariototales':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];
				$idusuario = $_GET["idusuario"];

				$rspta = $consulta->listarventasusuario($fecha_inicio, $fecha_fin, $idusuario);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodasventasusuariofecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodasventasusuariofecha($fecha_inicio, $fecha_fin);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->metodo_pago,
						"3" => $reg->tipo_comprobante,
						"4" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"5" => "<nav>S/. $reg->total_venta</nav>",
						"6" => $reg->impuesto,
						"7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodasventasusuariofechatotales':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];

				$rspta = $consulta->listartodasventasusuariofecha($fecha_inicio, $fecha_fin);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodasventasusuario':

				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listartodasventasusuario($idusuario);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->metodo_pago,
						"3" => $reg->tipo_comprobante,
						"4" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"5" => "<nav>S/. $reg->total_venta</nav>",
						"6" => $reg->impuesto,
						"7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodasventasusuariototales':
				$idusuario = $_REQUEST["idusuario"];

				$rspta = $consulta->listartodasventasusuario($idusuario);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodasventasusuariousuarios':

				$rspta = $consulta->listartodasventasusuariousuarios();
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->metodo_pago,
						"3" => $reg->tipo_comprobante,
						"4" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"5" => "<nav>S/. $reg->total_venta</nav>",
						"6" => $reg->impuesto,
						"7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodasventasusuariousuariostotales':

				$rspta = $consulta->listartodasventasusuariousuarios();
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_venta;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
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

			case 'articulosmasdevueltos_tipo3':

				$rspta = $consulta->articulosmasdevueltos_tipo3();
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

			case 'articulosmasdevueltos_tipo4':

				$rspta = $consulta->articulosmasdevueltos_tipo4();
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

			case 'articulosmasdevueltos_tipo5':

				$rspta = $consulta->articulosmasdevueltos_tipo5();
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

			case 'articulosmasdevueltos_tipo6':

				$rspta = $consulta->articulosmasdevueltos_tipo6();
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
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listarcomprastotales':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];
				$idproveedor = $_GET["idproveedor"];

				$rspta = $consulta->listarcompras($fecha_inicio, $fecha_fin, $idproveedor);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_compra;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodascomprasfecha':

				$fecha_inicio = $_REQUEST["fecha_inicio"];
				$fecha_fin = $_REQUEST["fecha_fin"];

				$rspta = $consulta->listartodascomprasfecha($fecha_inicio, $fecha_fin);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->proveedor,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodascomprasfechatotales':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];

				$rspta = $consulta->listartodascomprasfecha($fecha_inicio, $fecha_fin);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_compra;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodascompras':

				$idproveedor = $_REQUEST["idproveedor"];

				$rspta = $consulta->listartodascompras($idproveedor);
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->proveedor,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodascomprastotales':
				$idproveedor = $_REQUEST["idproveedor"];

				$rspta = $consulta->listartodascompras($idproveedor);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_compra;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;

			case 'listartodascomprasproveedores':

				$rspta = $consulta->listartodascomprasproveedores();
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
						"0" => $reg->fecha,
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->proveedor,
						"3" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"4" => $reg->tipo_comprobante,
						"5" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"6" => $reg->total_compra,
						"7" => $reg->impuesto,
						"8" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
							'<span class="label bg-red">Anulado</span>'
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

			case 'listartodascomprasproveedorestotales':

				$rspta = $consulta->listartodascomprasproveedores();
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + $reg->total_compra;
				}
				echo 'S/.' . number_format($total, 2, '.', '');
				break;
		}
		//Fin de las validaciones de acceso
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
