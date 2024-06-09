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
                <h1 class="box-title">
                  Pagos por crédito
                  <button class="btn btn-secondary" id="btnagregar" onclick="mostrarform(true); bloquearPrecios();">
                    <i class="fa fa-plus-circle"></i> Agregar
                  </button>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin" || $_SESSION["cargo"] == "encargado" || $_SESSION["cargo"] == "vendedor") { ?>
                    <a href="../reportes/rptcuotas.php" target="_blank">
                      <button class="btn btn-secondary" style="color: black !important;">
                        <i class="fa fa-clipboard"></i> Reporte
                      </button>
                    </a>
                  <?php } ?>
                  <button class="btn btn-secondary" id="btndetalle" onclick="irDetalle()">
                    <i class="fa fa-sliders"></i> Ir a detalles
                  </button>
                  <a href="articulo_form.php"><button style="color: black !important;" class="btn btn-secondary" id="btnagregar"><i class="fa fa-cart-plus"></i> Agregar artículos</button></a>
                </h1>
                <div class="box-tools pull-right">
                </div>
                <div class="panel-body table-responsive listadoregistros" style="overflow-x: visible; padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
                  <div class="form-group col-lg-5 col-md-5 col-sm-6 col-xs-12">
                    <label>Fecha Inicial:</label>
                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
                  </div>
                  <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <label>Fecha Final:</label>
                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin">
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <label id="label">ㅤ</label>
                    <div style="display: flex; gap: 10px;">
                      <button style="width: 80%;" class="btn btn-secondary" onclick="buscar()">Buscar</button>
                      <button style="width: 20%; height: 32px" class="btn btn-secondary" onclick="listar()"><i class="fa fa-repeat"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel-body listadoregistros" style="background-color: #ecf0f5 !important; padding-left: 0 !important; padding-right: 0 !important; height: max-content;">
                <div class="table-responsive" style="padding: 8px !important; padding: 20px !important; background-color: white;">
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important; margin: 0 !important;">
                    <thead>
                      <th style="width: 12%;">Opciones</th>
                      <th>Agregado por</th>
                      <th>Cliente</th>
                      <th>Vendedor</th>
                      <th>Fecha Creación</th>
                      <th>Fecha Anulación</th>
                      <th>Ubicación del local</th>
                      <th>Zona de entrega</th>
                      <th>Método de pago</th>
                      <th>Documento</th>
                      <th>Número Doc.</th>
                      <th>M. de Compra (S/.)</th>
                      <th>M. pagado (S/.)</th>
                      <th>Estado</th>
                      <th>Detalles</th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <th>Opciones</th>
                      <th>Agregado por</th>
                      <th>Cliente</th>
                      <th>Vendedor</th>
                      <th>Fecha Creación</th>
                      <th>Fecha Anulación</th>
                      <th>Ubicación del local</th>
                      <th>Zona de entrega</th>
                      <th>Método de pago</th>
                      <th>Documento</th>
                      <th>Número Doc.</th>
                      <th>M. de Compra (S/.)</th>
                      <th>M. pagado (S/.)</th>
                      <th>Estado</th>
                      <th>Detalles</th>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Usuario Cliente(*):</label>
                    <input type="hidden" name="idcuotas" id="idcuotas">
                    <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Usuario Vendedor(*):</label>
                    <select id="idvendedor" name="idvendedor" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                    </select>
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Ubicación del local(*):</label>
                    <select id="idalmacen" name="idalmacen" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Zona de entrega(*):</label>
                    <select id="idzona" name="idzona" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                    </select>
                  </div>

                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Tipo Comprobante(*):</label>
                    <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                      <option value="Nota de venta a crédito">Nota de venta a crédito</option>
                      <option value="Factura">Factura</option>
                      <option value="Ticket">Ticket</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label>Serie(*):</label>
                    <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="10" placeholder="Serie" onblur="convertirMayus(this)" required>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                    <label>Número(*):</label>
                    <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" maxlength="11" placeholder="Número" oninput="onlyNumbers(this)" onblur="formatearNumero(this)" required />
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label>Impuesto(*):</label>
                    <select name="impuesto" id="impuesto" class="form-control selectpicker" onchange="modificarSubototales();" required>
                      <option value="0">0</option>
                      <option value="18">18</option>
                    </select>
                  </div>

                  <div class="form-group col-lg-12 col-md-12 col-sm-12" style="margin: 0;">
                    <hr>
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" id="form_codigo_barra" style="float: right;">
                    <label style="float: left;">Método de pago:</label>
                    <select id="idmetodopago" name="idmetodopago" class="form-control selectpicker" data-size="6" data-live-search="true">
                      <option value="">- Seleccione -</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12" style="float: left; margin-top: 20px;">
                    <a data-toggle="modal" href="#myModal">
                      <button id="btnAgregarArt" type="button" class="btn btn-secondary" style="color: black !important"> <span class="fa fa-plus"></span> Agregar artículos</button>
                    </a>
                  </div>

                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important; margin: 0 !important;">
                      <thead style="background-color:#A9D0F5">
                        <th>Opciones</th>
                        <th>Artículo</th>
                        <th>Stock</th>
                        <th>Cantidad</th>
                        <th>Precio compra</th>
                        <th>Precio venta</th>
                        <th>Descuento</th>
                        <th>Subtotal</th>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>IGV</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>
                            <h4 id="igv">S/. 0.00</h4><input type="hidden" name="total_igv" id="total_igv">
                          </th>
                        </tr>
                        <tr>
                          <th>TOTAL</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th>
                            <h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta">
                          </th>
                        </tr>
                      </tfoot>
                      <tbody>

                      </tbody>
                    </table>
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

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 85% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Seleccione un Artículo</h4>
          </div>
          <div class="modal-body table-responsive">
            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important; margin: 0 !important;">
              <thead>
                <th>Opciones</th>
                <th>IMAGEN</th>
                <th>NOMBRE</th>
                <th>STOCK NORMAL</th>
                <th>STOCK MÍNIMO</th>
                <th>UBICACIÓN DEL LOCAL</th>
                <th>PRECIO DE COMPRA</th>
                <th>PRECIO DE VENTA</th>
                <th>C. PRODUCTO</th>
                <th>C. BARRA</th>
                <th>U. MEDIDA</th>
                <th>CATEGORÍA</th>
                <th>MARCA</th>
                <th>PESO</th>
                <th>TALLA</th>
                <th>COLOR</th>
                <th>POSICIÓN DE OBJETO</th>
                <th>AGREGADO POR</th>
                <th>ESTADO</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>IMAGEN</th>
                <th>NOMBRE</th>
                <th>STOCK NORMAL</th>
                <th>STOCK MÍNIMO</th>
                <th>UBICACIÓN DEL LOCAL</th>
                <th>PRECIO DE COMPRA</th>
                <th>PRECIO DE VENTA</th>
                <th>C. PRODUCTO</th>
                <th>C. BARRA</th>
                <th>U. MEDIDA</th>
                <th>CATEGORÍA</th>
                <th>MARCA</th>
                <th>PESO</th>
                <th>TALLA</th>
                <th>COLOR</th>
                <th>POSICIÓN DE OBJETO</th>
                <th>AGREGADO POR</th>
                <th>ESTADO</th>
              </tfoot>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal -->
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/cuotas39.js"></script>
<?php
}
ob_end_flush();
?>