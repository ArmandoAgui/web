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
                    console.log("Hay una sesión iniciada")
                    readProfile()
                } else {
                    modal(3, "Debe iniciar sesión.", 'login.html');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });

    readRows(API_USUARIO)
});

function readProfile(){
    fetch(API_USUARIO + 'readProfile', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si existe una sesión, de lo contrario se revisa si la respuesta es satisfactoria.
                if (response.status) {
                    document.getElementById("imagen").src = '../../api/images/profile_photos/' + response.dataset.imagen;
                    document.getElementById("nombre_completo").innerText = response.dataset.nombre_completo
                    document.getElementById("nombre_usuario").innerText = '@' + response.dataset.nombre_usuario
                } else {
                    modal(3, response.exception, null)
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function fillTable(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function(row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
        <article class="row register mb-4">
                <div class="info">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                        <img class="img align-self-center align-self-md-start" src="../../resources/img/admin_icons/order2.png" width="120px" alt="">
                        <div>
                            <h5 class="mt-4 mt-md-0">N° Pedido</h5>
                            <h6>${row.id_factura}</h6>
                        </div>
                        <div>
                            <h5>Fecha</h5>
                            <h6>${row.fecha.slice(0,-8)}</h6>
                        </div>
                        <div>
                            <h5>Estado</h5>
                            <h6>${row.id_estado_factura}</h6>
                        </div>
                        <div class="me-5">
                            <h5>Total</h5>
                            <h6 class="red-text">$${row.total}</h6>
                        </div>
                    </div>
                </div>
                <div class="my-auto options">
                    <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center mb-3"
                        data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetailOrder(${row.id_factura})">
                        Ver detalle
                        <img src="../../resources/img/admin_icons/edit.png" width="34px" alt="edit">
                    </button>
                    <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
                        onclick="openReport(${row.id_factura})">
                        Generar comprobante
                        <img src="../../resources/img/admin_icons/order2.png" width="34px" alt="edit">
                    </button>
                </div>
            </article>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.querySelector('#orders').innerHTML = main;
}

function showDetailOrder(id_factura){
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_factura', id_factura);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_USUARIO + 'readOrderDetail', {
        method: 'post',
        body: data
    }).then(function(request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function(response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se envían los datos a la función del controlador para que llene la tabla en la vista y se muestra un mensaje de éxito.
                    fillDetailOrder(response.dataset);
                } else {
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function fillDetailOrder(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function(row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
        <article class="d-flex mb-2 justify-content-between align-items-center">
            <img class="rounded" src="../../api/images/products/${row.imagen}" alt="" width="100px" height="100px">
            <h5>${row.nombre_articulo}</h5>
            <h5>${row.cantidad}</h5>
            <h5 class="red-text">$${row.sub_total}</h5>
        </article>
        <hr class="title-hr-1 opacity-100 m-0 mb-3">
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.querySelector('#orderDetailBody').innerHTML = main;
}

// Función para abrir el reporte de detalle de pedido.
function openReport(id) {
    // Se define una variable para inicializar los parámetros del reporte.
    let params = '?id=' + id;
    // Se establece la ruta del reporte en el servidor.
    let url = SERVER + 'reports/public/purchase_receipt.php';
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(url + params);
}