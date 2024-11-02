<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/factura.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $orden = new Carrito;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $orden->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Metodo Buscar
            case 'search':
                $_POST = $orden->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $orden->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
                //Caso para mostrar los detalles de la orden
            case 'readOrderDetail':
                if (!$orden->setIdFactura($_POST['id_orden'])) {
                    $result['exception'] = 'ID de orden inválido';
                } elseif ($result['dataset'] = $orden->readOrderDetail()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay productos agregados al detalle del pedido. Vuelva en otro momento.';
                }
                break;
                //Metodo leer
            case 'readOne':
                if (!$orden->setIdFactura($_POST['id_orden'])) {
                    $result['exception'] = 'ID de orden no válido';
                } elseif ($result['dataset'] = $orden->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Orden inexistente';
                }
                break;
                //Metodo actualizar
            case 'update':
                if (!$orden->setIdFactura($_POST['id_orden'])) {
                    $result['exception'] = 'ID de pedido no válido';
                } elseif (!$data = $orden->readOne()) {
                    $result['exception'] = 'Orden inexistente';
                } elseif (!$orden->setIdEstadoFactura($_POST['id_estado_factura'])) {
                    $result['exception'] = 'Estado de pedido no válido';
                } elseif ($orden->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Estado de pedido cambiado correctamente.';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readState':
                if ($result['dataset'] = $orden->readState()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay estados registrados';
                }
                break;
            case 'ventasMes':
                if ($result['dataset'] = $orden->ventasMes()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'ventasTopProductosMes':
                if ($result['dataset'] = $orden->ventasTopProductosMes()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            case 'ventasDepartamentoMes':
                if ($result['dataset'] = $orden->ventasDepartamentoMes()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        $result['exception'] = 'Acción no disponible dentro de la sesión';
        //print(json_encode($result));
        //print(json_encode('Acceso denegado'));
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
