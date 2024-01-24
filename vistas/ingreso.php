<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['compras'] == 1) {
?>

    <style>
      @media (max-width: 991px) {

        .label_fecha,
        .label_numero,
        .label_impuesto,
        .label_serie {
          width: 100px !important;
        }
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
                <h1 class="box-title">Ingresos
                  <a data-toggle="modal" href="#myModal2">
                    <button type="button" class="btn btn-secondary" style="color: black !important;" onclick="limpiar()"> <span class="fa fa-plus-circle"></span> Agregar</button>
                  </a>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin") { ?>
                    <a href="../reportes/rptingresos.php" target="_blank">
                      <button class="btn btn-secondary" style="color: black !important;">
                        <i class="fa fa-clipboard"></i> Reporte
                      </button>
                    </a>
                  <?php } ?>
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
                  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                    <thead>
                      <th style="width: 12%;">Opciones</th>
                      <th>Proveedor</th>
                      <th>Ubicación del local</th>
                      <th>Método de pago</th>
                      <th>Documento</th>
                      <th>Número Doc.</th>
                      <th>Total Compra</th>
                      <th>Agregado por</th>
                      <th>Fecha y hora</th>
                      <th>Estado</th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <th>Opciones</th>
                      <th>Proveedor</th>
                      <th>Ubicación del local</th>
                      <th>Método de pago</th>
                      <th>Documento</th>
                      <th>Número Doc.</th>
                      <th>Total Compra</th>
                      <th>Agregado por</th>
                      <th>Fecha y hora</th>
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

    <!-- Modal 2 -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 80%; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Registrar ingreso:</h4>
          </div>
          <div class="panel-body" id="formularioregistros">
            <form name="formulario" id="formulario" method="POST">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Local(*):</label>
                <select id="idalmacen" name="idalmacen" class="form-control selectpicker idalmacen" data-live-search="true" data-size="5" onchange="actualizarRUC()" required>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">RUC local(*):</label>
                <input type="number" class="form-control" id="local_ruc" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Proveedor(*):</label>
                <input type="hidden" name="idingreso" id="idingreso">
                <select id="idproveedor" name="idproveedor" class="form-control selectpicker" data-live-search="true" required>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Tipo Comp(*):</label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                  <option value="Boleta">Boleta</option>
                  <option value="Factura">Factura</option>
                  <option value="Ticket">Ticket</option>
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label class="label_serie" style="width: 110px;">Serie(*):</label>
                <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="10" placeholder="Serie" onblur="convertirMayus(this)" required>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label class="label_numero" style="width: 80px;">Número(*):</label>
                <input type="text" class="form-control" name="num_comprobante" id="num_comprobante" oninput="onlyNumbersAndMaxLenght(this)" maxlength="10" placeholder="Número" required />
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label class="label_impuesto" style="width: 80px;">Impuesto:</label>
                <select name="impuesto" id="impuesto" class="form-control selectpicker" onchange="modificarSubototales();" required>
                  <option value="0">0</option>
                  <option value="18">18</option>
                </select>
              </div>

              <div class="form-group col-lg-12 col-md-12 col-sm-12" style="margin: 0;">
                <hr>
              </div>

              <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="float: right;">
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
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th>Precio compra</th>
                    <th>Precio venta</th>
                    <th>Subtotal</th>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>IGV</th>
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
                      <th>
                        <h4 id="total">S/. 0.00</h4><input type="hidden" name="total_compra" id="total_compra">
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
    <!-- Fin modal 2 -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 85% !important;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Seleccione un Artículo</h4>
          </div>
          <div class="modal-body table-responsive">
            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
              <thead>
                <th>Opciones</th>
                <th>IMAGEN</th>
                <th>NOMBRE</th>
                <th>U. MEDIDA</th>
                <th>CATEGORÍA</th>
                <th>MARCA</th>
                <th>UBICACIÓN DEL LOCAL</th>
                <th>PESO</th>
                <th>TALLA</th>
                <th>COLOR</th>
                <th>POSICIÓN DE OBJETO</th>
                <th>C. PRODUCTO</th>
                <th>C. BARRA</th>
                <th>STOCK NORMAL</th>
                <th>STOCK MÍNIMO</th>
                <th>PRECIO DE COMPRA</th>
                <th>PRECIO DE VENTA</th>
                <th>AGREGADO POR</th>
                <th>ESTADO</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>IMAGEN</th>
                <th>NOMBRE</th>
                <th>U. MEDIDA</th>
                <th>CATEGORÍA</th>
                <th>MARCA</th>
                <th>UBICACIÓN DEL LOCAL</th>
                <th>PESO</th>
                <th>TALLA</th>
                <th>COLOR</th>
                <th>POSICIÓN DE OBJETO</th>
                <th>C. PRODUCTO</th>
                <th>C. BARRA</th>
                <th>STOCK NORMAL</th>
                <th>STOCK MÍNIMO</th>
                <th>PRECIO DE COMPRA</th>
                <th>PRECIO DE VENTA</th>
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
  <script type="text/javascript" src="scripts/ingreso22.js"></script>
<?php
}
ob_end_flush();
?>