// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_USUARIO = SERVER + 'public/usuario.php?action=';

/*const ENDPOINT_DEPARTMENT = SERVER + 'public/usuario.php?action=readDepartment';
const ENDPOINT_TOWN = SERVER + 'public/usuario.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function() {
    fillSelect(ENDPOINT_DEPARTMENT, 'depto', null);
});

const depto = document.getElementById("depto")
depto.addEventListener("click", ()=>{
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_depto', depto.value);
    // Petición para obtener los datos del registro solicitado.
    fetch(ENDPOINT_TOWN + 'readTown', {
        method: 'post',
        body: data
    }).then(function(request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function(response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                let content = '';
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Si no existe un valor para seleccionar, se muestra una opción para indicarlo.
                    content += '<option disabled selected>Seleccione una opción</option>';
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se obtiene el dato del primer campo de la sentencia SQL (valor para cada opción).
                        value = Object.values(row)[0];
                        // Se obtiene el dato del segundo campo de la sentencia SQL (texto para cada opción).
                        text = Object.values(row)[1];
                        content += `<option value="${value}">${text}</option>`;
                    });
                } else {
                    content += '<option>No hay municipios disponibles</option>';
                    console.log(response.exception)
                }
                // Se agregan las opciones a la etiqueta select mediante su id.
                document.getElementById("town").innerHTML = content;
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}) */

// Método manejador de eventos que se ejecuta cuando se envía el formulario de registrar.
document.getElementById('registerForm').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Petición para registrar el primer usuario del sitio privado.
    fetch(API_USUARIO + 'register', {
        method: 'post',
        body: new FormData(document.getElementById('registerForm'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    modal(1, response.message, 'login.html');
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});