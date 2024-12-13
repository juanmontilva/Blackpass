<?php require_once('event.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}




function ObtenerNombreUsuario($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT usuario.usu_login FROM usuario WHERE usuario.usu_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['usu_login'];

mysql_free_result($$row_ConsultaFuncion);



}

function ObtenerNombresUsuarios($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT usuario.usu_nombre FROM usuario WHERE usuario.usu_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['usu_nombre'];

mysql_free_result($$row_ConsultaFuncion);



}
function ObtenerApellidosUsuariospaterno($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT usuario.usu_apepaterno FROM usuario WHERE usuario.usu_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['usu_apepaterno'];

mysql_free_result($$row_ConsultaFuncion);



}
function ObtenerApellidosUsuariosmaterno($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT usuario.usu_apematerno FROM usuario WHERE usuario.usu_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['usu_apematerno'];

mysql_free_result($$row_ConsultaFuncion);



}

function ObtenerimagenUsuarioPortada($identificador)
{

if (is_file("images/usuarios/portada".$identificador.".jpg"))
{
	?>
    <img src="images/usuarios/portada/<?php echo  $identificador; ?>.jpg"  />
    <?php
	}
else
{
?>
    <img src="images/usuarios/default_portada.jpg" />
    <?php
}

}
function ObtenerimagenUsuario2($identificador)
{

if (is_file("images/usuarios/".$identificador.".jpg"))
{
	?>
    <img src="images/usuarios/<?php echo  $identificador; ?>.jpg" width="35" />
    <?php
	}
else{
?>
    <img src="images/default.jpg" width="35" />
    <?php
}

}
function ObtenerfotoUsuario($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT usuario.usu_imagenperfil FROM usuario WHERE usuario.usu_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['usu_imagenperfil'];

mysql_free_result($$row_ConsultaFuncion);



}
function ObtenerportadaUsuario($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT usuario.usu_imagenportada FROM usuario WHERE usuario.usu_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['usu_imagenportada'];

mysql_free_result($$row_ConsultaFuncion);



}
function ObtenerNombreCategoria($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT cat_nombre FROM categoria WHERE cat_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['cat_nombre'];

mysql_free_result($$row_ConsultaFuncion);


}
function ObtenerNombreHabitacion($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT hab_tipohabitacion FROM habitacion WHERE hab_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['hab_tipohabitacion'];

mysql_free_result($$row_ConsultaFuncion);


}
function ObtenerNombreRoles($identificador)
{
global $database_conexion_SICOPA,$conexion_SICOPA;
mysql_select_db($database_conexion_SICOPA, $conexion_SICOPA);
$query_ConsultaFuncion = sprintf("SELECT rol_nombre FROM roles WHERE rol_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$conexion_SICOPA) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['rol_nombre'];

mysql_free_result($$row_ConsultaFuncion);




}
function ObtenerNumeroMesa($identificador)
{
global $database_conexion_SICOPA,$conexion_SICOPA;
mysql_select_db($database_conexion_SICOPA, $conexion_SICOPA);
$query_ConsultaFuncion = sprintf("SELECT mes_numero FROM mesa WHERE mes_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$conexion_SICOPA) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['mes_numero'];

mysql_free_result($$row_ConsultaFuncion);




}

function ObtenerNombreCliente($identificador)
{
global $database_conexion_SICOPA,$conexion_SICOPA;
mysql_select_db($database_conexion_SICOPA, $conexion_SICOPA);
$query_ConsultaFuncion = sprintf("SELECT cli_nombre FROM cliente WHERE cli_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$conexion_SICOPA) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['cli_nombre'];

mysql_free_result($$row_ConsultaFuncion);




}
function ObtenerApellidopat($identificador)
{
global $database_conexion_SICOPA,$conexion_SICOPA;
mysql_select_db($database_conexion_SICOPA, $conexion_SICOPA);
$query_ConsultaFuncion = sprintf("SELECT cli_apellidopat FROM cliente WHERE cli_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$conexion_SICOPA) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['cli_apellidopat'];

mysql_free_result($$row_ConsultaFuncion);




}
function ObtenerApellidomat($identificador)
{
global $database_conexion_SICOPA,$conexion_SICOPA;
mysql_select_db($database_conexion_SICOPA, $conexion_SICOPA);
$query_ConsultaFuncion = sprintf("SELECT cli_apellidomat FROM cliente WHERE cli_codigo = %s", $identificador);

$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$conexion_SICOPA) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['cli_apellidomat'];

mysql_free_result($$row_ConsultaFuncion);




}

 function ObtenerNombreProducto($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT pro_nombre FROM producto WHERE pro_codigo = %s", $identificador);
$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['pro_nombre'];

mysql_free_result($$row_ConsultaFuncion);


}

function ObtenerNombrePrecio($identificador)
{
global $database_hotel,$hotel;
mysql_select_db($database_hotel, $hotel);
$query_ConsultaFuncion = sprintf("SELECT pro_precio FROM producto WHERE pro_codigo = %s", $identificador);
$ConsultaFuncion = mysql_query($query_ConsultaFuncion,$hotel) or die(mysql_error());
$row_ConsultaFuncion = mysql_fetch_assoc($ConsultaFuncion);
$totalRows_ConsultaFuncion =mysql_num_rows($ConsultaFuncion);

return $row_ConsultaFuncion['pro_precio'];

mysql_free_result($$row_ConsultaFuncion);


}


//FORMATEO DE FECHAS
function DateToQuotedMySQLDate($Fecha) 
{ 
$Parte1 = substr($Fecha, 0, 10);
$Parte2 = substr($Fecha, 10, 18);

if ($Parte1<>""){ 
   $trozos=explode("/",$Parte1,3); 
   return $trozos[2]."-".$trozos[1]."-".$trozos[0].$Parte2; } 
else 
   {return "NULL";} 
} 

function MySQLDateToDateHORA($MySQLFecha) 
{ 
if (($MySQLFecha == "") or ($MySQLFecha == "0000-00-00") ) 
    {return "";} 
else 
    {return date("H:i",strtotime($MySQLFecha));} 
} 

function MySQLDateToDateDIA($MySQLFecha) 
{ 
if (($MySQLFecha == "") or ($MySQLFecha == "0000-00-00") ) 
    {return "";} 
else 
    {return date("d/m/Y",strtotime($MySQLFecha));} 
}
 ?>
