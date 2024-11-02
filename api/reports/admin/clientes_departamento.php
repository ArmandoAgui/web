<?php
require('../../helpers/admin_report.php');
require('../../models/usuario.php');
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('CLIENTES POR DEPARTAMENTO');

// Se instancia el modelo Usuario para obtener los datos.
$usuario = new Usuario;

// Se establece un color de relleno para los encabezados.
$pdf->setFillColor(255, 87, 87);
$pdf->SetTextColor(255);
// Se establece la fuente para los encabezados.
$pdf->setFont('Arial', 'B', 11);
//Se establece el color del borde de los encabezados.
$pdf->setDrawColor(255);
// Se imprimen las celdas con los encabezados.
$pdf->cell(40, 10, '', 1, 0);
$pdf->cell(80, 10, utf8_decode('Departamento'), 1, 0, 'C', 1);
$pdf->cell(26, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
$pdf->cell(40, 10, '', 1, 1);

// Se establece la fuente para los datos del reporte.
$pdf->setFont('Arial', '', 11);

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataClientesDep = $usuario->clientsDepartment()) {
    $pdf->setDrawColor(255, 87, 87);
    $pdf->SetTextColor(50);
    // Se recorren los registros ($dataClientesDep) fila por fila ($rowCliente).
    foreach ($dataClientesDep as $rowCliente) {
        $pdf->cell(40, 10, '', 0, 0);
        $pdf->cell(80, 10, utf8_decode($rowCliente['depto']), 'B', 0);
        $pdf->cell(26, 10, $rowCliente['clientes'], 'B', 0, 'C');
        $pdf->cell(40, 10, '', 0, 1);
    }
} else {
    $pdf->cell(0, 10, utf8_decode('No hay clientes.'), 1, 1);
}

// Se envía el documento al navegador y se llama al método footer()
$pdf->output('I', 'clientes_departamento.pdf');