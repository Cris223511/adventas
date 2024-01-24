<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['cuotas'] == 1) {

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
    $pdf->Cell(100, 6, 'LISTA DE ZONAS', 1, 0, 'C');
    $pdf->Ln(10);

    //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(80, 6, utf8_decode('Ubicación'), 1, 0, 'C', 1);
    $pdf->Cell(40, 6, utf8_decode('Zona'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Usuario'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('F. Creación'), 1, 0, 'C', 1);
    $pdf->Cell(20, 6, utf8_decode('Estado'), 1, 0, 'C', 1);

    $pdf->Ln(10);
    //Comenzamos a crear las filas de los registros según la consulta mysql
    require_once "../modelos/Zonas.php";
    $zonas = new Zonas();

    $rspta = $zonas->listar();

    //Table with filas y columnas
    $pdf->SetWidths(array(80, 40, 25, 25, 20));

    while ($reg = $rspta->fetch_object()) {
      $ubicacion = $reg->ubicacion;
      $zona = $reg->zona;
      $usuario = $reg->usuario . " " . $reg->apellido;
      $fecha_hora = $reg->fecha_hora;
      $estado = $reg->estado;

      $newFecha = strtotime($fecha_hora);
      $resultFecha = date("d-m-Y", $newFecha);

      $pdf->SetFont('Arial', '', 10);
      $pdf->Row(array(
        utf8_decode($ubicacion),
        utf8_decode($zona),
        utf8_decode($usuario),
        utf8_decode($resultFecha),
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