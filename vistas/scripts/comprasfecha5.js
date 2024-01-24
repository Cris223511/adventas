var tabla;

//Función que se ejecuta al inicio
function init() {
	listarcompras();
	//Cargamos los items al select proveedor
	$.post("../ajax/ingreso.php?op=selectProveedor", function (r) {
		$("#idproveedor").html(r);
		$('#idproveedor').selectpicker('refresh');
	});

	$('#mConsultaC').addClass("treeview active");
	$('#lConsulasC').addClass("active");

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
function listarcompras() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idproveedor = $("#idproveedor").val();

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
				url: '../ajax/consultas.php?op=listarcompras',
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idproveedor: idproveedor },
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
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();

	listarcomprastotales(fecha_inicio, fecha_fin, idproveedor);
}

function listarcomprastotales(fecha_inicio, fecha_fin, idproveedor) {
	$.post("../ajax/consultas.php?op=listarcomprastotales&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&idproveedor=" + idproveedor, function (r) {
		$("#tbllistado tfoot th").eq(5).html(r);
		console.log(r);
	});
}

//Función Listar compras por fecha
function listartodascomprasfecha() {
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
				url: '../ajax/consultas.php?op=listartodascomprasfecha',
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
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();

	listartodascomprasfechatotales(fecha_inicio, fecha_fin);
}

function listartodascomprasfechatotales(fecha_inicio, fecha_fin) {
	$.post("../ajax/consultas.php?op=listartodascomprasfechatotales&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, function (r) {
		$("#tbllistado tfoot th").eq(5).html(r);
		console.log(r);
	});
}

//Función Listar Compras
function listartodascompras() {
	var idproveedor = $("#idproveedor").val();

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
				url: '../ajax/consultas.php?op=listartodascompras',
				data: { idproveedor: idproveedor },
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
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();

	listartodascomprastotales(idproveedor);
}

function listartodascomprastotales(idproveedor) {
	$.post("../ajax/consultas.php?op=listartodascomprastotales&idproveedor=" + idproveedor, function (r) {
		$("#tbllistado tfoot th").eq(5).html(r);
		console.log(r);
	});
}

//Función Listar Compras y Proveedores
function listartodascomprasproveedores() {

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
				url: '../ajax/consultas.php?op=listartodascomprasproveedores',
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
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();

	listartodascomprasproveedorestotales();
}

function listartodascomprasproveedorestotales() {
	$.post("../ajax/consultas.php?op=listartodascomprasproveedorestotales", function (r) {
		$("#tbllistado tfoot th").eq(5).html(r);
		console.log(r);
	});
}

init();