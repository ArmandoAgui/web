/*
*   CONTROLADOR DE USO GENERAL EN TODAS LAS PÁGINAS WEB.
*/

/*
*   Constante para establecer la ruta del servidor.
*/
const SERVER = 'http://localhost/SengaStoreWebSite/api/';


// Función para agregar el código para los modals en todas las páginas.
function modalsComponents() {
    let content = `
        <!--Information modal-->
        <div class="modal fade" id="informationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header mb-2">
                        <h5 class="modal-title" id="modalLabel">Modal title</h5>
                        <button type="button" class="btn-close d-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <img src="../../resources/img/admin_icons/advise.svg" alt="" width="70px" class="mx-auto mt-3" id="modalIcon">
                    <div class="modal-body text-center" id="modalMessage">
                        
                    </div>
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-secondary d-none" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn view-btn text-white px-3" id="confirmationButton" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Confirmation modal-->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="title m-0">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <img src="../../resources/img/admin_icons/advise.svg" alt="" width="70px" class="mx-auto mt-3">
                <div class="modal-body text-center">
                    ¿Estás seguro de eliminar este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="ms-auto view-btn d-flex align-items-center" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" id="confirmButton" class="me-auto view-btn d-flex align-items-center" data-bs-dismiss="modal">
                        <img src="../../resources/img/admin_icons/delete2.png" width="24px" alt="" class="me-2">
                        Confirmar
                    </button>
                </div>
            </div>
            </div>
        </div>
        `;
    // Se agregan los registros a la etiqueta modals.
    const div = document.getElementById('modalsComponents')
    if(div)div.innerHTML = content;
}

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    modalsComponents();
});

/*
*   Función para obtener todos los registros disponibles en los mantenimientos de tablas (operación read).
*
*   Parámetros: api (ruta del servidor para obtener los datos).
*
*   Retorno: ninguno.
*/
function readRows(api) {
    fetch(api + 'readAll', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                let data = [];
                // Se comprueba si la respuesta es satisfactoria para obtener los datos, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    data = response.dataset;
                } else {
                    modal(4, response.exception, null);
                }
                // Se envían los datos a la función del controlador para llenar la tabla en la vista.
                fillTable(data);
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

/*
*   Función para obtener los resultados de una búsqueda en los mantenimientos de tablas (operación search).
*
*   Parámetros: api (ruta del servidor para obtener los datos) y form (identificador del formulario de búsqueda).
*
*   Retorno: ninguno.
*/
function searchRows(api, form) {
    fetch(api + 'search', {
        method: 'post',
        body: new FormData(document.getElementById(form))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se envían los datos a la función del controlador para que llene la tabla en la vista y se muestra un mensaje de éxito.
                    fillTable(response.dataset);
                    //modal(1, response.message, null);
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

/*
*   Función para crear o actualizar un registro en los mantenimientos de tablas (operación create y update).
*
*   Parámetros: api (ruta del servidor para enviar los datos), form (identificador del formulario) y modal (identificador de la caja de dialogo).
*
*   Retorno: ninguno.
*/
function saveRow(api, action, form) {
    fetch(api + action, {
        method: 'post',
        body: new FormData(document.getElementById(form))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se cargan nuevamente las filas en la tabla de la vista después de guardar un registro y se muestra un mensaje de éxito.
                    readRows(api);
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

/*
*   Función para eliminar un registro seleccionado en los mantenimientos de tablas (operación delete). Requiere el archivo sweetalert.min.js para funcionar.
*
*   Parámetros: api (ruta del servidor para enviar los datos) y data (objeto con los datos del registro a eliminar).
*
*   Retorno: ninguno.
*/
function confirmDelete(api, data) {
    let btn = document.getElementById("confirmButton");
    btn.children[0].src = "../../resources/img/admin_icons/delete2.png"
    btn.parentElement.parentElement.children[0].children[0].innerHTML = "Confirmar eliminación"
    btn.parentElement.parentElement.children[2].innerHTML = "¿Estás seguro de eliminar este registro?"
    btn.onclick = ()=>{
        fetch(api + 'delete', {
            method: 'post',
            body: data
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                // Se obtiene la respuesta en formato JSON.
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        // Se cargan nuevamente las filas en la tabla de la vista después de borrar un registro y se muestra un mensaje de éxito.
                        readRows(api);
                        modal(1, response.message, null)
                        //sweetAlert(1, response.message, null);
                    } else {
                        console.log(response.exception);
                        //sweetAlert(2, response.exception, null);
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        });
    }
}

/*
*   Función para manejar los mensajes de notificación al usuario. Requiere el archivo bootstrap.min.js para funcionar.
*
*   Parámetros: type (tipo de mensaje), text (texto a mostrar) y url (ubicación para enviar al cerrar el mensaje).
*
*   Retorno: ninguno.
*/
function modal(type, text, url) {
    //Se declara e inicializa al elemento de Bootstrap
    var informationModal = new bootstrap.Modal(document.getElementById('informationModal'))
    //Se declaran los elementos del modal de confirmación
    const modalTitle = document.getElementById("modalLabel")
    const modalIcon = document.getElementById("modalIcon")
    const modalText = document.getElementById("modalMessage")
    const confirmationButton = document.getElementById("confirmationButton")
    // Se compara el tipo de mensaje a mostrar y se define el titulo del modal.
    switch (type) {
        case 1:
            modalTitle.innerHTML = 'Éxito';
            modalIcon.src = "../../resources/img/admin_icons/success.svg"
            break;
        case 2:
            modalTitle.innerHTML = 'Error';
            modalIcon.src = "../../resources/img/admin_icons/error.svg"
            break;
        case 3:
            modalTitle.innerHTML = 'Advertencia';
            modalIcon.src = "../../resources/img/admin_icons/advise.svg"
            break;
        case 4:
            modalTitle.innerHTML = 'Aviso';
            modalIcon.src = "../../resources/img/admin_icons/info.svg"
    }
    //Se asigna el mensaje a mostrar en el modal
    modalText.innerHTML = text
    // Si existe una ruta definida, se muestra el mensaje y se direcciona a dicha ubicación, de lo contrario solo se muestra el mensaje.
    if (url) {
        confirmationButton.onclick =()=>{  location.href = url }
    } else {  }
    informationModal.toggle()
    modalIcon.classList.add("anima")
}

/*
*   Función para cargar las opciones en un select de formulario.
*
*   Parámetros: endpoint (ruta específica del servidor para obtener los datos), select (identificador del select en el formulario) y selected (valor seleccionado).
*
*   Retorno: ninguno.
*/
function fillSelect(endpoint, select, selected) {
    fetch(endpoint, {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            // Se obtiene la respuesta en formato JSON.
            request.json().then(function (response) {
                let content = '';
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Si no existe un valor para seleccionar, se muestra una opción para indicarlo.
                    if (!selected) {
                        content += '<option disabled selected>Seleccione una opción</option>';
                    }
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Se obtiene el dato del primer campo de la sentencia SQL (valor para cada opción).
                        value = Object.values(row)[0];
                        // Se obtiene el dato del segundo campo de la sentencia SQL (texto para cada opción).
                        text = Object.values(row)[1];
                        // Se verifica si el valor de la API es diferente al valor seleccionado para enlistar una opción, de lo contrario se establece la opción como seleccionada.
                        if (value != selected) {
                            content += `<option value="${value}">${text}</option>`;
                        } else {
                            content += `<option value="${value}" selected>${text}</option>`;
                        }
                    });
                } else {
                    content += '<option>No hay opciones disponibles</option>';
                }
                // Se agregan las opciones a la etiqueta select mediante su id.
                document.getElementById(select).innerHTML = content;
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
}

/*
*   Función para generar un gráfico de barras verticales. Requiere el archivo chart.js. Para más información https://www.chartjs.org/
*
*   Parámetros: canvas (identificador de la etiqueta canvas), xAxis (datos para el eje X), yAxis (datos para el eje Y), legend (etiqueta para los datos) y title (título del gráfico).
*
*   Retorno: ninguno.
*/
function barGraph(canvas, xAxis, yAxis, legend, title) {
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = [];
    // Se generan códigos hexadecimales de 6 cifras de acuerdo con el número de datos a mostrar y se agregan al arreglo.
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#ff5757');
    }
    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar el gráfico con los datos recibidos.
    const chart = new Chart(context, {
        type: 'bar',
        data: {
            labels: xAxis,
            //Datasaet =  datos = cada barra
            datasets: [{
                //Texto a mostrar con el mouse sobre la barra
                label: legend,
                //Datos de las barras = datos en Y
                data: yAxis,
                //Color de fondo de las brras
                backgroundColor: colors,
                //Pixeles para redondear las barras
                borderRadius: 10
            }]
        },
        options: {
            plugins: {
                //Mostrar titulo de la gráfica, no se muestra porque se pone por aparte
                title: {
                    display: false,
                    text: title
                },
                //Mostrar leyenda de la gráfica, no se muestra
                legend: {
                    display: false
                }
            },
            scales: {
                //Desaparecer lineas en Y
                x: {
                    grid: {
                      color: "transparent"
                    }
                },
                //Definir que empiece en 0
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


/*
*   Función para generar un gráfico de lineas. Requiere el archivo chart.js. Para más información https://www.chartjs.org/
*
*   Parámetros: canvas (identificador de la etiqueta canvas), xAxis (datos para el eje X), yAxis (datos para el eje Y), legend (etiqueta para los datos) y title (título del gráfico).
*
*   Retorno: ninguno.
*/
function lineGraph(canvas, xAxis, yAxis, legend, title) {
    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar el gráfico con los datos recibidos.
    const chart = new Chart(context, {
        type: 'line',
        data: {
            labels: xAxis,
            //Datasaet =  datos = cada barra
            datasets: [{
                //Texto a mostrar con el mouse sobre la barra
                label: legend,
                //Datos de las barras = datos en Y
                data: yAxis,
                //Color de fondo de las brras
                backgroundColor: 'rgba(250,110,110)',
                //Pixeles para redondear las barras
                borderRadius: 10,
                //Color de la linea
                borderColor: 'rgb(255,255,255)',
                //Definir que tenga relleno
                fill: true,
                //Curva de la linea
                tension: 0.2,
                //Definir "circulos" alrededor de cada punto
                pointStyle: 'circle',
                pointRadius: 10,
                pointHoverRadius: 15
            }]
        },
        options: {
            plugins: {
                //Mostrar titulo de la gráfica, no se muestra porque se pone por aparte
                title: {
                    display: false,
                    text: title
                },
                //Mostrar leyenda de la gráfica, no se muestra
                legend: {
                    display: false
                }
            },
            scales: {
                //Desaparecer lineas en Y
                x: {
                    grid: {
                      color: "transparent"
                    }
                },
                //Definir que empiece en 0
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

/*
*   Función para generar un gráfico de pastel. Requiere el archivo chart.js. Para más información https://www.chartjs.org/
*
*   Parámetros: canvas (identificador de la etiqueta canvas), legends (valores para las etiquetas), values (valores de los datos) y title (título del gráfico).
*
*   Retorno: ninguno.
*/
function pieGraph(canvas, legends, values, title) {
    // Se declara un arreglo para guardar códigos de colores en formato hexadecimal.
    let colors = ['#BF4141', '#802B2B', '#FF5757', '#aa1616', '#E64E4E']
    //Mezclar los datos del array de colores
    colors.sort(()=> Math.random() - 0.5);

    // Se establece el contexto donde se mostrará el gráfico, es decir, se define la etiqueta canvas a utilizar.
    const context = document.getElementById(canvas).getContext('2d');
    // Se crea una instancia para generar el gráfico con los datos recibidos.
    const chart = new Chart(context, {
        type: 'doughnut',
        data: {
            labels: legends,
            datasets: [{
                data: values,
                backgroundColor: colors
            }]
        },
        options: {
            plugins: {
                //Para mostrar titulo, no se muestra porque se pone por aparte
                title: {
                    display: false,
                    text: title
                }
            }
        }
    });
}

function openLogOut(){
    var logOutModal = new bootstrap.Modal(document.getElementById('confirmModal'))
    logOutModal.toggle()
}

// Función para mostrar un mensaje de confirmación al momento de cerrar sesión.
function logOut(site) {
    let api;
    if(site){
        api = 'admin/empleado.php?action='
    }else{
        api = 'public/usuario.php?action='
    }

    let btn = document.getElementById("confirmButton");
    btn.children[0].src = "../../resources/img/admin_icons/success.svg"
    btn.parentElement.parentElement.children[0].children[0].innerHTML = "Confirmar cierre de sesión"
    btn.parentElement.parentElement.children[2].innerHTML = "¿Está seguro de querer cerrar sesión? Es posible que hayan cambios sin guardar."
    btn.onclick = ()=>{
        fetch(SERVER + api + 'logOut', {
            method: 'get'
        }).then(function (request) {
            // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
            if (request.ok) {
                // Se obtiene la respuesta en formato JSON.
                request.json().then(function (response) {
                    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                    if (response.status) {
                        modal(1, response.message, 'index.html');
                    } else {
                        modal(2, response.exception, null);
                    }
                });
            } else {
                console.log(request.status + ' ' + request.statusText);
            }
        });
    }
}