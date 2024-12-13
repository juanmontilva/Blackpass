<?php
session_start();

header('Content-type: application/json; charset=utf-8');
// URL for request GET /messages
include("dist/funciones/funciones.php");
include("dist/funciones/api_ws.php");
include("dist/funciones/conexion.php");
include("dist/funciones/cript.php");
include("dist/mail/sendqrmail.php");
include("dist/qr/index.php");
date_default_timezone_set('America/Caracas');
/*$APIurl = 'https://api.chat-api.com/instance221818/';
$token = 'hzsdahpwy9wy4la7';
$instanceId = '215059';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
*/
if (!isset($_SESSION['autenticado']) && isset($_SESSION['uid']) && isset($_SESSION['tc'])  && ($_SESSION['xy'])!=0  ) 
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: sign-in.html?error=ER001&".cadena2());
	//exit;
}else{
$fecha = date("Y-m-d");
$dia = date("Y-m-d H:i:s");
$chatId = "";
$i=0;
$j=0;
$accion="";

$accion = $_GET['xyz'];

if($accion=="p2p"){	
$decoded = json_decode(file_get_contents('php://input'), true);
ob_start();
var_dump($decoded);
$input = ob_get_contents();
//ob_end_clean();
print_r($decoded);	
echo "llegooooooo";	
		 $txtbanco = mysqli_real_escape_string($con,(strip_tags($_GET['txtbanco'],ENT_QUOTES)));
		 $txtmetodo = mysqli_real_escape_string($con,(strip_tags($_GET['metodo'],ENT_QUOTES)));
		 $txttitular = mysqli_real_escape_string($con,(strip_tags($_GET['NROTLF'],ENT_QUOTES)));
		 $txtref = mysqli_real_escape_string($con,(strip_tags($_GET['NROREFERENCIA'],ENT_QUOTES)));
		 $txtmonto = mysqli_real_escape_string($con,(strip_tags($_GET['TOTAL '],ENT_QUOTES)));
		 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
		 $idc = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
		 $result = mysqli_real_escape_string($con,(strip_tags($_GET['MSGDES'],ENT_QUOTES)));
		 $error = mysqli_real_escape_string($con,(strip_tags($_GET['MSGERROR '],ENT_QUOTES)));
		if($error==1000){
		 $fecha = date("Y-m-d");
		 if($txtmetodo==2){
			 $referencia = "CH".cadena();
		 }else{
			 $referencia = $txtref;
		 } 
		 $txtmonto = $txtmonto/35;
		 $idc = $desencriptar($idc);
			$usua_cl = "CL-".$_SESSION['uid'];
			$sql = mysqli_query($con,"select * from apps_clientes where id = '".$idc."' ");
			if(mysqli_num_rows($sql)!=0){
				$sqli = mysqli_query($con,"INSERT INTO apps_pagos 
						(fecha,monto,idc,orden,status,metodo,banco,registrado,
						referencia,pickup,contacto,phone,hora,direccion) VALUES 
						('".$fecha."','".$txtmonto."','".$idc."','".cadena()."','1',
						'".$txtmetodo."','".$txtbanco."','".$usua_cl."',
						'".$referencia."',$pick,
						'".$contac."','".$phone."','".$hora."','".$d2."')");
				
				if($sqli){
						header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok'
							));
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
					));	
				}
							
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
				));	
			}		
		}else{
			header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '3'
				));	
		}
		}

}
?>