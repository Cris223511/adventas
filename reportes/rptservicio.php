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
    $pdf->Cell(100, 6, 'LISTA DE SERVICIOS', 1, 0, 'C');
    $pdf->Ln(10);

    //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 6, 'Servicio', 1, 0, 'C', 1);
    $pdf->Cell(60, 6, utf8_decode('Almacén'), 1, 0, 'C', 1);
    $pdf->Cell(35, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
    $pdf->Cell(40, 6, utf8_decode('Código de servicio'), 1, 0, 'C', 1);

    $pdf->Ln(10);
    //Comenzamos a crear las filas de los registros según la consulta mysql
    require_once "../modelos/Servicio.php";
    $servicio = new Servicio();

    $idusuario = $_SESSION["idusuario"];
    $idalmacenSession = $_SESSION["idalmacen"];
    $cargo = $_SESSION["cargo"];

    // if ($cargo == "superadmin") {
    //   $rspta = $servicio->listar();
    // } else {
    $rspta = $servicio->listarPorUsuario($idalmacenSession);
    // }

    //Table with rows and columns
    $pdf->SetWidths(array(50, 60, 35, 40));

    while ($reg = $rspta->fetch_object()) {
      $nombre = $reg->nombre;
      $almacen = $reg->almacen;
      $categoria = (($reg->categoria != "") ? $reg->categoria : "Sin registrar.");
      $codigo = $reg->codigo_producto;

      $pdf->SetFont('Arial', '', 10);
      $pdf->Row(array(utf8_decode($nombre), utf8_decode($almacen), utf8_decode($categoria), $codigo));
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