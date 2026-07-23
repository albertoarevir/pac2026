var access_token = '';

function mostrarError(msg) {
    var div = document.getElementById('login-error');
    var btn = document.getElementById('btnSesion');
    div.textContent = msg;
    div.style.display = 'block';
    btn.disabled = false;
}

function iniciar_sesion() {
    var btn  = document.getElementById('btnSesion');
    var form = document.getElementById('formLogin');
    var err  = document.getElementById('login-error');

    btn.disabled = true;
    err.style.display = 'none';

    var rut      = document.getElementById('rut_funcionario').value;
    var password = document.getElementById('clave_intranet').value;

    axios.post('http://autentificaticapi.carabineros.cl/api/auth/login', {
        rut:      rut,
        password: password,
        website:  form.dataset.website
    }).then(function (response) {
        access_token = response.data.success.access_token;

        document.cookie = 'token_de_acceso=' + access_token + '; SameSite=Strict; Path=/; Max-Age=3600';
        document.getElementById('accessToken').value = access_token;
        document.getElementById('rutHidden').value   = rut;

        form.action = form.dataset.loginUrl;
        form.submit();

    }).catch(function (error) {
        var msg = 'Error de conexion con el servidor de autenticacion.';

        if (error.response) {
            var data = error.response.data;
            if (data && data.errors) {
                msg = data.errors.rut || data.errors.password || 'Credenciales invalidas.';
            } else if (data && data.message) {
                msg = data.message;
            } else {
                msg = 'Error del servidor (' + error.response.status + '). Verifique sus credenciales.';
            }
        } else if (error.request) {
            msg = 'No se pudo conectar con el servidor de autenticacion. Verifique su red.';
        }

        mostrarError(msg);
    });
}

function hideOrShowPassword() {
    var pass = document.getElementById('clave_intranet');
    pass.type = pass.type === 'password' ? 'text' : 'password';
}