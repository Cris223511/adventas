<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';
  if ($_SESSION['acceso'] == 1) {
?>
    <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title">Usuarios
                  <?php
                  if ($_SESSION['cargo'] == 'superadmin' || $_SESSION['cargo'] == 'admin') {
                  ?>
                    <button class="btn btn-secondary" id="btnagregar" onclick="mostrarform(true)">
                      <i class="fa fa-plus-circle"></i> Agregar
                    </button>
                  <?php
                  }
                  ?>
                  <?php if ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "admin" || $_SESSION["cargo"] == "encargado") { ?>
                    <a href="../reportes/rptusuarios.php" target="_blank">
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
                    <th>Opciones</th>
                    <th>Usuario</th>
                    <th>Cargo</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Número Doc.</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Ubicación del local</th>
                    <th>RUC del local</th>
                    <th>Foto</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Usuario</th>
                    <th>Cargo</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Número Doc.</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Ubicación del local</th>
                    <th>RUC del local</th>
                    <th>Foto</th>
                    <th>Estado</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Nombre(*):</label>
                    <input type="hidden" name="idusuario" id="idusuario">
                    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="20" placeholder="Nombre" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Tipo Documento:</label>
                    <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" onchange="changeValue(this);">
                      <option value="DNI">DNI</option>
                      <option value="RUC">RUC</option>
                      <option value="CEDULA">CEDULA</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Número:</label>
                    <input type="number" class="form-control" name="num_documento" id="num_documento" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="8" placeholder="Documento">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label id="almacenes">Local principal(*):</label>
                    <select id="idalmacen" name="idalmacen" class="form-control selectpicker" data-live-search="true" data-size="5" onchange="actualizarRUC()" required>
                      <option value="">- Seleccione -</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>RUC local(*):</label>
                    <input type="number" class="form-control" id="local_ruc" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" placeholder="RUC del local" disabled>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Dirección:</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección" maxlength="40">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Teléfono:</label>
                    <input type="number" class="form-control" name="telefono" id="telefono" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9" placeholder="Teléfono">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Email:</label>
                    <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Cargo(*):</label>
                    <select name="cargo" id="cargo" class="form-control selectpicker" required onchange="marcarCheckboxPorRol(this)">
                      <option value="admin">Administrador</option>
                      <option value="cliente">Cliente</option>
                      <option value="vendedor">Vendedor</option>
                      <option value="encargado">Encargado de pedido</option>
                      <option value="almacenero">Almacenero</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Usuario(*):</label>
                    <input type="text" class="form-control" name="login" id="login" maxlength="15" placeholder="Usuario" oninput="javascript: this.value = this.value.replace(/\s/g, '');" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Clave(*):</label>
                    <div style="display: flex;">
                      <input type="password" class="form-control" name="clave" id="clave" maxlength="30" placeholder="Clave" required>
                      <a onclick="mostrarClave()">
                        <button type="button" id="mostrarClave" class="btn btn-secondary" style="display: flex; align-items: center; height: 35px; color: #726f6a !important;">
                          <i class="fa fa-eye"></i>
                        </button>
                      </a>
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Permisos:</label>
                    <ul style="list-style: none; margin-bottom: 0px;">
                      <li><input id="checkAll" type="checkbox" onchange="toggleCheckboxes(this)">Marcar todos</li>
                    </ul>
                    <ul style="list-style: none;" id="permisos">
                    </ul>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label>Imagen:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg">
                    <input type="hidden" name="imagenactual" id="imagenactual">
                    <img src="" width="150px" id="imagenmuestra" style="display: none;">
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-secondary" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

  <script type="text/javascript" src="scripts/usuario13.js"></script>
<?php
}
ob_end_flush();
?>