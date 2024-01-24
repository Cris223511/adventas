<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['almacen'] == 1) {

    //Inlcuímos a la clase PDF_MC_Table
    require('PDF_MC_Table.php');

    //Instanciamos la clase para generar el documento pdf
    $pdf = new PDF_MC_Table();

    //Agregamos la primera página al documento pdf
    $pdf->AddPage();

    //Seteamos el inicio del margen superior en 25 pixeles 
    $y_axis_initial = 25;

    //Seteamos el tipo de letra y creamos el título de la página. No es un encabezado no se repetirá
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(45, 6, '', 0, 0, 'C');
    $pdf->Cell(100, 6, 'LISTA DE CUOTAS', 1, 0, 'C');
    $pdf->Ln(10);

    //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, 6, utf8_decode('F. Creación'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('F. Anulación'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Cliente'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Vendedor'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Usuario'), 1, 0, 'C', 1);
    $pdf->Cell(22, 6, utf8_decode('T. Venta'), 1, 0, 'C', 1);
    $pdf->Cell(22, 6, utf8_decode('M. Pagado'), 1, 0, 'C', 1);
    $pdf->Cell(22, 6, utf8_decode('Estado'), 1, 0, 'C', 1);

    $pdf->Ln(10);
    //Comenzamos a crear las filas de los registros según la consulta mysql
    require_once "../modelos/Cuotas.php";
    $cuotas = new Cuotas();

    $idusuario = $_SESSION["idusuario"];
    $idalmacenSession = $_SESSION["idalmacen"];
    $cargo = $_SESSION["cargo"];

    if ($cargo == "superadmin") {
      $rspta = $cuotas->listar();
    } else {
      $rspta = $cuotas->listarPorUsuario($idalmacenSession);
    }

    //Table with filas y columnas
    $pdf->SetWidths(array(25, 25, 25, 25, 25, 22, 22, 22));

    while ($reg = $rspta->fetch_object()) {
      $fecha = $reg->fecha;
      $anulado = $reg->anulado;
      $cliente = $reg->cliente . " " . $reg->cliente_apellido;
      $vendedor = $reg->vendedor . " " . $reg->vendedor_apellido;
      $usuario = $reg->usuario . " " . $reg->apellido;
      $total_venta = $reg->total_venta;
      $monto_pagado = $reg->monto_pagado;
      $estado = $reg->estado;

      $fecha1 = strtotime($fecha);
      $fecha1result = date("d-m-Y", $fecha1);

      $fecha2 = strtotime($anulado);
      $fecha2result = date("d-m-Y", $fecha2);

      $pdf->SetFont('Arial', '', 10);
      $pdf->Row(array(
        utf8_decode($fecha1result),
        utf8_decode($fecha2result == "30-11--0001" ? "00-00-0000" : $fecha2result),
        utf8_decode($cliente),
        utf8_decode($vendedor),
        utf8_decode($usuario),
        utf8_decode($total_venta),
        utf8_decode($monto_pagado),
        utf8_decode($estado),
      ));
    }

    //Mostramos el documento pdf
    $pdf->Output();

?>
<?php
  } else {
    echo 'No tiene permiso para visualizar el reporte';
  }
}
ob_end_flush();
?>