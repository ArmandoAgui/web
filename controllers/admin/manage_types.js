// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_TIPO_ARTICULO = SERVER + 'admin/tipo_articulo.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_TIPO_ARTICULO);
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
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                    <img class="img align-self-center align-self-md-start" src="../../resources/img/admin_icons/type2.png" width="90px" alt="">
                    <div class="w-50 ms-5">
                        <h5 class="mt-4 mt-md-0">${row.tipo_articulo}</h5>
                    </div>
                    <div class="w-50">
                        <h5>Estado</h5>
                        <h6>${(row.estado == true)? 'Activo' : 'Inactivo'}</h6>
                    </div>
                </div>
            </div>
            <div class="my-auto options">
                <button class="edit d-flex justify-content-between px-3 px-md-0 mb-2 mx-auto align-items-center"
                data-bs-toggle="modal" data-bs-target="#crudModal" onclick="openUpdate(${row.id_tipo_articulo})">
                    Editar
                    <img src="../../resources/img/admin_icons/edit.png" width="34px" alt="edit">
                </button>
                <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
                data-bs-toggle="modal" data-bs-target="#confirmModal" onclick="openDelete(${row.id_tipo_articulo})">
                    ${(row.estado == true)? 'Inhabilitar' : 'Habilitar'}
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
    searchRows(API_TIPO_ARTICULO, 'searchForm');
});
function openCreate() {
    //Se resetea el formulario
    document.getElementById("modalForm").reset()
    document.getElementById("agregarBtn").classList.remove("d-none")
    document.getElementById("modificarBtn").classList.add("d-none")
    //Se cambia el titulo de Modal porque se agrega un registro
    document.getElementById("modalTitle").innerHTML = 'Agregar tipo articulo';
}
// Función para preparar el formulario al moegimento de modificar un rstro.
function openUpdate(id) {
    document.getElementById("agregarBtn").classList.add("d-none")
    document.getElementById("modificarBtn").classList.remove("d-none")
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById("modalTitle").innerHTML = 'Actualizar tipo del articulo';
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_TIPO_ARTICULO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_tipo_articulo').value = response.dataset.id_tipo_articulo;
                    document.getElementById('tipo_articulo').value = response.dataset.tipo_articulo;
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
    (document.getElementById('id_tipo_articulo').value) ? action = 'update' : action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRow(API_TIPO_ARTICULO, action, 'modalForm');
});


// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_TIPO_ARTICULO, data);
}