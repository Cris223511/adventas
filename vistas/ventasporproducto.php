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
                <h1 class="box-title">Reporte de Ventas por Producto</h1>
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
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead>
                    <th>Opciones</th>
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

    <!-- Modal 2 -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 80%; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Artículos de la venta:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario" id="formulario" method="POST">
              <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                <table id="tbllistado2" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead>
                    <th>Usuario Vendedor</th>
                    <th>Cliente</th>
                    <th>Foto</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Precio venta</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                    <th>Impuesto</th>
                    <th>Total Venta</th>
                    <th>Método de pago</th>
                    <th>Comprobante</th>
                    <th>Número Doc.</th>
                    <th>Stock</th>
                    <th>Estado venta</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Usuario Vendedor</th>
                    <th>Cliente</th>
                    <th>Foto</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Precio venta</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                    <th>Impuesto</th>
                    <th>Total Venta</th>
                    <th>Método de pago</th>
                    <th>Comprobante</th>
                    <th>Número Doc.</th>
                    <th>Stock</th>
                    <th>Estado venta</th>
                  </tfoot>
                </table>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button id="btnCancelar" class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cerrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 2 -->
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/ventasporproducto.js"></script>
<?php
}
ob_end_flush();
?>