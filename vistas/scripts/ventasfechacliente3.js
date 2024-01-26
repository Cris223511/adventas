var tabla;

//Función que se ejecuta al inicio
function init() {
	listarventas();
	//Cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});
	$('#mReporte').addClass("treeview active");
	$('#lConsulasV').addClass("active");

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
function listarventas() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idcliente = $("#idcliente").val();

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
				url: '../ajax/consultas.php?op=listarventas',
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idcliente: idcliente },
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

	listarventastotales(fecha_inicio, fecha_fin, idcliente);
}

function listarventastotales(fecha_inicio, fecha_fin, idcliente) {
	$.post("../ajax/consultas.php?op=listarventastotales&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&idcliente=" + idcliente, function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

//Función Listar ventas por fecha
function listartodasventasfecha() {
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
				url: '../ajax/consultas.php?op=listartodasventasfecha',
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

	listartodasventasfechatotales(fecha_inicio, fecha_fin);
}

function listartodasventasfechatotales(fecha_inicio, fecha_fin) {
	$.post("../ajax/consultas.php?op=listartodasventasfechatotales&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

//Función listar Ventas
function listartodasventas() {
	var idcliente = $("#idcliente").val();

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
				url: '../ajax/consultas.php?op=listartodasventas',
				data: { idcliente: idcliente },
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

	listartodasventastotales(idcliente);
}

function listartodasventastotales(idcliente) {
	$.post("../ajax/consultas.php?op=listartodasventastotales&idcliente=" + idcliente, function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

//Función listar Ventas y Clientes
function listartodasventasclientes() {

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
				url: '../ajax/consultas.php?op=listartodasventasclientes',
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

	listartodasventasclientestotales();
}

function listartodasventasclientestotales() {
	$.post("../ajax/consultas.php?op=listartodasventasclientestotales", function (r) {
		$("#tbllistado tfoot th").eq(6).html(r);
		console.log(r);
	});
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});