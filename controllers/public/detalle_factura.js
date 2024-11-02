// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_PEDIDOS = SERVER + 'public/factura.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los productos del carrito de compras para llenar la tabla en la vista.

    readOrderDetail();
});

// Función para obtener el detalle del pedido (carrito de compras).
function readOrderDetail() {
    // Petición para solicitar los datos del pedido en proceso.
    fetch(API_PEDIDOS + 'readOrderDetail', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se declara e inicializa una variable para concatenar las filas de la tabla en la vista.
                    let main = '';
                    // Se declara e inicializa una variable para calcular el importe por cada producto.
                    let subtotal = 0;
                    // Se declara e inicializa una variable para ir sumando cada subtotal y obtener el monto final a pagar.
                    let total = 0;
                    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        subtotal = (row.precio_articulo * row.cantidad);

                        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                        main += `
                <div class="card-product w-100 d-flex flex-column flex-md-row align-items-center p-4 mb-4">
                    <img class=" card-image-product left" src="../../api/images/products/${row.imagen}"
                        alt="">
                    <div class="d-flex flex-column justify-content-between w-100 h-100 px-4">
                        <div class="w-100 d-flex align-content-center mt-3 mt-md-0">
                            <h5 class="w-50">${row.categoria}</h5>
                            <h5 class="w-50 text-end prize" id="sub_total${row.id_detalle_factura}">${subtotal.toFixed(2)}</h5>
                        </div>
                        <h3>${row.tipo_articulo} - ${row.nombre_articulo} </h3>
                        <div class="w-100 d-flex">
                        <fieldset class="w-50" data-quantity>
                            <button type="button" title="Down" class="sub" onclick="restCantidad(this.nextElementSibling, ${row.id_detalle_factura}, ${row.precio_articulo})">Down</button>
                            <input readonly="" type="number" name="quantity" pattern="[0-9]+" value="${row.cantidad}">
                            <button type="button" title="Up" class="add" onclick="addCantidad(this.previousElementSibling, ${row.id_detalle_factura}, ${row.precio_articulo})">Up</button>
                        </fieldset>
                            <a class="title-link ms-auto d-flex align-items-center" onclick="openDelete(${row.id_detalle_factura})">Eliminar</a>
                        </div>
                    </div>
                </div>
                        `;
                        //Se le va autoincrementado el subtotal a la variable total
                        total = total + subtotal;

                    });
                    console.log(total)
                    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
                    document.getElementById('total_ini').innerText = '$' + total;
                    document.getElementById('total_final').innerText = '$' + total;
                    document.getElementById('main').innerHTML = main;
                    /*
                    // Se muestra el total a pagar con dos decimales.
                    document.getElementById('pago').textContent = total.toFixed(2);*/
                } else {
                    //modal(4, response.exception, 'index.html');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}
//Funcion para agregar la cantidad a un articulo
function addCantidad(input, id, precio) {
    input.value = parseInt(input.value) + 1;
    //crear un variable con el valor del resumen del carrito
    let temp = document.getElementById('sub_total' + id);
    temp.innerText = (input.value * precio).toFixed(2);
    //se actualiza en la base de datos la cantidad del detalle
    updateDetail(id, input.value, temp.innerText);
    //aqui se imprime ese resultado
    let resume = document.getElementById('total_ini');
    resume.innerText = resume.innerText.slice(1)
    resume.innerText = '$' + (parseFloat(resume.innerText) + precio).toFixed(2);
    document.getElementById('total_final').innerText = resume.innerText
}
//Funcion para restar la cantidad a un articulo
function restCantidad(input, id, precio) {
    //Evaluamos que no ingrese una cantidad negativa o nula
    if (input.value < 2) {
    } else {
        input.value -= 1
        //Se crea una variable con el valor resumen del carrito
        let temp = document.getElementById('sub_total' + id);
        temp.innerText = (input.value * precio).toFixed(2);
        //se actualiza en la base de datos la cantidad del detalle
        updateDetail(id, input.value, temp.innerText)
        //Aqui se imprime el resultado obtenido
        let resume = document.getElementById('total_ini');
        resume.innerText = resume.innerText.slice(1)
        resume.innerText = '$' + (parseFloat(resume.innerText) - precio).toFixed(2);
        document.getElementById('total_final').innerText = resume.innerText
    }
}


// Método manejador de eventos que se ejecuta cuando se envía el formulario de cambiar cantidad de producto.
function updateDetail(id, cantidad, sub_total) {
    const data = new FormData();
    data.append('id_detalle_factura', id);
    data.append('cantidad', cantidad);
    data.append('sub_total', sub_total);
    // Petición para actualizar la cantidad de producto.
    fetch(API_PEDIDOS + 'updateDetail', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se actualiza la tabla en la vista para mostrar el cambio de la cantidad de producto.
                    console.log('Actualización exitosa');
                } else {
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
};

// Función para mostrar un mensaje de confirmación al momento de finalizar el pedido.
function finishOrder() {
    const total = parseFloat(document.getElementById('total_final').innerText.slice(1)).toFixed(2)
    const data = new FormData();
    data.append('total', total);
    // Petición para finalizar el pedido en proceso.
    fetch(API_PEDIDOS + 'finishOrder', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    modal(1, response.message, 'thank_you_page.html');
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para mostrar un mensaje de confirmación al momento de eliminar un producto del carrito.
function openDelete(id) {
    // Se define un objeto con los datos del producto seleccionado.
    const data = new FormData();
    data.append('id_detalle_factura', id);
    // Petición para remover un producto del pedido.
    fetch(API_PEDIDOS + 'deleteDetail', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se cargan nuevamente las filas en la tabla de la vista después de borrar un producto del carrito.
                    readOrderDetail();
                    modal(1, response.message, null);
                } else {
                    readOrderDetail();
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}