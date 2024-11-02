<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../../helpers/public_report.php');
    require('../../models/factura.php');

    // Se instancia el módelo Factura para procesar los datos.
    $orden = new Carrito;

    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($orden->setIdFactura($_GET['id'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowFactura = $orden->readOne()) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('COMPROBANTE DE COMPRA');

            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(255, 87, 87);
            $pdf->SetTextColor(255);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Arial', 'B', 11);

            $pdf->cell(80, 10, utf8_decode('Cliente'), 0, 1, 'C', 1);
            $pdf->SetTextColor(50);
            $pdf->setDrawColor(255, 87, 87);
            $pdf->setFont('Arial', '', 11);
            $pdf->cell(80, 10, utf8_decode($rowFactura['nombre_completo']), 'B', 1, 'C');
            $pdf->cell(80, 10, utf8_decode($rowFactura['num_telefono']), 'B', 0, 'C');
            $pdf->cell(36);
            $pdf->SetTextColor(255);
            $pdf->cell(70, 10, utf8_decode('N° Pedido'), 0, 1, 'C', 1);
            $pdf->SetTextColor(50);
            $pdf->cell(80, 10, utf8_decode($rowFactura['correo_electronico']), 'B', 0, 'C');
            $pdf->SetTextColor(50);
            $pdf->cell(36);
            $pdf->cell(70, 10, $_GET['id'], 'B', 1, 'C');
            $pdf->ln(10);
            //Se establece el color del borde de los encabezados.
            $pdf->setDrawColor(255);
            $pdf->SetTextColor(255);
            // Se imprimen las celdas con los encabezados.
            $pdf->cell(80, 10, utf8_decode('Nombre artículo'), 1, 0, 'C', 1);
            $pdf->cell(46, 10, utf8_decode('Tipo artículo'), 1, 0, 'C', 1);
            $pdf->cell(20, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
            $pdf->cell(20, 10, utf8_decode('Precio'), 1, 0, 'C', 1);
            $pdf->cell(20, 10, utf8_decode('Subtotal'), 1, 1, 'C', 1);

            // Se establece la fuente para los datos de los productos que lleva.
            $pdf->setFont('Arial', '', 11);

            // Se verifica si existen registros (detalle_factura) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataOrden = $orden->readOrderDetail()) {
                $pdf->setDrawColor(255, 87, 87);
                $pdf->SetTextColor(50);
                // Se recorren los registros ($dataOrden) fila por fila ($rowOrden).
                foreach ($dataOrden as $rowOrden) {
                    $pdf->cell(80, 10, utf8_decode($rowOrden['nombre_articulo']), 'B', 0);
                    $pdf->cell(46, 10, utf8_decode($rowOrden['tipo_articulo']), 'B', 0, 'C');
                    $pdf->cell(20, 10, $rowOrden['cantidad'], 'B', 0, 'C');
                    $pdf->cell(20, 10, '$' . $rowOrden['precio_articulo'], 'B', 0, 'C');
                    $pdf->cell(20, 10, '$' . $rowOrden['sub_total'], 'B', 1, 'C');
                }
            } else {
                $pdf->cell(0, 10, utf8_decode('No hay detalle de productos'), 1, 1);
            }
            $pdf->ln(20);
            $pdf->SetTextColor(255);
            $pdf->cell(116);
            $pdf->cell(70, 10, utf8_decode('Total'), 0, 1, 'C', 1);
            $pdf->SetTextColor(50);
            $pdf->cell(116);
            $pdf->cell(70, 10, '$' . $rowFactura['total'], 'B', 1, 'C');
            // Se envía el documento al navegador y se llama al método footer()
            $pdf->output('I', 'productos.pdf');
        } else {
            header('location: ../../../views/public/');
        }
    } else {
        header('location: ../../../views/public/');
    }
} else {
    header('location: ../../../views/public/');
}
