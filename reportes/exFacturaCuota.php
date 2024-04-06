<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['cuotas'] == 1) {
    //Incluímos el archivo Factura.php
    require('FacturaCuota.php');

    require_once "../modelos/Perfiles.php";
    $perfil = new Perfiles();
    $rspta = $perfil->mostrarReporte();

    //Establecemos los datos de la empresa
    $logo = $rspta["imagen"];
    $ext_logo = strtolower(pathinfo($rspta["imagen"], PATHINFO_EXTENSION));
    $empresa = $rspta["titulo"];
    $documento = ($rspta["ruc"] == '') ? 'Sin registrar' : $rspta["ruc"];
    $direccion = ($rspta["direccion"] == '') ? 'Sin registrar' : $rspta["direccion"];
    $telefono = ($rspta["telefono"] == '') ? 'Sin registrar' : number_format($rspta["telefono"], 0, '', ' ');
    $email = ($rspta["email"] == '') ? 'Sin registrar' : $rspta["email"];

    //Obtenemos los datos de la cabecera de la cuota actual
    require_once "../modelos/Cuotas.php";
    $cuota = new Cuotas();
    $rsptav = $cuota->ventacabecera($_GET["id"]);
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
      '../files/logo_reportes/' . $logo,
      $ext_logo
    );
    $pdf->fact_dev(utf8_decode($regv->tipo_comprobante), ' ' . $regv->serie_comprobante . ' - ' . $regv->num_comprobante);
    $pdf->temporaire("");
    $pdf->addDate($regv->fecha);
    $pdf->addStatus($regv->estado);
    $pdf->addMetodoPago(($regv->metodo_pago == '') ? 'Sin registrar.' : $regv->metodo_pago);

    //Enviamos los datos del cliente al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse1("Nombre: " . utf8_decode($regv->cliente), "Domicilio: " . utf8_decode($regv->direccionc), $regv->tipo_documentoc . ": " . $regv->num_documentoc, "Email: " . $regv->emailc, "Telefono: " . $regv->telefonoc);

    //Enviamos los datos del vendedor al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse2("Nombre: " . utf8_decode($regv->vendedor), "Domicilio: " . utf8_decode($regv->direccionv), $regv->tipo_documentov . ": " . $regv->num_documentov, "Email: " . $regv->emailv, "Telefono: " . $regv->telefonov);

    $pdf->addAlmacen(utf8_decode($regv->almacen));
    $pdf->addZona(utf8_decode($regv->ubicacion . ' - ' . strtoupper($regv->zona)));

    //Establecemos las columnas que va a tener la sección donde mostramos los detalles de la couta
    $cols = array(
      "CODIGO" => 30,
      "NOMBRE DE PRODUCTO" => 71,
      "CANTIDAD" => 22,
      "P.U." => 25,
      "DSCTO" => 20,
      "SUBTOTAL" => 22
    );
    $pdf->addCols($cols);
    $cols = array(
      "CODIGO" => "L",
      "NOMBRE DE PRODUCTO" => "L",
      "CANTIDAD" => "C",
      "P.U." => "C",
      "DSCTO" => "R",
      "SUBTOTAL" => "C"
    );
    $pdf->addLineFormat($cols);
    $pdf->addLineFormat($cols);
    //Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
    $y = 110;

    //Obtenemos todos los detalles de la cuota actual
    $rsptad = $cuota->ventadetalle($_GET["id"]);

    while ($regd = $rsptad->fetch_object()) {
      $line = array(
        "CODIGO" => "$regd->codigo_producto",
        "NOMBRE DE PRODUCTO" => utf8_decode("$regd->articulo"),
        "CANTIDAD" => "$regd->cantidad",
        "P.U." => "$regd->precio_venta",
        "DSCTO" => "$regd->descuento",
        "SUBTOTAL" => "$regd->subtotal"
      );
      $size = $pdf->addLine($y, $line);
      $y   += $size + 2;
    }

    // Convertimos el total en letras
    // require_once "Letras.php";
    // $V = new EnLetras();
    // $con_letra = strtoupper($V->ValorEnLetras(floatval($regv->total_venta), "NUEVOS SOLES"));
    // $pdf->addCadreTVAs("---" . $con_letra);

    $formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
    $izquierda = intval(floor($regv->total_venta));
    $derecha = intval(($regv->total_venta - floor($regv->total_venta)) * 100);

    $texto = $formatterES->format($izquierda) . " NUEVOS SOLES CON " . $formatterES->format($derecha) . " CÉNTIMOS";
    $textoEnMayusculas = mb_strtoupper($texto, 'UTF-8');

    $pdf->addCadreTVAs("---" . utf8_decode($textoEnMayusculas));

    //Mostramos el impuesto
    $pdf->addTVAs($regv->impuesto, $regv->total_venta, "S/ ");
    $pdf->addCadreEurosFrancs("IGV" . " $regv->impuesto %");
    $pdf->Output('Reporte de Venta por Cuota', 'I');
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
