@extends('layouts.admin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
	{{-- JavaScript y CSS necesarios 
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet" type="text/css" />
--}}
    <title>Plan Anual de Compras</title>
    <link rel="shortcut icon" href="{{ asset('AutentificaTic/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('AutentificaTic/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('AutentificaTic/css/style.css') }}">
    <script src="{{ asset('AutentificaTic/js/axios.min.js') }}"></script>
    <script src="{{ asset('AutentificaTic/js/cookies.min.js') }}"></script>

<style>
    /* --- FONDO COMPLETO SIN SCROLL --- */
   

    /* --- CONTENEDOR CENTRAL --- */
    .contenedor-centro {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 100%;
        max-width: 600px;
        padding: 0 20px;
        color: white;
    }

    .card-form {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
    }

    .titulo-app {
        font-size: 32px;
        font-weight: bold;
        text-shadow: 0 0 10px #000;
    }

    .subtitulo {
        font-size: 16px;
        margin-bottom: 20px;
        text-shadow: 0 0 10px #000;
    }

    .logo {
        width: 100px;
        margin-bottom: 15px;
    }

    /* INPUT */
    .input-style {
        width: 100%;
        padding: 12px;
        font-size: 18px;
        border: none;
        border-bottom: 2px solid green;
        background: transparent;
        outline: none;
        text-align: center;
    }

    .boton {
        background: green;
        color: white;
        padding: 10px 25px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: 0.3s;
    }

    .boton:hover {
        background: #0c4c0c;
    }
.fondo-app {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("{{ asset('AutentificaTic/images/bgOLD.png') }}") no-repeat center center fixed;
    background-size: cover;
    overflow: hidden;
}
   
</style>

    <script>
        $(document).ready(function() {
            document.title = 'Plan Anual de Compras';
        });

        function valida_forma() {
            var urlRegisterUser = 'http://autentificaticapi.carabineros.cl/api/institutional-app-user-from-external-app';

            axios({
                method: 'post',
                url: urlRegisterUser,
                headers: {
                    'Authorization': 'Bearer ' + docCookies.getItem('token_de_acceso'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                params: {
                    rut: document.form1.rut_funcionario.value,
                    website: 'http://pacdilocar.des.carabineros.cl/'
                }
            }).then(response => {
                alert("Usuario Habilitado en Autentifica TIC...");
            }).catch(error => {
                alert("Sr. Usuario su clave caduco, cerrar el sistema y reintentar...");
                window.location.href = "http://pacdilocar.des.carabineros.cl/";
                docCookies.removeItem('token_de_acceso');
                docCookies.removeItem('PERSONAL');
            });
        }
    </script>
<form name="form1" method="POST">
    @csrf
<div class="fondo-app">
    <div class="contenedor-centro">

        <img src="{{ asset('AutentificaTic/images/carabineros.png') }}" class="logo">

        <div class="titulo-app">Sistema de Gestión y Control</div>
        <div class="titulo-app">
              PLAN ANUAL DE COMPRAS</div>
        <div class="subtitulo">Oficina Asesoría Técnica.</div>

        <div class="card-form">

            <div class="input-group form-group">
                <input name="rut_funcionario" id="rut_funcionario"
                    type="text" class="input-style"
                    onChange="checkRut(this, 'rut_funcionario')" required>
                <label class="label-input"><i class="fa fa-user"></i> RUN (sin puntos ni guión)</label>
                <div class="invalid-feedback">
                    <span id="rut_error"></span>
                </div>
            </div>

            <br>

            <input type="button" value="Ingresar Usuarios en AutentificaTic" class="boton"
                   onclick="valida_forma(); return false;">

            <p style="margin-top:15px;"><strong>Registro de Usuarios en AutentificaTic</strong></p>

        </div>
    </div>
    </div>

    
     <script src="{{ asset('AutentificaTic/js/main.js') }}"></script>

<
    </form>

 


    
@endsection
