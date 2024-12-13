<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');
// URL for request GET /messages
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
include("../../dist/funciones/api_ws.php");
date_default_timezone_set('America/Caracas');
$APIurl = 'https://api.chat-api.com/instance377791/';
$token = 'ckp5sibey0i3b1rc';
$instanceId = '377791';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
$fecha = date("Y-m-d");
$chatId = "";
$i=0;
$j=0;

	if($_GET['accion']=="test"){
		$id = mysqli_real_escape_string($con,(strip_tags($_GET["idc"],ENT_QUOTES)));
		$phone = mysqli_real_escape_string($con,(strip_tags($_GET["phone"],ENT_QUOTES)));
		$sql_ = mysqli_query($con, "SELECT * FROM apps_bd_market where id = '$id'");
		if(mysqli_num_rows($sql_)!=0){
			$row = mysqli_fetch_assoc($sql_);
			$body = $row['label'];
			$banner = $row['url'];
			$chatId = $phone."@c.us";
			$respuesta =  sendFile($chatId,$banner,$body);
		   $obj = json_decode($respuesta);
			if($obj->{'sent'}==1){
				header('Content-type: application/json; charset=utf-8');
				echo json_encode(array("response" => "ok","respuesta"=>$obj->{'message'}));
			}else{
				header('Content-type: application/json; charset=utf-8');
						echo json_encode(array("response" => "0"));
			}
		}
	}
	
	if($_GET['accion']=="send"){
		$i=0;
		$id = mysqli_real_escape_string($con,(strip_tags($_GET["idc"],ENT_QUOTES)));
		$sql_ = mysqli_query($con, "SELECT * FROM apps_bd_market where id = '$id'");
		if(mysqli_num_rows($sql_)!=0){
			$row = mysqli_fetch_assoc($sql_);
			$update_c = mysqli_query($con,"UPDATE apps_bd_market set status = 2 where id = '$id'");
			$body = $row['label'];
			$banner = $row['url'];
			$sql = mysqli_query($con,"SELECT * FROM apps_clientes where lenguaje = '".$row['id_bd']."'");	
			echo json_encode(array("response" => "ok","respuesta"=>'Se esta enviando su campaña'));			
			while($row2 = mysqli_fetch_assoc($sql)){
				$i++;
				$primer = substr($row2['telefono'], 0, 1);
				   if($primer!=4){
						 $chatId = $row2['telefono'];
					}else{
						 $chatId = "58".$row2['telefono'];
					}
				$phone = $chatId;
				$chatId = $phone."@c.us";
				//echo mysqli_num_rows($sql);
				$respuesta =  sendFile($chatId,$banner,$body);
				 $obj = json_decode($respuesta);
				/* if($row['id_bd']!=95 && $row['id_bd']!=87){
					if($obj->{'sent'}==1){
					   $insert = mysqli_query($con,"UPDATE apps_bd_envios set status = 3 
					   where phone = '".$row2['phone']."'");
					}else{
					$insert = mysqli_query($con, "delete from apps_bd_envios 
									where phone = '".$row2['phone']."'");
					}
				 }*/
			}
			$update_c = mysqli_query($con,"UPDATE apps_bd_market set status = 3 where id = '$id'");
			//
			
		}else{
			//header('Content-type: application/json; charset=utf-8');
						echo json_encode(array("response" => "0"));
		}
	}

?>