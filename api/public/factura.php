<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/factura.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $factura = new Carrito;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            //Caso para crear un detalle de a factura
            case 'createDetail':
                $_POST = $factura->validateForm($_POST);
                if (!$factura->startOrder()) {
                    $result['exception'] = 'Ocurrió un problema al obtener el pedido';
                } elseif (!$factura->setIdArticulo($_POST['id_producto'])) {
                    $result['exception'] = 'Producto incorrecto';
                } elseif (!$factura->setCantidad($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif (!$factura->setSubTotal($_POST['sub_total'])) {
                    $result['exception'] = 'Sub total incorrecto';
                } elseif ($factura->createDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto agregado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            //Caso para mostrar los detalles de la factura
            case 'readOrderDetail':
                if (!$factura->startOrder()) {
                    $result['exception'] = 'Debe agregar un producto al carrito';
                } elseif ($result['dataset'] = $factura->readOrderDetail()) {
                    $result['status'] = 1;
                    $_SESSION['id_factura'] = $factura->getId_factura();
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No tiene productos en el carrito';
                }
                break;
            //Caso para actualizar el detaller de una factura
            case 'updateDetail':
                if (!$factura->setIdDetalleFactura($_POST['id_detalle_factura'])) {
                    $result['exception'] = 'Detalle incorrecto';
                } elseif (!$factura->setCantidad($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad incorrecta';
                } elseif (!$factura->setSubTotal($_POST['sub_total'])) {
                    $result['exception'] = 'Sub total incorrecta';
                } elseif ($factura->updateDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cantidad modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            //Caso para borrar un detaller de una factura
            case 'deleteDetail':
                if (!$factura->setIdDetalleFactura($_POST['id_detalle_factura'])) {
                    $result['exception'] = 'Detalle incorrecto';
                } elseif ($factura->deleteDetail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Producto removido correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al remover el producto';
                }
                break;
            //Caso para finalizar una orden
            case 'finishOrder':
                if (!$factura->setTotal($_POST['total'])) {
                    $result['exception'] = 'Dato de total inválido';
                } elseif ($factura->finishOrder()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido finalizado correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al finalizar el pedido';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            //Caso para crear un detalle en una factura
            case 'createDetail':
                $result['exception'] = 'Debe iniciar sesión para agregar el producto al carrito';
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}