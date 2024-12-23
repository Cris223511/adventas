var tabla;
let lastNumComp = 0;
let lastNumSerie = "";

function habilitarPersonales() {
	$("#idcliente").prop("disabled", false);
}

function deshabilitarPersonales() {
	$("#idcliente").prop("disabled", true);
	$("#idcliente").empty().append('<option value="0">- Seleccione -</option>');
}

//Función que se ejecuta al inicio
function init() {
	limpiar();
	listar();
	listarArticulos();
	deshabilitarPersonales();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$("#formulario2").on("submit", function (e) {
		guardaryeditar2(e);
	});

	$("#formulario3").on("submit", function (e) {
		guardaryeditar3(e);
	});

	$("#formSunat").on("submit", function (e) {
		buscarSunat(e);
	});

	$('[data-toggle="popover"]').popover();

	//Cargamos los items al select cliente
	$.post("../ajax/ventaServicio.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
		$('#idcliente').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');
	});

	//Cargamos los items al select cliente
	$.post("../ajax/ventaServicio.php?op=selectServicio", function (r) {
		$("#idservicio").html(r);
		$('#idservicio').selectpicker('refresh');
	});

	$.post("../ajax/locales.php?op=selectLocal", function (r) {
		$("#idalmacen").html(r);
		$('#idalmacen').selectpicker('refresh');
		$("#idalmacen2").html(r);
		$('#idalmacen2').selectpicker('refresh');
		$("#idalmacen3").html(r);
		$('#idalmacen3').selectpicker('refresh');
		actualizarRUC();
		actualizarRUC2();
		actualizarRUC3();
	});


	// obtenemos el último número de comprobante
	$.post("../ajax/ventaServicio.php?op=getLastNumComprobante", function (e) {
		console.log(e);
		lastNumComp = generarSiguienteCorrelativo(e);
		$("#num_comprobante").val("");
		$("#num_comprobante").val(lastNumComp);
	});

	// obtenemos la útlima serie
	$.post("../ajax/ventaServicio.php?op=getLastSerie", function (e) {
		console.log(e);
		lastNumSerie = e;
		$("#serie_comprobante").val(lastNumSerie);
	});

	$.post("../ajax/metodo_pago.php?op=selectMetodoPago", function (r) {
		console.log(r);
		$("#idmetodopago").html(r);
		$('#idmetodopago').selectpicker('refresh');
		$("#idmetodopago option:contains('Efectivo')").prop('selected', true);
		$('#idmetodopago').selectpicker('refresh');
	});

	$('#mVentas').addClass("treeview active");
	$('#lVentasServicio').addClass("active");
}

function actualizarRUC() {
	const selectLocal = document.getElementById("idalmacen");
	const localRUCInput = document.getElementById("local_ruc");
	const selectedOption = selectLocal.options[selectLocal.selectedIndex];

	if (selectedOption.value !== "") {
		const localRUC = selectedOption.getAttribute('data-local-ruc');
		localRUCInput.value = localRUC;
	} else {
		localRUCInput.value = "";
	}
}

function actualizarRUC2() {
	const selectLocal = document.getElementById("idalmacen2");
	const localRUCInput = document.getElementById("local_ruc2");
	const selectedOption = selectLocal.options[selectLocal.selectedIndex];

	if (selectedOption.value !== "") {
		const localRUC = selectedOption.getAttribute('data-local-ruc');
		localRUCInput.value = localRUC;
	} else {
		localRUCInput.value = "";
	}
}

function actualizarRUC3() {
	const selectLocal = document.getElementById("idalmacen3");
	const localRUCInput = document.getElementById("local_ruc3");
	const selectedOption = selectLocal.options[selectLocal.selectedIndex];

	if (selectedOption.value !== "") {
		const localRUC = selectedOption.getAttribute('data-local-ruc');
		localRUCInput.value = localRUC;
	} else {
		localRUCInput.value = "";
	}
}

function checkEnter(event) {
	if (event.key === "Enter") {
		if ($('.no-results').is(':visible')) {
			$('#myModal3').modal('show');
			limpiarModalClientes();
			$("#sunat").val("");
			console.log("di enter en idcliente =)");
		}
	}
}

// CLIENTES NUEVOS (POR SUNAT)

function listarClientes() {
	$.post("../ajax/ventaServicio.php?op=selectCliente", function (r) {
		$("#idcliente").empty();
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
		$('#idcliente').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');

		actualizarRUC();
		actualizarRUC2();
	});
}

function limpiarModalClientes() {
	$("#idcliente2").val("");
	$("#nombre").val("");
	$("#tipo_documento").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#descripcion").val("");

	habilitarTodoModalCliente();

	$("#idalmacen2").val($("#idalmacen2 option:first").val());
	$("#idalmacen2").selectpicker('refresh');

	$("#btnSunat").prop("disabled", false);
	$("#btnGuardarCliente").prop("disabled", true);

	actualizarRUC2();
}

function guardaryeditar2(e) {
	e.preventDefault();
	$("#btnGuardarCliente").prop("disabled", true);

	deshabilitarTodoModalCliente();
	var formData = new FormData($("#formulario2")[0]);
	formData.append('tipo_persona', 'Cliente');
	habilitarTodoModalCliente();

	$.ajax({
		url: "../ajax/persona.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardarCliente").prop("disabled", false);
				return;
			} else if (datos == "El número de documento que ha ingresado ya existe.") {
				bootbox.alert(datos);
				$("#btnGuardarCliente").prop("disabled", false);
				return;
			} else {
				bootbox.alert(datos);
				$('#myModal3').modal('hide');
				let idalmacen = $("#idalmacen").val();
				actualizarPersonales(idalmacen);
				limpiarModalClientes();
				$("#sunat").val("");
			}
		}
	});
}

function buscarSunat(e) {
	e.preventDefault();
	var formData = new FormData($("#formSunat")[0]);
	limpiarModalClientes();
	$("#btnSunat").prop("disabled", true);

	$.ajax({
		url: "../ajax/ventaServicio.php?op=consultaSunat",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				limpiarModalClientes();
				return;
			} else if (datos == "DNI no valido" || datos == "RUC no valido") {
				limpiarModalClientes();
				bootbox.confirm({
					message: datos + ", ¿deseas crear un cliente manualmente?",
					buttons: {
						cancel: {
							label: 'Cancelar',
						},
						confirm: {
							label: 'Aceptar',
						}
					},
					callback: function (result) {
						if (result) {
							(datos == "DNI no valido") ? $("#tipo_documento2").val("DNI") : $("#tipo_documento2").val("RUC");

							$("#tipo_documento2").trigger("change");

							let inputValue = $('#sunat').val();
							$("#num_documento2").val(inputValue);

							$('#myModal3').modal('hide');
							$('#myModal4').modal('show');
						}
					}
				});
				$("#btnSunat").prop("disabled", false);
			} else if (datos == "El DNI debe tener 8 caracteres." || datos == "El RUC debe tener 11 caracteres.") {
				bootbox.alert(datos);
				limpiarModalClientes();
				$("#btnSunat").prop("disabled", false);
			} else {
				const obj = JSON.parse(datos);
				console.log(obj);

				if (obj.tipoDocumento == "1") {
					var nombreCompleto = capitalizarTodasLasPalabras(obj.nombres + " " + obj.apellidoPaterno + " " + obj.apellidoMaterno);
					var direccionCompleta = "";
				} else {
					var nombreCompleto = capitalizarTodasLasPalabras(obj.razonSocial);
					var direccionCompleta = capitalizarTodasLasPalabras(obj.provincia + ", " + obj.distrito + ", " + obj.direccion);
				}

				console.log("Nombre completo es =) =>" + nombreCompleto);
				console.log("Direccion completa es =) =>" + direccionCompleta);

				$("#nombre").val(nombreCompleto);
				$("#tipo_documento").val(obj.tipoDocumento == "1" ? "DNI" : "RUC");
				$("#num_documento").val(obj.numeroDocumento);
				$("#direccion").val(direccionCompleta);
				$("#telefono").val(obj.telefono);
				$("#email").val(obj.email);

				// Deshabilitar los campos solo si están vacíos
				$("#nombre").prop("disabled", (obj.hasOwnProperty("nombres") || obj.hasOwnProperty("razonSocial")) && nombreCompleto !== "" ? true : false);
				$("#direccion").prop("disabled", obj.hasOwnProperty("direccion") && direccionCompleta !== "" ? true : false);
				$("#telefono").prop("disabled", obj.hasOwnProperty("telefono") && obj.telefono !== "" ? true : false);
				$("#email").prop("disabled", obj.hasOwnProperty("email") && obj.email !== "" ? true : false);

				$("#idalmacen2").prop("disabled", false);
				$("#descripcion").prop("disabled", false);

				$("#idalmacen2").val($("#idalmacen2 option:first").val());
				$("#idalmacen2").selectpicker('refresh');

				$("#sunat").val("");

				$("#btnSunat").prop("disabled", false);
				$("#btnGuardarCliente").prop("disabled", false);
			}
		}
	});
}

function agregarClienteManual() {
	limpiarModalClientes();
	$("#sunat").val("");
	$('#myModal3').modal('hide');
	$('#myModal4').modal('show');
}

function habilitarTodoModalCliente() {
	$("#tipo_documento").prop("disabled", true);
	$("#num_documento").prop("disabled", true);
	$("#nombre").prop("disabled", true);
	$("#direccion").prop("disabled", true);
	$("#telefono").prop("disabled", true);
	$("#email").prop("disabled", true);
	$("#idalmacen2").prop("disabled", true);
	$("#local_ruc2").prop("disabled", true);
	$("#descripcion").prop("disabled", true);
}

function deshabilitarTodoModalCliente() {
	$("#tipo_documento").prop("disabled", false);
	$("#num_documento").prop("disabled", false);
	$("#nombre").prop("disabled", false);
	$("#direccion").prop("disabled", false);
	$("#telefono").prop("disabled", false);
	$("#email").prop("disabled", false);
	$("#idalmacen2").prop("disabled", false);
	$("#local_ruc2").prop("disabled", false);
	$("#descripcion").prop("disabled", false);
}

// CLIENTES NUEVOS (POR SI NO ENCUENTRA LA SUNAT)

function limpiarModalClientes2() {
	$("#idcliente3").val("");
	$("#nombre2").val("");
	$("#tipo_documento2").val("");
	$("#num_documento2").val("");
	$("#direccion2").val("");
	$("#telefono2").val("");
	$("#email2").val("");
	$("#descripcion2").val("");

	$("#idalmacen3").val($("#idalmacen3 option:first").val());
	$("#idalmacen3").selectpicker('refresh');

	$("#btnGuardarCliente2").prop("disabled", false);

	actualizarRUC3();
}

function guardaryeditar3(e) {
	e.preventDefault();
	$("#btnGuardarCliente2").prop("disabled", true);
	var formData = new FormData($("#formulario3")[0]);
	formData.append('tipo_persona', 'Cliente');

	$.ajax({
		url: "../ajax/persona.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardarCliente2").prop("disabled", false);
				return;
			} else if (datos == "El número de documento que ha ingresado ya existe.") {
				bootbox.alert(datos);
				$("#btnGuardarCliente2").prop("disabled", false);
				return;
			} else {
				bootbox.alert(datos);
				$('#myModal4').modal('hide');
				let idalmacen = $("#idalmacen").val();
				actualizarPersonales(idalmacen);
				limpiarModalClientes2();
			}
		}
	});
}

function actualizarPersonales(idalmacen) {
	return new Promise((resolve, reject) => {
		habilitarPersonales();
		$.post("../ajax/ventaServicio.php?op=listarTodosLocalActivosPorUsuario", { idalmacen: idalmacen }, function (data) {
			console.log(data);
			const obj = JSON.parse(data);
			console.log(obj);

			const selects = {
				"idcliente": $("#idcliente"),
			};

			for (const selectId in selects) {
				const select = selects[selectId];
				const atributo = selectId.replace('id', '');

				if (selects.hasOwnProperty(selectId)) {
					if (obj.hasOwnProperty(atributo)) {
						select.empty();
						select.html('<option value="">- Seleccione -</option>');
						obj[atributo].forEach(function (opcion) {
							select.append('<option value="' + opcion.id + '">' + opcion.nombre + '</option>');
						});
						select.selectpicker('refresh');
						select.closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');
					} else if (idalmacen == 0) {
						select.empty();
						select.html('<option value="">- Seleccione -</option>');
						select.selectpicker('refresh');
						deshabilitarPersonales();
						select.selectpicker('refresh');
						select.closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');
					} else {
						select.empty();
						select.html('<option value="">- Seleccione -</option>');
						select.selectpicker('refresh');
						select.closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');
					}
				}
			}

			resolve();  // Resuelve la promesa una vez completado
		});
	});
}

// function generarSiguienteCorrelativo(correlativoActual) {
// 	const siguienteNumero = Number(correlativoActual) + 1;
// 	const siguienteCorrelativo = siguienteNumero.toString().padStart(4, "0");
// 	return siguienteCorrelativo;
// }

//Función limpiar
function limpiar() {
	deshabilitarPersonales();

	$("#idcliente").val($("#idcliente option:first").val());
	$("#idcliente").selectpicker('refresh');
	$("#idalmacen").val($("#idalmacen option:first").val());
	$("#idalmacen").selectpicker('refresh');
	$("#idmetodopago option:contains('Efectivo')").prop('selected', true);
	$('#idmetodopago').selectpicker('refresh');

	$("#idservicio").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val(lastNumSerie);
	$("#num_comprobante").val("");
	$("#num_comprobante").val(lastNumComp);
	$("#impuesto").val("0");
	$("#impuesto").selectpicker('refresh');

	$("#total_venta").val("");
	$("#btnAgregarArt").show();
	$(".filas").remove();
	$("#igv").html("S/. 0.00");
	$("#total").html("S/. 0.00");

	//Marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');

	$('#myModal2').modal('hide');
	$("#form_codigo_barra").show();

	$('#tblarticulos button').removeAttr('disabled');
	actualizarRUC();
}

//Función cancelarform
function cancelarform() {
	limpiar();
}

//Función Listar
function listar() {
	$("#fecha_inicio").val("");
	$("#fecha_fin").val("");

	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();

	tabla = $('#tbllistado').dataTable(
		{
			"lengthMenu": [15, 25, 50, 100],//mostramos el menú de registros a revisar
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
			],
			"ajax":
			{
				url: '../ajax/ventaServicio.php?op=listar',
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"language": {
				"lengthMenu": "Mostrar : _MENU_ registros",
				"buttons": {
					"copyTitle": "Tabla Copiada",
					"copySuccess": {
						_: '%d líneas copiadas',
						1: '1 línea copiada'
					}
				}
			},
			"bDestroy": true,
			"iDisplayLength": 15,//Paginación
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				$(row).find('td:eq(1)').css({
					"white-space": "nowrap"
				});
			},
			"initComplete": function () {
				agregarBuscadorColumna(this.api(), 5, "Buscar por N° documento.");
			},
		}).DataTable();
}

function buscar() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();

	if (fecha_inicio == "" || fecha_fin == "") {
		alert("Los campos de fecha inicial y fecha final son obligatorios.");
		return;
	} else if (fecha_inicio > fecha_fin) {
		alert("La fecha inicial no puede ser mayor que la fecha final.");
		return;
	}

	tabla = $('#tbllistado').dataTable(
		{
			"lengthMenu": [15, 25, 50, 100],
			"aProcessing": true,
			"aServerSide": true,
			dom: '<Bl<f>rtip>',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
			],
			"ajax":
			{
				url: '../ajax/ventaServicio.php?op=listar',
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"language": {
				"lengthMenu": "Mostrar : _MENU_ registros",
				"buttons": {
					"copyTitle": "Tabla Copiada",
					"copySuccess": {
						_: '%d líneas copiadas',
						1: '1 línea copiada'
					}
				}
			},
			"bDestroy": true,
			"iDisplayLength": 15,
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				$(row).find('td:eq(1)').css({
					"white-space": "nowrap"
				});
			},
			"initComplete": function () {
				agregarBuscadorColumna(this.api(), 5, "Buscar por N° documento.");
			},
		}).DataTable();
}

//Función ListarArticulos
function listarArticulos() {
	tabla = $('#tblarticulos').DataTable({
		"aProcessing": true,
		"aServerSide": true,
		"dom": 'Bfrtip',
		"buttons": [],
		"ajax": {
			url: '../ajax/ventaServicio.php?op=listarArticulosVenta',
			type: "GET",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5,
		"order": [],
		"drawCallback": function (settings) {
			// Vuelve a habilitar los botones de los artículos
			$('#tblarticulos button[data-idservicio]').removeAttr('disabled');

			// Obtén los detalles actuales
			var detalles = getDetalles();

			// Itera sobre cada detalle y deshabilita el botón correspondiente
			for (var i = 0; i < detalles.length; i++) {
				var idservicio = detalles[i].idservicio;
				$('#tblarticulos button[data-idservicio="' + idservicio + '"]').attr('disabled', true);
			}
		}
	});
}

function getDetalles() {
	var detalles = [];
	$("#detalles tbody tr").each(function (index) {
		var detalle = {
			idservicio: $(this).find("input[name='idservicio[]']").val(),
			cantidad: $(this).find("input[name='cantidad[]']").val(),
			precio_venta: $(this).find("input[name='precio_venta[]']").val(),
			descuento: $(this).find("input[name='descuento[]']").val(),
			subtotal: $(this).find("span[name='subtotal']").text(),
		};
		detalles.push(detalle);
	});
	return detalles;
}

function disableButton(button) {
	button.disabled = true;
}

//Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	modificarSubototales();
	formatearNumero();
	desbloquearPrecios();
	var formData = new FormData($("#formulario")[0]);
	$("#btnGuardar").prop("disabled", true);
	$.ajax({
		url: "../ajax/ventaServicio.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar").prop("disabled", false);
				return;
			} else if (datos == "El número correlativo que ha ingresado ya existe en el local seleccionado." || datos == "La venta de servicio no se pudo registrar." || datos == "El subtotal de uno de los artículos no puede ser menor a 0.") {
				bootbox.alert(datos);
				$("#btnGuardar").prop("disabled", false);
				return;
			} else {
				bootbox.alert(datos);
				limpiar();
				setTimeout(() => {
					location.reload();
				}, 1500);
			}
		}
	});
}

function mostrar(idventa_servicio) {
	$("#form_codigo_barra").hide();
	$("#btnAgregarArt").hide();

	$("#serie_comprobante").val("");
	$("#num_proforma").val("");

	$.post("../ajax/ventaServicio.php?op=mostrar", { idventa_servicio: idventa_servicio }, function (data, status) {
		data = JSON.parse(data);

		actualizarPersonales(data.idalmacen).then(() => {
			$("#idcliente").val(data.idcliente);
			$("#idcliente").selectpicker('refresh');
			$("#idalmacen").val(data.idalmacen);
			$("#idalmacen").selectpicker('refresh');
			$("#idmetodopago").val(data.idmetodopago);
			$("#idmetodopago").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante);
			$("#tipo_comprobante").selectpicker('refresh');
			$("#serie_comprobante").val(data.serie_comprobante);
			$("#num_comprobante").val(data.num_comprobante);

			var impuesto = parseInt(data.impuesto);
			$("#impuesto").val(impuesto);
			$("#impuesto").selectpicker('refresh');

			$("#idventa_servicio").val(data.idventa_servicio);
			actualizarRUC();

			$.post("../ajax/ventaServicio.php?op=listarDetalle&id=" + idventa_servicio, function (r) {
				$("#detalles").html(r);
			});
		})
	});

}

//Función para anular registros
function anular(idventa_servicio) {
	bootbox.confirm("¿Está seguro de anular la venta de servicio?", function (result) {
		if (result) {
			$.post("../ajax/ventaServicio.php?op=anular", { idventa_servicio: idventa_servicio }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para eliminar los registros
function eliminar(idventa_servicio) {
	bootbox.confirm("¿Estás seguro de eliminar la venta de servicio?", function (result) {
		if (result) {
			$.post("../ajax/ventaServicio.php?op=eliminar", { idventa_servicio: idventa_servicio }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
	var tipo_comprobante = $("#tipo_comprobante option:selected").text();
	if (tipo_comprobante == 'Factura') {
		$("#impuesto").val(impuesto);
		$("#impuesto").selectpicker('refresh');
	}
	else {
		$("#impuesto").val("0");
		$("#impuesto").selectpicker('refresh');
	}
	modificarSubototales();
}

function agregarDetalle(idservicio, servicio, precio_venta) {
	var cantidad = 1;
	var descuento = '0.00';

	if (idservicio != "") {
		var subtotal = cantidad * precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td class="nowrap-cell"><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ', ' + idservicio + ')">X</button></td>' +
			'<td class="nowrap-cell"><input type="hidden" name="idservicio[]" value="' + idservicio + '">' + servicio + '</td>' +
			'<td class="nowrap-cell"><input type="number" name="cantidad[]" id="cantidad[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="0" required value="' + cantidad + '"></td>' +
			'<td class="nowrap-cell"><input type="number" step="any" class="precios" name="precio_venta[]" id="precio_venta[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="0" required value="' + (precio_venta == '' ? parseFloat(0).toFixed(2) : precio_venta) + '"></td>' +
			'<td class="nowrap-cell"><input type="number" step="any" name="descuento[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="0" required value="' + descuento + '"></td>' +
			'<td class="nowrap-cell"><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
			'<td class="nowrap-cell"><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
			'</tr>';
		cont++;
		detalles = detalles + 1;
		$('#detalles').append(fila);
		modificarSubototales();
		evitarCaracteresEspecialesCamposNumericos();
		aplicarRestrictATodosLosInputs();
		console.log("Deshabilito a: " + idservicio + " =)");
	}
	else {
		alert("Error al ingresar el detalle, revisar los datos del artículo");
	}
}

function modificarSubototales() {
	var cant = document.getElementsByName("cantidad[]");
	var prec = document.getElementsByName("precio_venta[]");
	var desc = document.getElementsByName("descuento[]");
	var sub = document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpP = prec[i];
		var inpD = desc[i];
		var inpS = sub[i];

		inpS.value = (inpC.value * inpP.value) - inpD.value;
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value.toFixed(2);
	}
	calcularTotales();
}

function calcularTotales() {
	var sub = document.getElementsByName("subtotal");
	var total = 0.0;
	var igv = 0.0;

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}

	var impuesto = document.getElementById("impuesto").value;

	igv = impuesto == 18 ? total * 0.18 : total * 0;
	total = impuesto == 18 ? total + (total * 0.18) : total;

	$("#igv").html("S/. " + igv.toFixed(2));
	$("#total").html("S/. " + total.toFixed(2));
	$("#total_venta").val(total.toFixed(2));
	evaluar();
}

function llenarTabla() {
	var idservicio = $('#idservicio').val();
	console.log(idservicio);

	if (idservicio == "") {
		console.log("no hago nada =)");
		return;
	}

	// Función para verificar si el idservicio ya existe en el tbody
	const articuloExistente = () => {
		let tabla = document.querySelector("#detalles tbody");
		let inputs = tabla.querySelectorAll('input[name="idservicio[]"]');
		return Array.from(inputs).some(input => input.value === idservicio);
	};

	if (articuloExistente()) {
		alert("No puedes agregar el mismo artículo dos veces.");
		// Resetear el valor del select
		$('#idservicio').val($("#idservicio option:first").val());
		$("#idservicio").selectpicker('refresh');
	} else {
		$('#idservicio').prop("disabled", true);
		$.ajax({
			url: '../ajax/ventaServicio.php?op=listarServicios',
			type: 'GET',
			dataType: 'json',
			data: { idservicio: idservicio },
			success: function (e) {
				console.log(e);
				$('#idservicio').prop("disabled", false);
				console.log("Envío esto al servidor =>", e[0].idservicio, e[0].servicio, parseFloat(e[0].precio_venta).toFixed(2));

				// Resetear el valor del select
				$('#idservicio').val($("#idservicio option:first").val());
				$("#idservicio").selectpicker('refresh');

				agregarDetalle(e[0].idservicio, e[0].servicio, parseFloat(e[0].precio_venta).toFixed(2));

				$('#tblarticulos button[data-idservicio="' + idservicio + '"]').attr('disabled', 'disabled');
				console.log("Deshabilito a: " + idservicio + " =)");
			},
			error: function () {
				alert('Error al obtener los datos del producto.');
			}
		});
	}
}

function evaluar() {
	if (detalles > 0) {
		$("#btnGuardar").show();
	}
	else {
		$("#btnGuardar").hide();
		cont = 0;
	}
}

function eliminarDetalle(indice, idservicio) {
	$("#fila" + indice).remove();
	$('#tblarticulos button[data-idservicio="' + idservicio + '"]').removeAttr('disabled');
	console.log("Habilito a: " + idservicio + " =)");
	calcularTotales();
	detalles = detalles - 1;
	evaluar()
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});