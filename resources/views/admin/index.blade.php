@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-md-12">
      <div class="jumbotron">
          <h1>Sistema para la optimización de la Gestión del Plan Anual de Compras</h1>
          <p class="lead">Este sistema ha sido diseñado para otorgar a la Dirección de Logística, una
             visión clara y detallada del proceso del Plan Anual de Compras, facilitando la toma de decisiones
             estratégicas y el seguimiento eficiente de cada etapa.</p>
          <hr class="lead">
          <p class="lead">Aquí podrá encontrar información consolidada sobre las necesidades de compra de cada departamento, el estado de las solicitudes, los presupuestos asignados y el avance general del plan.</p>
         {{--
          <p class="lead">
              <a class="btn btn-primary btn-lg" href="" role="button">Ir al Dashboard Principal</a>
          </p>
          --}}
      </div>
  </div>
</div>


<div class="row">
  <div class="col-md-12">
      <div class="card">
          <div class="card-header">
              <h2>Este sistema permitirá:</h2>
          </div>
          <div class="card-body">
            <div class="div col-md-12">
              <div class="row">
                <div class="col-md-6">
                    <ul>
                        <li class="nav-item" style="font-size: 18px; color:rgb(13, 13, 14); margin-bottom: 0;">Visualizar un resumen ejecutivo del Plan Anual de Compras.</li>
                        <li class="nav-item" style="font-size: 18px; color:rgb(13, 13, 14); margin-bottom: 0;">Acceder a detalles específicos de cada solicitud y orden de compra.</li>
                        <li class="nav-item" style="font-size: 18px; color:rgb(13, 13, 14); margin-bottom: 0;">Monitorear el avance del presupuesto asignado a cada área.</li>
                        <li class="nav-item" style="font-size: 18px; color:rgb(13, 13, 14); margin-bottom: 0;">Generar informes para el análisis y la toma de decisiones.</li>
                    </ul>
                </div>
            
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm" style="background-color: transparent;">
                        <img src="images/dashboard2.jpg" class="card-img-top img-fluid zoom-on-hover"
                             alt="Descripción de la imagen"
                             style="opacity: 0.7; border: 2px solid #c8cdd1; border-radius: 20px; height: 250px;">
                    </div>
                </div>
            </div>

           
            </div>
          </div>
          </div>
      </div>
  </div>



  
@endsection