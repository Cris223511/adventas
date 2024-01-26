var tabla;

var idcuota = document.getElementById("idcuota").innerHTML;
console.log(idcuota);

let montoTotal = '';
let montoPagado = '';

//Función que se ejecuta al inicio
function init() {
	mostrar(idcuota);
	mostrarPagos(idcuota);
	contarPagos(idcuota);

	$("#formulario").on("submit", function (e) {
		guardarPagos(e);
	});

	$('#mCuotas').addClass("treeview active");
	$('#lCuotas').addClass("active");
}

//Función limpiar
function limpiar() {
	$("#metodo_pago").val("Efectivo");
	$('#metodo_pago').selectpicker('refresh');
	$("#concepto").val("");
	$("#monto").val("");
}

//Función cancelarform
function cancelarform() {
	limpiar();
}

function mostrar(idcuota) {
	$.post("../ajax/cuotas.php?op=listarDetalleCuota&id=" + idcuota, function (r) {
		$("#detallesCompra").html(r);
	});
}

function mostrarPagos(idcuota) {
	$.post("../ajax/cuotas.php?op=listarDetallePago&id=" + idcuota, function (r) {
		$("#detallesPagos").html(r);
	});
}

function contarPagos(idcuota) {
	$.post("../ajax/cuotas.php?op=contarDetallePago&id=" + idcuota, function (data, status) {
		// console.log(data.split("'"));

		$("#contarPagos").html("");
		$("#contarPagos").html(data.split("'")[1]);
	});

	$.post("../ajax/cuotas.php?op=sumaTotalDetallePago&id=" + idcuota, function (data, status) {
		$("#montoPagado").html("");
		$("#montoPagado").html(data + " S/.");
		verificarEstado(idcuota);
	});
}

function verificarEstado(idcuota) {
	var result1 = document.getElementById("montoTotal").innerHTML;
	var result2 = document.getElementById("montoPagado").innerHTML;

	var montoTotal = result1.split(" S/.")[0];
	var montoPagado = result2.split(" S/.")[0];

	console.log("Verificando los estados de los montos: " + montoTotal + " y " + montoPagado);

	$.post("../ajax/cuotas.php?op=verificarEstado&id=" + idcuota + "&montoTotal=" + montoTotal + "&montoPagado=" + montoPagado, function (data) {
		console.log(data);
	});
}

function guardarPagos(e) {
	var result1 = document.getElementById("montoTotal").innerHTML;
	var result2 = document.getElementById("montoPagado").innerHTML;

	var montoTotal = result1.split(" S/.")[0];
	var montoPagado = result2.split(" S/.")[0];

	console.log(montoTotal, montoPagado);

	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/cuotas.php?op=guardarpagos&id=" + idcuota + "&montoTotal=" + montoTotal + "&montoPagado=" + montoPagado,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			if (datos == "El monto de pago supera al monto total a pagar.") {
				bootbox.alert(datos);
				return;
			}
			bootbox.alert(datos);
			$('#myModal').modal('hide');
			mostrarPagos(idcuota);
			contarPagos(idcuota);

			setTimeout(function () {
				window.location.reload();
			}, 1500);
		}
	});
	limpiar();
}

document.addEventListener('DOMContentLoaded', function () {
	init();
});