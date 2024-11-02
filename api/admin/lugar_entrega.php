<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/lugar_entrega.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if(isset($_GET['action'])){
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $lugar_entrega = new LugarEntrega;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])){
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $lugar_entrega->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
    //Metodo buscar
            case 'search':
                $_POST = $lugar_entrega->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $lugar_entrega->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
    //Metodo crear o agregar
            case 'create':
                $_POST = $lugar_entrega->validateForm($_POST);
                if (!$lugar_entrega->setLugar($_POST['lugar_entrega'])) {
                    $result['exception'] = 'Lugar entrega incorrecto';
                }elseif (!$lugar_entrega->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion incorrecta';
                }elseif (!$lugar_entrega->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                }elseif ($lugar_entrega->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Lugar entrega creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo leer
            case 'readOne':
                if (!$lugar_entrega->setId($_POST['id'])) {
                    $result['exception'] = 'Lugar entrega incorrecta';
                } elseif ($result['dataset'] = $lugar_entrega->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Lugar entrega inexistente';
                }
                break;
    //Metodo actualizar
            case 'update':
                $_POST = $lugar_entrega->validateForm($_POST);
                if (!$lugar_entrega->setId($_POST['id_lugar_entrega'])) {
                     $result['exception'] = 'Lugar entrega incorrecta';
                } elseif (!$data = $lugar_entrega->readOne()) {
                    $result['exception'] = 'Lugar entrega inexistente';
                } elseif (!$lugar_entrega->setLugar($_POST['lugar_entrega'])) {
                    $result['exception'] = 'Nombre lugar entrega incorrecta';
                }elseif (!$lugar_entrega->setDireccion($_POST['direccion'])) {
                    $result['exception'] = 'Direccion incorrecta';
                }elseif (!$lugar_entrega->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                }elseif ($lugar_entrega->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Lugar entrega modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;  
    //Metodo eliminar                   
            case 'delete':
                if (!$lugar_entrega->setId($_POST['id'])) {
                    $result['exception'] = 'Lugar entrega incorrecta';
                } elseif (!$lugar_entrega->readOne()) {
                    $result['exception'] = 'Lugar entrega inexistente';
                } elseif ($lugar_entrega->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Lugar entrega eliminada correctamente';
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
