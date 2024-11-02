// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_LUGARENTREGA = SERVER + 'admin/lugar_entrega.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_LUGARENTREGA);
    
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content= '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
        <article class="row product mb-4">
                        <div class="info">
                            <div
                                class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                                <img class="img align-self-center align-self-md-start"
                                    src="../../resources/img/admin_icons/place2.png" width="100px" alt="">
                                <div class="w-75 ms-5">
                                    <h5 class="mt-4 mt-md-0">${row.lugar_entrega}</h5>
                                    <h6>${row.direccion}</h6>
                                </div>
                                <div class="w-25">
                                    <h5>Estado</h5>
                                    <h6>${row.estado}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="my-auto options">
                            <button
                                class="edit d-flex justify-content-between px-3 px-md-0 mb-2 mx-auto align-items-center"
                                data-bs-toggle="modal" data-bs-target="#crudModal" id="edit" onclick= "openUpdate(${row.id_lugar_entrega})">
                                Editar
                                <img src="../../resources/img/admin_icons/edit.png" width="34px" alt="edit">
                            </button>
                            <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
                                data-bs-toggle="modal" data-bs-target="#confirmModal" id="delete" onclick="openDelete(${row.id_lugar_entrega})">
                                Eliminar
                                <img src="../../resources/img/admin_icons/delete1.png" width="34px" alt="delete">
                            </button>
                        </div>
                    </article>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.querySelector('main').innerHTML = content;
}

// Método manejador de eventos que se ejecuta cuando cambia el valor del input de buscar.
document.getElementById('search').addEventListener('input', function() {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_LUGARENTREGA, 'searchForm');
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreate() {
    //Se resetea el formulario
    document.getElementById("modalForm").reset()
    document.getElementById("agregarBtn").classList.remove("d-none")
    document.getElementById("modificarBtn").classList.add("d-none")
    //Se cambia el titulo de Modal porque se agrega un registro
    document.getElementById("modalTitle").innerHTML = 'Agregar Lugar Entrega';
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    document.getElementById("agregarBtn").classList.add("d-none")
    document.getElementById("modificarBtn").classList.remove("d-none")
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById("modalTitle").innerHTML = 'Actualizar Lugar Entrega';
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_LUGARENTREGA + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_lugar_entrega').value = response.dataset.id_lugar_entrega;
                    document.getElementById('lugar_entrega').value = response.dataset.lugar_entrega;
                    document.getElementById('direccion').value = response.dataset.direccion;
                    document.getElementById('estado').value = response.dataset.estado;
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
    (document.getElementById('id_lugar_entrega').value) ? action = 'update' : action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRow(API_LUGARENTREGA, action, 'modalForm', 'crudModal');
});

// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_LUGARENTREGA, data);
}