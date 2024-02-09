var tabla;

//Función que se ejecuta al inicio
function init() {
	limpiar();
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});
	$('#mCompras').addClass("treeview active");
	$('#lProveedores').addClass("active");
}

//Función limpiar
function limpiar() {
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#idpersona").val("");

	//Marcamos el primer tipo_documento
	$("#tipo_documento").val("DNI");
	$("#tipo_documento").selectpicker('refresh');

	$('#myModal').modal('hide');

	$("#btnGuardar").prop("disabled", false);
}

//Función cancelarform
function cancelarform() {
	limpiar();
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
				url: '../ajax/persona.php?op=listarp',
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
//Función para guardar o editar

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	var formData = new FormData($("#formulario")[0]);
	$("#btnGuardar").prop("disabled", true);
	$.ajax({
		url: "../ajax/persona.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar").prop("disabled", false);
				return;
			} else {
				$("#btnGuardar").prop("disabled", false);
				bootbox.alert(datos);
				limpiar();
				tabla.ajax.reload();
			}
		}
	});
}

function mostrar(idpersona) {
	$.post("../ajax/persona.php?op=mostrar", { idpersona: idpersona }, function (data, status) {
		data = JSON.parse(data);
		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").trigger("change");
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#idpersona").val(data.idpersona);
	})
}

//Función para eliminar registros
function eliminar(idpersona) {
	bootbox.confirm("¿Está seguro de eliminar el proveedor?", function (result) {
		if (result) {
			$.post("../ajax/persona.php?op=eliminar", { idpersona: idpersona }, function (e) {
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