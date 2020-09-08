var tabla;

//funcion que se ejecuta al inicio
function init() {
    mostrarFormularioDisponibilidad(false, "null");
    listarRegistrosDisponibilidad();

    $("#formularioDisponibilidad").on("submit", function (e) {
        guardaryeditar(e);
    })
}

//funcion listar
function listarRegistrosDisponibilidad() {
    tabla = $('#tableDisponibilidad').dataTable({
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
            url: '../ajax/disponibilidad.php?op=listarRegistrosDisponibilidad',
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

function cargarSelectProvincia() {
    $.post("../ajax/disponibilidad.php?op=cargarProvincias", function (r) {
        $("#selectProvincia").html('<option value="0" selected>Seleccione una provincia</option>');
        $("#selectProvincia").append(r);
        $('#selectProvincia').selectpicker('refresh');
    });
}

function onChangeSelectProvincia(idProvincia) {
    $.post("../ajax/disponibilidad.php?op=cargarCiudadesxProvincia&id=" + idProvincia.value, function (r) {
        $("#selectCiudad").html('<option value="0" selected>Seleccione una ciudad</option>');
        $("#selectCiudad").append(r);
        $('#selectCiudad').selectpicker('refresh');
    });
}

function cargarSelectCiudad() {
    $.post("../ajax/disponibilidad.php?op=cargarCiudades", function (r) {
        $("#selectCiudad").html('<option value="0" selected>Seleccione una ciudad</option>');
        $("#selectCiudad").append(r);
        $('#selectCiudad').selectpicker('refresh');
    });
}

function cargarSelectTipoVehiculo() {
    $.post("../ajax/disponibilidad.php?op=cargarTipoDeVehiculos", function (r) {
        $("#selectTipoVehiculo").html(r);
        $('#selectTipoVehiculo').selectpicker('refresh');
    });
}

function mostrarRegistroDisponibilidad(idDisponibilidad) {

    mostrarFormularioDisponibilidad(true, "editar");

    $.post("../ajax/disponibilidad.php?op=mostrar", { iddisponibilidad: idDisponibilidad },
        function (data, status) {
            data = JSON.parse(data);
            console.log('Detalle de registro', data);

            $("#idDisponibilidad").val(data.iddisponibilidad);

            $("#inputIdVenta").val(data.idventa);

            $("#selectProvincia").val(data.idprovincia);
            $("#selectProvincia").selectpicker('refresh');

            $("#selectCiudad").val(data.idplanta);
            $("#selectCiudad").selectpicker('refresh');

            $("#selectTipoVehiculo").val(data.idcategoria_vehiculo);
            $("#selectTipoVehiculo").selectpicker('refresh');

            $("#inputFechaDisponible").val(data.fecha_disponible);
            $("#inputHoraDisponible").val(data.hora_disponible);

            $("#selectEstado").val(data.estado);
            $("#selectEstado").selectpicker('refresh');
        })
}

//funcion para desactivar
function eliminarRegistroDisponibilidad(idDisponibilidad) {
    bootbox.confirm("¿Esta seguro de eliminar este registro?", function (result) {
        if (result) {

            $.post("../ajax/disponibilidad.php?op=eliminar", { iddisponibilidad: idDisponibilidad }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}


//funcion mostrar formulario 
function mostrarFormularioDisponibilidad(flag, accion) {
    limpiar();
    if (flag) {
        cargarSelectProvincia();
        cargarSelectTipoVehiculo();


        $("#listadoDisponibilidad").hide();
        $("#divFormularioDisponibilidad").show();
        $("#btnGuardarDisponibilidad").prop("disabled", false);
        $("#btnAgregarDisponibilidad").hide();

        if (accion == "nuevo") {
            $("#inputIdVenta").prop("disabled", true);
            $("#input-pedido").hide();
        }

        if (accion == "editar") {
            $("#inputIdVenta").prop("disabled", false);
            $("#input-pedido").show();
            cargarSelectCiudad();
        }

    } else {
        $("#listadoDisponibilidad").show();
        $("#divFormularioDisponibilidad").hide();
        $("#btnAgregarDisponibilidad").show();
    }
}

//cancelar form
function cancelarFormularioDisponibilidad() {
    limpiar();
    mostrarFormularioDisponibilidad(false, "null");
}

//funcion limpiar
function limpiar() {
    $("#inputIdVenta").val("");

    $("#selectProvincia").html('<option value="0" selected>Seleccione una provincia</option>');
    $('#selectProvincia').selectpicker('refresh');

    $("#selectCiudad").html('<option value="0" selected>Seleccione una ciudad</option>');
    $('#selectCiudad').selectpicker('refresh');

    $("#selectTipoVehiculo").html('<option value="0" selected>Seleccione un tipo de vehiculo</option>');
    $('#selectTipoVehiculo').selectpicker('refresh');

    $("#inputFechaDisponible").val("");
    $("#inputHoraDisponible").val("");

    $("#selectEstado").val(0);
    $('#selectEstado').selectpicker('refresh');
}

//funcion para guardaryeditar
function guardaryeditar(e) {
    e.preventDefault();//no se activara la accion predeterminada 
    $("#btnGuardarDisponibilidad").prop("disabled", true);

    var formData = new FormData($("#formularioDisponibilidad")[0]);

    $.ajax({
        url: "../ajax/disponibilidad.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            bootbox.alert(datos);
            mostrarFormularioDisponibilidad(false, "null");
            tabla.ajax.reload();
        }
    });

    limpiar();
}

init();