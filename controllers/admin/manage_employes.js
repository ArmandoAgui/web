// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_EMPLEADOS = SERVER + 'admin/empleado.php?action=';
const ENDPOINT_TIPO_EMPLEADO = SERVER + 'admin/empleado.php?action=readType';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_EMPLEADOS);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
        <article class="row product mb-4">
            <div class="info">
                <button class="edit mw-100" data-bs-toggle="modal" data-bs-target="#crudModal"><h5 class="mb-3">Josué Argumedo</h5></button>
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                    <img class="img align-self-center align-self-md-start col-2 pe-4" src="../../resources/img/admin_icons/user.png" alt="Employee">
                    <div class="col-3">
                        <h5>Nombre usuario</h5>
                        <h6>${row.usuario_empleado}</h6>
                    </div>
                    <div class="col-2">
                        <h5>DUI</h5>
                        <h6>${row.dui}</h6>
                    </div>
                    <div class="col-3">
                        <h5>Tipo usuario</h5>
                        <h6>${row.tipo_empleado}</h6>
                    </div>
                    <div class="col-2">
                        <h5>Estado</h5>
                        <h6>${row.estado}</h6>
                    </div>
                </div>
            </div>
            <div class="my-auto options">
                <button class="edit d-flex justify-content-between px-3 px-md-0 mb-2 mx-auto align-items-center"
                data-bs-toggle="modal" data-bs-target="#crudModal" id="edit" onclick="openUpdate(${row.id_empleado})">
                    Editar
                    <img src="../../resources/img/admin_icons/edit.png" width="34px" alt="edit">
                </button>
                <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
                data-bs-toggle="modal" data-bs-target="#confirmModal" id="delete">
                    Eliminar
                    <img src="../../resources/img/admin_icons/delete1.png" width="34px" alt="delete">
                </button>
            </div>
        </article>
        `;
    });
    // Se agregan los registros a la etiquete main.
    document.querySelector('main').innerHTML = content;
}

// Método manejador de eventos que se ejecuta cuando cambia el valor del input de buscar.
document.getElementById('search').addEventListener('input', function() {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_EMPLEADOS, 'searchForm');
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreate() {
    //Se resetea el formulario
    document.getElementById("modalForm").reset()
    document.getElementById("agregarBtn").classList.remove("d-none")
    document.getElementById("modificarBtn").classList.add("d-none")

    document.getElementById('contrasena1').parentElement.classList.remove("d-none")
    document.getElementById('contrasena2').parentElement.classList.remove("d-none")

    document.getElementById('usuario_empleado').removeAttribute("disabled")
    document.getElementById('nombre_empleado').removeAttribute("disabled")
    document.getElementById('correo_electronico').removeAttribute("disabled")
    document.getElementById('dui').removeAttribute("disabled")
    document.getElementById('contrasena1').removeAttribute("disabled")
    document.getElementById('contrasena2').removeAttribute("disabled")
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modalTitle').innerHTML = 'Agregar empleado';
    // Se llama a la función que llena el select del formulario. Se encuentra en el archivo components.js
    fillSelect(ENDPOINT_TIPO_EMPLEADO, 'id_tipo_empleado', null);
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    document.getElementById("agregarBtn").classList.add("d-none")
    document.getElementById("modificarBtn").classList.remove("d-none")

    document.getElementById('contrasena1').parentElement.classList.add("d-none")
    document.getElementById('contrasena2').parentElement.classList.add("d-none")

    document.getElementById('usuario_empleado').disabled = 'true'
    document.getElementById('nombre_empleado').disabled = 'true'
    document.getElementById('correo_electronico').disabled = 'true'
    document.getElementById('dui').disabled = 'true'
    document.getElementById('contrasena1').disabled = 'true'
    document.getElementById('contrasena2').disabled = 'true'
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modalTitle').innerHTML = 'Actualizar empleado';
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_EMPLEADOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_empleado').value = response.dataset.id_empleado;
                    document.getElementById('usuario_empleado').value = response.dataset.usuario_empleado;
                    document.getElementById('nombre_empleado').value = response.dataset.nombre_empleado;
                    document.getElementById('correo_electronico').value = response.dataset.correo_electronico;
                    document.getElementById('dui').value = response.dataset.dui;
                    document.getElementById('id_tipo_empleado').value = response.dataset.id_tipo_empleado;
                    fillSelect(ENDPOINT_TIPO_EMPLEADO, 'id_tipo_empleado', response.dataset.id_tipo_empleado);
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('modalForm').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    (document.getElementById('id_empleado').value) ? action = 'update' : action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRow(API_EMPLEADOS, action, 'modalForm');
});

// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_USUARIOS, data);
}