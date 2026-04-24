@extends('layouts.admin')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        /* Contenedor del semáforo para posicionar elementos */
        .semaforo-container {
            position: relative;
            /* Esencial para posicionar las luces */
            width: 100px;
            /* Ajusta el ancho según tu imagen base */
            height: 250px;
            /* Ajusta la altura según tu imagen base */
            margin: 0 auto 15px auto;
            /* Centra el semáforo y da margen inferior */
        }

        /* Imagen de la carcasa del semáforo */
        .semaforo-base {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* Asegura que la imagen se ajuste sin distorsionarse */
            position: absolute;
            /* Para que las luces se puedan superponer */
            top: 0;
            left: 0;
        }

        /* Estilos para las luces (círculos de Font Awesome) */
        .semaforo-luz {
            position: absolute;
            font-size: 2.2rem;
            /* Tamaño del círculo, ajusta según tu imagen base */
            opacity: 0.2;
            /* Inicialmente "apagadas" */
            filter: brightness(0.5);
            /* Oscurece las luces apagadas */
            transition: opacity 0.3s ease-in-out, filter 0.3s ease-in-out;
            /* Transición suave */
            color: #444;
            /* Color base oscuro para las luces apagadas */
        }

        /* Posiciones de las luces (ajusta según tu imagen base de semáforo) */
        .luz-roja {
            top: 20px;
            /* Ajusta la posición vertical de la luz roja */
            left: 35px;
            /* Ajusta la posición horizontal de la luz roja */
            color: red;
            /* Color real de la luz */
        }

        .luz-amarilla {
            top: 100px;
            /* Ajusta la posición vertical de la luz amarilla */
            left: 35px;
            /* Ajusta la posición horizontal de la luz amarilla */
            color: gold;
            /* Color real de la luz */
        }

        .luz-verde {
            top: 180px;
            /* Ajusta la posición vertical de la luz verde */
            left: 35px;
            /* Ajusta la posición horizontal de la luz verde */
            color: limegreen;
            /* Color real de la luz */
        }

        /* Clases para "encender" las luces */
        .luz-activa {
            opacity: 1;
            filter: brightness(1);
            /* Brillo completo */
        }

        /* Estilo de la tarjeta para el semáforo (si no usas Bootstrap) */
        .card-semaforo {
            border-radius: 0.5rem;
            color: white;
            /* El texto será blanco */
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background-color: #343a40;
            /* Fondo oscuro para la tarjeta del semáforo */
        }

        .card-semaforo .card-title,
        .card-semaforo .h1,
        .card-semaforo p {
            color: white;
            /* Asegura que el texto sea blanco */
        }

        /* Opcional: Estilos para el contenedor del gráfico */
        .chart-container {
            width: 100%;
            /* Los pie charts suelen verse mejor un poco más pequeños */
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Para hacer el canvas responsivo y con una altura consistente */
        canvas {
            max-width: 100%;
            height: 300px;
            /* Altura consistente para todos los gráficos */
            display: block;
            /* Asegura que no haya espacio extra debajo del canvas */
        }

        /* Estilo para el ícono de la tarjeta de proyectos */
        .text-custom-info {
            color: #17a2b8;
            /* Bootstrap info color */
        }

        /* Estilos para las nuevas cajas de información por departamento */
        .info-box {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            height: 100%;
            /* Asegura que todas las info-box dentro de un row tengan la misma altura */
        }

        .info-box-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            /* Iconos redondos */
            font-size: 2.2em;
            /* Tamaño del icono */
            color: #fff;
            margin-right: 15px;
        }

        .info-box-content {
            flex-grow: 1;
        }

        .info-box-text {
            margin: 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }

        .info-box-number {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Colores de fondo para los iconos */
        .info-box-icon.bg-red {
            background-color: #dc3545;
        }

        /* Danger */
        .info-box-icon.bg-primary {
            background-color: #007bff;
        }

        /* Primary */
        .info-box-icon.bg-green {
            background-color: #28a745;
        }

        /* Success */
        .info-box-icon.bg-yellow {
            background-color: #ffc107;
        }

        /* Warning */

        /* Ajustes responsivos para las info-box */
        @media (max-width: 767.98px) {
            .info-box {
                flex-direction: row;
                /* Mantener en fila para móviles */
                text-align: left;
                align-items: center;
                justify-content: flex-start;
            }

            .info-box-icon {
                margin-bottom: 0;
                margin-right: 10px;
            }
        }
    </style>
    <div class="row" style="margin-left: 45px">
        <?php
        // Obtener el año actual
        $currentYear = now()->year;
        ?>
        <h1 style="font-size: 30px; color:rgb(12, 13, 14); margin-bottom: 0;"><strong>Dashboard - Panel
                Administrativo año {{ $currentYear }}</strong></h1>
    </div>
    <hr>

    <div class="col-md-11">
        {{-- Formulario de filtro de departamento --}}
        <div class="container-fluid mb-4" style="margin-left: 40px;">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('dashboard') }}" method="GET" class="form-inline d-flex align-items-center">

                        {{-- 1. Nuevo Filtro por Año --}}
                        <div class="form-group mb-2 mr-4">
                            <label for="year" class="mr-2" style="font-size: 20px">Filtrar por Año:</label>
                            {{-- Asegúrate de que $availableYears exista en tu controlador y contenga los años disponibles --}}
                            <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                                {{-- Si usas un arreglo de años como $availableYears = [2025, 2024, 2023] --}}
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}"
                                        {{ (string) $year === (string) $selectedYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Filtro por Departamento Existente --}}
                        <div class="form-group mb-2 mr-2">
                            <label for="departamento_id" class="mr-2" style="font-size: 20px">Filtrar por
                                Departamento:</label>
                            <select name="departamento_id" id="departamento_id" class="form-control"
                                onchange="this.form.submit()">
                                @foreach ($availableDepartmentsForSelect as $departamento)
                                    <option value="{{ $departamento->id }}"
                                        {{ (string) $departamento->id === (string) $selectedDepartamentoId ? 'selected' : '' }}>
                                        {{ $departamento->detalle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Fin del formulario de filtro --}}


        {{-- Contenedor original de las tres primeras tarjetas --}}
        <div class="container-md-12" style="margin-left: 40px">
            <h1 style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">1.- Resúmen general del total de
                Presupuesto /Comprometido y Porcentaje de Ejecución en la Dirección de Logística</h1> <br>
            <h2 style="font-size: 18px; color:rgb(12, 13, 14); margin-bottom: 0;"><strong>Criterios para la
                    Semaforización</strong></h2> <br>
            <h2 style="font-size: 18px; color:rgb(12, 13, 14); margin-bottom: 0;"><strong>* Porcentaje mayor a 75% --->
                    Ejecución Aceptable (Color verde)<h2>
                        <h2 style="font-size: 18px; color:rgb(12, 13, 14); margin-bottom: 0;"><strong>* Porcentaje entre 60%
                                y 74% ---> Ejecución Media (Color Amarillo)<h2>
                                    <h2 style="font-size: 18px; color:rgb(12, 13, 14); margin-bottom: 0;"><strong>*
                                            Porcentaje menor a 60% ---> Baja Ejecución (Color Rojo)</strong> </h2>
                                    <br>
                                    <hr>

                                    <div class="container-md-12">
                                        <div class="row">
                                            <div class="col-md-3 col-4">
                                                <div class="small-box bg-primary">
                                                    <div class="inner">
                                                        <h4 style="font-size: 20px">Total Presupuesto</h4>
                                                        <h4><strong> $
                                                                {{ number_format($total_presupuesto_general, 0, ',', '.') }}</strong>
                                                        </h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="bi bi-currency-dollar" style="font-size: 2.8em;"></i>
                                                    </div>
                                                    <a href="{{ url('/pac') }}" class="small-box-footer"
                                                        style="font-size: 20px; color:rgb(12, 13, 14);
                                        margin-bottom: 0;">
                                                        Mas información
                                                        <i class="fas fa-arrow-circle-right"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-4">
                                                <div class="small-box bg-success">
                                                    <div class="inner">
                                                        <h4 style="font-size: 20px">Total Comprometido</h4>
                                                        <h4><strong> $
                                                                {{ number_format($total_comprometido_general, 0, ',', '.') }}</strong>
                                                        </h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="bi bi-graph-up-arrow" style="font-size: 2.8em;"></i>
                                                    </div>
                                                    <a href="{{ url('/pac') }}" class="small-box-footer"
                                                        style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">Mas
                                                        información <i class="fas fa-arrow-circle-right"></i></a>
                                                </div>
                                            </div>


                                            <div class="col-md-3 col-4">
                                                <div class="small-box bg-warning">
                                                    <div class="inner">
                                                        <h4 style="font-size: 20px">Porcentaje de Ejecución</h4>
                                                        <h4> <strong> {{ $total_porcentaje_ejecucion_general }} % </strong>
                                                        </h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="bi bi-percent" style="font-size: 2.8em;"></i>
                                                    </div>
                                                    <a href="#" class="small-box-footer"
                                                        style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">Mas
                                                        información <i class="fas fa-arrow-circle-right"></i></a>
                                                </div>
                                            </div>


                                            {{-- TARJETA --}}
                                            <div class="col-md-2 col-2 d-flex">

                                                @php
                                                    // ... Lógica PHP (se mantiene) ...
                                                    $porcentaje = $total_porcentaje_ejecucion_general;
                                                    $imagenSemaforo =
                                                        $porcentaje > 75
                                                            ? 'semaforo_verde.jpg'
                                                            : ($porcentaje >= 60 && $porcentaje <= 74
                                                                ? 'semaforo_amarillo.jpg'
                                                                : 'semaforo_rojo.jpg');
                                                    $estadoTexto =
                                                        $porcentaje > 75
                                                            ? 'Ejecución Aceptable'
                                                            : ($porcentaje >= 60 && $porcentaje <= 74
                                                                ? 'Ejecución Media'
                                                                : 'Baja Ejecución');

                                                    $colorFondoTarjeta = 'bg-light';
                                                    $textoColorClase = 'text-dark';
                                                    $colorFooter = 'bg-secondary';
                                                @endphp

                                                <div
                                                    class="card h-92 w-100 {{ $colorFondoTarjeta }} {{ $textoColorClase }}">

                                                    <div
                                                        class="card-body text-center d-flex flex-column justify-content-center align-items-center">

                                                        <h5 class="card-title text-uppercase mb-3">
                                                            <strong>Indicador - KPI</strong>
                                                        </h5>

                                                        {{-- ** CONTENEDOR FLEX PRINCIPAL: SEPARA EL TEXTO Y LA IMAGEN ** --}}
                                                        <div
                                                            class="d-flex justify-content-between align-items-center w-100">

                                                            {{-- COLUMNA 1: Texto (Alineado a la izquierda y ocupa el espacio) --}}
                                                            <div class="text-start flex-grow-1 me-3">

                                                                <strong>{{ $estadoTexto }}</strong>

                                                            </div>


                                                        </div>
                                                    </div>



                                                </div>

                                            </div>
                                            {{-- FIN TARJETA --}}


                                            <div class="col-md-1 col-4">
                                                <div class="small-box bg-light">
                                                    <div class="inner">

                                                        <div class="ms-auto">
                                                            <h4 class="card-title text-uppercase mb-3">
                                                                <strong>semáforo</strong>
                                                            </h4>
                                                            <center>
                                                                @if ($imagenSemaforo)
                                                                    <img src="{{ 'images/' . $imagenSemaforo }}"
                                                                        alt="Semáforo de Ejecución" class="img-fluid"
                                                                        {{-- CLAVE: Aumentar el tamaño del botón aquí --}}
                                                                        style=" height: 60px; width: 60px;">
                                                                @endif
                                                            </center>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>




                                        </div>
                                    </div>



                                    {{-- cantidad de proyectos --}}
                                    <div class="container-md-12" style="margin-left: 40px">
                                        <h1 style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">2.- Indicador
                                            cantidad de
                                            Proyectos, según
                                            cada Departamento</h1>
                                        <br>
                                        <div class="col-md-12">
                                            <div class="row d-flex align-items-stretch"> {{-- Agrega d-flex y align-items-stretch aquí --}}
                                                <div class="col-md-3">
                                                    <div class="card rounded-lg h-100"> {{-- Cambia h-400 por h-100 para que ocupe el 100% de la altura disponible --}}
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="col-md-3">
                                                                    <i class="bi bi-list-task text-custom-info me-3"
                                                                        style="font-size: 2.5rem;"></i>
                                                                </div>
                                                                <div class="card-title-container flex-grow-1">
                                                                    <h5 class="card-title text-uppercase fw-bold mb-0">
                                                                        <strong>Cantidad de Proyectos Registrados</strong>
                                                                    </h5>
                                                                </div>
                                                            </div>

                                                            <div class="text-center">
                                                                <h1 class="display-4 fw-bold text-dark mb-0">
                                                                    {{ number_format($total_proyectos_registrados, 0, ',', '.') }}
                                                                </h1>
                                                                <a href="{{ url('modalidad') }}" class="btn btn-info">Ver
                                                                    Proyectos</a>
                                                            </div>

                                                            <br>
                                                            @if ($ultima_actualizacion_pac)
                                                                <p style="text-align: justify; font-size: 16px;">Fecha de
                                                                    última
                                                                    actualización de
                                                                    ingresos de proyectos:
                                                                    {{ $ultima_actualizacion_pac->updated_at->format('d/m/Y H:i:s') }}
                                                                    por el
                                                                    <strong>{{ $ultima_actualizacion_pac->departamento->detalle }}</strong>
                                                                </p>
                                                            @else
                                                                <p>No hay proyectos registrados para el año actual o
                                                                    departamento.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    {{-- Se eliminó el estilo inline de height y width para usar el estilo global en <style> --}}
                                                    <canvas id="proyectosPorDepartamentoChart"
                                                        style="border: 1px solid #7c2121; height: 100%; width: 100%;"></canvas>
                                                    {{-- Asegúrate de que el canvas también ocupe el 100% de la altura --}}
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                    </div>

                                    {{-- fin cantidad de proyectos --}}

                                    <div class="container-md-12" style="margin-left: 40px">
                                        <h1 style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">3.-
                                            Presupuesto asignado v/s
                                            total
                                            devengado, según cada
                                            Departamento</h1>
                                        <br>
                                        <div class="col-md-12">
                                            <div class="row">
                                                {{-- Se eliminó el estilo inline de height y width para usar el estilo global en <style> --}}
                                                <canvas id="chart2" style="border: 1px solid #7c2121;"></canvas>
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                    </div>

                                    {{-- Nuevo contenedor para las tarjetas de Licitaciones y Órdenes de Compra --}}
                                    <div class="container-md-12" style="margin-left: 30px">
                                        <h1 style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">4.- Cantidad
                                            de Licitaciones,
                                            con
                                            indicación de aquellas que no mantienen ordenes de compras asociadas e
                                            indicación del registro
                                            total de
                                            ordenes de compras</h1>
                                        <br>
                                        <div class="col-md-12">
                                            <div class="row align-items-stretch">
                                                <div class="col-md-4 col-sm-12 mb-4">
                                                    <div class="card h-100">
                                                        <h5 style="text-align: justify; font-size: 16px;"
                                                            class="card-header bg-info">
                                                            Cantidad de
                                                            Licitaciones
                                                            registradas
                                                        </h5>
                                                        <div class="card-body d-flex flex-column">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between w-100 px-3">
                                                                <h1 style="font-size: 40px; margin-left: 40px;">
                                                                    {{ number_format($total_licitaciones_registradas, 0, ',', '.') }}
                                                                </h1>
                                                                <a href="{{ url('modalidad') }}" class="btn btn-info">Ver
                                                                    Licitaciones</a>
                                                            </div>
                                                            <div class="flex-grow-1"></div>
                                                            @if ($ultima_actualizacion_licitacion)
                                                                <p class="mt-auto"
                                                                    style="text-align: justify; font-size: 16px;"> Fecha de
                                                                    última
                                                                    actualización de licitaciones:
                                                                    {{ $ultima_actualizacion_licitacion->updated_at->format('d/m/Y H:i:s') }}
                                                                    por el
                                                                    <strong>
                                                                        @if ($ultima_actualizacion_licitacion->pac && $ultima_actualizacion_licitacion->pac->departamento)
                                                                            {{ $ultima_actualizacion_licitacion->pac->departamento->detalle }}
                                                                        @else
                                                                            (Departamento Desconocido)
                                                                        @endif
                                                                    </strong>
                                                                </p>
                                                            @else
                                                                <p class="mt-auto"
                                                                    style="text-align: justify; font-size: 16px;">No hay
                                                                    licitaciones
                                                                    registradas para el año actual o departamento.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Cantidad de Licitaciones sin Ordenes de Compras --}}
                                                <div class="col-md-4 col-sm-12 mb-4">
                                                    <div class="card h-100">
                                                        <h5 style="text-align: justify; font-size: 16px;"
                                                            class="card-header bg-info">
                                                            Licitaciones sin
                                                            Ordenes de Compras</h5>
                                                        <div class="card-body d-flex flex-column">
                                                            <div
                                                                class="row flex-grow-1 d-flex align-items-center justify-content-center">
                                                                <div class="col-md-12">
                                                                    <h1 style="font-size: 50px; text-align: center;">
                                                                        {{ number_format($total_licitaciones_sin_ordenes, 0, ',', '.') }}
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Cantidad de Ordenes de Compras registradas --}}
                                                <div class="col-md-4 col-sm-12 mb-4">
                                                    <div class="card h-100">
                                                        <h5 style="text-align: justify; font-size: 16px;"
                                                            class="card-header bg-info">
                                                            Cantidad de
                                                            Ordenes de
                                                            Compras registradas</h5>
                                                        <div class="card-body d-flex flex-column">
                                                            <div class="row flex-grow-1">
                                                                <div
                                                                    class="col-md-6 d-flex flex-column justify-content-center">
                                                                    <h5 class="card-title">Se desglosa por estado, según
                                                                        gráfico:</h5>
                                                                    <br>
                                                                    <a href="{{ url('ordenes') }}"
                                                                        class="btn btn-info mt-2">Ver Ordenes
                                                                        de
                                                                        Compras</a>
                                                                </div>
                                                                <div
                                                                    class="col-md-3 d-flex align-items-center justify-content-center">
                                                                    <h1 style="font-size: 50px; text-align: center;">
                                                                        {{ number_format($total_ordenes_registradas, 0, ',', '.') }}
                                                                    </h1>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Fin de la tarjeta de Cantidad de Ordenes de Compras registradas --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row" style="margin-left: 20px">

                                            <div class="col-md-6">
                                                {{-- Se eliminó el estilo inline de height y width para usar el estilo global en <style> --}}
                                                <canvas id="licitacionesEstadoChart"
                                                    style="border: 1px solid #7c2121;"></canvas>
                                            </div>

                                            <div class="col-md-6">
                                                {{-- Se eliminó el estilo inline de height y width para usar el estilo global en <style> --}}
                                                <canvas id="comprasEstadoChart"
                                                    style="border: 1px solid #7c2121;"></canvas>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- Script único para la inicialización de todos los gráficos --}}
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            // Registrar el plugin datalabels globalmente una vez
                                            Chart.register(ChartDataLabels);

                                            // --- Configuración para el gráfico chart2 (Barras Verticales) ---
                                            const ctxChart2 = document.getElementById('chart2').getContext('2d');
                                            const labelsChart2 = @json(array_map(fn($dato) => $dato['departamento']->detalle, $datos_departamentos));
                                            const presupuestosChart2 = @json(array_map(fn($dato) => $dato['presupuesto'], $datos_departamentos));
                                            const comprometidosChart2 = @json(array_map(fn($dato) => $dato['comprometido'], $datos_departamentos));
                                          

                                            // Obtener el color primary de Bootstrap
                                            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--bs-primary')
                                                .trim() || 'rgba(0, 123, 255, 1)';

                                            const dataChart2 = {
                                                labels: labelsChart2,
                                                datasets: [{
                                                        label: 'Total Presupuesto',
                                                        data: presupuestosChart2,
                                                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Alpha corregido a 0.7
                                                        borderColor: primaryColor,
                                                        //borderWidth: 1,
                                                        datalabels: {
                                                            anchor: 'end',
                                                            align: 'end',
                                                            color: '#000',
                                                            font: {
                                                                weight: 'bold',
                                                                size: 11
                                                            },
                                                            display: true,
                                                            formatter: function(value) {
                                                                return '$' + Number(value).toLocaleString();
                                                            }
                                                        }
                                                    },
                                                    {
                                                        label: 'Total Devengado',
                                                        data: comprometidosChart2,
                                                        backgroundColor: 'rgba(25, 135, 84, 0.7)', // Alpha corregido a 0.7
                                                        borderColor: 'rgba(75, 192, 192, 1)',
                                                        // borderWidth: 1,
                                                        datalabels: {
                                                            anchor: 'end',
                                                            align: 'end',
                                                            color: '#000',
                                                            font: {
                                                                weight: 'bold',
                                                                size: 11
                                                            },
                                                            display: true,
                                                            formatter: function(value) {
                                                                return '$' + Number(value).toLocaleString();
                                                            }
                                                        }
                                                    }
                                                ]
                                            };

                                            const configChart2 = {
                                                type: 'bar',
                                                data: dataChart2,
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    plugins: {
                                                        legend: {
                                                            position: 'top',
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Presupuesto asignado y total Devengado por Departamento'
                                                        },
                                                        datalabels: {
                                                            formatter: function(value) {
                                                                return '$' + Number(value).toLocaleString();
                                                            }
                                                        }
                                                    },
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            ticks: {
                                                                callback: function(value) {
                                                                    return '$' + Number(value).toLocaleString();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            };

                                            new Chart(ctxChart2, configChart2);

                                            // --- Configuración para el gráfico de BARRAS HORIZONTALES de licitaciones ---
                                            const chartLicitacionLabels = @json($chartLicitacionLabels);
                                            const chartLicitacionData = @json($chartLicitacionData);

                                            const ctxLicitaciones = document.getElementById('licitacionesEstadoChart').getContext('2d');
                                            new Chart(ctxLicitaciones, {
                                                type: 'bar', // El tipo sigue siendo 'bar'
                                                data: {
                                                    labels: chartLicitacionLabels,
                                                    datasets: [{
                                                        label: 'Número de Licitaciones',
                                                        data: chartLicitacionData,
                                                        backgroundColor: [
                                                            'rgba(75, 192, 192, 0.7)',
                                                            'rgba(153, 102, 255, 0.7)',
                                                            'rgba(255, 159, 64, 0.7)',
                                                            'rgba(255, 99, 132, 0.7)',
                                                            'rgba(54, 162, 235, 0.7)',
                                                            'rgba(255, 206, 86, 0.7)',
                                                            'rgba(83, 102, 203, 0.7)',
                                                            'rgba(199, 199, 199, 0.7)',
                                                            'rgba(60, 179, 113, 0.7)',
                                                            'rgba(200, 100, 100, 0.7)'
                                                        ],
                                                        borderColor: [
                                                            'rgba(75, 192, 192, 1)',
                                                            'rgba(153, 102, 255, 1)',
                                                            'rgba(255, 159, 64, 1)',
                                                            'rgba(255, 99, 132, 1)',
                                                            'rgba(54, 162, 235, 1)',
                                                            'rgba(255, 206, 86, 1)',
                                                            'rgba(83, 102, 203, 1)',
                                                            'rgba(199, 199, 199, 1)',
                                                            'rgba(60, 179, 113, 1)',
                                                            'rgba(200, 100, 100, 1)'
                                                        ],
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    indexAxis: 'y', // ¡Esta es la propiedad clave para barras horizontales!
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    scales: {
                                                        x: { // Eje X ahora representa la cantidad
                                                            beginAtZero: true,
                                                            title: {
                                                                display: true,
                                                                text: 'Cantidad de Licitaciones'
                                                            },
                                                            ticks: {
                                                                precision: 0 // Asegura que el conteo sea un número entero
                                                            },
                                                            // --- CAMBIO AQUÍ: Establecer el valor máximo del eje X ---
                                                            max: Math.max(...chartLicitacionData) +
                                                                1 // Añade 1 al valor máximo de los datos
                                                        },
                                                        y: { // Eje Y ahora representa los estados
                                                            title: {
                                                                display: true,
                                                                text: 'Estado de Licitación'
                                                            }
                                                        }
                                                    },
                                                    plugins: {
                                                        legend: {
                                                            display: false
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Licitaciones por Estado',
                                                            font: {
                                                                size: 14
                                                            }
                                                        },
                                                        datalabels: { // Mantener datalabels para mostrar el valor en cada barra
                                                            color: '#000', // Un color oscuro para que contraste con las barras
                                                            anchor: 'end', // Posición de la etiqueta (al final de la barra)
                                                            align: 'start', // Alineación del texto de la etiqueta
                                                            offset: 4, // Desplazamiento desde el borde de la barra
                                                            font: {
                                                                weight: 'bold',
                                                                size: 12
                                                            },
                                                            formatter: (value) => {
                                                                return value; // Simplemente muestra el valor numérico
                                                            }
                                                        }
                                                    }
                                                }
                                            });

                                            // --- Configuración para el gráfico de comprasEstadoChart (Dona) ---
                                            const chartLabelsCompras = @json($chartLabels);
                                            const chartDataCompras = @json($chartData);

                                            const ctxCompras = document.getElementById('comprasEstadoChart').getContext('2d');
                                            new Chart(ctxCompras, {
                                                type: 'bar', // Cambiamos el tipo a 'pie'
                                                data: {
                                                    labels: chartLabelsCompras,
                                                    datasets: [{
                                                        label: 'Número de Compras', // Este label aparecerá en el tooltip
                                                        data: chartDataCompras,
                                                        backgroundColor: [
                                                            'rgba(255, 99, 132, 0.7)', // Rojo
                                                            'rgba(54, 162, 235, 0.7)', // Azul
                                                            'rgba(255, 206, 86, 0.7)', // Amarillo
                                                            'rgba(75, 192, 192, 0.7)', // Verde azulado
                                                            'rgba(153, 102, 255, 0.7)', // Púrpura
                                                            'rgba(255, 159, 64, 0.7)', // Naranja
                                                            'rgba(199, 199, 199, 0.7)', // Gris
                                                            'rgba(83, 102, 203, 0.7)', // Azul oscuro
                                                            'rgba(255, 99, 255, 0.7)', // Rosa
                                                            'rgba(100, 200, 100, 0.7)', // Verde claro
                                                            'rgba(60, 179, 113, 0.7)' // Verde mar
                                                        ],
                                                        borderColor: [ // Bordes un poco más oscuros para contraste
                                                            'rgba(255, 99, 132, 1)',
                                                            'rgba(54, 162, 235, 1)',
                                                            'rgba(255, 206, 86, 1)',
                                                            'rgba(75, 192, 192, 1)',
                                                            'rgba(153, 102, 255, 1)',
                                                            'rgba(255, 159, 64, 1)',
                                                            'rgba(199, 199, 199, 1)',
                                                            'rgba(83, 102, 203, 1)',
                                                            'rgba(255, 99, 255, 1)',
                                                            'rgba(100, 200, 100, 1)',
                                                            'rgba(60, 179, 113, 1)'
                                                        ],
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    indexAxis: 'y', // ¡Esta es la propiedad clave para barras horizontales!


                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    plugins: {
                                                        legend: {
                                                            position: 'bottom', // Muestra la leyenda debajo del gráfico
                                                            labels: {
                                                                font: {
                                                                    size: 12
                                                                }
                                                            }
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Ordenes de Compras por Estado',
                                                            font: {
                                                                size: 14
                                                            }
                                                        },
                                                        // --- Configuración del plugin datalabels ---
                                                        datalabels: { // Mantener datalabels para mostrar el valor en cada barra
                                                            color: '#000', // Un color oscuro para que contraste con las barras
                                                            anchor: 'end', // Posición de la etiqueta (al final de la barra)
                                                            align: 'start', // Alineación del texto de la etiqueta
                                                            offset: 4, // Desplazamiento desde el borde de la barra
                                                            font: {
                                                                weight: 'bold',
                                                                size: 10
                                                            },
                                                            formatter: (value) => {
                                                                return value; // Simplemente muestra el valor numérico
                                                            }
                                                        }
                                                        // --- Fin de la configuración del plugin datalabels ---
                                                    }
                                                },
                                            });
                                        });

                                        // --- Gráfico de Proyectos por Departamento ---
                                        document.addEventListener('DOMContentLoaded', function() {

                                            const ctxProyectos = document.getElementById('proyectosPorDepartamentoChart');
                                            const chartProyectosLabels = @json($chartProyectosLabels);
                                            const chartProyectosData = @json($chartProyectosData);

                                            // Calcula el valor máximo para el eje Y: el valor más alto + 1
                                            const maxProyectos = Math.max(...chartProyectosData);
                                            const yAxisMaxProyectos = maxProyectos + 1; // Un punto más arriba

                                            new Chart(ctxProyectos, {
                                                type: 'bar',
                                                data: {
                                                    labels: chartProyectosLabels,
                                                    datasets: [{
                                                        label: 'Cantidad de Proyectos',
                                                        data: chartProyectosData,
                                                        backgroundColor: [
                                                            'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)',
                                                            'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)',
                                                            'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)',
                                                            'rgba(199, 199, 199, 0.6)'
                                                        ],
                                                        borderColor: [
                                                            'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)',
                                                            'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)',
                                                            'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)',
                                                            'rgba(199, 199, 199, 1)'
                                                        ],
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            max: yAxisMaxProyectos, // Establece el tope del eje Y
                                                            title: {
                                                                display: true,
                                                                text: 'Cantidad de Proyectos'
                                                            },
                                                            ticks: {
                                                                precision: 0
                                                            }
                                                        },
                                                        x: {
                                                            title: {
                                                                display: true,
                                                                text: 'Departamento'
                                                            }
                                                        }
                                                    },
                                                    plugins: {
                                                        legend: {
                                                            display: false
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Proyectos por Departamento'
                                                        },
                                                        datalabels: { // Configuración del plugin datalabels
                                                            anchor: 'end', // Posiciona la etiqueta al final de la barra
                                                            align: 'end', // Alinea la etiqueta al final de la barra
                                                            formatter: function(value) { // Formatea el valor si es necesario
                                                                return value;
                                                            },
                                                            color: '#000', // Color del texto
                                                        }
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                    <br>
                                    <br>
                                    {{-- NUEVO: Detalles por Departamento (filtrado por el select) --}}
                                    <div class="container-md-12" style="margin-left: 40px">
                                        <h1 style="font-size: 20px; color:rgb(12, 13, 14); margin-bottom: 0;">5.- Resumen
                                            por Departamentos
                                        </h1>
                                        <br>

                                        {{-- Verifica si hay datos de departamentos para mostrar --}}
                                        @if (count($datos_departamentos) > 0)
                                            {{-- NUEVA FILA DE ENCABEZADOS DE COLUMNA --}}
                                            <div class="d-flex flex-wrap justify-content-start"
                                                style="font-weight: bold; background-color: #e9ecef; padding: 5px 0; border-bottom: 2px solid #ced4da;">
                                                <div class="col-md-3 col-sm-6 col-xs-12 p-1 text-center">Departamento</div>
                                                <div class="col-md-2 col-sm-6 col-xs-12 p-1 text-center">Presupuesto</div>
                                                <div class="col-md-2 col-sm-6 col-xs-12 p-1 text-center">Comprometido</div>
                                                <div class="col-md-2 col-sm-6 col-xs-12 p-1 text-center">Porcentaje</div>
                                                <div class="col-md-1 col-sm-6 col-xs-12 p-1 text-center">Semaforización
                                                </div>
                                                <div class="col-md-2 col-sm-6 col-xs-12 p-1 text-center">Estado/KPI
                                                </div>
                                            </div>

                                            {{-- FIN DE LA FILA DE ENCABEZADOS --}}

                                            {{-- Contenedor flexible para alinear las cajas en filas de 4 --}}
                                            <div class="d-flex flex-wrap justify-content-start">
                                                @foreach ($datos_departamentos as $dato)
                                                    {{-- RECALCULAMOS LAS VARIABLES DENTRO DEL BUCLE --}}
                                                    @php
                                                        $porcentaje2 = $dato['porcentaje'];
                                                        $imagenSema =
                                                            $porcentaje2 > 75
                                                                ? 'semaforo_verde.jpg'
                                                                : ($porcentaje2 >= 60 && $porcentaje2 <= 74
                                                                    ? 'semaforo_amarillo.jpg'
                                                                    : 'semaforo_rojo.jpg');
                                                        $estadoTexto2 =
                                                            $porcentaje2 > 75
                                                                ? 'Ejecución Aceptable'
                                                                : ($porcentaje2 >= 60 && $porcentaje2 <= 74
                                                                    ? 'Ejecución Media'
                                                                    : 'Baja Ejecución');
                                                    @endphp
                                                    {{-- --------------------------------------------- --}}

                                                    <div class="col-md-3 col-sm-6 col-xs-12 p-1"> {{-- p-1 para añadir un pequeño padding entre columnas --}}
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-red"><i class="bi bi-house-door"
                                                                    style="font-size: 0.9em;"></i></span>
                                                            <div class="info-box-content">
                                                                <h5 class="info-box-text" style="font-size: 13px;">
                                                                    <strong>
                                                                        {{ $dato['departamento']->detalle }}</strong>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-6 col-xs-12 p-1">
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-primary"> <i
                                                                    class="bi bi-currency-dollar"
                                                                    style="font-size: 0.8em;"></i></span>
                                                            <div class="info-box-content">

                                                                <h6 class="info-box-number" style="font-size: 16px;">$
                                                                    {{ number_format($dato['presupuesto'], 0, ',', '.') }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-6 col-xs-12 p-1">
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-green"><i
                                                                    class="bi bi-plus-slash-minus"
                                                                    style="font-size: 0.8em;"></i></span>
                                                            <div class="info-box-content">

                                                                <h6 class="info-box-number" style="font-size: 16px;">$
                                                                    {{ number_format($dato['comprometido'], 0, ',', '.') }}
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-sm-6 col-xs-12 p-1">
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-warning"><i
                                                                    class="bi bi-percent"
                                                                    style="font-size: 0.8em;"></i></span>
                                                            <div class="info-box-content" style="font-size: 16px;">

                                                                <h6 class="info-box-number">{{ $dato['porcentaje'] }} %
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- Aquí agrupamos Semáforo y Métrica en el espacio restante (col-md-1) --}}

                                                    <div class="col-md-1 col-sm-6 col-xs-12 p-1">
                                                        <div class="info-box">
                                                            <div class="inner">
                                                                @php
                                                                    // ... Lógica PHP (se mantiene) ...
                                                                    $porcentaje2 = $dato['porcentaje'];
                                                                    $imagenSema =
                                                                        $porcentaje2 > 75
                                                                            ? 'semaforo_verde.jpg'
                                                                            : ($porcentaje2 >= 60 && $porcentaje2 <= 74
                                                                                ? 'semaforo_amarillo.jpg'
                                                                                : 'semaforo_rojo.jpg');
                                                                    $estadoTexto2 =
                                                                        $porcentaje2 > 75
                                                                            ? 'Ejecución Aceptable'
                                                                            : ($porcentaje2 >= 60 && $porcentaje2 <= 74
                                                                                ? 'Ejecución Media'
                                                                                : 'Baja Ejecución');

                                                                    $colorFondoTarjeta = 'bg-light';
                                                                    $textoColorClase = 'text-dark';
                                                                    $colorFooter = 'bg-secondary';
                                                                @endphp

                                                                <div class="ms-auto">
                                                                    <div class="text-center">
                                                                        {{-- Usamos la clase de Bootstrap text-center en un div contenedor --}}
                                                                        <center>
                                                                            <h6><strong>Indicador</strong></h6>
                                                                            @if ($imagenSema)
                                                                                <img src="{{ 'images/' . $imagenSema }}"
                                                                                    alt="Semáforo de Ejecución"
                                                                                    class="img-fluid"
                                                                                    style="height: 60px; width: 60px; display: block; margin: 0 auto;">
                                                                                {{-- Se añade display: block y margin: 0 auto para centrado manual --}}
                                                                            @endif
                                                                        </center>
                                                                    </div>
                                                                    <br>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>


                                                    <div class="col-md-2 col-sm-6 col-xs-12 p-1">


                                                        <div class="info-box">
                                                            <div class="inner">
                                                                <div class="inner text-end">

                                                                    @php
                                                                        // ... Lógica PHP (se mantiene) ...
                                                                        $porcentaje2 = $dato['porcentaje'];
                                                                        $imagenSema =
                                                                            $porcentaje2 > 75
                                                                                ? 'semaforo_verde.jpg'
                                                                                : ($porcentaje2 >= 60 &&
                                                                                $porcentaje2 <= 74
                                                                                    ? 'semaforo_amarillo.jpg'
                                                                                    : 'semaforo_rojo.jpg');
                                                                        $estadoTexto2 =
                                                                            $porcentaje2 > 75
                                                                                ? 'Ejecución Aceptable'
                                                                                : ($porcentaje2 >= 60 &&
                                                                                $porcentaje2 <= 74
                                                                                    ? 'Ejecución Media'
                                                                                    : 'Baja Ejecución');

                                                                        $colorFondoTarjeta = 'bg-light';
                                                                        $textoColorClase = 'text-dark';
                                                                        $colorFooter = 'bg-secondary';
                                                                    @endphp

                                                                    <div class="inner text-end">


                                                                        <h6><strong>{{ $estadoTexto2 }}</strong></h6>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>


                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No hay datos de departamentos disponibles para mostrar con los filtros
                                                actuales.</p>
                                        @endif
                                    </div>
                                    <br>
                                    <hr>
        </div>

        {{-- FIN: Detalles por Departamento --}}
    </div>
@endsection
