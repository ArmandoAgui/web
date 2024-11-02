<?php
require('../../helpers/admin_report.php');
require('../../models/articulo.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('ARTICULOS POR ESTADO');

// Se instancia el módelo Articulo para obtener los datos.
$articulo = new Articulo;
// Se verifica si existen registros (estados) para mostrar, de lo contrario se imprime un mensaje.
if ($dataEstados = $articulo->readAllEstados()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(255, 87, 87);
    //Se establece el color de texto de los encabezados
    $pdf->SetTextColor(255);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    //Se establece el color del borde de los encabezados.
    $pdf->setDrawColor(255);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(15, 10, utf8_decode('ID'), 1 , 0, 'C', 1);
    $pdf->cell(114, 10, utf8_decode('Nombre Artículo'), 1, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Tipo Artículo'), 1, 0, 'C', 1);
    $pdf->cell(27, 10, utf8_decode('Precio (US$)'), 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre del estado.
    $pdf->setFillColor(252, 109, 109);
    // Se establece la fuente para los datos de los articulos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros ($dataEstados) fila por fila ($rowEstado).
    foreach ($dataEstados as $rowEstado) {
        //Se define el color de texto del estado.
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre del estado.
        $pdf->cell(0, 10, utf8_decode('Estado: '.$rowEstado['estado_articulo']), 1, 1, 'C', 1);

        // Se establece el estado para obtener sus articulos, de lo contrario se imprime un mensaje de error.
        if ($articulo->setId_estado_articulo($rowEstado['id_estado_articulo'])) {
            // Se verifica si existen registros (articulos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataArticulos = $articulo->articulosEstado()) {
                $pdf->setDrawColor(255, 87, 87);
                $pdf->SetTextColor(50);
                // Se recorren los registros ($dataArticulos) fila por fila ($rowArticulo).
                foreach ($dataArticulos as $rowArticulo) {
                    // Se imprimen las celdas con los datos de los articulos.
                    $pdf->cell(15, 10, $rowArticulo['id_articulo'], 'B', 0, 'C');
                    $pdf->cell(114, 10, utf8_decode($rowArticulo['nombre_articulo']), 'B', 0);
                    $pdf->cell(30, 10, utf8_decode($rowArticulo['tipo_articulo']), 'B', 0);
                    $pdf->cell(27, 10, $rowArticulo['precio'], 'B', 1, 'C');
                }
            } else {
                $pdf->SetTextColor(50);
                $pdf->cell(0, 10, utf8_decode('No hay articulos para este estado'), 'B', 1);
            }
        } else {
            $pdf->cell(0, 10, utf8_decode('Estado incorrecto o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, utf8_decode('No hay estados para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'Articulos_estado_reporte.pdf');
