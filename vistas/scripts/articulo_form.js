var tabla;

//Función que se ejecuta al inicio
function init() {
	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

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
			"idalmacen": $("#idalmacen"),
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

		$('#idcategoria').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregarCategoria(event)');
		$('#idcategoria').closest('.form-group').find('input[type="text"]').attr('maxlength', '40');

		$('#idmarcas').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregarMarca(event)');
		$('#idmarcas').closest('.form-group').find('input[type="text"]').attr('maxlength', '40');

		$('#idmedida').closest('.form-group').find('input[type="text"]').attr('onkeydown', 'agregarMedida(event)');
		$('#idmedida').closest('.form-group').find('input[type="text"]').attr('maxlength', '40');

		actualizarRUC();
	});

	$("#imagenmuestra").hide();
	$('#mAlmacen').addClass("treeview active");
	$('#lArticulos').addClass("active");
}

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
			$("#nombre4").val(inputValue.val());

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
						$("#nombre4").val("");
						$("#descripcion4").val("");
					}
				}
			});
		}
	}
}

function changeGanancia() {
	let precio_venta = $("#precio_venta").val();
	let precio_compra = $("#precio_compra").val();

	// Verificar si ambos campos están llenos
	if (precio_venta !== '' && precio_compra !== '') {
		let ganancia = precio_venta - precio_compra;
		$("#ganancia").val(ganancia.toFixed(2));
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

//Función limpiar
function limpiar() {
	$("#codigo").val("");
	$("#codigo_producto").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#talla").val("");
	$("#color").val("");
	$("#peso").val("");
	$("#posicion").val("");
	$("#stock").val("");
	$("#stock_minimo").val("");
	$("#precio_compra").val("");
	$("#precio_venta").val("");
	$("#ganancia").val("");
	$("#imagenmuestra").attr("src", "");
	$("#imagenmuestra").hide();
	$("#imagenactual").val("");
	$("#imagen").val("");
	$("#print").hide();
	$("#idarticulo").val("");

	$("#idcategoria").val($("#idcategoria option:first").val());
	$("#idcategoria").selectpicker('refresh');
	$("#idalmacen").val($("#idalmacen option:first").val());
	$("#idalmacen").selectpicker('refresh');
	$("#idmedida").val($("#idmedida option:first").val());
	$("#idmedida").selectpicker('refresh');
	$("#idmarcas").val($("#idmarcas option:first").val());
	$("#idmarcas").selectpicker('refresh');
	actualizarRUC();

	$(".btn1").show();
	$(".btn2").hide();

	detenerEscaneo();
}

function frmDetalles(bool) {
	if (bool == true) { $("#frmDetalles").show(); $("#btnDetalles1").hide(); $("#btnDetalles2").show(); }
	if (bool == false) { $("#frmDetalles").hide(); $("#btnDetalles1").show(); $("#btnDetalles2").hide(); }
	// $('html, body').animate({ scrollTop: $(document).height() }, 10);
}

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento

	var codigoBarra = $("#codigo").val();

	// var formatoValido = /^[0-9]{1} [0-9]{2} [0-9]{4} [0-9]{1} [0-9]{4} [0-9]{1}$/.test(codigoBarra);

	// if (!formatoValido && codigoBarra != "") {
	// 	bootbox.alert("El formato del código de barra no es válido. El formato correcto es: X XX XXXX X XXXX X");
	// 	$("#btnGuardar").prop("disabled", false);
	// 	return;
	// }

	// var stock = parseFloat($("#stock").val());
	// var stock_minimo = parseFloat($("#stock_minimo").val());

	// if (stock_minimo > stock) {
	// 	bootbox.alert("El stock mínimo no puede ser mayor que el stock normal.");
	// 	return;
	// }

	var precio_compra = parseFloat($("#precio_compra").val());
	var precio_venta = parseFloat($("#precio_venta").val());

	if (precio_compra > precio_venta) {
		bootbox.alert("El precio de compra no puede ser mayor que el precio de venta.");
		return;
	}

	$("#btnGuardar").prop("disabled", true);

	$("#ganancia").prop("disabled", false);
	desbloquearPrecioCompraVenta();
	var formData = new FormData($("#formulario")[0]);
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
				$("#btnGuardar").prop("disabled", false);
				return;
			} else if (datos == "El código de barra del artículo que ha ingresado ya existe." || datos == "El código del artículo que ha ingresado ya existe.") {
				bootbox.alert(datos);
				$("#btnGuardar").prop("disabled", false);
				return;
			} else {
				limpiar();
				bootbox.alert(datos);
				// setTimeout(() => {
				// 	history.back();
				// }, 1500);
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

document.addEventListener('DOMContentLoaded', function () {
	init();
});