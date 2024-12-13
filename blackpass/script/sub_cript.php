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
$accion = $_GET['xyz'];
	if($accion=="ws"){
		$phone = mysqli_real_escape_string($con,(strip_tags($_GET["number"],ENT_QUOTES)));
			$url = 'https://api.chat-api.com/instance350141/checkPhone?token=d058euuknbi3rty8&phone='.$phone;
	        $decoded = json_decode(file_get_contents($url), true);
		  
			if($decoded['result']=='exists'){
				header('Content-type: application/json; charset=utf-8');
				echo json_encode(array("response" => "ok"));
			}else{
				header('Content-type: application/json; charset=utf-8');
						echo json_encode(array("response" => "0"));
			}
	}else if($accion=="rol"){
		    $phone = mysqli_real_escape_string($con,(strip_tags($_GET["number"],ENT_QUOTES)));
			$fnac = mysqli_real_escape_string($con,(strip_tags($_GET["fnac"],ENT_QUOTES)));
			$sex = mysqli_real_escape_string($con,(strip_tags($_GET["sex"],ENT_QUOTES)));
			$pais = mysqli_real_escape_string($con,(strip_tags($_GET["pais"],ENT_QUOTES)));
			$name = mysqli_real_escape_string($con,(strip_tags($_GET["name"],ENT_QUOTES)));
			$uid = mysqli_real_escape_string($con,(strip_tags($_GET["uid"],ENT_QUOTES)));
			$passw = mysqli_real_escape_string($con,(strip_tags($_GET["pas"],ENT_QUOTES)));
			if($name=="" || $name==null){
				$name = "Usuario";
			}else{
				$name  = strtoupper($name);
			}
			$fecha = date("Y-m-d");
			
			$passw2 = $encriptar($passw);
			$sql_1 = mysqli_query($con,"SELECT * from apps_clientes where telefono = '$phone'  ");
			if(mysqli_num_rows($sql_1)==0){
				
				$sql_1 = mysqli_query($con,"SELECT codigo from apps_clientes order by codigo DESC ");
				$row_i = mysqli_fetch_assoc($sql_1);
				$cod = $row_i['codigo']+1;
				$insert = mysqli_query($con,"INSERT INTO apps_clientes 
					(codigo,nombres,fecha_nacimiento,sexo,telefono,estado,uid,lenguaje)
						VALUES ('".$cod."','".$name."','".$fnac."',
						'".$sex."','".$phone."','1','".$uid."',1) ");
				
				if($insert){
					$id=mysqli_insert_id($con);
					$insert2 = mysqli_query($con,"INSERT INTO apps_clientes_login
					(user_id, token) VALUES ($id,'".$passw2."')");

					if($insert2){
						$body = saludo().", *$name*\n".
							"Bienvenido a *24hOpen*\n\n".
							"_Gestiona tú cuenta_\n".
							"🙍🏻‍♂️ *Usuario*:  $phone\n\n".
							"🔐 *Clave*:  $passw\n\n";
							$uid = $phone."@us.c";
							$logo="banner_welcome.jpg";
							//$respuesta = sendFile($uid,$logo,$body);
							$respuesta = sendMessage($uid,$body);
							$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									//header('Content-type: application/json; charset=utf-8');
									echo json_encode(array("response" => "ok","respuesta"=>$obj->{'message'}));
								}else{
									header('Content-type: application/json; charset=utf-8');
									echo json_encode(array("response" => "1"));
								}
					}
				}else{
					echo json_encode(array("response" => "2"));
				}
			}else{
				echo json_encode(array("response" => "3"));
			}
	}

?>