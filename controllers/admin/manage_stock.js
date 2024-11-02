// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_EXISTENCIA = SERVER + 'admin/existencia.php?action=';
const ENDPOINT_ARTICULO = SERVER + 'admin/articulo.php?action=readAll'

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_EXISTENCIA);
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
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                    <img class="img align-self-center align-self-md-start" src="../../resources/img/admin_icons/product.png" width="90px" alt="">
                    <div class="ms-5">
                        <h5 class="mt-4 mt-md-0">Producto</h5>
                        <h6>${row.nombre_articulo}</h6>
                    </div>
                    <div>
                        <h5>Cantidad</h5>
                        <h6>${row.cantidad}</h6>
                    </div>
                    <div class="me-5">
                        <h5>Fecha</h5>
                        <h6>${row.fecha}</h6>
                    </div>
                </div>
            </div>
            <div class="my-auto options">
                
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
    searchRows(API_EXISTENCIA, 'searchForm');
});

function openCreate() {
    //Se resetea el formulario
    document.getElementById("modalForm").reset()
    document.getElementById("agregarBtn").classList.remove("d-none")
    document.getElementById("modificarBtn").classList.add("d-none")
    // Se llama a la función que llena el select del formulario. Se encuentra en el archivo components.js
    fillSelect(ENDPOINT_ARTICULO, 'id_articulo', null);
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('modalForm').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRow(API_EXISTENCIA, 'create', 'modalForm');
});