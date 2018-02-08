<?php


session_start();

if (!isset($_SESSION['id_crovan']))
{
	$usuario = '';
} else {
    $usuario = $_SESSION['nombre_crovan'];
}


include ('../sistema/includes/funcionesUsuarios.php');
include ('../sistema/includes/funciones.php');
include ('../sistema/includes/funcionesHTML.php');
include ('../sistema/includes/funcionesReferencias.php');
   
$serviciosUsuarios          = new ServiciosUsuarios();
$serviciosFunciones         = new Servicios();
$serviciosHTML              = new ServiciosHTML();
$serviciosReferencias       = new ServiciosReferencias();


//  tomo el estado de la compra   ///
$estado = 1;

$estadoCompra = 0;

////////       EJECUTO TODO LO DE MERCADOPAGO  ////////



////////            FIN MERCADOPAGO            ////////

////////         RECIBO ESTADO DE LA COMPRA    ////////

////////             FIN ESTADO DE LA COMRPA   ////////

$totalGral = 0;

if (isset($_SESSION['idProducto_carrito_crovan'])) {
    switch ($estado) {

        case 1:
            ////////                TODO "OK"                          ////////
            $error = "Su Compra se registro correctamente";
            $ico   = "glyphicon glyphicon-ok-sign";

            //*********   verifico por ultima vez el stock    *********************//
            $resVerificacion = $serviciosReferencias->verificarCarritoProductosStock($_SESSION['idProducto_carrito_crovan'], $_SESSION['cantidad_carrito_crovan']);

            if ($resVerificacion == 1) {
                header('../carrito/corregir.php');
            }
            //********              fin                         *******************//
                //----- INGRESO LA COMPRA  -----//
                //-- parametros --//

                $numero         = $serviciosReferencias->generarNroVenta();
                $reftipopago    = 3; //me lo da la api de mercadopago "payment_type"
                $fecha          = date('Y-m-d H:i:s');
                $total          = 0;
                $usuario        = $usuario;
                $cancelado      = 0;
                $refusuarios    = $_SESSION['id_crovan'];
                $descuento      = 0;
                $refestados     = 7; //me lo da la api de mercadopago "status"
                $idmercadopago  = ''; //token o algo que me devuelva

                //--  fin parametros -----///

                $resVenta = $serviciosReferencias->insertarVentas($reftipopago,$numero,$fecha,$total,$usuario,$cancelado,$refusuarios,$descuento,$refestados,$idmercadopago);

                //-- fin compra ------///////

                //---   inserto los detalles ----///
                $i=0;
                foreach ($_SESSION['idProducto_carrito_crovan'] as $row) {
                    $cantidad       = $_SESSION['cantidad_carrito_crovan'][$i];
                    $resProducto    = $serviciosReferencias->traerProductosPorIdWeb($row);
                    $nombre         = $serviciosReferencias->mysqli_result($resProducto,0,'nombre');
                    $precio         = $serviciosReferencias->mysqli_result($resProducto,0,'precioventa');
                    $costo          = $serviciosReferencias->mysqli_result($resProducto,0,'preciocosto');
                    $total          = $serviciosReferencias->mysqli_result($resProducto,0,'precioventa') * $_SESSION['cantidad_carrito_crovan'][$i];

                    $resDetalle = $serviciosReferencias->insertarDetalleventas($resVenta,$row,$cantidad,$costo,$precio,$total,$nombre);

                    $serviciosReferencias->descontarStock($row, $cantidad);

                    $totalGral += $total;

                }

                $serviciosReferencias->modificarVentasSoloTotal($resVenta,$totalGral);

                //--- borro todo el carrito menos el login -----//
                unset($_SESSION['idProducto_carrito_crovan']);
                unset($_SESSION['cantidad_carrito_crovan']);
                unset($_SESSION['precio_carrito_crovan']);
                unset($_SESSION['idUsuario_carrito_crovan']);

                //---  fin borrar carrito -----//////////////////

                //---    fin insertar los detalles ///


                //****    SI PASO ALGO BORRO TODO Y DEVUELVO EL STOCK ************/////////


                //****            FIN DEL ROLLBACK                    ************/////////

                //****   despues de confirmar todo verifico el nuevo estado *****//////////
                $estadoCompra = 1;



            ////////            FIN                                     ///////

        break;
        case 2:    
            ////////                TODO "MAL"                          ////////
            $error = "Su Compra no pudo realizarce, vuelva a intentarlo";
            $ico   = "glyphicon glyphicon-remove-sign";

            ////////            FIN                                     ///////


        break;
        case 3:    
            ////////                TODO "PENDIENTE"                          ////////
            $error = "Su Compra quedo pendiente de pago, aguardamos al pago para procesar su solicitud";
            $ico   = "glyphicon glyphicon-exclamation-sign";

            ////////            FIN                                     ///////
        break;

    }
} else {
    $estadoCompra = 0;
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
        
    .btn-crovan2 {
        color: #fff;
        background-color: #ff8601;
        border-top: 1px solid #fe8c0e;
        border-left: 1px solid #f48102;
        border-bottom: 1px solid #f48102;
        border-right: 1px solid #fe8c0e;
        padding: 10px 20px;
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
    <?php
        if (!isset($_SESSION['id_crovan'])) {
    ?>
    <div class="col-xs-12 mp-navbar">¿YA ESTAS REGISTRADO? <a href="../login/" style="color:#00F;">INGRESA ACA</a> <img src="../assets/img/iniciarsesion.png"></div>
    <?php
        } else {
    ?>
    <div class="col-xs-12 mp-navbar ingresoY"><img src="../assets/img/iniciarsesion.png"> Bienvenido: <span class="usuarioLogueado"><?php echo $usuario; ?></span></div>
    <?php
        }
    ?>
            
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
                        if ($estadoCompra == 1) {
                    ?>
                    <li class="dropdown"><h3 style="margin-top:-5px;"><span class="glyphicon glyphicon-gift"></span> TU COMPRA SE EFECTUÓ CON ÉXITO.</h3></li>                
                    <?php
                        } else {
                    ?>
                    <li class="dropdown"><h3 style="margin-top:-5px;"><span class="glyphicon glyphicon-remove"></span> LO SENTIMOS, SE GENERO UN ERROR EN LA COMPRA.</h3></li>                   
                    <?php        
                        }
                    ?>
                </div>                         
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2">
            
        </div>
        <div class="col-xs-8" style="margin-top:20px;">
            <?php
                if ($estadoCompra == 1) {
            ?>
            <div align="center">
                <ul class="list-inline" style="font-size:120px; color:#F7DC6F;">
                    <li><span class="glyphicon glyphicon-send"> </span></li>
                    <li><span class="glyphicon glyphicon-envelope"> </span></li>
                    <li>@</li>
                </ul>  
                <br>
                <br>
                <h3 style="border-bottom:2px solid #F7DC6F;font-size: 160%;">PARA TERMINAR TU REGISTRO TE ENVIAREMOS UN EMAIL DE CONFIRMACIÓN</h3>
                <br>
                <br>
                <h5 style="font-size: 100%;">MUCHAS GRACIAS POR ELEGIRNOS. <a href="../index.php">REGRESE A CROVAN KEGS</a></h5>  
            </div>               
            <?php
                } else {
            ?>
            <div align="center">
                <ul class="list-inline" style="font-size:120px; color:#F7DC6F;">
                    <li><span class="glyphicon glyphicon-send"> </span></li>
                    <li><span class="glyphicon glyphicon-envelope"> </span></li>
                    <li>@</li>
                </ul>  
                <br>
                <br>
                <h3 style="border-bottom:2px solid #F00;font-size: 160%;">SE GENERO UN INCONVENIENTE, INTENTE NUEVAMENTE. <a href="../index.php">REGRESE A CROVAN KEGS</a></h3>
            </div>                    
            <?php        
                }
            ?>
            
        </div>
        <div class="col-xs-2">
            
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
            
            
            $('.tienda').click(function() {
                url = "../productos/";
                $(location).delay(4000).attr('href',url);                   
            });
            
            $('.volver').click(function() {
                url = "../carrito/";
                $(location).delay(4000).attr('href',url);                   
            });
        
          

            
        
    });
    </script>        
</body>

</html>