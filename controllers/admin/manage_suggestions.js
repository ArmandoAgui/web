// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_SUGERENCIAS = SERVER + 'admin/sugerencia.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_SUGERENCIAS);
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
                    <img class="img align-self-center align-self-md-start" src="../../resources/img/admin_icons/chat2.png" width="120px" alt="">
                    <div class="w-50 ms-5">
                        <h5 class="mt-4 mt-md-0">Sugerencia</h5>
                        <h6>${row.descripcion}</h6>
                    </div>
                    <div class="w-50 ms-5">
                        <h5>Tipo sugerencia</h5>
                        <h6>${row.tipo_sugerencia}</h6>
                    </div>
                </div>
            </div>
            <div class="my-auto options">
                <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
                data-bs-toggle="modal" data-bs-target="#confirmModal" id="delete" onclick="openDelete(${row.id_sugerencia})">
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
    searchRows(API_SUGERENCIAS, 'searchForm');
});


// Función para establecer el registro a eliminar y abrir una caja de diálogo de confirmación.
function openDelete(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id', id);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_SUGERENCIAS, data);
}