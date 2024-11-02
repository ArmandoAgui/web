// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = SERVER + 'admin/usuario.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_USUARIOS);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
        <article class="row product mb-4">
        <div class="info">
            <button class="edit mw-100" data-bs-toggle="modal" data-bs-target="#crudModal"><h5 class="mb-3">${row.nombre_completo}</h5></button>
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                <img class="img align-self-center align-self-md-start" src="../../api/images/profile_photos/${row.imagen}" width="120px" alt="">
                <div>
                    <h5 class="mt-4 mt-md-0">ID Usuario</h5>
                    <h6 class="red-text">${row.id_usuario}</h6>
                </div>
                <div>
                    <h5>Nombre usuario</h5>
                    <h6>${row.nombre_usuario}</h6>
                </div>
                <div>
                    <h5>Num. Teléfono</h5>
                    <h6>${row.num_telefono}</h6>
                </div>
                <div>
                    <h5>Estado</h5>
                    <h6>${row.estado ? 'Activo':'Inactivo'}</h6>
                </div>
            </div>
        </div>
        <div class="my-auto options">
            <button class="edit d-flex justify-content-between px-3 px-md-0 mb-2 mx-auto align-items-center"
            data-bs-toggle="modal" data-bs-target="#crudModal" onclick="openUpdate(${row.id_usuario})">
                Ver detalles
                <img src="../../resources/img/admin_icons/edit.png" width="34px" alt="edit">
            </button>
            <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
            data-bs-toggle="modal" data-bs-target="#confirmModal" onclick="openDelete(${row.id_usuario})">
                ${row.estado ? 'Inhabilitar':'Habilitar'}
                <img src="../../resources/img/admin_icons/delete1.png" width="34px" alt="delete">
            </button>
        </div>
    </article>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.querySelector('main').innerHTML = main;
}

// Método manejador de eventos que se ejecuta cuando cambia el valor del input de buscar.
document.getElementById('search').addEventListener('input', function() {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_USUARIOS, 'searchForm');
});

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    // Se deshabilitan los campos de alias y contraseña.
    document.getElementById('nombre_usuario').disabled = true;
    document.getElementById('nombre_completo').disabled = true;
    document.getElementById('num_telefono').disabled = true;
    document.getElementById('correo_electronico').disabled = true;
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_usuario', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_USUARIOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_usuario').value = response.dataset.id_usuario;
                    document.getElementById('nombre_usuario').value = response.dataset.nombre_usuario;
                    document.getElementById('nombre_completo').value = response.dataset.nombre_completo;
                    document.getElementById('correo_electronico').value = response.dataset.correo_electronico;
                    document.getElementById('num_telefono').value = response.dataset.num_telefono;
                    document.getElementById('imagen').src = '../../api/images/profile_photos/' + response.dataset.imagen
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_USUARIOS, data);
}