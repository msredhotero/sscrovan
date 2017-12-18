<?php



include ('sistema/includes/funcionesUsuarios.php');
include ('sistema/includes/funciones.php');
include ('sistema/includes/funcionesHTML.php');
include ('sistema/includes/funcionesReferencias.php');


$serviciosUsuarios          = new ServiciosUsuarios();
$serviciosFunciones         = new Servicios();
$serviciosHTML              = new ServiciosHTML();
$serviciosReferencias       = new ServiciosReferencias();

$idCategoria = $_GET['cat'];

$traerSecciones = $serviciosReferencias->traerCategoriasespecificacionPorCategoria($idCategoria);

$resProductos = $serviciosReferencias->traerProductos();

$cadProductos = '';
    $cadProductos .= '<option value="0"> </option>';
    while ($row = mysql_fetch_array($resProductos)) {

        $cadProductos .= '<option value="'.$row[0].'">'.$row['nombre'].'</option>';
    }

?>
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

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/liquidmetal.js" type="text/javascript"></script>
    <script src="assets/js/jquery.flexselect.js" type="text/javascript"></script>
    <link rel="stylesheet" href="assets/css/flexselect.css" type="text/css" media="screen" />
</head>

<body>

    

    <div class="row text-center hidden-xs" id="barraup1">
        <div class="col-xs-2"></div>
        <div class="col-xs-2"><i class="material-icons">local_shipping</i>
            <p class="text-center">Seguí tu compra</p>
        </div>
        <div class="col-xs-2"><i class="fa fa-facebook-square"></i>
            <p class="text-center">/crovankegs </p>
        </div>
        <div class="col-xs-2"><i class="fa fa-instagram"></i>
            <p>crovankegs </p>
        </div>
        <div class="col-xs-2"><i class="fa fa-envelope-o"></i>
            <p>info@crovankegs.com</p>
        </div>
        <div class="col-xs-2"></div>
    </div>

    <div class="row">   
           <img src="assets/img/banner.png" class="img-responsive" alt="">       
    </div>

    <div class="row" id="barraup">
        <div class="col-xs-4"><img class="img-responsive center-block" src="assets/img/logonav.png"></div>
        <div class="col-xs-4">
            <a href="#" title=""><i class="material-icons cpibsup">search</i></a>
            <select id="lstResProductos" class="flexselect form-control">
                <?php echo $cadProductos; ?>
            </select>


        </div>
        <div class="col-xs-4">
            <div class="row">
                <a href="#" title=""><div class="col-xs-6 "><i class="fa fa-shopping-cart center-block"> </i><h5 class="fpbsup text-left"> Carrito</h5></div></a>
                <a href="#" title=""><div class="col-xs-6 center-block"><i class="fa fa-user text-center"></i><h5 class="fpbsup text-left">Iniciar Sesión</h5></div></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified">
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="listar.php?cat=1">Barriles <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="listar.php?cat=1">Entrar</a></li>
                        <li role="presentation"><a href="#">Second Item</a></li>
                        <li role="presentation"><a href="#">Third Item</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="listar.php?cat=2">MiniKeg <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="listar.php?cat=2">Entrar</a></li>
                        <li role="presentation"><a href="#">Second Item</a></li>
                        <li role="presentation"><a href="#">Third Item</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="listar.php?cat=3">Accesorios <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><a href="listar.php?cat=3">Entrar</a></li>
                        <li role="presentation"><a href="#">Second Item</a></li>
                        <li role="presentation"><a href="#">Third Item</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="row mprlpg">
        <div class="col-xs-3">
            <div class="row">
                <?php
                    while ($row = mysql_fetch_array($traerSecciones)) {
                        $traerValores = $serviciosReferencias->traerEspecificacionesproductoPorGrupoCategoria($row['refgrupoespecificaciones'], $idCategoria);
                ?>
                <div class="col-xs-12">
                    <div class="list-group lstSecciones">
                      <a href="#" class="list-group-item disabled">
                        <?php echo $row[3]; ?>
                      </a>
                      <?php 
                        while ($rowD = mysql_fetch_array($traerValores)) {
                      ?>
                      <a href="javascript:void(0)" class="list-group-item subseccion" id="<?php echo $rowD[0]; ?>"><?php echo $rowD[2]; ?></a>
                        <?php
                            }
                        ?>

                    </div>                    
                </div>

                <?php
                    }
                ?>
                
                                             
            </div>
        </div>
        <div class="col-xs-9">
            <div class="row lstProductos">
                
            </div>
            <div class="row mpridprod hidden">
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p>
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                        
                </div>
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p>
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                        
                </div>
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p>
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                        
                </div>
            </div>
            <div class="row mpridprod hidden">
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p> 
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                       
                </div>
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p> 
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                       
                </div>
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p> 
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p> 
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                      
                </div>
            </div>    
            <div class="row mpridprod hidden">
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p> 
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                       
                </div>
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p> 
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p> 
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                      
                </div>
                <div class="col-xs-4">
                    <img class=" img-responsive center-block" src="assets/img/prod.png">
                    <p class="text-center">Detalle producto</p> 
                    <p class="text-center"><small> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. </small></p>
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                      <span class="glyphicon  glyphicon-shopping-cart  center-block" aria-hidden="true"></span>
                    </button>                                       
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
                    <img class="img-circle img-responsive center-block" src="assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>
                </div>
                <div class="col-xs-2">
                    <img class="img-circle img-responsive center-block" src="assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2">
                    <img class="img-circle img-responsive center-block" src="assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2">
                    <img class="img-circle img-responsive center-block" src="assets/img/b3.png">
                    <p class="text-center">Detalle producto</p>                    
                </div>
                <div class="col-xs-2"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ">
            <p class="text-center text-muted">Crovan Kegs Copyright 2018 - Powered By Saupurein Consulting</p>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>




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

        function traerProductosCategoria() {
            $.ajax({
                data:  {idCategoria: <?php echo $idCategoria; ?>, accion: 'traerProductosPorCategoriaWeb'},
                url:   'sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    $('.lstProductos').html(response);

                }
            });


        }


        function traerProductosCategoriaEspecificaciones(especificaciones) {
            $.ajax({
                data:  {idcategoria: <?php echo $idCategoria; ?>, 
                        especificaciones: especificaciones, 
                        accion: 'traerProductosPorCategoriaEspecificacionWeb'},
                url:   'sistema/ajax/ajax.php',
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

        $('.lstProductos').on("click",'.glyphicon-shopping-cart', function() {
            usersid =  $(this).attr("id");
              if (!isNaN(usersid)) {
                
                url = "producto-seleccionado.php?prod=" + usersid;
                $(location).attr('href',url);
              } else {
                alert("Error, vuelva a realizar la acción.");   
              }
        })

        traerProductosCategoria();

    });
</script>
</body>

</html>