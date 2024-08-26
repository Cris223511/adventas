<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['cuotas'] == 1) {
		require_once "../modelos/Cuotas.php";

		$cuota = new Cuotas();

		$idusuario = $_SESSION["idusuario"];
		$idalmacenSession = $_SESSION["almacen"];
		$cargo = $_SESSION["cargo"];

		$idcuotas = isset($_POST["idcuotas"]) ? limpiarCadena($_POST["idcuotas"]) : "";
		$idmetodopago = isset($_POST["idmetodopago"]) ? limpiarCadena($_POST["idmetodopago"]) : "";
		$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
		$idvendedor = isset($_POST["idvendedor"]) ? limpiarCadena($_POST["idvendedor"]) : "";
		$idzona = isset($_POST["idzona"]) ? limpiarCadena($_POST["idzona"]) : "";
		$idalmacen = isset($_POST["idalmacen"]) ? limpiarCadena($_POST["idalmacen"]) : "";
		$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
		$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
		$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
		$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
		$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

		$idcuotasparam = isset($_POST["idcuotasparam"]) ? limpiarCadena($_POST["idcuotasparam"]) : "";
		$metodo_pago = isset($_POST["metodo_pago"]) ? limpiarCadena($_POST["metodo_pago"]) : "";
		$concepto = isset($_POST["concepto"]) ? limpiarCadena($_POST["concepto"]) : "";
		$monto = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";

		// idcuotas
		// idcliente
		// idvendedor
		// idalmacen
		// idzona
		// tipo_comprobante
		// serie_comprobante
		// num_comprobante
		// impuesto
		// total_venta

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idcuotas)) {
					$numeroExiste = $cuota->verificarNumeroExiste($num_comprobante, $idalmacen);
					if ($numeroExiste) {
						echo "El número correlativo que ha ingresado ya existe en el local seleccionado.";
					} else {
						$rspta = $cuota->insertar($idusuario, $idmetodopago, $idcliente, $idvendedor, $idzona, $idalmacen, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total_venta, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_compra"], $_POST["precio_venta"], $_POST["descuento"]);
						if ($rspta === true) {
							echo "Cuota registrada";
						} else {
							echo $rspta;
						}
					}
				} else {
				}
				break;

			case 'guardarpagos':
				$montoTotal = $_GET['montoTotal'];
				$montoPagado = $_GET['montoPagado'];

				if (empty($idcuotas)) {
					if (($monto + $montoPagado) > $montoTotal) {
						echo "El monto de pago supera al monto total a pagar.";
					} else {
						$rspta = $cuota->insertarpagos($idcuotasparam, $metodo_pago, $concepto, $monto);
						echo $rspta ? "Pago registrado" : "No se pudieron registrar todos los datos del pago.";
					}
				} else {
				}
				break;

			case 'verificarEstado':
				$id = $_GET['id'];
				$montoTotal = $_GET['montoTotal'];
				$montoPagado = $_GET['montoPagado'];

				if ($montoTotal == $montoPagado) {
					$rspta = $cuota->actualizarEstadoPagado($id);
					echo "Felicidades, completaste los pagos :D";
				} else {
					$rspta = $cuota->actualizarEstadoDebe($id);
					echo "Aún debes :)";
				}
				break;

			case 'desactivar':
				$rspta = $cuota->desactivar($idcuotas);
				echo $rspta ? "Cuota anulada" : "Cuota no se puede anular";
				break;

			case 'mostrar':
				$rspta = $cuota->mostrar($idcuotas);
				//Codificar el resultado utilizando json
				echo json_encode($rspta);
				break;

			case 'eliminar':
				$rspta = $cuota->eliminar($idcuotas);
				echo $rspta ? "Cuota eliminada" : "Cuota no se puede eliminar";
				break;

			case 'listarDetalle':
				$id = $_GET['id'];

				$rspta = $cuota->listarDetalle($id);
				$rspta2 = $cuota->mostrar($id);

				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
				<th>Opciones</th>
				<th>Artículo</th>
				<th>Cantidad</th>
				<th>Precio compra</th>
				<th>Precio venta</th>
				<th>Descuento</th>
				<th>Subtotal</th>
			</thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td></td><td>' . $reg->nombre . '</td><td>' . $reg->cantidad . '</td><td>' . "<nav>S/. $reg->precio_compra</nav>" . '</td><td>' . "<nav>S/. $reg->precio_venta</nav>" . '</td><td>' . "<nav>S/. $reg->descuento</nav>" . '</td><td>' . "<nav>S/. $reg->subtotal</nav>" . '</td></tr>';
					$igv = $igv + ($rspta2["impuesto"] == 18 ? ($reg->subtotal) * 0.18 : ($reg->subtotal) * 0);
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
						<th><h4 id="igv">S/.' . number_format($igv, 2, '.', '') . '</h4><input type="hidden" name="total_igv" id="total_igv"></th>
						</tr>
						<tr>
						<th>TOTAL</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="total">S/.' . number_format($rspta2["total_venta"], 2, '.', '') . '</h4><input type="hidden" name="total_venta" id="total_venta"></th>
						</tr>
					</tfoot>';
				break;

			case 'listarDetalleCuota':
				$id = $_GET['id'];

				$rspta = $cuota->listarDetalleCuota($id);
				$rspta2 = $cuota->mostrar($id);

				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
				<th>Código</th>
				<th>Artículo</th>
				<th>Cantidad</th>
				<th>Precio venta</th>
				<th>Descuento</th>
				<th>Subtotal</th>
			</thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td>' . $reg->codigo_producto . '</td><td>' . $reg->nombre . '</td><td>' . $reg->cantidad . '</td><td>' . "<nav>S/. $reg->precio_venta</nav>" . '</td><td>' . "<nav>S/. $reg->descuento</nav>" . '</td><td>' . "<nav>S/. $reg->subtotal</nav>" . '</td></tr>';
					$igv = $igv + ($rspta2["impuesto"] == 18 ? ($reg->subtotal) * 0.18 : ($reg->subtotal) * 0);
				}
				echo '
					<tfoot>
						<tr>
						<th>IGV</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="igv">S/.' . number_format($igv, 2, '.', '') . '</h4><input type="hidden" name="total_igv" id="total_igv"></th>
						</tr>
						<tr>
						<th>TOTAL</th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><h4 id="total">S/.' . number_format($rspta2["total_venta"], 2, '.', '') . '</h4><input type="hidden" name="total_venta" id="total_venta"></th>
						</tr>
					</tfoot>';
				break;

			case 'listarDetallePago':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $cuota->listarDetallePago($id);
				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
				<th>Método de pago</th>
				<th>Concepto</th>
				<th>Fecha de pago</th>
				<th>Estado</th>
				<th>Monto de pago</th>
			</thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td>' . (($reg->metodo_pago == 'Tarjeta') ? 'Tarjeta de crédito' : $reg->metodo_pago) . '</td><td>' . $reg->concepto . '</td><td>' . $reg->fecha_pago . '</td><td><span class="label bg-green">Pagado</span></td><td>' . $reg->monto . '</td>';
					$total = $total + ($reg->monto);
				}
				echo '<tfoot>
				<th>TOTAL</th>
				<th></th>
				<th></th>
				<th></th>
				<th><h4 id="total">S/.' . number_format($total, 2, '.', '') . '</h4><input type="hidden" name="total_venta" id="total_venta"></th>
			</tfoot>';
				break;

			case 'contarDetallePago':
				$id = $_GET['id'];

				$rspta = $cuota->contarDetallePago(($id))->fetch_assoc();
				//Codificar el resultado utilizando json
				echo var_export($rspta['COUNT(*)']);
				break;

			case 'sumaTotalDetallePago':
				$id = $_GET['id'];

				$rspta = $cuota->listarDetallePago($id);
				$total = 0;

				while ($reg = $rspta->fetch_object()) {
					$total = $total + ($reg->monto);
				}

				$rspta = $cuota->actualizarTotalPago($total, $id);

				echo number_format($total, 2, '.', '');
				break;

			case 'listar':
				$fecha_inicio = $_GET["fecha_inicio"];
				$fecha_fin = $_GET["fecha_fin"];

				if ($cargo == "superadmin") {
					if ($fecha_inicio == "" && $fecha_fin == "") {
						$rspta = $cuota->listar();
					} else {
						$rspta = $cuota->listarPorFecha($fecha_inicio, $fecha_fin);
					}
				} else {
					if ($fecha_inicio == "" && $fecha_fin == "") {
						$rspta = $cuota->listarPorUsuario($idalmacenSession);
					} else {
						$rspta = $cuota->listarPorUsuarioFecha($idalmacenSession, $fecha_inicio, $fecha_fin);
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
				$totalMontoPagado = 0;

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
						$url = '../reportes/exTicketCuota.php?id=';
					} else {
						$url = '../reportes/exFacturaCuota.php?id=';
					}

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							'<a data-toggle="modal" href="#myModal2"><button class="btn btn-secondary" style="color: black !important;" onclick="mostrar(' . $reg->idcuotas . ');"><i class="fa fa-eye"></i></button></a>' .
							(($reg->estado == 'Deuda' || $reg->estado == 'Pagado') ?
								(mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" onclick="anular(' . $reg->idcuotas . ')"><i class="fa fa-close"></i></button>')) : ('')) .
							mostrarBoton($reg->cargo, $cargo, $reg->idusuario, '<button class="btn btn-secondary" onclick="eliminar(' . $reg->idcuotas . ')"><i class="fa fa-trash"></i></button>') .
							(($reg->estado == 'Deuda' || $reg->estado == 'Pagado') ?
								('<a target="_blank" href="' . $url . $reg->idcuotas . '"> <button class="btn btn-secondary" style="color: black !important;"><i class="fa fa-file"></i></button></a>') : ('')) .
							'</div>',
						"1" => $reg->usuario . ' - ' . $cargo_detalle,
						"2" => $reg->cliente,
						"3" => $reg->vendedor,
						"4" => $reg->fecha,
						"5" => ($reg->anulado == '00-00-0000 00:00:00') ? 'Sin registrar.' : $reg->anulado,
						"6" => $reg->almacen,
						"7" => $reg->ubicacion . ' - ' . $reg->zona,
						"8" => ($reg->metodo_pago == '') ? 'Sin registrar.' : $reg->metodo_pago,
						"9" => $reg->tipo_comprobante,
						"10" => $reg->serie_comprobante . ' - ' . $reg->num_comprobante,
						"11" => $reg->total_venta,
						"12" => $reg->monto_pagado,
						"13" => ($reg->estado == 'Deuda') ? ('<span class="label bg-red">Deuda</span>') : (($reg->estado == 'Pagado') ? ('<span class="label bg-green">Pagado</span>') : (($reg->estado == 'Anulado') ? ('<span class="label bg-red">Anulado</span>') : '')),
						'14' => ((($reg->estado == 'Deuda' || $reg->estado == 'Pagado') ? ('<div style="text-align: center;"><a href="../vistas/cuotaDetalle.php?id=' . $reg->idcuotas . '"> <button class="btn btn-secondary" style="color: black !important;" id="detalle"><i class="fa fa-sliders"></i></button></a></div>') : ''))
					);

					$totalPrecioVenta += $reg->total_venta;
					$totalMontoPagado += $reg->monto_pagado;
					$firstIteration = false; // Marcar que ya no es la primera iteración
				}

				if (!$firstIteration) {
					$data[] = array(
						"0" => "",
						"1" => "",
						"2" => "",
						"3" => "",
						"4" => "",
						"5" => "",
						"5" => "",
						"6" => "",
						"7" => "",
						"8" => "",
						"9" => "",
						"10" => "<strong>TOTAL</strong>",
						"11" => '<strong>' . number_format($totalPrecioVenta, 2) . '</strong>',
						"12" => '<strong>' . number_format($totalMontoPagado, 2) . '</strong>',
						"13" => "",
						"14" => "",
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

				$rspta = $persona->listarUsuarioCliente();

				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . ' - ' . $reg->cargo . '</option>';
				}
				break;

			case 'selectVendedor':
				require_once "../modelos/Persona.php";
				$persona = new Persona();

				$rspta = $persona->listarUsuarioVendedor();

				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . ' - ' . $reg->cargo . '</option>';
				}
				break;

			case 'selectZona':
				require_once "../modelos/Zonas.php";
				$zonas = new Zonas();

				$rspta = $zonas->listar();

				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idzona . '>' . $reg->ubicacion . ' - ' . $reg->zona . '</option>';
				}
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
						"0" => ($reg->stock != '0') ? '<button class="btn btn-secondary" data-idarticulo="' . $reg->idarticulo . '" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . $reg->nombre . '\',\'' . $reg->stock . '\',\'' . $reg->precio_compra . '\',\'' . $reg->precio_venta . '\'); bloquearPrecios(); disableButton(this);"><span class="fa fa-plus"></span></button>' : '',
						"1" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
						"2" => $reg->nombre,
						"3" => ($reg->medida == '') ? 'Sin registrar.' : $reg->medida,
						"4" => $reg->categoria,
						"5" => $reg->almacen,
						"6" => $reg->marca,
						"7" => $reg->codigo_producto,
						"8" => ($reg->codigo == '') ? 'Sin registrar.' : $reg->codigo,
						"9" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span style="color: orange; font-weight: bold">' . $reg->stock . '</span>' : (($reg->stock != '0') ? '<span>' . $reg->stock . '</span>' : '<span style="color: red; font-weight: bold">' . $reg->stock . '</span>'),
						"10" => $reg->stock_minimo,
						"11" => $reg->precio_compra == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_compra,
						"12" => $reg->precio_venta == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta,
						// "13" => $reg->ganancia == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->ganancia,
						"13" => ($reg->peso == '0.00') ? 'Sin registrar.' : $reg->peso,
						"14" => ($reg->talla == '') ? 'Sin registrar.' : $reg->talla,
						"15" => ($reg->color == '') ? 'Sin registrar.' : $reg->color,
						"16" => ($reg->posicion == '') ? 'Sin registrar.' : $reg->posicion,
						"17" => $reg->usuario . ' - ' . $cargo_detalle,
						"18" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span class="label bg-orange">agotandose</span>' : (($reg->stock != '0') ? '<span class="label bg-green">Disponible</span>' : '<span class="label bg-red">agotado</span>')
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
				$row = mysqli_fetch_assoc($cuota->getLastNumComprobante($idalmacenSession));
				if ($row != null) {
					$last_num_comprobante = $row["last_num_comprobante"];
					echo $last_num_comprobante;
				} else {
					echo $row;
				}
				break;

			case 'getLastSerie':
				$row = mysqli_fetch_assoc($cuota->getLastSerie());
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
		}
		//Fin de las validaciones de acceso
	} else {
		require 'noacceso.php';
	}
}
ob_end_flush();
