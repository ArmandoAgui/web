// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ARTICULO = SERVER + 'public/articulo.php?action=';
const API_RESENA = SERVER + 'public/resena.php?action=';
const API_FACTURA = SERVER + 'public/factura.php?action=';

let ID = '';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    let params = new URLSearchParams(location.search);
    // Se obtienen los datos localizados por medio de las variables.
    ID = params.get('idArt');
    // Se llama a la función que muestra el detalle del producto seleccionado previamente.
    if (ID === null) {
        location.href = 'products.html'
    } else {
        showDetail(ID)
        showReview(ID)
        document.getElementById("id_articulo").value = ID
    }
});

// Función para mostrar todos los detalles de un producto
function showDetail(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_articulo', id);
    // Petición para obtener los datos del registro solicitado.
    fetch(API_ARTICULO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('imagen').src = '../../api/images/products/' + response.dataset.imagen;
                    document.getElementById('nombre_articulo').innerText = response.dataset.nombre_articulo;
                    document.getElementById('precio').innerText = '$' + response.dataset.precio;
                    document.getElementById('tipo_articulo').innerText = response.dataset.tipo_articulo;
                    document.getElementById('marca').innerText = response.dataset.marca;
                    document.getElementById('stock').innerText = response.dataset.stock;
                    document.getElementById('estado_articulo').innerText = response.dataset.estado_articulo;
                    document.getElementById('descripcion').innerText = response.dataset.descripcion;
                    document.getElementById('id_producto').value = id;
                    document.getElementById('cantidad').value = 1;
                    document.getElementById('sub_total').value = response.dataset.precio;
                } else {
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Reseñas

//Funcion para mostrar las reseñas del articulo seleccionado
function showReview(id) {
    const data = new FormData();
    data.append('id_articulo', id);
    // Petición para obtener los datos de las reviews
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
                } else {
                    document.querySelector('#reviewBody').innerHTML = "No hay reseñas";
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Función para mostrar los datos de la base de la tabla de reviews al sitio web
function fillReviews(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
            <article class="review p-4 mb-4">
            <div class="d-flex align-items-center mb-3">
                <img class="photo-user" src="../../resources/img/public_icons/user2.png" alt="user_photo">
                <h5 class="m-0 ms-2 name" id="name">${row.nombre_completo}</h5>         
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
    document.getElementById('reviewBody').innerHTML = main;
}


// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('collapseForm').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    fetch(API_RESENA + 'create', {
        method: 'post',
        body: new FormData(document.getElementById('collapseForm'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se cargan nuevamente las filas en la tabla de la vista después de guardar un registro y se muestra un mensaje de éxito.
                    showReview(ID)
                    modal(1, response.message, null);
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});


/// Método manejador de eventos que se ejecuta cuando se envía el formulario de agregar un producto al carrito.
document.getElementById('shopping-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Petición para agregar un producto al pedido.
    fetch(API_FACTURA + 'createDetail', {
        method: 'post',
        body: new FormData(document.getElementById('shopping-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            console.log(document.getElementById(''))
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
                if (response.status) {
                    modal(1, response.message, 'shopping_cart.html');
                } else {
                    // Se verifica si el cliente ha iniciado sesión para mostrar la excepción, de lo contrario se direcciona para que se autentique. 
                    if (response.session) {
                        modal(2, response.exception, null);
                    } else {
                        modal(3, response.exception, 'login.html');
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});