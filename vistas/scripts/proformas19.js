var tabla;
let numProforma = 0; // proforma
let lastNumSerie = ""; // proforma

let lastNumCompV = 0; // venta
let lastNumSerieV = ""; // venta


// Nombres de las columnas a ocultar
var columnasAocultar = [
	"Precio compra",
	"PRECIO DE COMPRA",
	"precio de compra",
	"PRECIO COMPRA",
	"Precio compra",
	"precio compra",
	"Ganancia",
	"GANANCIA",
	"ganancia"
];

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

	$("#formulario4").on("submit", function (e) {
		guardaryeditar4(e);
	})

	$("#formulario5").on("submit", function (e) {
		guardaryeditar5(e);
	})

	$('[data-toggle="popover"]').popover();

	//Cargamos los items al select cliente
	$.post("../ajax/proformas.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
		$('#idcliente').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');

		$("#idcliente2").html(r);
		$('#idcliente2').selectpicker('refresh');
		$("#idcliente3").html(r);
		$('#idcliente3').selectpicker('refresh');
	});

	//Cargamos los items al select cliente
	$.post("../ajax/proformas.php?op=selectProducto", function (r) {
		$("#idproducto").html(r);
		$('#idproducto').selectpicker('refresh');
	});

	// obtenemos el último número de proforma (PROFORMA)
	$.post("../ajax/proformas.php?op=getLastNumProforma", function (e) {
		console.log("Num de proforma =) => ", e);
		numProforma = generarSiguienteCorrelativo(e);
		$("#num_proforma").val("")
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

	ocultarColumnasPorNombre("detalles", columnasAocultar);
	ocultarColumnasPorNombre("tblarticulos", columnasAocultar);
}

// modal artículos

$("#btnDetalles1").show();
$("#btnDetalles2").hide();
$("#frmDetalles").hide();

$(".btn1").show();
$(".btn2").hide();

$.post("../ajax/articulo.php?op=listarTodosActivos", function (data) {
	// console.log(data)
	const obj = JSON.parse(data);
	console.log(obj);

	const selects = {
		"idmarcas": $("#idmarcas"),
		"idcategoria": $("#idcategoria"),
		"idalmacen": $("#idalmacen, #idalmacen3, #idalmacen4, #idalmacen5, #idalmacen6"),
		"idmedida": $("#idmedida"),
	};

	for (const selectId in selects) {
		if (selects.hasOwnProperty(selectId)) {
			const select = selects[selectId];
			const atributo = selectId.replace('id', '');

			if (obj.hasOwnProperty(atributo)) {
				select.empty();
				select.html('<option value="">- Seleccione -</option>');
				obj[atributo].forEach(function (opcion) {
					if (atributo != "almacen") {
						select.append('<option value="' + opcion.id + '">' + opcion.nombre + '</option>');
					} else {
						select.append('<option value="' + opcion.id + '" data-local-ruc="' + opcion.ruc + '">' + opcion.nombre + '</option>');
					}
				});
				select.selectpicker('refresh');
			}
		}
	}

	$("#idalmacen").val($("#idalmacen option:first").val());
	$("#idalmacen").selectpicker('refresh');

	$("#idalmacen3").val($("#idalmacen3 option:first").val());
	$("#idalmacen3").selectpicker('refresh');

	$("#idalmacen4").val($("#idalmacen4 option:first").val());
	$("#idalmacen4").selectpicker('refresh');

	$("#idalmacen5").val($("#idalmacen5 option:first").val());
	$("#idalmacen5").selectpicker('refresh');

	$("#idalmacen6").val($("#idalmacen6 option:first").val());
	$("#idalmacen6").selectpicker('refresh');

	$('#idcategoria').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregarCategoria(event)');
	$('#idcategoria').closest('.form-group').find('input[type="text"]').attr('maxlength', '40');

	$('#idmarcas').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregarMarca(event)');
	$('#idmarcas').closest('.form-group').find('input[type="text"]').attr('maxlength', '40');

	$('#idmedida').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregarMedida(event)');
	$('#idmedida').closest('.form-group').find('input[type="text"]').attr('maxlength', '40');

	actualizarRUC();
	actualizarRUC3();
	actualizarRUC4();
	actualizarRUC5();
	actualizarRUC6();
});

$("#imagenmuestra").hide();

function listarTodosActivos(selectId) {
	$.post("../ajax/articulo.php?op=listarTodosActivos", function (data) {
		const obj = JSON.parse(data);

		const select = $("#" + selectId);
		const atributo = selectId.replace('id', '');

		if (obj.hasOwnProperty(atributo)) {
			select.empty();
			select.html('<option value="">- Seleccione -</option>');
			obj[atributo].forEach(function (opcion) {
				if (atributo !== "almacen") {
					select.append('<option value="' + opcion.id + '">' + opcion.nombre + '</option>');
				}
			});
			select.selectpicker('refresh');
		}

		select.closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregar' + atributo.charAt(0).toUpperCase() + atributo.slice(1) + '(event)');
		select.closest('.form-group').find('input[type="text"]').attr('maxlength', '40');
		$("#" + selectId + ' option:last').prop("selected", true);
		select.selectpicker('refresh');
		select.selectpicker('toggle');
	});
}

function agregarCategoria(e) {
	let inputValue = $('#idcategoria').closest('.form-group').find('input[type="text"]');

	if (e.key === "Enter") {
		if ($('.no-results').is(':visible')) {
			e.preventDefault();
			$("#nombre2").val(inputValue.val());

			var formData = new FormData($("#formularioCategoria")[0]);

			$.ajax({
				url: "../ajax/categoria.php?op=guardaryeditar",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,

				success: function (datos) {
					datos = limpiarCadena(datos);
					if (!datos) {
						console.log("No se recibieron datos del servidor.");
						return;
					} else if (datos == "El nombre de la categoría que ha ingresado ya existe.") {
						bootbox.alert(datos);
						return;
					} else {
						// bootbox.alert(datos);
						listarTodosActivos("idcategoria");
						$("#idcategoria2").val("");
						$("#nombre2").val("");
						$("#descripcion2").val("");
					}
				}
			});
		}
	}
}

function agregarMarca(e) {
	let inputValue = $('#idmarcas').closest('.form-group').find('input[type="text"]');

	if (e.key === "Enter") {
		if ($('.no-results').is(':visible')) {
			e.preventDefault();
			$("#nombre3").val(inputValue.val());

			var formData = new FormData($("#formularioMarcas")[0]);

			$.ajax({
				url: "../ajax/marcas.php?op=guardaryeditar",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,

				success: function (datos) {
					datos = limpiarCadena(datos);
					if (!datos) {
						console.log("No se recibieron datos del servidor.");
						return;
					} else if (datos == "El nombre de la marca que ha ingresado ya existe.") {
						bootbox.alert(datos);
						return;
					} else {
						// bootbox.alert(datos);
						listarTodosActivos("idmarcas");
						$("#idmarcas3").val("");
						$("#nombre3").val("");
						$("#descripcion3").val("");
					}
				}
			});
		}
	}
}

function agregarMedida(e) {
	let inputValue = $('#idmedida').closest('.form-group').find('input[type="text"]');

	if (e.key === "Enter") {
		if ($('.no-results').is(':visible')) {
			e.preventDefault();
			$("#nombre5").val(inputValue.val());

			var formData = new FormData($("#formularioMedidas")[0]);

			$.ajax({
				url: "../ajax/medidas.php?op=guardaryeditar",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,

				success: function (datos) {
					datos = limpiarCadena(datos);
					if (!datos) {
						console.log("No se recibieron datos del servidor.");
						return;
					} else if (datos == "El nombre de la medida que ha ingresado ya existe.") {
						bootbox.alert(datos);
						return;
					} else {
						// bootbox.alert(datos);
						listarTodosActivos("idmedida");
						$("#idmedida4").val("");
						$("#nombre5").val("");
						$("#descripcion4").val("");
					}
				}
			});
		}
	}
}

function changeGanancia() {
	let precio_compra = parseFloat($("#precio_compra").val()) || 0;
	let precio_venta = parseFloat($("#precio_venta").val()) || 0;

	if (precio_venta === 0) {
		$("#ganancia").val("0.00");
		return;
	}

	if (precio_venta > 0 && precio_compra >= 0) {
		let ganancia = precio_venta - precio_compra;
		$("#ganancia").val(ganancia.toFixed(2));
	} else {
		$("#ganancia").val("0.00");
	}
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

function actualizarRUC4() {
	const selectLocal = document.getElementById("idalmacen4");
	const localRUCInput = document.getElementById("local_ruc4");
	const selectedOption = selectLocal.options[selectLocal.selectedIndex];

	if (selectedOption.value !== "") {
		const localRUC = selectedOption.getAttribute('data-local-ruc');
		localRUCInput.value = localRUC;
	} else {
		localRUCInput.value = "";
	}
}

function actualizarRUC5() {
	const selectLocal = document.getElementById("idalmacen5");
	const localRUCInput = document.getElementById("local_ruc5");
	const selectedOption = selectLocal.options[selectLocal.selectedIndex];

	if (selectedOption.value !== "") {
		const localRUC = selectedOption.getAttribute('data-local-ruc');
		localRUCInput.value = localRUC;
	} else {
		localRUCInput.value = "";
	}
}

function actualizarRUC6() {
	const selectLocal = document.getElementById("idalmacen6");
	const localRUCInput = document.getElementById("local_ruc6");
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
			$('#myModal5').modal('show');
			limpiarModalClientes();
			$("#sunat").val("");
			console.log("di enter en idcliente =)");
		}
	}
}

// CLIENTES NUEVOS (POR SUNAT)

function listarClientes() {
	$.post("../ajax/proformas.php?op=selectCliente", function (r) {
		$("#idcliente").empty();
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
		$('#idcliente').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'checkEnter(event)');

		actualizarRUC4();
		actualizarRUC5();
		actualizarRUC6();
	});
}

function limpiarModalClientes() {
	$("#idcliente3").val("");
	$("#nombre").val("");
	$("#tipo_documento").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#descripcion").val("");

	habilitarTodoModalCliente();

	$("#idalmacen4").val($("#idalmacen4 option:first").val());
	$("#idalmacen4").selectpicker('refresh');

	$("#btnSunat").prop("disabled", false);
	$("#btnGuardarCliente").prop("disabled", true);

	actualizarRUC5();
	actualizarRUC6();
}

function guardaryeditar4(e) {
	e.preventDefault();
	$("#btnGuardarCliente").prop("disabled", true);

	deshabilitarTodoModalCliente();
	var formData = new FormData($("#formulario4")[0]);
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
				$('#myModal6').modal('hide');
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
		url: "../ajax/proformas.php?op=consultaSunat",
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

							$('#myModal5').modal('hide');
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

				$("#idalmacen4").prop("disabled", false);
				$("#descripcion").prop("disabled", false);

				$("#idalmacen4").val($("#idalmacen4 option:first").val());
				$("#idalmacen4").selectpicker('refresh');

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
	$('#myModal5').modal('hide');
	$('#myModal4').modal('show');
}

function habilitarTodoModalCliente() {
	$("#tipo_documento").prop("disabled", true);
	$("#num_documento").prop("disabled", true);
	$("#nombre").prop("disabled", true);
	$("#direccion").prop("disabled", true);
	$("#telefono").prop("disabled", true);
	$("#email").prop("disabled", true);
	$("#idalmacen4").prop("disabled", true);
	$("#local_ruc4").prop("disabled", true);
	$("#descripcion").prop("disabled", true);
}

function deshabilitarTodoModalCliente() {
	$("#tipo_documento").prop("disabled", false);
	$("#num_documento").prop("disabled", false);
	$("#nombre").prop("disabled", false);
	$("#direccion").prop("disabled", false);
	$("#telefono").prop("disabled", false);
	$("#email").prop("disabled", false);
	$("#idalmacen4").prop("disabled", false);
	$("#local_ruc4").prop("disabled", false);
	$("#descripcion").prop("disabled", false);
}

// CLIENTES NUEVOS (POR SI NO ENCUENTRA LA SUNAT)

function limpiarModalClientes2() {
	$("#idcliente3").val("");
	$("#nombre6").val("");
	$("#tipo_documento2").val("");
	$("#num_documento2").val("");
	$("#direccion2").val("");
	$("#telefono2").val("");
	$("#email2").val("");
	$("#descripcion2").val("");

	$("#idalmacen5").val($("#idalmacen5 option:first").val());
	$("#idalmacen5").selectpicker('refresh');

	$("#btnGuardarCliente2").prop("disabled", false);

	actualizarRUC4();
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
	$("#idmetodopago2").val($("#idmetodopago2 option:first").val());
	$("#idmetodopago2").selectpicker('refresh');

	$("#idproducto").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val(lastNumSerie);
	$("#num_proforma").val("");
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
	$("#idcliente3").val($("#idcliente3 option:first").val());
	$("#idcliente3").selectpicker('refresh');
	$("#idalmacen3").val($("#idalmacen3 option:first").val());
	$("#idalmacen3").selectpicker('refresh');

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
	actualizarRUC5();
}

//Función limpiar
function limpiarModalProducto() {
	$("#codigo").val("");
	$("#codigo_producto").val("");
	$("#nombre5").val("");
	$("#descripcion5").val("");
	$("#talla").val("");
	$("#color").val("");
	$("#peso").val("");
	$("#posicion").val("");
	$("#fecha_emision").val("");
	$("#fecha_vencimiento").val("");
	$("#nota_1").val("");
	$("#nota_2").val("");
	$("#stock").val("");
	$("#stock_minimo").val("");
	$("#precio_compra").val("");
	$("#precio_venta").val("");
	$("#precio_venta_mayor").val("");
	$("#ganancia").val("0.00");
	$("#imagenmuestra").attr("src", "");
	$("#imagenmuestra").hide();
	$("#imagenactual").val("");
	$("#imagen").val("");
	$("#print").hide();
	$("#idarticulo").val("");

	$("#idcategoria").val($("#idcategoria option:first").val());
	$("#idcategoria").selectpicker('refresh');
	$("#idalmacen6").val($("#idalmacen6 option:first").val());
	$("#idalmacen6").selectpicker('refresh');
	$("#idmedida").val($("#idmedida option:first").val());
	$("#idmedida").selectpicker('refresh');
	$("#idmarcas").val($("#idmarcas option:first").val());
	$("#idmarcas").selectpicker('refresh');
	actualizarRUC6();

	$(".btn1").show();
	$(".btn2").hide();

	detenerEscaneo();

	$("#myModal6").modal("hide");
	frmDetalles(false);
}


function frmDetalles(bool) {
	if (bool == true) { $("#frmDetalles").show(); $("#btnDetalles1").hide(); $("#btnDetalles2").show(); }
	if (bool == false) { $("#frmDetalles").hide(); $("#btnDetalles1").show(); $("#btnDetalles2").hide(); }
	// $('html, body').animate({ scrollTop: $(document).height() }, 10);
}

function guardaryeditar5(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento

	var codigoBarra = $("#codigo").val();

	// var formatoValido = /^[0-9]{1} [0-9]{2} [0-9]{4} [0-9]{1} [0-9]{4} [0-9]{1}$/.test(codigoBarra);

	// if (!formatoValido && codigoBarra != "") {
	// 	bootbox.alert("El formato del código de barra no es válido. El formato correcto es: X XX XXXX X XXXX X");
	// 	$("#btnGuardar4").prop("disabled", false);
	// 	return;
	// }

	// var stock = parseFloat($("#stock").val());
	// var stock_minimo = parseFloat($("#stock_minimo").val());

	// if (stock_minimo > stock) {
	// 	bootbox.alert("El stock mínimo no puede ser mayor que el stock normal.");
	// 	return;
	// }

	var precio_compra = parseFloat($("#precio_compra").val()) || 0;
	var precio_venta = parseFloat($("#precio_venta").val()) || 0;
	var precio_venta_mayor = parseFloat($("#precio_venta_mayor").val()) || 0;

	if ((precio_venta > 0 || precio_venta_mayor > 0) && (precio_compra > precio_venta || precio_compra > precio_venta_mayor)) {
		bootbox.alert("El precio de venta no puede ser menor que el precio de compra.");
		return;
	}

	$("#btnGuardar4").prop("disabled", true);

	$("#ganancia").prop("disabled", false);
	desbloquearPrecioCompraVenta();
	var formData = new FormData($("#formulario5")[0]);
	$("#ganancia").prop("disabled", true);

	$.ajax({
		url: "../ajax/articulo.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar4").prop("disabled", false);
				return;
			} else if (datos == "El código de barra del artículo que ha ingresado ya existe." || datos == "El código del artículo que ha ingresado ya existe.") {
				bootbox.alert(datos);
				$("#btnGuardar4").prop("disabled", false);
				return;
			} else {
				$("#btnGuardar4").prop("disabled", false);
				limpiarModalProducto();
				listarArticulos();

				$.post("../ajax/proformas.php?op=selectProducto", function (r) {
					$("#idproducto").html(r);
					$('#idproducto').selectpicker('refresh');
				});

				$("#myModal6").modal("hide");
				bootbox.alert(datos);
			}
		}
	});
}

var quaggaIniciado = false;

function escanear() {
	$(".btn1").hide();
	$(".btn2").show();
	$("#camera").show();

	Quagga.init({
		inputStream: {
			name: "Live",
			type: "LiveStream",
			target: document.querySelector('#camera')
		},
		decoder: {
			readers: ["code_128_reader"]
		}
	}, function (err) {
		if (err) {
			console.log(err);
			return
		}
		console.log("Initialization finished. Ready to start");
		Quagga.start();
		quaggaIniciado = true;
	});

	Quagga.onDetected(function (data) {
		console.log(data.codeResult.code);
		var codigoBarra = data.codeResult.code;
		document.getElementById('codigo').value = codigoBarra;
	});
}

function detenerEscaneo() {
	if (quaggaIniciado) {
		Quagga.stop();
		$(".btn1").show();
		$(".btn2").hide();
		$("#camera").hide();
		formatearNumero();
		quaggaIniciado = false;
	}
}

$("#codigo").on("input", function () {
	formatearNumero();
});

function formatearNumero() {
	var codigo = $("#codigo").val().replace(/\s/g, '').replace(/\D/g, '');
	var formattedCode = '';

	// for (var i = 0; i < codigo.length; i++) {
	// 	if (i === 1 || i === 3 || i === 7 || i === 8 || i === 12 || i === 13) {
	// 		formattedCode += ' ';
	// 	}

	// 	formattedCode += codigo[i];
	// }

	// var maxLength = parseInt($("#codigo").attr("maxlength"));
	// if (formattedCode.length > maxLength) {
	// 	formattedCode = formattedCode.substring(0, maxLength);
	// }
	$("#codigo").val(codigo);
	generarbarcode(0);
}

function borrar() {
	$("#codigo").val("");
	$("#codigo").focus();
	$("#print").hide();
}

//función para generar el número aleatorio del código de barra
function generar() {
	var codigo = "775";
	codigo += generarNumero(10000, 999) + "";
	codigo += Math.floor(Math.random() * 10) + "";
	codigo += generarNumero(100, 9) + "";
	codigo += Math.floor(Math.random() * 10);
	$("#codigo").val(codigo);
	generarbarcode(1);
}

function generarNumero(max, min) {
	var numero = Math.floor(Math.random() * (max - min + 1)) + min;
	var numeroFormateado = ("0000" + numero).slice(-4);
	return numeroFormateado;
}

// Función para generar el código de barras
function generarbarcode(param) {

	// if (param == 1) {
	// 	var codigo = $("#codigo").val().replace(/\s/g, '');
	// 	console.log(codigo.length);

	// 	if (!/^\d+$/.test(codigo)) {
	// 		bootbox.alert("El código de barra debe contener solo números.");
	// 		return;
	// 	} else if (codigo.length !== 13) {
	// 		bootbox.alert("El código de barra debe tener 13 dígitos.");
	// 		return;
	// 	} else {
	// 		codigo = codigo.slice(0, 1) + " " + codigo.slice(1, 3) + " " + codigo.slice(3, 7) + " " + codigo.slice(7, 8) + " " + codigo.slice(8, 12) + " " + codigo.slice(12, 13);
	// 	}
	// } else {
	// 	var codigo = $("#codigo").val()
	// }

	var codigo = $("#codigo").val().replace(/\s/g, '');

	if (codigo != "") {
		JsBarcode("#barcode", codigo);
		$("#codigo").val(codigo);
		$("#print").show();
	} else {
		$("#print").hide();
	}
}

//Función para imprimir el código de barras
function imprimir() {
	$("#print").printArea();
}

//Función cancelarform
function cancelarform() {
	limpiar();
}

//Función cancelarform
function cancelarform2() {
	limpiarModalProducto();
}

//Función Listar
function listar() {
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
	formatearNumero();
	desbloquearPrecios();
	var formData = new FormData($("#formulario")[0]);

	$("#btnGuardar2").prop("disabled", true);
	$.ajax({
		url: "../ajax/proformas.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar2").prop("disabled", false);
				return;
			} else if (datos == "El número de proforma que ha ingresado ya existe en el local seleccionado." || datos == "Una de las cantidades superan al stock normal del artículo." || datos == "El subtotal de uno de los artículos no puede ser menor a 0." || datos == "El precio de venta de uno de los artículos no puede ser menor al precio de compra.") {
				bootbox.alert(datos);
				$("#btnGuardar2").prop("disabled", false);
				return;
			} else {
				bootbox.alert(datos);
				limpiarModalProducto();
				limpiar();
				setTimeout(() => {
					location.reload();
				}, 1500);
			}
		}
	});
}

function guardaryeditar2(e) {
	e.preventDefault();
	$("#idcliente2").prop("disabled", false);
	$("#tipo_comprobante2").prop("disabled", false);
	$("#impuesto2").prop("disabled", false);
	$("#idalmacen3").prop("disabled", false);
	var formData = new FormData($("#formulario2")[0]);
	formData.append('lastNumCompV', lastNumCompV);
	formData.append('lastNumSerieV', lastNumSerieV);
	$("#idcliente2").prop("disabled", true);
	$("#tipo_comprobante2").prop("disabled", true);
	$("#impuesto2").prop("disabled", true);
	$("#idalmacen3").prop("disabled", true);

	$("#btnGuardar3").prop("disabled", true);
	$.ajax({
		url: "../ajax/proformas.php?op=guardaryeditar2",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function (datos) {
			datos = limpiarCadena(datos);
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar3").prop("disabled", false);
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

function mostrar(idproforma) {
	$("#form_codigo_barra").hide();
	$("#btnAgregarArt").hide();
	$("#btnGuardar2").hide();
	$("#btnCrearArt").hide();

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

			$.post("../ajax/proformas.php?op=listarDetalle&id=" + idproforma, function (r) {
				$("#detalles").html(r);
				$('[data-toggle="popover"]').popover();
				ocultarColumnasPorNombre("detalles", columnasAocultar);
				ocultarColumnasPorNombre("tblarticulos", columnasAocultar);
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
			$("#idalmacen3").val(data.idalmacen);
			$("#idalmacen3").selectpicker('refresh');
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
			actualizarRUC3();

			$.post("../ajax/proformas.php?op=listarDetalle2&id=" + idproforma, function (r) {
				$("#detalles2").html(r);
				$('[data-toggle="popover"]').popover();
				ocultarColumnasPorNombre("detalles2", columnasAocultar);
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

function agregarDetalle(idarticulo, articulo, stock, precio_compra, precio_venta) {
	var cantidad = 1;
	var descuento = '0.00';

	if (idarticulo != "") {
		var subtotal = cantidad * precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td class="nowrap-cell"><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ', ' + idarticulo + ')">X</button></td>' +
			'<td class="nowrap-cell"><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
			'<td class="nowrap-cell">' + stock + '</td>' +
			'<td class="nowrap-cell"><input type="number" name="cantidad[]" id="cantidad[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" step="any" min="0.1" required value="' + cantidad + '"></td>' +
			// '<td class="nowrap-cell"><input type="text" name="cantidad[]" onblur="verificar_stock(' + idarticulo + ', \'' + articulo + '\')" id="cantidad[]" value="' + cantidad + '"></td>' +
			'<td class="nowrap-cell"><input type="hidden" step="any" class="precios" name="precio_compra[]" value="' + precio_compra + '"><span> S/. ' + precio_compra + '</span></td>' +
			'<td class="nowrap-cell"><input type="number" step="any" class="precios" name="precio_venta[]" id="precio_venta[]" lang="en-US" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" step="any" min="0.1" required value="' + (precio_venta == '' ? parseFloat(0).toFixed(2) : precio_venta) + '"></td>' +
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

		ocultarColumnasPorNombre("detalles", columnasAocultar);
		ocultarColumnasPorNombre("tblarticulos", columnasAocultar);
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
				console.log("Envío esto al servidor =>", e[0].idarticulo, e[0].articulo, e[0].stock, parseFloat(e[0].precio_compra).toFixed(2), parseFloat(e[0].precio_venta).toFixed(2));

				// Resetear el valor del select
				$('#idproducto').val($("#idproducto option:first").val());
				$("#idproducto").selectpicker('refresh');

				agregarDetalle(e[0].idarticulo, e[0].articulo, e[0].stock, parseFloat(e[0].precio_compra).toFixed(2), parseFloat(e[0].precio_venta).toFixed(2));

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