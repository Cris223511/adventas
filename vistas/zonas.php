<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  require '../config/Conexion.php';

  if ($_SESSION['cuotas'] == 1) {
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
                <h1 class="box-title">Zonas y ubicaciones
                  <button class="btn btn-secondary" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin") { ?>
                    <a href="../reportes/rptzonas.php" target="_blank"><button class="btn btn-secondary" style="color: black !important;"><i class="fa fa-clipboard"></i> Reporte</button></a>
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
                    <th>Opciones</th>
                    <th style="width: 40%; min-width: 280px; white-space: nowrap;">Ubicación</th>
                    <th>Zona</th>
                    <th>Agregado por</th>
                    <th>Fecha y hora</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Ubicación</th>
                    <th>Zona</th>
                    <th>Agregado por</th>
                    <th>Fecha y hora</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Ubicación(*):</label>
                    <input type="hidden" name="idzona" id="idzona">
                    <input type="text" class="form-control" name="ubicacion" id="ubicacion" maxlength="50" placeholder="Ubicación" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Zona(*):</label>
                    <input type="text" class="form-control" name="zona" id="zona" maxlength="50" placeholder="Zona A, Zona 1, Zona A1..." required>
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button id="btnCancelar" class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                    <button class="btn btn-secondary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                  </div>
                </form>
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
  <script type="text/javascript" src="scripts/zonas17.js"></script>
<?php
}
ob_end_flush();
?>