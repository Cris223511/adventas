var tabla1;
var tabla2;

function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});
	$('#mPerfilUsuario').addClass("treeview active");
	$('#lLocalesExternos').addClass("active");
}

function limpiar() {
	$("#didalmacen").val("");
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
		$(".listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled", false);
		$("#btnagregar").hide();
	}
	else {
		$(".listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function cancelarform() {
	limpiar();
	mostrarform(false);
}

function listar() {
	$("#fecha_inicio").val("");
	$("#fecha_fin").val("");

	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();

	tabla1 = $('#tbllistado').dataTable(
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
				url: '../ajax/localesExternos.php?op=listar',
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
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				// $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(4), td:eq(5), td:eq(6), td:eq(7)').addClass('nowrap-cell');
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

	tabla1 = $('#tbllistado').dataTable(
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
				url: '../ajax/localesExternos.php?op=listar',
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
		url: "../ajax/localesExternos.php?op=guardaryeditar",
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
			tabla1.ajax.reload();
		}
	});
}

function mostrar(didalmacen) {
	$.post("../ajax/localesExternos.php?op=mostrar", { didalmacen: didalmacen }, function (data, status) {
		// console.log(data);
		data = JSON.parse(data);
		mostrarform(true);

		console.log(data);

		$("#ubicacion").val(data.ubicacion);
		$("#local_ruc").val(data.local_ruc);
		$("#descripcion").val(data.descripcion);
		$("#didalmacen").val(data.didalmacen);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src", "../files/locales/" + data.imagen);
		$("#imagenactual").val(data.imagen);
	})
}

function trabajadores(didalmacen, titulo) {
	$("#local").text(titulo);
	tabla2 = $('#tbltrabajadores').DataTable({
		"aProcessing": true,
		"aServerSide": true,
		"dom": 'Bfrtip',
		"buttons": [],
		"ajax": {
			url: '../ajax/localesExternos.php?op=listarUsuariosLocal&didalmacen=' + didalmacen,
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
			// $(row).find('td:eq(0), td:eq(1), td:eq(2), td:eq(4), td:eq(5), td:eq(6), td:eq(7)').addClass('nowrap-cell');
		}
	});
}


function desactivar(didalmacen) {
	bootbox.confirm("¿Está seguro de desactivar el local?", function (result) {
		if (result) {
			$.post("../ajax/localesExternos.php?op=desactivar", { didalmacen: didalmacen }, function (e) {
				bootbox.alert(e);
				tabla1.ajax.reload();
			});
		}
	})
}

function activar(didalmacen) {
	bootbox.confirm("¿Está seguro de activar el local?", function (result) {
		if (result) {
			$.post("../ajax/localesExternos.php?op=activar", { didalmacen: didalmacen }, function (e) {
				bootbox.alert(e);
				tabla1.ajax.reload();
			});
		}
	})
}

function eliminar(didalmacen) {
	bootbox.confirm("¿Estás seguro de eliminar el local?", function (result) {
		if (result) {
			$.post("../ajax/localesExternos.php?op=eliminar", { didalmacen: didalmacen }, function (e) {
				bootbox.alert(e);
				tabla1.ajax.reload();
			});
		}
	})
}

init();