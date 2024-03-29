<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
  session_start();

if (!isset($_SESSION["nombre"])) {
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
  if ($_SESSION['compras'] == 1) {

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
    $pdf->Cell(100, 6, 'LISTA DE PROVEEDORES', 1, 0, 'C');
    $pdf->Ln(10);

    //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(58, 6, 'Nombre', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, 'Documento', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, utf8_decode('Número'), 1, 0, 'C', 1);
    $pdf->Cell(26, 6, utf8_decode('Teléfono'), 1, 0, 'C', 1);
    $pdf->Cell(55, 6, 'Email', 1, 0, 'C', 1);

    $pdf->Ln(10);
    //Comenzamos a crear las filas de los registros según la consulta mysql
    require_once "../modelos/Persona.php";
    $persona = new Persona();

    $idusuario = $_SESSION["idusuario"];
    $cargo = $_SESSION["cargo"];

    $rspta = $persona->listarp();

    //Table with rows and columns
    $pdf->SetWidths(array(58, 25, 25, 26, 55));

    while ($reg = $rspta->fetch_object()) {
      $nombre = $reg->nombre;
      $tipo_documento = $reg->tipo_documento == '' ? 'Sin registrar.' : $reg->tipo_documento;
      $num_documento = $reg->num_documento == '' ? 'Sin registrar.' : $reg->num_documento;
      $telefono = $reg->telefono == '' ? 'Sin registrar.' : $reg->telefono;
      $email = $reg->email == '' ? 'Sin registrar.' : $reg->email;

      $pdf->SetFont('Arial', '', 10);
      $pdf->Row(array(utf8_decode($nombre), $tipo_documento, $num_documento, utf8_decode($telefono), $email));
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