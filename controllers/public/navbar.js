// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_USUARIO = SERVER + 'public/usuario.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Petición para consultar si existen empleados registrados.
    fetch(API_USUARIO + 'getUser', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.session) {
                    document.getElementById("online").setAttribute('style', 'display:flex !important');
                    document.getElementById("nombre_usuario").innerHTML = '<img src="../../../resources/img/public_icons/user1.png" width="50px" alt="">' + response.username
                } else {
                    document.getElementById("offline").setAttribute('style', 'display:flex !important');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});