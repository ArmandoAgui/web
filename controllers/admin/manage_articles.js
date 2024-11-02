// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ARTICULO = SERVER + 'admin/articulo.php?action=';
const API_RESENA = SERVER + 'admin/resena.php?action=';
const ENDPOINT_CATEGORIAS = SERVER + 'admin/categoria.php?action=readAll';
const ENDPOINT_DEPARTAMENTO = SERVER + 'admin/articulo.php?action=readType';
const ENDPOINT_TIPO_ARTICULO = SERVER + 'admin/tipo_articulo.php?action=readAll';
const ENDPOINT_MARCA = SERVER + 'admin/marca.php?action=readAll';
const ENDPOINT_ESTADO_ARTICULO = SERVER + 'admin/articulo.php?action=readType1';
// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function() {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_ARTICULO);
});

function fillTable(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function(row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
        <article class="row product mb-4">
                    <div class="info">
                        <button class="edit mw-100" data-bs-toggle="modal" data-bs-target="#crudModal">
                            <h4 class="mb-3">${row.nombre_articulo}</h4>
                        </button>
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between product-text">
                            <img class="img align-self-center align-self-md-start" src="../../api/images/products/${row.imagen}" width="120px" alt="imagen_${row.nombre_articulo}">
                            <div>
                                <h5 class="mt-4 mt-md-0">Departamento</h5>
                                <h6>${row.departamento}</h6>
                            </div>
                            <div>
                                <h5>Tipo</h5>
                                <h6>${row.tipo_articulo}</h6>
                            </div>
                            <div>
                                <h5>Estado</h5>
                                <h6>${row.estado_articulo}</h6>
                            </div>
                            <div>
                                <h5>Precio</h5>
                                <h6 class="red-text">${row.precio}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="my-auto options">
                        <button class="edit d-flex justify-content-between mb-3 px-3 px-md-0 mx-auto align-items-center"
                            data-bs-toggle="modal" data-bs-target="#crudModal" id="edit" onclick="openUpdate(${row.id_articulo})">
                            Editar
                            <img src="../../resources/img/admin_icons/edit.png" width="34px" alt="edit">
                        </button>
                        <button class="edit d-flex justify-content-between px-3 px-md-0 mx-auto align-items-center"
                            data-bs-toggle="modal" data-bs-target="#reviewModal" onclick="openReview(${row.id_articulo})">
                            Ver reseñas
                            <img src="../../resources/img/admin_icons/star2.png" width="32px" alt="review">
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
    searchRows(API_ARTICULO, 'searchForm');
});

function openCreate() {
    //Se resetea el formulario
    document.getElementById("modalForm").reset()
    document.getElementById("agregarBtn").classList.remove("d-none")
    document.getElementById("modificarBtn").classList.add("d-none")
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modalTitle').textContent = 'Crear artículo';
    // Se llama a la función que llena el select del formulario. Se encuentra en el archivo components.js
    fillSelect(ENDPOINT_CATEGORIAS, 'id_categoria', null);
    fillSelect(ENDPOINT_DEPARTAMENTO, 'id_departamento', null);
    fillSelect(ENDPOINT_TIPO_ARTICULO, 'id_tipo_articulo', null);
    fillSelect(ENDPOINT_ESTADO_ARTICULO, 'id_estado_articulo', null);
    fillSelect(ENDPOINT_MARCA, 'id_marca', null);
    //
    document.getElementById("imagen_articulo").classList.add("d-none")
}

// Función para abrir el reporte de productos.
function openReport() {
    // Se establece la ruta del reporte en el servidor.
    let url = SERVER + 'reports/dashboard/productos.php';
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(url);
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdate(id) {
    document.getElementById("agregarBtn").classList.add("d-none")
    document.getElementById("modificarBtn").classList.remove("d-none")

    document.getElementById("imagen_articulo").classList.remove("d-none")
    // Se asigna el título para la caja de diálogo (modal).
    document.getElementById('modalTitle').textContent = 'Actualizar producto';
    fillSelect(ENDPOINT_CATEGORIAS, 'id_categoria', null);
    fillSelect(ENDPOINT_DEPARTAMENTO, 'id_departamento', null);
    fillSelect(ENDPOINT_TIPO_ARTICULO, 'id_tipo_articulo', null);
    fillSelect(ENDPOINT_ESTADO_ARTICULO, 'id_estado_articulo', null);
    fillSelect(ENDPOINT_MARCA, 'id_marca', null);
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_articulo', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_ARTICULO + 'readOne', {
        method: 'post',
        body: data
    }).then(function(request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function(response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_articulo').value = response.dataset.id_articulo;
                    document.getElementById('nombre_articulo').value = response.dataset.nombre_articulo;
                    document.getElementById('descripcion').value = response.dataset.descripcion;
                    document.getElementById('precio').value = response.dataset.precio;
                    document.getElementById('imagen_articulo').src = "../../api/images/products/" + response.dataset.imagen
                    fillSelect(ENDPOINT_DEPARTAMENTO, 'id_departamento', response.dataset.departamento);
                    fillSelect(ENDPOINT_CATEGORIAS, 'id_categoria', response.dataset.categoria);
                    fillSelect(ENDPOINT_ESTADO_ARTICULO, 'id_estado_articulo', response.dataset.estado_articulo);
                    fillSelect(ENDPOINT_TIPO_ARTICULO, 'id_tipo_articulo', response.dataset.tipo_articulo);
                    fillSelect(ENDPOINT_MARCA, 'id_marca', response.dataset.marca);
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
document.getElementById('modalForm').addEventListener('submit', function(event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    (document.getElementById('id_articulo').value) ? action = 'update': action = 'create';
    // Se llama a la función para guardar el registro. Se encuentra en el archivo components.js
    saveRow(API_ARTICULO, action, 'modalForm');
});


function openReview(id) {
    const data = new FormData();
    data.append('id_articulo', id);

    fetch(API_RESENA + 'readReviews', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se envían los datos a la función del controlador para que llene la tabla en la vista y se muestra un mensaje de éxito.
                    fillReviews(response.dataset);
                    //modal(1, response.message, null);
                } else {
                    document.querySelector('#reviewBody').innerHTML = "No hay reseñas";
                    //modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function fillReviews(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function(row) {
        let estado = ''
        if(row.estado){
            estado = "Dar de baja"
        }else{
            estado = "Habilitar"
        }
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
        <article class="review p-4 mb-4">
            <div class="d-flex align-items-center mb-3">
                <img class="photo-user" src="../../resources/img/public_icons/user2.png" alt="user_photo">
                <h5 class="m-0 ms-2 name" id="name">${row.nombre_completo}</h5>
                <button class="ms-auto view-btn py-0" onclick="updateState(${row.id_resena}, ${row.id_articulo})">${estado}</button>
            </div>
            <div class="d-flex">
                <img class="star me-2" src="../../resources/img/public_icons/star2.png" alt="star">
                <h5 class="me-3 mb-0">${row.estrellas}</h5>
                <h6 class="m-0 ms-2 fw-bold">${row.titulo}</h6>
            </div>
            <p class="m-0 mt-2 fw-light">${row.descripcion}</p>
        </article>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.querySelector('#reviewBody').innerHTML = main;
}

function updateState(id_resena, id_articulo) {
    const data = new FormData();
    data.append('id_resena', id_resena);

    fetch(API_RESENA + 'updateState', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                   //Se manda a llamar la función para refrescar las reviews.
                    openReview(id_articulo);
                    modal(1, response.message, null);
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

function openReport(){
    let url = SERVER + 'reports/admin/articulos_departamento.php';
    window.open(url);
}