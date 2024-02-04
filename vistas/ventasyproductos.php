<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['reporteP'] == 1) {
?>
    <style>
      td {
        height: 30.84px !important;
      }
    </style>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Reporte de Ventas y Productos</h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros" style="overflow-x: visible; padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
                <div id="idusuarioSesion" style="display: none;"><?php echo $_SESSION['idusuario'] ?></div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Inicio:</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Fin:</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Cliente:</label>
                  <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required></select>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Usuario Vendedor:</label>
                  <select name="idusuario" id="idusuario" class="form-control selectpicker" data-live-search="true" required></select>
                </div>
                <div class="row">
                  <div class="col-12"></div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
                    <div class="box-header with-border">
                      <h1 class="box-title">General</h1>
                      <div class="box-tools pull-right">
                      </div>
                    </div>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventasfecha()">Ventas entre el rango de fecha</button>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventasclientes()">Todas las ventas</button>
                  </div>
                  <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
                    <div class="box-header with-border">
                      <h1 class="box-title">Clientes</h1>
                      <div class="box-tools pull-right">
                      </div>
                    </div>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listarventas()">Ventas del cliente por fecha</button>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventas()">Todas las ventas del cliente</button>
                  </div>
                  <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
                    <div class="box-header with-border">
                      <h1 class="box-title">Usuarios</h1>
                      <div class="box-tools pull-right">
                      </div>
                    </div>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listarventasusuario()">Ventas del usuario por fecha</button>
                    <button style="margin-top: 10px; margin-left: 10px; float: left;" class="btn btn-secondary" onclick="listartodasventasusuario()">Todas las ventas del usuario</button>
                  </div>
                </div>
              </div>
              <div class="panel-body listadoregistros" style="background-color: #ecf0f5 !important; padding-left: 0 !important; padding-right: 0 !important; height: max-content;">
                <div class="table-responsive" style="padding: 8px !important; padding: 20px !important; background-color: white;">
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                    <thead>
                      <th>Fecha</th>
                      <th>Comprobante</th>
                      <th>Número Doc.</th>
                      <th>Ubicación del local</th>
                      <th>Usuario Vendedor</th>
                      <th>Cliente</th>
                      <th>Artículo</th>
                      <th>Cantidad</th>
                      <th>Precio Venta (S/.)</th>
                      <th>Subtotal (S/.)</th>
                      <th>Estado</th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <th>Fecha</th>
                      <th>Comprobante</th>
                      <th>Número Doc.</th>
                      <th>Ubicación del local</th>
                      <th>Usuario Vendedor</th>
                      <th>Cliente</th>
                      <th>Artículo</th>
                      <th>Cantidad</th>
                      <th>Precio Venta (S/.)</th>
                      <th>Subtotal (S/.)</th>
                      <th>Estado</th>
                    </tfoot>
                  </table>
                </div>
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
  <script type="text/javascript" src="scripts/ventasyproductos.js"></script>
<?php
}
ob_end_flush();
?>