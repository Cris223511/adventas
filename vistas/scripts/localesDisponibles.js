var tabla;

function init() {
	mostrarform(false);
	mostrarform2(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$("#formulario2").on("submit", function (e) {
		guardaryeditar2(e);
	});

	$("#imagenmuestra").hide();
	$('#mPerfilUsuario').addClass("treeview active");
	$('#lLocalesDisponibles').addClass("active");

	cargarLocalesDisponibles();

	// $.post("../ajax/usuario.php?op=listarUsuariosActivos", function (r) {
	// 	console.log(r);
	// 	$("#idusuario_asignar").html(r);
	// 	$('#idusuario_asignar').selectpicker('refresh');
	// });
}

function cargarLocalesDisponibles() {
	// $.post("../ajax/localesDisponibles.php?op=selectLocalDisponible", function (r) {
	// 	console.log(r);
	// 	$("#idalmacen_asignar").html(r);
	// 	$('#idalmacen_asignar').selectpicker('refresh');
	// });
}

function limpiar() {
	$("#idalmacen").val("");
	$("#ubicacion").val("");
	$("#local_ruc").val("");
	$("#descripcion").val("");

	$("#idalmacen_asignar").val($("#idalmacen_asignar option:first").val());
	$("#idalmacen_asignar").selectpicker('refresh');

	$("#idusuario_asignar").val($("#idusuario_asignar option:first").val());
	$("#idusuario_asignar").selectpicker('refresh');
}

function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
		$("#btnasignar").hide();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btnasignar").show();
	}
}

function mostrarform2(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioasignacion").show();
		$("#btnGuardar2").prop("disabled", false);
		$("#btnagregar").hide();
		$("#btnasignar").hide();
	}
	else {
		$("#listadoregistros").show();
		$("#formularioasignacion").hide();
		$("#btnagregar").show();
		$("#btnasignar").show();
	}
}

function cancelarform() {
	limpiar();
	mostrarform(false);
}

function cancelarform2() {
	limpiar();
	mostrarform2(false);
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
				url: '../ajax/localesDisponibles.php?op=listar',
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
				// $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(4), td:eq(5)').addClass('nowrap-cell');
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
		url: "../ajax/localesDisponibles.php?op=guardaryeditar",
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
			cargarLocalesDisponibles();

			var idalmacen = formData.get("idalmacen");
			actualizarInfoUsuario(idalmacen);

		}
	});
}

function guardaryeditar2(e) {
	e.preventDefault();
	$("#btnGuardar2").prop("disabled", true);
	var formData = new FormData($("#formulario2")[0]);

	$.ajax({
		url: "../ajax/localesDisponibles.php?op=guardaryeditar2",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			limpiar();
			bootbox.alert(datos);
			mostrarform2(false);
			tabla.ajax.reload();
			cargarLocalesDisponibles();
		}
	});
}

// función para actualizar la información del usuario en sesión en tiempo real
function actualizarInfoUsuario(idalmacen) {
	$.ajax({
		url: "../ajax/localesDisponibles.php?op=actualizarSession",
		type: "POST",
		data: { idalmacen: idalmacen },
		dataType: 'json',
		success: function (data) {
			console.log(data);
			if (data.local) {
				// actualizar la imagen y el nombre del usuario en la cabecera
				$('.user-menu .local').html('<strong> Local: ' + data.local + '</strong>');
			}
		}
	});
}

function mostrar(idalmacen) {
	$.post("../ajax/localesDisponibles.php?op=mostrar", { idalmacen: idalmacen }, function (data, status) {
		// console.log(data);
		data = JSON.parse(data);
		mostrarform(true);

		console.log(data);

		$("#ubicacion").val(data.ubicacion);
		$("#local_ruc").val(data.local_ruc);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src", "../files/locales/" + data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#descripcion").val(data.descripcion);
		$("#idalmacen").val(data.idalmacen);
	})
}

function desactivar(idalmacen) {
	bootbox.confirm("¿Está seguro de desactivar el local?", function (result) {
		if (result) {
			$.post("../ajax/localesDisponibles.php?op=desactivar", { idalmacen: idalmacen }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
				cargarLocalesDisponibles();
			});
		}
	})
}

function activar(idalmacen) {
	bootbox.confirm("¿Está seguro de activar el local?", function (result) {
		if (result) {
			$.post("../ajax/localesDisponibles.php?op=activar", { idalmacen: idalmacen }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
				cargarLocalesDisponibles();
			});
		}
	})
}

function eliminar(idalmacen) {
	bootbox.confirm("¿Estás seguro de eliminar el local?", function (result) {
		if (result) {
			$.post("../ajax/localesDisponibles.php?op=eliminar", { idalmacen: idalmacen }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
				cargarLocalesDisponibles();
			});
		}
	})
}

init();