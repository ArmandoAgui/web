<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/resena.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if(isset($_GET['action'])){
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $resena = new Resena;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])){
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readReviews':
                if (!$resena->setIdResena($_POST['id_articulo'])) {
                    $result['exception'] = 'ID inválido';
                } elseif ($result['dataset'] = $resena->searchRows($_POST['id_articulo'])) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay reseñas';
                }
                break;
            //Actualizar estado de las reviews
            case 'updateState':
                if (!$resena->setIdResena($_POST['id_resena'])) {
                    $result['exception'] = 'ID inválido';
                } elseif ($resena->updateState()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cambio de estado de reseña realizado correctamente.';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo Buscar
            case 'search':
                $_POST = $resena->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $resena->searchRows($_POST['search'])) {
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
                $_POST = $resena->validateForm($_POST);
                if (!$resena->setCategoria($_POST['categoria'])) {
                    $result['exception'] = 'Categoria incorrecta';
                } elseif (!$resena->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                }elseif ($resena->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'categoria creada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo leer
            case 'readOne':
                if (!$resena->setId($_POST['id'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif ($result['dataset'] = $resena->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Categoría inexistente';
                }
                break;
    //Metodo actualizar
            case 'update':
                $_POST = $resena->validateForm($_POST);
                if (!$resena->setId($_POST['id_categoria'])) {
                    $result['exception'] = 'Categoría incorrecta';
                } elseif (!$data = $resena->readOne()) {
                    $result['exception'] = 'Categoría inexistente';
                } elseif (!$resena->setCategoria($_POST['categoria'])) {
                    $result['exception'] = 'Nombre categoria incorrecta';
                }elseif (!$resena->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado incorrecto';
                }elseif ($resena->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'categoria modificada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo eliminar
            case 'delete':
                if (!$resena->setId($_POST['id'])) {
                    $result['exception'] = 'Categoria incorrecta';
                } elseif (!$resena->readOne()) {
                    $result['exception'] = 'categoria inexistente';
                } elseif ($resena->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'categoria eliminada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
    //Metodo para grafica
            case 'mejorCalificacionProductos':
                if ($result['dataset'] = $resena->mejorCalificacionProductos()) {
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'No hay datos disponibles';
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
