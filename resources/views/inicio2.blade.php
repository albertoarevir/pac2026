<!DOCTYPE html>
<html lang="es">
<head>
    <!-- CRÍTICO: Asegúrate de que estas rutas sean correctas -->
    <script src="assets/autenticaTic/js/jquery-3.3.1.min.js"></script>
    <script src="assets/autenticaTic/js/cookies.min.js"></script>
    <title>KÁRDEX VEHICULAR TÁCTICO</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <meta name="theme-color" content="#3c763d;">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"> 
    
    <style>
        /* Estilo para el contenedor de mensajes de error */
        .alert-error-ui {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-login:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }
    </style>
    
    <script>
        // Limpia el sessionStorage al cargar la página
        sessionStorage.clear(); 

        /**
         * Muestra un mensaje de error en la UI en lugar de usar alert().
         * @param {string} message - Mensaje de error a mostrar.
         */
        function displayJsError(message) {
            const errorContainer = document.getElementById('js-error-container');
            if (errorContainer) {
                errorContainer.innerHTML = message ? '<div class="alert-error-ui">' + message + '</div>' : '';
            }
        }
        
        /**
         * Restablece el botón de inicio de sesión a su estado original.
         */
        function resetSesionButton() {
            const btnSesion = document.getElementById("enviar");
            if (btnSesion) {
                btnSesion.removeAttribute("disabled");
                btnSesion.value = "Iniciar Sesion";
            }
        }

        //******************************************** Submit (Paso 3)
        function accion_submit(){
            document.form_login.username.value = document.form_login.rut.value;
            // CRÍTICO: Asegúrate que 'index.php' sea la ruta correcta de destino en tu sistema.
            document.form_login.action = 'index.php';
            document.form_login.submit();
        }

        //******************************************** Tiempo caducidad de password (Paso 2)
        function timeExpirationPassword(){
            const token = docCookies.getItem('access_token');
            if (!token) {
                displayJsError("Error: No se encontró el token de acceso para verificar la caducidad.");
                resetSesionButton();
                return;
            }

            $.ajax({
                url: 'http://autentificaticapi.carabineros.cl/api/auth/user',
                type:'get',
                headers: {
                    'Authorization' :'Bearer ' + token,
                    'Accept': 'application/json'
                },
                success: function(response){
                    // Lógica para determinar el mensaje de caducidad
                    var color = "white";
                    var tiempoPass = ""; 
                    if (response.success && response.success.user) {
                        const days = response.success.user.password_expiration;
                        if (days <= 5){
                            color ="red";
                        } else if (days > 5 && days <= 15){
                            color ="yellow";
                        } 
                        tiempoPass = '<div style="color: ' + color + ';">SU PASSWORD CADUCARÁ EN ' + days + " DÍAS </div>";  
                    }
                    
                    // Guardar y continuar al submit
                    sessionStorage.setItem("timePass", tiempoPass);
                    document.getElementById("timePass").value = tiempoPass; // También actualiza el campo oculto
                    accion_submit();

                },
                error: function(xhr, status, errorThrown){
                    // Si falla la obtención del usuario, se asume que no hay caducidad que mostrar y se envía el formulario de todas formas
                    console.warn("ADVERTENCIA: Falló la verificación de caducidad de password. Continuando con el login.");
                    console.error("Detalles del error en verificación de caducidad:", xhr.responseJSON || errorThrown);
                    
                    // Se permite continuar, pero se podría agregar un error UI si es crítico
                    accion_submit();
                }
            }); 
        }

        //******************************************** Valida ingreso al Sistema (Paso 1)
        function iniciar_sesion(){
            // Limpiar errores previos y deshabilitar botón
            displayJsError(''); 
            const btnSesion = document.getElementById("enviar");
            if (btnSesion) {
                btnSesion.setAttribute("disabled", "true");
                btnSesion.value = "Cargando...";
            } else {
                 console.error("Botón 'enviar' no encontrado.");
                 return;
            }

            const rut = document.getElementById("rut").value.trim();
            const password = document.getElementById("password").value;
            const website = document.getElementById("website").value;

            if (!rut || !password) {
                displayJsError("Debe ingresar su RUT y Contraseña correctamente.");
                resetSesionButton();
                return;
            }
        
            //************ INICIO AUTENTIFICATIC 
            var parametros = {
                "rut" : rut,
                "password" : password,
                "website": website
            };
            
            $.ajax({
                data: parametros,
                url: 'http://autentificaticapi.carabineros.cl/api/auth/login',
                type:'post',
                success: function(response){
                    // Autenticación exitosa. Guardar token y pasar a verificar caducidad (Paso 2).
                    const access_token = response.success.access_token;
                    
                    docCookies.setItem("access_token", access_token);
                    sessionStorage.setItem("accessToken", access_token);
                    
                    // Inicia el proceso de verificación de caducidad
                    timeExpirationPassword(); 
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    // Errores de la API
                    console.error("Error en login:", XMLHttpRequest.responseJSON || errorThrown);
                    
                    let errorMessage = "Error de autenticación: Credenciales no válidas.";
                    
                    if (XMLHttpRequest.responseJSON && XMLHttpRequest.responseJSON.errors) {
                        const errors = XMLHttpRequest.responseJSON.errors;
                        if (errors.rut) {
                            errorMessage = errors.rut;
                        } else if (errors.password) {
                            errorMessage = errors.password;
                        } else if (errors.message) {
                            errorMessage = errors.message;
                        }
                    } else if (textStatus === 'error') {
                        errorMessage = "Error de red o CORS: No se pudo conectar con el servidor de autenticación.";
                    }
                    
                    displayJsError(errorMessage); 
                    resetSesionButton();
                }
            });
            //************ FIN AUTENTIFICATIC   
        }

        //******************************************** Valida ingreso al Sistema (Versión anterior - Actualizada para no usar alert)
        function validaUsuario() {
            // Se recomienda usar iniciar_sesion() en su lugar.
            var contrasena = document.form_login.password.value.replace("'", "");
            if (document.form_login.rut.value=='' || document.form_login.password.value==''){
                displayJsError("Debe ingresar Rut y Contraseña correctamente");
                if (document.form_login.rut.value==''){
                    document.form_login.rut.focus();
                }else{
                    document.form_login.password.focus();
                }
            }else{
                document.form_login.password.value = contrasena;
                document.form_login.accion.value = 'validaIngreso';
                // CRÍTICO: Esta ruta puede estar obsoleta. 
                document.form_login.action = 'login.api'; 
                document.form_login.submit();
            }
        }
        
        //******************************************** despliega alert con texto pre seleccionado
        function mensaje_alert(texto) {
            displayJsError(texto);
        }
        
        // El resto de funciones (mensaje, mensaje2) se dejan como estaban.

        document.addEventListener('DOMContentLoaded', () => {
            // Diagnóstico de librerías
            console.log("--- Diagnóstico de Librerías ---");
            if (typeof jQuery === 'undefined') {
                console.error("DIAGNÓSTICO: jQuery NO está cargado. La función iniciar_sesion fallará. Revisa la ruta a 'jquery-3.3.1.min.js'.");
            } else {
                console.log("DIAGNÓSTICO: jQuery está cargado (versión: " + jQuery.fn.jquery + ").");
            }
            if (typeof docCookies === 'undefined') {
                console.error("DIAGNÓSTICO: docCookies NO está cargado. La función iniciar_sesion fallará. Revisa la ruta a 'cookies.min.js'.");
            } else {
                console.log("DIAGNÓSTICO: docCookies está cargado.");
            }
            console.log("--------------------------------");
        });
    </script> 
</head> 
<body class="bg-login"> 
    <div class="margintop-login">
        
        <div class="carabineros">
            <!-- Título y logo (código omitido por brevedad) -->
        </div>
        <div style="clear:both"></div>
        
        <!-- CONTENEDOR DE ERRORES DE JAVASCRIPT/AJAX -->
        <div id="js-error-container"></div>
        
        <div class="login-page" class="background-black-06">
            <div class="autentificatic-sello text-center">
                <a href="http://autentificaticapi.carabineros.cl/assets/documents/procedimiento_de_seguridad.pdf" target="_blank">
                    <img src="http://autentificaticapi.carabineros.cl/assets/images/autentificatic.png" width="280" height="auto" style="padding-top: 6px;">
                </a>
            </div>
            <div class="text-center">
                <a href="#popup"><img src="assets/images/info.png" width="60" height="auto"></a>
            </div>
            
            <div class="input-size"> 
                <form id="form_login" name="form_login" method="post">
                    <input type="hidden" name="username" id="username">
                    <input type="hidden" name="timePass" id="timePass" value=''> 
                    
                    <div class="input-group form-group">
                        <input name="rut" id="rut" type="text" class="input-style" size="10" onChange="checkRut(this, 'rut')" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label class="label-input"><i class="fa fa-user"></i> RUT (sin puntos ni guión)</label>
                        <div class="invalid-feedback">
                            <span id="rut_error"></span>
                        </div>
                    </div>
                    
                    <div class="input-group form-group">
                        <input name="password" id="password" type="password" class="input-style" size="20" required>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label class="label-input"><i class="fa fa-lock"></i> Contraseña</label>
                        <div class="invalid-feedback">
                            <span id="password_error"></span>
                        </div>
                    </div>

                    <input type="hidden" name="website" id="website" value="http://name-system.carabineros.cl">

                    <div style="float: left;">
                        <a href="http://autentificatic.carabineros.cl/password/reset" style="width: 50%" >¿Olvidaste tu contraseña?</a>
                    </div>

                    <div style="float: right;">
                        <a href="http://autentificatic.carabineros.cl/register" style="width: 50%">Registrate en autentificatic</a>
                    </div>

                    <div style="clear: both; padding-bottom: 15px;"></div>

                    <div class="text-center">
                        <!-- El ID 'enviar' es usado por el JS para el estado de carga -->
                        <input type="button" class="btn-login" name="enviar" value="Iniciar Sesion" id="enviar" onClick="iniciar_sesion();" />
                    </div>
                    
                    <div class="text-center">
                        <p style="margin-bottom: 0px;"><strong>K&aacute;rdex Vehicular; V1.0.0 - 22</strong></p>
                    </div>
                </form> 
            </div>
        </div>

        <?php 
        // Lógica PHP para mostrar errores del backend (dejada intacta)
        if (isset($logout)){ 
            // ... (código PHP omitido) ...
        } 
        ?>
    </div>

    <!-- Resto del HTML (Logos, Popup) omitido por brevedad -->
    
    <div class="logos-bottom">
        <img src="http://intranetv2.carabineros.cl/DescargasTIC/aniversario.png" width="70" height="auto" style="float: left; padding-top: 20px;">
        <img src="http://intranetv2.carabineros.cl/DescargasTIC/sello-TIC.png" width="70" height="auto" style="float: right;">
    </div>

    <div class="text-center slogan"><img src="http://intranetv2.carabineros.cl/DescargasTIC/slogan.png" style="padding-top: 20px;"></div>
    
    <div id="popup" class="overlay">
        <div id="popupBody">
            <h2>Objetivo del sistema</h2>
            <a id="cerrar" href="#">&times;</a>
            <div class="popupContent">
                <p>El sistema Documentación Electrónica tiene como objetivo tramitar de forma oficial los documentos a nivel nacional de Carabineros de Chile.</p>
                <p>La actual plataforma funciona desde Noviembre de 2011, siendo la segunda version de este aplicativo.</p>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="assets/autenticaTic/js/main.js"></script>
</body>
</html>