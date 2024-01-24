<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require '../config/Conexion.php';
  require 'header.php';

  if ($_SESSION['proforma'] == 1) {
?>

    <style>
      @media (max-width: 991px) {

        .label_fecha,
        .label_serie,
        .label_numero,
        .label_impuesto,
        .lavel_proforma {
          width: 100px !important;
        }

        tbody .popover {
          z-index: 10000 !important;
          width: 190px !important;
        }
      }
    </style>

    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Registro de proformas
                  <a data-toggle="modal" href="#myModal2">
                    <button type="button" class="btn btn-secondary" style="color: black !important;" onclick="limpiar();">
                      <span class="fa fa-plus-circle"></span> Agregar
                    </button>
                  </a>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin") { ?>
                    <a href="../reportes/rptproformas.php" target="_blank">
                      <button class="btn btn-secondary" style="color: black !important;">
                        <i class="fa fa-clipboard"></i> Reporte
                      </button>
                    </a>
                  <?php } ?>
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead>
                    <th style="width: 12%;">Opciones</th>
                    <th>Cliente</th>
                    <th>Ubicación del local</th>
                    <th>Método de pago</th>
                    <th>Documento</th>
                    <th>Número Doc.</th>
                    <th>Total Venta</th>
                    <th>Agregado por</th>
                    <th>Fecha y hora</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Cliente</th>
                    <th>Ubicación del local</th>
                    <th>Método de pago</th>
                    <th>Documento</th>
                    <th>Número Doc.</th>
                    <th>Total Venta</th>
                    <th>Agregado por</th>
                    <th>Fecha y hora</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Modal 3 -->
    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 80%; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Enviar proforma: <a href="#" data-toggle="popover" data-placement="bottom" data-html="true" title="Enviar proforma" data-content="Al enviar la proforma, todos los datos pasarán a registrarse en el módulo de ventas (incluído sus productos registrados) y también, el estado de la proforma cambiará a <strong>Enviado</strong>." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></h4>
          </div>
          <div class="panel-body">
            <form name="formulario" id="formulario2" method="POST">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Cliente(*):</label>
                <input type="hidden" name="idproforma" id="idproforma2">
                <select id="idcliente2" name="idcliente" class="form-control selectpicker" data-live-search="true" disabled>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Tipo Comp(*):</label>
                <select name="tipo_comprobante" id="tipo_comprobante2" class="form-control selectpicker" disabled>
                  <option value="Boleta">Boleta</option>
                  <option value="Factura">Factura</option>
                  <option value="Ticket">Ticket</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Local(*):</label>
                <select id="idalmacen2" name="idalmacen" class="form-control selectpicker idalmacen2" data-live-search="true" data-size="5" onchange="actualizarRUC2()" disabled>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">RUC local(*):</label>
                <input type="number" class="form-control" id="local_ruc2" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label class="label_serie" style="width: 110px;">Serie(*):</label>
                <input type="text" class="form-control" name="serie_comprobante" placeholder="Serie" onblur="convertirMayus(this)" id="serie_comprobante2" readonly>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Proforma(*):</label>
                <input type="text" class="form-control" name="num_proforma" id="num_proforma2" oninput="onlyNumbersAndMaxLenght(this)" maxlength="10" placeholder="proforma" readonly>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Impuesto:</label>
                <select name="impuesto" id="impuesto2" class="form-control selectpicker" disabled>
                  <option value="0">0</option>
                  <option value="18">18</option>
                </select>
              </div>

              <div class="form-group col-lg-12 col-md-12 col-sm-12" style="margin: 0;">
                <hr>
              </div>

              <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="float: right;">
                <label style="float: left;">Método de pago:</label>
                <select id="idmetodopago2" name="idmetodopago" class="form-control selectpicker" data-size="6" data-live-search="true">
                  <option value="">- Seleccione -</option>
                </select>
              </div>

              <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive">
                <table id="detalles2" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
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
                      <th>
                        <h4 id="igv2">S/. 0.00</h4><input type="hidden" name="total_igv" id="total_igv2">
                      </th>
                    </tr>
                    <tr>
                      <th>TOTAL</th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>
                        <h4 id="total2">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta2">
                      </th>
                    </tr>
                  </tfoot>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardar3"><i class="fa fa-save"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 3 -->

    <!-- Modal 2 -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 80%; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Registrar proforma:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario" id="formulario" method="POST">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Local(*):</label>
                <select id="idalmacen" name="idalmacen" class="form-control selectpicker idalmacen" data-live-search="true" data-size="5" onchange="actualizarRUC(); actualizarPersonales(this.value);" required>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">RUC local(*):</label>
                <input type="number" class="form-control" id="local_ruc" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Cliente(*):</label>
                <input type="hidden" name="idproforma" id="idproforma">
                <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" required>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Tipo Comp(*):</label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                  <option value="Nota de venta">Nota de venta</option>
                  <option value="Factura">Factura</option>
                  <option value="Ticket">Ticket</option>
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label class="label_serie" style="width: 110px;">Serie(*):</label>
                <input type="text" class="form-control" name="serie_comprobante" id="serie_comprobante" maxlength="10" placeholder="Serie" onblur="convertirMayus(this)" required>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Proforma(*):</label>
                <input type="text" class="form-control" name="num_proforma" id="num_proforma" oninput="onlyNumbersAndMaxLenght(this)" maxlength="10" placeholder="proforma">
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

              <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" id="form_codigo_barra" style="float: right;">
                <label style="float: left;">Buscar por código de barra: <a href="#" data-toggle="popover" data-placement="top" title="Buscar por código de barra" data-content="Sólo se listan los productos que no están en stock." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></label>
                <select id="idproducto" name="idproducto" class="form-control selectpicker" data-size="6" data-live-search="true" onchange="llenarTabla()">
                  <option value="">Busca un producto.</option>
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="float: right;">
                <label style="float: left;">Método de pago:</label>
                <select id="idmetodopago" name="idmetodopago" class="form-control selectpicker" data-size="6" data-live-search="true">
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-2 col-md-2 col-sm-8 col-xs-12" style="float: left; margin-top: 20px;">
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
                        <h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta">
                      </th>
                    </tr>
                  </tfoot>
                  <tbody>

                  </tbody>
                </table>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardar2"><i class="fa fa-save"></i> Guardar</button>
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
  <script type="text/javascript" src="scripts/proformas19.js"></script>
<?php
}
ob_end_flush();
?>