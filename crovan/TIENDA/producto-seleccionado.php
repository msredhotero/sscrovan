<?php



include ('../sistema/includes/funcionesUsuarios.php');
include ('../sistema/includes/funciones.php');
include ('../sistema/includes/funcionesHTML.php');
include ('../sistema/includes/funcionesReferencias.php');


$serviciosUsuarios          = new ServiciosUsuarios();
$serviciosFunciones         = new Servicios();
$serviciosHTML              = new ServiciosHTML();
$serviciosReferencias       = new ServiciosReferencias();

$idProducto = $_GET['prod'];

$resProd = $serviciosReferencias->traerProductosPorIdWeb($idProducto);

$nombre = mysql_result($resProd,0,'nombre');
$detalle = mysql_result($resProd,0,'descripcion');
$precioventa = mysql_result($resProd,0,'precioventa');
$codigo = mysql_result($resProd,0,'codigo');
$imagenproducto = mysql_result($resProd,0,'imagenproducto');


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
          <select id="lstResProductos" class="flexselect form-control" placeholder="Busca tu producto">
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
                        <li role="presentation"><a href="#">10 Litros</a></li>
                        <li role="presentation"><a href="#">5 Litros</a></li>
                        <li role="presentation"><a href="#">4 Litros</a></li>
                        <li role="presentation"><a href="#">2 Litros</a></li>
                        <li role="presentation"><a href="#">Botellon 2L</a></li>
                        <li role="presentation"><a href="#">Accesorios</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Barriles <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="#">Euro Standard</a></li>
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
       
      <div class="col-xs-12 text-center">
        <ol class="breadcrumb">
          <li><a href="#">MiniKeg</a></li>
          <li><a href="#">5L</a></li>
          
        </ol>           
      </div> 
          
    </div>    
    <div id="producto" class="row mprprod" >   
           <div class="col-xs-1 mprprod"></div> 
           <div class="col-xs-7 mprprod">

               <div class="row">
                    <div id="miniprod" class="carousel slide" data-ride="carousel">
                                                          <!-- Indicators -->
                                                          <ol class="carousel-indicators">
                                                            <li data-target="#miniprod" data-slide-to="0" class="active"></li>
                                                            <li data-target="#miniprod" data-slide-to="1"></li>
                                                            <li data-target="#miniprod" data-slide-to="2"></li>
                                                          </ol>

                                                          <!-- Wrapper for slides -->
                                                          <div class="carousel-inner" role="listbox">
                                                            <div class="item active">
                                                              <img src="../<?php echo $imagenproducto; ?>" class=" center-block" alt="Responsive Image">
                                                              <div class="carousel-caption">
                                                                
                                                              </div>
                                                            </div>
                                                            <div class="item">
                                                              <img src="../<?php echo $imagenproducto; ?>" class=" center-block" alt="Responsive Image">
                                                              <div class="carousel-caption">
                                                                
                                                              </div>
                                                            </div>
                                                            <div class="item">
                                                              <img src="../<?php echo $imagenproducto; ?>" class=" center-block" alt="Responsive Image">
                                                              <div class="carousel-caption">
                                                                
                                                              </div>
                                                            </div>                
                                                          </div>

                                                          <!-- Controls -->
                                                          <a class="left carousel-control" href="#miniprod" role="button" data-slide="prev">
                                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                          </a>
                                                          <a class="right carousel-control" href="#miniprod" role="button" data-slide="next">
                                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                          </a>
                </div>                                             
               </div>
            <div class="row text-center mpridprod">
                           <a href="#" class="hvr-float" title=""><img claass="img-responsive " src="assets/img/miniprod.png"></a>
                           <a href="#" class="hvr-float" title=""><img claass="img-responsive " src="assets/img/miniprod.png"></a>
                           <a href="#" class="hvr-float" title=""><img claass="img-responsive " src="assets/img/miniprod.png"></a>

            </div>               
           </div> 
           <div class="col-xs-3 text-center mprprod">
               <div class="row ">
                   <div class="col-xs-12">
                       <h2><?php echo $nombre; ?> COD#<?php echo $codigo; ?></h2>
                       <hr>
                   </div>
                   <div class="col-xs-12">
                       <h5 class="text-muted">
                                Precio anterior
                                <br>
                                $ <?php echo $precioventa; ?>
                       </h5>
                       <h5>
                            Precio anterior
                                <br>                            
                            $0000.00
                       </h5>
                       <hr>
                       <span class="glyphicon glyphicon-credit-card" aria-hidden="true"><h4>18 cuotas de $ <?php echo round(($precioventa / 18),2); ?></h4></span>
                       <h5><small> <a href="#" title="">Ver Condiciones de pago</a> | <a href="#" title="">Medios de pago</a> </small></h5>

                   </div>
                   <div class="col-xs-12">
                        <button  type="button" class="btn btn-warning ">
                          <span class="glyphicon glyphicon-shopping-cart " aria-hidden="true"></span> Comprar
                        </button>                       
                   </div>
               </div>
           </div>
           <div class="col-xs-1 mprprod"></div>     
    </div>
    <div class="row">
        <div class="col-xs-12">
            
            <div class="col-xs-12 text-center">
                <h1>Especificaciones Técnicas</h1>
            </div>
            <div class="col-xs-12">
              <?php echo $detalle; ?>
            </div>
        </div>
    </div>
  
    <div class="row ">
        <div class="col-xs-12 mprlpg">
            <h1 class="text-center text-muted">Productos Destacados</h1>
            <div class="row">
                <div class="col-xs-2"></div>
                <div class="col-lg-offset-0 col-xs-2 text-center">
                    <a href=""><img class="img-circle img-responsive center-block hvr-float" src="assets/img/b3.png"></a>
                    <p class="text-center">Detalle producto</p>
                </div>
                <div class="col-xs-2">
                    <a href=""><img class="img-circle img-responsive center-block hvr-float" src="assets/img/b3.png"></a>
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2">
                    <a href=""><img class="img-circle img-responsive center-block hvr-float" src="assets/img/b3.png"></a>
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2">
                    <a href=""><img class="img-circle img-responsive center-block hvr-float" src="assets/img/b3.png"></a>
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ">
            <p class="text-center text-muted">Crovan Kegs | (+ 54 9) 11 7017 3422 | info@crovankegs.com</p>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/scrolling-nav.js"></script>
    <script src="assets/js/wow.js"></script>
    <script>
     new WOW().init();
    </script>      
</body>

</html>