<?php
ob_start();
if (strlen(session_id()) < 1) {
	session_start(); //Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"])) {
	header("Location: ../vistas/login.html"); //Validamos el acceso solo a los usuarios logueados al sistema.
} else {
	//Validamos el acceso solo al usuario logueado y autorizado.
	if ($_SESSION['devolucion'] == 1) {
		require_once "../modelos/Devoluciones.php";

		$devolucion = new Devolucion();

		$iddevolucion = isset($_POST["iddevolucion"]) ? limpiarCadena($_POST["iddevolucion"]) : "";
		$idalmacenero = isset($_POST["idalmacenero"]) ? limpiarCadena($_POST["idalmacenero"]) : "";
		$idencargado = $_SESSION["idusuario"];
		$codigo_pedido = isset($_POST["codigo_pedido"]) ? limpiarCadena($_POST["codigo_pedido"]) : "";
		$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
		$comentario = isset($_POST["comentario"]) ? limpiarCadena($_POST["comentario"]) : "";
		$empresa = isset($_POST["empresa"]) ? limpiarCadena($_POST["empresa"]) : "";
		$opcion = isset($_POST["opcion"]) ? limpiarCadena($_POST["opcion"]) : "";

		switch ($_GET["op"]) {
			case 'actualizarDevolucion':
				$rspta = $devolucion->actualizarDevolucion($iddevolucion, $_POST["idarticulo"], $_POST["cantidad_devuelta"]);
				echo $rspta ? "Devolución de artículos actualizados correctamente" : "Una de las cantidades a devolver superan a la cantidad prestada del artículo.";
				break;

			case 'actualizarDevolucion2':
				$rspta = $devolucion->actualizarDevolucion2($iddevolucion, $opcion, $_POST["idarticulo"], $_POST["cantidad_devuelta"]);
				echo $rspta ? "Artículos devueltos correctamente" : "Artículos no se pudieron devolver";
				break;

			case 'mostrar':
				$rspta = $devolucion->mostrar($iddevolucion);
				echo json_encode($rspta);
				break;

			case 'guardaryeditarcomentario':
				$rspta = $devolucion->actualizarComentario($iddevolucion, $comentario);
				echo $rspta ? "Comentario registrado" : "Comentario no se puede registrar";
				break;

			case 'mostrarComentario':
				if (($_SESSION['cargo'] == 'superadmin') || ($_SESSION['cargo'] == 'admin') || ($_SESSION['cargo'] == 'almacenero') || ($_SESSION['cargo'] == 'encargado')) {
					$rspta = $devolucion->mostrarComentario($iddevolucion);
					$rspta['script'] = '
						<script>
							$("#comentario").attr("placeholder", "Ingrese un comentario.");
							$("#btnGuardar2").show();
							$("#comentario").prop("disabled", false);
						</script>
					';
					echo json_encode($rspta);
				} else {
					$rspta = $devolucion->mostrarComentario($iddevolucion);
					$rspta['script'] = '<script>$("#comentario").attr("placeholder", "Sin comentarios.");</script>';
					echo json_encode($rspta);
				}
				break;

			case 'listarDetalle':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $devolucion->listarDetalle($id);
				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
									<th>Categoría</th>
									<th>Marca</th>
                                    <th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad que solicitaste prestar al almacenero." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                                    <th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que te prestó el almacenero." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                                    <th>Cantidad Devuelta <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Devuelta" data-content="Es la cantidad que solicitaste devolver al almacén." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                                </thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td></td><td>' . $reg->nombre . '</td><td>' . $reg->categoria . '</td><td>' . $reg->marca . '</td><td>' . $reg->cantidad . '</td><td>' . $reg->cantidad_prestada . '</td><td>' . $reg->cantidad_devuelta . '</td></tr>';
				}
				break;

			case 'listarDetalle2':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $devolucion->listarDetalle2($id);
				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
						<th>Opciones</th>
						<th>Artículo</th>
						<th>Categoría</th>
						<th>Marca</th>
						<th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad que solicitaste prestar al almacenero." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
						<th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que te prestó el almacenero." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
						<th>Cantidad Devuelta <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Devuelta" data-content="Es la cantidad que solicitaste devolver al almacén." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
						<th>Cantidad a Devolver <a href="#" data-toggle="popover" data-placement="top" title="Cantidad a Devolver" data-content="Digita la cantidad que deseas devolver al almacén (no debe superar la cantidad prestada)." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
					</thead>';

				$iterador = 1;
				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td></td><td><input type="hidden" name="idarticulo[]" value="' . $reg->idarticulo . '">' . $reg->nombre . '</td><td>' . $reg->categoria . '</td><td>' . $reg->marca . '</td><td>' . $reg->cantidad . '</td><td data-cantidadprestada="' . $iterador . '">' . $reg->cantidad_prestada . '</td><td data-cantidaddevuelta="' . $iterador . '">' . $reg->cantidad_devuelta . '</td><td><input type="text" data-cantidaddevolver="' . $iterador . '" name="cantidad_devuelta[]" id="cantidad_devuelta[]" oninput="onlyNumbers(this)" onpaste="false" ondrop="false" required></td></tr>';
					$iterador = $iterador + 1;
				}
				break;

			case 'listarDetalle3':
				//Recibimos el idingreso
				$id = $_GET['id'];

				$rspta = $devolucion->listarDetalle2($id);
				$total = 0;
				echo '<thead style="background-color:#A9D0F5">
									<th>Opciones</th>
									<th>Artículo</th>
									<th>Categoría</th>
									<th>Marca</th>
									<th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad que solicitaste prestar al almacenero." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
									<th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que te prestó el almacenero." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
									<th>Cantidad a Devolver <a href="#" data-toggle="popover" data-placement="top" title="Cantidad a Devolver" data-content="Es la cantidad que el encargado solicitó devolver al almacén." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
								</thead>';

				while ($reg = $rspta->fetch_object()) {
					echo '<tr class="filas"><td></td><td><input type="hidden" name="idarticulo[]" value="' . $reg->idarticulo . '">' . $reg->nombre . '</td><td>' . $reg->categoria . '</td><td>' . $reg->marca . '</td><td>' . $reg->cantidad . '</td><td>' . $reg->cantidad_prestada . '</td><td><input type="text" name="cantidad_devuelta[]" id="cantidad_devuelta[]" oninput="onlyNumbers(this)" onpaste="false" ondrop="false" value="' . $reg->cantidad_devuelta . '" disabled></td></td></tr>';
				}
				break;

			case 'listar':
				if (($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero'))
					$rspta = $devolucion->listar();
				else
					$rspta = $devolucion->listarUsuario($idencargado);
				//Vamos a declarar un array
				$data = array();

				$url = '../reportes/exFacturaDevolucion.php?id=';

				while ($reg = $rspta->fetch_object()) {
					$cargo_pedido = "";
					$cargo_despacho = "";

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
							break;
					}

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
							break;
					}

					$data[] = array(
						"0" => '<div style="display: flex; flex-wrap: nowrap; gap: 3px">' .
							(($reg->estado == 'Pendiente') ?
								(('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->iddevolucion . ')"><i class="fa fa-eye"></i></i></button></a>' .
									(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal3" title="Enviar comentario" style="color: black"><button class="btn btn-secondary" onclick="mostrarComentario(' . $reg->iddevolucion . ')"><i class="fa fa-commenting"></i></button></a>') : '') .
									(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado') ? ('<a data-toggle="modal" href="#myModal4" title="Solicitar devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar2(' . $reg->iddevolucion . ')"><i class="fa fa-sign-in"></i></button></a>') : '') .
									'<a target="_blank" href="' . $url . $reg->iddevolucion . '"> <button class="btn btn-secondary" style="color: black"><i class="fa fa-file"></i></button></a>'))
								: (($reg->estado == 'En curso') ?
									('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->iddevolucion . ')"><i class="fa fa-eye"></i></i></button></a>' .
										(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal3" title="Enviar comentario" style="color: black"><button class="btn btn-secondary" onclick="mostrarComentario(' . $reg->iddevolucion . ')"><i class="fa fa-commenting"></i></button></a>') : '') .
										(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado') ? ('<a data-toggle="modal" href="#myModal4" title="Solicitar devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar2(' . $reg->iddevolucion . ')"><i class="fa fa-sign-in"></i></button></a>') : '') .
										(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal5" title="Aceptar devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar3(' . $reg->iddevolucion . ')"><i class="fa fa-truck"></i></button></a>') : '') .
										'<a target="_blank" href="' . $url . $reg->iddevolucion . '"> <button class="btn btn-secondary" style="color: black"><i class="fa fa-file"></i></button></a>')
									: (($reg->estado == 'Finalizado') ?
										('<a data-toggle="modal" href="#myModal2" title="Mirar detalles de devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->iddevolucion . ')"><i class="fa fa-eye"></i></i></button></a>' .
											(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal3" title="Enviar comentario" style="color: black"><button class="btn btn-secondary" onclick="mostrarComentario(' . $reg->iddevolucion . ')"><i class="fa fa-commenting"></i></button></a>') : '') .
											(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'almacenero') ? ('<a data-toggle="modal" href="#myModal5" title="Aceptar devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar3(' . $reg->iddevolucion . ')"><i class="fa fa-truck"></i></button></a>') : '') .
											(($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado') ?  '<a target="_blank" href="' . $url . $reg->iddevolucion . '"> <button class="btn btn-secondary" style="color: black"><i class="fa fa-file"></i></button></a>' : ''))
										: '<a data-toggle="modal" href="#myModal2" title="Mirar detalles de devolución" style="color: black"><button class="btn btn-secondary" onclick="mostrar(' . $reg->iddevolucion . ')"><i class="fa fa-eye"></i></i></button></a>')) . '</div>'),
						"1" => "N° " . $reg->codigo_pedido,
						"2" => $reg->fecha_hora_pedido,
						"3" => ($reg->fecha_hora_devolucion == "01-01-2000 00:00:00") ? "Sin registrar" : $reg->fecha_hora_devolucion,
						"4" => ucwords($reg->responsable_pedido) . " - " . $cargo_pedido,
						"5" => ucwords($reg->responsable_despacho) . " - " . $cargo_despacho,
						"6" => $reg->empresa,
						"7" => $reg->telefono,
						"8" => ($reg->estado == 'Pendiente') ? '<span class="label bg-blue">Pendiente</span>' : (($reg->estado == 'Finalizado') ? ('<span class="label bg-green">Finalizado</span>') : ((($reg->estado == 'En curso') ? '<span class="label bg-orange">En curso</span>' : '<span class="label bg-red">Rechazado</span>')))
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
				$rspta = $devolucion->listarSelectAlmacenero();
				echo '<option value="">- Sin registrar -</option>';
				while ($reg = $rspta->fetch_object()) {
					echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . ' - ' . $reg->cargo . '</option>';
				}
				break;

			case 'listarArticulosDevolucion':
				require_once "../modelos/Articulo.php";
				$articulo = new Articulo();

				$rspta = $articulo->listarActivosVenta();
				//Vamos a declarar un array
				$data = array();

				while ($reg = $rspta->fetch_object()) {
					$data[] = array(
						"0" => ($reg->stock != '0') ? '<button class="btn btn-secondary" data-idarticulo="' . $reg->idarticulo . '" onclick="agregarDetalle(' . $reg->idarticulo . ',\'' . $reg->nombre . '\'); disableButton(this);"><span class="fa fa-plus"></span></button>' : '',
						"1" => $reg->nombre,
						"2" => $reg->categoria,
						"3" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span style="color: orange; font-weight: bold">' . $reg->stock . '</span>' : (($reg->stock != '0') ? '<span>' . $reg->stock . '</span>' : '<span style="color: red; font-weight: bold">' . $reg->stock . '</span>'),
						"4" => $reg->stock_minimo,
						"5" => $reg->precio_venta == '' ? "S/. 0.00" : 'S/. ' . $reg->precio_venta,
						"6" => "<img src='../files/articulos/" . $reg->imagen . "' height='50px' width='50px' >",
						"7" => ($reg->stock > 0 && $reg->stock < $reg->stock_minimo) ? '<span class="label bg-orange">agotandose</span>' : (($reg->stock != '0') ? '<span class="label bg-green">Disponible</span>' : '<span class="label bg-red">agotado</span>')
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
