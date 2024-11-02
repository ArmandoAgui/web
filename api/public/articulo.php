<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/articulo.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $articulo = new Articulo;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_usuario'])) {
        $result['session'] = 1;
    } else {
    }
    //Se programan los posibles casos a realizar para ser llamados en controller
    switch ($_GET['action']) {
        //Caso para mostrar todos los articulos
        case 'readAll':
            if ($result['dataset'] = $articulo->readAll()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No hay productos';
            }
            break;
        //Caso para mostrar los articulos de un determinado departamento
        case 'departmentArticle':
            if (!$articulo->setId_departamento($_POST['id_departamento'])) {
                $result['exception'] = 'ID de departamento inválido';
            } elseif ($result['dataset'] = $articulo->departmentArticle()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No hay articulos de este departamento';
            }
            break;
        //Caso para filtrar los articulos con parametros
        case 'filterArticle':
            if (!$articulo->setId_departamento($_POST['id_departamento'])) {
                $result['exception'] = 'ID de departamento inválido';
            } elseif (!$articulo->setId_categoria($_POST['id_categoria'])) {
                $result['exception'] = 'ID de categoria inválido';
            } elseif (!$articulo->setIdTipoArticulo($_POST['id_tipo_articulo'])) {
                $result['exception'] = 'ID de tipo de articulo inválido';
            } elseif ($result['dataset'] = $articulo->filterArticle()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'No hay articulos que conincidan con el filtro';
            }
            break;
        //caso para mostrar la información de un articulo seleccionado
        case 'readOne':
            if (!$articulo->setIdArticulo($_POST['id_articulo'])) {
                $result['exception'] = 'ID de artículo inválido';
            } elseif ($result['dataset'] = $articulo->readOne()) {
                $result['status'] = 1;
            } elseif (Database::getException()) {
                $result['exception'] = Database::getException();
            } else {
                $result['exception'] = 'Articulo inexistente';
            }
            break;
        default:
            $result['exception'] = 'Acción no disponible';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
