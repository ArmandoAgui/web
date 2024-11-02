<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/existencia.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if(isset($_GET['action'])){
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $existencia = new Existencia;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])){
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $existencia->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
    //Metodo Buscar
            case 'search':
                $_POST = $existencia->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $existencia->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
    //Metodo Crear o agregar
            case 'create':
                $_POST = $existencia->validateForm($_POST);
                if (!$existencia->setIdArticulo($_POST['id_articulo'])) {
                    $result['exception'] = 'ID inválido';
                } elseif (!$existencia->setCantidad($_POST['cantidad'])) {
                    $result['exception'] = 'Cantidad inválida';
                } elseif (!$existencia->setFecha($_POST['fecha'])) {
                    $result['exception'] = 'Fecha inválida';
                } elseif ($existencia->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Ingreso registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));

    }else {
        print(json_encode('Acceso denegado'));
    }

}else{
    print(json_encode('Recurso no disponible'));
}
