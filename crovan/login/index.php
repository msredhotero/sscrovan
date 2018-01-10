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
    <div class="col-xs-12 mp-navbar ingresoY"><img src="../assets/img/iniciarsesion.png"> Bienvenido: <span class="usuarioLogueado"></span></div>
    <div class="col-xs-12 mp-navbar ingresoN">¿TODAVIA NO TENES TU USUARIO? <a href="../registro/" style="color:#00F;">INGRESA ACA</a> <img src="../assets/img/iniciarsesion.png"></div>
</div>
</nav>
</div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified">
                <div class="col-xs-1">
                </div>
                <div class="col-xs-11">
                    <li class="dropdown"><h3 style="margin-top:-5px;">BIENVENIDO EN CROVAN KEGS</h3></a></li>                
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
                    <h4 class="scale--js tituloA">INGRESA CON TU USUARIO A CROVAN KEGS</h4>
                </div>
              <div class="form-group">
                <label for="exampleInputEmail1">EMAIL</label>
                <input type="email" class="form-control" id="txtEmail" aria-describedby="emailHelp" placeholder="E-MAIL">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">CONTRASEÑA</label>
                <input type="password" class="form-control" id="txtPassword" aria-describedby="emailHelp" placeholder="CONTRASEÑA">
              </div>
                <div class="form-group">
                  <div class="error"></div>
                </div>
              <div class="form-group" style="padding:20px; background-color:#f5f5f5; border:1px solid #ececec;">
                <label for="exampleInputPassword1">No cerrar sessión</label>
                <input type="checkbox" class="form-check-input" style="margin: 0px 25px 0px 7px;">
                <br>
                <small id="emailHelp" class="form-text text-muted">¿OLVIDASTE TU CONTRASEÑA?</small>
                <div class="pull-right" style="margin-top:-25px; margin-bottom:15px;">
                <button type="button" class="btn-crovan">INGRESAR</button>

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
          function ingresar(email, password) {
            $.ajax({
                data:  {email: email, 
                        password: password, 
                        accion: 'ingresarCrovan'},
                url:   '../sistema/ajax/ajax.php',
                type:  'post',
                beforeSend: function () {

                },
                success:  function (response) {
                    if (response == '') {
                        $('.error').html('Se logueo correctamente.');
                        $('.error').removeClass('alert alert-danger');
                        $('.error').addClass('alert alert-success');
                        
                    } else {
                        $('.error').html(response);    
                        $('.error').removeClass('alert alert-success');
                        $('.error').addClass('alert alert-danger');
                    }
                    

                }
            });


          }
        
            
        $("#txtEmail").click(function(event) {
            $("#txtEmail").removeClass("alert alert-danger");
            $("#txtEmail").attr('placeholder','Ingrese el email');
        });

        $("#txtEmail").change(function(event) {
         $("#txtEmail").removeClass("alert alert-danger");
         $("#txtEmail").attr('placeholder','Ingrese el email');
        });
            
            
       
            
        $("#txtPassword").click(function(event) {
            $("#txtPassword").removeClass("alert alert-danger");
            $("#txtPassword").attr('placeholder','Ingrese el Contraseña');
            $('#errorP').html('');
        });

        $("#txtPassword").change(function(event) {
            $("#txtPassword").removeClass("alert alert-danger");
            $("#txtPassword").attr('placeholder','Ingrese la Contraseña');
            $('#errorP').html('');
        });
 
            
        function validar() {
            var $error = '';
            
            if ($('#txtPassword').val() == "") {
                $error = "Es obligatorio el campo Contraseña.";
                $('#errorP').html($error);
            }
            
          
            
            if ($('#txtEmail').val() == "") {
                $error = "Es obligatorio el campo Email.";
                $('#txtEmail').addClass("alert alert-danger");
                $('#txtEmail').attr('placeholder',$error);
                
            }
            
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            
            if( !emailReg.test( $('#txtEmail').val() ) ) {
                $error = "El E-Mail ingresado es inválido.";
                $('#txtEmail').addClass("alert alert-danger");
                $('#txtEmail').attr('placeholder',$error);
                
                
            }
            
            return $error;
        }

        $('.ingresar').click(function(){
           if (validar() == '') {
               ingresar($('#txtEmail').val(),$('#txtPassword').val());
           }
               
        });
    });
    </script>        
</body>

</html>