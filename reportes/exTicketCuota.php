<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['cuotas'] == 1) {
?>
    <style>
      td {
        font-weight: bold;
        font-size: 15px !important;
      }
    </style>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <link href="../public/css/ticket3.css" rel="stylesheet" type="text/css">
    </head>

    <body onload="window.print();">
      <?php

      //Incluímos la clase cuota
      require_once "../modelos/Cuotas.php";
      //Instanaciamos a la clase con el objeto cuota
      $cuotas = new Cuotas();
      //En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
      $rspta = $cuotas->ventacabecera($_GET["id"]);
      //Recorremos todos los valores obtenidos
      $reg = $rspta->fetch_object();

      require_once "../modelos/Perfiles.php";
      $perfil = new Perfiles();
      $rspta2 = $perfil->mostrarReporte();

      //Establecemos los datos de la empresa
      $logo = $rspta2["imagen"];
      $empresa = $rspta2["titulo"];
      $documento = ($rspta2["ruc"] == '') ? 'Sin registrar' : $rspta2["ruc"];
      $direccion = ($rspta2["direccion"] == '') ? 'Sin registrar' : $rspta2["direccion"];
      $telefono = ($rspta2["telefono"] == '') ? 'Sin registrar' : number_format($rspta2["telefono"], 0, '', ' ');
      $email = ($rspta2["email"] == '') ? 'Sin registrar' : $rspta2["email"];

      ?>
      <div class="zona_impresion">
        <!-- codigo imprimir -->
        <br>
        <table border="0" align="center" width="370px">
          <tr>
            <td align="center">
              <img width="100px" src="../files/logo_reportes/<?php echo $logo ?>">
            </td>
          </tr>
          <tr>
            <td colspan="16"></td>
          </tr>
          <tr>
            <td align="center">
              <!-- Mostramos los datos de la empresa en el documento HTML -->
              <?php echo $empresa; ?><br>
            </td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"><?php echo $documento; ?></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"><?php echo $direccion; ?></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"><?php echo $telefono; ?></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"><?php echo $email; ?></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"><?php echo $reg->fecha; ?></td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
        </table>
        <table border="0" width="370px">
          <tr>
            <td colspan="16">
              Estado: <?php echo $reg->estado; ?>
            </td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <td align="center"></td>
          </tr>
          <tr>
            <!-- Mostramos los datos del cliente en el documento HTML -->
            <td colspan="8" style="text-align: left;">
              Cliente: <?php echo $reg->cliente; ?>
            </td>
            <td colspan="8" style="text-align: right;">
              Vendedor: <?php echo $reg->vendedor; ?>
            </td>
          </tr>
          <tr>
            <!-- Mostramos los datos del cliente en el documento HTML -->
            <td colspan="8" style="text-align: left;">
              <?php echo $reg->tipo_documentoc . ": " . $reg->num_documentoc; ?>
            </td>
            <td colspan="8" style="text-align: left;">
              <?php echo $reg->tipo_documentov . ": " . $reg->num_documentov; ?>
            </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td>Nº de venta: <?php echo $reg->serie_comprobante . " - " . $reg->num_comprobante; ?></td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td>Ubicación Almacén:</td>
          </tr>
          <tr>
            <td><?php echo $reg->almacen; ?></td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td colspan="16"> </td>
          </tr>
          <tr>
            <td>Zona de venta:</td>
          </tr>
          <tr>
            <td><?php echo $reg->ubicacion . ' - ' . strtoupper($reg->zona); ?></td>
          </tr>
        </table>
        <br>
        <!-- Mostramos los detalles de la cuota en el documento HTML -->
        <table border="0" width="370px">
          <tr>
            <td>CANT.</td>
            <td>DESCRIPCIÓN</td>
            <td align="right">IMPORTE</td>
          </tr>
          <tr>
            <td colspan="3">====================================================</td>
          </tr>
          <?php
          $id = $_GET['id'];

          $rsptad = $cuotas->ventadetalle($_GET["id"]);
          $rspta3 = $cuotas->mostrar($id);
          $cantidad = 0;
          $igv = 0;

          while ($regd = $rsptad->fetch_object()) {
            echo "<tr>";
            echo "<td>" . $regd->cantidad . "</td>";
            echo "<td>" . $regd->articulo;
            echo "<td align='right'>S/ " . $regd->subtotal . "</td>";
            echo "</tr>";
            $cantidad += $regd->cantidad;
            $igv = $igv + ($rspta3["impuesto"] == 18 ? $regd->subtotal * 0.18 : $regd->subtotal * 0);
          }
          ?>
          <!-- Mostramos los totales de la cuota en el documento HTML -->
          <tr>
            <td>&nbsp;</td>
            <td align="right"><b>IGV:</b></td>
            <td align="right"><b>S/ <?php echo number_format($igv, 2, '.', ''); ?></b></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="right"><b>TOTAL:</b></td>
            <td align="right"><b>S/ <?php echo number_format($rspta3["total_venta"], 2, '.', '') ?></b></td>
          </tr>
          <tr>
            <td colspan="3">Nº de artículos: <?php echo $cantidad; ?></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" align="center">¡Gracias por su compra!</td>
          </tr>
          <tr>
            <td colspan="3" align="center">Sistema De Inventario</td>
          </tr>
          <tr>
            <td colspan="3" align="center">Lima - Perú</td>
          </tr>

        </table>
        <br>
      </div>
      <p>&nbsp;</p>

    </body>

    </html>
<?php
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>