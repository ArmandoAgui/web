// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_ORDER = SERVER + 'admin/orden.php?action=';
const API_ORDER_STATE = SERVER + 'admin/orden.php?action=readState';
let ID = '';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    let params = new URLSearchParams(location.search);
    // Se obtienen los datos localizados por medio de las variables.
    ID = params.get('order');
    // Se llama a la función que obtiene los productos del carrito de compras para llenar la tabla en la vista.
    readOrderDetail();
    readOrder();
});

// Función para obtener el detalle del pedido (carrito de compras).
function readOrderDetail() {
    const data = new FormData();
    data.append('id_orden', ID);
    // Petición para solicitar los datos del pedido.
    fetch(API_ORDER + 'readOrderDetail', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se declara e inicializa una variable para concatenar las filas de la tabla en la vista.
                    let main = '';
                    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                        main += `
                            <div class="card d-flex flex-column flex-md-row align-items-center p-4 mb-4">
                                <img class="card-image-product left" src="../../api/images/products/${row.imagen}" alt="">
                                <div class="d-flex flex-column justify-content-between w-100 px-4">
                                    <div class="d-flex justify-content-between mt-3 mt-md-0">
                                        <h5>${row.tipo_articulo}</h5>
                                        <h5 class="prize">${row.precio_articulo}</h5>
                                    </div>
                                    <h3 class="my-4">${row.nombre_articulo}</h3>
                                    <p>Cantidad: ${row.cantidad}</p>
                                    <p>Sub total: ${row.sub_total}</p>
                                </div>
                            </div>
                        `;

                    });
                    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
                    document.getElementById('products').innerHTML = main;
                } else {
                    modal(4, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para obtener los datos del pedido.
function readOrder() {
    const data = new FormData();
    data.append('id_orden', ID);
    // Petición para solicitar los datos del pedido.
    fetch(API_ORDER + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    document.getElementById('total').innerText = "$" + response.dataset.total;
                    document.getElementById('lugar_entrega').innerText = response.dataset.lugar_entrega;
                    document.getElementById('nombre_completo').innerText = "Nombre: " + response.dataset.nombre_completo;
                    document.getElementById('num_telefono').innerText = "Teléfono: " + response.dataset.num_telefono;
                    document.getElementById('correo_electronico').innerText = "Correo: " + response.dataset.correo_electronico;
                    document.getElementById('pedido').innerText = "Detalle de pedido #" + ID;
                    fillSelect(API_ORDER_STATE, 'state', response.dataset.id_estado_factura)
                } else {
                    modal(4, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

document.getElementById("state").addEventListener("change", ()=>{
    const data = new FormData();
    data.append('id_orden', ID);
    data.append('id_estado_factura', document.getElementById("state").value)
    // Petición para solicitar los datos del pedido.
    fetch(API_ORDER + 'update', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    modal(1, response.message, null)
                } else {
                    modal(4, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
})