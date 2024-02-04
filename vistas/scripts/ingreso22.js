var tabla;
let lastNumComp = 0;
let lastNumSerie = "";

//Función que se ejecuta al inicio
function init() {
	limpiar();
	listar();
	listarArticulos();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/ingreso.php?op=selectProveedor", function (r) {
		$("#idproveedor").html(r);
		$('#idproveedor').selectpicker('refresh');
	});
	// obtenemos el último número de comprobante
	$.post("../ajax/ingreso.php?op=getLastNumComprobante", function (e) {
		console.log(e);
		lastNumComp = generarSiguienteCorrelativo(e);
		$("#num_comprobante").val("");
		$("#num_comprobante").val(lastNumComp);
	});

	$.post("../ajax/locales.php?op=selectAlmacen", function (r) {
		console.log(r)
		$("#idalmacen").html(r);
		$('#idalmacen').selectpicker('refresh');
	});

	// obtenemos la útlima serie
	$.post("../ajax/ingreso.php?op=getLastSerie", function (e) {
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

	$('#mCompras').addClass("treeview active");
	$('#lIngresos').addClass("active");
	actualizarRUC();
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

// function generarSiguienteCorrelativo(correlativoActual) {
// 	const siguienteNumero = Number(correlativoActual) + 1;
// 	const siguienteCorrelativo = siguienteNumero.toString().padStart(4, "0");
// 	return siguienteCorrelativo;
// }

function generarSiguienteCorrelativo(numeros) {
    numeros = numeros.trim() === "" ? "0000" : numeros;

    // Convertir a cadena si es un número
    if (!isNaN(numeros)) {
        numeros = String(numeros);
    }

    const siguienteNumero = parseInt(numeros, 10) + 1;
    const longitud = numeros.length;
    const siguienteCorrelativo = String(siguienteNumero).padStart(longitud, '0');
    return siguienteCorrelativo;
}

//Función limpiar
function limpiar() {
	$("#idproveedor").val($("#idproveedor option:first").val());
	$("#idproveedor").selectpicker('refresh');
	$("#idalmacen").val($("#idalmacen option:first").val());
	$("#idalmacen").selectpicker('refresh');
	$("#idmetodopago option:contains('Efectivo')").prop('selected', true);
	$('#idmetodopago').selectpicker('refresh');
	$("#proveedor").val("");
	$("#serie_comprobante").val(lastNumSerie);
	$("#num_comprobante").val("");
	$("#num_comprobante").val(lastNumComp);
	$("#impuesto").val("0");
	$("#impuesto").selectpicker('refresh');

	$("#total_compra").val("");
	$("#btnAgregarArt").show();
	$(".filas").remove();
	$("#igv").html("S/. 0.00");
	$("#total").html("S/. 0.00");

	//Marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');

	$('#myModal2').modal('hide');

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
				url: '../ajax/ingreso.php?op=listar',
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
			"iDisplayLength": 5,//Paginación
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				$(row).find('td:eq(1)').css({
					"white-space": "nowrap"
				});
			}
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
			"lengthMenu": [5, 10, 25, 75, 100],
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
				url: '../ajax/ingreso.php?op=listar',
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
			"iDisplayLength": 5,
			"order": []
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
			url: '../ajax/ingreso.php?op=listarArticulos',
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

//Función para guardar o editar
function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	modificarSubototales();
	var formData = new FormData($("#formulario")[0]);
	$("#btnGuardar").prop("disabled", true);
	$.ajax({
		url: "../ajax/ingreso.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar").prop("disabled", false);
				return;
			} else if (datos == "El número correlativo que ha ingresado ya existe en el local seleccionado." || datos == "El precio de venta de uno de los artículos no puede ser menor al precio de compra.") {
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

function mostrar(idingreso) {
	$("#btnAgregarArt").hide();

	$.post("../ajax/ingreso.php?op=mostrar", { idingreso: idingreso }, function (data, status) {
		data = JSON.parse(data);
		$("#idproveedor").val(data.idproveedor);
		$("#idproveedor").selectpicker('refresh');
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

		$("#idingreso").val(data.idingreso);
		actualizarRUC();
	});

	$.post("../ajax/ingreso.php?op=listarDetalle&id=" + idingreso, function (r) {
		$("#detalles").html(r);
	});
}

//Función para desactivar registros
function desactivar(idingreso) {
	bootbox.confirm("¿Está seguro de desactivar el ingreso?", function (result) {
		if (result) {
			$.post("../ajax/ingreso.php?op=desactivar", { idingreso: idingreso }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para eliminar los registros
function eliminar(idingreso) {
	bootbox.confirm("¿Estás seguro de eliminar el ingreso?", function (result) {
		if (result) {
			$.post("../ajax/ingreso.php?op=eliminar", { idingreso: idingreso }, function (e) {
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

function agregarDetalle(idarticulo, articulo, precio_compra, precio_venta) {
	var cantidad = 1;

	if (idarticulo != "") {
		var subtotal = cantidad * precio_compra;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td class="nowrap-cell"><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ', ' + idarticulo + ')">X</button></td>' +
			'<td class="nowrap-cell"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
			'<td class="nowrap-cell"><input type="number" name="cantidad[]" id="cantidad[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + cantidad + '"></td>' +
			'<td class="nowrap-cell"><input type="number" step="any" name="precio_compra[]" id="precio_compra[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + (precio_compra == '' ? parseFloat(0).toFixed(2) : precio_compra) + '"></td>' +
			'<td class="nowrap-cell"><input type="number" step="any" name="precio_venta[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + (precio_venta == '' ? parseFloat(0).toFixed(2) : precio_venta) + '"></td>' +
			'<td class="nowrap-cell"><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
			'<td class="nowrap-cell"><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
			'</tr>';
		cont++;
		detalles = detalles + 1;
		$('#detalles').append(fila);
		modificarSubototales();
		evitarCaracteresEspecialesCamposNumericos();
		aplicarRestrictATodosLosInputs();
		// aquí busco el idarticulo del botón para deshabilitarlo y volver a agregarlo.
		console.log("Deshabilito a: " + idarticulo + " =)");
	}
	else {
		alert("Error al ingresar el detalle, revisar los datos del artículo");
	}
}

function modificarSubototales() {
	var cant = document.getElementsByName("cantidad[]");
	var prec = document.getElementsByName("precio_compra[]");
	var sub = document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpP = prec[i];
		var inpS = sub[i];

		inpS.value = inpC.value * inpP.value;
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
	$("#total_compra").val(total.toFixed(2));
	evaluar();
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

function eliminarDetalle(indice, idarticulo) {
	$("#fila" + indice).remove();
	// con esto busco el botón del idarticulo que estoy eliminando de la tabla "#detalles" para habilitarlo nuevamente.
	$('#tblarticulos button[data-idarticulo="' + idarticulo + '"]').removeAttr('disabled');
	console.log("Habilito a: " + idarticulo + " =)");
	calcularTotales();
	detalles = detalles - 1;
	evaluar()
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});