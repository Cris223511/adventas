var tabla;
var idusuario = document.getElementById("idusuarioSesion").innerHTML;
console.log(idusuario);

//Función que se ejecuta al inicio
function init() {
	listarventasusuario();
	//Cargamos los items al select usuario
	$.post("../ajax/usuario.php?op=selectUsuarios", function (data) {
		$("#idusuario").html(data);
		$("#idusuario").val(idusuario);
		$('#idusuario').selectpicker('refresh');
	});
	$('#mReporte').addClass("treeview active");
	$('#lConsultaU').addClass("active");

	//Obtenemos la fecha actual
	var fecha = new Date();
	var mes = fecha.getMonth() + 1;
	var dia = fecha.getDate();
	var ano = fecha.getFullYear();
	if (dia < 10)
		dia = '0' + dia;
	if (mes < 10)
		mes = '0' + mes;
	$("#fecha_inicio").val(ano + "-" + mes + "-" + dia);
	$("#fecha_fin").val(ano + "-" + mes + "-" + dia);
}


//Función Listar
function listarventasusuario() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idusuario = $("#idusuario").val();

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
				url: '../ajax/consultas.php?op=listarventasusuario',
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idusuario: idusuario },
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
			"order": []//Ordenar (columna,orden)
		}).DataTable();

	listarventasusuariototales(fecha_inicio, fecha_fin, idusuario);
}

function listarventasusuariototales(fecha_inicio, fecha_fin, idusuario) {
	$.post("../ajax/consultas.php?op=listarventasusuariototales&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&idusuario=" + idusuario, function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

//Función Listar ventas usuario por fecha
function listartodasventasusuariofecha() {
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
				url: '../ajax/consultas.php?op=listartodasventasusuariofecha',
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
			"order": []//Ordenar (columna,orden)
		}).DataTable();

	listartodasventasusuariofechatotales(fecha_inicio, fecha_fin);
}

function listartodasventasusuariofechatotales(fecha_inicio, fecha_fin) {
	$.post("../ajax/consultas.php?op=listartodasventasusuariofechatotales&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

//Función listar Ventas
function listartodasventasusuario() {
	var idusuario = $("#idusuario").val();

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
				url: '../ajax/consultas.php?op=listartodasventasusuario',
				data: { idusuario: idusuario },
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
			"order": []//Ordenar (columna,orden)
		}).DataTable();

	listartodasventasusuariototales(idusuario);
}

function listartodasventasusuariototales(idusuario) {
	$.post("../ajax/consultas.php?op=listartodasventasusuariototales&idusuario=" + idusuario, function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

//Función listar Ventas y Usuarios
function listartodasventasusuariousuarios() {

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
				url: '../ajax/consultas.php?op=listartodasventasusuariousuarios',
				data: "",
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
			"order": []//Ordenar (columna,orden)
		}).DataTable();

	listartodasventasusuariousuariostotales();
}

function listartodasventasusuariousuariostotales() {
	$.post("../ajax/consultas.php?op=listartodasventasusuariousuariostotales", function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

init();