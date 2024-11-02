<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/resena.php');

//Se comprueba si existe una accion a realizar, de lo contrario se finaliza el script con un mnensaje de error
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $resena = new Resena;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                //Metodo Crear o agregar
            case 'create':
                $_POST = $resena->validateForm($_POST);
                if (!$resena->setIdArticulo($_POST['id_articulo'])) {
                    $result['exception'] = 'Id de articulo invalido';
                } elseif (!$resena->setTitulo($_POST['titulo'])) {
                    $result['exception'] = 'Titulo incorrecto';
                } elseif (!$resena->setDescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripcion incorrecta';
                } elseif (!$resena->setEstrellas($_POST['estrellas'])) {
                    $result['exception'] = 'No ha ingresado estrellas';
                } elseif ($resena->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Reseña creada correctamente';
                } else {
                    //$result['exception'] = Database::getException();
                    $result['exception'] = 'Debe de haber comprado el producto para poder realizar una reseña';
                }
                break;
                //Metodo eliminar
            case 'delete':
                if (!$resena->setIdResena($_POST['id'])) {
                    $result['exception'] = 'Reseña incorrecta';
                } elseif (!$resena->readOne()) {
                    $result['exception'] = 'Reseña inexistente';
                } elseif ($resena->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Reseña eliminada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        $result['exception'] = 'Debe de iniciar sesion para poder agregar una reseña';
    }
    switch ($_GET['action']) {
            //Caso para leer las reseñas
        case 'readReviews':
            if (!$resena->setIdResena($_POST['id_articulo'])) {
                $result['exception'] = 'ID inválido';
            } elseif ($result['dataset'] = $resena->readReviewState($_POST['id_articulo'])) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No hay reseñas';
            }
            break;
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
