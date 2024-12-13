<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');
// URL for request GET /messages
include("dist/funciones/funciones.php");
include("dist/funciones/api_ws.php");
include("dist/funciones/conexion.php");
include("dist/funciones/cript.php");
date_default_timezone_set('America/Caracas');
/*$APIurl = 'https://api.chat-api.com/instance221818/';
$token = 'hzsdahpwy9wy4la7';
$instanceId = '215059';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
*/
$fecha = date("Y-m-d");
$chatId = "";
$i=0;
$j=0;
$accion = $_POST['xyz'];
	 if($accion=="view"){
		    $uid = mysqli_real_escape_string($con,(strip_tags($_POST["tuser"],ENT_QUOTES)));
			
			$sql_1 = mysqli_query($con,"SELECT * from apps_clientes where telefono = '$uid'  ");
			if(mysqli_num_rows($sql_1)!=0){
				$row_i = mysqli_fetch_assoc($sql_1);
				echo json_encode(array("response" => "ok","pl"=>$row_i['plan'],"token"=>cadena2()));
			}else{
				echo json_encode(array("response" => "2"));
			}
	}

?>