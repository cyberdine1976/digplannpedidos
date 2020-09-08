<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.html");
} else {

    require 'header.php';
    if ($_SESSION['planta'] == 1) {
?>
        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Section -->
            <section class="content">

                <!-- Box -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">

                            <!-- Box Header -->
                            <div class="box-header with-border">
                                <h1 class="box-title">Disponibilidad <button class="btn btn-success" onclick="mostrarFormularioDisponibilidad(true, 'nuevo')" id="btnAgregarDisponibilidad"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
                                <div class="box-tools pull-right">

                                </div>
                            </div>
                            <!-- Fin Box Header-->


                            <!-- DataTable -->
                            <div class="panel-body table-responsive" id="listadoDisponibilidad">
                                <table id="tableDisponibilidad" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>Opciones</th>
                                        <th>Pedido</th>
                                        <th>Provincia</th>
                                        <th>Ciudad</th>
                                        <th>Tipo de vehiculo</th>
                                        <th>Fecha disponible</th>
                                        <th>Hora disponible</th>
                                        <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <th>Opciones</th>
                                        <th>Pedido</th>
                                        <th>Provincia</th>
                                        <th>Ciudad</th>
                                        <th>Tipo de vehiculo</th>
                                        <th>Fecha disponible</th>
                                        <th>Hora disponible</th>
                                        <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Fin DataTable -->

                            <!-- Formulario Disponibilidad -->

                            <div class="panel-body" id="divFormularioDisponibilidad">
                                <form action="" name="formulario" id="formularioDisponibilidad" method="POST">
                                    <div class="row" id="input-pedido">
                                        <div class="col-xs-6 col-md-4">
                                            <div class="form-group">
                                                <label for="formControlSelect1">Nº Pedido</label>
                                                <input class="form-control" type="hidden" name="iddisponibilidad" id="idDisponibilidad">
                                                <input class="form-control" type="text" name="idventa" id="inputIdVenta" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-md-4">
                                            <div class="form-group">
                                                <label for="formControlSelect1">Provincia</label>
                                                <select onchange="onChangeSelectProvincia(this);" class="form-control select-picker" name="idprovincia" id="selectProvincia" required>
                                                    <option value="0" selected>Seleccione una provincia</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-4">
                                            <div class="form-group">
                                                <label for="formControlSelect2">Ciudad</label>
                                                <select class="form-control select-picker" name="idplanta" id="selectCiudad" required>
                                                    <option value="0" selected>Seleccione una ciudad</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-4">
                                            <div class="form-group">
                                                <label for="formControlSelect3">Tipo de vehiculo</label>
                                                <select class="form-control select-picker" name="idcategoria_vehiculo" id="selectTipoVehiculo" required>
                                                    <option value="0" selected>Seleccione un tipo de vehiculo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-md-4">
                                            <label for="">Fecha:</label>
                                            <input class="form-control" type="date" name="fecha_disponible" id="inputFechaDisponible" required>
                                        </div>
                                        <div class="col-xs-6 col-md-4">
                                            <label for="">Hora:</label>
                                            <input class="form-control" type="time" name="hora_disponible" id="inputHoraDisponible" required>
                                        </div>
                                        <div class="col-xs-6 col-md-4">
                                            <div class="form-group">
                                                <label for="formControlSelect4">Estado</label>
                                                <select class="form-control select-picker" name="estado" id="selectEstado" required>
                                                    <option value="0" selected>Seleccione un estado</option>
                                                    <option value="Disponible">Disponible</option>
                                                    <option value="Pedido despachado">Pedido despachado</option>
                                                    <option value="Pedido generado">Pedido generado</option>
                                                    <option value="Pedido anulado">Pedido anulado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardarDisponibilidad"><i class="fa fa-save"></i> Guardar</button>
                                        <button class="btn btn-danger" onclick="cancelarFormularioDisponibilidad()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- Fin Formulario Disponibilidad -->


                        </div>
                    </div>

                    <!-- Fin Box -->
                </div>


                <!-- Fin Section -->
            </section>

            <!-- Fin Main Content -->
        </div>

    <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script src="scripts/disponibilidad.js"></script>
<?php
}

ob_end_flush();
?>