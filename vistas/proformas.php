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
      .caja1 .contenedor {
        text-align: center;
      }

      @media (max-width: 1198px) {
        .label_input {
          width: 100px !important;
        }
      }

      @media (max-width: 991px) {

        .label_fecha,
        .label_numero,
        .label_impuesto,
        .label_serie {
          width: 100px !important;
        }

        .caja1 {
          padding-left: 0 !important;
          padding-right: 0 !important;
        }

        .caja1 .contenedor {
          display: flex;
          flex-direction: column;
          justify-content: center;
          text-align: center;
          gap: 15px;
        }

        .caja1 .contenedor img {
          width: 25% !important;
        }

        .contenedor_articulos {
          display: flex;
          flex-direction: column-reverse !important;
        }
      }

      td {
        height: 30.84px !important;
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

    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Registro de proformas
                  <a data-toggle="modal" href="#myModal2">
                    <button type="button" class="btn btn-secondary" style="color: black !important;" onclick="limpiar(); bloquearPrecios();">
                      <span class="fa fa-plus-circle"></span> Agregar
                    </button>
                  </a>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin" || $_SESSION["cargo"] == "encargado") { ?>
                    <a href="../reportes/rptproformas.php" target="_blank">
                      <button class="btn btn-secondary" style="color: black !important;">
                        <i class="fa fa-clipboard"></i> Reporte
                      </button>
                    </a>
                  <?php } ?>
                  <!-- <a href="articulo_form.php"><button style="color: black !important;" class="btn btn-secondary"><i class="fa fa-cart-plus"></i> Agregar artículos</button></a> -->
                </h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important; margin: 0 !important;">
                  <thead>
                    <th style="width: 12%;">Opciones</th>
                    <th>Cliente</th>
                    <th>Ubicación del local</th>
                    <th>Método de pago</th>
                    <th>Documento</th>
                    <th>Número Doc.</th>
                    <th>Total Venta (S/.)</th>
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
                    <th>Total Venta (S/.)</th>
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

    <!-- Form categoría -->
    <form name="formularioCategoria" id="formularioCategoria" method="POST" style="display: none;">
      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label>Nombre(*):</label>
        <input type="hidden" name="idcategoria" id="idcategoria2">
        <input type="text" class="form-control" name="nombre" id="nombre2" maxlength="50" placeholder="Nombre" required>
      </div>
      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label>Descripción:</label>
        <input type="text" class="form-control" name="descripcion" id="descripcion2" maxlength="10000" placeholder="Descripción">
      </div>
    </form>
    <!-- Fin form categoría -->

    <!-- Form marcas -->
    <form name="formularioMarcas" id="formularioMarcas" method="POST" style="display: none;">
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Marca:</label>
        <input type="hidden" name="idmarcas" id="idmarcas3">
        <input type="text" class="form-control" name="nombre" id="nombre3" maxlength="50" placeholder="Nombre de la marca" required>
      </div>
      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label>Descripción:</label>
        <textarea type="text" class="form-control" name="descripcion" id="descripcion3" maxlength="10000" rows="4" placeholder="Descripción"></textarea>
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
        <textarea type="text" class="form-control" name="descripcion" id="descripcion4" maxlength="10000" rows="4" placeholder="Descripción"></textarea>
      </div>
    </form>
    <!-- Fin form medidas -->

    <!-- Modal 4 -->
    <div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
      <div class="modal-dialog" style="width: 95% !important; max-height: 95vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Crear nuevo artículo:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario5" id="formulario5" method="POST" enctype="multipart/form-data">
              <div class="contenedor_articulos">
                <div class="form-group col-lg-10 col-md-9 col-sm-12 caja2" style="background-color: white; border-top: 3px #3d3f3f solid; margin: 0; padding: 20px 0; padding-bottom: 0;">
                  <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label class="label_input" style="width: 100px;">Nombre(*):</label>
                    <input type="hidden" name="idarticulo" id="idarticulo">
                    <input type="text" class="form-control" name="nombre" id="nombre5" maxlength="100" placeholder="Nombre" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Unidad de medida(*):</label>
                    <select id="idmedida" name="idmedida" class="form-control selectpicker" data-live-search="true" required></select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Local(*):</label>
                    <select id="idalmacen6" name="idalmacen" class="form-control selectpicker" data-live-search="true" data-size="5" onchange="actualizarRUC6()" required>
                      <option value="">- Seleccione -</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">RUC local(*):</label>
                    <input type="number" class="form-control" id="local_ruc6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Categoría:</label>
                    <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true" data-size="5"></select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Marca:</label>
                    <select id="idmarcas" name="idmarcas" class="form-control selectpicker" data-live-search="true" data-size="5"></select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Stock:</label>
                    <input type="number" class="form-control" name="stock" id="stock" onkeydown="evitarNumerosNegativos(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" step="any" min="0" placeholder="Stock">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Stock mínimo:</label>
                    <input type="number" class="form-control" name="stock_minimo" id="stock_minimo" onkeydown="evitarNumerosNegativos(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" step="any" min="0" placeholder="Stock mínimo">
                  </div>
                  <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label class="label_input" style="width: 115px;">Precio compra(*):</label>
                    <input type="number" class="form-control" name="precio_compra" id="precio_compra" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); changeGanancia();" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" step="any" min="0.1" placeholder="Ingrese el precio de compra." required>
                  </div>
                  <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Precio venta(*):</label>
                    <input type="number" class="form-control" name="precio_venta" id="precio_venta" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); changeGanancia();" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" step="any" min="0.1" placeholder="Ingrese el precio de venta." required>
                  </div>
                  <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                    <label style="width: 100px;">Ganancia(*):</label>
                    <input type="number" class="form-control" name="ganancia" id="ganancia" step="any" placeholder="Ganancia total." disabled required>
                  </div>
                  <div class="form-group col-lg-12 col-md-12">
                    <label>Descripción:</label>
                    <textarea type="text" class="form-control" name="descripcion" id="descripcion" maxlength="10000" rows="4" placeholder="Descripción del artículo."></textarea>
                  </div>
                  <div class="form-group col-lg-6 col-md-12">
                    <label>Imagen:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg">
                    <input type="hidden" name="imagenactual" id="imagenactual">
                  </div>
                  <div class="form-group col-lg-6 col-md-12">
                    <label>Código del producto(*):</label>
                    <input type="text" class="form-control" name="codigo_producto" id="codigo_producto" maxlength="20" placeholder="Código del producto" onblur="convertirMayus(this)" required>
                  </div>
                  <div class="form-group col-lg-12 col-md-12" style="display: flex; justify-content: center;">
                    <button class="btn btn-success" type="button" id="btnDetalles1" onclick="frmDetalles(true)"><i class="fa fa-plus"></i> Más detalles</button>
                    <button class="btn btn-danger" type="button" id="btnDetalles2" onclick="frmDetalles(false)"><i class="fa fa-minus"></i> Cerrar</button>
                  </div>
                  <!-- form detalles -->
                  <div id="frmDetalles" class="col-lg-12 col-md-12" style="margin: 0 !important; padding: 0 !important;">
                    <div class="form-group col-lg-6 col-md-12">
                      <label>Talla:</label>
                      <textarea type="text" class="form-control" name="talla" id="talla" maxlength="10000" rows="4" placeholder="Ingrese la talla del producto."></textarea>
                    </div>
                    <div class="form-group col-lg-6 col-md-12">
                      <label>Color:</label>
                      <textarea type="text" class="form-control" name="color" id="color" maxlength="10000" rows="4" placeholder="Ingrese el color del producto."></textarea>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                      <label>Posición de objeto:</label>
                      <input type="text" class="form-control" name="posicion" id="posicion" maxlength="30" placeholder="Ingrese la posición de objeto." autocomplete="off">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                      <label>Peso:</label>
                      <input type="number" class="form-control" name="peso" id="peso" step="any" onkeydown="evitarNegativo(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" placeholder="Ingrese el peso.">
                    </div>
                    <div class="form-group col-lg-6 col-md-12">
                      <label>Fecha Emisión:</label>
                      <input type="date" class="form-control" name="fecha_emision" id="fecha_emision">
                    </div>
                    <div class="form-group col-lg-6 col-md-12">
                      <label>Fecha Vencimiento:</label>
                      <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento">
                    </div>
                    <div class="form-group col-lg-6 col-md-12">
                      <label>Nota 1:</label>
                      <textarea type="text" class="form-control" name="nota_1" id="nota_1" maxlength="10000" rows="4" placeholder="Ingrese la nota 1."></textarea>
                    </div>
                    <div class="form-group col-lg-6 col-md-12">
                      <label>Nota 2:</label>
                      <textarea type="text" class="form-control" name="nota_2" id="nota_2" maxlength="10000" rows="4" placeholder="Ingrese la nota 2."></textarea>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                      <label>Código de barra:</label>
                      <input type="text" class="form-control" name="codigo" id="codigo" maxlength="13" placeholder="Código de barra">
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
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                      <div style="display: flex; justify-content: start;">
                        <div id="camera"></div>
                      </div>
                    </div>
                  </div>
                  <!-- end form detalles -->
                </div>
                <div class="form-group col-lg-2 col-md-3 col-sm-12 caja1" style="padding-right: 0 !important; padding-left: 20px;">
                  <div class="contenedor" style="background-color: white; border-top: 3px #3d3f3f solid !important; padding: 10px 20px 20px 20px;">
                    <label>Imagen de muestra:</label>
                    <div>
                      <img src="" width="100%" id="imagenmuestra" style="display: none;">
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group col-lg-10 col-md-8 col-sm-12 botones" style="background-color: white !important; padding: 10px !important; float: left; margin: 0px;">
                <div style="float: left;">
                  <button class="btn btn-secondary" onclick="cancelarform2()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  <button class="btn btn-secondary" type="submit" id="btnGuardar2"><i class="fa fa-save"></i> Guardar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 4 -->

    <!-- Modal 3 -->
    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 100vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
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
                <select id="idcliente2" name="idcliente" class="form-control selectpicker" data-live-search="true" data-size="5" disabled>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Tipo Comp(*):</label>
                <select name="tipo_comprobante" id="tipo_comprobante2" class="form-control selectpicker" disabled>
                  <option value="Nota de venta al contado">Nota de venta al contado</option>
                  <option value="Factura">Factura</option>
                  <option value="Ticket">Ticket</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Local(*):</label>
                <select id="idalmacen3" name="idalmacen" class="form-control selectpicker idalmacen3" data-live-search="true" data-size="5" onchange="actualizarRUC3()" disabled>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">RUC local(*):</label>
                <input type="number" class="form-control" id="local_ruc3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label class="label_serie" style="width: 110px;">Serie(*):</label>
                <input type="text" class="form-control" name="serie_comprobante" placeholder="Serie" onblur="convertirMayus(this)" id="serie_comprobante2" readonly>
              </div>
              <div class="form-group col-lg-4 col-md-4 col-sm-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Proforma(*):</label>
                <input type="text" class="form-control" name="num_proforma" id="num_proforma2" oninput="onlyNumbersAndMaxLenght(this)" onblur="formatearNumero(this)" maxlength="10" placeholder="proforma" readonly>
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
                <table id="detalles2" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important; margin: 0 !important;">
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
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 15px; margin-bottom: 0px;">
                <button class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardar3"><i class="fa fa-save"></i> Enviar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 3 -->

    <!-- Modal 2 -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 100vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
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
                <select id="idcliente" name="idcliente" class="form-control selectpicker" data-live-search="true" data-size="5" required>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Tipo Comp(*):</label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                  <option value="Nota de venta al contado">Nota de venta al contado</option>
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
                <input type="text" class="form-control" name="num_proforma" id="num_proforma" oninput="onlyNumbersAndMaxLenght(this)" onblur="formatearNumero(this)" maxlength="10" placeholder="proforma">
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
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12" style="float: left; margin-top: 20px;">
                <div style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 10px;">
                  <a data-toggle="modal" href="#myModal">
                    <button id="btnAgregarArt" type="button" class="btn btn-secondary" style="color: black !important"> <span class="fa fa-plus"></span> Agregar artículo</button>
                  </a>
                  <a data-toggle="modal" href="#myModal6">
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
                <th>Imagen</th>
                <th>Nombre</th>
                <th>U. medida</th>
                <th style="width: 20%; min-width: 300px;">Descripción</th>
                <th>Categoría</th>
                <th>Ubicación del local</th>
                <th>Marca</th>
                <th>C. producto</th>
                <th>C. barra</th>
                <th>Stock normal</th>
                <th>Stock mínimo</th>
                <th>Precio de compra</th>
                <th>Precio de venta</th>
                <th>Precio venta por mayor</th>
                <th style="width: 20%; min-width: 200px;">Talla</th>
                <th style="width: 20%; min-width: 200px;">Color</th>
                <th>Peso</th>
                <th>Posición</th>
                <th>Fecha emisión</th>
                <th>Fecha vencimiento</th>
                <th style="width: 20%; min-width: 200px;">Nota 1</th>
                <th style="width: 20%; min-width: 200px;">Nota 2</th>
                <th>Agregado por</th>
                <th>Estado</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>U. medida</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Ubicación del local</th>
                <th>Marca</th>
                <th>C. producto</th>
                <th>C. barra</th>
                <th>Stock normal</th>
                <th>Stock mínimo</th>
                <th>Precio de compra</th>
                <th>Precio de venta</th>
                <th>Precio venta por mayor</th>
                <th>Talla</th>
                <th>Color</th>
                <th>Peso</th>
                <th>Posición</th>
                <th>Fecha emisión</th>
                <th>Fecha vencimiento</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Agregado por</th>
                <th>Estado</th>
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

    <!-- Modal 3 -->
    <div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 95vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: visible;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">NO SE ENCONTRÓ AL CLIENTE, ¿DESEA AGREGAR UNO NUEVO?:</h4>
          </div>
          <div class="panel-body">
            <form name="formSunat" id="formSunat" method="POST">
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
                <div style="display: flex;">
                  <input type="number" class="form-control" name="sunat" id="sunat" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="Buscar cliente por DNI o RUC a la SUNAT." required>
                  <button class="btn btn-secondary" type="submit" id="btnSunat">Buscar</button>
                </div>
              </div>
            </form>
            <form name="formulario3" id="formulario3" method="POST">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Tipo Documento(*):</label>
                <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" onchange="changeValue(this);" required disabled>
                  <option value="">- Seleccione -</option>
                  <option value="DNI">DNI</option>
                  <option value="RUC">RUC</option>
                  <option value="CEDULA">CEDULA</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Número(*):</label>
                <input type="number" class="form-control" name="num_documento" id="num_documento" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="8" placeholder="Ingrese el N° de documento." required disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nombre(*):</label>
                <input type="hidden" name="idcliente" id="idcliente3">
                <input type="text" class="form-control" name="nombre" id="nombre" maxlength="40" placeholder="Ingrese el nombre del cliente." autocomplete="off" required disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Dirección:</label>
                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Ingrese la dirección." maxlength="40" disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Teléfono:</label>
                <input type="number" class="form-control" name="telefono" id="telefono" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9" placeholder="Ingrese el teléfono." disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Ingrese el correo electrónico." disabled>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Local(*):</label>
                <select id="idalmacen4" name="idalmacen" class="form-control selectpicker idalmacen" data-live-search="true" data-size="5" onchange="actualizarRUC4()" required disabled>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>RUC local(*):</label>
                <input type="number" class="form-control" id="local_ruc4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local." disabled>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Descripción:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="10000" placeholder="Ingrese la descripción del cliente." autocomplete="off" disabled>
              </div>

              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0 !important; padding: 0 !important;">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="limpiarModalClientes();"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardarCliente" disabled><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="agregarClienteManual();"><i class="fa fa-sign-in"></i> Agregar Cliente Manual</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 3 -->

    <!-- Modal 4 -->
    <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 85% !important; max-height: 95vh; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: visible;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">CREAR NUEVO CLIENTE:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario4" id="formulario4" method="POST">
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Tipo Documento(*):</label>
                <select class="form-control select-picker" name="tipo_documento" id="tipo_documento2" onchange="changeValue(this);" required>
                  <option value="">- Seleccione -</option>
                  <option value="DNI">DNI</option>
                  <option value="RUC">RUC</option>
                  <option value="CEDULA">CEDULA</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Número(*):</label>
                <input type="number" class="form-control" name="num_documento" id="num_documento2" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="8" placeholder="Ingrese el N° de documento." required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Nombre(*):</label>
                <input type="hidden" name="idcliente" id="idcliente3">
                <input type="text" class="form-control" name="nombre" id="nombre6" maxlength="40" placeholder="Ingrese el nombre del cliente." autocomplete="off" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Dirección:</label>
                <input type="text" class="form-control" name="direccion" id="direccion2" placeholder="Ingrese la dirección." maxlength="40">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Teléfono:</label>
                <input type="number" class="form-control" name="telefono" id="telefono2" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9" placeholder="Ingrese el teléfono.">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" id="email2" maxlength="50" placeholder="Ingrese el correo electrónico.">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Local(*):</label>
                <select id="idalmacen5" name="idalmacen" class="form-control selectpicker idalmacen" data-live-search="true" data-size="5" onchange="actualizarRUC5()" required>
                  <option value="">- Seleccione -</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>RUC local(*):</label>
                <input type="number" class="form-control" id="local_ruc5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local." disabled>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Descripción:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion2" maxlength="10000" placeholder="Ingrese la descripción del cliente." autocomplete="off">
              </div>

              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0 !important; padding: 0 !important;">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" onclick="limpiarModalClientes2();"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardarCliente2"><i class="fa fa-save"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 4 -->

  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/proformas19.js"></script>
<?php
}
ob_end_flush();
?>