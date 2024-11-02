var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

fecha = new Date();
var meses = new Array ("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
var texto = fecha.getDate()+ " " + meses[fecha.getMonth()] + " " + fecha.getFullYear();

// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_FACTURA = SERVER + 'admin/orden.php?action=';
const API_RESENA = SERVER + 'admin/resena.php?action=';
const API_ARTICULO = SERVER + 'admin/articulo.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById("date").innerHTML = texto
    grafico1();
    grafico2();
    grafico3();
    grafico4();
    grafico5();
});

// Función para mostrar las ventas por mes del año actual en un gráfico de barras.
function grafico1() {
    // Petición para obtener los datos del gráfico.
    fetch(API_FACTURA + 'ventasMes', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let mes = [];
                    let total = [];
                    let totalG = 0;
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        mes.push(row.mes);
                        total.push(row.total);
                        totalG += parseFloat(row.total);
                    });
                    document.getElementById("totalVentas").innerText = "$" + totalG
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    barGraph('chart1', mes, total, 'Total de ganancias $');
                } else {
                    document.getElementById('chart1').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para mostrar el top 5 artículos con mayor porcentaje de venta en el mes en un gráfico de pastel.
function grafico2() {
    // Petición para obtener los datos del gráfico.
    fetch(API_FACTURA + 'ventasTopProductosMes', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let mes = [];
                    let total = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        mes.push(row.nombre_articulo);
                        total.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    pieGraph('chart2', mes, total);
                } else {
                    document.getElementById('chart2').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para mostrar los top 5 productos con mejor calificacion en un gráfico de barras.
function grafico3() {
    // Petición para obtener los datos del gráfico.
    fetch(API_RESENA + 'mejorCalificacionProductos', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let nombre_articulo = [];
                    let promedio = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        nombre_articulo.push(row.nombre_articulo);
                        promedio.push(row.promedio);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    barGraph('chart3', nombre_articulo, promedio, "Promedio estrellas");
                } else {
                    document.getElementById('chart3').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para mostrar el porcentaje de ventas por departamento en un gráfico de pastel.
function grafico4() {
    // Petición para obtener los datos del gráfico.
    fetch(API_FACTURA + 'ventasDepartamentoMes', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let departamento = [];
                    let porcentaje = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        departamento.push(row.departamento);
                        porcentaje.push(row.cantidad);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    pieGraph('chart4', departamento, porcentaje);
                } else {
                    document.getElementById('chart4').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para mostrar los 5 articulos con menor existencia en un gráfico de barras.
function grafico5() {
    // Petición para obtener los datos del gráfico.
    fetch(API_ARTICULO + 'stockArticulo', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
                if (response.status) {
                    // Se declaran los arreglos para guardar los datos a graficar.
                    let nombre_articulo = [];
                    let stock = [];
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se agregan los datos a los arreglos.
                        nombre_articulo.push(row.nombre_articulo);
                        stock.push(row.stock);
                    });
                    // Se llama a la función que genera y muestra un gráfico de barras. Se encuentra en el archivo components.js
                    lineGraph('chart5', nombre_articulo, stock, "Stock restante");
                } else {
                    document.getElementById('chart5').remove();
                    console.log(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

// Función para abrir los distintos reportes.
function openReport(report) {
    // Se establece la ruta del reporte en el servidor.
    let url = SERVER + 'reports/admin/' + report + '.php';
    // Se abre el reporte en una nueva pestaña del navegador web.
    window.open(url);
}