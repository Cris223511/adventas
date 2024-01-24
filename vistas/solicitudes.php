<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require '../config/Conexion.php';
  require 'header.php';

  if ($_SESSION['solicitud'] == 1) {
?>

    <style>
      .popover {
        z-index: 10000 !important;
        width: 200px !important;
      }

      tbody .popover {
        z-index: 10000 !important;
        width: 190px !important;
      }


      @media (max-width: 991px) {
        .label_serie {
          width: 100px !important;
        }
      }

      @media (max-width: 767px) {
        label {
          width: 100px !important;
        }
      }
    </style>

    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-md-12" style="overflow-x: visible !important;">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Solicitud de materiales
                  <?php
                  if (($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin' || $_SESSION['cargo'] == 'encargado')) {
                  ?>
                    <a data-toggle="modal" href="#myModal2">
                      <button type="button" class="btn btn-secondary" style="color: black !important;" onclick="limpiar()">
                        <span class="fa fa-plus-circle"></span> Agregar
                      </button>
                    </a>
                  <?php
                  }
                  ?>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin") { ?>
                    <a href="../reportes/rptsolicitudes.php" target="_blank">
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
                    <th>Código LCL</th>
                    <th>Fecha pedido</th>
                    <th>Fecha despacho</th>
                    <th>Responsable pedido</th>
                    <th>Responsable despacho</th>
                    <th>Empresa</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Código LCL</th>
                    <th>Fecha pedido</th>
                    <th>Fecha despacho</th>
                    <th>Responsable pedido</th>
                    <th>Responsable despacho</th>
                    <th>Empresa</th>
                    <th>Telefono</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Modal 4 -->
    <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 80%; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Registro de solicitud:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario3" id="formulario3" method="POST">
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Responsable despacho(*):</label>
                <input type="hidden" name="idsolicitud" id="idsolicitud2">
                <select id="idalmacenero2" name="idalmacenero" class="form-control selectpicker" data-live-search="true" disabled>
                  <option value="">- Sin registrar -</option>
                </select>
              </div>
              <div class="row" style="padding-left: 15px; padding-right: 15px;">
                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 125px;">Código LCL(*):</label>
                  <input type="text" class="form-control" name="codigo_pedido" id="codigo_pedido2" maxlength="10" placeholder="Cargando..." disabled>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 125px;">Teléfono(*):</label>
                  <input type="number" class="form-control" name="telefono" id="telefono2" maxlength="9" placeholder="Cargando..." disabled>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label class="label_serie">Empresa(*):</label>
                  <input type="text" class="form-control" name="empresa" id="empresa2" maxlength="50" placeholder="Cargando..." disabled>
                </div>
              </div>

              <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive" style="overflow-x: visible !important;">
                <table id="detalles2" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Artículo</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad solicitada a prestar." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                    <th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que el almacenero prestó." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                    <th>Cantidad a Prestar <a href="#" data-toggle="popover" data-placement="top" title="Cantidad a Prestar" data-content="Digita la cantidad que deseas prestar al encargado (no debe superar la cantidad solicitada)." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button id="btnCancelar" class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardar3"><i class="fa fa-save"></i> Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin modal 4 -->

    <!-- Modal 3 -->
    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width: 90% !important; max-height: 80%; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%); overflow-x: hidden;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title infotitulo">Comentarios:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario2" id="formulario2" method="POST">
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Comentario(*):</label>
                <input type="hidden" name="idsolicitud" id="idsolicitud">
                <textarea type="text" class="form-control" style="resize: none;" name="comentario" id="comentario" maxlength="200" rows="4" placeholder="Cargando..."></textarea>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button id="btnCancelar" class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                <button class="btn btn-secondary" type="submit" id="btnGuardar2"><i class="fa fa-save"></i> Guardar</button>
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
            <h4 class="modal-title infotitulo">Registro de solicitud:</h4>
          </div>
          <div class="panel-body">
            <form name="formulario" id="formulario" method="POST">
              <input type="hidden" name="idsolicitud" id="idsolicitud">
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 despachador" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                <label style="width: 100px;">Responsable despacho(*):</label>
                <select id="idalmacenero" name="idalmacenero" class="form-control selectpicker" data-live-search="true" disabled>
                  <option value="">- Sin registrar -</option>
                </select>
              </div>
              <div class="row" style="padding-left: 15px; padding-right: 15px;">
                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 125px;">Código LCL(*):</label>
                  <input type="text" class="form-control" name="codigo_pedido" id="codigo_pedido" oninput="onlyNumbersAndMaxLenght(this)" maxlength="10" onpaste="false" ondrop="false" placeholder="Ingrese el código correlativo LCL." required>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label style="width: 125px;">Teléfono(*):</label>
                  <input type="number" class="form-control" name="telefono" id="telefono" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9" placeholder="Ingrese el número telefónico." required>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12" style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
                  <label class="label_serie">Empresa(*):</label>
                  <input type="text" class="form-control" name="empresa" id="empresa" maxlength="50" placeholder="Ingrese la empresa." required>
                </div>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="float: left;">
                <a data-toggle="modal" href="#myModal">
                  <button id="btnAgregarArt" type="button" class="btn btn-secondary" style="color: black !important"> <span class="fa fa-plus"></span> Agregar artículos</button>
                </a>
              </div>

              <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive" style="overflow-x: visible !important;">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                  <thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Artículo</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Cantidad Solicitada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Solicitada" data-content="Es la cantidad solicitada a prestar." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                    <th>Cantidad Prestada <a href="#" data-toggle="popover" data-placement="top" title="Cantidad Prestada" data-content="Es la cantidad que el almacenero prestó." style="color: #418bb7"><i class="fa fa-question-circle"></i></a></th>
                  </thead>
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
          <div class="modal-body table-responsive" style="overflow-x: visible !important;">
            <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
              <thead>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Stock normal</th>
                <th>Stock mínimo</th>
                <th>Precio de venta</th>
                <th>Imagen</th>
                <th>Estado</th>
              </thead>
              <tbody>

              </tbody>
              <tfoot>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Stock normal</th>
                <th>Stock mínimo</th>
                <th>Precio de venta</th>
                <th>Imagen</th>
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
  <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <div id="script"></div>
  <script type="text/javascript" src="scripts/solicitudes34.js"></script>
<?php
}
ob_end_flush();
?>