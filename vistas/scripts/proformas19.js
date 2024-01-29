var tabla;
let numProforma = 0; // proforma
let lastNumSerie = ""; // proforma

let lastNumCompV = 0; // venta
let lastNumSerieV = ""; // venta

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

	$('[data-toggle="popover"]').popover();

	//Cargamos los items al select cliente
	$.post("../ajax/proformas.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
		$("#idcliente2").html(r);
		$('#idcliente2').selectpicker('refresh');
	});

	//Cargamos los items al select cliente
	$.post("../ajax/proformas.php?op=selectProducto", function (r) {
		$("#idproducto").html(r);
		$('#idproducto').selectpicker('refresh');
	});

	$.post("../ajax/locales.php?op=selectAlmacen", function (r) {
		console.log(r)
		$("#idalmacen").html(r);
		$('#idalmacen').selectpicker('refresh');
		$("#idalmacen2").html(r);
		$('#idalmacen2').selectpicker('refresh');
		actualizarRUC();
		actualizarRUC2();
	});

	// obtenemos el último número de proforma (PROFORMA)
	$.post("../ajax/proformas.php?op=getLastNumProforma", function (e) {
		console.log("Num de proforma =) => ", e);
		numProforma = generarSiguienteCorrelativo(e);
		$("#num_proforma").val(numProforma)
	});

	// obtenemos la útlima serie (PROFORMA)
	$.post("../ajax/proformas.php?op=getLastSerie", function (e) {
		console.log("Serie de proforma =) => ", e);
		lastNumSerie = e;
		$("#serie_comprobante").val(lastNumSerie);
	});

	// obtenemos el último número de comprobante (VENTA)
	$.post("../ajax/venta.php?op=getLastNumComprobante", function (e) {
		console.log("Num de venta =) => ", e);
		lastNumCompV = generarSiguienteCorrelativo(e);
	});

	// obtenemos la útlima serie (VENTA)
	$.post("../ajax/venta.php?op=getLastSerie", function (e) {
		console.log("Serie de venta =) => ", e);
		lastNumSerieV = e;
	});

	$.post("../ajax/metodo_pago.php?op=selectMetodoPago", function (r) {
		console.log(r);
		$("#idmetodopago").html(r);
		$('#idmetodopago').selectpicker('refresh');
		$("#idmetodopago option:contains('Efectivo')").prop('selected', true);
		$('#idmetodopago').selectpicker('refresh');
		$("#idmetodopago2").html(r);
		$('#idmetodopago2').selectpicker('refresh');
	});

	$('#mProformas').addClass("treeview active");
	$('#lProformas').addClass("active");
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

function actualizarPersonales(idalmacen) {
	return new Promise((resolve, reject) => {
		habilitarPersonales();
		$.post("../ajax/proformas.php?op=listarTodosLocalActivosPorUsuario", { idalmacen: idalmacen }, function (data) {
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
					} else if (idalmacen == 0) {
						select.empty();
						select.html('<option value="">- Seleccione -</option>');
						select.selectpicker('refresh');
						deshabilitarPersonales();
						select.selectpicker('refresh');
					} else {
						select.empty();
						select.html('<option value="">- Seleccione -</option>');
						select.selectpicker('refresh');
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

function generarSiguienteCorrelativo(numeros) {
	numeros = numeros.trim() === "" ? "0000" : numeros;

	const siguienteNumero = parseInt(numeros, 10) + 1;
	const longitud = numeros.length;
	const siguienteCorrelativo = String(siguienteNumero).padStart(longitud, '0');
	return siguienteCorrelativo;
}


//Función limpiar
function limpiar() {
	deshabilitarPersonales();

	$("#idcliente").val($("#idcliente option:first").val());
	$("#idcliente").selectpicker('refresh');
	$("#idalmacen").val($("#idalmacen option:first").val());
	$("#idalmacen").selectpicker('refresh');
	$("#idmetodopago option:contains('Efectivo')").prop('selected', true);
		$('#idmetodopago').selectpicker('refresh');
	$("#idmetodopago2").val($("#idmetodopago2 option:first").val());
	$("#idmetodopago2").selectpicker('refresh');

	$("#idproducto").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val(lastNumSerie);
	$("#num_proforma").val(numProforma);
	$("#impuesto").val("0");
	$("#impuesto").selectpicker('refresh');

	$("#total_venta").val("");
	$("#btnAgregarArt").show();
	$(".filas").remove();
	$("#igv").html("S/. 0.00");
	$("#total").html("S/. 0.00");
	$("#igv2").html("S/. 0.00");
	$("#total2").html("S/. 0.00");

	$("#idcliente2").val($("#idcliente2 option:first").val());
	$("#idcliente2").selectpicker('refresh');
	$("#idalmacen2").val($("#idalmacen2 option:first").val());
	$("#idalmacen2").selectpicker('refresh');

	$("#cliente2").val("");
	$("#serie_comprobante2").val(lastNumSerie);
	$("#num_proforma2").val(numProforma);
	$("#impuesto2").val("0");
	$("#impuesto2").selectpicker('refresh');

	$("#total_venta2").val("");
	$("#btnAgregarArt2").show();
	$("#igv2").html("0");
	$("#total2").html("0");

	//Marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');

	$("#tipo_comprobante2").val("Boleta");
	$("#tipo_comprobante2").selectpicker('refresh');

	$('#myModal2').modal('hide');
	$('#myModal3').modal('hide');
	$("#form_codigo_barra").show();

	$('#tblarticulos button').removeAttr('disabled');
	actualizarRUC();
	actualizarRUC2();
}

//Función cancelarform
function cancelarform() {
	limpiar();
}

//Función Listar
function listar() {
	tabla = $('#tbllistado').dataTable(
		{
			"lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
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
				url: '../ajax/proformas.php?op=listar',
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
			"iDisplayLength": 5,//Paginación
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				$(row).find('td:eq(1)').css({
					"white-space": "nowrap"
				});
			}
		}).DataTable();

	tabla.on('init.dt', function () {
		$('[data-toggle="popover"]').popover();
	});
}


//Función ListarArticulos
function listarArticulos() {
	tabla = $('#tblarticulos').DataTable({
		"aProcessing": true,
		"aServerSide": true,
		"dom": 'Bfrtip',
		"buttons": [],
		"ajax": {
			url: '../ajax/proformas.php?op=listarArticulosVenta',
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
			$('#tblarticulos button[data-idarticulo]').removeAttr('disabled');

			// Obtén los detalles actuales
			var detalles = getDetalles();

			// Itera sobre cada detalle y deshabilita el botón correspondiente
			for (var i = 0; i < detalles.length; i++) {
				var idarticulo = detalles[i].idarticulo;
				$('#tblarticulos button[data-idarticulo="' + idarticulo + '"]').attr('disabled', true);
			}
		}
	});
}

function getDetalles() {
	var detalles = [];
	$("#detalles tbody tr").each(function (index) {
		var detalle = {
			idarticulo: $(this).find("input[name='idarticulo[]']").val(),
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

function guardaryeditar(e) {
	e.preventDefault();
	modificarSubototales();
	var formData = new FormData($("#formulario")[0]);

	$("#btnGuardar2").prop("disabled", true);
	$.ajax({
		url: "../ajax/proformas.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			if (datos == "El número de proforma que ha ingresado ya existe en el local seleccionado." || datos == "Una de las cantidades superan al stock normal del artículo." || datos == "El subtotal de uno de los artículos no puede ser menor a 0.") {
				bootbox.alert(datos);
				$("#btnGuardar2").prop("disabled", false);
				return;
			}
			bootbox.alert(datos);
			limpiar();
			setTimeout(() => {
				location.reload();
			}, 1500);
		}
	});
}

function guardaryeditar2(e) {
	e.preventDefault();
	$("#idcliente2").prop("disabled", false);
	$("#tipo_comprobante2").prop("disabled", false);
	$("#impuesto2").prop("disabled", false);
	$("#idalmacen2").prop("disabled", false);
	var formData = new FormData($("#formulario2")[0]);
	formData.append('lastNumCompV', lastNumCompV);
	formData.append('lastNumSerieV', lastNumSerieV);
	$("#idcliente2").prop("disabled", true);
	$("#tipo_comprobante2").prop("disabled", true);
	$("#impuesto2").prop("disabled", true);
	$("#idalmacen2").prop("disabled", true);

	$("#btnGuardar3").prop("disabled", true);
	$.ajax({
		url: "../ajax/proformas.php?op=guardaryeditar2",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			bootbox.alert(datos);
			limpiar();
			setTimeout(() => {
				location.reload();
			}, 1500);
		}
	});
}

function mostrar(idproforma) {
	$("#form_codigo_barra").hide();
	$("#btnAgregarArt").hide();
	$("#btnGuardar2").hide();

	$("#serie_comprobante").val("");
	$("#num_proforma").val("");

	$.post("../ajax/proformas.php?op=mostrar", { idproforma: idproforma }, function (data, status) {
		console.log(data)
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
			$("#num_proforma").val(data.num_proforma);

			var impuesto = parseInt(data.impuesto);
			$("#impuesto").val(impuesto);
			$("#impuesto").selectpicker('refresh');

			$("#idproforma").val(data.idproforma);
			actualizarRUC();
			actualizarRUC2();

			$.post("../ajax/proformas.php?op=listarDetalle&id=" + idproforma, function (r) {
				$("#detalles").html(r);
				$('[data-toggle="popover"]').popover();
			});
		})
	});

}

function enviar(idproforma) {
	$("#form_codigo_barra").hide();
	$("#btnAgregarArt").hide();

	$("#serie_comprobante2").val("");
	$("#num_proforma2").val("");

	$.post("../ajax/proformas.php?op=mostrar", { idproforma: idproforma }, function (data, status) {
		console.log(data)
		data = JSON.parse(data);

		actualizarPersonales(data.idalmacen).then(() => {
			$("#idcliente2").val(data.idcliente);
			$("#idcliente2").selectpicker('refresh');
			$("#idalmacen2").val(data.idalmacen);
			$("#idalmacen2").selectpicker('refresh');
			$("#idmetodopago2").val(data.idmetodopago);
			$("#idmetodopago2").selectpicker('refresh');
			$("#serie_comprobante2").val(data.serie_comprobante);
			$("#serie_comprobante2").val(data.serie_comprobante);
			$("#tipo_comprobante2").val(data.tipo_comprobante);
			$("#tipo_comprobante2").selectpicker('refresh');
			$("#num_proforma2").val(data.num_proforma);

			var impuesto = parseInt(data.impuesto);
			$("#impuesto2").val(impuesto);
			$("#impuesto2").selectpicker('refresh');

			$("#idproforma2").val(data.idproforma);
			actualizarRUC();
			actualizarRUC2();

			$.post("../ajax/proformas.php?op=listarDetalle2&id=" + idproforma, function (r) {
				$("#detalles2").html(r);
			});
		})
	});

}

//Función para anular registros
function anular(idproforma) {
	bootbox.confirm("¿Está seguro de anular la proforma?", function (result) {
		if (result) {
			$.post("../ajax/proformas.php?op=anular", { idproforma: idproforma }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para activar registros
function activar(idproforma) {
	bootbox.confirm("¿Está seguro de activar la proforma?", function (result) {
		if (result) {
			$.post("../ajax/proformas.php?op=activar", { idproforma: idproforma }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para eliminar los registros
function eliminar(idproforma) {
	bootbox.confirm("¿Estás seguro de eliminar la proforma?", function (result) {
		if (result) {
			$.post("../ajax/proformas.php?op=eliminar", { idproforma: idproforma }, function (e) {
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
$("#btnGuardar2").hide();
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

function agregarDetalle(idarticulo, articulo, precio_venta) {
	var cantidad = 1;
	var descuento = '0.00';

	if (idarticulo != "") {
		var subtotal = cantidad * precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ', ' + idarticulo + ')">X</button></td>' +
			'<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
			'<td><input type="number" name="cantidad[]" id="cantidad[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + cantidad + '"></td>' +
			// '<td><input type="text" name="cantidad[]" onblur="verificar_stock(' + idarticulo + ', \'' + articulo + '\')" id="cantidad[]" value="' + cantidad + '"></td>' +
			'<td><input type="number" step="any" name="precio_venta[]" id="precio_venta[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + (precio_venta == '' ? parseFloat(0).toFixed(2) : precio_venta) + '"></td>' +
			'<td><input type="number" step="any" name="descuento[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="0" required value="' + descuento + '"></td>' +
			'<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
			'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
			'</tr>';
		cont++;
		detalles = detalles + 1;
		$('#detalles').append(fila);
		modificarSubototales();
		evitarCaracteresEspecialesCamposNumericos();
		aplicarRestrictATodosLosInputs();
		console.log("Deshabilito a: " + idarticulo + " =)");
	}
	else {
		alert("Error al ingresar el detalle, revisar los datos del artículo");
	}
}

function verificar_stock(idarticulo, articulo) {
	var cantidad = document.querySelector('#cantidad\\[\\]').value;
	if (cantidad !== '') {
		console.log('El valor del input cantidad es: ' + cantidad);
		console.log('El nombre del artículo es: ' + articulo);
		console.log('El idarticulo que verificaremos es: ' + idarticulo);

		$.post("../ajax/proformas.php?op=verificarStockMinimo&id=" + idarticulo + "&nombre=" + articulo + "&cantidad=" + cantidad, function (data) {
			if (data !== '') {
				bootbox.alert(data);
			}
		});
	} else {
		console.log('El input "cantidad" está vacío');
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
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
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
	var idarticulo = $('#idproducto').val();
	console.log(idarticulo);

	if (idarticulo == "") {
		console.log("no hago nada =)");
		return;
	}

	// Función para verificar si el idarticulo ya existe en el tbody
	const articuloExistente = () => {
		let tabla = document.querySelector("#detalles tbody");
		let inputs = tabla.querySelectorAll('input[name="idarticulo[]"]');
		return Array.from(inputs).some(input => input.value === idarticulo);
	};

	if (articuloExistente()) {
		alert("No puedes agregar el mismo artículo dos veces.");
		// Resetear el valor del select
		$('#idproducto').val($("#idproducto option:first").val());
		$("#idproducto").selectpicker('refresh');
	} else {
		$('#idproducto').prop("disabled", true);
		$.ajax({
			url: '../ajax/proformas.php?op=listarProductos',
			type: 'GET',
			dataType: 'json',
			data: { idarticulo: idarticulo },
			success: function (e) {
				console.log(e);
				$('#idproducto').prop("disabled", false);
				console.log("Envío esto al servidor =>", e[0].idarticulo, e[0].articulo, parseFloat(e[0].precio_venta).toFixed(2));

				// Resetear el valor del select
				$('#idproducto').val($("#idproducto option:first").val());
				$("#idproducto").selectpicker('refresh');

				agregarDetalle(e[0].idarticulo, e[0].articulo, parseFloat(e[0].precio_venta).toFixed(2));

				$('#tblarticulos button[data-idarticulo="' + idarticulo + '"]').attr('disabled', 'disabled');
				console.log("Deshabilito a: " + idarticulo + " =)");
			},
			error: function () {
				alert('Error al obtener los datos del producto.');
			}
		});
	}
}

function evaluar() {
	if (detalles > 0) {
		$("#btnGuardar2").show();
	}
	else {
		$("#btnGuardar2").hide();
		cont = 0;
	}
}

function eliminarDetalle(indice, idarticulo) {
	$("#fila" + indice).remove();
	$('#tblarticulos button[data-idarticulo="' + idarticulo + '"]').removeAttr('disabled');
	console.log("Habilito a: " + idarticulo + " =)");
	calcularTotales();
	detalles = detalles - 1;
	evaluar();
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});