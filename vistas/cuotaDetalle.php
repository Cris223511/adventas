<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

$id = $_GET['id'];

if (!isset($_SESSION["nombre"])) {
  header("Location: login.html");
} else {
  require 'header.php';

  if ($_SESSION['cuotas'] == 1) {

    include('../config/Conexion.php');
    $sql = "SELECT cu.idcuotas,cu.fecha_hora as fecha,cu.fecha_anulado as anulado,z.idzona as idzona,al.idalmacen as idalmacen,cu.idcliente,ucl.nombre as cliente,ucv.nombre as vendedor,ucl.tipo_documento as tipo_doc_cl,ucv.tipo_documento as tipo_doc_ve,ucl.num_documento as num_doc_cl,ucv.num_documento as num_doc_ve,ucl.idusuario as idcliente,ucv.idusuario as idvendedor,u.idusuario,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.cargo AS cargo,z.ubicacion as ubicacion,z.zona as zona,al.ubicacion as almacen,cu.tipo_comprobante,cu.serie_comprobante,cu.num_comprobante,cu.total_venta,cu.monto_pagado,cu.impuesto,cu.estado FROM cuotas cu LEFT JOIN usuario ucl ON cu.idcliente=ucl.idusuario LEFT JOIN usuario ucv ON cu.idvendedor=ucv.idusuario LEFT JOIN usuario u ON cu.idusuario=u.idusuario LEFT JOIN zonas z ON cu.idzona=z.idzona LEFT JOIN almacen al ON cu.idalmacen=al.idalmacen WHERE cu.idcuotas='$id'";
    $result = $conexion->query($sql);

    $sql = "SELECT dc.idcuotas,a.codigo,dc.idarticulo,a.nombre,dc.cantidad,dc.precio_venta,dc.descuento,(dc.cantidad*dc.precio_venta-dc.descuento) as subtotal FROM detalle_cuotas dc LEFT JOIN articulo a on dc.idarticulo=a.idarticulo where dc.idcuotas='$id'";
    $result2 = $conexion->query($sql);
    $result2 = $result2->num_rows;

    foreach ($result as $row) {
?>

      <style>
        h3 {
          margin-top: 10px !important;
          margin-bottom: 10px !important;
        }

        .espacio {
          padding-bottom: 20px;
        }

        .detalles {
          display: flex;
          background: white;
          padding-left: 20px;
          padding-right: 20px;
          padding-top: 5px;
          padding-bottom: 5px;
          border-radius: 15px;
        }

        .box {
          background: linear-gradient(90deg, rgba(62, 71, 138, 1) 0%, rgba(27, 106, 155, 1) 35%, rgba(7, 127, 164, 1) 100%);
        }

        .box-title {
          color: white;
        }

        .box-header {
          padding-left: 0 !important;
          padding-right: 0 !important;
        }

        .subtitulo {
          font-weight: 500;
          color: #c6c6c6;
        }

        .textos {
          width: 50%;
        }

        .iconos {
          width: 50%;
          padding-top: 10px;
          padding-bottom: 10px;
          justify-content: end;
          align-items: center;
          display: flex;
        }

        .fondo {
          height: 70px;
          width: 70px;
          background: linear-gradient(90deg, rgba(62, 71, 138, 1) 0%, rgba(27, 106, 155, 1) 35%, rgba(7, 127, 164, 1) 100%);
          border-radius: 50%;
          display: flex;
          justify-content: center;
          align-items: center;
          color: white;
          font-size: 25px;
        }

        .informacion {
          background: white;
          border-radius: 15px;
          padding-left: 20px;
          padding-right: 20px;
          padding-top: 0;
          padding-bottom: 20px;
        }

        .infotitulo {
          display: inline-block;
          font-size: 18px;
          font-weight: bold;
          margin: 0;
          line-height: 1;
          color: #313153;
        }

        .contenido {
          display: flex;
        }

        .cajamitad1 {
          width: 50%;
        }

        .cajamitad2 {
          width: 50%;
          justify-content: end;
          text-align: end;
        }

        .tituloinfo {
          font-size: 15px;
        }

        .subtituloinfo {
          font-weight: 500;
          color: #c6c6c6;
        }

        .tabla {
          border-radius: 20px;
        }

        .sombra {
          box-shadow: 0px 0px 20px -9px;
        }

        table tr:last-child td:first-child {
          border-bottom-left-radius: 10px;
        }

        table tr:last-child td:last-child {
          border-bottom-right-radius: 10px;
        }
      </style>

      <div id="idcuota" style="display: none;"><?php echo $id ?></div>

      <div class="content-wrapper">
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="panel-body">
                  <div class="box-header">
                    <h1 class="box-title" style="padding-bottom: 10px;">Detalle de cuotas</h1>
                  </div>
                  <div class="col-sm-6 espacio">
                    <div class="detalles">
                      <div class="textos">
                        <h5 class="subtitulo">MONTO TOTAL</h5>
                        <h3 id="montoTotal"><?php echo $row['total_venta'] ?> S/.</h3>
                      </div>
                      <div class="iconos">
                        <div class="fondo">
                          <i class="fa fa-credit-card"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 espacio">
                    <div class="detalles">
                      <div class="textos">
                        <h5 class="subtitulo">MONTO PAGADO</h5>
                        <h3 id="montoPagado"></h3>
                      </div>
                      <div class="iconos">
                        <div class="fondo">
                          <i class="fa fa-credit-card"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 espacio">
                    <div class="detalles">
                      <div class="textos">
                        <h5 class="subtitulo">PRODUCTOS</h5>
                        <h3><?php echo $result2 ?></h3>
                      </div>
                      <div class="iconos">
                        <div class="fondo">
                          <i class="fa fa-shopping-cart"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 espacio">
                    <div class="detalles">
                      <div class="textos">
                        <h5 class="subtitulo">CUOTAS</h5>
                        <h3 id="contarPagos"></h3>
                      </div>
                      <div class="iconos">
                        <div class="fondo">
                          <i class="fa fa-list-ul"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 espacio">
                    <div class="informacion">
                      <div class="box-header">
                        <h3 class="infotitulo">Información general:</h3>
                      </div>
                      <hr style="height: 15px; margin: 0;">
                      <div class="contenido">
                        <div class="cajamitad1">
                          <h4 class="subtituloinfo">CLIENTE</h4>
                          <h5 class="tituloinfo"><?php echo $row['cliente'] ?> - <?php echo $row['tipo_doc_cl'] ?>: <?php echo $row['num_doc_cl'] ?></h5>
                        </div>
                        <!-- <div class="cajamitad2">
                          <h4 class="subtituloinfo">NOMBRE DEL ARTICULO</h4>
                          <h5 class="tituloinfo">2022-02-02</h5>
                        </div> -->
                      </div>
                      <hr style="height: 15px; margin: 0;">
                      <div class="contenido">
                        <div class="cajamitad1">
                          <h4 class="subtituloinfo">VENDEDOR</h4>
                          <h5 class="tituloinfo"><?php echo $row['vendedor'] ?> - <?php echo $row['tipo_doc_ve'] ?>: <?php echo $row['num_doc_ve'] ?></h5>
                        </div>
                        <!-- <div class="cajamitad2">
                          <h4 class="subtituloinfo">NOMBRE DEL ARTICULO</h4>
                          <h5 class="tituloinfo">2022-02-02</h5>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 espacio">
                    <div class="informacion">
                      <div class="box-header">
                        <h3 class="infotitulo">Detalles de compra:</h3>
                      </div>
                      <hr style="height: 15px; margin: 0;">
                      <div class="contenido sombra tabla">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive" style="padding: 0;">
                          <table id="detallesCompra" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                            <thead style="background-color:#A9D0F5">
                              <th>Código</th>
                              <th>Artículo</th>
                              <th>Cantidad</th>
                              <th>Precio venta</th>
                              <th>Descuento</th>
                              <th>Subtotal</th>
                            </thead>
                            <tfoot>
                              <th>TOTAL</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>
                                <h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta">
                              </th>
                            </tfoot>
                            <tbody>

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12 espacio">
                    <div class="informacion">
                      <div class="contenido" style="padding-top: 10px; padding-bottom: 10px;">
                        <div class="cajamitad1">
                          <h3 class="infotitulo">Pagos registrados:</h3>
                        </div>
                        <div class="cajamitad2">
                          <a data-toggle="modal" href="#myModal">
                            <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cancelarform()"> <span class="fa fa-plus"></span> Nuevo pago</button>
                          </a>
                          <a href="cuotas.php">
                            <button class="btn btn-danger">
                              <i class="fa fa-arrow-circle-left"></i> Volver
                            </button>
                          </a>
                        </div>
                      </div>
                      <hr style="height: 15px; margin: 0;">
                      <div class="contenido sombra tabla">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 table-responsive" style="padding: 0;">
                          <table id="detallesPagos" class="table table-striped table-bordered table-condensed table-hover w-100" style="width: 100% !important">
                            <thead style="background-color:#A9D0F5">
                              <th>Método de pago</th>
                              <th>Concepto</th>
                              <th>Fecha de pago</th>
                              <th>Estado</th>
                              <th>Monto de pago</th>
                            </thead>
                            <tfoot>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tfoot>
                            <tbody>

                            </tbody>
                            <tfoot>
                              <th>TOTAL</th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>
                                <h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta">
                              </th>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 50% !important; margin: 0 !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%);">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title infotitulo">Registro de nuevo pago:</h4>
            </div>
            <form name="formulario" id="formulario" method="POST">
              <div class="modal-body">
                <input type="text" class="form-control" name="idcuotasparam" value="<?php echo $id ?>" style="display: none;" required>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Método de pago:</label>
                  <select name="metodo_pago" id="metodo_pago" class="form-control selectpicker" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta de crédito</option>
                    <option value="Yape">Yape</option>
                    <option value="Otros">Otros</option>
                  </select>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Concepto de cobro:</label>
                  <input type="text" class="form-control" name="concepto" id="concepto" maxlength="50" placeholder="Concepto de cobro" required>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Monto (S/.):</label>
                  <input type="number" class="form-control" name="monto" id="monto" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" min="1" maxlength="10" placeholder="Monto (S/.)" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-secondary" id="btnGuardar">Generar pago</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Fin modal -->

  <?php
    }
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script type="text/javascript" src="scripts/cuotaDetalle15.js"></script>
<?php
}
ob_end_flush();
?>