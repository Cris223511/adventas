<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['consultau'] == 1) {
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
                <h1 class="box-title">Productos más vendidos
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin") { ?>
                    <a href="../reportes/rptproductosmasvendido.php" target="_blank"><button class="btn btn-secondary" style="color: black !important;"><i class="fa fa-clipboard"></i> Reporte</button></a>
                  <?php } ?>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Ubicación del local</th>
                    <th>Código de producto</th>
                    <th>Stock normal</th>
                    <th>Imagen</th>
                    <th>Veces vendidos</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Ubicación del local</th>
                    <th>Código de producto</th>
                    <th>Stock normal</th>
                    <th>Imagen</th>
                    <th>Veces vendidos</th>
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
  <script type="text/javascript" src="scripts/productosmasvendido3.js"></script>
<?php
}
ob_end_flush();
?>