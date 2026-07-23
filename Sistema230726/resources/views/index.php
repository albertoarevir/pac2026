<script language="JavaScript" type="text/JavaScript">
    sessionStorage.clear(); 
</script>


<!DOCTYPE html>
<html lang="es">
<head>
	
	<script SRC="assets/autenticaTic/js/jquery-3.3.1.min.js"></script>
	<script SRC="assets/autenticaTic/js/cookies.min.js"></script>
	
	<title>KÁRDEX VEHICULAR TÁCTICO</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="assets/images/favicon.ico">
	<meta name="theme-color" content="#3c763d;">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">	
		
</head>
<script language="JavaScript" type="text/JavaScript">
<!--
//********************************************   Submit
function accion_submit(){
	
	//alert("accion_storage: "+sessionStorage.getItem("timePass"));
	
	document.form_login.username.value = document.form_login.rut.value;
	document.form_login.action = 'index.php';
	document.form_login.submit();
}
//********************************************   Tiempo caducidad de password	
function timeExpirationPassword(){
var color ="white";
var tiempoPass = "";    
  		//************ INICIO AUTENTIFICATIC BUSCAR USUARIO
		 $.ajax({
         url: 'http://autentificaticapi.carabineros.cl/api/auth/user',
         type:'get',
         headers: {
         		'Authorization' :'Bearer '+docCookies.getItem('access_token'),
         		'Accept': 'application/json'
         },
         success: function(response){	           
         	  //console.log("tiempo:" + response.success.user.password_expiration); 
         	  if (response.success.user.password_expiration<=5){
         	  	color ="red";
         	  }
         	  if ((response.success.user.password_expiration>5) && (response.success.user.password_expiration<=15)){
         	  	color ="yellow";
         	  }	
         	  tiempoPass = '<div style="color: '+color+';">SU PASSWORD CADUCARA EN '+ response.success.user.password_expiration + " DIAS </div>";  
         	  sessionStorage.setItem("timePass", tiempoPass);
         	  accion_submit();
         },
         error: "no autorizado"        
	   });	 
	   
	     	     
}		
//********************************************   Valida ingreso al Sistema
function iniciar_sesion(){
	
   var rut_usuario= document.getElementById("rut").value.trim();
   var contrasena = document.getElementById("password").value;
  
   //************ INICIO AUTENTIFICATIC	
	   var parametros = {
	           "rut" : document.getElementById("rut").value,
	           "password" : document.getElementById("password").value
 		  
		  	
	   };
	   		   
	   $.ajax({
	           data: parametros,
	           url: 'http://autentificaticapi.carabineros.cl/api/auth/login',
	           type:'post',
	           success: function(response){
	           	           console.log(response.success.access_token)
				 

	                           docCookies.setItem("access_token", response.success.access_token);
	                           sessionStorage.setItem("accessToken", response.success.access_token);
	                           accion_submit(); 
	           },
	           error: function(XMLHttpRequest, textStatus, errorThrown){
	                           console.log(XMLHttpRequest.responseJSON.errors.rut)
	                           XMLHttpRequest.responseJSON.errors.rut != undefined?alert(XMLHttpRequest.responseJSON.errors.rut):''
	                           XMLHttpRequest.responseJSON.errors.password != undefined?alert(XMLHttpRequest.responseJSON.errors.password):''
	                           //alert(XMLHttpRequest.responseJSON.errors.rut)	   
	           }
	   });
	   //************ FIN AUTENTIFICATIC   
}
//********************************************   Valida ingreso al Sistema
function validaUsuario() {
	var contrasena = document.form_login.password.value;
	
	contrasena = contrasena.replace("'", "");
	if (document.form_login.rut.value=='' || document.form_login.password.value==''){
		alert("Debe ingresar Rut y Contraseña correctamente");
		if (document.form_login.rut.value==''){
			document.form_login.rut.focus();
		}else{
			document.form_login.password.focus();
		}
	}else{
		document.form_login.password.value = contrasena;
		document.form_login.accion.value = 'validaIngreso';
		document.form_login.action       = '../output/login.htm';
		document.form_login.submit();
	}
}
//********************************************  despkliega alert con texto pre seleccionado
function mensaje_alert(texto) {
	alert(texto);
}
//********************************************
function mensaje(){
	var theURL;
	var winName;
	var features;
	
	theURL  ='mensaje.php';
	winName ='mensaje';
	features = 'status=yes, scrollbars=yes, width=585, height=446';	
	window.open(theURL, winName, features);
}
//********************************************
function mensaje2(){
	var theURL;
	var winName;
	var features;
	
	theURL  ='mensaje2.html';
	winName ='mensaje';
	features = 'status=yes, scrollbars=yes, width=585, height=430';	
	window.open(theURL, winName, features);
}
-->	
</script>			
		
<body class="bg-login">	

	<div class="margintop-login">
  
	    <div class="carabineros">
		    <div  style="line-height: 40px; width: 70%; float: right; text-align: left;">
		        <h1 class="title-name-app">K&Aacute;RDEX VEHICULAR T&Aacute;CTICO</h1>
		        <h5 class="subtitle-name-app">DIRECCI&Oacute;N DE LOG&Iacute;STICA</h5>
		    </div>
		    
		    <div  style="width: 30%">
		        <img src="assets/images/carabineros.png" width="70" height="auto">
		    </div>
		    	
		    <div>  
		    	<!--          
                <p style="background-color: yellow;"><strong>¡IMPORTANTE!</strong><br>
                A partir del día <strong>18.08.2021</strong>, la clave de <strong>AUTENTIFICATIC</strong>, se bloqueará por un 1 minuto, 
                si se ingresa 3 veces erróneamente. Posteriormente si la contraseña se vuelve a ingresar 
                4 veces erróneamente, Ud. deberá realizar la recuperación de la misma.</p> 
                -->           
            </div> 	
	    </div>
		<div style="clear:both"></div>
	    <div class="login-page"  class="background-black-06">
	   		<div class="autentificatic-sello text-center">
	   			<a href="http://autentificaticapi.carabineros.cl/assets/documents/procedimiento_de_seguridad.pdf" target="_blank">
	   				<img src="http://autentificaticapi.carabineros.cl/assets/images/autentificatic.png" width="280" height="auto" style="padding-top: 6px;">
	   			</a>
	   		</div>
	   			<div  class="text-center">
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
		          		<input  type="button" class="btn-login" name="enviar" value="Iniciar Sesion"  id="enviar" onClick="iniciar_sesion();" />
				  	</div>
				  
				  	<div class="text-center">
				  		<p style="margin-bottom: 0px;"><strong>K&aacute;rdex Vehicular; V1.0.0 - 22</strong></p>
				  	</div>
				</form>		       
		   	</div>
		</div>

	  	
					<?php 
					if (isset($logout)){ 
						if ($logout=="1"){  
							echo "<script language='javascript'>mensaje_alert('Usuario no existe en el sistema...');</script>";
						} 
						if ($logout=="2"){ 
							echo "<script language='javascript'>mensaje_alert('Clave esta siendo ocupada...');</script>";
						} 
						if ($logout=="3"){  
							echo "<script language='javascript'>mensaje_alert('Las credenciales no son validas');</script>";
						}
						if ($logout=="4"){ 
							echo "<script language='javascript'>mensaje_alert('Usuario Caducado...');</script>";
						}
						$logout='';		
					} 
					?>
		   </div>  
		    
		</div>	    

		<div class="logos-bottom">
			<img src="http://intranetv2.carabineros.cl/DescargasTIC/aniversario.png" width="70" height="auto" style="float: left; padding-top: 20px;">
			<img src="http://intranetv2.carabineros.cl/DescargasTIC/sello-TIC.png" width="70" height="auto" style="float: right;">
		</div>

		<div class="text-center slogan"><img src="http://intranetv2.carabineros.cl/DescargasTIC/slogan.png" style="padding-top: 20px;"></div>

	</div>

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

	<script type="text/javascript" src="assets/js/main.js"></script>	
</body>
</html>
		
