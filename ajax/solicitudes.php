<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['prestamo'] == 1) {
		require_once "../modelos/Solicitudes.php";

		$solicitud = new Solicitud();

		$idencargado = $_SESSION["idusuario"];
		$idalmacenero = $_SESSION["idusuario"];

		$cargo = $_SESSION["cargo"];

		$idsolicitud = isset($_POST["idsolicitud"]) ? limpiarCadena($_POST["idsolicitud"]) : "";
		$codigo_pedido = isset($_POST["codigo_pedido"]) ? limpiarCadena($_POST["codigo_pedido"]) : "";
		$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
		$comentario = isset($_POST["comentario"]) ? limpiarCadena($_POST["comentario"]) : "";
		$empresa = isset($_POST["empresa"]) ? limpiarCadena($_POST["empresa"]) : "";
		$destino = isset($_POST["destino"]) ? limpiarCadena($_POST["destino"]) : "";

		$emisor = isset($_POST["emisor"]) ? limpiarCadena($_POST["emisor"]) : "";
		$receptor = isset($_POST["receptor"]) ? limpiarCadena($_POST["receptor"]) : "";

		switch ($_GET["op"]) {
			case 'guardaryeditar':
				if (empty($idsolicitud)) {
					$codigoPedidoExiste = $solicitud->verificarCodigoPedidoExiste($codigo_pedido);
					if ($codigoPedidoExiste) {
						echo "El número correlativo que ha ingresado ya existe.";
					} else {
						($emisor != "") ? ($idencargado = $emisor) : ($idencargado = $idencargado);
						$rspta = $solicitud->insertar($idencargado, $codigo_pedido, $telefono, $empresa, $destino, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_venta"]);
						echo $rspta ? "Solicitud registrada" : "Una de las cantidades superan a la cantidad o stock del artículo.";
					}
				} else {
				}
				break;

			case 'actualizarSolicitud':
				($receptor != "") ? ($idalmacenero = $receptor) : ($idalmacenero = $idalmacenero);
				$rspta = $solicitud->actualizarSolicitud($idalmacenero, $idsolicitud, $_POST["idarticulo"], $_POST["cantidad_prestada"]);
				echo $rspta ? "Préstamos de artículos actualizados correctamente" : "Una de las cantidades a prestar superan a la cantidad solicitada del artículo.";
				break;

			case 'probarDatos':
				// Recibe los datos enviados por AJAX
				$idalmacenero = $_SESSION["idusuario"];
				$idsolicitud = isset($_POST["idsolicitud"]) ? limpiarCadena($_POST["idsolicitud"]) : "";
				$idarticulo = isset($_POST["idarticulo"]) ? $_POST["idarticulo"] : array();
				$cantidad_prestada = isset($_POST["cantidad_prestada"]) ? $_POST["cantidad_prestada"] : array();

				// Aquí llamarías a tu función del modelo para obtener los datos necesarios
				$datos = $solicitud->obtenerDatosParaPrueba($idalmacenero, $idsolicitud, $idarticulo, $cantidad_prestada);

				// Enviar los datos como un objeto JSON
				echo json_encode($datos);
				break;

			case 'anular':
				$rspta = $solicitud->anular($idsolicitud);
				echo $rspta ? "Solicitud anulada" : "Solicitud no se puede anular";
				break;

			case 'activar':
				$rspta = $solicitud->activar($idsolicitud);
				echo $rspta ? "Solicitud activada" : "Solicitud no se puede activar";
				break;

			case 'rechazar':
				$rspta = $solicitud->rechazar($idsolicitud);
				echo $rspta ? "Solicitud rechazada" : "Solicitud no se puede rechazar";
				break;

			case 'mostrar':
				$rspta = $solicitud->mostrar($idsolicitud);
				echo json_encode($rspta);
				break;

			case 'eliminar':
				$rspta = $solicitud->eliminar($idsolicitud);
				echo $rspta ? "Solicitud eliminada" : "Solicitud no se puede eliminar";
				break;

			case 'guardaryeditarcomentario':
				$rspta = $solicitud->actualizarComentario($idsolicitud, $comentario);
				echo $rspta ? "Comentario registrado" : "Comentario no se puede registrar";
				break;

			case 'mostrarComentario':
				if (($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin') || ($_SESSION['cargo'] == 'almacenero')) {
					$rspta = $solicitud->mostrarComentario($idsolicitud);
					$rspta['script'] = '
						<script>
							$("#comentario").attr("placeholder", "Ingrese un comentario.");
							$("#btnGuardar2").show();
							$("#comentario").prop("disabled", false);
						</script>
					';
					echo json_encode($rspta);
				} else {
					$rspta = $solicitud->mostrarComentario($idsolicitud);
					$rspta['script'] = '<script>$("#comentario").attr("placeholder", "Sin comentarios.");</script>';
					echo json_encode($rspta);
				}
				break;

			case 'listarDetalle':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $solicitud->listarDetalle($id);
				$total = 0;

				$estado = '';

				echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Categoría</th>
                                    <th>Marca</th>
									<th>Local</th>
									<th>Precio venta</th>
                                    <th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad solicitada a prestar." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                                    <th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que el almacenero prestó." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
									<th>Estado</th>
                                </thead>';

				while ($reg = $rspta->fetch_object()) {
					if ($reg->cantidad == $reg->cantidad_prestada) {
						$estado = '<span class="label bg-green">Completado</span>';
					} else {
						$estado = '<span class="label bg-orange">Incompleto</span>';
					}

					echo '<tr class="filas"><td></td><td>' . $reg->nombre . '</td><td>' . (($reg->categoria != "") ? $reg->categoria : "Sin registrar.") . '</td><td>' . (($reg->marca != "") ? $reg->marca : "Sin registrar.") . '</td><td>' . $reg->almacen . '</td><td>' . "<nav>S/. " . number_format($reg->precio_venta, 2) . "</nav>" . '</td><td>' . $reg->cantidad . '</td><td>' . $reg->cantidad_prestada . '</td><td>' . $estado . '</td></tr>';
				}
				break;

			case 'listarDetalle2':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $solicitud->listarDetalle2($id);
				$total = 0;

				$estado = '';

				echo '<thead style="background-color:#A9D0F5">
										<th>Opciones</th>
										<th>Artículo</th>
										<th>Categoría</th>
										<th>Marca</th>
										<th>Local</th>
										<th>Precio venta</th>
										<th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad solicitada a prestar." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
										<th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que el receptor de pedido prestó." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
										<th>Cantidad a Prestar <a href="#" data-toggle="popover" data-placement="top" title="Cantidad a Prestar" data-content="Digita la cantidad que deseas prestar al emisor de pedido (no debe superar la cantidad solicitada)." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
										<th>Estado</th>
									</thead>';

				$iterador = 1;
				while ($reg = $rspta->fetch_object()) {
					if ($reg->cantidad == $reg->cantidad_prestada) {
						$estado = '<span class="label bg-green">Completado</span>';
					} else {
						$estado = '<span class="label bg-orange">Incompleto</span>';
					}

					echo '<tr class="filas"><td></td><td><input type="hidden" name="idarticulo[]" value="' . $reg->idarticulo . '">' . $reg->nombre . '</td><td>' . (($reg->categoria != "") ? $reg->categoria : "Sin registrar.") . '</td><td>' . (($reg->marca != "") ? $reg->marca : "Sin registrar.") . '</td><td>' . $reg->almacen . '</td><td>' . "<nav>S/. " . number_format($reg->precio_venta, 2) . "</nav>" . '</td><td data-cantidadsolicitada="' . $iterador . '">' . $reg->cantidad . '</td><td data-cantidadprestada="' . $iterador . '">' . $reg->cantidad_prestada . '</td><td><input type="number" data-cantidadprestar="' . $iterador . '" name="cantidad_prestada[]" id="cantidad_prestada[]" step="any" value="0" min="0.1" required></td><td>' . $estado . '</td></tr>';
					$iterador = $iterador + 1;
				}
				break;

			case 'listar':
				if (($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero'))
					$rspta = $solicitud->listar();
				else
					$rspta = $solicitud->listarUsuario($idencargado);
				//Vamos a declarar un array
				$data = array();

				$url = '../reportes/exFacturaSolicitud.php?id=';

				while ($reg = $rspta->fetch_object()) {
					$cargo_pedido = "";

					switch ($reg->cargo_pedido) {
						case 'superadmin':
							$cargo_pedido = "Superadministrador";
							break;
						case 'admin':
							$cargo_pedido = "Administrador";
							break;
						case 'cliente':
							$cargo_pedido = "Cliente";
							break;
						case 'vendedor':
							$cargo_pedido = "Vendedor";
							break;
						case 'almacenero':
							$cargo_pedido = "Almacenero";
							break;
						case 'encargado':
							$cargo_pedido = "Encargado";
							break;
						default:
					}

					$cargo_despacho = "";

					switch ($reg->cargo_despacho) {
						case 'superadmin':
							$cargo_despacho = "Superadministrador";
							break;
						case 'admin':
							$cargo_despacho = "Administrador";
							break;
						case 'cliente':
							$cargo_despacho = "Cliente";
							break;
						case 'vendedor':
							$cargo_despacho = "Vendedor";
							break;
						case 'almacenero':
							$cargo_despacho = "Almacenero";
							break;
						case 'encargado':
							$cargo_despacho = "Encargado";
							break;
						default:
					}

					$reg->telefono = ($reg->telefono == '') ? 'Sin registrar' : number_format($reg->telefono, 0, '', ' ');
					$reg->destino = ($reg->destino == '') ? 'Sin registrar' : ($reg->destino);
					$reg->empresa = ($reg->empresa == '') ? 'Sin registrar' : ($reg->empresa);

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							(($reg->estado == 'Recibido') ?
								('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de solicitud" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->idsolicitud . ')"><i class="fa fa-eye"></i></button></a>' .
									(($_SESSION['cargo'] == 'encargado') ? ('<button class="btn btn-secondary" title="Anular solicitud" style="color: black" onclick="anular(' . $reg->idsolicitud . ')"><i class="fa fa-close"></i></button>') : '') .
									(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? ('<button class="btn btn-secondary" title="Rechazar solicitud" style="color: black" onclick="rechazar(' . $reg->idsolicitud . ')"><i class="fa fa-times-circle"></i></button>') : '') .
									(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado') ? ('<button class="btn btn-secondary" title="Eliminar solicitud" style="color: black" onclick="eliminar(' . $reg->idsolicitud . ')"><i class="fa fa-trash"></i></button>') : '') .
									(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal3" title="Enviar comentario" style="color: black"><button class="btn btn-secondary" onclick="mostrarComentario(' . $reg->idsolicitud . ')"><i class="fa fa-commenting"></i></button></a>') : '') .
									(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal4" title="Aceptar solicitud" style="color: black"><button class="btn btn-secondary" onclick="mostrar2(' . $reg->idsolicitud . ')"><i class="fa fa-retweet"></i></button></a>') : ''))
								: (($reg->estado == 'Pendiente') ?
									(('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de solicitud" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->idsolicitud . ')"><i class="fa fa-eye"></i></button></a>' .
										(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? ('<button class="btn btn-secondary" title="Rechazar solicitud" style="color: black" onclick="rechazar(' . $reg->idsolicitud . ')"><i class="fa fa-times-circle"></i></button>') : '') .
										(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal3" title="Enviar comentario" style="color: black"><button class="btn btn-secondary" onclick="mostrarComentario(' . $reg->idsolicitud . ')"><i class="fa fa-commenting"></i></button></a>') : '') .
										(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal4" title="Aceptar solicitud" style="color: black"><button class="btn btn-secondary" onclick="mostrar2(' . $reg->idsolicitud . ')"><i class="fa fa-retweet"></i></button></a>') : '')))
									: (($reg->estado == 'Finalizado' || $reg->estado == 'Rechazado') ?
										('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de solicitud" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->idsolicitud . ')"><i class="fa fa-eye"></i></button></a>' .
											(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal3" title="Enviar comentario" style="color: black"><button class="btn btn-secondary" onclick="mostrarComentario(' . $reg->idsolicitud . ')"><i class="fa fa-commenting"></i></button></a>') : '') .
											(($reg->estado_devolucion == "Finalizado") ? (($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado') ? ('<button class="btn btn-secondary" title="Eliminar solicitud" style="color: black" onclick="eliminar(' . $reg->idsolicitud . ')"><i class="fa fa-trash"></i></button>') : '') : ''))
										: ('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de solicitud" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->idsolicitud . ')"><i class="fa fa-eye"></i></button></a>' .
											(($_SESSION['cargo'] == 'encargado') ? ('<button class="btn btn-secondary" title="Activar solicitud" style="color: black; width: 36px" onclick="activar(' . $reg->idsolicitud . ')"><i class="fa fa-check"></i></button>') : '') .
											(($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado') ? ('<button class="btn btn-secondary" title="Eliminar solicitud" style="color: black" onclick="eliminar(' . $reg->idsolicitud . ')"><i class="fa fa-trash"></i></button>') : '')) . '</div>'))) .
							'<a target="_blank" href="' . $url . $reg->idsolicitud . '"> <button class="btn btn-secondary" style="color: black;"><i class="fa fa-file"></i></button></a>',
						"1" => "N° " . $reg->codigo_pedido,
						"2" => $reg->fecha_hora_pedido,
						"3" => ($reg->fecha_hora_despacho == "01-01-2000 00:00:00") ? "Sin registrar" : $reg->fecha_hora_despacho,
						"4" => ucwords($reg->responsable_pedido) . " - " . $cargo_pedido,
						"5" => ($reg->idalmacenero == 0 || $reg->idalmacenero == "0") ? "Sin registrar" : ucwords($reg->responsable_despacho) . " - " . $cargo_despacho,
						"6" => $reg->empresa,
						"7" => $reg->destino,
						"8" => $reg->telefono,
						"9" => ($reg->estado == 'Recibido') ? (($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? '<span class="label bg-blue">Recibido</span>' : '<span class="label bg-blue">Enviado</span>') : (($reg->estado == 'Pendiente') ? '<span class="label bg-orange">Pendiente</span>' : (($reg->estado == 'Finalizado') ? '<span class="label bg-green">Finalizado</span>' : (($reg->estado == 'Rechazado') ? '<span class="label bg-red">Rechazado</span>' : '<span class="label bg-red">Anulado</span>')))
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

			case 'selectAlmacenero':
				$rspta = $solicitud->listarSelectAlmacenero();
				echo '<option value="">- Sin registrar -</option>';
				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . ' - ' . $reg->cargo . '</option>';
				}
				break;

			case 'listarArticulosSolicitud':
				require_once "../modelos/Articulo.php";
				$articulo = new Articulo();

				$rspta = $articulo->listar();
				//Vamos a declarar un array
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
						"0" => ($reg->stock != '0') ? '<button class="btn btn-secondary" data-idarticulo="' . $reg->idarticulo . '" onclick="agregarDetalle(\'' . (($reg->categoria != "") ? $reg->categoria : "Sin registrar.") . '\',\'' . $reg->almacen . '\',\'' . $reg->precio_venta . '\',\'' . (($reg->marca != "") ? $reg->marca : "Sin registrar.") . '\',\'' . $reg->idarticulo . '\',\'' . $reg->stock . '\',\'' . $reg->nombre . '\'); disableButton(this);"><span class="fa fa-plus"></span></button>' : '',
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
						"13" => $reg->ganancia == '0.00' ? "S/. 0.00" : 'S/. ' . $reg->ganancia,
						"14" => ($reg->peso == '0.00') ? 'Sin registrar.' : $reg->peso,
						"15" => ($reg->talla == '') ? 'Sin registrar.' : $reg->talla,
						"16" => ($reg->color == '') ? 'Sin registrar.' : $reg->color,
						"17" => ($reg->posicion == '') ? 'Sin registrar.' : $reg->posicion,
						"18" => $reg->usuario . ' - ' . $cargo_detalle,
						"19" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span class="label bg-orange">agotandose</span>' : (($reg->stock != '0') ? '<span class="label bg-green">Disponible</span>' : '<span class="label bg-red">agotado</span>')
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

			case 'getLastCodigoPedido':
				$result = $solicitud->getLastCodigoPedido();
				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_assoc($result);
					$codigo_pedido = $row["codigo_pedido"];
				} else {
					$codigo_pedido = 0;
				}
				echo $codigo_pedido;
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
