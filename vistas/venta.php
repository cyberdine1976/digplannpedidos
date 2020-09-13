<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {


  require 'header.php';

  if ($_SESSION['ventas'] == 1) {

?>
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Pedidos <button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
                <div class="box-tools pull-right">

                </div>
              </div>
              <!--box-header-->
              <!--centro-->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Fecha Pedido</th>
                    <th>Fecha Entrega</th>
                    <th>Hora Entrega</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Planta</th>
                    <th>Tipo Vehiculo</th>
                    <th>Observaciones</th>
                    <th>Total Venta</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Fecha Pedido</th>
                    <th>Fecha Entrega</th>
                    <th>Hora Entrega</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Documento</th>
                    <th>Planta</th>
                    <th>Tipo Vehiculo</th>
                    <th>Observaciones</th>
                    <th>Total Venta</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" style="height: 400px;" id="formularioregistros">
                <form action="" name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-4 col-md-4 col-xs-12">
                    <label for="">Cliente(*):</label>
                    <input class="form-control" type="hidden" name="idventa" id="idventa">
                    <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required>

                    </select>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-xs-12">
                    <label for="">Fecha(*): </label>
                    <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required readonly>
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-xs-12">
                    <label for="">Tipo Comprobante(*): </label>
                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                      <option value="Factura">Factura</option>
                      <option value="Albaran">Albaran</option>
                    </select>
                  </div>

                  <!-- Seleccionar la disponibilidad (Abrir modal) -->
                  <!-- <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a data-toggle="modal" href="#modalDisponibilidad">
                      <button onclick="mostrarModalDisponibilidad()" id="btnAgregarDisponibilidad" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Disponibilidad</button>
                    </a>
                  </div> -->
                  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a data-toggle="modal" href="#modalSeleccionDisponibilidad">
                      <button id="btnAgregarDisponibilidad" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Disponibilidad</button>
                    </a>
                  </div>

                  <!-- DataTable -->
                  <div class="form-group col-lg-12 col-md-12 col-xs-12">
                    <table id="detalleSeleccionDisponibilidad" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>Provincia</th>
                        <th>Ciudad</th>
                        <th>Tipo de vehiculo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <!--  -->
                  <!--  -->

                  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a data-toggle="modal" href="#myModal">
                      <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Materiales</button>
                    </a>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>Materiales</th>
                        <th>Cantidad</th>
                        <th>Precio Venta</th>
                        <th>Descuento</th>
                        <th>Subtotal</th>
                      </thead>
                      <tfoot>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                          <h4 id="total"> €. 0.00</h4><input type="hidden" name="total_venta" id="total_venta">
                        </th>
                      </tfoot>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                    <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                </form>
              </div>
              <!--fin centro-->
            </div>
          </div>
        </div>
        <!-- /.box -->

      </section>
      <!-- /.content -->
    </div>

    <!-- Modal Disponibilidad -->
    <!-- <div class="modal fade" id="modalDisponibilidad" tabindex="-1" role="dialog" aria-labelledby="modalDisponibilidadLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione la disponibilidad</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-xs-6 col-md-4">
                <div class="form-group">
                  <label for="formControlSelect1">Seleccione una provincia</label>
                  <select onchange="getIdProvincia(this);" id="selectProvincia" class="form-control">
                  </select>
                </div>
              </div>
              <div class="col-xs-6 col-md-4">
                <div class="form-group">
                  <label for="formControlSelect2">Seleccione una ciudad</label>
                  <select onchange="getIdCiudad(this);" class="form-control" id="selectCiudad">
                  </select>
                </div>
              </div>
              <div class="col-xs-6 col-md-4">
                <div class="form-group">
                  <label for="formControlSelect2">Seleccione un tipo de vehiculo</label>
                  <select onchange="getIdTipoVehiculo(this);" class="form-control" id="selectTipoVehiculo">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6">
                <label for="">Fecha:</label>
                <label id="labelFechaDisponible" for=""></label>
              </div>
              <div class="col-xs-6">
                <label for="">Hora:</label>
                <label id="labelHoraDisponible" for=""></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-12">
                <button type="button" id="agregarDisponibilidad" class="btn btn-primary btn-sm">Agregar</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div> -->
    <!-- Fin Modal Disponibilidad -->

    <!--Modal-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione los Materiales</h4>
          </div>
          <div class="modal-body">
            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Codigo</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Imagen</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Codigo</th>
                <th>Stock</th>
                <th>Precio Venta</th>
                <th>Imagen</th>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- fin Modal-->

    <!-- Modal Seleccion Disponibilidad -->
    <div class="modal fade" id="modalSeleccionDisponibilidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Seleccione los horarios de disponibilidad</h4>
          </div>
          <div class="modal-body">
            <table id="tableSeleccionDisponibilidad" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <th>Opciones</th>
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
                <th>Provincia</th>
                <th>Ciudad</th>
                <th>Tipo de vehiculo</th>
                <th>Fecha disponible</th>
                <th>Hora disponible</th>
                <th>Estado</th>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin Modal Seleccion Disponibilidad-->
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script src="scripts/venta.js"></script>
<?php
}

ob_end_flush();
?>