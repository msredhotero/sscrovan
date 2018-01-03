<?php

require '../../includes/funcionesUsuarios.php';
require '../../includes/funcionesServicios.php';


session_start();

$serviciosServicios = new ServiciosServicios();
$serviciosUsuario = new ServiciosUsuarios();


$token = $_GET['token'];

$idcliente = $serviciosUsuario->traerActivacionusuariosPorTokenFechas($token);

if ((integer)$idcliente > 0) {
	$datosLogin = $serviciosUsuario->traerUsuarioId($idcliente);
	$serviciosUsuario->activarUsuario($idcliente);
	
	$email = mysql_result($datosLogin,0,'email');
	$serviciosUsuario->loginUsuario($email,mysql_result($datosLogin,0,'password'));	
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
                    <li class="dropdown"><h3 style="margin-top:-5px;">REGISTRATE EN CROVAN KEGS</h3></a></li>                
                </div>                         
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3">
            
        </div>
        <div class="col-xs-6" style="margin-top:20px;">

            <form>
                <div class="scale__container--js">
                    <h4 class="scale--js tituloA">COMPLETA EL FORMULARIO CON TUS DATOS Y HACE TUS COMPRAS</h4>
                </div>
              <div class="form-group">
                
                <input type="email" class="form-control" id="txtEmail" aria-describedby="emailHelp" placeholder="E-MAIL">
              </div>
              <div class="form-group">
                
                <input type="text" class="form-control" id="txtPassword" aria-describedby="emailHelp" placeholder="CONTRASEÑA">
              </div>

              <div class="form-group">
                
                <input type="text" class="form-control" id="txtPassword2" aria-describedby="emailHelp" placeholder="CONTRASEÑA DE NUEVO">
              </div>

              <div class="form-group">
                
                <input type="text" class="form-control" id="txtNombre" aria-describedby="emailHelp" placeholder="NOMBRE">
              </div>

              <div class="form-group">
                
                <input type="text" class="form-control" id="txtApellido" aria-describedby="emailHelp" placeholder="APELLIDO">
              </div>


                <div class="form-group">
                
                <input type="text" class="form-control" id="txtTelefono" aria-describedby="emailHelp" placeholder="TELEFONO">
              </div>
              <div class="form-group" style="padding:20px; background-color:#f5f5f5; border:1px solid #ececec;">
                <label for="exampleInputPassword1">Acepta los terminos y condiciones</label>
                <input type="checkbox" class="form-check-input" style="margin: 0px 25px 0px 7px;">
                <div class="pull-right" style="margin-top:-10px; margin-bottom:15px;">
                <button type="button" class="btn-crovan registrar">REGISTRATE</button>
                </div>
              </div>
              
              
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
          
        });
    </script>      
</body>

</html>