<?php

require '../sistema/includes/funcionesUsuarios.php';
require '../sistema/includes/funcionesReferencias.php';


$serviciosReferencias       = new ServiciosReferencias();
$serviciosUsuario = new ServiciosUsuarios();


$token = $_GET['token'];

$codActivacion = $serviciosUsuario->traerActivacionusuariosPorTokenFechas($token);

$error = 0;
if (mysqli_num_rows($codActivacion) > 0) {
    $idUsuario = $serviciosUsuario->mysqli_result($codActivacion,0,'refusuarios');
    
    $resUsuario = $serviciosUsuario->traerUsuarioId($idUsuario);
    
    if ($serviciosUsuario->mysqli_result($resUsuario,0,7) == 'Si') {
        $error = 2;
    } else {
    
        
        $activar = $serviciosUsuario->activarUsuario($idUsuario);

        $resUsuario = $serviciosUsuario->traerUsuarioId($idUsuario);

        $email = $serviciosUsuario->mysqli_result($resUsuario,0,'email');

        $destinatario = $email;
        $asunto = "Cuenta Activada Correctamente";
        $cuerpo = "<h3>Gracias por registrarse en Crovan Kegs.</h3><br>
                    <p>Ya puede comenzar a comprar ingresando con su email y contraseña, visite nuestros productos <a href='http://www.crovankegs.com/tienda/'>AQUI</a></p>";

        $serviciosUsuario->modificarActivacionusuariosConcretada($token);
        $serviciosUsuario->enviarEmail($destinatario,$asunto,$cuerpo);
    }
} else {
	$error = 1;
}

 
 
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/fonts/material-icons.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
   <link rel="stylesheet" type="text/css" href="../assets/css/animate.css">
    <link href="../assets/css/hover.css" rel="stylesheet">  
    <link href="../assets/css/imagehover.min.css" rel="stylesheet">    

    <style type="text/css">
    .tituloA {
      color:#FFA917; 
      border-bottom: 2px solid #FFA917;

    }

    .tituloA:after {
      display: inline-block;
      width: 100%;

    }

    .scale__container--js {
      text-align: center;
      margin-bottom: 35px;
    }
    .scale--js {
      display: inline-block;
      transform-origin: 50% 0;
      -webkit-font-smoothing: antialiased;
      transform: translate3d( 0, 0, 0);
    }
    .btn-crovan {
        color: #fff;
        background-color: #ff8601;
        border-top: 1px solid #fe8c0e;
        border-left: 1px solid #f48102;
        border-bottom: 1px solid #f48102;
        border-right: 1px solid #fe8c0e;
        padding: 10px 40px;
        font-weight: 600;
    }
    </style>
</head>



<body>
<div class="row" id="barraup">
<nav class="navbar navbar-default">
<div class="col-xs-3">
    <div class="col-xs-12">
        <img src="../assets/img/logonav.png" class="img-responsive pull-right imgnavbar">
    </div>
</div>
<div class="col-xs-4">
    
</div>
<div class="col-xs-5">
    <div class="col-xs-12 mp-navbar">¿YA ESTAS REGISTRADO? <a href="../login/" style="color:#00F;">INGRESA ACA</a> <img src="../assets/img/iniciarsesion.png"></div>
</div>
</nav>
</div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified">
                <div class="col-xs-1">
                </div>
                <div class="col-xs-11">
                	<?php
                		if ($error == 0) {
                	?>
                    <li class="dropdown"><h3 style="margin-top:-5px;">CUENTA CREADA CORRECTAMENTE</h3></a></li> 
                    <?php
                		} else {
                	?>
                	<li class="dropdown"><h3 style="margin-top:-5px;">SURGIO UN ERROR VUELVA A INTENTARLO</h3></a></li> 
                	<?php
                		}
                	?>          
                </div>                         
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3">
            
        </div>
        <div class="col-xs-6" style="margin-top:20px;">

            <form>
            	<?php
            		if ($error == 0) {
            	?>
                <div class="scale__container--js">
                    <h4 class="scale--js tituloA">TE DAMOS LA BIENVENIDA A CROVAN KEGS</h4>
                </div>
              	<div class="col-xs-6" style="margin-top:20px;">
              		<button type="button" class="btn-crovan ingresar">INGRESA</button>
              	</div>

              	<div class="col-xs-6" style="margin-top:20px;">
              		<button type="button" class="btn-crovan tienda">TIENDA</button>
              	</div>
              	<?php
            		} 
                
                    if ($error == 1) {
            	?>
            	<div class="scale__container--js">
                    <h4 class="scale--js tituloA">DEBE VOLVER A ACTIVAR SU CUENTA</h4>
                </div>

                <div class="col-xs-12" style="margin-top:20px;">
              		<p>Por favor vuelva a revisar su casilla de correo con las instrucciones</p>
              	</div>
            	<?php
            		}
            	?> 
            	
            	
            	<?php
                
                    if ($error == 2) {
            	?>
            	<div class="scale__container--js">
                    <h4 class="scale--js tituloA">SU CUENTA YA FUE ACTIVADA</h4>
                </div>

                <div class="col-xs-12" style="margin-top:20px;">
              		<p>Puede loguearse a CROVAN KEGS</p>
              	</div>
              	
              	<div class="col-xs-6" style="margin-top:20px;">
              		<button type="button" class="btn-crovan ingresar">INGRESA</button>
              	</div>

              	<div class="col-xs-6" style="margin-top:20px;">
              		<button type="button" class="btn-crovan tienda">TIENDA</button>
              	</div>
            	<?php
            		}
            	?> 
              
            </form>
        </div>
        <div class="col-xs-3">
            
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 ">
            <p class="text-center text-muted">Crovan Kegs | (+ 54 9) 11 7017 3422 | info@crovankegs.com</p>
        </div>
    </div>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.easing.min.js"></script>
    <script src="../assets/js/scrolling-nav.js"></script>
    <script src="../assets/js/wow.js"></script>
    <script>
     new WOW().init();

     function scaleHeader() {
          var scalable = document.querySelectorAll('.scale--js');
          var margin = 10;
          for (var i = 0; i < scalable.length; i++) {
            var scalableContainer = scalable[i].parentNode;
            scalable[i].style.transform = 'scale(1)';
            var scalableContainerWidth = scalableContainer.offsetWidth - margin;
            var scalableWidth = scalable[i].offsetWidth;
            scalable[i].style.transform = 'scale(' + scalableContainerWidth / scalableWidth + ')';
            scalableContainer.style.height = scalable[i].getBoundingClientRect().height + 'px';
          }
        } 


        // Debounce by David Walsch
        // https://davidwalsh.name/javascript-debounce-function

        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        var myScaleFunction = debounce(function() {
            scaleHeader();
        }, 250);

        myScaleFunction();

        window.addEventListener('resize', myScaleFunction);
    </script>   

    <script type="text/javascript">
        $(document).ready(function(){
          $('.ingresar').click(function() {
          	url = "../login/";
			$(location).attr('href',url);
          });

          $('.tienda').click(function() {
          	url = "../productos/";
			$(location).attr('href',url);
          });

        });
    </script>      
</body>

</html>