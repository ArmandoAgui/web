
const login = document.getElementById("login"),
forgot = document.getElementById("forgot"),
code = document.getElementById("code");

const forgotBtn = document.getElementById("forgotBtn"),
codeBtn = document.getElementById("codeBtn");

forgotBtn.addEventListener("click", ()=>{
    login.style.cssText = "display: none !important;";
    forgot.style.cssText = "display: flex !important;";
})

codeBtn.addEventListener("click", ()=>{
    forgot.style.cssText = "display: none !important;";
    code.style.cssText = "display: flex !important;";
})

// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_USUARIO = SERVER + 'public/usuario.php?action=';

// Método manejador de eventos que se ejecuta cuando se envía el formulario de iniciar sesión.
document.getElementById('loginForm').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Petición para revisar si el administrador se encuentra registrado.
    fetch(API_USUARIO + 'logIn', {
        method: 'post',
        body: new FormData(document.getElementById('loginForm'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje en la consola indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    location.href = 'user_profile.html'
                } else {
                    modal(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    });
});