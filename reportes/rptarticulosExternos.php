<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['almacen'] == 1 && ($_SESSION["cargo"] == "superadmin" || $_SESSION["cargo"] == "encargado")) {

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
    $pdf->Cell(100, 6, 'LISTA DE ARTICULOS', 1, 0, 'C');
    $pdf->Ln(10);

    //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 6, 'Nombre', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
    $pdf->Cell(50, 6, utf8_decode('Código'), 1, 0, 'C', 1);
    $pdf->Cell(25, 6, 'Stock normal', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Stock mínimo'), 1, 0, 'C', 1);

    $pdf->Ln(10);
    //Comenzamos a crear las filas de los registros según la consulta mysql
    require_once "../modelos/ArticuloExterno.php";
    $articulo = new ArticuloExterno();

    $idusuario = $_SESSION["idusuario"];
    $cargo = $_SESSION["cargo"];

    $rspta = $articulo->listar($idalmacenSession);

    //Table with rows and columns
    $pdf->SetWidths(array(60, 25, 50, 25, 25));

    while ($reg = $rspta->fetch_object()) {
      $nombre = $reg->nombre;
      $categoria = (($reg->categoria != "") ? $reg->categoria : "Sin registrar.");
      $codigo = $reg->codigo_producto;
      $stock = $reg->stock;
      $stock_minimo = $reg->stock_minimo;

      $pdf->SetFont('Arial', '', 10);
      $pdf->Row(array(utf8_decode($nombre), utf8_decode($categoria), $codigo, $stock, $stock_minimo));
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