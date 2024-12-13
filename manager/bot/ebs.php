<?php
header('Access-Control-Allow-Origin: *');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("funciones/conexion.php");
include("funciones/cript.php");
include("ebs_script2.php");
$sql_api = mysqli_query($con,"select * from apps_api where status = 1");
$row_api = mysqli_fetch_assoc($sql_api);

$token = $row_api['apikey'];
$instanceId = $row_api['instance'];
$APIurl = 'https://api.chat-api.com/instance'.$instanceId.'/';

$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
$chatId = "";
$bandera = 0;

//$hoy = date("M/d/Y");
$hoy = new DateTime();
$hoy = $hoy->modify('first day of this month');
$hoy = date('d/m/Y');
$fecha = date("Y-m-d");
$decoded = json_decode(file_get_contents('php://input'), true);
    //write parsed JSON-body to the file for debugging
    ob_start();
    var_dump($decoded);
    $input = ob_get_contents();
    ob_end_clean();
	// file_put_contents('input_requests.log','entro y escribio',FILE_APPEND);
    file_put_contents('input_requests.log',$input.PHP_EOL,FILE_APPEND);
if(isset($decoded['messages'])){
	foreach($decoded['messages'] as $message){ // Echo every message
			if($message['fromMe']==false && $message['body']!=""){
				
						$chatId = $message['chatId'];
						$titulo = "";
						$titulo = $message['senderName'];
						$numero = explode("@",$message['chatId']);
						//Validamos la opcion ingresada//
						
						$cuerpo = strtoupper($message['body']); 
						$numero = $numero[0];
						 // buscamos el bannerADS  disponible 
				//MENU AYUDA//
				  $valor = 0;
					$consultaw = consulta_welcome($numero,$message['chatId'],$fecha,$titulo,$cuerpo);
					if($consultaw[0]=="a"){
						$respuesta = sendFile($chatId,$consultaw[3],$consultaw[1]);
						$obj = json_decode($respuesta);
							if($obj->{'sent'}==1){
								
								$sql = mysqli_query($con,"SELECT * FROM apps_clientes where telefono = '".$numero."' ");
								
								if(mysqli_num_rows($sql) != 0 ){  // VALIDAMOS SI EL CLIENTE EXISTE
								  $valor = 0;
								}else{
									$valor = 100;
								}
								
								$update = mysqli_query($con, "INSERT INTO apps_welcome (chat_id,fecha,mensaje,numero,status,id_m) VALUES 
								('".$chatId."','".$fecha."','".$obj->{'message'}."','".$numero."','".$valor."',
								'".$obj->{'id'}."')") or die(mysqli_error());
							}

					}else if($consultaw[0]=="b"){
						$respuesta = sendMessage($chatId,$consultaw[1]);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
								$insert_aux = mysqli_query($con,"INSERT INTO aux_clientes 
							   (chatid ,nombre,cumple,gender,paso,idioma)
								VALUES ('$chatId','0','01/01/1970','1','1','$cuerpo') ");
								$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 2
								WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							
							}
					}else if($consultaw[0]=="c"){
						$respuesta = sendMessage($chatId,$consultaw[1]);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
								$insert_aux = mysqli_query($con,"UPDATE aux_clientes set tipo = '".$cuerpo."', paso = 2
								WHERE chatid = '$chatId' ");
								$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 3
								WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							
							}
					}else if($consultaw[0]=="d"){ // INSERT EL CLIENTE Y DA LA BIENVENIDA
				
								$insert_aux = mysqli_query($con,"UPDATE aux_clientes set nombre = '".strtoupper($cuerpo)."', paso = 3
								WHERE chatid = '$chatId' ");
								
								
								$sql_1 = mysqli_query($con,"SELECT * from aux_clientes where chatid = '$chatId' and paso = 3 ");
								if(mysqli_num_rows($sql_1)!=0){
									$row_i = mysqli_fetch_assoc($sql_1);
									$sqlc2 = mysqli_query($con,"select * from apps_clientes ORDER BY id desc LIMIT 1");
									$rowc2 = mysqli_fetch_assoc($sqlc2);
									$id = $rowc2['id']+1;
									$id = "0000".$id;
									$insert = mysqli_query($con,"INSERT INTO apps_clientes 
											    (codigo,nombres,fecha_nacimiento,sexo,
												telefono,estado,lenguaje,puesto,plan)
												VALUES ('".$id."','".$row_i['nombre']."','".$row_i['cumple']."',
												'".$row_i['gender']."','".$numero."','1',
												'".$row_i['idioma']."','".$row_i['tipo']."',0) ");
									if($insert){
										$respuesta = sendMessage($chatId,$consultaw[1]);
										$obj = json_decode($respuesta);
										if($obj->{'sent'}==1){
											$delete = mysqli_query($con,"DELETE FROM aux_clientes where chatid = '$chatId'");
											$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 0
											WHERE chat_id = '".$chatId."'") or die(mysqli_error());
										}
									}else{
										$mensaje = "_Lo sentimos, ocurrio un *error* registrando tus datos_\n ".
										"_Intenta nuevamente_";
										sendMessage($chatId,$mensaje);
										$delete = mysqli_query($con,"DELETE FROM aux_clientes where chatid = '$chatId'");
									}	
								}
							
						
					}else if($consultaw[0]=="e"){ 	
							
							$respuesta = sendMessage($chatId,$consultaw[1]);
							$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									$insert_tmp = mysqli_query($con,
										"INSERT INTO apps_temp (chatID,idw,status)
										 VALUES ('$chatId','".$consultaw[2]."','1') ");
										 $insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 2"); //envia los servicios
										// sendMessage($chatId,$consultaw[1]);
								}
					}else if($consultaw[0]=="f"){ 	
							
							$respuesta = sendMessage($chatId,$consultaw[1]);
							$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									$update = mysqli_query($con, "UPDATE apps_temp SET  status = 2, serv = '".$cuerpo."' 
										WHERE chatID = '".$chatId."'") or die(mysqli_error());
										 $insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 3"); //envia los servicios
										// sendMessage($chatId,$l);
								}
					}else if($consultaw[0]=="g"){ 	
						if($consultaw[2]==1){
							if($consultaw[3] ==10){
								$mensaje = "🕑 *_Selecciona la hora_*  \n\n".
											$consultaw[1]."\n\n".
											"*_Envia:_*\n".
											"*MF* 👉🏻 Cambiar Fecha \n";
								//echo $mensaje;
								$respuesta = sendMessage($chatId,$mensaje);
								$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 4
									WHERE chat_id = '".$chatId."'") or die(mysqli_error());
									$update = mysqli_query($con, "UPDATE apps_temp SET  status = 5
									WHERE chatID = '".$chatId."'") or die(mysqli_error());
								}
							}else if($consultaw[3] ==11){
								$mensaje = "📋 La Fecha   *".$cuerpo."*\n es mayor al maximo de 30 dias 🗓️:\n\n".
								"_Intenta nuevamente_\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}
							else if($consultaw[3] ==14){
								$mensaje = "📋 La Fecha   *".$cuerpo."* \n no puede ser menor a \n*".$hoy."* 🗓️:\n\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}else if($consultaw[3] ==0){
								$mensaje = "📋 El formato Fecha   *".$cuerpo."* \n No es valido\n\n".
								"_Intenta nuevamente_\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}else if($consultaw[3] ==15){
								$mensaje = "📋 Disculpa no laboramos este dias  \n\n".
								"_Intenta nuevamente_\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}
						}else if($consultaw[2]==2){
							if($consultaw[3] ==10){
								$mensaje = "🕑 *_Selecciona la hora_*  \n\n".
											$consultaw[1]."\n\n".
											"*_Envia:_*\n".
											"*MF* 👉🏻 Cambiar Fecha \n";
								//echo $mensaje;
								$respuesta = sendMessage($chatId,$mensaje);
								$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 4
									WHERE chat_id = '".$chatId."'") or die(mysqli_error());
									$update = mysqli_query($con, "UPDATE apps_temp SET  status = 5
									WHERE chatID = '".$chatId."'") or die(mysqli_error());
								}
							}else if($consultaw[3] ==11){
								$mensaje = "📋 La Fecha   *".$cuerpo."*\n es mayor al maximo de 30 dias 🗓️:\n\n".
								"_Intenta nuevamente_\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}
							else if($consultaw[3] ==14){
								$mensaje = "📋 La Fecha   *".$cuerpo."* \n no puede ser menor a \n*".$hoy."* 🗓️:\n\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}else if($consultaw[3] ==0){
								$mensaje = "📋 El formato Fecha   *".$cuerpo."* \n No es valido\n\n".
								"_Intenta nuevamente_\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}else if($consultaw[3] ==11){
								$mensaje = "📋 Disculpa no laboramos este dias  \n\n".
								"_Intenta nuevamente_\n".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n";
								$respuesta = sendMessage($chatId,$mensaje);
							}
						}
					}else if($consultaw[0]=="h"){
						if($consultaw[2]==1){
							if($consultaw[3]!==0){
								$respuesta = sendMessage($chatId,$consultaw[1]);
								$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									$insert = mysqli_query($con, "delete from apps_welcome 
									WHERE chat_id = '".$chatId."'") or die(mysqli_error());
									$insert = mysqli_query($con, "delete from apps_temp 
									WHERE chatID = '".$chatId."'") or die(mysqli_error());
									
								}
							}else if($consultaw[3] ==15){
								$mensaje = "🚫 No hay disponibilidad ️🕑 para la fecha:\n\n".
								"_Intenta nuevamente otra fecha_".
								"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
								"*_Envia:_*\n".
								"*S*  👉🏻 Cambiar Servicio\n".
								"*L*  👉🏻 Cambiar Localidad\n".
								"*AYUDA ó HELP*  👉🏻 Para más comandos";
								$respuesta = sendMessage($chatId,$mensaje);
							}
							else{
								$mensaje = "🚫 Codigo de Hora ️🕑 *".$cuerpo."*  *INVALIDO*:\n\n".
								"_Intenta nuevamente_".
								"_Ejemplo: 🕑 *1*_ \n\n".
								"*_Envia:_*\n".
								"*MF* 👉🏻 Cambiar Fecha".
								"*S*  👉🏻 Cambiar Servicio\n".
								"*L*  👉🏻 Cambiar Localidad\n".
								"*AYUDA ó HELP*  👉🏻 Para más comandos";
								$respuesta = sendMessage($chatId,$mensaje);
							}
							
						}
					}else if($consultaw[0]=="i"){
							$respuesta = sendMessage($chatId,$consultaw[1]);
								$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 21
									WHERE chat_id = '".$chatId."'") or die(mysqli_error());
									
								}
					}else if($consultaw[0]=="j"){
							$respuesta = sendMessage($chatId,$consultaw[1]);
								$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
									if($consultaw[2]==1){
										$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 31
									    WHERE chat_id = '".$chatId."'") or die(mysqli_error());
									}else{
										$insert = mysqli_query($con, "delete from apps_welcome 
									 WHERE chat_id = '".$chatId."'") or die(mysqli_error());
									}	
								}
					}else if($consultaw[0]=="k"){
						if($consultaw[2]==1){
							$respuesta = sendTax($chatId,$consultaw[1],$consultaw[1]);
							$obj = json_decode($respuesta);
								if($obj->{'sent'}==1){
										$insert = mysqli_query($con, "delete from apps_welcome 
									 WHERE chat_id = '".$chatId."'") or die(mysqli_error());
								}
						}else{
							$respuesta = sendMessage($chatId,$consultaw[1]);
						}
								
					}

			}
	}
}