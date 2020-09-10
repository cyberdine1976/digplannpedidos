var tabla;

//funcion que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});

	//cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

}

//funcion para cargar los selects del modal disponibilidad
function mostrarModalDisponibilidad() {
	//cargamos los items al select provincia
	$.post("../ajax/venta.php?op=selectProvincia", function (r) {
		$("#selectProvincia").html(r);
		$('#selectProvincia').selectpicker('refresh');
	});
}

function getIdProvincia(idProvincia) {
	$.post("../ajax/venta.php?op=selectCiudad&id=" + idProvincia.value, function (r) {
		$("#selectCiudad").html(r);
		$('#selectCiudad').selectpicker('refresh');
	});
}

function getIdCiudad(idCiudad) {
	$.post("../ajax/venta.php?op=selectTipoVehiculo&id=" + idCiudad.value, function (r) {
		$("#selectTipoVehiculo").html(r);
		$('#selectTipoVehiculo').selectpicker('refresh');
	});
}

function getIdTipoVehiculo(idCategoriaVehiculo) {
	$.post("../ajax/venta.php?op=getFechaHoraDisponibilidad&id=" + idCategoriaVehiculo.value, function (r) {

		var data = $.parseJSON(r);
		$("#labelFechaDisponible").text(data[0][0]);
		$("#labelHoraDisponible").text(data[0][1])
	});
}

//funcion limpiar
function limpiar() {

	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val("");
	$("#tipo_vehiculo").val("");


	$("#total_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

	//obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	$("#fecha_hora").val(today);
	$("#fecha_entrega").val("");
	$("#hora_entrega").val("");
	$("#observaciones").val("");
	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');

}

//funcion mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();
		listarDisponibilidad();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles = 0;
		$("#btnAgregarArt").show();
		detalleDisponibilidad = 0;
		$("#btnAgregarDisponibilidad").show();


	} else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//funcion listar
function listar() {
	tabla = $('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
			'copyHtml5',
			'excelHtml5',
			'csvHtml5',
			'pdf'
		],
		"ajax":
		{
			url: '../ajax/venta.php?op=listar',
			type: "get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5,//paginacion
		"order": [[0, "desc"]]//ordenar (columna, orden)
	}).DataTable();
}

function listarArticulos() {
	tabla = $('#tblarticulos').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [

		],
		"ajax":
		{
			url: '../ajax/venta.php?op=listarArticulos',
			type: "get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5,//paginacion
		"order": [[0, "desc"]]//ordenar (columna, orden)
	}).DataTable();
}

function listarDisponibilidad() {
	tabla = $('#tableSeleccionDisponibilidad').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [

		],
		"ajax":
		{
			url: '../ajax/venta.php?op=listarDisponibilidadEstadoDisponible',
			type: "get",
			dataType: "json",
			error: function (e) {
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5,//paginacion
		"order": [[0, "desc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e) {
	e.preventDefault();//no se activara la accion predeterminada 
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/venta.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			bootbox.alert(datos);
			mostrarform(false);
			listar();
		}
	});

	limpiar();
}

function mostrar(idventa) {
	$.post("../ajax/venta.php?op=mostrar", { idventa: idventa },
		function (data, status) {
			data = JSON.parse(data);
			mostrarform(true);

			$("#idcliente").val(data.idcliente);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante);
			$("#tipo_comprobante").selectpicker('refresh');
			$("#planta").val(data.planta);
			$("#tipo_vehiculo").val(data.tipo_vehiculo);
			$("#fecha_hora").val(data.fecha);
			$("#fecha_entrega").val(data.fecha_entrega);
			$("#hora_entrega").val(data.hora_entrega);
			$("#observaciones").val(data.observaciones);
			$("#idventa").val(data.idventa);

			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
		});
	$.post("../ajax/venta.php?op=listarDetalle&id=" + idventa, function (r) {
		$("#detalles").html(r);
	});

}


//funcion para desactivar
function anular(idventa) {
	bootbox.confirm("Esta seguro de anular esta Factura?", function (result) {
		if (result) {
			$.post("../ajax/venta.php?op=anular", { idventa: idventa }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//declaramos variables necesarias para trabajar con las compras y sus detalles
var impuesto = 15;
var cont = 0;
var detalles = 0;

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
	var tipo_comprobante = $("#tipo_comprobante option:selected").text();
	if (tipo_comprobante == 'Factura') {
		$("#impuesto").val(impuesto);
	} else {
		$("#impuesto").val("0");
	}
}

function agregarDetalle(idarticulo, articulo, precio_venta) {
	var cantidad = 1;
	var descuento = 0;

	if (idarticulo != "") {
		var subtotal = cantidad * precio_venta;
		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
			'<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
			'<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
			'<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '"></td>' +
			'<td><input type="number" name="descuento[]" value="' + descuento + '"></td>' +
			'<td><span id="subtotal' + cont + '" name="subtotal">' + subtotal + '</span></td>' +
			'<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
			'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		modificarSubtotales();

	} else {
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

var contDisponibilidad = 0;
var detalleDisponibilidad = 0;

function agregarDetalleDisponibilidad(nombreProvincia) {
	console.log('agregando detalle...', nombreProvincia);
	/* console.log('agregando detalle...');
	console.log(idDisponibilidad + nombreProvincia + nombreCiudad + tipoVehiculo + fechaDisponible + horaDisponible);
	if (idDisponibilidad != "") {
		var filaDisponibilidad =
			'<tr class="filasDisponibilidad" id="disponibilidad' + contDisponibilidad + '">' +
			'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalleDisponibilidad(' + contDisponibilidad + ')">X</button></td>' +
			'<td><input type="hidden" name="iddisponibilidad[]" value="' + idDisponibilidad + '">' + nombreProvincia + '</td>' +
			'<td>' + nombreCiudad + '"></td>' +
			'<td>' + tipoVehiculo + '"></td>' +
			'<td>' + fechaDisponible + '"></td>' +
			'<td>' + horaDisponible + '"></td>' +
			'</tr>';
		contDisponibilidad++;
		detalleDisponibilidad++;
		$('#detalleSeleccionDisponibilidad').append(filaDisponibilidad);
	} else {
		alert("Error al ingresar el detalle, revisar los datos de la disponibilidad ");
	} */
}

function modificarSubtotales() {
	var cant = document.getElementsByName("cantidad[]");
	var prev = document.getElementsByName("precio_venta[]");
	var desc = document.getElementsByName("descuento[]");
	var sub = document.getElementsByName("subtotal");


	for (var i = 0; i < cant.length; i++) {
		var inpV = cant[i];
		var inpP = prev[i];
		var inpS = sub[i];
		var des = desc[i];


		inpS.value = (inpV.value * inpP.value) - des.value;
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
	}

	calcularTotales();
}

function calcularTotales() {
	var sub = document.getElementsByName("subtotal");
	var total = 0.0;

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html("€." + total);
	$("#total_venta").val(total);
	evaluar();
}

function evaluar() {

	if (detalles > 0) {
		$("#btnGuardar").show();
	}
	else {
		$("#btnGuardar").hide();
		cont = 0;
	}
}

function eliminarDetalle(indice) {
	$("#fila" + indice).remove();
	calcularTotales();
	detalles = detalles - 1;

}

function eliminarDetalleDisponibilidad(indice) {
	$("#disponibilidad" + indice).remove();
	detalleDisponibilidad = detalleDisponibilidad - 1;
}

init();