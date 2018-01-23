<?php



include ('../sistema/includes/funcionesUsuarios.php');
include ('../sistema/includes/funciones.php');
include ('../sistema/includes/funcionesHTML.php');
include ('../sistema/includes/funcionesReferencias.php');


$serviciosUsuarios          = new ServiciosUsuarios();
$serviciosFunciones         = new Servicios();
$serviciosHTML              = new ServiciosHTML();
$serviciosReferencias       = new ServiciosReferencias();

session_start();

if (!isset($_SESSION['id_crovan']))
{
	$usuario = '';
} else {
    $usuario = $_SESSION['nombre_crovan'];
}

if (!isset($_GET['cat'])) {
    $idCategoria = 1;
} else {
    $idCategoria = $_GET['cat'];
}
$traerSecciones = $serviciosReferencias->traerCategoriasespecificacionPorCategoria($idCategoria);

$resProductos = $serviciosReferencias->traerProductos();

$cadProductos = '';
    $cadProductos .= '<option value="0"> </option>';
    while ($row = mysqli_fetch_array($resProductos)) {

        $cadProductos .= '<option value="'.$row[0].'">'.$row['nombre'].'</option>';
    }

$items = $serviciosReferencias->devolverCantidadItemsCarrito();

$subBarriles = $serviciosReferencias->traerEspecificacionesproductoPorCategoria(1);
$subMiniKegs = $serviciosReferencias->traerEspecificacionesproductoPorCategoria(2);
$subAccesorios = $serviciosReferencias->traerEspecificacionesproductoPorCategoria(3);

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

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/liquidmetal.js" type="text/javascript"></script>
    <script src="../assets/js/jquery.flexselect.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../assets/css/flexselect.css" type="text/css" media="screen" />   
</head>

<body>

   <body>
<div class="row" id="barraup">
<nav class="navbar navbar-default">
<div class="col-xs-3">
    <div class="col-xs-12">
        <img src="../assets/img/logonav.png" class="img-responsive pull-right imgnavbar">
    </div>
</div>
<div class="col-xs-6">
    <div class="col-xs-12">
              <form class="navbar-form navbar-left">
        <div class="form-group">
          <select id="lstResProductos" class="flexselect form-control">
                <?php echo $cadProductos; ?>
            </select>
        </div>
        <button type="submit" class=" glyphicon glyphicon-search"></button>
      </form>
    </div>
</div>
<div class="col-xs-3">
    
    <?php
        if (!isset($_SESSION['id_crovan'])) {
    ?>
    <a href="../login/"><div class="col-xs-8 mp-navbar"><img src="../assets/img/iniciarsesion.png">   Iniciar Sesión</div></a>
    <?php
        } else {
    ?>
    <a href=""><div class="col-xs-8 mp-navbar"><img src="../assets/img/iniciarsesion.png"> Bienvenido: <span class="usuarioLogueado"><?php echo $usuario; ?></span></div></a>
    <?php
        }
    ?>
    <?php
    if ($items > 0) {
        echo '<a href="../carrito/"><div class="col-xs-4 mp-navbar"><img src="../assets/img/carrito.png">  <span class="badge lstItems"> '.$items.'</span></div></a>';
    } else {
        echo '<a href="#"><div class="col-xs-4 mp-navbar"><img src="../assets/img/carrito.png">  <span class="badge lstItems"> 0</span></div></a>';
    }
    ?>
</div>
</nav>
</div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified">

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Minikegs <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="index.php?cat=2">MiniKeg</a></li>
                        <?php
                        while ($row = mysqli_fetch_array($subMiniKegs)) {
                            if ($row[1]== 3) {
                                $subSeccion = $row[2].' litros';    
                            } else {
                                $subSeccion = $row[2];
                            }
                        ?>
                        <li role="presentation"><a href="index.php?cat=2&sub=<?php echo $row[0]; ?>"><?php echo $subSeccion; ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Barriles <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="index.php?cat=1">Barriles</a></li>
                        <?php
                        while ($row = mysqli_fetch_array($subBarriles)) {
                            if ($row[1]== 3) {
                                $subSeccion = $row[2].' litros';    
                            } else {
                                $subSeccion = $row[2];
                            }
                        ?>
                        <li role="presentation"><a href="index.php?cat=1&sub=<?php echo $row[0]; ?>"><?php echo $subSeccion; ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Accesorios <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="index.php?cat=3">Accesorios</a></li>
                        <?php
                        while ($row = mysqli_fetch_array($subAccesorios)) {
                        ?>
                        <li role="presentation"><a href="index.php?cat=3&sub=<?php echo $row[0]; ?>"><?php echo $row[2]; ?></a></li>
                        <?php
                        }
                        ?>

                    </ul>
                </li>
                                                         
            </ul>
        </div>
    </div>

    <div class="row mprlpg">
    <div class="col-xs-1"></div>
        <div class="col-xs-2 hidden-xs">
            <div class="row mprfprod">


                <div class="col-xs-12">
                  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  <?php
                    while ($row = mysqli_fetch_array($traerSecciones)) {
                        $traerValores = $serviciosReferencias->traerEspecificacionesproductoPorGrupoCategoria($row['refgrupoespecificaciones'], $idCategoria);
                ?>
                <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <span class="glyphicon glyphicon-chevron-down cnaranj" aria-hidden="true"></span> <?php echo $row[3]; ?>
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                    <ul class="nav nav-pills nav-stacked">
                      <?php 
                        while ($rowD = mysqli_fetch_array($traerValores)) {
                            if ($rowD[1]== 3) {
                                $subSeccion = $rowD[2].' litros';    
                            } else {
                                $subSeccion = $rowD[2];
                            }
                      ?>
                      <li role="presentation" class="lstSecciones"><a href="javascript:void(0)" class="subseccion" id="<?php echo $rowD[0]; ?>"><?php echo $subSeccion; ?></a></li>
                        <?php
                            }
                        ?>

                    </ul>
                  </div>
                </div>
              </div>
                <?php
                    }
                ?>

                </div>     <!-- fin del acordion -->               
              </div>
                                             
            </div>
        </div> <!-- fin del x2 -->
        <div class="col-xs-9">
            <div class="row pprod">
                <div class="col-xs-12">
                    <h1>Nombre del Producto</h1>
                </div>
                <div class="col-xs-12 text-center lstProductos">
                    
                </div>
                           
            </div>    

                <div class="col-xs-12 text-center">
                        <nav aria-label="Page navigation">
                          <ul class="pagination">
                            <li>
                              <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                              <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                            </li>
                          </ul>
                        </nav>                    
                </div>
            </div>                                   
        </div>    
    </div>  
    <div class="row mprproddes mprlpg">
        <div class="col-xs-12 mprlpg">
            <h1 class="text-center text-muted">Productos Destacados</h1>
            <div class="row">
                <div class="col-xs-2"></div>
                <div class="col-lg-offset-0 col-xs-2 text-center">
                    <img class="img-circle img-responsive center-block hvr-float" src="../assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>
                </div>
                <div class="col-xs-2">
                    <img class="img-circle img-responsive center-block hvr-float" src="../assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2">
                    <img class="img-circle img-responsive center-block hvr-float" src="../assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2">
                    <img class="img-circle img-responsive center-block hvr-float" src="../assets/img/b3.png">
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
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.easing.min.js"></script>
    <script src="../assets/js/scrolling-nav.js"></script>
    <script src="../assets/js/wow.js"></script>
    <script>
     new WOW().init();
    </script>   


    <script type="text/javascript">
    $(document).ready(function(){

        $("select.flexselect").flexselect();
        
        $("select.flexselect").change(function() {
            if ($("select.flexselect").val() != null) {
                url = "../detalle/index.php?prod=" + $("select.flexselect").val();
                $(location).attr('href',url);    
            }
            
            //$('#selction-ajax').html('<button type="button" class="btn btn-warning varJugadorModificar" id="' + $("select.flexselect").val() + '" style="margin-left:0px;">Modificar</button>');
        });

        function traerProductosCategoria() {
            $.ajax({
                data:  {idCategoria: <?php echo $idCategoria; ?>, accion: 'traerProductosPorCategoriaWeb'},
                url:   '../sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    $('.lstProductos').html(response);

                }
            });


        }
        
        
        function devolverItems() {
            $.ajax({
                data:  {accion: 'devolverItems'},
                url:   '../sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    $('.lstItems').html(response);

                }
            });


        }
        
        devolverItems();


        function traerProductosCategoriaEspecificaciones(especificaciones) {
            $.ajax({
                data:  {idcategoria: <?php echo $idCategoria; ?>, 
                        especificaciones: especificaciones, 
                        accion: 'traerProductosPorCategoriaEspecificacionWeb'},
                url:   '../sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    $('.lstProductos').html(response);

                }
            });


        }

        $(".lstSecciones").on("click",'.subseccion', function(){
              usersid =  $(this).attr("id");
              if (!isNaN(usersid)) {
                
                traerProductosCategoriaEspecificaciones(usersid);
              } else {
                alert("Error, vuelva a realizar la acción.");   
              }
        });//

        $('.lstProductos').on("click",'.comprar', function() {
            usersid =  $(this).attr("id");
              if (!isNaN(usersid)) {
                
                url = "../detalle/index.php?prod=" + usersid;
                $(location).attr('href',url);
              } else {
                alert("Error, vuelva a realizar la acción.");   
              }
        })
        
        <?php
        if (!isset($_GET['sub'])) {
        ?>
        traerProductosCategoria();
        <?php
        } else {
        ?>
        traerProductosCategoriaEspecificaciones(<?php echo $_GET['sub']; ?>);
        <?php
        }
        ?>

    });
</script>   
</body>

</html>