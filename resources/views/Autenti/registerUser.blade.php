@extends('layouts.admin')

@section('content')
    <script src="{{ asset('AutentificaTic/js/axios.min.js') }}"></script>
    <script src="{{ asset('AutentificaTic/js/cookies.min.js') }}"></script>

    <div class="row">
        <h2 style="font-size: 25px; margin-bottom: 3px; margin-left: 35px;">
            <strong>Registro de Usuarios en AutentificaTic</strong>
        </h2>
    </div>
    <br>
    <div class="row">
        <div class="col-md-5" style="margin-left: 30px">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <img src="{{ asset('AutentificaTic/images/carabineros.png') }}"
                             style="height: 32px; margin-right: 10px;">
                        Habilitar funcionario en AutentificaTic
                    </h3>
                </div>
                <div class="card-body">
                    <form name="form1" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="rut_funcionario">RUN del funcionario <small class="text-muted">(sin puntos ni guion)</small></label>
                            <input name="rut_funcionario" id="rut_funcionario"
                                   type="text" class="form-control"
                                   placeholder="Ej: 12345678K"
                                   required>
                            <div class="invalid-feedback">
                                <span id="rut_error"></span>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('/admin') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="button" id="btnHabilitar" class="btn btn-primary">
                                    <i class="bi bi-person-check"></i> Habilitar en AutentificaTic
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script @cspNonce>
        document.getElementById('btnHabilitar').addEventListener('click', function() {
            var rutVal = document.getElementById('rut_funcionario').value.trim();
            if (!rutVal) {
                alert('Debe ingresar el RUN del funcionario.');
                return;
            }

            var urlRegisterUser = 'http://autentificaticapi.carabineros.cl/api/institutional-app-user-from-external-app';

            var formData = new URLSearchParams();
            formData.append('rut', rutVal);

            axios({
                method: 'post',
                url: urlRegisterUser,
                headers: {
                    'Authorization': 'Bearer ' + docCookies.getItem('token_de_acceso'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: formData
            }).then(function(response) {
                alert('Usuario habilitado correctamente en AutentificaTic.');
                document.getElementById('rut_funcionario').value = '';
            }).catch(function(error) {
                var msg = (error.response && error.response.data && error.response.data.errors)
                    ? JSON.stringify(error.response.data.errors)
                    : 'Su sesion ha caducado. Cierre el sistema y vuelva a ingresar.';
                alert(msg);
                if (error.response && error.response.status === 401) {
                    docCookies.removeItem('token_de_acceso');
                    docCookies.removeItem('PERSONAL');
                    window.location.href = 'http://pacdilocar.des.carabineros.cl/';
                }
            });
        });
    </script>

    <script src="{{ asset('AutentificaTic/js/main.js') }}"></script>

@endsection
