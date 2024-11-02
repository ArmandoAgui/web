// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_ARTICULOS = SERVER + 'public/articulo.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    let params = new URLSearchParams(location.search);
    // Se obtienen los datos localizados por medio de las variables.
    const ID = params.get('idDep');
    // Se llama a la función que muestra el detalle del producto seleccionado previamente.
    if(ID === null){
        readRows(API_ARTICULOS)
    }else{
        departmentsProducts(ID)
    }
});

function fillTable(dataset) {
    let main = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        main += `
            <article class="col-4 p-0 product overflow-hidden">
                <img class="img-product position-absolute" src="../../api/images/products/${row.imagen}" alt="">
                <div class="elements">
                    <div class="stars d-flex flex-row align-items-center justify-content-center m-0 overflow-hidden">
                        <p class="fs-5 m-0 me-1">5</p>
                        <img src="../../resources/img/public_icons/star1.png" alt="">
                    </div>
                    <p class="add-cart d-flex justify-content-center align-items-center m-0 opacity-0">Añadir a carrito</p>
                    <p class="preview text-white d-flex justify-content-center align-items-center m-0 opacity-0">Vista previa</p>
                </div>
                <div class="text-product p-3">
                    <h3 class="m-0"><a href="product.html?idArt=${row.id_articulo}" class="text-decoration-none text-black">${row.nombre_articulo}</a></h3>
                    <div class="d-flex justify-content-between">
                        <p>${row.tipo_articulo}</p>
                        <p>${row.precio}</p>
                    </div>
                </div>
            </article>`;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('main').innerHTML = main;
}

//Se crea una constante donde se guarda el id del departamento
const departmentsProducts = (departamento) => {
    const data = new FormData();
    data.append('id_departamento', departamento);
    //Se llama el caso en donde se muestra la información del articulo
    fetch(API_ARTICULOS + 'departmentArticle', {
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
                    fillTable(response.dataset)
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

//Se crea una constante donde se guarda el id del departamento, id de la categoria y el tipo articulo
const categorizeProducts = (departamento, categoria, tipo_articulo) => {
    const data = new FormData();
    data.append('id_departamento', departamento);
    data.append('id_categoria', categoria);
    data.append('id_tipo_articulo', tipo_articulo);
    //Se llama el caso en donde se muestra la información del articulo
    fetch(API_ARTICULOS + 'filterArticle', {
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
                    fillTable(response.dataset)
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}