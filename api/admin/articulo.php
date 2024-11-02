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
    if (isset($_SESSION['id_empleado'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $articulo->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'search':
                $_POST = $articulo->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $articulo->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'readType':
                if ($result['dataset'] = $articulo->readType()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay departamentos registrados';
                }
                break;
            case 'readType1':
                if ($result['dataset'] = $articulo->readType1()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay departamentos registrados';
                }
                break;
            case 'create':
                $_POST = $articulo->validateForm($_POST);
                if (!$articulo->setNombre_articulo($_POST['nombre_articulo'])) {
                    $result['exception'] = 'Nombre del articulo no valido';
                } elseif (!$articulo->setdescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción no valida';
                } elseif (!$articulo->setPrecio($_POST['precio'])) {
                    $result['exception'] = 'Precio no valido';
                } elseif (!$articulo->setId_departamento($_POST['id_departamento'])) {
                    $result['exception'] = 'Departamento no valido';
                } elseif (!$articulo->setIdTipoArticulo($_POST['id_tipo_articulo'])) {
                    $result['exception'] = 'Tipo Articulo no valido';
                } elseif (!$articulo->setId_estado_articulo($_POST['id_estado_articulo'])) {
                    $result['exception'] = 'Estado Articulo no valido';
                } elseif (!$articulo->setId_marca($_POST['id_marca'])) {
                    $result['exception'] = 'Marca no valida';
                } elseif (!$articulo->setId_categoria($_POST['id_categoria'])) {
                    $result['exception'] = 'Categoria no valida';
                } elseif (!is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                    $result['exception'] = 'Seleccione una imagen';
                } elseif (!$articulo->setImagen($_FILES['imagen'])) {
                    $result['exception'] = $articulo->getFileError();
                } elseif ($articulo->createRow()) {
                    $result['status'] = 1;
                    if ($articulo->saveFile($_FILES['imagen'], $articulo->getRuta(), $articulo->getImagen())) {
                        $result['message'] = 'Articulo creado correctamente';
                    } else {
                        $result['message'] = 'Articulo creado pero no se guardó la imagen';
                    }
                } else {
                    $result['exception'] = Database::getException();;
                }
                break;
            case 'readOne':
                if (!$articulo->setIdArticulo($_POST['id_articulo'])) {
                    $result['exception'] = 'Articulo Equivocada';
                } elseif ($result['dataset'] = $articulo->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Articulo inexistente';
                }
                break;
            case 'update':
                $_POST = $articulo->validateForm($_POST);
                if (!$articulo->setIdArticulo($_POST['id_articulo'])) {
                    $result['exception'] = 'Producto invalido';
                } elseif (!$data = $articulo->readOne()) {
                    $result['exception'] = 'Producto invalido';
                } elseif (!$articulo->setNombre_articulo($_POST['nombre_articulo'])) {
                    $result['exception'] = 'Nombre invalido';
                } elseif (!$articulo->setdescripcion($_POST['descripcion'])) {
                    $result['exception'] = 'Descripción invalido';
                } elseif (!$articulo->setPrecio($_POST['precio'])) {
                    $result['exception'] = 'Precio invalido';
                } elseif (!$articulo->setId_departamento($_POST['id_departamento'])) {
                    $result['exception'] = 'Seleccione un tipo';
                } elseif (!$articulo->setIdTipoArticulo($_POST['id_tipo_articulo'])) {
                    $result['exception'] = 'Seleccione un tipo';
                } elseif (!$articulo->setId_estado_articulo($_POST['id_estado_articulo'])) {
                    $result['exception'] = 'Seleccione una estado';
                } elseif (!$articulo->setId_marca($_POST['id_marca'])) {
                    $result['exception'] = 'Seleccione una estado';
                } elseif (!$articulo->setId_categoria($_POST['id_categoria'])) {
                    $result['exception'] = 'Seleccione una categoría';
                } elseif (!is_uploaded_file($_FILES['imagen']['tmp_name'])) {
                    if ($articulo->updateRow($data['imagen'])) {
                        $result['status'] = 1;
                        $result['message'] = 'Artículo modificado correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } elseif (!$articulo->setImagen($_FILES['imagen'])) {
                    $result['exception'] = $articulo->getFileError();
                } elseif ($articulo->updateRow($data['imagen'])) {
                    $result['status'] = 1;
                    if ($articulo->saveFile($_FILES['imagen'], $articulo->getRuta(), $articulo->getImagen())) {
                        $result['message'] = 'Artículo modificado correctamente';
                    } else {
                        $result['message'] = 'Artículo modificado pero no se guardó la imagen';
                    }
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'stockArticulo':
                if ($result['dataset'] = $articulo->stockArticulo()) {
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
    }
} else {
    print(json_encode('Recurso no disponible'));
}
