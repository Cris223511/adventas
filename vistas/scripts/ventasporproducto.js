var tabla;
var tabla2;
var idusuario = document.getElementById("idusuarioSesion").innerHTML;
console.log(idusuario);

//Función que se ejecuta al inicio
function init() {
	listarventas();
	//Cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

	//Cargamos los items al select usuario
	$.post("../ajax/usuario.php?op=selectUsuarios", function (data) {
		$("#idusuario").html(data);
		$("#idusuario").val(idusuario);
		$('#idusuario').selectpicker('refresh');
	});

	$('#mReporte').addClass("treeview active");
	$('#lConsultaVP').addClass("active");

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

function mostrar(idventa) {
	tabla2 = $('#tbllistado2').dataTable(
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
				url: '../ajax/venta.php?op=listarDetalleproductoventa&id=' + idventa,
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
			"order": [],
			"createdRow": function (row, data, dataIndex) {
				$(row).find('td:eq(1)').css({
					"white-space": "nowrap"
				});
			}
		}).DataTable();
}

function cancelarForm() {
	$('#myModal2').modal('hide');
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
				url: '../ajax/consultas.php?op=listarventasproducto',
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
	$.post("../ajax/consultas.php?op=listarventastotalesproducto&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&idcliente=" + idcliente, function (r) {
		$("#tbllistado tfoot th").eq(7).html(r);
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
				url: '../ajax/consultas.php?op=listartodasventasfechaproducto',
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
	$.post("../ajax/consultas.php?op=listartodasventasfechatotalesproducto&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, function (r) {
		$("#tbllistado tfoot th").eq(7).html(r);
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
				url: '../ajax/consultas.php?op=listartodasventasproducto',
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
	$.post("../ajax/consultas.php?op=listartodasventastotalesproducto&idcliente=" + idcliente, function (r) {
		$("#tbllistado tfoot th").eq(7).html(r);
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
				url: '../ajax/consultas.php?op=listartodasventasclientesproducto',
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
	$.post("../ajax/consultas.php?op=listartodasventasclientestotalesproducto", function (r) {
		$("#tbllistado tfoot th").eq(7).html(r);
		console.log(r);
	});
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
				url: '../ajax/consultas.php?op=listarventasusuarioproducto',
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
	$.post("../ajax/consultas.php?op=listarventasusuariototalesproducto&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&idusuario=" + idusuario, function (r) {
		$("#tbllistado tfoot th").eq(7).html(r);
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
				url: '../ajax/consultas.php?op=listartodasventasusuarioproducto',
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
	$.post("../ajax/consultas.php?op=listartodasventasusuariototalesproducto&idusuario=" + idusuario, function (r) {
		$("#tbllistado tfoot th").eq(7).html(r);
		console.log(r);
	});
}

init();