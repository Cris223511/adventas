<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['reporte'] == 1) {
?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Reporte de Ventas por Usuario</h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div id="idusuarioSesion" style="display: none;"><?php echo $_SESSION['idusuario'] ?></div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Inicio:</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Fin:</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Usuario Vendedor:</label>
                  <select name="idusuario" id="idusuario" class="form-control selectpicker" data-live-search="true" required></select>
                </div>
                <div class="row">
                  <div class="col-12"></div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                  <div class="col-md-12">
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventasusuariofecha()">Ventas entre el rango de fecha</button>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listarventasusuario()">Ventas del usuario por fecha</button>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventasusuario()">Todas las ventas del usuario</button>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventasusuariousuarios()">Todas las ventas de todos los usuarios</button>
                  </div>
                </div>
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead>
                    <th>Fecha</th>
                    <th>Usuario Vendedor</th>
                    <th>Cliente</th>
                    <th>Método de pago</th>
                    <th>Comprobante</th>
                    <th>Número Doc.</th>
                    <th>Total Venta</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                      <h4 id="total">S/. 0.00</h4>
                    </th>
                    <th></th>
                    <th></th>
                  </tfoot>
                </table>
              </div>

            </div>
          </div>
        </div>
      </section>

    </div>
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/ventasfechausuario4.js"></script>
<?php
}
ob_end_flush();
?>