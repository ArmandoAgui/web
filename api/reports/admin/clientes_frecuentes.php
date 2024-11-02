<?php
require('../../helpers/admin_report.php');
require('../../models/factura.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('CLIENTES FRECUENTES DEL MES');

// Se instancia el módelo Categorías para obtener los datos.
$factura = new Carrito;

// Se establece un color de relleno para los encabezados.
$pdf->setFillColor(255, 87, 87);
$pdf->SetTextColor(255);
// Se establece la fuente para los encabezados.
$pdf->setFont('Arial', 'B', 11);
//Se establece el color del borde de los encabezados.
$pdf->setDrawColor(255);
// Se imprimen las celdas con los encabezados.
$pdf->cell(75, 10, utf8_decode('Nombre Completo'), 1, 0, 'C', 1);
$pdf->cell(65, 10, utf8_decode('Correo Electrónico'), 1, 0, 'C', 1);
$pdf->cell(23, 10, utf8_decode('Estado'), 1, 0, 'C', 1);
$pdf->cell(23, 10, utf8_decode(''), 1, 1, 'C', 0);

// Se establece un color de relleno para mostrar la información de clientes.
$pdf->setFillColor(252, 109, 109);
// Se establece la fuente para los datos de los clientes.
$pdf->setFont('Arial', '', 11);

// Se imprime una celda con el nombre del cliente y la cantidad.
$pdf->cell(163, 10, utf8_decode('Clientes por número de compras'), 1, 0, 'C', 1);
$pdf->cell(23, 10, utf8_decode('Cantidad'), 1, 1, 'C', 1);

// Se verifica si existen registros (clientes) para mostrar, de lo contrario se imprime un mensaje.
if ($dataClientesVentas = $factura->clientesVentas()) {
    $pdf->setDrawColor(255, 87, 87);
    $pdf->SetTextColor(50);
    // Se recorren los registros ($dataClientesVentas) fila por fila ($rowCliente).
    foreach ($dataClientesVentas as $rowCliente) {
        $pdf->cell(75, 10, utf8_decode($rowCliente['nombre_completo']), 'B', 0);
        $pdf->cell(65, 10, utf8_decode($rowCliente['correo_electronico']), 'B', 0);
        if($rowCliente['estado']){
            $pdf->cell(23, 10, 'Activo', 'B', 0, 'C');
        }else{
            $pdf->cell(23, 10, 'Inactivo', 'B', 0, 'C');
        }
        $pdf->cell(23, 10, $rowCliente['cantidad'], 'B', 1, 'C');
    }
} else {
    $pdf->cell(0, 10, utf8_decode('No hay ventas este mes'), 1, 1);
}

// Se establece un color de relleno para mostrar el nombre del cliente.
$pdf->SetTextColor(255);
$pdf->setDrawColor(255);

$pdf->cell(0, 10, '', 0, 1);

// Se imprime una celda con los encabezados establecidos.
$pdf->cell(163, 10, utf8_decode('Clientes por monto de compra'), 1, 0, 'C', 1);
$pdf->cell(23, 10, utf8_decode('Monto'), 1, 1, 'C', 1);

// Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
if ($dataClientesVentas = $factura->clientesMonto()) {
    $pdf->setDrawColor(255, 87, 87);
    $pdf->SetTextColor(50);
    // Se recorren los registros ($dataClientesVentas) fila por fila ($rowCliente).
    foreach ($dataClientesVentas as $rowCliente) {
        $pdf->cell(75, 10, utf8_decode($rowCliente['nombre_completo']), 'B', 0);
        $pdf->cell(65, 10, utf8_decode($rowCliente['correo_electronico']), 'B', 0);
        if($rowCliente['estado']){
            $pdf->cell(23, 10, 'Activo', 'B', 0, 'C');
        }else{
            $pdf->cell(23, 10, 'Inactivo', 'B', 0, 'C');
        }
        $pdf->cell(23, 10, $rowCliente['total'], 'B', 1, 'C');
    }
} else {
    $pdf->cell(0, 10, utf8_decode('No hay ventas este mes'), 1, 1);
}

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'clientes_frecuentes_mes.pdf');