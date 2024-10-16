var tabla;

function bloquearCampos() {
	$("input, select, textarea").not("#fecha_hora").prop("disabled", true);
}

function desbloquearCampos() {
	$("input, select, textarea").not("#fecha_hora").prop("disabled", false);
}

function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$('#mAlmacen').addClass("treeview active");
	$('#lLocales').addClass("active");
}

function limpiar() {
	desbloquearCampos();
	$("#btnGuardar").show();

	$("#idalmacen").val("");
	$("#ubicacion").val("");
	$("#local_ruc").val("");
	$("#imagenmuestra").attr("src", "");
	$("#imagenmuestra").hide();
	$("#imagenactual").val("");
	$("#imagen").val("");
	$("#descripcion").val("");
}

function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function cancelarform() {
	limpiar();
	mostrarform(false);
}

function listar() {
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
				url: '../ajax/locales.php?op=listar',
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
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				// $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(4), td:eq(5), td:eq(6), td:eq(7)').addClass('nowrap-cell');
			}
		}).DataTable();
}

function guardaryeditar(e) {
	e.preventDefault();
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	if (formData.get("local_ruc").length < 11) {
		bootbox.alert("El RUC del local debe ser de 11 dígitos.");
		$("#btnGuardar").prop("disabled", false);
		return;
	}

	$.ajax({
		url: "../ajax/locales.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			datos = limpiarCadena(datos);
			if (datos == "El nombre del local ya existe.") {
				bootbox.alert(datos);
				$("#btnGuardar").prop("disabled", false);
				return;
			}
			limpiar();
			bootbox.alert(datos);
			mostrarform(false);
			tabla.ajax.reload();
			actualizarInfoUsuario();
		}
	});
}

// función para actualizar la información del usuario en sesión en tiempo real
function actualizarInfoUsuario() {
	$.ajax({
		url: "../ajax/locales.php?op=actualizarSession",
		dataType: 'json',
		success: function (data) {
			console.log(data)
			// actualizar la imagen y el nombre del usuario en la cabecera
			$('.user-menu .local').html('<strong> Local: ' + data.local + '</strong>');
		}
	});
}

function mostrar(idalmacen) {
	$.post("../ajax/locales.php?op=mostrar", { idalmacen: idalmacen }, function (data, status) {
		// console.log(data);
		data = JSON.parse(data);
		mostrarform(true);

		console.log(data);

		$("#ubicacion").val(data.ubicacion);
		$("#local_ruc").val(data.local_ruc);
		$("#descripcion").val(data.descripcion);
		$("#idalmacen").val(data.idalmacen);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src", "../files/locales/" + data.imagen);
		$("#imagenactual").val(data.imagen);
	})
}

function mostrar2(idalmacen) {
	$.post("../ajax/locales.php?op=mostrar", { idalmacen: idalmacen }, function (data, status) {
		// console.log(data);
		data = JSON.parse(data);
		mostrarform(true);
		bloquearCampos();
		$("#btnGuardar").hide();

		console.log(data);

		$("#ubicacion").val(data.ubicacion);
		$("#local_ruc").val(data.local_ruc);
		$("#descripcion").val(data.descripcion);
		$("#idalmacen").val(data.idalmacen);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src", "../files/locales/" + data.imagen);
		$("#imagenactual").val(data.imagen);
	})
}

function trabajadores(idalmacen, titulo) {
	$("#local").text(titulo);
	tabla = $('#tbltrabajadores').DataTable({
		"aProcessing": true,
		"aServerSide": true,
		"dom": 'Bfrtip',
		"buttons": [],
		"ajax": {
			url: '../ajax/locales.php?op=listarUsuariosLocal&idalmacen=' + idalmacen,
			type: "GET",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5,
		"order": [],
		"createdRow": function (row, data, dataIndex) {
			// $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(4), td:eq(5), td:eq(6), td:eq(8), td:eq(9), td:eq(10)').addClass('nowrap-cell');
		}
	});
}

function desactivar(idalmacen) {
	bootbox.confirm("¿Está seguro de desactivar el almacén?", function (result) {
		if (result) {
			$.post("../ajax/locales.php?op=desactivar", { idalmacen: idalmacen }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idalmacen) {
	bootbox.confirm("¿Está seguro de activar el almacén?", function (result) {
		if (result) {
			$.post("../ajax/locales.php?op=activar", { idalmacen: idalmacen }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function eliminar(idalmacen) {
	bootbox.confirm("¿Estás seguro de eliminar el almacén?", function (result) {
		if (result) {
			$.post("../ajax/locales.php?op=eliminar", { idalmacen: idalmacen }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();