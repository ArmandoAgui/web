<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/marca.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if(isset($_GET['action'])){
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $marca = new Marca;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])){
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $marca->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
    //Metodo buscar
            case 'search':
                $_POST = $marca->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $marca->searchRows($_POST['search'])) {
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
                    $_POST = $marca->validateForm($_POST);
                if (!$marca->setMarca($_POST['marca'])) {
                    $result['exception'] = 'Marca incorrecta';
                }elseif (!$marca->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                }elseif ($marca->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Marca agregada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo leer
            case 'readOne':
                if (!$marca->setId($_POST['id'])) {
                    $result['exception'] = 'Marca incorrecta';
                } elseif ($result['dataset'] = $marca->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Marca inexistente';
                }
                break;
    //Metodo actualizar
            case 'update':
                $_POST = $marca->validateForm($_POST);
                if (!$marca->setId($_POST['id_marca'])) {
                    $result['exception'] = 'Marca incorrecto';
                } elseif (!$data = $marca->readOne()) {
                    $result['exception'] = 'Marca inexistente';
                } elseif (!$marca->setMarca($_POST['marca'])) {
                    $result['exception'] = 'Nombre marca incorrecto';
                } elseif (!$marca->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif ($marca->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Marca modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo eliminar
            case 'delete':
                if (!$marca->setId($_POST['id'])) {
                    $result['exception'] = 'Marca incorrecto';
                } elseif (!$marca->readOne()) {
                    $result['exception'] = 'Marca inexistente';
                } elseif ($marca->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Marca eliminada correctamente';
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
