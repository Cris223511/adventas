<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['almacen'] == 1 && $_SESSION["cargo"] == "superadmin") {
?>
    <!--Contenido-->
    <!-- Content Wrapper. Contains page content -->
    <style>
      @media (max-width: 1198px) {
        .label_input {
          width: 100px !important;
        }
      }

      @media (max-width: 991px) {
        .caja1 {
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
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Artículos Externos
                  <button class="btn btn-secondary" id="btnagregar" onclick="mostrarform(true); desbloquearPrecioCompraVenta();"><i class="fa fa-plus-circle"></i> Agregar</button>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin" || $_SESSION["cargo"] == "encargado") { ?>
                    <a href="../reportes/rptarticulosExternos.php" target="_blank"><button class="btn btn-secondary" style="color: black !important;"><i class="fa fa-clipboard"></i> Reporte</button></a>
                  <?php } ?>
                </h1>
                <div class="box-tools pull-right"></div>
              </div>
              <!-- /.box-header -->
              <!-- centro -->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead>
                    <th>Opciones</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>U. medida</th>
                    <th>Categoría</th>
                    <th>Ubicación del local</th>
                    <th>Marca</th>
                    <th>C. producto</th>
                    <th>C. barra</th>
                    <th>Stock normal</th>
                    <th>Stock mínimo</th>
                    <th>Precio de compra</th>
                    <th>Precio de venta</th>
                    <th>Ganancia</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th>Peso</th>
                    <th>Posición</th>
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
                    <th>Categoría</th>
                    <th>Ubicación del local</th>
                    <th>Marca</th>
                    <th>C. producto</th>
                    <th>C. barra</th>
                    <th>Stock normal</th>
                    <th>Stock mínimo</th>
                    <th>Precio de compra</th>
                    <th>Precio de venta</th>
                    <th>Ganancia</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th>Peso</th>
                    <th>Posición</th>
                    <th>Agregado por</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros" style="background-color: #ecf0f5 !important; padding-left: 0 !important; padding-right: 0 !important;">
                <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                  <div class="form-group col-lg-2 col-md-4 col-sm-12 caja1" style="padding-left: 0 !important; padding-right: 20px;">
                    <div class="contenedor" style="background-color: white; border-top: 3px #3d3f3f solid; padding: 10px 20px 20px 20px;">
                      <label>Imagen de muestra:</label>
                      <div>
                        <img src="" width="100%" id="imagenmuestra" style="display: none;">
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-10 col-md-8 col-sm-12 caja2" style="background-color: white; border-top: 3px #3d3f3f solid; padding: 20px;">
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label class="label_input" style="width: 90px;">Nombre(*):</label>
                      <input type="hidden" name="idarticulo" id="idarticulo">
                      <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Unidad de medida(*):</label>
                      <select id="idmedida" name="idmedida" class="form-control selectpicker" data-live-search="true" required></select>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Local(*):</label>
                      <select id="idalmacen" name="idalmacen" class="form-control selectpicker idalmacen" data-live-search="true" data-size="5" onchange="actualizarRUC()" required>
                        <option value="">- Seleccione -</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">RUC local(*):</label>
                      <input type="number" class="form-control" id="local_ruc" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Categoría(*):</label>
                      <select id="idcategoria" name="idcategoria" class="form-control selectpicker" data-live-search="true" data-size="5" required></select>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Marca(*):</label>
                      <select id="idmarcas" name="idmarcas" class="form-control selectpicker" data-live-search="true" data-size="5" required></select>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Stock(*):</label>
                      <input type="number" class="form-control" name="stock" id="stock" onkeydown="evitarNumerosNegativos(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" min="0" placeholder="Stock" required>
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Stock mínimo(*):</label>
                      <input type="number" class="form-control" name="stock_minimo" id="stock_minimo" onkeydown="evitarNumerosNegativos(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" min="1" placeholder="Stock mínimo" required>
                    </div>
                    <div class="form-group col-lg-4 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label class="label_input" style="width: 115px;">Precio compra(*):</label>
                      <input type="number" class="form-control" name="precio_compra" id="precio_compra" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); changeGanancia();" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" placeholder="Ingrese el precio de compra." required>
                    </div>
                    <div class="form-group col-lg-4 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Precio venta(*):</label>
                      <input type="number" class="form-control" name="precio_venta" id="precio_venta" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); changeGanancia();" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="1" placeholder="Ingrese el precio de venta." required>
                    </div>
                    <div class="form-group col-lg-4 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label style="width: 100px;">Ganancia(*):</label>
                      <input type="number" class="form-control" name="ganancia" id="ganancia" step="any" placeholder="Ganancia total." disabled required>
                    </div>
                    <div class="form-group col-lg-12 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label class="label_input" style="width: 90px;">Descripción:</label>
                      <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del artículo" autocomplete="off">
                    </div>
                    <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                      <label class="label_input" style="width: 90px;">Imagen:</label>
                      <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg">
                      <input type="hidden" name="imagenactual" id="imagenactual">
                    </div>
                    <div class="form-group col-lg-6 col-md-12">
                      <div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                        <label style="width: 100px;">Código del producto(*):</label>
                        <input type="text" class="form-control" name="codigo_producto" id="codigo_producto" maxlength="13" placeholder="Código del producto" onblur="convertirMayus(this)" required>
                      </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12" style="display: flex; justify-content: center;">
                      <button class="btn btn-success" type="button" id="btnDetalles1" onclick="frmDetalles(true)"><i class="fa fa-plus"></i> Más detalles</button>
                      <button class="btn btn-danger" type="button" id="btnDetalles2" onclick="frmDetalles(false)"><i class="fa fa-minus"></i> Cerrar</button>
                    </div>
                    <!-- form detalles -->
                    <div id="frmDetalles" class="col-lg-12 col-md-12" style="margin: 0 !important; padding: 0 !important;">
                      <div class="form-group col-lg-6 col-md-12">
                        <label>Talla:</label>
                        <input type="text" class="form-control" name="talla" id="talla" maxlength="5" placeholder="Ingrese la talla del producto." autocomplete="off">
                      </div>
                      <div class="form-group col-lg-6 col-md-12">
                        <label>Color:</label>
                        <input type="text" class="form-control" name="color" id="color" maxlength="30" placeholder="Ingrese el color del producto." autocomplete="off">
                      </div>
                      <div class="form-group col-lg-6 col-md-12">
                        <label>Posición de objeto:</label>
                        <input type="text" class="form-control" name="posicion" id="posicion" maxlength="30" placeholder="Ingrese la posición de objeto." autocomplete="off">
                      </div>
                      <div class="form-group col-lg-6 col-md-12">
                        <label>Peso:</label>
                        <input type="number" class="form-control" name="peso" id="peso" step="any" onkeydown="evitarNegativo(event)" oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" placeholder="Ingrese el peso.">
                      </div>
                      <div class="form-group col-lg-6 col-md-12">
                        <div>
                          <label>Código de barra(*):</label>
                          <input type="text" class="form-control" name="codigo" id="codigo" maxlength="13" placeholder="Código de barra">
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
                      <div class="form-group col-lg-6 col-md-12">
                        <div style="display: flex; justify-content: start;">
                          <div id="camera"></div>
                        </div>
                      </div>
                    </div>
                    <!-- end form detalles -->
                  </div>
                  <div class="form-group col-lg-10 col-md-8 col-sm-12 botones" style="background-color: white !important; padding: 10px 10px 10px 0 !important; float: right;">
                    <div style="float: right;">
                      <button class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                      <button class="btn btn-secondary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                    </div>
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
  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="../public/js/JsBarcode.all.min.js"></script>
  <script type="text/javascript" src="../public/js/jquery.PrintArea.js"></script>
  <script type="text/javascript" src="scripts/articuloExterno.js"></script>

<?php
}
ob_end_flush();
?>