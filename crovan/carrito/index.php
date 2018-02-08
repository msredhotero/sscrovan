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

$items = $serviciosReferencias->devolverCantidadItemsCarrito();

if ($items < 1) {
    header('Location: ../index.php');
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
        
    .modal-header {

        background-color: #ff8501;
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
                    <li class="dropdown"><h3 style="margin-top:-5px;"><span class="glyphicon glyphicon-shopping-cart"></span> MI CARRITO</h3></a></li>                
                </div>                         
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1">
            
        </div>
        <div class="col-xs-10" style="margin-top:20px;">

            <form>
              <div id="lstProductosCarrito">
               <?php
                    $total = 0;
                    if (isset($_SESSION['idProducto_carrito_crovan'])) {
                ?>
                <?php
                $i=0;
                foreach ($_SESSION['idProducto_carrito_crovan'] as $row) {
                    $resProducto = $serviciosReferencias->traerProductosPorIdWeb($row);
                    $imagen = $serviciosReferencias->mysqli_result($resProducto,0,'imagenproducto');
                    $nombre = $serviciosReferencias->mysqli_result($resProducto,0,'nombre');
                    $descripcion = $serviciosReferencias->mysqli_result($resProducto,0,'descripcion');
                    $precio = $serviciosReferencias->mysqli_result($resProducto,0,'precioventa');
                    $total += $serviciosReferencias->mysqli_result($resProducto,0,'precioventa') * $_SESSION['cantidad_carrito_crovan'][$i];
                    
                    $cantidadDisponible = $serviciosReferencias->hayStockWeb($row);
                    
                ?>
                <div class="row" id="col-data-<?php echo $row; ?>">
                <div class="col-xs-2" style="margin-top:20px;">
                    <img src="../<?php echo $imagen; ?>" style="max-height: 110px;" class="img-responsive hvr-grow">
                </div>
                <div class="col-xs-10" style="margin-top:20px; border-bottom:3px solid #ff8601; padding-bottom:22px;">
                    <div class="col-xs-5" style="margin-top:20px;">
                        <h4><?php echo $nombre; ?></h4>
                        <p><?php echo substr($descripcion,0,30); ?></p>
                    </div>
                    <div class="col-xs-3" style="margin-top:20px;">
                        <ul class="list-inline">
                            <li>
                                <button type="button" class="btn btn-default quitarCantidad" id="<?php echo $row; ?>"><span class="glyphicon glyphicon-minus-sign"></span></button>
                            </li>
                            <li>
                                <input style="width:50px; text-align: center; padding:4px;" type="text" readonly id="cantidad<?php echo $row; ?>" name="cantidad" value="<?php echo $_SESSION['cantidad_carrito_crovan'][$i]; ?>" maxlength="<?php echo $cantidadDisponible; ?>"/>
                                
                            </li>
                            <li>
                                <button type="button" class="btn btn-default agregarCantidad" id="<?php echo $row; ?>" data-toggle="modal" data-target="#exampleModal"><span class="glyphicon glyphicon-plus-sign"></span></button>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-2" style="margin-top:20px;">
                        <h4>$ <span class="precio" id="precio<?php echo $row; ?>"><?php echo ($precio * $_SESSION['cantidad_carrito_crovan'][$i]); ?></span></h4>
                        <input style="width:50px; text-align: center; padding:4px;" type="hidden" readonly id="hprecio<?php echo $row; ?>" name="hprecio" value="<?php echo $precio; ?>"/>
                    </div>
                    <div class="col-xs-2" style="margin-top:20px;">
                        <h5 style="color:#2E64FE; cursor: pointer;" class="quitarProducto" id="<?php echo $row; ?>">ELIMINAR</h5>
                    </div>
                </div>
                </div>
                </div><!-- fin del contenedor de carrito -->
                <?php
                    $i += 1;
                }
                ?>
                <?php
                    } else {
                ?>
            
                <div class="scale__container--js">
                    <h4 class="scale--js tituloA">USTED YA ESTA LOGUEADO EN CROVAN KEGS</h4>
                </div>
                <div class="form-group" style="padding:20px; background-color:#f5f5f5; border:1px solid #ececec; height:120px;">
                    <div class="col-xs-6" style="margin-top:20px;">
                        <button type="button" class="btn-crovan tienda">TIENDA</button>
                    </div>
                    <div class="col-xs-6" style="margin-top:20px;">
                        <button type="button" class="btn-crovan logout">SALIR</button>
                    </div>
                    

                </div>
                

                
                <?php
                    }
                ?>
                
                <div class="row" style="margin-top:30px; " id="divFinalizarCompra">
                    <div class="col-xs-3">
                    
                    </div>
                    <div class="col-xs-3" style="background-color: #EDEDED; height:60px; padding-top:8px; border-bottom:2px solid #BCBCBC;">
                        <h4 style="color:#ABABAB;">SUBTOTAL <span class="total pull-right" id="subtotal"><?php echo $total; ?></span></h4>
                    </div>
                    <div class="col-xs-3" style="background-color: #EDEDED;height:60px; padding-top:8px;">
                        <button type="button" class="btn-crovan confirmar">FINALIZAR COMPRA</button>
                    </div>
                    <div class="col-xs-3">
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                    
                    </div>
                    <div class="col-xs-3" style="background-color: #EDEDED; height:60px; padding-top:8px;">
                        <h4>TOTAL <span class="total pull-right" id="total"><?php echo $total; ?></span></h4>
                    </div>
                    <div class="col-xs-3" style="background-color: #EDEDED;height:60px; padding-top:8px;">
                        <button type="button" class="btn-crovan2 tienda">CONTINUAR COMPRANDO</button>
                    </div>
                    <div class="col-xs-3">
                    
                    </div>
                </div>
              
              
            </form>
        </div>
        <div class="col-xs-1">
            
        </div>
    </div>

    
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Crovan Kegs Carrito</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="resultadoAgregar"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
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
            
            $('.confirmar').click(function() {
                <?php
                    if ($usuario == '') {
                ?>
                url = "../logincompra/";
                <?php
                    } else {
                        
                ?>
                url = "../confirmacion/";
                <?php  
                    }
                ?>
                
                $(location).delay(4000).attr('href',url);                   
            });
        
          function modificarCantidad(idProducto, contenedor, operacion) {
            $.ajax({
                data:  {idProducto: idProducto,  
                        operacion: operacion, 
                        accion: 'modificarCantidadTienda'},
                url:   '../sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    if ($.isNumeric(response)) {
                        $('#'+contenedor).val(parseInt($('#'+contenedor).val()) + parseInt(response));    

                        $('#precio'+idProducto).html( parseFloat($('#'+contenedor).val()) * parseFloat($('#hprecio'+idProducto).val()) );
                        
                        actualizarTotal();
                        
                        if (response == 0) {
                            $('.resultadoAgregar').html('<spam class="glyphicon glyphicon-remove-circle"></spam> Stock Insuficiente');
                        } else {
                            $('.resultadoAgregar').html('<spam class="glyphicon glyphicon-ok-circle"></spam> Se cargo al carrito el producto');
                        }
                    } else {
                        $('.error').html(response);    
                        $('.error').removeClass('alert alert-success');
                        $('.error').addClass('alert alert-danger');
                    }
                    

                }
            });
              
          }
        
        function actualizarTotal() {
            var total = 0;
            $( ".precio" ).each(function( index ) {
              total += parseFloat($( this ).text());
            });
            
            if (total == 0) {
                
                $('#divFinalizarCompra').hide();
                        
            }
            
            $('#total').html(total);
            $('#subtotal').html(total);
        }
            
        $('.quitarCantidad').click(function() {
            prodId =  $(this).attr("id"); 
            if ($('#cantidad'+ prodId).val() > 1) {
                modificarCantidad(prodId,'cantidad' + prodId, 'E'); 
            }
        });
  
        $('.agregarCantidad').click(function() {
            prodId =  $(this).attr("id"); 

            modificarCantidad(prodId,'cantidad' + prodId, 'I');
            
        });
              
        

        function quitarProducto(idProducto) {
            $.ajax({
                data:  {idProducto: idProducto, 
                        accion: 'quitarProductoTienda'},
                url:   '../sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    if (response == '') {
                        $('#col-data-'+idProducto).remove();
                        actualizarTotal();
                        

                    } else {
                        $('.error').html(response);    
                        $('.error').removeClass('alert alert-success');
                        $('.error').addClass('alert alert-danger');
                    }


                }
            });


        }
            
        $('.quitarProducto').click(function() {
            prodId =  $(this).attr("id"); 

            quitarProducto(prodId);
            
        });

            
        
    });
    </script>        
</body>

</html>