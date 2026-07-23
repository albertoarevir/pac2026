@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4" style="border: none; border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #1a2740 0%, #2c3e60 100%); box-shadow: 0 6px 18px rgba(0,0,0,0.15);">
            <div class="card-body" style="padding: 2.5rem;">
                <span class="badge" style="background-color: rgba(255,255,255,0.15); color: #cfe2ff; font-size: 13px; letter-spacing: 0.5px; padding: 6px 14px; border-radius: 20px;">
                    <i class="fas fa-shield-alt mr-1"></i> DIRECCIÓN DE LOGÍSTICA · CARABINEROS DE CHILE
                </span>
                <h1 class="mt-3" style="color: #ffffff; font-weight: 700; font-size: 2.1rem;">
                    Sistema para la Gestión del Plan Anual de Compras
                </h1>
                <br>
                <p class="lead" style="color: #f8f9fa; max-width: 1980px; font-size: 20px;">
                    Una plataforma tecnológica centralizada orientada a la planificación, monitoreo y control estratégico de las adquisiciones
                    institucionales, en estricto apego al marco normativo de la Ley N° 19.886 de Bases sobre Contratos Administrativos de
                    Suministro y Prestación de Servicios.<br><br>
                    Este sistema garantiza la eficiencia en el uso de los recursos públicos, proporcionando una herramienta integral para
                    asegurar la trazabilidad, transparencia y una oportuna toma de decisiones en el ciclo completo del Plan Anual de Compras.
                </p>
                <hr style="border-color: rgba(255,255,255,0.15); max-width: 1980px;">
                <p class="lead" style="color: #f8f9fa; max-width: 1980px; font-size: 20px; margin-bottom: 0;">
                    Consulte información consolidada sobre las necesidades de compra de cada departamento, el estado
                    de las solicitudes, los presupuestos asignados y el avance general del plan, todo en un mismo
                    lugar.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-header" style="background-color: #f8f9fb; border-bottom: 1px solid #e9ecef; border-radius: 16px 16px 0 0;">
                <h1 style="font-size: 2.3rem; font-weight: 600; margin-bottom: 0; color: #1a2740;">
                    <i class="fas fa-layer-group mr-2" style="color:#2c3e60;"></i>Este sistema permitirá:
                </h1>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check-circle mt-1 mr-3" style="color:#2e7d32; font-size: 20px;"></i>
                                <span style="font-size: 20px; color:#2c333d;">Visualizar un resumen ejecutivo del Plan Anual de Compras.</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check-circle mt-1 mr-3" style="color:#2e7d32; font-size: 20px;"></i>
                                <span style="font-size: 20px; color:#2c333d;">Acceder a detalles específicos de cada solicitud y orden de compra.</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check-circle mt-1 mr-3" style="color:#2e7d32; font-size: 20px;"></i>
                                <span style="font-size: 20px; color:#2c333d;">Monitorear el avance del presupuesto asignado a cada área.</span>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="fas fa-check-circle mt-1 mr-3" style="color:#2e7d32; font-size: 20px;"></i>
                                <span style="font-size: 20px; color:#2c333d;">Generar informes para el análisis y la toma de decisiones.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="pac-hero-image-frame">
                            <img src="{{ asset('images/dashboard2.jpg') }}"
                                 class="img-fluid"
                                 alt="Vista del sistema de gestión del Plan Anual de Compras">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-3 text-right">
        <br>
        <br>
        <br>
        <br>
        <button type="button" class="btn btn-lg pac-security-btn" data-toggle="modal" data-target="#modalCiberseguridad">
            <i class="fas fa-shield-alt mr-2"></i>Buenas Prácticas en la Seguridad de la Información
        </button>
    </div>
</div>

<div class="modal fade" id="modalCiberseguridad" tabindex="-1" role="dialog" aria-labelledby="modalCiberseguridadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a2740 0%, #2c3e60 100%);">
                <h5 class="modal-title text-white" id="modalCiberseguridadLabel">
                    <i class="fas fa-lock mr-2"></i>Ciberseguridad
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-size: 20px; color: #2c333d;">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-key" style="font-size: 20px; color: #b71c1c; flex-shrink: 0; margin-right: 30px;"></i>
                        <strong style="flex-shrink: 0; min-width: 40px;">1.</strong>
                        <span style="text-align: justify;">Cada Usuario es responsable del uso de su clave de acceso a la plataforma
                            pacdilocar.des.carabineros.cl, estando prohibido compartir cuentas y claves. Recordamos utilizar una clave
                            segura con caracteres, números y mayúsculas.</span>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="fas fa-clipboard-check" style="font-size: 22px; color: #b71c1c; flex-shrink: 0; margin-right: 30px;"></i>
                        <strong style="flex-shrink: 0; min-width: 40px;">2.</strong>
                        <span style="text-align: justify;">Cada Usuario es responsable de la veracidad de la información que ingresa al sistema.</span>
                    </li>
                    <li class="d-flex align-items-start mb-0">
                        <i class="fas fa-exclamation-triangle" style="font-size: 20px; color: #b71c1c; flex-shrink: 0; margin-right: 25px;"></i>
                        <strong style="flex-shrink: 0; min-width: 40px;">3.</strong>
                        <span style="text-align: justify;">Nunca se pedirá claves de acceso por correo. Si recibe un mail sospechoso,
                            repórtalo al OSI de su Alta Repartición.</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .pac-hero-image-frame {
        position: relative;
        border-radius: 20px;
        padding: 10px;
        background: linear-gradient(135deg, #eef1f6 0%, #dde3ec 100%);
        box-shadow: 0 8px 24px rgba(26, 39, 64, 0.12);
    }

    .pac-hero-image-frame img {
        display: block;
        width: 100%;
        height: 260px;
        object-fit: cover;
        border-radius: 14px;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .pac-hero-image-frame:hover img {
        transform: scale(1.03);
        box-shadow: 0 10px 22px rgba(26, 39, 64, 0.25);
    }

    .pac-security-btn {
        background: linear-gradient(135deg, #a14646 0%, #b71c1c 100%);
        color: #ffffff;
        font-weight: 600;
        font-size: 16px;
        padding: 12px 28px;
        border: none;
        border-radius: 30px;
        box-shadow: 0 6px 16px rgba(183, 28, 28, 0.35);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .pac-security-btn:hover,
    .pac-security-btn:focus {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(183, 28, 28, 0.45);
    }
</style>
@endpush

@endsection
