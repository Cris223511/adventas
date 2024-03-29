var tabla;
let lastNumComp = 0;
let lastNumSerie = "";

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	//Cargamos los items al select cliente
	$.post("../ajax/cuotas.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

	//Cargamos los items al select vendedor
	$.post("../ajax/cuotas.php?op=selectVendedor", function (r) {
		$("#idvendedor").html(r);
		$('#idvendedor').selectpicker('refresh');
	});

	//Cargamos los items al select almacen
	$.post("../ajax/locales.php?op=selectAlmacen", function (r) {
		console.log(r)
		$("#idalmacen").html(r);
		$('#idalmacen').selectpicker('refresh');
	});

	//Cargamos los items al select zona
	$.post("../ajax/cuotas.php?op=selectZona", function (r) {
		$("#idzona").html(r);
		$('#idzona').selectpicker('refresh');
	});

	// obtenemos el último número de comprobante
	$.post("../ajax/cuotas.php?op=getLastNumComprobante", function (e) {
		console.log(e);
		lastNumComp = generarSiguienteCorrelativo(e);
		$("#num_comprobante").val("");
		$("#num_comprobante").val(lastNumComp);
	});

	// obtenemos la útlima serie
	$.post("../ajax/cuotas.php?op=getLastSerie", function (e) {
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

	$('#mCuotas').addClass("treeview active");
	$('#lCuotas').addClass("active");
}

// function generarSiguienteCorrelativo(correlativoActual) {
// 	const siguienteNumero = Number(correlativoActual) + 1;
// 	const siguienteCorrelativo = siguienteNumero.toString().padStart(4, "0");
// 	return siguienteCorrelativo;
// }

//Función limpiar
function limpiar() {
	$("#idcliente").val($("#idcliente option:first").val());
	$("#idcliente").selectpicker('refresh');
	$("#idvendedor").val("");
	$('#idvendedor').selectpicker('refresh');
	$("#idalmacen").val("");
	$('#idalmacen').selectpicker('refresh');
	$("#idmetodopago option:contains('Efectivo')").prop('selected', true);
	$('#idmetodopago').selectpicker('refresh');
	$("#idzona").val("");
	$('#idzona').selectpicker('refresh');
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

	$('#tblarticulos button').removeAttr('disabled');
}

//Función mostrar formulario
function mostrarform(flag) {
	//limpiar();
	if (flag) {
		$(".listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btndetalle").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles = 0;
	}
	else {
		$(".listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btndetalle").show();
		$("#num_comprobante").prop('disabled', false);
	}
}

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
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
				url: '../ajax/cuotas.php?op=listar',
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
			"order": []
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
				url: '../ajax/cuotas.php?op=listar',
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
			url: '../ajax/venta.php?op=listarArticulosVenta',
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
	formatearNumero();
	desbloquearPrecios();
	var formData = new FormData($("#formulario")[0]);
	$("#btnGuardar").prop("disabled", true);
	$.ajax({
		url: "../ajax/cuotas.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (datos == "El número correlativo que ha ingresado ya existe en el local seleccionado." || datos == "Una de las cantidades superan al stock normal del artículo." || datos == "El subtotal de uno de los artículos no puede ser menor a 0." || datos == "El precio de venta de uno de los artículos no puede ser menor al precio de compra.") {
				bootbox.alert(datos);
				$("#btnGuardar").prop("disabled", false);
				return;
			} else {
				limpiar();
				bootbox.alert(datos);
				mostrarform(false);
				// listar();
				setTimeout(() => {
					location.reload();
				}, 1500);
			}
		}

	});
}

function mostrar(idcuotas) {
	$("#btnAgregarArt").hide();

	$.post("../ajax/cuotas.php?op=mostrar", { idcuotas: idcuotas }, function (data, status) {
		console.log(data);
		data = JSON.parse(data);
		mostrarform(true);

		console.log(data);

		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#idvendedor").val(data.idvendedor);
		$("#idvendedor").selectpicker('refresh');
		$("#idalmacen").val(data.idalmacen);
		$("#idalmacen").selectpicker('refresh');
		$("#idmetodopago").val(data.idmetodopago);
		$("#idmetodopago").selectpicker('refresh');
		$("#idzona").val(data.idzona);
		$("#idzona").selectpicker('refresh');
		$("#tipo_comprobante").val(data.tipo_comprobante);
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#num_comprobante").val(data.num_comprobante);
		$("#num_comprobante").prop('disabled', true);

		var impuesto = parseInt(data.impuesto);
		$("#impuesto").val(impuesto);
		$("#impuesto").selectpicker('refresh');

		$("#idcuotas").val(data.idcuotas);

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
	});

	$.post("../ajax/cuotas.php?op=listarDetalle&id=" + idcuotas, function (r) {
		$("#detalles").html(r);
		ocultarPrecioCompra();
	});
}

//Función para anular registros
function anular(idcuotas) {
	bootbox.confirm("¿Está seguro de anular la cuota?", function (result) {
		if (result) {
			$.post("../ajax/cuotas.php?op=desactivar", { idcuotas: idcuotas }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para eliminar los registros
function eliminar(idcuotas) {
	bootbox.confirm("¿Estás seguro de eliminar la cuota?", function (result) {
		if (result) {
			$.post("../ajax/cuotas.php?op=eliminar", { idcuotas: idcuotas }, function (e) {
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
	var descuento = '0.00';

	if (idarticulo != "") {
		var subtotal = cantidad * precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td class="nowrap-cell"><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ', ' + idarticulo + ')">X</button></td>' +
			'<td class="nowrap-cell"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
			'<td class="nowrap-cell"><input type="number" name="cantidad[]" id="cantidad[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + cantidad + '"></td>' +
			// '<td class="nowrap-cell"><input type="text" name="cantidad[]" onblur="verificar_stock(' + idarticulo + ', \'' + articulo + '\')" id="cantidad[]" value="' + cantidad + '"></td>' +
			'<td class="nowrap-cell precio_compra"><input type="hidden" step="any" class="precios" name="precio_compra[]" value="' + precio_compra + '"><span> S/. ' + precio_compra + '</span></td>' +
			'<td class="nowrap-cell"><input type="number" step="any" class="precios" name="precio_venta[]" id="precio_venta[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" required value="' + (precio_venta == '' ? parseFloat(0).toFixed(2) : precio_venta) + '"></td>' +
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

		$.post("../ajax/venta.php?op=verificarStockMinimo&id=" + idarticulo + "&nombre=" + articulo + "&cantidad=" + cantidad, function (data) {
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
	$('#tblarticulos button[data-idarticulo="' + idarticulo + '"]').removeAttr('disabled');
	console.log("Habilito a: " + idarticulo + " =)");
	calcularTotales();
	detalles = detalles - 1;
	evaluar()
}

function irDetalle() {
	var boton = document.getElementById('detalle');
	if (boton != null) {
		boton.focus();
	}
	return;
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});