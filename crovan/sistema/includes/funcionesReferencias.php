<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Buenos_Aires');

class ServiciosReferencias {

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


///**********  PARA SUBIR ARCHIVOS  ***********************//////////////////////////
	function borrarDirecctorio($dir) {
		array_map('unlink', glob($dir."/*.*"));	
	
	}
	
	function borrarArchivo($id,$archivo) {
		$sql	=	"delete from images where idfoto =".$id;
		
		$res =  unlink("./../archivos/".$archivo);
		if ($res)
		{
			$this->query($sql,0);	
		}
		return $res;
	}
	
	
	function existeArchivo($id,$nombre,$type) {
		$sql		=	"select * from images where refproyecto =".$id." and imagen = '".$nombre."' and type = '".$type."'";
		$resultado  =   $this->query($sql,0);
			   
			   if(mysqli_num_rows($resultado)>0){
	
				   return $this->mysqli_result($resultado,0,0);
	
			   }
	
			   return 0;	
	}
	
	function sanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
 
 
    return $string;
}

function crearDirectorioPrincipal($dir) {
	if (!file_exists($dir)) {
		mkdir($dir, 0777);
	}
}

	function subirArchivo($file,$carpeta,$id) {
		
		
		
		$dir_destino = '../archivos/'.$carpeta.'/'.$id.'/';
		$imagen_subida = $dir_destino . $this->sanear_string(str_replace(' ','',basename($_FILES[$file]['name'])));
		
		$noentrar = '../imagenes/index.php';
		$nuevo_noentrar = '../archivos/'.$carpeta.'/'.$id.'/'.'index.php';
		
		if (!file_exists($dir_destino)) {
			mkdir($dir_destino, 0777);
		}
		
		 
		if(!is_writable($dir_destino)){
			
			echo "no tiene permisos";
			
		}	else	{
			if ($_FILES[$file]['tmp_name'] != '') {
				if(is_uploaded_file($_FILES[$file]['tmp_name'])){
					//la carpeta de libros solo los piso
					if ($carpeta == 'galeria') {
						$this->eliminarFotoPorObjeto($id);
					}
					/*echo "Archivo ". $_FILES['foto']['name'] ." subido con éxtio.\n";
					echo "Mostrar contenido\n";
					echo $imagen_subida;*/
					if (move_uploaded_file($_FILES[$file]['tmp_name'], $imagen_subida)) {
						
						$archivo = $this->sanear_string($_FILES[$file]["name"]);
						$tipoarchivo = $_FILES[$file]["type"];
						
						if ($carpeta == 'galeria') {
							if ($this->existeArchivo($id,$archivo,$tipoarchivo) == 0) {
								$sql	=	"insert into images(idfoto,refproyecto,imagen,type) values ('',".$id.",'".str_replace(' ','',$archivo)."','".$tipoarchivo."')";
								$this->query($sql,1);
							}
						} else {
							$sql = "update dblibros set ruta = '".$dir_destino.$archivo."'";
							$this->query($sql,0);	
						}
						echo "";
						
						copy($noentrar, $nuevo_noentrar);
		
					} else {
						echo "Posible ataque de carga de archivos!\n";
					}
				}else{
					echo "Posible ataque del archivo subido: ";
					echo "nombre del archivo '". $_FILES[$file]['tmp_name'] . "'.";
				}
			}
		}	
	}


	
	function TraerFotosRelacion($id) {
		$sql    =   "select 'galeria',s.idproducto,f.imagen,f.idfoto,f.type
							from dbproductos s
							
							inner
							join images f
							on	s.idproducto = f.refproyecto

							where s.idproducto = ".$id;
		$result =   $this->query($sql, 0);
		return $result;
	}
	
	
	function eliminarFoto($id)
	{
		
		$sql		=	"select concat('galeria','/',s.idproducto,'/',f.imagen) as archivo
							from dbproductos s
							
							inner
							join images f
							on	s.idproducto = f.refproyecto

							where f.idfoto =".$id;
		$resImg		=	$this->query($sql,0);
		
		if (mysqli_num_rows($resImg)>0) {
			$res 		=	$this->borrarArchivo($id,$this->mysqli_result($resImg,0,0));
		} else {
			$res = true;
		}
		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}
	
	function eliminarLibro($id)
	{
		
		$sql		=	"update dblibros set ruta = '' where idlibro =".$id;
		$res		=	$this->query($sql,0);
		
		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}
	
	
	function eliminarFotoPorObjeto($id)
	{
		
		$sql		=	"select concat('galeria','/',s.idproducto,'/',f.imagen) as archivo,f.idfoto
							from dbproductos s
							
							inner
							join images f
							on	s.idproducto = f.refproyecto

							where s.idproducto =".$id;
		$resImg		=	$this->query($sql,0);
		
		if (mysqli_num_rows($resImg)>0) {
			$res 		=	$this->borrarArchivo($this->mysqli_result($resImg,0,1),$this->mysqli_result($resImg,0,0));
		} else {
			$res = true;
		}
		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}

/* fin archivos */

/* para el carrito de compra */
    function agregarCarrito($idProducto, $cantidad, $precioUnit, $idUsuario=null) {
        session_start();
        $error = 0;
        
        $stock = $this->hayStockWeb($idProducto);
        
		if (isset($_SESSION['idProducto_carrito_crovan'])) {
            
            if (in_array($idProducto, $_SESSION['idProducto_carrito_crovan'])) {
                $error = $this->modificarCantidadCarritoSinSession($idProducto, $cantidad, $precioUnit, $idUsuario=null);
            } else {
                
                if ($stock < $cantidad) {
                    $error = '<spam class="glyphicon glyphicon-remove-circle"></spam> Stock Insuficiente';
                } else {
                    
                
                    array_push($_SESSION['idProducto_carrito_crovan'], $idProducto);
                    array_push($_SESSION['cantidad_carrito_crovan'], $cantidad);
                    array_push($_SESSION['precio_carrito_crovan'], $precioUnit);
                    array_push($_SESSION['idUsuario_carrito_crovan'], $idUsuario);
                    $error = 1;
                }
            }
            
        } else {
            
            if ($stock < $cantidad) {
                $error = 'Stock Insuficiente';
            } else {
                $_SESSION['idProducto_carrito_crovan'] = array();
                $_SESSION['cantidad_carrito_crovan'] = array();
                $_SESSION['precio_carrito_crovan'] = array();
                $_SESSION['idUsuario_carrito_crovan'] = array();

                array_push($_SESSION['idProducto_carrito_crovan'], $idProducto);
                array_push($_SESSION['cantidad_carrito_crovan'], $cantidad);
                array_push($_SESSION['precio_carrito_crovan'], $precioUnit);
                array_push($_SESSION['idUsuario_carrito_crovan'], $idUsuario);
                $error = 1;
            }
        }
        
        return $error;
        
    }
    
    
    function devolverCantidadItemsCarrito() {
        @session_start();

        
        
        if (isset($_SESSION['idProducto_carrito_crovan'])) {
            return count($_SESSION['idProducto_carrito_crovan']);
        }
        
        return 0;
    }
    
    function modificarCantidadCarrito($idProducto, $cantidad, $precioUnit, $idUsuario=null) {
        session_start();
        $error = '';
        //busco la posicion en el array
        $posItem = array_search($idProducto, $_SESSION['idProducto_carrito_crovan']);
        
        $_SESSION['cantidad_carrito_crovan'][$posItem] = $cantidad + $_SESSION['cantidad_carrito_crovan'][$posItem];
        
        if ($error == '') {
            return '';
        } else {
            return $error;
        }
    }
    
    
    function modificarCantidadCarritoSinSession($idProducto, $cantidad, $precioUnit, $idUsuario=null) {

        $error = '';
        //busco la posicion en el array
        $posItem = array_search($idProducto, $_SESSION['idProducto_carrito_crovan']);
        
        $stock = $this->hayStockWeb($idProducto);
        
        if ($stock < $cantidad + $_SESSION['cantidad_carrito_crovan'][$posItem]) {
            $error = '<spam class="glyphicon glyphicon-remove-circle"></spam> Stock Insuficiente';
        } else {
            $_SESSION['cantidad_carrito_crovan'][$posItem] = $cantidad + $_SESSION['cantidad_carrito_crovan'][$posItem];
        }
        
        return $error;
    }
    
    
    function eliminarCarrito($idProducto, $cantidad, $precioUnit, $idUsuario=null) {
        session_start();
        
        $error = '';
        
        //busco la posicion en el array
        $posItem = array_search($idProducto, $_SESSION['idProducto_carrito_crovan']);
        
        unset($_SESSION['idProducto_carrito_crovan'][$posItem]);
        unset($_SESSION['cantidad_carrito_crovan'][$posItem]);
        unset($_SESSION['precio_carrito_crovan'][$posItem]);
        unset($_SESSION['idUsuario_carrito_crovan'][$posItem]);
        
        
        if ($error == '') {
            return '';
        } else {
            return $error;
        }
    }
    
    function quitarProductoTienda($idProducto) {
        
        $error = '';
        
        $res = $this->eliminarCarrito($idProducto,'','','');
        
        if ($error == '') {
            return '';
        } else {
            return $error;
        }
    }
    
    function modificarCantidad($idProducto, $cantidad) {
        session_start();
        
        $error = '';
        
        //busco la posicion en el array
        $posItem = array_search($idProducto, $_SESSION['idProducto_carrito_crovan']);
        
        $stock = $this->hayStockWeb($idProducto);
        
        if ($stock < $cantidad + $_SESSION['cantidad_carrito_crovan'][$posItem]) {
            $cantidad = 0;
        } else {
            $_SESSION['cantidad_carrito_crovan'][$posItem] += $cantidad;
        }
        
        if ($error == '') {
            return $cantidad;
        } else {
            return $error;
        }
        
    }
    
    
    function quitarCantidad($idProducto, $cantidad) {
        session_start();
        
        $error = '';
        
        //busco la posicion en el array
        $posItem = array_search($idProducto, $_SESSION['idProducto_carrito_crovan']);
        
        $_SESSION['cantidad_carrito_crovan'][$posItem] -= 1;
        
        if ($error == '') {
            return -1;
        } else {
            return $error;
        }
        
    }
    
    
/* fin carrito de compra */
    
    
    
/* PARA Administrativo */

function yaExiste($anio,$mes) {
	$sql = "select idadministrativo from dbadministrativo where anio = ".$anio." and mes = ".$mes;
	$res	=	$this->query($sql,0);
	if (mysqli_num_rows($res) > 0) {
		return true;
	} else {
		return false;
	}
}

function insertarAdministrativo($importesueldos,$importegastosvarios,$importemercaderia,$importegas,$importeluz,$importetelefono,$importeagua,$importeinmobiliario,$importeimpuestos,$importeautonomos,$importeingresosbrutos,$importeaportes,$importesmunicipal,$importefiestas,$anio,$mes) { 
$sql = "insert into dbadministrativo(idadministrativo,importesueldos,importegastosvarios,importemercaderia,importegas,importeluz,importetelefono,importeagua,importeinmobiliario,importeimpuestos,importeautonomos,importeingresosbrutos,importeaportes,importesmunicipal,importefiestas,anio,mes) 
values ('',".$importesueldos.",".$importegastosvarios.",".$importemercaderia.",".$importegas.",".$importeluz.",".$importetelefono.",".$importeagua.",".$importeinmobiliario.",".$importeimpuestos.",".$importeautonomos.",".$importeingresosbrutos.",".$importeaportes.",".$importesmunicipal.",".$importefiestas.",".$anio.",".$mes.")"; 
	
	if ($this->yaExiste($anio,$mes) == false) {
		$res	=	$this->query($sql,1);
		if ($res == false) {
			return 'Error al insertar datos';
		} else {
			return '';
		}
	} else {
		return 'Ya esta cargado ese año y mes';
	}
} 


function modificarAdministrativo($id,$importesueldos,$importegastosvarios,$importemercaderia,$importegas,$importeluz,$importetelefono,$importeagua,$importeinmobiliario,$importeimpuestos,$importeautonomos,$importeingresosbrutos,$importeaportes,$importesmunicipal,$importefiestas,$anio,$mes) { 
$sql = "update dbadministrativo 
set 
importesueldos = ".$importesueldos.",importegastosvarios = ".$importegastosvarios.",importemercaderia = ".$importemercaderia.",importegas = ".$importegas.",importeluz = ".$importeluz.",importetelefono = ".$importetelefono.",importeagua = ".$importeagua.",importeinmobiliario = ".$importeinmobiliario.",importeimpuestos = ".$importeimpuestos.",importeautonomos = ".$importeautonomos.",importeingresosbrutos = ".$importeingresosbrutos.",importeaportes = ".$importeaportes.",importesmunicipal = ".$importesmunicipal.",importefiestas = ".$importefiestas.",anio = ".$anio.",mes = ".$mes." 
where idadministrativo =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarAdministrativo($id) { 
$sql = "delete from dbadministrativo where idadministrativo =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerAdministrativo() { 
$sql = "select 
a.idadministrativo,
a.importesueldos,
a.importegastosvarios,
a.importemercaderia,
a.importegas,
a.importeluz,
a.importetelefono,
a.importeagua,
a.importeinmobiliario,
a.importeimpuestos,
a.importeautonomos,
a.importeingresosbrutos,
a.importeaportes,
a.importesmunicipal,
a.importefiestas,
a.anio,
a.mes
from dbadministrativo a 
order by anio desc,mes desc"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerAdministrativoPorId($id) { 
$sql = "select idadministrativo,importesueldos,importegastosvarios,importemercaderia,importegas,importeluz,importetelefono,importeagua,importeinmobiliario,importeimpuestos,importeautonomos,importeingresosbrutos,importeaportes,importesmunicipal,importefiestas,anio,mes from dbadministrativo where idadministrativo =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

function traerAdministratoMesDia($anio,$mes) {
	$sql = "select
				idadministrativo,
				importesueldos,
				importegastosvarios,
				importemercaderia,
				importegas,
				importeluz,
				importetelefono,
				importeagua,
				importeinmobiliario,
				importeimpuestos,
				importeautonomos,
				importeingresosbrutos,
				importeaportes,
				importesmunicipal,
				importefiestas,
				anio,
				mes
			from lcdd_administrativo
			where anio = ".$anio." and mes = ".$mes." 
			order by anio desc,mes desc";
	$res	=	$this->query($sql,0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}



/* Fin */
/* /* Fin de la Tabla: dbadministrativo*/



/* PARA Detallepreventas */

function insertarDetallepreventas($refventas,$refproductos,$cantidad,$costo,$precio,$total,$nombre) { 
$sql = "insert into dbdetallepreventas(iddetallepreventa,refventas,refproductos,cantidad,costo,precio,total,nombre) 
values ('',".$refventas.",".$refproductos.",".$cantidad.",".$costo.",".$precio.",".$total.",'".utf8_decode($nombre)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarDetallepreventas($id,$refventas,$refproductos,$cantidad,$costo,$precio,$total,$nombre) { 
$sql = "update dbdetallepreventas 
set 
refventas = ".$refventas.",refproductos = ".$refproductos.",cantidad = ".$cantidad.",costo = ".$costo.",precio = ".$precio.",total = ".$total.",nombre = '".utf8_decode($nombre)."' 
where iddetallepreventa =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarDetallepreventas($id) { 
$sql = "delete from dbdetallepreventas where iddetallepreventa =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

function eliminarDetallepreventasPorVenta($refventas) { 
$sql = "delete from dbdetallepreventas where refventas =".$refventas; 
$res = $this->query($sql,0); 
return $res; 
} 

function traerDetallepreventas() { 
$sql = "select 
d.iddetallepreventa,
d.refventas,
d.refproductos,
d.cantidad,
d.costo,
d.precio,
d.total,
d.nombre
from dbdetallepreventas d 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerDetallepreventasPorId($id) { 
$sql = "select iddetallepreventa,refventas,refproductos,cantidad,costo,precio,total,nombre from dbdetallepreventas where iddetallepreventa =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbdetallepreventas*/


/* PARA Ventasaux */

function insertarVentasaux($reftipopago,$numero,$fecha,$total,$usuario,$cancelado,$refclientes,$descuento) { 
$sql = "insert into dbventasaux(idventaaux,reftipopago,numero,fecha,total,usuario,cancelado,refclientes,descuento) 
values ('',".$reftipopago.",'".utf8_decode($numero)."','".utf8_decode($fecha)."',".$total.",'".utf8_decode($usuario)."',".$cancelado.",".$refclientes.",".$descuento.")"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarVentasaux($id,$reftipopago,$numero,$fecha,$total,$usuario,$cancelado,$refclientes,$descuento) { 
$sql = "update dbventasaux 
set 
reftipopago = ".$reftipopago.",numero = '".utf8_decode($numero)."',fecha = '".utf8_decode($fecha)."',total = ".$total.",usuario = '".utf8_decode($usuario)."',cancelado = ".$cancelado.",refclientes = ".$refclientes.",descuento = ".$descuento." 
where idventaaux =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarVentasaux($id) { 
$sql = "delete from dbventasaux where idventaaux =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerVentasaux() { 
$sql = "select 
v.idventaaux,
v.reftipopago,
v.numero,
v.fecha,
v.total,
v.usuario,
v.cancelado,
v.refclientes,
v.descuento
from dbventasaux v 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerVentasauxPorId($id) { 
$sql = "select idventaaux,reftipopago,numero,fecha,total,usuario,cancelado,refclientes,descuento from dbventasaux where idventaaux =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbventasaux*/

/* PARA Promodetalle */

function insertarPromodetalle($refpromos,$refproductos,$cantidad) { 
$sql = "insert into dbpromodetalle(idpromodetalle,refpromos,refproductos,cantidad) 
values ('',".$refpromos.",".$refproductos.",".$cantidad.")"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarPromodetalle($id,$refpromos,$refproductos,$cantidad) { 
$sql = "update dbpromodetalle 
set 
refpromos = ".$refpromos.",refproductos = ".$refproductos.",cantidad = ".$cantidad." 
where idpromodetalle =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarPromodetalle($id) { 
$sql = "delete from dbpromodetalle where idpromodetalle =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerPromodetalle() { 
$sql = "select 
p.idpromodetalle,
p.refpromos,
p.refproductos,
p.cantidad
from dbpromodetalle p 
inner join dbpromos pro ON pro.idpromo = p.refpromos 
inner join dbproductos pro ON pro.idproducto = p.refproductos 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerPromodetallePorPromo($idPromo) { 
$sql = "select 
p.idpromodetalle,
pro.nombre as promo,
prod.nombre as producto,
p.cantidad,
p.refpromos,
p.refproductos

from dbpromodetalle p 
inner join dbpromos pro ON pro.idpromo = p.refpromos 
inner join dbproductos prod ON prod.idproducto = p.refproductos 
where	pro.idpromo = ".$idPromo."
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 



function traerPromodetallePorId($id) { 
$sql = "select idpromodetalle,refpromos,refproductos,cantidad from dbpromodetalle where idpromodetalle =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbpromodetalle*/


/* PARA Promos */

function insertarPromos($nombre,$descripcion,$vigenciadesde,$vigenciahasta,$descuento) { 
$sql = "insert into dbpromos(idpromo,nombre,descripcion,vigenciadesde,vigenciahasta,descuento) 
values ('','".($nombre)."','".($descripcion)."','".($vigenciadesde)."','".($vigenciahasta)."',".$descuento.")"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarPromos($id,$nombre,$descripcion,$vigenciadesde,$vigenciahasta,$descuento) { 
$sql = "update dbpromos 
set 
nombre = '".($nombre)."',descripcion = '".($descripcion)."',vigenciadesde = '".($vigenciadesde)."',vigenciahasta = '".($vigenciahasta)."',descuento = ".$descuento." 
where idpromo =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarPromos($id) { 
$sql = "delete from dbpromos where idpromo =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerPromos() { 
$sql = "select 
p.idpromo,
p.nombre,
p.descripcion,
p.vigenciadesde,
p.vigenciahasta,
p.descuento
from dbpromos p 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerPromosPorId($id) { 
$sql = "select idpromo,nombre,descripcion,vigenciadesde,vigenciahasta,descuento from dbpromos where idpromo =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbpromos*/


/* PARA Clientes */

function insertarClientes($nombrecompleto,$cuil,$dni,$direccion,$telefono,$email,$observaciones) { 
$sql = "insert into dbclientes(idcliente,nombrecompleto,cuil,dni,direccion,telefono,email,observaciones) 
values ('','".utf8_decode($nombrecompleto)."','".utf8_decode($cuil)."','".utf8_decode($dni)."','".utf8_decode($direccion)."','".utf8_decode($telefono)."','".utf8_decode($email)."','".utf8_decode($observaciones)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarClientes($id,$nombrecompleto,$cuil,$dni,$direccion,$telefono,$email,$observaciones) { 
$sql = "update dbclientes 
set 
nombrecompleto = '".utf8_decode($nombrecompleto)."',cuil = '".utf8_decode($cuil)."',dni = '".utf8_decode($dni)."',direccion = '".utf8_decode($direccion)."',telefono = '".utf8_decode($telefono)."',email = '".utf8_decode($email)."',observaciones = '".utf8_decode($observaciones)."' 
where idcliente =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarClientes($id) { 
$sql = "delete from dbclientes where idcliente =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerClientes() { 
$sql = "select 
c.idcliente,
c.nombrecompleto,
c.cuil,
c.dni,
c.direccion,
c.telefono,
c.email,
c.observaciones
from dbclientes c 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerClientesPorId($id) { 
$sql = "select idcliente,nombrecompleto,cuil,dni,direccion,telefono,email,observaciones from dbclientes where idcliente =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbclientes*/

/* PARA Empleados */

function insertarEmpleados($nombre,$apellido,$sexo,$fechanac,$direccion,$telefono,$celular,$email,$dni,$fechaing,$sueldo,$estado) { 
$sql = "insert into dbempleados(idempleado,nombre,apellido,sexo,fechanac,direccion,telefono,celular,email,dni,fechaing,sueldo,estado) 
values ('','".utf8_decode($nombre)."','".utf8_decode($apellido)."','".utf8_decode($sexo)."','".utf8_decode($fechanac)."','".utf8_decode($direccion)."','".utf8_decode($telefono)."','".utf8_decode($celular)."','".utf8_decode($email)."','".utf8_decode($dni)."','".utf8_decode($fechaing)."',".$sueldo.",'".utf8_decode($estado)."')";
$res = $this->query($sql,1); 
return $res; 
} 


function modificarEmpleados($id,$nombre,$apellido,$sexo,$fechanac,$direccion,$telefono,$celular,$email,$dni,$fechaing,$sueldo,$estado) { 
$sql = "update dbempleados 
set 
nombre = '".utf8_decode($nombre)."',apellido = '".utf8_decode($apellido)."',sexo = '".utf8_decode($sexo)."',fechanac = '".utf8_decode($fechanac)."',direccion = '".utf8_decode($direccion)."',telefono = '".utf8_decode($telefono)."',celular = '".utf8_decode($celular)."',email = '".utf8_decode($email)."',dni = '".utf8_decode($dni)."',fechaing = '".utf8_decode($fechaing)."',sueldo = ".$sueldo.",estado = '".utf8_decode($estado)."' 
where idempleado =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarEmpleados($id) { 
$sql = "delete from dbempleados where idempleado =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEmpleados() { 
$sql = "select 
e.idempleado,
e.nombre,
e.apellido,
e.dni,
e.sexo,
e.fechanac,
e.direccion,
e.telefono,
e.celular,
e.email,
e.fechaing,
e.sueldo,
e.estado
from dbempleados e 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEmpleadosPorId($id) { 
$sql = "select idempleado,nombre,apellido,sexo,fechanac,direccion,telefono,celular,email,dni,fechaing,sueldo,estado from dbempleados where idempleado =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbempleados*/


/* PARA Productos */

function zerofill($valor, $longitud){
 $res = str_pad($valor, $longitud, '0', STR_PAD_LEFT);
 return $res;
}

function generarCodigo() {
	$sql = "select idproducto from dbproductos order by idproducto desc limit 1";
	$res = $this->query($sql,0);
	if (mysqli_num_rows($res)>0) {
		$c = $this->zerofill($this->mysqli_result($res,0,0)+1,6);
		return "PRO".$c;
	}
	return "PRO000001";
}

function existeCodigo($codigo) {
	$sql = "select idproducto from dbproductos where codigo ='".$codigo."'";
	$res = $this->query($sql,0);
	if (mysqli_num_rows($res)>0) {
		return 1;
	}
	return 0;
}

function traerCantidadProductos() {
	$sql = "select count(*) from dbproductos where activo = 1";
	$res = $this->query($sql,0); 
	return $res; 	
}

function traerCantidadClientes() {
	$sql = "select count(*) from dbclientes";
	$res = $this->query($sql,0); 
	return $res; 	
}

function traerCantidadPedidos() {
	$sql = "select count(*) from dbpedidos where refestados in (1,2)";
	$res = $this->query($sql,0); 
	return $res; 	
}

function traerCantidadVentas($fecha) {
	$sql = "select count(*) from dbventas where fecha = '".$fecha."'";
	$res = $this->query($sql,0); 
	return $res; 	
}

function insertarProductos($codigo,$codigobarra,$nombre,$descripcion,$stock,$stockmin,$preciocosto,$precioventa,$preciodescuento,$utilidad,$imagen,$refcategorias,$tipoimagen,$unidades,$refproveedores, $capacidad,$marca) { 
$sql = "insert into dbproductos(idproducto,codigo,codigobarra,nombre,descripcion,stock,stockmin,preciocosto,precioventa,preciodescuento,utilidad,imagen,refcategorias,tipoimagen,unidades, activo,refproveedores, capacidad, marca) 
values ('','".utf8_decode($codigo)."','".utf8_decode($codigobarra)."','".utf8_decode($nombre)."','".utf8_decode($descripcion)."',".($stock=='' ? 0 : $stock).",".($stockmin == '' ? 0 : $stockmin).",".($preciocosto == '' ?  0 : $preciocosto).",".($precioventa == '' ? 0 : $precioventa).",".($preciodescuento == '' ? 0 : $preciodescuento).",".$utilidad.",'".utf8_decode($imagen)."',".$refcategorias.",'".utf8_decode($tipoimagen)."',".($unidades=='' ? 1 : $unidades).",1,".$refproveedores.",'".utf8_decode($capacidad)."', '".utf8_decode($marca)."')"; 
$res = $this->query($sql,1); 

return $res; 
} 


function modificarProductos($id,$codigo,$codigobarra,$nombre,$descripcion,$stock,$stockmin,$preciocosto,$precioventa,$preciodescuento,$utilidad,$imagen,$refcategorias,$tipoimagen,$unidades,$activo,$refproveedores,$capacidad,$marca) { 
$sql = "update dbproductos 
set 
codigo = '".utf8_decode($codigo)."',codigobarra = '".utf8_decode($codigobarra)."',nombre = '".utf8_decode($nombre)."',descripcion = '".utf8_decode($descripcion)."',stock = ".$stock.",stockmin = ".$stockmin.",preciocosto = ".$preciocosto.",precioventa = ".$precioventa.",preciodescuento = ".$preciodescuento.",utilidad = ".$utilidad.",imagen = '".utf8_decode($imagen)."',refcategorias = ".$refcategorias.",tipoimagen = '".utf8_decode($tipoimagen)."', unidades = ".($unidades=='' ? 1 : $unidades).",activo = ".$activo.",refproveedores = ".$refproveedores."  ,capacidad = '".$capacidad."',marca = '".utf8_decode($marca)."'
where idproducto =".$id; 
$res = $this->query($sql,0);

return $res; 
} 

function descontarStock($idProductos, $cantidad) {
	$sql = "update dbproductos set stock = (stock - ".$cantidad.") where idproducto = ".$idProductos." and stock > 0";
	$res = $this->query($sql,0); 
	
	$sqlS = "update dbproductos set stock = 0 where stock < 0";
	$resS = $this->query($sqlS,0); 
	
	$producto = $this->traerProductosPorId($idProductos);
	
	return $this->mysqli_result($producto,0,'nombre'); 
}
    
function hayStockWeb($idProducto, $enCarrito=0) {
    $sql = "select (case when stock - stockmin - ".$enCarrito." < 0 then 0 else stock - stockmin end) as stock from dbproductos where idproducto =".$idProducto;
    
    $res = $this->query($sql,0); 
    
    return $this->mysqli_result($res,0,0);
}

function verificarCarritoProductosStock($lstProductos, $lstCantidad) {
    $falta = 0;
    
    $i=0;
    foreach ($lstProductos as $row) {
        $stock = $this->hayStockWeb($row);
        if ($stock < $lstCantidad[$i]) {
            $falta = 1;
        }
        $i += 1;
    }
    
    return $falta;
}

function sumarStock($idProductos, $cantidad) {
	$sql = "update dbproductos set stock = (stock + ".$cantidad.") where idproducto = ".$idProductos;
	$res = $this->query($sql,0); 
	return $res; 
}


function eliminarProductos($id) { 
$this->eliminarFotoPorObjeto($id);
$sql = "update dbproductos set activo = 0 where idproducto =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductos() { 
$sql = "select 
p.idproducto,
p.codigo,
p.codigobarra,
p.nombre,
p.descripcion,
p.stock,
p.stockmin,
p.precioventa,
p.preciodescuento,
cat.descripcion as categoria,
p.preciocosto,
prov.nombre as proveedor,
p.capacidad,
p.unidades,
p.refcategorias,
p.utilidad,
p.tipoimagen,
p.imagen,
ii.imagen as imagenproducto,
p.marca
from dbproductos p 
inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
left join images ii ON ii.refproyecto = p.idproducto
where p.activo = 1 and cat.activo = 1
order by p.nombre"; 
$res = $this->query($sql,0); 
return $res; 
} 

function traerProductosPorCategoriaWeb($idcategoria) {
	$sql = "select
		p.idproducto, p.nombre, p.descripcion, p.precioventa, concat('sistema/archivos/galeria/',p.idproducto,'/', ii.imagen) as imagenproducto
		from	dbproductos p
		inner
		join	tbcategorias cat
		on		cat.idcategoria = p.refcategorias
		left 
		join 	images ii ON ii.refproyecto = p.idproducto
		where	p.activo = 1 and cat.activo = 1 and p.refcategorias = ".$idcategoria;

	$res = $this->query($sql,0); 
	return $res; 
}


function traerProductosPorCategoriaEspecificaionWeb($categorias, $especificaciones) {
	$sql = "select
		p.idproducto, p.nombre, p.descripcion, p.precioventa, concat('sistema/archivos/galeria/',p.idproducto,'/', ii.imagen) as imagenproducto
		from	dbproductos p
		inner
		join	tbcategorias cat
		on		cat.idcategoria = p.refcategorias
		inner
		join	dbproductoespecificaciones pe
		on		pe.refproductos = p.idproducto
		inner
		join	tbespecificacionesproducto esp
		on		esp.idespecificacionproducto = pe.refespecificacionesproducto
		inner
		join	dbgrupoespecificaciones gr
		on		gr.idgrupoespecificaion = esp.refgrupoespecificaiones
		inner
		join	dbcategoriasespecificacion ce
		on		ce.refcategorias = cat.idcategoria and ce.refgrupoespecificaciones = gr.idgrupoespecificaion
		left 
		join 	images ii ON ii.refproyecto = p.idproducto
		where	p.activo = 1 and cat.activo = 1 and p.refcategorias in (".$categorias.") and esp.idespecificacionproducto in (".$especificaciones.")";

	$res = $this->query($sql,0); 
	return $res; 
}


function traerProductosOrden($Orden) { 

$where = '';
switch ($Orden) {
	case 1:
		$where = "p.precioventa desc";
		break;
	case 2:
		$where = "CAST(p.capacidad AS UNSIGNED)";
		break;
	case 3:
		$where = "p.stock";
		break;	
}

$sql = "select 
p.idproducto,
p.codigo,
p.codigobarra,
p.nombre,
p.descripcion,
p.stock,
p.stockmin,
p.precioventa,
p.preciodescuento,
cat.descripcion as categoria,
p.preciocosto,
prov.nombre as proveedor,
p.capacidad,
p.unidades,
p.refcategorias,
p.utilidad,
p.tipoimagen,
p.imagen,
ii.imagen as imagenproducto,
p.marca
from dbproductos p 
inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
left join images ii ON ii.refproyecto = p.idproducto
where p.activo = 1 and cat.activo = 1
order by ".$where; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductosWebBuscar($busqueda) { 

$sql = "select 
p.idproducto,
p.codigo,
p.codigobarra,
p.nombre,
p.descripcion,
p.stock,
p.stockmin,
p.precioventa,
p.preciodescuento,
cat.descripcion as categoria,
p.preciocosto,
prov.nombre as proveedor,
p.capacidad,
p.unidades,
p.refcategorias,
p.utilidad,
p.tipoimagen,
p.imagen,
ii.imagen as imagenproducto,
p.marca
from dbproductos p 
inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
left join images ii ON ii.refproyecto = p.idproducto
where p.activo = 1 and cat.activo = 1 and p.descripcion like '%".$busqueda."%'"; 
$res = $this->query($sql,0); 
return $res; 
}

function traerProductosPorOrden() { 
$sql = "select 
p.idproducto,
p.nombre,
p.stock as cantidad,
(p.stock / p.unidades) as stock,
p.stockmin,
p.precioventa,
p.preciodescuento,
p.imagen,
cat.descripcion,
p.unidades,
p.refcategorias,

p.utilidad,
p.preciocosto,
prov.nombre as proveedor,
p.tipoimagen,
p.marca
from dbproductos p 
inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
where p.activo = 1 and cat.activo = 1
order by 1
limit 100"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductosPorCategoria($categoria) { 
$sql = "select 
p.idproducto,
p.codigo,
p.codigobarra,
p.nombre,
p.descripcion,
p.stock,
p.stockmin,
p.precioventa,
p.preciodescuento,
p.imagen,
cat.descripcion,
p.unidades,
p.refcategorias,

p.utilidad,
p.preciocosto,
prov.nombre as proveedor,
p.tipoimagen,
p.marca
from dbproductos p 
inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
where p.activo = 1 and cat.idcategoria = ".$categoria." and cat.activo = 1
order by p.nombre"; 
$res = $this->query($sql,0); 
return $res; 
} 

function buscarProductos($tipobusqueda,$busqueda) {
		switch ($tipobusqueda) {
			case '1':
				$sql = "select 
							p.idproducto,
							p.nombre,
							p.codigobarra,
							p.precioventa,
							p.stock,
							p.stockmin,
							p.preciodescuento,
							
							p.imagen,
							cat.descripcion,
							p.unidades,
							p.refcategorias,
							
							p.utilidad,
							p.preciocosto,
							prov.nombre as proveedor,
							p.tipoimagen,
							p.marca
						from dbproductos p 
						inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
						inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
						where p.activo = 1 and p.nombre like '%".$busqueda."%' and cat.activo = 1
						order by p.nombre
						limit 100";
				break;
			case '2':
				$sql = "select 
							p.idproducto,
							p.nombre,
							p.codigobarra,
							p.precioventa,
							p.stock,
							p.stockmin,
							p.preciodescuento,
							
							p.imagen,
							cat.descripcion,
							p.unidades,
							p.refcategorias,
							
							p.utilidad,
							p.preciocosto,
							prov.nombre as proveedor,
							p.tipoimagen,
							p.marca
						from dbproductos p 
						inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
						inner join dbproveedores prov ON prov.idproveedor = p.refproveedores 
						where p.activo = 1 and p.codigobarra = '".$busqueda."' and cat.activo = 1
						order by p.nombre
						limit 100";
				break;
			case '3':
				$sql = "select 
							p.idproducto,
							p.nombre,
							p.codigobarra,
							p.precioventa,
							p.stock,
							p.stockmin,
							p.preciodescuento,
							
							p.imagen,
							cat.descripcion,
							p.unidades,
							p.refcategorias,
							
							p.utilidad,
							p.preciocosto,
							prov.nombre as proveedor,
							p.tipoimagen,
							p.marca
						from dbproductos p 
						inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
						inner join dbproveedores prov ON prov.idproveedor = p.refproveedores
						where p.activo = 1 and p.codigo like '%".$busqueda."%' and cat.activo = 1
						order by p.nombre
						limit 100";
				break;
			case '4':
				$sql = "select 
							p.idproducto,
							p.nombre,
							p.codigobarra,
							p.precioventa,
							p.stock,
							p.stockmin,
							p.preciodescuento,
							
							p.imagen,
							cat.descripcion,
							p.unidades,
							p.refcategorias,
							
							p.utilidad,
							p.preciocosto,
							prov.nombre as proveedor,
							p.tipoimagen,
							p.marca
						from dbproductos p 
						inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
						inner join dbproveedores prov ON prov.idproveedor = p.refproveedores
						where p.activo = 1 and prov.nombre like '%".$busqueda."%' and cat.activo = 1
						order by p.nombre
						limit 100";
				break;
			
		
		}
		return $this->query($sql,0);
	}

function modificarprecios($idProducto, $precio, $porcentaje) {
	if ($precio > 0) {
		$sql	=	"update dbproductos set preciocosto = ".$precio." where idproducto =".$idProducto;
		$res = $this->query($sql,0); 	
	} 
	
	if ($porcentaje > 0) {
		$sql	=	"update dbproductos set precioventa = (preciocosto + (preciocosto * ".$porcentaje." / 100)) where idproducto =".$idProducto;
		$res = $this->query($sql,0); 
		return $res; 	
	} else {
		return '';	
	}
}

function traerProductosFaltantes() { 
$sql = "select 
p.idproducto,
p.nombre,
(p.stockmin - p.stock) + p.stockmin as cantidad,
p.stock,
p.stockmin,
p.preciocosto,
prov.nombre as proveedor,

p.precioventa,
p.preciodescuento,
p.imagen,
cat.descripcion,
p.unidades,
p.refcategorias,
p.utilidad,

p.codigo,
p.codigobarra,
p.descripcion,
p.tipoimagen,
p.marca
from dbproductos p 
inner join tbcategorias cat ON cat.idcategoria = p.refcategorias 
inner join dbproveedores prov ON prov.idproveedor = p.refproveedores
where p.stockmin >= p.stock and p.activo = 1 and cat.activo = 1
order by nombre"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductosPorId($id) { 
$sql = "select idproducto,codigo,codigobarra,nombre,descripcion,stock,stockmin,preciocosto,precioventa,preciodescuento,utilidad,imagen,refcategorias,tipoimagen, (case when activo=1 then '1' else '0' end) as activo,refproveedores,capacidad,marca from dbproductos where idproducto =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductosPorIdWeb($id) { 
$sql = "SELECT 
    p.idproducto,
    p.codigo,
    p.codigobarra,
    p.nombre,
    p.descripcion,
    p.stock,
    p.stockmin,
    p.preciocosto,
    p.precioventa,
    p.preciodescuento,
    p.utilidad,
    concat('sistema/archivos/galeria/',p.idproducto,'/', ii.imagen) as imagenproducto,
    p.refcategorias,
    p.tipoimagen,
    (CASE
        WHEN p.activo = 1 THEN '1'
        ELSE '0'
    END) AS activo,
    p.refproveedores,
    p.capacidad,
    p.marca
FROM
    dbproductos p
left 
		join 	images ii ON ii.refproyecto = p.idproducto
WHERE
    idproducto =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

function traerProductoPorCodigoBarra($id) { 
$sql = "select idproducto,codigo,codigobarra,nombre,descripcion,stock,stockmin,preciocosto,precioventa,preciodescuento,utilidad,imagen,refcategorias,tipoimagen, (case when activo=1 then '1' else '0' end) as activo,refproveedores,capacidad,marca from dbproductos where activo=1 and codigobarra ='".$id."'"; 
$res = $this->query($sql,0); 
return $res; 
}

/* Fin */
/* /* Fin de la Tabla: dbproductos*/


/* PARA Proveedores */

function insertarProveedores($nombre,$cuit,$dni,$direccion,$telefono,$celular,$email,$observacionces) { 
$sql = "insert into dbproveedores(idproveedor,nombre,cuit,dni,direccion,telefono,celular,email,observacionces) 
values ('','".utf8_decode($nombre)."','".utf8_decode($cuit)."','".utf8_decode($dni)."','".utf8_decode($direccion)."','".utf8_decode($telefono)."','".utf8_decode($celular)."','".utf8_decode($email)."','".utf8_decode($observacionces)."')";
$res = $this->query($sql,1); 
return $res; 
} 


function modificarProveedores($id,$nombre,$cuit,$dni,$direccion,$telefono,$celular,$email,$observacionces) { 
$sql = "update dbproveedores 
set 
nombre = '".utf8_decode($nombre)."',cuit = '".utf8_decode($cuit)."',dni = '".utf8_decode($dni)."',direccion = '".utf8_decode($direccion)."',telefono = '".utf8_decode($telefono)."',celular = '".utf8_decode($celular)."',email = '".utf8_decode($email)."',observacionces = '".utf8_decode($observacionces)."' 
where idproveedor =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarProveedores($id) { 
$sql = "delete from dbproveedores where idproveedor =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProveedores() { 
$sql = "select 
p.idproveedor,
p.nombre,
p.cuit,
p.dni,
p.direccion,
p.telefono,
p.celular,
p.email,
p.observacionces
from dbproveedores p 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProveedoresPorId($id) { 
$sql = "select idproveedor,nombre,cuit,dni,direccion,telefono,celular,email,observacionces from dbproveedores where idproveedor =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbproveedores*/



/* PARA Detallepedidoaux */

function insertarDetallepedidoaux($refproductos,$cantidad,$precio,$total) {
$sql = "insert into dbdetallepedidoaux(iddetallepedidoaux,refproductos,cantidad,precio,total)
values ('',".$refproductos.",".$cantidad.",".$precio.",".$total.")";
$res = $this->query($sql,1);
return $res;
}


function modificarDetallepedidoaux($id,$refproductos,$cantidad,$precio,$total) {
$sql = "update dbdetallepedidoaux
set
refproductos = ".$refproductos.",cantidad = ".$cantidad.",precio = ".$precio.",total = ".$total."
where iddetallepedidoaux =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarDetallepedidoaux($id) {
$sql = "delete from dbdetallepedidoaux where iddetallepedidoaux =".$id;
$res = $this->query($sql,0);
return $res;
}

function vaciarDetallepedidoaux() {
$sql = "delete from dbdetallepedidoaux ";
$res = $this->query($sql,0);
return $res;
}


function traerDetallepedidoaux() {
$sql = "select
d.iddetallepedidoaux,
d.refproductos,
p.nombre,
d.cantidad,
p.stock,
p.preciocosto as precio,
d.total,
p.deposito
from dbdetallepedidoaux d
inner
join	dbproductos p
on		p.idproducto = d.refproductos
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerDetallepedidoauxPorId($id) {
$sql = "select iddetallepedidoaux,refproductos,cantidad,precio,total from dbdetallepedidoaux where iddetallepedidoaux =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbdetallepedidoaux*/


/* PARA Pedidos */

function insertarPedidos($fechasolicitud,$fechaentrega,$total,$refestados,$referencia,$observacion) {
$sql = "insert into dbpedidos(idpedido,fechasolicitud,fechaentrega,total,refestados,referencia,observacion)
values ('','".utf8_decode($fechasolicitud)."','".utf8_decode($fechaentrega)."',".$total.",".$refestados.",'".utf8_decode($referencia)."','".utf8_decode($observacion)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarPedidos($id,$fechasolicitud,$fechaentrega,$total,$refestados,$referencia,$observacion) {
$sql = "update dbpedidos
set
fechasolicitud = '".utf8_decode($fechasolicitud)."',fechaentrega = '".utf8_decode($fechaentrega)."',total = ".$total.",refestados = ".$refestados.",referencia = '".utf8_decode($referencia)."',observacion = '".utf8_decode($observacion)."'
where idpedido =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarPedidos($id) {

$sqlDel = "delete from dbdetallepedido where refpedidos =".$id;	
$this->query($sqlDel,0);

$sql = "delete from dbpedidos where idpedido =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerPedidos() {
$sql = "select
p.idpedido,
p.referencia,
p.fechasolicitud,
p.fechaentrega,
p.total,
est.estado,
p.observacion,
p.refestados
from dbpedidos p
inner join tbestados est ON est.idestado = p.refestados
order by 1";
$res = $this->query($sql,0);
return $res;
}

function traerPedidosActivos() {
$sql = "select
p.idpedido,
p.referencia,
p.fechasolicitud,
p.fechaentrega,
p.total,
est.estado,
p.observacion,
p.refestados
from dbpedidos p
inner join tbestados est ON est.idestado = p.refestados
where p.refestados in (1,2)
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerPedidosPorId($id) {
$sql = "select idpedido,fechasolicitud,fechaentrega,total,refestados,referencia,observacion from dbpedidos where idpedido =".$id;
$res = $this->query($sql,0);
return $res;
}

function finalizarPedido($id) {
	$sql = "update dbpedidos set refestados = 3 where idpedido =".$id;
	$res = $this->query($sql,0);
	return $res;	
}

/* Fin */
/* /* Fin de la Tabla: dbpedidos*/

/* PARA Detallepedido */

function insertarDetallepedidoDesdeTemporal($idpedido) {
	$sql	=	"INSERT INTO dbdetallepedido (iddetallepedido,refpedidos,refproductos,cantidad,precio,total,falto)
				  SELECT '', ".$idpedido.", d.refproductos, d.cantidad, p.preciocosto, d.cantidad * p.preciocosto, 0
				  FROM dbdetallepedidoaux  d
					inner
					join	dbproductos p
					on		p.idproducto = d.refproductos;";	
				  
	$res = $this->query($sql,1);
	
	$sqlUp = "update dbpedidos
				set total = (SELECT sum(d.cantidad * p.preciocosto)
				  FROM dbdetallepedidoaux d
					inner
					join	dbproductos p
					on		p.idproducto = d.refproductos)
			  where idpedido = ".$idpedido;
	$res2 = $this->query($sqlUp,0);		  
	
	return $res;			  
}

function insertarDetallepedido($refpedidos,$refproductos,$cantidad,$precio,$total,$falto) {
$sql = "insert into dbdetallepedido(iddetallepedido,refpedidos,refproductos,cantidad,precio,total,falto)
values ('',".$refpedidos.",".$refproductos.",".$cantidad.",".$precio.",".$total.",".$falto.")";
$res = $this->query($sql,1);
return $res;
}


function modificarDetallepedido($id,$refpedidos,$refproductos,$cantidad,$precio,$total,$falto) {
$sql = "update dbdetallepedido
set
refpedidos = ".$refpedidos.",refproductos = ".$refproductos.",cantidad = ".$cantidad.",precio = ".$precio.",total = ".$total.",falto = ".$falto."
where iddetallepedido =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarDetallepedido($id) {
$sql = "delete from dbdetallepedido where iddetallepedido =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerDetallepedido() {
$sql = "select
d.iddetallepedido,
pro.nombre,
d.cantidad,
d.precio,
d.total,
d.falto,
d.refpedidos,
d.refproductos,
from dbdetallepedido d
inner join dbpedidos ped ON ped.idpedido = d.refpedidos
inner join tbestados es ON es.idestado = ped.refestados
inner join dbproductos pro ON pro.idproducto = d.refproductos
inner join tbcategorias ca ON ca.idcategoria = pro.refcategorias
order by 1";
$res = $this->query($sql,0);
return $res;
}

function traerDetallepedidoPorPedido($idPedido) {
$sql = "select
d.iddetallepedido,
pro.nombre,
d.cantidad,
d.precio,
d.total,
d.falto,
d.refpedidos,
d.refproductos,
pro.stock,
ped.fechasolicitud,
ped.fechaentrega,
ped.referencia,
pro.codigo,
es.estado,
es.idestado,
ped.observacion,
pro.deposito
from dbdetallepedido d
inner join dbpedidos ped ON ped.idpedido = d.refpedidos
inner join tbestados es ON es.idestado = ped.refestados
inner join dbproductos pro ON pro.idproducto = d.refproductos
inner join tbcategorias ca ON ca.idcategoria = pro.refcategorias
where	ped.idpedido = ".$idPedido."
order by 1";
$res = $this->query($sql,0);
return $res;
}

function registrarEntradaPorPedidoProducto($iddetallepedido, $cantidad) {
	$sql = "update dbproductos 
				set stock = (stock + ".$cantidad.")
				where idproducto = (select d.refproductos
						from dbdetallepedido d
						inner join dbpedidos ped ON ped.idpedido = d.refpedidos
						where	d.iddetallepedido = ".$iddetallepedido.");";	
	$res = $this->query($sql,0);
	return $sql;
}

function registrarFaltantes($iddetallepedido, $cantidad) {
	$sql = "update dbdetallepedido
				set falto = (cantidad - ".$cantidad.")
						where	iddetallepedido = ".$iddetallepedido;	
	$res = $this->query($sql,0);
	return $res;	
}

function determinarEstado($idpedido) {
	$sql = 'SELECT sum(falto) FROM dbdetallepedido where refpedidos ='.$idpedido;
	$res = $this->query($sql,0);
	if ($this->mysqli_result($res,0,0)== 0) {
		$sqlUpdate = "update dbpedidos
						set refestados = 3
						where	idpedido = ".$idpedido;	
	
	} else {
		$sqlUpdate = "update dbpedidos
						set refestados = 4
						where	idpedido = ".$idpedido;	
	}
	$resUp = $this->query($sqlUpdate,0);
}


function traerDetallepedidoPorId($id) {
$sql = "select iddetallepedido,refpedidos,refproductos,cantidad,precio,total,falto from dbdetallepedido where iddetallepedido =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbdetallepedido*/

/* PARA Usuarios */

function insertarUsuarios($usuario,$password,$refroles,$email,$nombrecompleto) { 
$sql = "insert into dbusuarios(idusuario,usuario,password,refroles,email,nombrecompleto) 
values ('','".utf8_decode($usuario)."','".utf8_decode($password)."',".$refroles.",'".utf8_decode($email)."','".utf8_decode($nombrecompleto)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarUsuarios($id,$usuario,$password,$refroles,$email,$nombrecompleto) { 
$sql = "update dbusuarios 
set 
usuario = '".utf8_decode($usuario)."',password = '".utf8_decode($password)."',refroles = ".$refroles.",email = '".utf8_decode($email)."',nombrecompleto = '".utf8_decode($nombrecompleto)."' 
where idusuario =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarUsuarios($id) { 
$sql = "delete from dbusuarios where idusuario =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerUsuarios() { 
$sql = "select 
u.idusuario,
u.usuario,
u.password,
u.refroles,
u.email,
u.nombrecompleto
from dbusuarios u 
inner join tbroles rol ON rol.idrol = u.refroles 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerUsuariosPorId($id) { 
$sql = "select idusuario,usuario,password,refroles,email,nombrecompleto from dbusuarios where idusuario =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbusuarios*/


/* PARA Categorias */

function insertarCategorias($descripcion,$esegreso,$activo) {
$sql = "insert into tbcategorias(idcategoria,descripcion,esegreso,activo)
values ('','".utf8_decode($descripcion)."',".$esegreso.",".$activo.")";
$res = $this->query($sql,1);
return $res;
}


function modificarCategorias($id,$descripcion,$esegreso,$activo) {
$sql = "update tbcategorias
set
descripcion = '".utf8_decode($descripcion)."',esegreso = ".$esegreso.",activo = ".$activo."
where idcategoria =".$id;
$res = $this->query($sql,0);
return $res;
} 


function eliminarCategorias($id) { 
$sql = "update tbcategorias set activo = 0 where idcategoria =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerCategorias() {
$sql = "select
c.idcategoria,
c.descripcion,
(case when c.esegreso = 1 then 'Si' else 'No' end) as esegreso,
(case when c.activo = 1 then 'Si' else 'No' end) as activo
from tbcategorias c
where c.activo = 1
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerCategoriasPorId($id) {
$sql = "select idcategoria,descripcion,esegreso,(case when activo=1 then 'Si' else 'No' end) as activo from tbcategorias where idcategoria =".$id;
$res = $this->query($sql,0);
return $res;
} 

/* Fin */
/* /* Fin de la Tabla: tbcategorias*/




/* PARA SubCategorias */

function insertarSubCategorias($descripcion,$esegreso,$activo,$refcategorias) {
$sql = "insert into tbsubcategorias(idsubcategoria,descripcion,esegreso,activo,refcategorias)
values ('','".utf8_decode($descripcion)."',".$esegreso.",".$activo.",".$refcategorias.")";
$res = $this->query($sql,1);
return $res;
}


function modificarSubCategorias($id,$descripcion,$esegreso,$activo,$refcategorias) {
$sql = "update tbsubcategorias
set
descripcion = '".utf8_decode($descripcion)."',esegreso = ".$esegreso.",activo = ".$activo.",refcategorias = ".$refcategorias."
where idsubcategoria =".$id;
$res = $this->query($sql,0);
return $res;
} 


function eliminarSubCategorias($id) { 
$sql = "update tbsubcategorias set activo = 0 where idsubcategoria =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerSubCategorias() {
$sql = "select
c.idsubcategoria,
c.descripcion,
(case when c.esegreso = 1 then 'Si' else 'No' end) as esegreso,
(case when c.activo = 1 then 'Si' else 'No' end) as activo,
c.descripcion as categoria,
c.refcategorias
from tbsubcategorias c
inner join tbcategorias cat ON cat.idcategoria = c.refcategorias
where c.activo = 1
order by 1";
$res = $this->query($sql,0);
return $res;
}



function traerSubCategoriasPorId($id) {
$sql = "select idsubcategoria,descripcion,esegreso,(case when activo=1 then 'Si' else 'No' end) as activo,refcategorias from tbsubcategorias where idsubcategoria =".$id;
$res = $this->query($sql,0);
return $res;
} 

/* Fin */
/* /* Fin de la Tabla: tbsubcategorias*/


/* PARA SubsubCategorias */

function insertarSubsubCategorias($descripcion,$esegreso,$activo,$refsubcategorias) {
$sql = "insert into tbsubsubcategorias(idsubsubcategoria,descripcion,esegreso,activo,refsubcategorias)
values ('','".utf8_decode($descripcion)."',".$esegreso.",".$activo.",".$refsubcategorias.")";
$res = $this->query($sql,1);
return $res;
}


function modificarSubsubCategorias($id,$descripcion,$esegreso,$activo,$refsubcategorias) {
$sql = "update tbsubsubcategorias
set
descripcion = '".utf8_decode($descripcion)."',esegreso = ".$esegreso.",activo = ".$activo.",refsubcategorias = ".$refsubcategorias."
where idsubsubcategoria =".$id;
$res = $this->query($sql,0);
return $res;
} 


function eliminarSubsubCategorias($id) { 
$sql = "update tbsubsubcategorias set activo = 0 where idsubsubcategoria =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerSubsubCategorias() {
$sql = "select
c.idsubsubcategoria,
c.descripcion,
(case when c.esegreso = 1 then 'Si' else 'No' end) as esegreso,
(case when c.activo = 1 then 'Si' else 'No' end) as activo,
cats.descripcion as subcategoria,
cat.descripcion as categoria,
c.refsubcategorias,
cats.refcategorias
from tbsubsubcategorias c
inner join tbsubcategorias cats ON cats.idsubcategoria = c.refsubcategorias
inner join tbcategorias cat ON cat.idcategoria = cats.refcategorias
where c.activo = 1
order by 1";
$res = $this->query($sql,0);
return $res;
}



function traerSubsubCategoriasPorId($id) {
$sql = "select idsubsubcategoria,descripcion,esegreso,(case when activo=1 then 'Si' else 'No' end) as activo,refsubcategorias from tbsubsubcategorias where idsubsubcategoria =".$id;
$res = $this->query($sql,0);
return $res;
} 

/* Fin */
/* /* Fin de la Tabla: tbsubcategorias*/


/* PARA Estados */

function insertarEstados($estado,$icono) { 
$sql = "insert into tbestados(idestado,estado,icono) 
values ('','".utf8_decode($estado)."','".utf8_decode($icono)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarEstados($id,$estado,$icono) { 
$sql = "update tbestados 
set 
estado = '".utf8_decode($estado)."',icono = '".utf8_decode($icono)."' 
where idestado =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarEstados($id) { 
$sql = "delete from tbestados where idestado =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEstados() { 
$sql = "select 
e.idestado,
e.estado,
e.icono
from tbestados e 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEstadosPorId($id) { 
$sql = "select idestado,estado,icono from tbestados where idestado =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: tbestados*/



/* PARA Tipopago */

function insertarTipopago($descripcion) { 
$sql = "insert into tbtipopago(idtipopago,descripcion) 
values ('','".utf8_decode($descripcion)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarTipopago($id,$descripcion) { 
$sql = "update tbtipopago 
set 
descripcion = '".utf8_decode($descripcion)."' 
where idtipopago =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarTipopago($id) { 
$sql = "delete from tbtipopago where idtipopago =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerTipopago() { 
$sql = "select 
t.idtipopago,
t.descripcion
from tbtipopago t 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerTipopagoPorId($id) { 
$sql = "select idtipopago,descripcion from tbtipopago where idtipopago =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: tbtipopago*/

function estadosFingidos() {
$sql = "SELECT 'Activo' as estado
union all
select 'Inactivo' as estado";
	$res = $this->query($sql,0); 
return $res; 
}


/* PARA Predio_menu */

function insertarPredio_menu($url,$icono,$nombre,$Orden,$hover,$permiso) {
$sql = "insert into predio_menu(idmenu,url,icono,nombre,Orden,hover,permiso)
values ('','".utf8_decode($url)."','".utf8_decode($icono)."','".utf8_decode($nombre)."',".$Orden.",'".utf8_decode($hover)."','".utf8_decode($permiso)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarPredio_menu($id,$url,$icono,$nombre,$Orden,$hover,$permiso) {
$sql = "update predio_menu
set
url = '".utf8_decode($url)."',icono = '".utf8_decode($icono)."',nombre = '".utf8_decode($nombre)."',Orden = ".$Orden.",hover = '".utf8_decode($hover)."',permiso = '".utf8_decode($permiso)."'
where idmenu =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarPredio_menu($id) {
$sql = "delete from predio_menu where idmenu =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerPredio_menu() {
$sql = "select
p.idmenu,
p.url,
p.icono,
p.nombre,
p.Orden,
p.hover,
p.permiso
from predio_menu p
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerPredio_menuPorId($id) {
$sql = "select idmenu,url,icono,nombre,Orden,hover,permiso from predio_menu where idmenu =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: predio_menu*/



/* PARA Roles */

function insertarRoles($descripcion,$activo) {
$sql = "insert into tbroles(idrol,descripcion,activo)
values ('','".utf8_decode($descripcion)."',".$activo.")";
$res = $this->query($sql,1);
return $res;
}


function modificarRoles($id,$descripcion,$activo) {
$sql = "update tbroles
set
descripcion = '".utf8_decode($descripcion)."',activo = ".$activo."
where idrol =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarRoles($id) {
$sql = "delete from tbroles where idrol =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerRoles() {
$sql = "select
r.idrol,
r.descripcion,
r.activo
from tbroles r
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerRolesPorId($id) {
$sql = "select idrol,descripcion,activo from tbroles where idrol =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbroles*/




/* Para la parte de Ventas   **************************** VENTAS  ************************************* */
/* PARA Ventas */

function generarNroVenta() {
	$sql = "select max(idventa) as id from dbventas";	
	$res = $this->query($sql,0);
	
	if (mysqli_num_rows($res)>0) {
		$nro = 'CC'.str_pad($this->mysqli_result($res,0,0)+1, 8, "0", STR_PAD_LEFT);
	} else {
		$nro = 'CC00000001';
	}
	
	return $nro;
}



/* PARA Detalleventas */

function insertarDetalleventas($refventas,$refproductos,$cantidad,$costo,$precio,$total,$nombre) {
    $sql = "insert into dbdetalleventas(iddetalleventa,refventas,refproductos,cantidad,costo,precio,total,nombre)
    values ('',".$refventas.",".$refproductos.",".$cantidad.",".$costo.",".$precio.",".$total.",'".utf8_decode($nombre)."')";
    $res = $this->query($sql,1);

    return $res;
}


function modificarDetalleventas($id,$refventas,$refproductos,$cantidad,$costo,$precio,$total,$nombre) {
$sql = "update dbdetalleventas
set
refventas = ".$refventas.",refproductos = ".$refproductos.",cantidad = ".$cantidad.",costo = ".$costo.",precio = ".$precio.",total = ".$total.",nombre = '".utf8_decode($nombre)."'
where iddetalleventa =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarDetalleventas($id) {
$sql = "delete from dbdetalleventas where iddetalleventa =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerDetalleventas() {
$sql = "select
d.iddetalleventa,
d.refventas,
d.refproductos,
d.cantidad,
d.costo,
d.precio,
d.total,
d.nombre
from dbdetalleventas d
inner join dbventas ven ON ven.idventa = d.refventas
inner join tbtipopago ti ON ti.idtipopago = ven.reftipopago
inner join dbproductos pro ON pro.idproducto = d.refproductos
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerDetalleventasPorId($id) {
$sql = "select iddetalleventa,refventas,refproductos,cantidad,costo,precio,total,nombre from dbdetalleventas where iddetalleventa =".$id;
$res = $this->query($sql,0);
return $res;
}

function traerDetalleventasPorVenta($id) {
$sql = "select 
				d.iddetalleventa,
				pro.nombre as producto,
				d.cantidad,
				d.precio,
				d.total,
				d.nombre,
				d.costo,
				d.refventas,
				d.refproductos
	from dbdetalleventas d
	inner join dbventas ven ON ven.idventa = d.refventas
	inner join tbtipopago ti ON ti.idtipopago = ven.reftipopago
	inner join dbproductos pro ON pro.idproducto = d.refproductos
 where refventas =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerCarritoPorVenta($id) {
$sql = "select 
				d.iddetallepreventa,
				pro.nombre as producto,
				d.cantidad,
				d.precio,
				d.total,
				d.nombre,
				d.costo,
				d.refventas,
				d.refproductos,
				pro.preciodescuento
	from dbdetallepreventas d
	inner join dbventasaux ven ON ven.idventaaux = d.refventas
	inner join tbtipopago ti ON ti.idtipopago = ven.reftipopago
	inner join dbproductos pro ON pro.idproducto = d.refproductos
 where refventas =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbdetalleventas*/
/* Fin */
/* PARA Ventas */

function insertarVentas($reftipopago,$numero,$fecha,$total,$usuario,$cancelado,$refusuarios,$descuento,$refestados,$idmercadopago) { 
$sql = "insert into dbventas(idventa,reftipopago,numero,fecha,total,usuario,cancelado,refusuarios,descuento,refestados,idmercadopago) 
values ('',".$reftipopago.",'".($numero)."','".utf8_decode($fecha)."',".$total.",'".($usuario)."',".$cancelado.",".$refusuarios.",".$descuento.",".$refestados.",'".($idmercadopago)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarVentas($id,$reftipopago,$numero,$fecha,$total,$usuario,$cancelado,$refusuarios,$descuento,$refestados,$idmercadopago) { 
    $sql = "update dbventas 
                set 
            reftipopago = ".$reftipopago.",numero = '".utf8_decode($numero)."',fecha = '".utf8_decode($fecha)."',total = ".$total.",usuario = '".($usuario)."',cancelado = ".$cancelado.",refusuarios = ".$refusuarios.",descuento = ".$descuento.",refestados = ".$refestados.",idmercadopago = '".($idmercadopago)."' 
            where idventa =".$id; 
    $res = $this->query($sql,0); 
    
    if ($cancelado == 1) {
        $resDetalle = $this->traerDetalleventasPorVenta($id);
        while ($row = mysql_fetch_array($resDetalle)) {
            $this->sumarStock($row['refproductos'],$row['cantidad']); //regreso el stock de la venta
        }
    }
    return $res; 
} 



function eliminarVentas($id) {
$sql = "update dbventas set cancelado = 1 where idventa =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerVentas() {
$sql = "select
v.idventa,
v.reftipopago,
v.numero,
v.fecha,
v.total,
v.usuario,
(case v.cancelado = 0 then 'Si' else 'No' end) as cancelado,
v.refclientes
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerVentasLimit($limite) {
	$sql = "select
v.idventa,

v.numero,
v.fecha,
tip.descripcion,
v.total,
cli.nombrecompleto,
(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
v.reftipopago,
v.usuario,
v.refclientes
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
order by 1
limit ".$limite;
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorDia($fecha) {
	$sql = "select
v.idventa,

v.numero,
v.fecha,
tip.descripcion,
v.total,
cli.nombrecompleto,
(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
v.reftipopago,
v.usuario,
v.refclientes
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
where	fecha = '".$fecha."'
order by 1 desc";
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorDiaPorTipo($fecha, $tipo) {
	switch ($tipo) {
		case 1:
			$sql = "select
			v.idventa,
			v.numero,
			v.fecha,
			tip.descripcion,
			v.total,
			cli.nombrecompleto,
			(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
			v.reftipopago,
			v.usuario,
			v.refclientes,
			dv.nombre,
			dv.cantidad,
			dv.precio,
			dv.total as subtotal
			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	fecha = '".$fecha."' and v.reftipopago = 1 and c.esegreso = 0 and v.cancelado = 0
			order by 1 desc";
			break;
		case 2:
			$sql = "select
			v.idventa,
			v.numero,
			v.fecha,
			tip.descripcion,
			v.total,
			cli.nombrecompleto,
			(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
			v.reftipopago,
			v.usuario,
			v.refclientes,
			dv.nombre,
			dv.cantidad,
			dv.precio,
			dv.total as subtotal
			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	fecha = '".$fecha."' and v.reftipopago in (2,3,4,5) and c.esegreso = 0 and v.cancelado = 0
			order by 1 desc";
			break;
		case 3:
			$sql = "select
			v.idventa,
			v.numero,
			v.fecha,
			tip.descripcion,
			v.total,
			cli.nombrecompleto,
			(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
			v.reftipopago,
			v.usuario,
			v.refclientes,
			dv.nombre,
			dv.cantidad,
			dv.precio,
			dv.total as subtotal
			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	fecha = '".$fecha."' and c.esegreso = 1 and v.cancelado = 0
			order by 1 desc";
			break;		
	}
	
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorDiaPorTipoTotales($fecha, $tipo) {
	switch ($tipo) {
		case 1:
			$sql = "select
				sum(dv.total) as total
			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	fecha = '".$fecha."' and v.reftipopago = 1 and c.esegreso = 0 and v.cancelado = 0";
			break;
		case 2:
			$sql = "select
				sum(dv.total) as total
			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	fecha = '".$fecha."' and v.reftipopago in (2,3,4,5) and c.esegreso = 0 and v.cancelado = 0";
			break;
		case 3:
			$sql = "select
				sum(dv.total) as total
			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	fecha = '".$fecha."' and c.esegreso = 1 and v.cancelado = 0";
			break;		
	}
	
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorMesTipo($fecha) {
	$sql = "select
v.idventa,

v.numero,
v.fecha,
tip.descripcion,
v.total,
cli.nombrecompleto,
(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
v.reftipopago,
v.usuario,
v.refclientes
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
where	fecha = '".$fecha."'
order by 1 desc";
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorAno($anio) {
		$sql = "select
			m.mes as mes,
			m.nombremes as nombremes,
			coalesce( v.total,0) as total
			from tbmeses m
			left join (select sum(ve.total) as total,month(ve.fecha) as mes
						from dbventas ve
						where year(ve.fecha)=".$anio." and ve.cancelado = 0 
						group by month(ve.fecha)
					  ) v on v.mes = m.mes
			order by m.mes";
	$res = $this->query($sql,0);
	return $res;
}

function graficosProductosConsumo($anio) {


	$sql = "select
			
				p.refcategorias, c.descripcion, coalesce(count(c.idcategoria),0)
		
					from dbventas v
					inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
					inner join dbclientes cli ON cli.idcliente = v.refclientes
					inner join dbdetalleventas dv ON v.idventa = dv.refventas
					inner join dbproductos p ON p.idproducto = dv.refproductos
					inner join tbcategorias c ON c.idcategoria = p.refcategorias
					where	year(v.fecha) = ".$anio." and c.esegreso = 0 and v.cancelado = 0
			group by p.refcategorias, c.descripcion
			";
			
	$sqlT = "select
			
				coalesce(count(p.idproducto),0)

			from dbventas v
			inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
			inner join dbclientes cli ON cli.idcliente = v.refclientes
			inner join dbdetalleventas dv ON v.idventa = dv.refventas
			inner join dbproductos p ON p.idproducto = dv.refproductos
			inner join tbcategorias c ON c.idcategoria = p.refcategorias
			where	year(v.fecha) = ".$anio." and c.esegreso = 0 and v.cancelado = 0";
			
	$sqlT2 = "select
					count(*)
				from dbproductos p
				where p.activo = 1
			";

	
	$resT = $this->mysqli_result($this->query($sqlT,0),0,0);
	$resR = $this->query($sql,0);
	
	$cad	= "Morris.Donut({
              element: 'graph2',
              data: [";
	$cadValue = '';
	if ($resT > 0) {
		while ($row = mysql_fetch_array($resR)) {
			$cadValue .= "{value: ".((100 * $row[2])	/ $resT).", label: '".$row[1]."'},";
		}
	}
	

	$cad .= substr($cadValue,0,strlen($cadValue)-1);
    $cad .=          "],
              formatter: function (x) { return x + '%'}
            }).on('click', function(i, row){
              console.log(i, row);
            });";
			
	return $cad;
}



function traerVentasPorDiaTotales($fecha) {
	$sql = "select
	count(*),
	sum(v.total) as total
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
where	fecha = '".$fecha."' and cancelado = 0
order by 1";
$res = $this->query($sql,0);
return $res;
}

function traerVentasPorId($id) {
$sql = "select idventa,reftipopago,numero,fecha,total,usuario,(case when cancelado = 1 then 'Si' else 'No' end) as cancelado,descuento,refclientes from dbventas where idventa =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorClientesACuenta($idCliente) {
	$sql = "select
v.idventa,

v.numero,
v.fecha,
tip.descripcion,
v.total,
cli.nombrecompleto,
(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
v.reftipopago,
v.usuario,
v.refclientes
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
where	v.refclientes = ".$idCliente." and tip.idtipopago = 5
order by 1 desc";
$res = $this->query($sql,0);
return $res;
}


function traerVentasPorClientes($idCliente) {
	$sql = "select
v.idventa,

v.numero,
v.fecha,
tip.descripcion,
v.total,
cli.nombrecompleto,
(case when v.cancelado = 1 then 'Si' else 'No' end) as cancelado,
v.reftipopago,
v.usuario,
v.refclientes
from dbventas v
inner join tbtipopago tip ON tip.idtipopago = v.reftipopago
inner join dbclientes cli ON cli.idcliente = v.refclientes
where	v.refclientes = ".$idCliente." and tip.idtipopago <> 5
order by 1 desc";
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbventas*/


/* PARA Pagos */

function insertarPagos($refclientes,$pago,$fechapago,$observaciones) {
$sql = "insert into dbpagos(idpago,refclientes,pago,fechapago,observaciones)
values ('',".$refclientes.",".$pago.",'".utf8_decode($fechapago)."','".utf8_decode($observaciones)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarPagos($id,$refclientes,$pago,$fechapago,$observaciones) {
$sql = "update dbpagos
set
refclientes = ".$refclientes.",pago = ".$pago.",fechapago = '".utf8_decode($fechapago)."',observaciones = '".utf8_decode($observaciones)."'
where idpago =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarPagos($id) {
$sql = "delete from dbpagos where idpago =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerPagos() {
$sql = "select
p.idpago,
cli.nombrecompleto,
p.pago,
p.fechapago,
p.observaciones,
p.refclientes
from dbpagos p
inner join dbclientes cli ON cli.idcliente = p.refclientes
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerDetallePagosPorCliente($idCliente) {
$sql = "select
p.idpago,
cli.nombrecompleto,
p.pago,
p.fechapago,
p.observaciones,
p.refclientes
from dbpagos p
inner join dbclientes cli ON cli.idcliente = p.refclientes
where cli.idcliente = ".$idCliente."
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerPagosPorCliente($idCliente) {
$sql = "select
(
(select 
    coalesce(sum(v.total), 0) as cuenta
from
    dbventas v
        inner join
    dbclientes cli ON v.refclientes = cli.idcliente
where
    v.reftipopago = 5 and cli.idcliente = ".$idCliente.")
-
(select 
		coalesce(coalesce(sum(p.pago), 0))
	from
		dbpagos p
	where
		p.refclientes = ".$idCliente.")) * -1 as cuenta";
$res = $this->query($sql,0);

	if (mysqli_num_rows($res)>0) {
		return $this->mysqli_result($res,0,0);
	}
	
	return 0;
}


function traerPagosPorId($id) {
$sql = "select idpago,refclientes,pago,fechapago,observaciones from dbpagos where idpago =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbpagos*/




/* PARA Libros */

function insertarLibros($autor,$titulo,$editorial,$genero,$paginas,$edicion,$refclientes) {
$sql = "insert into dblibros(idlibro,autor,titulo,editorial,genero,paginas,edicion,refclientes)
values ('','".utf8_decode($autor)."','".utf8_decode($titulo)."','".utf8_decode($editorial)."','".utf8_decode($genero)."',".($paginas == '' ? 0 : $paginas).",'".utf8_decode($edicion)."',".$refclientes.")";
$res = $this->query($sql,1);
return $res;
}


function modificarLibros($id,$autor,$titulo,$editorial,$genero,$paginas,$edicion,$refclientes) {
$sql = "update dblibros
set
autor = '".utf8_decode($autor)."',titulo = '".utf8_decode($titulo)."',editorial = '".utf8_decode($editorial)."',genero = '".utf8_decode($genero)."',paginas = ".($paginas == '' ? 0 : $paginas).",edicion = '".utf8_decode($edicion)."',refclientes = ".$refclientes."
where idlibro =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarLibros($id) {
$sql = "delete from dblibros where idlibro =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerLibros() {
$sql = "select
l.idlibro,
l.autor,
l.titulo,
l.editorial,
l.genero,
l.paginas,
l.edicion,
cli.nombrecompleto,
l.ruta,
l.refclientes
from dblibros l
inner join dbclientes cli ON cli.idcliente = l.refclientes
order by 1";
$res = $this->query($sql,0);
return $res;
}

function traerLibrosPorCliente($idCliente) {
$sql = "select
l.idlibro,
l.autor,
l.titulo,
l.editorial,
l.genero,
l.paginas,
l.edicion,
cli.nombrecompleto,
l.ruta,
l.refclientes
from dblibros l
inner join dbclientes cli ON cli.idcliente = l.refclientes
where cli.idcliente = ".$idCliente."
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerLibrosPorId($id) {
$sql = "select idlibro,autor,titulo,editorial,genero,paginas,edicion,refclientes, ruta from dblibros where idlibro =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dblibros*/




/* PARA Grupoespecificaiones */

function insertarGrupoespecificaiones($grupo) { 
$sql = "insert into dbgrupoespecificaciones(idgrupoespecificaion,grupo) 
values ('','".utf8_decode($grupo)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarGrupoespecificaiones($id,$grupo) { 
$sql = "update dbgrupoespecificaciones 
set 
grupo = '".utf8_decode($grupo)."' 
where idgrupoespecificaion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarGrupoespecificaiones($id) { 
$sql = "delete from dbgrupoespecificaciones where idgrupoespecificaion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerGrupoespecificaiones() { 
$sql = "select 
g.idgrupoespecificaion,
g.grupo
from dbgrupoespecificaciones g 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerGrupoespecificaionesPorId($id) { 
$sql = "select idgrupoespecificaion,grupo from dbgrupoespecificaciones where idgrupoespecificaion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbgrupoespecificaciones*/


/* PARA Categoriasespecificacion */

function insertarCategoriasespecificacion($refcategorias,$refgrupoespecificaciones) { 
$sql = "insert into dbcategoriasespecificacion(idcategoriaespecificacion,refcategorias,refgrupoespecificaciones) 
values ('',".$refcategorias.",".$refgrupoespecificaciones.")"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarCategoriasespecificacion($id,$refcategorias,$refgrupoespecificaciones) { 
$sql = "update dbcategoriasespecificacion 
set 
refcategorias = ".$refcategorias.",refgrupoespecificaciones = ".$refgrupoespecificaciones." 
where idcategoriaespecificacion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarCategoriasespecificacion($id) { 
$sql = "delete from dbcategoriasespecificacion where idcategoriaespecificacion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarCategoriasespecificacionPorCatgoria($idCategoria) { 
$sql = "delete from dbcategoriasespecificacion where refcategorias =".$idCategoria; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerCategoriasespecificacion() { 
$sql = "select 
c.idcategoriaespecificacion,
c.refcategorias,
c.refgrupoespecificaciones
from dbcategoriasespecificacion c 
inner join tbcategorias cat ON cat.idcategoria = c.refcategorias 
inner join dbgrupoespecificaciones gru ON gru.idgrupoespecificaion = c.refgrupoespecificaciones 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerCategoriasespecificacionPorCategoria($idCategoria) { 
$sql = "select 
c.idcategoriaespecificacion,
c.refcategorias,
c.refgrupoespecificaciones,
gru.grupo
from dbcategoriasespecificacion c 
inner join tbcategorias cat ON cat.idcategoria = c.refcategorias 
inner join dbgrupoespecificaciones gru ON gru.idgrupoespecificaion = c.refgrupoespecificaciones 
where cat.idcategoria = ".$idCategoria."
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerCategoriasespecificacionPorId($id) { 
$sql = "select idcategoriaespecificacion,refcategorias,refgrupoespecificaciones from dbcategoriasespecificacion where idcategoriaespecificacion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbcategoriasespecificacion*/


/* PARA Especificacionesproducto */

function insertarEspecificacionesproducto($refgrupoespecificaiones,$especificacion) { 
$sql = "insert into tbespecificacionesproducto(idespecificacionproducto,refgrupoespecificaiones,especificacion) 
values ('',".$refgrupoespecificaiones.",'".utf8_decode($especificacion)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarEspecificacionesproducto($id,$refgrupoespecificaiones,$especificacion) { 
$sql = "update tbespecificacionesproducto 
set 
refgrupoespecificaiones = ".$refgrupoespecificaiones.",especificacion = '".utf8_decode($especificacion)."' 
where idespecificacionproducto =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarEspecificacionesproducto($id) { 
$sql = "delete from tbespecificacionesproducto where idespecificacionproducto =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEspecificacionesproducto() { 
$sql = "select 
e.idespecificacionproducto,
e.refgrupoespecificaiones,
e.especificacion
from tbespecificacionesproducto e 
inner join dbgrupoespecificaciones gru ON gru.idgrupoespecificaion = e.refgrupoespecificaiones 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEspecificacionesproductoPorGrupo($idGrupo) { 
$sql = "select 
e.idespecificacionproducto,
e.refgrupoespecificaiones,
e.especificacion
from tbespecificacionesproducto e 
inner join dbgrupoespecificaciones gru ON gru.idgrupoespecificaion = e.refgrupoespecificaiones 
where e.refgrupoespecificaiones = ".$idGrupo."
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEspecificacionesproductoPorGrupoCategoria($idGrupo, $idCategoria) { 
$sql = "select 
e.idespecificacionproducto,
e.refgrupoespecificaiones,
e.especificacion
from tbespecificacionesproducto e 
inner join dbgrupoespecificaciones gru ON gru.idgrupoespecificaion = e.refgrupoespecificaiones 
inner join dbcategoriasespecificacion ce ON ce.refgrupoespecificaciones = e.refgrupoespecificaiones
where e.refgrupoespecificaiones = ".$idGrupo." and ce.refcategorias = ".$idCategoria."
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerEspecificacionesproductoPorId($id) { 
$sql = "select idespecificacionproducto,refgrupoespecificaiones,especificacion from tbespecificacionesproducto where idespecificacionproducto =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: tbespecificacionesproducto*/

/* PARA Productoespecificaciones */

function insertarProductoespecificaciones($refproductos,$refespecificacionesproducto,$valor) { 
$sql = "insert into dbproductoespecificaciones(iddbproductoespecificacion,refproductos,refespecificacionesproducto,valor) 
values ('',".$refproductos.",".$refespecificacionesproducto.",'".utf8_decode($valor)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarProductoespecificaciones($id,$refproductos,$refespecificacionesproducto,$valor) { 
$sql = "update dbproductoespecificaciones 
set 
refproductos = ".$refproductos.",refespecificacionesproducto = ".$refespecificacionesproducto.",valor = '".utf8_decode($valor)."' 
where iddbproductoespecificacion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarProductoespecificaciones($id) { 
$sql = "delete from dbproductoespecificaciones where iddbproductoespecificacion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

function eliminarProductoespecificacionesPorProducto($idProducto) { 
$sql = "delete from dbproductoespecificaciones where refproductos =".$idProducto; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductoespecificaciones() { 
$sql = "select 
p.iddbproductoespecificacion,
p.refproductos,
p.refespecificacionesproducto,
p.valor
from dbproductoespecificaciones p 
inner join dbproductos pro ON pro.idproducto = p.refproductos 
inner join tbespecificacionesproducto esp ON esp.idespecificacionproducto = p.refespecificacionesproducto 
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductoespecificacionesPorProducto($idProducto) { 
$sql = "select 
p.iddbproductoespecificacion,
p.refproductos,
p.refespecificacionesproducto,
p.valor
from dbproductoespecificaciones p 
inner join dbproductos pro ON pro.idproducto = p.refproductos 
inner join tbespecificacionesproducto esp ON esp.idespecificacionproducto = p.refespecificacionesproducto 
where p.refproductos = ".$idProducto."
order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerProductoespecificacionesPorId($id) { 
$sql = "select iddbproductoespecificacion,refproductos,refespecificacionesproducto,valor from dbproductoespecificaciones where iddbproductoespecificacion =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */
/* /* Fin de la Tabla: dbproductoespecificaciones*/








/* PARA Audit */

function insertarAudit($tabla,$idtabla,$campo,$previousvalue,$newvalue,$dateupdate,$user,$action) { 
$sql = "insert into audit(idaudit,tabla,idtabla,campo,previousvalue,newvalue,dateupdate,user,action) 
values ('','".utf8_decode($tabla)."',".$idtabla.",'".$campo."','".utf8_decode($previousvalue)."','".utf8_decode($newvalue)."','".utf8_decode($dateupdate)."','".utf8_decode($user)."','".utf8_decode($action)."')"; 
$res = $this->query($sql,1); 
return $res; 
} 


function modificarAudit($id,$tabla,$idtabla,$idmodificado,$previousvalue,$newvalue,$dateupdate,$user,$action) { 
$sql = "update audit 
set 
tabla = '".utf8_decode($tabla)."',idtabla = ".$idtabla.",idmodificado = ".$idmodificado.",previousvalue = '".utf8_decode($previousvalue)."',newvalue = '".utf8_decode($newvalue)."',dateupdate = '".utf8_decode($dateupdate)."',user = '".utf8_decode($user)."',action = '".utf8_decode($action)."' 
where idaudit =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function eliminarAudit($id) { 
$sql = "delete from audit where idaudit =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerAudit() { 
$sql = "select idaudit,tabla,idtabla,idmodificado,previousvalue,newvalue,dateupdate,user,action from audit order by 1"; 
$res = $this->query($sql,0); 
return $res; 
} 


function traerAuditPorId($id) { 
$sql = "select idaudit,tabla,idtabla,idmodificado,previousvalue,newvalue,dateupdate,user,action from audit where idaudit =".$id; 
$res = $this->query($sql,0); 
return $res; 
} 

/* Fin */

/* PARA Cajadiaria */

function insertarCajadiaria($fecha,$inicio,$fin) {
$sql = "insert into tbcajadiaria(idcajadiaria,fecha,inicio,fin)
values ('','".utf8_decode($fecha)."',".$inicio.",".($fin == '' ? 0 : $fin).")";
$res = $this->query($sql,1);
return $res;
}


function modificarCajadiaria($id,$fecha,$inicio,$fin) {
$sql = "update tbcajadiaria
set
fecha = '".utf8_decode($fecha)."',inicio = ".$inicio.",fin = ".($fin == '' ? 0 : $fin)."
where idcajadiaria =".$id;
$res = $this->query($sql,0);
return $id;
}


function eliminarCajadiaria($id) {
$sql = "delete from tbcajadiaria where idcajadiaria =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerCajadiaria() {
$sql = "select
c.idcajadiaria,
c.fecha,
c.inicio,
c.fin
from tbcajadiaria c
order by 1";
$res = $this->query($sql,0);
return $res;
}

function traerCajadiariaPorFecha($fecha) {
$sql = "select
c.idcajadiaria,
c.fecha,
c.inicio,
c.fin
from tbcajadiaria c 
where c.fecha = '".$fecha."'
";
$res = $this->query($sql,0);
return $res;
}


function traerCajadiariaPorId($id) {
$sql = "select idcajadiaria,fecha,inicio,fin from tbcajadiaria where idcajadiaria =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbcajadiaria*/









/* PARA Configuracion */

function insertarConfiguracion($empresa,$cuit,$direccion,$telefono,$email,$localidad,$codigopostal) {
$sql = "insert into tbconfiguracion(idconfiguracion,empresa,cuit,direccion,telefono,email,localidad,codigopostal)
values ('','".utf8_decode($empresa)."','".utf8_decode($cuit)."','".utf8_decode($direccion)."','".utf8_decode($telefono)."','".utf8_decode($email)."','".utf8_decode($localidad)."','".utf8_decode($codigopostal)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarConfiguracion($id,$empresa,$cuit,$direccion,$telefono,$email,$localidad,$codigopostal) {
$sql = "update tbconfiguracion
set
empresa = '".utf8_decode($empresa)."',cuit = '".utf8_decode($cuit)."',direccion = '".utf8_decode($direccion)."',telefono = '".utf8_decode($telefono)."',email = '".utf8_decode($email)."',localidad = '".utf8_decode($localidad)."',codigopostal = '".utf8_decode($codigopostal)."'
where idconfiguracion =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarConfiguracion($id) {
$sql = "delete from tbconfiguracion where idconfiguracion =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerConfiguracion() {
$sql = "select
c.idconfiguracion,
c.empresa,
c.cuit,
c.direccion,
c.telefono,
c.email,
c.localidad,
c.codigopostal
from tbconfiguracion c
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerConfiguracionUltima() {
$sql = "select
c.idconfiguracion,
c.empresa,
c.cuit,
c.direccion,
c.telefono,
c.email,
c.localidad,
c.codigopostal
from tbconfiguracion c
order by 1 desc
limit 1";
$res = $this->query($sql,0);
return $res;
}


function traerConfiguracionPorId($id) {
$sql = "select idconfiguracion,empresa,cuit,direccion,telefono,email,localidad,codigopostal from tbconfiguracion where idconfiguracion =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbconfiguracion*/



/* PARA Autorizacion */

function insertarAutorizacion($token) {
$sql = "insert into tbautorizacion(idautorizacion,token)
values ('','".$token."')";
$res = $this->query($sql,1);
return $res;
}


function modificarAutorizacion($id,$token) {
$sql = "update tbautorizacion
set
token = ".$token."
where idautorizacion =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarAutorizacion($id) {
$sql = "delete from tbautorizacion where idautorizacion =".$id;
$res = $this->query($sql,0);
return $res;
}

function eliminarAutorizacionTodas() {
$sql = "delete from tbautorizacion ";
$res = $this->query($sql,0);
return $res;
}

function eliminarAutorizacionPorToken($token) {
$sql = "delete from tbautorizacion where token ='".$token."'";
$res = $this->query($sql,0);
return $res;
}


function traerAutorizacion() {
$sql = "select
a.idautorizacion,
a.token
from tbautorizacion a
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerAutorizacionPorId($id) {
$sql = "select idautorizacion,token from tbautorizacion where idautorizacion =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerAutorizacionPorToken($token) {
$sql = "select idautorizacion,token from tbautorizacion where token ='".$token."'";
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbautorizacion*/

function query_p($sql,$accion) {
		
		
		
		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();	
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];
		
		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());
		
		mysql_select_db($database);
		
		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
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
			$result = mysql_insert_id();
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