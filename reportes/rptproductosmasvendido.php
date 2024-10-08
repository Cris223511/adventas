<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['reporteP'] == 1) {

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
        $pdf->Cell(100, 6, utf8_decode('LISTA DE PRODUCTOS MÁS VENDIDOS'), 1, 0, 'C');
        $pdf->Ln(10);

        //Creamos las celdas para los títulos de cada columna y le asignamos un fondo gris y el tipo de letra
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(48, 6, 'Nombre', 1, 0, 'C', 1);
        $pdf->Cell(30, 6, utf8_decode('Categoría'), 1, 0, 'C', 1);
        $pdf->Cell(50, 6, utf8_decode('Código'), 1, 0, 'C', 1);
        $pdf->Cell(31, 6, 'Stock', 1, 0, 'C', 1);
        $pdf->Cell(31, 6, utf8_decode('Veces vendido'), 1, 0, 'C', 1);

        $pdf->Ln(10);
        //Comenzamos a crear las filas de los registros según la consulta mysql
        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();

        $rspta = $consulta->articulosmasvendidos();

        //Table with rows and columns
        $pdf->SetWidths(array(48, 30, 50, 31, 31));

        while ($reg = $rspta->fetch_object()) {
            $nombre = $reg->nombre;
            $categoria = (($reg->categoria != "") ? $reg->categoria : "Sin registrar.");
            $codigo = $reg->codigo;
            $stock = $reg->stock;
            $cantidad = $reg->cantidad;

            $pdf->SetFont('Arial', '', 10);
            $pdf->Row(array(utf8_decode($nombre), utf8_decode($categoria), $codigo, $stock, utf8_decode($cantidad)));
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