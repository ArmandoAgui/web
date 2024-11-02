<?php
require('../../helpers/admin_report.php');
require('../../models/articulo.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('EXISTENCIAS POR DEPARTAMENTO');

// Se instancia el modelo Articulo para obtener los datos.
$articulo = new Articulo;
// Se verifica si existen registros (departamentos) para mostrar, de lo contrario se imprime un mensaje.
if ($dataDepartamentos = $articulo->readAllDeps()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(255, 87, 87);
    //Se establece el color del texto de los encabezados
    $pdf->setTextColor(255);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    //Se establece el color del borde de los encabezados.
    $pdf->setDrawColor(255);
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(15, 10, utf8_decode('ID'), 1, 0, 'C', 1);
    $pdf->cell(126, 10, utf8_decode('Nombre Artículo'), 1, 0, 'C', 1);
    $pdf->cell(30, 10, utf8_decode('Tipo Artículo'), 1, 0, 'C', 1);
    $pdf->cell(15, 10, utf8_decode('Stock'), 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre del departamento.
    $pdf->setFillColor(252, 109, 109);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros ($dataCategorias) fila por fila ($rowDepartamento).
    foreach ($dataDepartamentos as $rowDepartamento) {
        //Se define el color de texto del departamento.
        $pdf->SetTextColor(255);
        $pdf->setDrawColor(255);
        // Se imprime una celda con el nombre de la departamento.
        $pdf->cell(0, 10, utf8_decode('Departamento: '.$rowDepartamento['departamento']), 1, 1, 'C', 1);

        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($articulo->setId_departamento($rowDepartamento['id_departamento'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $articulo->stockArticulos()) {
                $pdf->setDrawColor(255, 87, 87);
                $pdf->SetTextColor(50);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    $pdf->cell(15, 10, $rowProducto['id_articulo'], 'B', 0, 'C');
                    $pdf->cell(126, 10, utf8_decode($rowProducto['nombre_articulo']), 'B', 0);
                    $pdf->cell(30, 10, utf8_decode($rowProducto['tipo_articulo']), 'B', 0);
                    $pdf->cell(15, 10, $rowProducto['stock'], 'B', 1, 'C');
                }
            } else {
                $pdf->SetTextColor(50);
                $pdf->setDrawColor(255, 87, 87);
                $pdf->cell(0, 10, utf8_decode('No hay productos para este departamento'), 'B', 1);
            }
        } else {
            $pdf->cell(0, 10, utf8_decode('Departamento no válido o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->cell(0, 10, utf8_decode('No hay departamentos para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'Stock_departamento_reporte.pdf');