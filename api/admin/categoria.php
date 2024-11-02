<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/categoria.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $categoria = new Categoria;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $categoria->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
                //Metodo Buscar
            case 'search':
                $_POST = $categoria->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $categoria->searchRows($_POST['search'])) {
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
                $_POST = $categoria->validateForm($_POST);
                if (!$categoria->setCategoria($_POST['categoria'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif (!$categoria->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                } elseif ($categoria->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'categoria creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Metodo leer
            case 'readOne':
                if (!$categoria->setIdCategoria($_POST['id'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif ($result['dataset'] = $categoria->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Categoría inexistente';
                }
                break;
                //Metodo actualizar
            case 'update':
                $_POST = $categoria->validateForm($_POST);
                if (!$categoria->setIdCategoria($_POST['id_categoria'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif (!$data = $categoria->readOne()) {
                    $result['exception'] = 'Categoría inexistente';
                } elseif (!$categoria->setCategoria($_POST['categoria'])) {
                    $result['exception'] = 'Nombre categoria incorrecta';
                } elseif ($categoria->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Categoría modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
                //Metodo eliminar
            case 'delete':
                if (!$categoria->setIdCategoria($_POST['id'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif (!$categoria->readOne()) {
                    $result['exception'] = 'categoria inexistente';
                } elseif ($categoria->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cambio de estado de categoria realizado correctamente';
                } else {
                    $result['exception'] = Database::getException();
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
