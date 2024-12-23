<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['proforma'] == 1) {

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
    $pdf->Cell(100, 6, 'LISTA DE PROFORMAS', 1, 0, 'C');
    $pdf->Ln(10);

    //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(37, 6, 'Fecha y hora', 1, 0, 'C', 1);
    $pdf->Cell(46, 6, 'Usuario', 1, 0, 'C', 1);
    $pdf->Cell(34, 6, 'Cliente', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, 'Documento', 1, 0, 'C', 1);
    $pdf->Cell(28, 6, utf8_decode('N° Proforma'), 1, 0, 'C', 1);
    $pdf->Cell(20, 6, 'Total', 1, 0, 'C', 1);

    $pdf->Ln(10);
    //Comenzamos a crear las filas de los registros según la consulta mysql
    require_once "../modelos/Proformas.php";
    $proforma = new Proforma();

    $idusuario = $_SESSION["idusuario"];
    $idalmacenSession = $_SESSION["idalmacen"];
    $cargo = $_SESSION["cargo"];

    if ($cargo == "superadmin") {
      $rspta = $proforma->listar();
    } else {
      $rspta = $proforma->listarPorUsuario($idalmacenSession);
    }

    //Table with rows and columns
    $pdf->SetWidths(array(37, 46, 34, 25, 28, 20));

    while ($reg = $rspta->fetch_object()) {
      $fecha = $reg->fecha;
      $usuario = $reg->usuario . " " . $reg->apellido;
      $cliente = $reg->cliente;
      $tipo_comprobante = "Proforma";
      $num_proforma = $reg->serie_comprobante . "-" . $reg->num_proforma;
      $total_venta = $reg->total_venta;

      $pdf->SetFont('Arial', '', 10);
      $pdf->Row(array($fecha, utf8_decode($usuario), utf8_decode($cliente), $tipo_comprobante, $num_proforma, $total_venta));
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