<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['almacen'] == 1) {
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

        .contenedor_servicios {
          display: flex;
          flex-direction: column-reverse !important;
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
                <h1 class="box-title">Servicios
                  <button class="btn btn-secondary" id="btnagregar" onclick="mostrarform(true); desbloquearPrecioCompraVenta();"><i class="fa fa-plus-circle"></i> Agregar</button>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin" || $_SESSION["cargo"] == "encargado") { ?>
                    <a href="../reportes/rptservicio.php" target="_blank"><button class="btn btn-secondary" style="color: black !important;"><i class="fa fa-clipboard"></i> Reporte</button></a>
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
                    <th>Ubicación del local</th>
                    <th>C. servicio</th>
                    <th style="width: 20%; min-width: 200px;">Descripción</th>
                    <th>Precio de venta</th>
                    <th>Agregado por</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Ubicación del local</th>
                    <th>C. servicio</th>
                    <th>Descripción</th>
                    <th>Precio de venta</th>
                    <th>Agregado por</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros" style="background-color: #ecf0f5 !important; padding-left: 0 !important; padding-right: 0 !important;">
                <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                  <div class="contenedor_servicios">
                    <div class="form-group col-lg-10 col-md-8 col-sm-12 caja2" style="background-color: white; border-top: 3px #3d3f3f solid; padding: 20px;">
                      <div class="form-group col-lg-12 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                        <label class="label_input" style="width: 100px;">Nombre(*):</label>
                        <input type="hidden" name="idservicio" id="idservicio">
                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
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
                      <div class="form-group col-lg-6 col-md-12">
                        <div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                          <label style="width: 100px;">Código del servicio(*):</label>
                          <input type="text" class="form-control" name="codigo_producto" id="codigo_producto" maxlength="13" placeholder="Código del servicio" onblur="convertirMayus(this)" required>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                        <label style="width: 100px;">Precio(*):</label>
                        <input type="number" class="form-control" name="precio_venta" id="precio_venta" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" onkeydown="evitarNegativo(event)" onpaste="return false;" onDrop="return false;" min="0" placeholder="Ingrese el precio." required>
                      </div>
                      <div class="form-group col-lg-12 col-md-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                        <label class="label_input" style="width: 90px;">Imagen:</label>
                        <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg">
                        <input type="hidden" name="imagenactual" id="imagenactual">
                      </div>
                      <div class="form-group col-lg-12 col-md-12">
                        <label>Descripción:</label>
                        <textarea type="text" class="form-control" name="descripcion" id="descripcion" maxlength="10000" rows="4" placeholder="Descripción del servicio."></textarea>
                      </div>
                    </div>
                    <div class="form-group col-lg-2 col-md-4 col-sm-12 caja1" style="padding-right: 0 !important; padding-left: 20px;">
                      <div class="contenedor" style="background-color: white; border-top: 3px #3d3f3f solid !important; padding: 10px 20px 20px 20px;">
                        <label>Imagen de muestra:</label>
                        <div>
                          <img src="" width="100%" id="imagenmuestra" style="display: none;">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-10 col-md-8 col-sm-12 botones" style="background-color: white !important; padding: 10px !important; float: left;">
                    <div style="float: left;">
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
  <?php
  } else {
    require 'noacceso.php';
  }
  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/servicio.js"></script>

<?php
}
ob_end_flush();
?>