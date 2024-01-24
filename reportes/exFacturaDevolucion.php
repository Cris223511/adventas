<?php
// Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['devolucion'] == 1) {
    // Incluímos el archivo FacturaDevolucion.php
    require('FacturaDevolucion.php');

    // Establecemos los datos de la empresa
    $logo = "logo.jpeg";
    $ext_logo = "jpg";
    $empresa = "Arena San Andrés Perú S.A.C.";
    $documento = "20477157772";
    $direccion = "Av Gerardo Unger 5689 - Los Olivos - Lima";
    $telefono = "998 393 220";
    $email = "jcarlos.ad7@gmail.com";

    // Obtenemos los datos de la cabecera de la devolucion actual
    require_once "../modelos/Devoluciones.php";
    $devolucion = new Devolucion();
    $rsptas = $devolucion->devolucioncabecera($_GET["id"]);
    // Recorremos todos los valores obtenidos
    $regs = $rsptas->fetch_object();

    // Establecemos la configuración de la factura
    $pdf = new PDF_Invoice('P', 'mm', 'A4');
    $pdf->AddPage();

    // Enviamos los datos de la empresa al método addSociete de la clase Factura
    $pdf->addSociete(
      utf8_decode($empresa),
      $documento . "\n" .
        utf8_decode("Dirección: ") . utf8_decode($direccion) . "\n" .
        utf8_decode("Teléfono: ") . $telefono . "\n" .
        "Email: " . $email,
      $logo,
      $ext_logo
    );

    $pdf->fact_dev(utf8_decode("Cod. LCL: N° $regs->codigo_pedido"));
    $pdf->fact_dev2(utf8_decode($regs->estado));

    $pdf->temporaire("");
    $pdf->FancyTable("", "");

    $pdf->addDate1(($regs->fecha_hora_devolucion == "01-01-2000 00:00:00") ? "Sin registrar" : $regs->fecha_hora_devolucion);
    $pdf->addDate2($regs->fecha_hora_pedido);

    // Enviamos los datos del ENCARGADO al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse1("Nombres: " . utf8_decode($regs->responsable_pedido) . " " . utf8_decode($regs->responsable_pedido_apellido), "Domicilio: " . utf8_decode($regs->direccion_pedido), $regs->tipo_documento_pedido . ": " . $regs->num_documento_pedido, "Email: " . $regs->email_pedido, "Telefono: " . $regs->telefono_pedido);

    // Enviamos los datos del DESPACHADOR al método addClientAdresse de la clase Factura
    $pdf->addClientAdresse2("Nombres: " . utf8_decode($regs->responsable_despacho) . " " . utf8_decode($regs->responsable_despacho_apellido), "Domicilio: " . utf8_decode($regs->direccion_despacho), $regs->tipo_documento_despacho . ": " . $regs->num_documento_despacho, "Email: " . $regs->email_despacho, "Telefono: " . $regs->telefono_despacho);

    // Establecemos las columnas que va a tener la sección donde mostramos los detalles de la devolucion
    $cols = array(
      "CODIGO" => 34,
      "DESCRIPCION" => 80,
      "CANTIDAD" => 28,
      "C. DEVUELTA" => 28,
      "P.V." => 20
    );

    $pdf->addCols($cols);
    $cols = array(
      "CODIGO" => "L",
      "NOMBRE DE PRODUCTO" => "L",
      "CANTIDAD" => "C",
      "C. DEVUELTA" => "C",
      "P.V." => "C"
    );

    $pdf->addLineFormat($cols);
    $pdf->addLineFormat($cols);

    // Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
    $y = 84;

    // Obtenemos todos los detalles de la devolucion actual
    $rsptad = $devolucion->devoluciondetalle($_GET["id"]);
    $total_venta = 0;
    $total_cantidad = 0;

    while ($regd = $rsptad->fetch_object()) {
      $line = array(
        "CODIGO" => "$regd->codigo_producto",
        "NOMBRE DE PRODUCTO" => utf8_decode("$regd->nombre"),
        "CANTIDAD" => "$regd->cantidad",
        "C. DEVUELTA" => "$regd->cantidad_devuelta",
        "P.V." => $regd->precio_venta == '' ? "0.00" : $regd->precio_venta
      );
      $size = $pdf->addLine($y, $line);
      $y   += $size + 2;

      $total_venta = $total_venta + $regd->precio_venta;
      $total_cantidad = $total_cantidad + $regd->cantidad;
    }

    // Convertimos el total en letras
    // require_once "Letras.php";
    // $V = new EnLetras();
    // $con_letra = strtoupper($V->ValorEnLetras(floatval($total_venta), "NUEVOS SOLES"));
    // $pdf->addCadreTVAs("---" . $con_letra);

    $formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
    $izquierda = intval(floor($total_venta));
    $derecha = intval(($total_venta - floor($total_venta)) * 100);

    $texto = $formatterES->format($izquierda) . " NUEVOS SOLES CON " . $formatterES->format($derecha) . " CÉNTIMOS";
    $textoEnMayusculas = mb_strtoupper($texto, 'UTF-8');

    $pdf->addCadreTVAs("---" . utf8_decode($textoEnMayusculas));

    // Mostramos el impuesto
    $pdf->addTVAs($total_cantidad, $total_venta);
    $pdf->addCadreEurosFrancs();

    // Firmas
    $pdf->firma1();
    $pdf->firma2();

    $pdf->Output('Reporte de Devolucion.pdf', 'I');
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>

<style>

</style>