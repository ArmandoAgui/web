<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/empleado.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $empleado = new Empleado;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null, 'dataset' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'getUser':
                if (isset($_SESSION['usuario_empleado'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['usuario_empleado'];
                } else {
                    $result['exception'] = 'Alias de empleado indefinido';
                }
                break;
            case 'logOut':
                unset($_SESSION['id_empleado']);
                if (!isset($_SESSION['id_empleado'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión cerrada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $empleado->readProfile()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'empleado inexistente';
                }
                break;
            case 'editProfile':
                $_POST = $empleado->validateForm($_POST);
                if (!$empleado->setNombres($_POST['nombres'])) {
                    $result['exception'] = 'Nombres incorrectos';
                } elseif (!$empleado->setApellidos($_POST['apellidos'])) {
                    $result['exception'] = 'Apellidos incorrectos';
                } elseif (!$empleado->setCorreo($_POST['correo'])) {
                    $result['exception'] = 'Correo incorrecto';
                } elseif ($empleado->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'changePassword':
                $_POST = $empleado->validateForm($_POST);
                if (!$empleado->setId($_SESSION['id_empleado'])) {
                    $result['exception'] = 'empleado incorrecto';
                } elseif (!$empleado->checkPassword($_POST['actual'])) {
                    $result['exception'] = 'Clave actual incorrecta';
                } elseif ($_POST['nueva'] != $_POST['confirmar']) {
                    $result['exception'] = 'Claves nuevas diferentes';
                } elseif (!$empleado->setClave($_POST['nueva'])) {
                    $result['exception'] = $empleado->getPasswordError();
                } elseif ($empleado->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $empleado->readAll()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay datos registrados';
                }
                break;
            case 'readType':
                if ($result['dataset'] = $empleado->readType()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay tipos de empleados registrados';
                }
                break;
            case 'search':
                $_POST = $empleado->validateForm($_POST);
                if ($_POST['search'] == '') {
                    $result['exception'] = 'Ingrese un valor para buscar';
                } elseif ($result['dataset'] = $empleado->searchRows($_POST['search'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Valor encontrado';
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay coincidencias';
                }
                break;
            case 'create':
                $_POST = $empleado->validateForm($_POST);
                if (!$empleado->setNombre_empleado($_POST['nombre_empleado'])) {
                    $result['exception'] = 'Nombre de empleado inválido';
                } elseif (!$empleado->setUsuario_empleado($_POST['usuario_empleado'])) {
                    $result['exception'] = 'Nombre de usuario inválido';
                } elseif (!$empleado->setDUI($_POST['dui'])) {
                    $result['exception'] = 'Formato de DUI incorrrecto, debe incluir guión';
                } elseif (!$empleado->setCorreo_electronico($_POST['correo_electronico'])) {
                    $result['exception'] = 'Formato de correo electrónico inválido';
                } elseif ($_POST['contrasena1'] != $_POST['contrasena2']) {
                    $result['exception'] = 'Las contraseñas no coinciden';
                } elseif (!$empleado->setContrasena($_POST['contrasena1'])) {
                    $result['exception'] = $empleado->getPasswordError();
                } elseif (!$empleado->setIdTipoEmpleado($_POST['id_tipo_empleado'])) {
                    $result['exception'] = 'Tipo de empleado inválido';
                } elseif (!$empleado->setEstado($_POST['estado'])) {
                    $result['exception'] = 'Estado inválido';
                } elseif ($empleado->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'readOne':
                if (!$empleado->setId_empleado($_POST['id'])) {
                    $result['exception'] = 'ID de empleado inválido';
                } elseif ($result['dataset'] = $empleado->readOne()) {
                    $result['status'] = 1;
                } elseif (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'Empleado inexistente';
                }
                break;
            case 'update':
                $_POST = $empleado->validateForm($_POST);
                if (!$empleado->setId_empleado($_POST['id_empleado'])) {
                    $result['exception'] = 'ID de empleado inválido';
                } elseif (!$empleado->readOne()) {
                    $result['exception'] = 'Empleado inexistente';
                } elseif (!$empleado->setIdTipoEmpleado($_POST['id_tipo_empleado'])) {
                    $result['exception'] = 'Tipo de empleado inválido';
                } elseif ($empleado->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado modificado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'delete':
                if ($_POST['id'] == $_SESSION['id_empleado']) {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                } elseif (!$empleado->setId($_POST['id'])) {
                    $result['exception'] = 'empleado incorrecto';
                } elseif (!$empleado->readOne()) {
                    $result['exception'] = 'empleado inexistente';
                } elseif ($empleado->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'empleado eliminado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($empleado->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un empleado registrado';
                } else {
                    $result['exception'] = 'No existen empleados registrados';
                }
                break;
            case 'register':
                $_POST = $empleado->validateForm($_POST);
                if (!$empleado->setNombre_empleado($_POST['nombre_empleado'])) {
                    $result['exception'] = 'Nombre de empleado inválido';
                } elseif (!$empleado->setUsuario_empleado($_POST['usuario_empleado'])) {
                    $result['exception'] = 'Nombre de usuario inválido';
                } elseif (!$empleado->setDUI($_POST['dui'])) {
                    $result['exception'] = 'Formato de DUI incorrrecto, debe incluir guión';
                } elseif (!$empleado->setCorreo_electronico($_POST['correo_electronico'])) {
                    $result['exception'] = 'Formato de correo electrónico inválido';
                } elseif ($_POST['contrasena1'] != $_POST['contrasena2']) {
                    $result['exception'] = 'Las contraseñas no coinciden';
                } elseif (!$empleado->setContrasena($_POST['contrasena1'])) {
                    $result['exception'] = $empleado->getPasswordError();
                } elseif (!($empleado->setIdTipoEmpleado(1) && $empleado->setEstado(true))) {
                    $result['exception'] = '¿Puedes programar bien esto? Error de capa 8';
                } elseif ($empleado->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado registrado correctamente';
                } else {
                    $result['exception'] = Database::getException();
                }
                break;
            case 'logIn':
                $_POST = $empleado->validateForm($_POST);
                if (!$empleado->checkUser($_POST['usuario_empleado'])) {
                    $result['exception'] = 'Usuario incorrecto';
                } elseif ($empleado->checkPassword($_POST['contrasena'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $_SESSION['id_empleado'] = $empleado->getId_empleado();
                    $_SESSION['usuario_empleado'] = $empleado->getUsuario_empleado();
                } else {
                    $result['exception'] = 'Contraseña incorrecta';
                }
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
