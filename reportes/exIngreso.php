<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['compras'] == 1) {
    //Incluímos el archivo Ingreso.php
    require('Ingreso.php');

    //Establecemos los datos de la empresa
    $logo = "logo.jpeg";
    $ext_logo = "jpg";
    $empresa = "Arena San Andrés Perú S.A.C.";
    $documento = "20477157772";
    $direccion = "Av Gerardo Unger 5689 - Los Olivos - Lima";
    $telefono = "998 393 220";
    $email = "jcarlos.ad7@gmail.com";

    //Obtenemos los datos de la cabecera de la venta actual
    require_once "../modelos/Ingreso.php";
    $ingreso = new Ingreso();
    $rsptav = $ingreso->ingresocabecera($_GET["id"]);
    //Recorremos todos los valores obtenidos
    $regv = $rsptav->fetch_object();

    //Establecemos la configuración de la factura
    $pdf = new PDF_Invoice('P', 'mm', 'A4');
    $pdf->AddPage();

    //Enviamos los datos de la empresa al método addSociete de la clase Factura
    $pdf->addSociete(
      utf8_decode($empresa),
      $documento . "\n" .
        utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" .
        utf8_decode("Teléfono: ") . $telefono . "\n" .
        "Email: " . $email . "\n" .
        utf8_decode("Local: ") . utf8_decode($regv->almacen) . "\n",
      $logo,
      $ext_logo
    );
    $pdf->fact_dev("$regv->tipo_comprobante ", "$regv->serie_comprobante-$regv->num_comprobante");
    $pdf->temporaire("");
    $pdf->addDate($regv->fecha);
    $pdf->addMetodoPago(($regv->metodo_pago == '') ? 'Sin registrar.' : $regv->metodo_pago);

    //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse(
      utf8_decode($regv->proveedor),
      "Domicilio: " . utf8_decode($regv->direccion !== "" ? $regv->direccion : "Sin registrar."),
      $regv->tipo_documento . ": " . ($regv->num_documento !== "" ? $regv->num_documento : "Sin registrar."),
      "Email: " . ($regv->email !== "" ? $regv->email : "Sin registrar."),
      "Telefono: " . ($regv->telefono !== "" ? $regv->telefono : "Sin registrar.")
    );

    //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta
    $cols = array(
      "CODIGO" => 26,
      "NOMBRE DE PRODUCTO" => 75,
      "CANTIDAD" => 22,
      "P.C." => 25,
      "P.V." => 20,
      "SUBTOTAL" => 22
    );
    $pdf->addCols($cols);
    $cols = array(
      "CODIGO" => "L",
      "NOMBRE DE PRODUCTO" => "L",
      "CANTIDAD" => "C",
      "P.C." => "R",
      "P.V." => "R",
      "SUBTOTAL" => "C"
    );
    $pdf->addLineFormat($cols);
    $pdf->addLineFormat($cols);
    //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
    $y = 89;

    //Obtenemos todos los detalles de la venta actual
    $rsptad = $ingreso->ingresodetalle($_GET["id"]);

    while ($regd = $rsptad->fetch_object()) {
      $line = array(
        "CODIGO" => "$regd->codigo_producto",
        "NOMBRE DE PRODUCTO" => utf8_decode("$regd->articulo"),
        "CANTIDAD" => "$regd->cantidad",
        "P.C." => "$regd->precio_compra",
        "P.V." => "$regd->precio_venta",
        "SUBTOTAL" => "$regd->subtotal"
      );
      $size = $pdf->addLine($y, $line);
      $y   += $size + 2;
    }

    // Convertimos el total en letras
    // require_once "Letras.php";
    // $V = new EnLetras();
    // $con_letra = strtoupper($V->ValorEnLetras(floatval($regv->total_compra), "NUEVOS SOLES"));
    // $pdf->addCadreTVAs("---" . $con_letra);

    $formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
    $izquierda = intval(floor($regv->total_compra));
    $derecha = intval(($regv->total_compra - floor($regv->total_compra)) * 100);

    $texto = $formatterES->format($izquierda) . " NUEVOS SOLES CON " . $formatterES->format($derecha) . " CÉNTIMOS";
    $textoEnMayusculas = mb_strtoupper($texto, 'UTF-8');

    $pdf->addCadreTVAs("---" . utf8_decode($textoEnMayusculas));

    //Mostramos el impuesto
    $pdf->addTVAs($regv->impuesto, $regv->total_compra, "S/ ");
    $pdf->addCadreEurosFrancs("IGV" . " $regv->impuesto %");
    $pdf->Output('Reporte de Venta', 'I');
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
