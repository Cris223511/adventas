var tabla;
let select = $("#idalmacen"); // select

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	})

	$("#imagenmuestra").hide();
	//Mostramos los permisos
	$.post("../ajax/usuario.php?op=permisos&id=", function (r) {
		$("#permisos").html(r);
	});

	$('#mAcceso').addClass("treeview active");
	$('#lUsuarios').addClass("active");

	$("#checkAll").prop("checked", false);
}

function toggleCheckboxes(checkbox) {
	var checkboxes = document.querySelectorAll('#permisos input[type="checkbox"]');

	checkboxes.forEach(function (cb) {
		cb.checked = checkbox.checked;
	});
}

function cargarLocalDisponible() {
	select.empty();
	// Cargamos los items al select "local principal"
	$.post("../ajax/locales.php?op=selectLocales", function (data) {
		// console.log(data);
		objSelects = JSON.parse(data);
		console.log(objSelects);
		if (objSelects.length != 0) {
			select.html('<option value="">- Seleccione -</option>');

			objSelects.almacenes.forEach(function (opcion) {
				select.append('<option value="' + opcion.idalmacen + '" data-local-ruc="' + opcion.local_ruc + '">' + opcion.ubicacion + '</option>');
			});
			select.selectpicker('refresh');
		} else {
			console.log("no hay datos =)")
		}
		limpiar();
	});
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
	$("#nombre").val("");
	$("#idalmacen").val($("#idalmacen option:first").val());
	$("#idalmacen").selectpicker('refresh');
	$("tipo_documento").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#local_ruc").val("");
	$("#cargo").val("admin");
	$("#cargo").selectpicker('refresh');
	$("#login").val("");
	$("#clave").val("");
	$("#imagen").val("");
	$("#imagenmuestra").attr("src", "");
	$("#imagenmuestra").hide();
	$("#imagenactual").val("");
	$("#idusuario").val("");

	$("#checkAll").prop("checked", false);
}

//Función mostrar formulario
function mostrarform(flag) {
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
		cargarLocalDisponible();

		// desenmarcamos los selects
		$("#permisos input[type='checkbox']").each(function () {
			$(this).prop('checked', false);
		});
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
				url: '../ajax/usuario.php?op=listar',
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
			"order": []
		}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			if (!datos) {
				console.log("No se recibieron datos del servidor.");
				$("#btnGuardar").prop("disabled", false);
				return;
			} else if (datos == "El nombre del usuario que ha ingresado ya existe." || datos == "El número de documento que ha ingresado ya existe.") {
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

function mostrar(idusuario) {
	$.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function (data, status) {
		// console.log(data);
		data = JSON.parse(data);
		console.log(data);
		mostrarform(true);

		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").trigger("change");
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#idalmacen").val(data.idalmacen);
		$("#idalmacen").selectpicker('refresh');
		$("#local_ruc").val(data.local_ruc);
		$("#cargo").val(data.cargo);
		$("#cargo").selectpicker('refresh');
		$("#login").val(data.login);
		$("#clave").val(data.clave);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#idusuario").val(data.idusuario);

		$("#checkAll").prop("checked", false);
	});

	$.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function (r) {
		$("#permisos").html(r);
	});
}

//Función para desactivar registros
function desactivar(idusuario) {
	bootbox.confirm("¿Está seguro de desactivar el usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//Función para activar registros
function activar(idusuario) {
	bootbox.confirm("¿Está seguro de activar el usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=activar", { idusuario: idusuario }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//Función para eliminar los registros
function eliminar(idusuario) {
	bootbox.confirm("¿Estás seguro de eliminar el usuario?", function (result) {
		if (result) {
			$.post("../ajax/usuario.php?op=eliminar", { idusuario: idusuario }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
				cargarLocalDisponible();
			});
		}
	})
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});