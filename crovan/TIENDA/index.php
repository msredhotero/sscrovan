<?php

include ('../sistema/includes/funcionesUsuarios.php');
include ('../sistema/includes/funciones.php');
include ('../sistema/includes/funcionesHTML.php');
include ('../sistema/includes/funcionesReferencias.php');


$serviciosUsuarios          = new ServiciosUsuarios();
$serviciosFunciones         = new Servicios();
$serviciosHTML              = new ServiciosHTML();
$serviciosReferencias       = new ServiciosReferencias();

$resProductos = $serviciosReferencias->traerProductos();

$cadProductos = '';
    $cadProductos .= '<option value="0"> </option>';
    while ($row = mysql_fetch_array($resProductos)) {

        $cadProductos .= '<option value="'.$row[0].'">'.$row['nombre'].'</option>';
    }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
   <link rel="stylesheet" type="text/css" href="assets/css/animate.css">
    <link href="assets/css/hover.css" rel="stylesheet">  
    <link href="assets/css/imagehover.min.css" rel="stylesheet">   

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/liquidmetal.js" type="text/javascript"></script>
    <script src="assets/js/jquery.flexselect.js" type="text/javascript"></script>
    <link rel="stylesheet" href="assets/css/flexselect.css" type="text/css" media="screen" /> 
</head>

<body>
<div class="row" id="barraup">
<nav class="navbar navbar-default">
<div class="col-xs-3">
    <div class="col-xs-12">
        <img src="assets/img/logonav.png" class="img-responsive pull-right imgnavbar">
    </div>
</div>
<div class="col-xs-6">
    <div class="col-xs-12">
              <form class="navbar-form navbar-left">
        <div class="form-group">
          <select id="lstResProductos" class="flexselect form-control" style="width:300px;" placeholder="Busca tu producto">
                <?php echo $cadProductos; ?>
            </select>
        </div>
        <button type="submit" class=" glyphicon glyphicon-search"></button>
      </form>
    </div>
</div>
<div class="col-xs-3">
    <a href="#"><div class="col-xs-6 mp-navbar"><img src="assets/img/iniciarsesion.png">   Iniciar Sesión</div></a>
    <a href="#"><div class="col-xs-6 mp-navbar"><img src="assets/img/carrito.png">  <span class="badge"> 42</span></div></a>
</div>
</nav>
</div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified">

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Minikegs <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="listar.php?cat=2">10 Litros</a></li>
                        <li role="presentation"><a href="#">5 Litros</a></li>
                        <li role="presentation"><a href="#">4 Litros</a></li>
                        <li role="presentation"><a href="#">2 Litros</a></li>
                        <li role="presentation"><a href="#">Botellon 2L</a></li>
                        <li role="presentation"><a href="#">Accesorios</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Barriles <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="listar.php?cat=1">Euro Standard</a></li>
                        <li role="presentation"><a href="#">Din Standard</a></li>
                        <li role="presentation"><a href="#">Slim Standard</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Accesorios <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="#">Conectores</a></li>
                        <li role="presentation"><a href="#">Reguladores de Presión</a></li>
                        <li role="presentation"><a href="#"> Primario</a></li>
                        <li role="presentation"><a href="#"> Con manguera</a></li>
                        <li role="presentation"><a href="#"> Sin manguera</a></li>
                        <li role="presentation"><a href="#">  Secundario</a></li>
                        <li role="presentation"><a href="#"> Party Pump</a></li>
                        <li role="presentation"><a href="#"> Torres – Pilones</a></li>

                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Levaduras<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="#">First Item</a></li>
                        <li role="presentation"><a href="#">Second Item</a></li>
                        <li role="presentation"><a href="#">Third Item</a></li>
                    </ul>
                </li>  
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Lúpulos <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="#">First Item</a></li>
                        <li role="presentation"><a href="#">Second Item</a></li>
                        <li role="presentation"><a href="#">Third Item</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Maltas <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="#">First Item</a></li>
                        <li role="presentation"><a href="#">Second Item</a></li>
                        <li role="presentation"><a href="#">Third Item</a></li>
                    </ul>
                </li>                                              
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
        <img src="assets/img/banner.png" class="img-responsive ">
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 ">
            <p class="text-center text-muted">Crovan Kegs | (+ 54 9) 11 7017 3422 | info@crovankegs.com</p>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/scrolling-nav.js"></script>
    <script src="assets/js/wow.js"></script>
    <script>
     new WOW().init();
    </script>   


    <script type="text/javascript">
$(document).ready(function(){
$("select.flexselect").flexselect();

    $("select.flexselect").change(function() {
        if ($("select.flexselect").val() != null) {
            url = "producto-seleccionado.php?prod=" + $("select.flexselect").val();
            $(location).attr('href',url);    
        }
        
        //$('#selction-ajax').html('<button type="button" class="btn btn-warning varJugadorModificar" id="' + $("select.flexselect").val() + '" style="margin-left:0px;">Modificar</button>');
    });
});
</script>    
</body>

</html>