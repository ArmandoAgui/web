// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ORDEN = SERVER + 'admin/orden.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_ORDEN);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content= '';
    console.log(dataset)
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
        <article class="row product mb-4">
            <div class="info">
                <a href="order_detail.html" class="edit mw-100 text-decoration-none"><h5 class="mb-3">Pedido # ${row.id_factura}</h5></a>
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                    <img class="img align-self-center align-self-md-start col-2 pe-5" src="../../resources/img/admin_icons/order2.png" width="120px" alt="">
                    <div class="col-3">
                        <h5>Cliente</h5>
                        <h6>${row.nombre_usuario}</h6>
                    </div>
                    <div class="col-2">
                        <h5>Fecha</h5>
                        <h6>${row.fecha}</h6>
                    </div>
                    <div class="col-2">
                        <h5>Total</h5>
                        <h6 class="red-text">$${row.total}</h6>
                    </div>
                    <div class="col-2">
                        <h5>Estado</h5>
                        <h6>${row.estado_factura}</h6>
                    </div>
                </div>
            </div>
            <div class="my-auto options">
                <a href="order_detail.html?order=${row.id_factura}" class="edit d-flex justify-content-between 
                px-3 px-md-0 mb-2 mx-auto align-items-center text-decoration-none" id="edit">
                    Atender
                    <img src="../../resources/img/admin_icons/attend.png" width="34px" alt="edit">
                </a>
            </div>
        </article>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('main').innerHTML = content;
}

// Método manejador de eventos que se ejecuta cuando cambia el valor del input de buscar.
document.getElementById('search').addEventListener('input', function() {
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_ORDEN, 'searchForm');
});