var tablas = [];

async function init() {
	$('#mConsultaD').addClass("treeview active");
	$('#lConsultaD').addClass("active");

	await cargarTablas();
	pintarTablas();
}

async function cargarTablas() {
	const promise = [
		cargarDatos('articulosmasdevueltos_tipo1'),
		cargarDatos('articulosmasdevueltos_tipo2'),
		cargarDatos('articulosmasdevueltos_tipo3'),
		cargarDatos('articulosmasdevueltos_tipo4'),
		cargarDatos('articulosmasdevueltos_tipo5'),
		cargarDatos('articulosmasdevueltos_tipo6'),
	];

	tablas = await Promise.all(promise);
}

async function cargarDatos(op) {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: `../ajax/consultas.php?op=${op}`,
			type: "get",
			dataType: "json",
			success: function (data) {
				resolve(data);
			},
			error: function (e) {
				console.log(e.responseText);
				reject(e);
			}
		});
	});
}

function pintarTabla(tableId, tabIndex) {
	$(`#${tableId}`).dataTable({
		"lengthMenu": [5, 10, 25, 75, 100],
		"aProcessing": true,
		"aServerSide": true,
		dom: '<Bl<f>rtip>',
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
		],
		"language": {
			"emptyTable": tablas[tabIndex]?.aaData ? "No existen datos" : "Cargando...",
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
		"data": tablas[tabIndex]?.aaData || []
	});
}

function pintarTablas() {
	for (let i = 0; i < 6; i++) {
		pintarTabla(`tbllistado_${i + 1}`, i);
	}
}

pintarTablas();
init();