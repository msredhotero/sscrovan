<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Buenos_Aires');

class ServiciosUsuarios {

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


function login($usuario,$pass) {
	
	$sqlusu = "select * from dbusuarios where email = '".$usuario."'";

$error = '';

if (trim($usuario) != '' and trim($pass) != '') {

$respusu = $this->query($sqlusu,0);

if (mysqli_num_rows($respusu) > 0) {
	
	
	$idUsua = $this->mysqli_result($respusu,0,0);
	$sqlpass = "select concat(u.apellido,' ',u.nombre) as nombrecompleto,email,concat(u.apellido,' ',u.nombre) as usuario,r.descripcion, r.idrol from dbusuarios u inner join tbroles r on r.idrol = u.refroles where password = '".$pass."' and idusuario = ".$idUsua;


	$resppass = $this->query($sqlpass,0);
	
	if (mysqli_num_rows($resppass)>0) {
		$error = '';
		} else {
			$error = 'Usuario o Password incorrecto';
		}
	
	}
	else
	
	{
		$error = 'Usuario o Password incorrecto';	
	}
	
	if ($error == '') {
		//die(var_dump($error));
		session_start();
		$_SESSION['usua_predio'] = $usuario;
		$_SESSION['nombre_predio'] = $this->mysqli_result($resppass,0,0);
		$_SESSION['email_predio'] = $this->mysqli_result($resppass,0,1);
		$_SESSION['idroll_predio'] = $this->mysqli_result($resppass,0,4);
		$_SESSION['refroll_predio'] = $this->mysqli_result($resppass,0,3);
		
		return '';
	}
	
}	else {
	$error = 'Usuario y Password son campos obligatorios';	
}
	
	
	return $error;
	
}
    
    function loginCrovan($usuario,$pass) {

        $sqlusu = "select * from dbusuarios where email = '".$usuario."' and refroles = 4";

        $error = '';

        if (trim($usuario) != '' and trim($pass) != '') {

            $respusu = $this->query($sqlusu,0);

            if (mysqli_num_rows($respusu) > 0) {


            $idUsua = $this->mysqli_result($respusu,0,0);
            $sqlpass = "select concat(u.apellido,' ',u.nombre) as nombrecompleto,email,concat(u.apellido,' ',u.nombre) as usuario,r.descripcion, r.idrol, u.activo from dbusuarios u inner join tbroles r on r.idrol = u.refroles where password = '".$pass."' and idusuario = ".$idUsua;


            $resppass = $this->query($sqlpass,0);

            // debo verificar si el usuario esta activo, en caso contrario le envio un email para darse de alta.
            $activo = $this->mysqli_result($resppass,0,5);

                if ($activo == 1) {

                    if (mysqli_num_rows($resppass)>0) {
                        $error = '';
                    } else {
                        $error = 'Usuario o Password incorrecto';
                    }

                } else {
                    $resVerificador = $this->traerActivacionusuariosPorUsuarioFechas($idUsua);
                    if (mysqli_num_rows($resVerificador) > 0) {
                        $error = 'El usuario no esta activo, verifique su casilla de correo para activarlo';
                    } else {
                        $token = $this->GUID();
                        $this->insertarActivacionusuarios($idUsua,$token,'','');
                        
                        $destinatario = $usuario;
                        $asunto = "Activar Cuenta";
                        $cuerpo = "<h3>Gracias por registrarse en Crovan Kegs.</h3><br>
                                    <p>Por favor haga click <a href='saupureinconsulting.com.ar/crovan/activacion/index.php?token=".$token."'>AQUI</a> para activar la cuenta</p><br><br>
                                    <p>PD: Recuerde que solo estara pendiente la confirmacion por 2 dias</p>";
                        $this->enviarEmail($destinatario,$asunto,$cuerpo);
                        $error = 'El usuario no esta activo, verifique su casilla de correo para activarlo';
                    }
                }

            } else {
                $error = 'Usuario o Password incorrecto';	
            }

            if ($error == '') {
                //die(var_dump($error));
                session_start();
                $_SESSION['usua_crovan'] = $usuario;
                $_SESSION['nombre_crovan'] = $this->mysqli_result($resppass,0,0);
                $_SESSION['email_crovan'] = $this->mysqli_result($resppass,0,1);
                $_SESSION['idroll_crovan'] = $this->mysqli_result($resppass,0,4);
                $_SESSION['refroll_crovan'] = $this->mysqli_result($resppass,0,3);
                $_SESSION['id_crovan'] = $idUsua;

                return '';
            }

        } else {
            $error = 'Usuario y Password son campos obligatorios';	
        }


        return $error;

    }

function loginFacebook($usuario) {
	
	$sqlusu = "select concat(apellido,' ',nombre),email,direccion,refroll from se_usuarios where email = '".$usuario."'";
	$error = '';


if (trim($usuario) != '') {

$respusu = $this->query($sqlusu,0);

	if (mysqli_num_rows($respusu) > 0) {
		
		
		if ($error == '') {
			session_start();
			$_SESSION['usua_predio'] = $usuario;
			$_SESSION['nombre_predio'] = $this->mysqli_result($resppass,0,0);
			$_SESSION['email_predio'] = $this->mysqli_result($resppass,0,1);
			$_SESSION['refroll_predio'] = $this->mysqli_result($resppass,0,3);
			//$error = 'andube por aca'-$sqlusu;
		}
		
	}	else {
		$error = 'Usuario y Password son campos obligatorios';	
	}

}

	return $error;
	
}




function loginUsuario($usuario,$pass) {
	
	$sqlusu = "select * from dbusuarios where email = '".$usuario."'";



if (trim($usuario) != '' and trim($pass) != '') {

	$respusu = $this->query($sqlusu,0);
	
	if (mysqli_num_rows($respusu) > 0) {
		$error = '';
		
		$idUsua = $this->mysqli_result($respusu,0,0);
		$sqlpass = "select concat(apellido,' ',nombre),email,refroles from dbusuarios where password = '".$pass."' and IdUsuario = ".$idUsua;
	
		$resppass = $this->query($sqlpass,0);
		
			if (mysqli_num_rows($resppass) > 0) {
				$error = '';

			} else {
				if ($this->mysqli_result($respusu,0,'activo') == 0) {
					$error = 'El usuario no fue activado, verifique su cuenta de email: '.$usuario;
				} else {
					$error = 'Usuario o Password incorrecto';
				}

			}
		
		}
		else
		
		{
			$error = 'Usuario o Password incorrecto';	
		}
		
		if ($error == '') {
			session_start();
			$_SESSION['usua_predio'] = $usuario;
			$_SESSION['nombre_predio'] = $this->mysqli_result($resppass,0,0);
			$_SESSION['email_predio'] = $this->mysqli_result($resppass,0,1);
			$_SESSION['refroll_predio'] = $this->mysqli_result($resppass,0,2);
		}
	
	
	}	else {
		$error = 'Usuario y Password son campos obligatorios';	
	}
	
	
	return $error;
	
}


function traerRoles() {
	$sql = "select * from tbroles";
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerRolesSimple() {
	$sql = "select * from tbroles where idrol <> 1";
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}


function traerUsuario($email) {
	$sql = "select idusuario,usuario,refroll,nombrecompleto,email,password from se_usuarios where email = '".$email."'";
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerUsuarios() {
	$sql = "select u.idusuario,u.usuario, u.password, r.descripcion, u.email , u.nombrecompleto, u.refroles
			from dbusuarios u
			inner join tbroles r on u.refroles = r.idrol 
			order by nombrecompleto";
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}


function traerUsuariosSimple() {
	$sql = "select u.idusuario,u.usuario, u.password, r.descripcion, u.email , u.nombrecompleto, u.refroles
			from dbusuarios u
			inner join tbroles r on u.refroles = r.idrol 
			where r.idrol <> 1
			order by nombrecompleto";
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerTodosUsuarios() {
	$sql = "select u.idusuario,u.usuario,u.nombrecompleto,u.refroll,u.email,u.password
			from se_usuarios u
			order by nombrecompleto";
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerUsuarioId($id) {
	$sql = "select idusuario,nombre,apellido,refroles,email,password,telefono,(case when activo= 1 then 'Si' else 'No' end) as activo from dbusuarios where idusuario = ".$id;
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function existeUsuario($usuario) {
	$sql = "select * from dbusuarios where email = '".$usuario."'";
	$res = $this->query($sql,0);
	if (mysqli_num_rows($res)>0) {
		return true;	
	} else {
		return false;	
	}
}

/* PARA Activacionusuarios */

function insertarActivacionusuarios($refusuarios,$token,$vigenciadesde,$vigenciahasta) { 
$sql = "insert into dbactivacionusuarios(idactivacionusuario,refusuarios,token,vigenciadesde,vigenciahasta) 
values ('',".$refusuarios.",'".utf8_decode($token)."',now(),ADDDATE(now(), INTERVAL 2 DAY))"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarActivacionusuarios($id,$refusuarios,$token,$vigenciadesde,$vigenciahasta) { 
$sql = "update dbactivacionusuarios 
set 
refusuarios = ".$refusuarios.",token = '".($token)."',vigenciadesde = '".utf8_decode($vigenciadesde)."',vigenciahasta = '".utf8_decode($vigenciahasta)."' 
where idactivacionusuario =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function modificarActivacionusuariosConcretada($token) { 
$sql = "update dbactivacionusuarios 
set 
vigenciadesde = 'NULL',vigenciahasta = 'NULL' 
where token =".$token; 
$res = $this->query($sql,0); 
return $res; 
} 


function modificarActivacionusuariosRenovada($refusuarios,$token,$vigenciadesde,$vigenciahasta) { 
$sql = "update dbactivacionusuarios 
set 
vigenciadesde = now(),vigenciahasta = ADDDATE(now(), INTERVAL 2 DAY),token = '".($token)."'
where refusuarios =".$refusuarios; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarActivacionusuarios($id) { 
$sql = "delete from dbactivacionusuarios where idactivacionusuario =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

function eliminarActivacionusuariosPorUsuario($refusuarios) { 
$sql = "delete from dbactivacionusuarios where refusuarios =".$refusuarios; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerActivacionusuarios() { 
$sql = "select 
a.idactivacionusuario,
a.refusuarios,
a.token,
a.vigenciadesde,
a.vigenciahasta
from dbactivacionusuarios a 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerActivacionusuariosPorId($id) { 
$sql = "select idactivacionusuario,refusuarios,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where idactivacionusuario =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerActivacionusuariosPorToken($token) { 
$sql = "select idactivacionusuario,refusuarios,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where token =".$token; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerActivacionusuariosPorTokenFechas($token) { 
$sql = "select idactivacionusuario,refusuarios,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where token ='".$token."' and now() between vigenciadesde and vigenciahasta "; 
$res = $this->query($sql,0); 
return $res; 
} 

function traerActivacionusuariosPorUsuarioFechas($usuario) { 
$sql = "select idactivacionusuario,refusuarios,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where refusuarios =".$usuario." and now() between vigenciadesde and vigenciahasta "; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbactivacionusuarios*/

function enviarEmail($destinatario,$asunto,$cuerpo) {

	
	# Defina el número de e-mails que desea enviar por periodo. Si es 0, el proceso por lotes
	# se deshabilita y los mensajes son enviados tan rápido como sea posible.
	define("MAILQUEUE_BATCH_SIZE",0);

	//para el envío en formato HTML
	//$headers = "MIME-Version: 1.0\r\n";
	
	// Cabecera que especifica que es un HMTL
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	//dirección del remitente
	$headers .= "From: Crovan <msredhotero@msn.com>\r\n";
	
	//ruta del mensaje desde origen a destino
	$headers .= "Return-path: ".$destinatario."\r\n";
	
	//direcciones que recibirán copia oculta
	$headers .= "Bcc: msredhotero@msn.com\r\n";
	
	mail($destinatario,$asunto,$cuerpo,$headers); 	
}


function insertarUsuarios($email,$password,$nombre,$apellido,$refroles,$activo,$telefono) { 
$sql = "insert into dbusuarios(idusuario,email,password,nombre,apellido,refroles,activo,telefono) 
values ('','".utf8_decode($email)."',
			'".utf8_decode($password)."',
			'".utf8_decode($nombre)."',
			'".utf8_decode($apellido)."',
			".$refroles.",
			".$activo.",
			'".utf8_decode($telefono)."')"; 
	if ($this->existeUsuario($email) == true) {
		return "Ya existe el usuario";	
	}
	$res = $this->query($sql,1);
	if ($res == false) {
		return 'Error al insertar datos';
	} else {
		
		return $res;
	}
}


function modificarUsuarios($id,$email,$password,$nombre,$apellido,$refroles,$activo,$telefono) { 
	$sql = "update dbusuarios 
	set 
		email = '".utf8_decode($email)."',
		password = '".utf8_decode($password)."',
		nombre = '".utf8_decode($nombre)."',
		apellido = '".utf8_decode($apellido)."',
		refroles = ".$refroles.",
		activo = ".$activo.",
		telefono = '".utf8_decode($telefono)."' 
	where idusuario =".$id; 
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al modificar datos';
	} else {
		return '';
	}
}

function activarUsuario($refusuario) {
	$sql = "update dbusuarios 
	set 
		activo = 1
	where idusuario =".$refusuario; 
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al modificar datos';
	} else {
		return '';
	}
}



function mysqli_result($res,$row=0,$col=0){
	    $numrows = mysqli_num_rows($res);
	    if ($numrows && $row <= ($numrows-1) && $row >=0){
	        mysqli_data_seek($res,$row);
	        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
	        if (isset($resrow[$col])){
	            return $resrow[$col];
	        }
	    }
	    return false;
	}

	function query($sql,$accion) {
		
		
		
		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();	
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];
		
		//$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());
		$conex = mysqli_connect($hostname,$username,$password, $database);

		if (!$conex) {
		    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
		    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
		    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}
		//mysql_select_db($database);
		
		$error = 0;
		mysqli_query($conex,"BEGIN");
		$result=mysqli_query($conex,$sql);
		if ($accion && $result) {
			$result = mysqli_insert_id($conex);
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysqli_query($conex,"ROLLBACK");
			return false;
		}
		 else{
			mysqli_query($conex,"COMMIT");
			return $result;
		}

		mysqli_close($conex);
		
	}

}

?>