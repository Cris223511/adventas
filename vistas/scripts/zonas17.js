var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	$('#mCuotas').addClass("treeview active");
	$('#lZonas').addClass("active");
}

//Función limpiar
function limpiar() {
	$("#ubicacion").val("");
	$("#zona").val("");
}

//Función mostrar formulario
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

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
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
				url: '../ajax/zonas.php?op=listar',
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

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/zonas.php?op=guardaryeditar",
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
			} else if (datos == "La ubicación que ha ingresado ya existe.") {
				bootbox.alert(datos);
				$("#btnGuardar").prop("disabled", false);
				return;
			} else {
				limpiar();
				bootbox.alert(datos);
				mostrarform(false);
				tabla.ajax.reload();
			}
		}
	});
}

function mostrar(idzona) {
	$.post("../ajax/zonas.php?op=mostrar", { idzona: idzona }, function (data, status) {
		data = JSON.parse(data);
		mostrarform(true);
		console.log(data);

		$("#ubicacion").val(data.ubicacion);
		$("#zona").val(data.zona);
		$("#idzona").val(data.idzona);
	});
}

//Función para desactivar registros
function desactivar(idzona) {
	bootbox.confirm("¿Está seguro de desactivar la zona?", function (result) {
		if (result) {
			$.post("../ajax/zonas.php?op=desactivar", { idzona: idzona }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para activar registros
function activar(idzona) {
	bootbox.confirm("¿Está seguro de activar la zona?", function (result) {
		if (result) {
			$.post("../ajax/zonas.php?op=activar", { idzona: idzona }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

//Función para eliminar los registros
function eliminar(idzona) {
	bootbox.confirm("¿Estás seguro de eliminar la zona?", function (result) {
		if (result) {
			$.post("../ajax/zonas.php?op=eliminar", { idzona: idzona }, function (e) {
				bootbox.alert(e);
				setTimeout(() => {
					location.reload();
				}, 1500);
			});
		}
	})
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});