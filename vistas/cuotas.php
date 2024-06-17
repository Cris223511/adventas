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
      .caja1 .contenedor {
        text-align: center;
      }

      @media (max-width: 991px) {

        .label_fecha,
        .label_numero,
        .label_impuesto,
        .label_serie {
          width: 100px !important;
        }
      }

      td {
        height: 30.84px !important;
      }

      @media (max-width: 1198px) {
        .label_input {
          width: 100px !important;
        }
      }

      @media (max-width: 991px) {
        .caja1 {
          padding-right: 0 !important;
        }

        .caja1 .contenedor img {
          width: 30% !important;
        }
      }

      @media (max-width: 767px) {
        .botones {
          width: 100% !important;
        }
      }

      #camera video {
        width: 250px;
        height: auto;
      }

      #camera canvas.drawingBuffer {
        width: 250px;
        height: auto;
        position: absolute;
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
                  <!-- <a href="articulo_form.php"><button style="color: black !important;" class="btn btn-secondary"><i class="fa fa-cart-plus"></i> Agregar artículos</button></a> -->
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

                  <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <hr style="margin-bottom: 0; margin-top: 10px;">
                  </div>

                  <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" id="form_codigo_barra" style="float: right;">
                    <label style="float: left;">Método de pago:</label>
                    <select id="idmetodopago" name="idmetodopago" class="form-control selectpicker" data-size="6" data-live-search="true">
                      <option value="">- Seleccione -</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: left; margin-top: 20px;">
                    <div style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 10px;">
                      <a data-toggle="modal" href="#myModal">
                        <button id="btnAgregarArt" type="button" class="btn btn-secondary" style="color: black !important"> <span class="fa fa-plus"></span> Agregar artículo</button>
                      </a>
                      <a data-toggle="modal" href="#myModal3">
                        <button id="btnCrearArt" type="button" class="btn btn-secondary" style="color: black !important"> <span class="fa fa-cart-plus"></span> Crear artículo</button>
                      </a>
                    </div>
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

                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px; margin-bottom: 0px;">
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

    <!-- Form categoría -->
    <form name="formularioCategoria" id="formularioCategoria" method="POST" style="display: none;">
      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label>Nombre(*):</label>
        <input type="hidden" name="idcategoria" id="idcategoria2">
        <input type="text" class="form-control" name="nombre" id="nombre2" maxlength="50" placeholder="Nombre" required>
      </div>
      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label>Descripción:</label>
        <input type="text" class="form-control" name="descripcion" id="descripcion2" maxlength="256" placeholder="Descripción">
      </div>
    </form>
    <!-- Fin form categoría -->

    <!-- Form marcas -->
    <form name="formularioMarcas" id="formularioMarcas" method="POST" style="display: none;">
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Marca(*):</label>
        <input type="hidden" name="idmarcas" id="idmarcas3">
        <input type="text" class="form-control" name="nombre" id="nombre3" maxlength="50" placeholder="Nombre de la marca" required>
      </div>
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Descripción:</label>
        <textarea type="text" class="form-control" name="descripcion" id="descripcion3" maxlength="150" rows="4" placeholder="Descripción"></textarea>
      </div>
    </form>
    <!-- Fin form marcas -->

    <!-- Form medidas -->
    <form name="formularioMedidas" id="formularioMedidas" method="POST" style="display: none;">
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Medida(*):</label>
        <input type="hidden" name="idmedida" id="idmedida4">
        <input type="text" class="form-control" name="nombre" id="nombre4" maxlength="50" placeholder="Nombre de la medida" required>
      </div>
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Descripción:</label>
        <textarea type="text" class="form-control" name="descripcion" id="descripcion4" maxlength="150" rows="4" placeholder="Descripción"></textarea>
      </div>
    </form>
    <!-- Fin form medidas -->

    <!-- Modal 3 -->
    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
      <div class="modal-dialog" style="width: 95% !important; max-height: 95vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Crear nuevo artículo:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario2" id="formulario2" method="POST" enctype="multipart/form-data">
              <div class="form-group col-lg-2 col-md-3 col-sm-12 caja1" style="padding-left: 0 !important; padding-right: 20px;">
                <div class="contenedor" style="background-color: white; border-top: 3px #3d3f3f solid; padding: 10px 20px 20px 20px;">
                  <label>Imagen de muestra:</label>
                  <div>
                    <img src="" width="100%" id="imagenmuestra" style="display: none;">
                  </div>
                </div>
              </div>
              <div class="form-group col-lg-10 col-md-9 col-sm-12 caja2" style="background-color: white; border-top: 3px #3d3f3f solid; padding: 20px; margin: 0;">
                <div class="form-group col-lg-12 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label class="label_input" style="width: 90px;">Nombre(*):</label>
                  <input type="hidden" name="idarticulo" id="idarticulo">
                  <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Local(*):</label>
                  <select id="idalmacen2" name="idalmacen" class="form-control selectpicker" data-live-search="true" data-size="5" onchange="actualizarRUC2()" required>
                    <option value="">- Seleccione -</option>
                  </select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">RUC local(*):</label>
                  <input type="number" class="form-control" id="local_ruc2" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Categoría(*):</label>
                  <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true" data-size="5" required></select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Marca(*):</label>
                  <select id="idmarcas" name="idmarcas" class="form-control selectpicker" data-live-search="true" data-size="5" required></select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Stock(*):</label>
                  <input type="number" class="form-control" name="stock" id="stock" onkeydown="evitarNumerosNegativos(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" min="0" placeholder="Stock" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Stock mínimo(*):</label>
                  <input type="number" class="form-control" name="stock_minimo" id="stock_minimo" onkeydown="evitarNumerosNegativos(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" min="1" placeholder="Stock mínimo" required>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label class="label_input" style="width: 115px;">Precio compra(*):</label>
                  <input type="number" class="form-control" name="precio_compra" id="precio_compra" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); changeGanancia();" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" placeholder="Ingrese el precio de compra." required>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Precio venta(*):</label>
                  <input type="number" class="form-control" name="precio_venta" id="precio_venta" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); changeGanancia();" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" placeholder="Ingrese el precio de venta." required>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 100px;">Ganancia(*):</label>
                  <input type="number" class="form-control" name="ganancia" id="ganancia" step="any" placeholder="Ganancia total." disabled required>
                </div>
                <div class="form-group col-lg-12 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label class="label_input" style="width: 90px;">Descripción:</label>
                  <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del artículo" autocomplete="off">
                </div>
                <div class="form-group col-lg-12 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label class="label_input" style="width: 90px;">Imagen:</label>
                  <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg">
                  <input type="hidden" name="imagenactual" id="imagenactual">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                  <div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Código del producto(*):</label>
                    <input type="text" class="form-control" name="codigo_producto" id="codigo_producto" maxlength="13" placeholder="Código del producto" onblur="convertirMayus(this)" required>
                  </div>
                  <div style="display: flex; justify-content: end;">
                    <div id="camera"></div>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                  <div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Código de barra(*):</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" maxlength="18" placeholder="Código de barra">
                  </div>
                  <div style="margin-top: 10px; display: flex; gap: 5px; flex-wrap: wrap;">
                    <button class="btn btn-info" type="button" onclick="generar()">Generar</button>
                    <button class="btn btn-warning" type="button" onclick="imprimir()">Imprimir</button>
                    <button class="btn btn-danger" type="button" onclick="borrar()">Borrar</button>
                    <button class="btn btn-success btn1" type="button" onclick="escanear()">Escanear</button>
                    <button class="btn btn-danger btn2" type="button" onclick="detenerEscaneo()">Detener</button>
                  </div>
                  <div id="print" style="overflow-y: hidden;">
                    <img id="barcode">
                  </div>
                </div>
                <div class="form-group col-lg-12 col-md-12" style="display: flex; justify-content: center;">
                  <button class="btn btn-success" type="button" id="btnDetalles1" onclick="frmDetalles(true)"><i class="fa fa-plus"></i> Más detalles</button>
                  <button class="btn btn-danger" type="button" id="btnDetalles2" onclick="frmDetalles(false)"><i class="fa fa-minus"></i> Cerrar</button>
                </div>
                <!-- form detalles -->
                <div id="frmDetalles" class="col-lg-12 col-md-12" style="margin: 0 !important; padding: 0 !important;">
                  <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label>Talla:</label>
                    <input type="text" class="form-control" name="talla" id="talla" maxlength="5" placeholder="Ingrese la talla del producto." autocomplete="off">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label>Color:</label>
                    <input type="text" class="form-control" name="color" id="color" maxlength="30" placeholder="Ingrese el color del producto." autocomplete="off">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label>Posición de objeto:</label>
                    <input type="text" class="form-control" name="posicion" id="posicion" maxlength="30" placeholder="Ingrese la posición de objeto." autocomplete="off">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label>Unidad de medida:</label>
                    <select id="idmedida" name="idmedida" class="form-control selectpicker" data-live-search="true"></select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label>Peso:</label>
                    <input type="number" class="form-control" name="peso" id="peso" step="any" onkeydown="evitarNegativo(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" placeholder="Ingrese el peso.">
                  </div>
                </div>
                <!-- end form detalles -->
              </div>
              <div class="form-group col-lg-10 col-md-8 col-sm-12 botones" style="background-color: white !important; padding: 10px 10px 10px 0 !important; float: right; margin: 0px;">
                <div style="float: right;">
                  <button class="btn btn-secondary" onclick="cancelarform2()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  <button class="btn btn-secondary" type="submit" id="btnGuardar2"><i class="fa fa-save"></i> Guardar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 3 -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 95% !important; max-height: 95vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
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
  <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/cuotas39.js"></script>
<?php
}
ob_end_flush();
?>