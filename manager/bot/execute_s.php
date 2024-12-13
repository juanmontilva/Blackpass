<?php
header('Access-Control-Allow-Origin: *');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("dist/funciones/conexion.php");
include("24hopen.php");
$APIurl = 'https://api.chat-api.com/instance365604/';
$token = 'djo59yz2q9pxqblp';
$instanceId = '365604';
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
				
				$cuerpo = $message['body']; 
			    $numero = $numero[0];
				$banner = ads($fecha,$numero,$titulo);  // buscamos el bannerADS  disponible 
		//MENU AYUDA//
$sql_c_i = mysqli_query($con,"select * from apps_clientes where telefono = '$numero'");
$sql_c_2 = mysqli_query($con,"select * from apps_clientes where telefono = '$numero'");
$sql_x2 = mysqli_query($con,"SELECT * FROM apps_welcome where chat_id = '".$chatId."' AND status >1");

$rowc2 = "";
$idioma_a = "";
if(mysqli_num_rows($sql_c_2)!=0){
	$rowc2 = mysqli_fetch_assoc($sql_c_2);
	$idioma_a = $rowc2['lenguaje'];
}
$sql_b = mysqli_query($con,"SELECT * FROM apps_reservas_anu where chatid = '$chatId' and status <> 2");
if(mysqli_num_rows($sql_b)!=0){
	$bandera = 1;
	$row_b = mysqli_fetch_assoc($sql_b);
 if($row_b['tipo']==0){ // proceso cancelar cita
	if($row_b['status']==0){
		$respuesta = anular_res($chatId,$cuerpo);
		if($respuesta[0]==100){
			sendMessage($chatId,$respuesta[1]);
		}else if($respuesta[0]==55){
			if($idioma_a==1){
				$mensaje = "🚫 _Por favor selecciona una orden para cancelar_ \n\n".
				$respuesta[1];
			}else if($idioma_a==2){
				$mensaje = "🚫 _Please select an order to cancel_ \n\n".
				$respuesta[1];
			}
			
			
			sendMessage($chatId,$mensaje);
		}
	}if($row_b['status']==1){
		$respuesta = encuesta_anul($chatId,$cuerpo);
		$b1 = "43404369-regresa-pronto-nota-en-titular-de-la-tarjeta.jpg";
		sendFile($chatId,$b1,$respuesta);
	}
 }if($row_b['tipo']==1){  // proceso modificar cita
	if($row_b['status']==0){
		$respuesta = modif_res($chatId,$cuerpo);
		if($respuesta[0]==101){
			sendMessage($chatId,$respuesta[1]);
		}else if($respuesta[0]==56){
			if($idioma_a==1){
				$mensaje = "🚫 _Por favor selecciona una orden para Modificar_ \n\n".
				$respuesta[1];
			}else if($idioma_a==2){
				$mensaje = "🚫 _Please select an order to Modify_ \n\n".
				$respuesta[1];
			}
			
			
			sendMessage($chatId,$mensaje);
		}
	}if($row_b['status']==1){
		$respuesta = cita_modf($chatId,$cuerpo,$numero);
		if($respuesta[0]==102){
			if($idioma_a==1){
			$mensaje = "🕑 *_Selecciona la hora_*  \n\n".
			$respuesta[1];
			}else if($idioma_a==2){
				$mensaje = "🕑 *_Select Time_*  \n\n".
				$respuesta[1];
			}
			
		sendMessage($chatId,$mensaje);
		}else if($respuesta[0] ==10){
			     if($idioma_a==1){
					$mensaje = "📋 La Fecha   *".$cuerpo."*\n es mayor al maximo de 30 dias 🗓️:\n\n".
					"_Intenta nuevamente_\n".
					"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
					"*_Envia:_*\n".
					"*AYUDA ó HELP*  👉🏻 Para más comandos";
					$respuesta = sendMessage($chatId,$mensaje);
				 }else if($idioma_a==2){
					 "_Try again_\n".
					"_Example: 🗓️ *".$hoy."*_ \n\n".
					"*Send:_*\n".
					"*HELP*  👉🏻 For more commands";
					$respuesta = sendMessage($chatId,$mensaje);
				 }
				}
				else if($respuesta[0] ==14){
					if($idioma_a==1){
					$mensaje = "📋 La Fecha   *".$cuerpo."*  no puede ser menor a \n*".$hoy."* 🗓️:\n\n".
					"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
					"*_Envia:_*\n".
					"*AYUDA ó HELP*  👉🏻 Para más comandos";
					$respuesta = sendMessage($chatId,$mensaje);
					}else if($idioma_a==2){
						$mensaje = "📋 The date   *".$cuerpo."*  Cannot be less than \n*".$hoy."* 🗓️:\n\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*HELP*  👉🏻 For more commands";
					}
				}else if($respuesta[0] ==57){
					if($idioma_a==1){
						$mensaje = "📋 El formato Fecha   *".$cuerpo."* \n No es valido\n\n".
						"_Intenta nuevamente_\n".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*_Envia:_*\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}else if($idioma_a==2){
						$mensaje = "📋 The Date formata   *".$cuerpo."* \n It's not valid\n\n".
						"_Try again_\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}
					
				}else if($respuesta[0] ==11){
					if($idioma_a==1){
						$mensaje = "📋 Disculpa no laboramos este dia \n\n".
						"_Intenta nuevamente_\n".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}elseif($idioma_a==2){
						$mensaje = "📋 Sorry we do not work this day \n\n".
						"_Intenta nuevamente_\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}
					
				}
		
		
		
	}else if($row_b['status']==3){
		$respuesta = modif_cita_h($chatId,$cuerpo,$numero);
		sendMessage($chatId,$respuesta[0]);
		//sendMessage($chatId,$respuesta[1]);
		//sendMessage($chatId,$respuesta);
		if($respuesta[0]==106){
			if($idioma_a==1){
				$mensaje = "🚫 Codigo de Hora ️🕑 *".$cuerpo."*  *INVALID*:\n\n".
					"_Intenta nuevamente_\n".
					"_Ejemplo: 🗓️ *1*_ \n\n".
					"*_Envia:_*\n".
					"*AYUDA ó HELP*  👉🏻 Para más comandos";
			}else if($idioma_a==2){
				$mensaje = "🚫 Time Code ️🕑 *".$cuerpo."*  *INVALIDO*:\n\n".
					"_Intenta nuevamente_\n".
					"_Example: 🗓️ *1*_ \n\n".
					"*Send:_*\n".
					"*HELP*  👉🏻 For more comands";
			}
			
					$respuesta = sendMessage($chatId,$mensaje);
		}else if($respuesta[0] ==104){
					$respuesta = sendMessage($chatId,$respuesta[1]);
					$obj = json_decode($respuesta);
					if($obj->{'sent'}==1){
						$insert = mysqli_query($con, "delete from apps_welcome 
						WHERE chat_id = '".$chatId."'") or die(mysqli_error());
						$insert = mysqli_query($con, "delete from apps_temp 
						WHERE chatID = '".$chatId."'") or die(mysqli_error());
						$insert = mysqli_query($con, "delete from aux_anulacion 
						WHERE chatid = '".$chatId."'") or die(mysqli_error());
						$update = mysqli_query($con,"UPDATE apps_reservas_anu SET  status = 2
								where chatid = '$chatId'");
						
					}
				}else if($respuesta ==15){
					if($idioma_a==1){
						$mensaje = "🚫 No hay disponibilidad ️🕑 para la fecha:\n\n".
						"_Intenta nuevamente otra fecha_".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*_Envia:_*\n".
						"*MF*  👉🏻 Cambiar Fecha\n".
						"*S*   👉🏻  Cambiar Servicio\n".
						"*L*   👉🏻  Cambiar Localidad\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
					}else if($idioma_a==2){
						$mensaje = "🚫 No availability ️🕑 to date:\n\n".
						"_Try another date again_".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*MF*  👉🏻  Chage Date\n".
						"*S*   👉🏻  Change Service\n".
						"*L*   👉🏻  Change Location\n".
						"*HELP*  👉🏻 For more comands";
					}
					
					$respuesta = sendMessage($chatId,$mensaje);
				}
	}
	 
 }
}else{
if(strtoupper($cuerpo) =='CC' && mysqli_num_rows($sql_x2)==0){
	$respuesta = validar_cita($chatId,$numero);
	if($respuesta!=""){
		sendMessage($chatId,$respuesta);
	}
	
}else if(strtoupper($cuerpo)=='TRANKS' ||strtoupper($cuerpo)=='GOOD' || strtoupper($cuerpo)=='EXCELENT' || strtoupper($cuerpo)=='BIEN' || strtoupper($cuerpo)=='EXCELENTE' || strtoupper($cuerpo) =='GRACIAS' || strtoupper($cuerpo) == 'TK' ){
	$respuesta = "💋";
	sendMessage($chatId,$respuesta);
	return;
}else if(strtoupper($cuerpo) =='LG'){
	$respuesta = "*Envia / Send*:\n\n".
	             "*ESP*  👉🏻 Español 🇪🇸\n".
				 "*ING*  👉🏻 English 🇺🇸";
		sendMessage($chatId,$respuesta);
}else if(mysqli_num_rows($sql_c_i)!= 0 && ( strtoupper($cuerpo) =='ING' || strtoupper($cuerpo) =='INGLISH' || strtoupper($cuerpo) =='ENGLISH' || strtoupper($cuerpo) =='INGLES')){
	$respuesta = cambiar_idioma($chatId,2,$numero);
	if($respuesta!=""){
		$respuesta = "🇺🇸 *OK!* _We have successfully changed your language_\n".
					"_Write a message to continue_";
		sendMessage($chatId,$respuesta);
		$insert = mysqli_query($con, "delete from apps_welcome 
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$insert = mysqli_query($con, "delete from apps_temp 
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
	}
}else if(mysqli_num_rows($sql_c_i)!= 0 && (strtoupper($cuerpo) =='ESP' || strtoupper($cuerpo) =='ESPAÑOL' || strtoupper($cuerpo) =='ESPANOL' || strtoupper($cuerpo) =='SPANISH')){
	$respuesta = cambiar_idioma($chatId,1,$numero);
	if($respuesta!=""){
		$respuesta = "🇪🇸 *OK!* _He cambiado con exito el idioma_\n".
					 "_Escribe un mensaje para continuar_";
		sendMessage($chatId,$respuesta);
		$insert = mysqli_query($con, "delete from apps_welcome 
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$insert = mysqli_query($con, "delete from apps_temp 
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
	}
}

else if(strtoupper($cuerpo) =='MC' && mysqli_num_rows($sql_x2)==0){
	$respuesta = modif_cita($chatId,$numero);
	if($respuesta!=""){
		sendMessage($chatId,$respuesta);
	}
	
}else if(strtoupper($cuerpo) =='SERVICE' && mysqli_num_rows($sql_x2)==0 || strtoupper($cuerpo) =='SERVICES' || strtoupper($cuerpo) =='SERVICIO' || strtoupper($cuerpo) =='SERVICIOS'){
	$respuesta = ver_servicios($chatId,$numero);
	if($respuesta!=""){
		sendMessage($chatId,$respuesta);
	}
	
}
else if(strtoupper($cuerpo) =='ADS' || strtoupper($cuerpo) =='OFERTAS' || strtoupper($cuerpo) =='OFERTA'){
	$banner = ads($fecha,$numero,$titulo);
	sendFile($chatId,$banner[1],$banner[0]);
}else if(strtoupper($cuerpo) =='AYUDA' || strtoupper($cuerpo) =='HELP'){
		$sql_m = mysqli_query($con,"select * from apps_comercio");
		$row = mysqli_fetch_assoc($sql_m);
		if($idioma_a==1){
			$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo()." *".strtoupper($titulo)."*\n".
								"_Bienvenid@ al menú de ayúda_ \n\n".
								"*LG*			👉🏻	_Cambiar Idioma_ 🌀  \n".
								"*MC*		👉🏻	_Cambiar Cita_  🕟\n".
								"*CC*		👉🏻	_Cancelar Cita_  🚫\n".
								"*ADS*		👉🏻	_Ofertas_ 🏷️\n".
								"Recuerda que el formato de Fechas es:\n".
								"Ejemplo: *dd/mm/yyyy ".date("d/m/Y")."*\n".
								"Enero						👉🏻	Ene\n".
								"Febrero				👉🏻	Feb\n".
								"Marzo					👉🏻	Mar\n".
								"Abril						👉🏻	Abr\n".
								"Mayo						👉🏻	May\n".
								"Junio						👉🏻	Jun\n".
								"Julio						👉🏻	Jul\n".
								"Agosto					👉🏻	Ago\n".
								"Septiembe		👉🏻	Sep\n".
								"Octubre				👉🏻	Oct \n".
								"Noviembre		👉🏻	Nov\n".
								"Diciembre			👉🏻	Dic\n";
		}else if($idioma_a==2){
			$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo2()." *".strtoupper($titulo)."*\n".
								"_Welcome@ Help Menu_ \n\n".
								"*LG*       👉🏻    _Change language_ 🌀  \n".
								"*MC*      👉🏻   _Cambiar Cita_  🕟\n".
								"*CC*       👉🏻   _Cancel Appointment_  🚫\n".
								"*ADS*    👉🏻   _Offers_ 🏷️\n".
								"Remember that the Dates format is:\n".
								"Example: *dd/mm/yyyy ".date("d/m/Y")."*\n".
								"January			👉🏻	Jan\n".
								"February			👉🏻	Feb\n".
								"March				👉🏻	Mar\n".
								"April					👉🏻	Apr\n".
								"Mayo					👉🏻	May\n".
								"Juni						👉🏻	Jun\n".
								"July						👉🏻	Jul\n".
								"August				👉🏻	Aug\n".
								"September	👉🏻	Sep\n".
								"October			👉🏻	Oct\n".
								"November		👉🏻	Nov\n".
								"December		👉🏻	Dec\n";
		}
		
			sendMessage($chatId,$mensaje);
		
	}else if(strtoupper($message['body']) =='MF'){ //MENU CAMBIAR FECHA
		$sql_x = mysqli_query($con,"SELECT * FROM apps_welcome where chat_id = '".$chatId."' AND status = 51");
		if(mysqli_num_rows($sql_x)==1){
			$row_x = mysqli_fetch_assoc($sql_x);
			$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 41
						WHERE chat_id = '".$chatId."'") or die(mysqli_error());
			$update = mysqli_query($con, "UPDATE apps_temp SET  status = 41 
						WHERE chatID = '".$chatId."'") or die(mysqli_error());
					//	sendMessage($chatId,$idioma_a);
			if($idioma_a==1){
				$mensaje = "🗓️ _*Que fecha deseas ser atendido?*_ \n\n".
					"*Ejemplo:* ".date("d/m/Y")."\n\n".
					"*_Envia:_*\n".
					"*S*  👉🏻 Cambiar Servicio\n".
					"*L*  👉🏻 Cambiar Localidad\n".
					"*AYUDA ó HELP*  👉🏻 Para más comandos";
			}else if($idioma_a==2){
				$mensaje = "🗓️ _*What date do you want to be attended??*_ \n\n".
					"*Example:* ".date("d/m/Y")."\n\n".
					"*Send:_*\n".
					"*S*  👉🏻 Change Service\n".
					"*L*  👉🏻 Change Location\n".
					"*HELP*  👉🏻 Para más comandos";
			}
			
			$respuesta = sendMessage($chatId,$mensaje);
		}else{
			if($idioma_a==1){
			$mensaje = "Disculpe, no tiene 🗓️ fecha para modificar";
			}else if($idioma_a==2){
				$mensaje = "Sorry, you don't have 🗓️ date to modify";
			}
		}
		
		//sendMessage($chatId,"hola");
	}else if(strtoupper($message['body']) =='L'){ //MENU CAMBIAR LOCALIDAD
		$insert = mysqli_query($con, "delete from apps_welcome 
						WHERE chat_id = '".$chatId."'") or die(mysqli_error());
		$insert = mysqli_query($con, "delete from apps_temp 
						WHERE chatID = '".$chatId."'") or die(mysqli_error());
		$cuerpo = "Cuerpo Hola";
		$message['body'] = "body hola";
		sleep (0);
		$consultaw = consulta_welcome($numero,$message['chatId'],$fecha,$numero,$banner,$titulo,$cuerpo);
		$respuesta = sendMessage($chatId,$consultaw[0]);
		$obj = json_decode($respuesta);
				if($obj->{'sent'}==1){
					$update = mysqli_query($con, "INSERT INTO apps_welcome (chat_id,fecha,mensaje,numero,status,id_m) VALUES 
					('".$chatId."','".$fecha."','".$obj->{'message'}."','".$numero."','".$obj->{'sent'}."',
					'".$obj->{'id'}."')") or die(mysqli_error());
					
					$idw= mysqli_insert_id($con);
						foreach($consultaw[1] as $l){
							$i++;
							$insert_aux = mysqli_query($con,"INSERT INTO aux_local (idw,localidad,ref)
											VALUES ('$idw','$l','$i') ");
							
						}
						$insert_tmp = mysqli_query($con,"INSERT INTO apps_temp (chatID,idw)
											VALUES ('$chatId','$idw') ");
					
				}
	}else if(strtoupper($message['body']) =='S'){  //MENU CAMBIAR SERVICIO
		
		
				$sql_s = mysqli_query($con,"SELECT local FROM apps_temp
						where chatID = '".$chatId."'");
				if(mysqli_num_rows($sql_s)!=0){
					$row_s = mysqli_fetch_assoc($sql_s);
					$sql_r = mysqli_query($con,"SELECT l.ref FROM  aux_local l, apps_temp t, apps_welcome w  WHERE 
											l.ref = t.local
											and w.id = l.idw
											and t.local = '".$row_s['local']."'
											and w.chat_id = '$chatId'
											GROUP by l.localidad");
					
					if(mysqli_num_rows($sql_r)!=0){
						$row_r = mysqli_fetch_assoc($sql_r);
					}
					$resultado = consulta_s($row_r['ref'],$chatId);
				echo $resultado;
				if($resultado !==0){
					if($idioma_a==1){
					$mensaje = "📋 _Por favor ingresa el *CODIGO* del servicio_: \n\n".
					$resultado."\n\n".
					"_*Envia*_ :\n".
					"*L*  👉🏻 Cambiar Localidad\n".
					"*AYUDA ó HELP*  👉🏻 Para más comandos";
					}else if($idioma_a==2){
						$mensaje = "📋 _Please enter the *CODE* from service_: \n\n".
					$resultado."\n\n".
					"_*Send*_ :\n".
					"*L*  👉🏻 Change Location\n".
					"*HELP*  👉🏻 For more commands";
					}
					$respuesta = sendMessage($chatId,$mensaje);
					$obj = json_decode($respuesta);
					if($obj->{'sent'}==1){
						$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 2
						WHERE chat_id = '".$chatId."'") or die(mysqli_error());
						$update = mysqli_query($con, "UPDATE apps_temp SET  status = 2 
						WHERE chatID = '".$chatId."'") or die(mysqli_error());
					}
				}
				}
				
		
	}else if($message['body']!='' && $message['body']!='S' && $message['body']!='CC' && $message['body']!='MC' && $message['body']!='L' && strtoupper($message['body'])!='MF' && strtoupper($message['body'])!='ADS' && strtoupper($message['body'])!='NOW'){
		$sql_i = mysqli_query($con,"select * from apps_clientes where telefono = '".$numero."' ");
		$row_l = mysqli_fetch_assoc($sql_i);

			//sendMessage($chatId,$numero);
			$consultaw = consulta_welcome($numero,$chatId,$fecha,$numero,$banner,$titulo,$cuerpo);

//echo $consultaw[0];
//$consultaw = explode(",",$consultaw);
if($consultaw[1]==300 && $consultaw[0]!=""){
	$respuesta = sendMessage($chatId,$consultaw[0]);
	$obj = json_decode($respuesta);
	if($obj->{'sent'}==1){
		$insert_aux = mysqli_query($con,"INSERT INTO aux_clientes (chatid ,nombre,cumple,gender,paso,idioma)
								VALUES ('$chatId','0','01/01/1970','1','0','0') ");
	}	
}else if($consultaw[1]==3 && $consultaw[0]!=""){
	$respuesta = sendMessage($chatId,$consultaw[0]);
	$obj = json_decode($respuesta);
	if($obj->{'sent'}==1){
		$idio =0;
		if($cuerpo==2){
			$idio = 2;
			$insert_aux = mysqli_query($con,"UPDATE aux_clientes set idioma = '".$idio."', paso = 1
								WHERE chatid = '$chatId' ");
		}else if($cuerpo==1){
			$idio = 1;
			$insert_aux = mysqli_query($con,"UPDATE aux_clientes set idioma = '".$idio."', paso = 1
								WHERE chatid = '$chatId' ");
		}
		
		
	}
	
}else if($consultaw[1]==4 && $consultaw[0]!=""){
	$respuesta = sendMessage($chatId,$consultaw[0]);
	$obj = json_decode($respuesta);
	if($obj->{'sent'}==1){
		$insert_aux = mysqli_query($con,"UPDATE aux_clientes set nombre = '".strtoupper($cuerpo)."', paso = 2
								WHERE chatid = '$chatId' ");
				
		
	}
	
}
else if($consultaw[1]==6 && $consultaw[0]!=""){
	$respuesta = sendMessage($chatId,$consultaw[0]);
	$obj = json_decode($respuesta);
	if($obj->{'sent'}==1){
		$sexo = "";
		if($cuerpo==1){
			$sexo = "F";
		}else{
			$sexo = "M";
		}  
		$insert_aux = mysqli_query($con,"UPDATE aux_clientes set gender = '$sexo', paso = 3
								WHERE chatid = '$chatId' ");
	}
}else if($consultaw[1] == 5 && $consultaw[0]!=""){
	$respuesta = sendMessage($chatId,$consultaw[0]);	
}
else if($consultaw[1]==7 && $consultaw[0]!=""){
	
		$insert_aux = mysqli_query($con,"UPDATE aux_clientes set cumple = '".$consultaw[2]."', paso = 4
								WHERE chatid = '$chatId' ");
		$sql_1 = mysqli_query($con,"SELECT * from aux_clientes where chatid = '$chatId' and paso = 4 ");
		if(mysqli_num_rows($sql_1)!=0){
			$row_i = mysqli_fetch_assoc($sql_1);
			$phone = explode("@",$chatId);
			$sqlc2 = mysqli_query($con,"select * from apps_clientes ORDER BY id desc LIMIT 1");
			$rowc2 = mysqli_fetch_assoc($sqlc2);
			$id = $rowc['id']+1;
			$id = "0000".$id;
			$insert = mysqli_query($con,"INSERT INTO apps_clientes (codigo,nombres,fecha_nacimiento,sexo,telefono,estado,lenguaje)
						VALUES ('".$id."','".$row_i['nombre']."','".$row_i['cumple']."',
						'".$row_i['gender']."','".$phone[0]."','1','".$row_i['idioma']."') ");
			if($insert){
				$respuesta = sendMessage($chatId,$consultaw[0]);
				$obj = json_decode($respuesta);
				if($obj->{'sent'}==1){
					$delete = mysqli_query($con,"DELETE FROM aux_clientes where chatid = '$chatId'");
				}
			}else{
				$mensaje = "_Lo sentimos, ocurrio un *error* registrando tus datos_\n ".
				"_Intenta nuevamente_";
				sendMessage($chatId,$mensaje);
				$delete = mysqli_query($con,"DELETE FROM aux_clientes where chatid = '$chatId'");
			}	
		}
}else if($consultaw[1] == 8 && $consultaw[0]!=""){
	$respuesta = sendMessage($chatId,$consultaw[0]);	
}

			//echo $consultaw[0];
			//$consultaw = explode(",",$consultaw);
			else if($consultaw[0]!=1 && $consultaw[0]!=2){
				$i = 0;
				$respuesta = sendMessage($chatId,$consultaw[0]);
				$obj = json_decode($respuesta);
				if($obj->{'sent'}==1){
					$update = mysqli_query($con, "INSERT INTO apps_welcome (chat_id,fecha,mensaje,numero,status,id_m) VALUES 
					('".$chatId."','".$fecha."','".$obj->{'message'}."','".$numero."','".$obj->{'sent'}."',
					'".$obj->{'id'}."')") or die(mysqli_error());
					
					$idw= mysqli_insert_id($con);
						foreach($consultaw[1] as $l){
							$i++;
							$insert_aux = mysqli_query($con,"INSERT INTO aux_local (idw,localidad,ref)
											VALUES ('$idw','$l','$i') ");
							
						}
						$insert_tmp = mysqli_query($con,"INSERT INTO apps_temp (chatID,idw)
											VALUES ('$chatId','$idw') ");
					
				}
			
			}else if($consultaw[0]==1 && $consultaw[1]==1){ // consultamos los servicios
				$resultado = consulta_s($cuerpo,$chatId);
				//echo $resultado;
				if($row_l['lenguaje']==1){
				    if($resultado !==0){
						$mensaje = "📋 _Por favor ingresa el *CODIGO* del servicio_: \n\n".
						$resultado."\n\n".
						"_*Envia*_ :\n".
						"*L*  👉🏻 Cambiar Localidad\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
							$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 2
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$update = mysqli_query($con, "UPDATE apps_temp SET  status = 2, local = '".$cuerpo."' 
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
						}
					}else{
						$mensaje = "📋 _Localidad *".$cuerpo."* es invalido para tu cita_ ⛔ \n\n".
						"_Intenta nuevamente_";
						$respuesta = sendMessage($chatId,$mensaje);
					}
				}else if($row_l['lenguaje']==2){
						if($resultado !==0){
							$mensaje = "📋 _Please enter the *CODE* of the service_: \n\n".
							$resultado."\n\n".
							"_*Send*_ :\n".
							"*L*  👉🏻 Change Location\n".
							"*HELP*  👉🏻 Para más comandos";
							$respuesta = sendMessage($chatId,$mensaje);
							$obj = json_decode($respuesta);
							if($obj->{'sent'}==1){
								$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 2
								WHERE chat_id = '".$chatId."'") or die(mysqli_error());
								$update = mysqli_query($con, "UPDATE apps_temp SET  status = 2, local = '".$cuerpo."' 
								WHERE chatID = '".$chatId."'") or die(mysqli_error());
							}
						}else{
							$mensaje = "📋 _Location *".$cuerpo."* Is invalid for your appointment_ ⛔ \n\n".
							"_Try again_";
							$respuesta = sendMessage($chatId,$mensaje);
						}
				}

			}else if($consultaw[0]==1 && $consultaw[1]==2){
				$resultado = consulta_s_d($cuerpo,$chatId);
				//echo $resultado;
				if($row_l['lenguaje']==1){
					if($resultado !==0){
							$respuesta = sendMessage($chatId,$resultado);
							$obj = json_decode($respuesta);
							if($obj->{'sent'}==1){
								$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 31
								WHERE chat_id = '".$chatId."'") or die(mysqli_error());
								$update = mysqli_query($con, "UPDATE apps_temp SET  status = 31, serv = '".$cuerpo."' 
								WHERE chatID = '".$chatId."'") or die(mysqli_error());
							}
						}else{
							$mensaje = "📋 *CODIGO* incorrecto 🚫  \n\n".
										"Por favor ingresa el *CODIGO* del servicio: \n\n".
										"_Ejemplo: *W1*_";
							$respuesta = sendMessage($chatId,$mensaje);
						}
				}else if($row_l['lenguaje']==2){
					if($resultado !==0){
							$respuesta = sendMessage($chatId,$resultado);
							$obj = json_decode($respuesta);
							if($obj->{'sent'}==1){
								$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 31
								WHERE chat_id = '".$chatId."'") or die(mysqli_error());
								$update = mysqli_query($con, "UPDATE apps_temp SET  status = 31, serv = '".$cuerpo."' 
								WHERE chatID = '".$chatId."'") or die(mysqli_error());
							}
						}else{
							$mensaje = "📋 Incorrect *CODE* 🚫  \n\n".
										"Please enter the *CODE* of the service: \n\n".
										"_Example: *W1*_";
							$respuesta = sendMessage($chatId,$mensaje);
						}
				}

			}
			else if($consultaw[0]==1 && $consultaw[1]==31){
				//sendMessage($chatId,"hola");
				$resultado = validar_p($cuerpo,$chatId);
				//echo $resultado;
				if($row_l['lenguaje']==1){
					if($resultado !==0){
						$mensaje = "🗓️ _*Que fecha deseas ser atendido?*_ \n\n".
						"Ejemplo: *".$hoy."*\n\n".
						 "*_Envia:_*\n".
						 "*S*  👉🏻 Cambiar Servicio\n".
						 "*L*  👉🏻 Cambiar Localidad\n".
						 "*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
							$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 41
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$update = mysqli_query($con, "UPDATE apps_temp SET  status = 41, prof = '".$resultado."' 
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
						}
					}else{
						$mensaje = "📋 El codigo del Profesional  💇🏻‍♀️ 💇🏻‍♂️: *".$cuerpo."* es *INVALIDO* 🚫:\n\n".
						"_Intenta nuevamente_\n".
						"*_Envia:_*\n".
						"*S*  👉🏻 Cambiar Servicio\n".
						 "*L*  👉🏻 Cambiar Localidad\n".
						 "*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}
				}else if($row_l['lenguaje']==2){
						if($resultado !==0){
							$mensaje = "🗓️ _*What date do you want to be attended?*_ \n\n".
							"Example: *".$hoy."*\n\n".
							"*_Send:_*\n".
							 "*S*  👉🏻 Change Service\n".
							 "*L*  👉🏻 Change Location\n".
							 "*HELP*  👉🏻 For more commands";
							$respuesta = sendMessage($chatId,$mensaje);
							$obj = json_decode($respuesta);
							if($obj->{'sent'}==1){
								$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 41
								WHERE chat_id = '".$chatId."'") or die(mysqli_error());
								$update = mysqli_query($con, "UPDATE apps_temp SET  status = 41, prof = '".$resultado."' 
								WHERE chatID = '".$chatId."'") or die(mysqli_error());
							}
						}else{
							$mensaje = "📋 The Professional's code  💇🏻‍♀️ 💇🏻‍♂️: *".$cuerpo."* is * INVALID * 🚫:\n\n".
							"_Try again_\n".
							"*_Send:_*\n".
							"*S*  👉🏻 Change Service\n".
							"*L*  👉🏻 Change Location\n".
							"*HELP*  👉🏻 For more commands";
							$respuesta = sendMessage($chatId,$mensaje);
						}
				}

			}
			else if($consultaw[0]==1 && $consultaw[1]==41){
				$resultado = validar_f($cuerpo,$chatId);
				if($row_l['lenguaje']==1){
					if($resultado[0] ==12){
						$mensaje = "🕑 *_Selecciona la hora_*  \n\n".
									$resultado[1]."\n\n".
									"*_Envia:_*\n".
									"*MF* 👉🏻 Cambiar Fecha \n".
									"*S*  👉🏻 Cambiar Servicio\n".
									"*L*  👉🏻 Cambiar Localidad\n".
									"*AYUDA ó HELP*  👉🏻 Para más comandos";
						echo $mensaje;
						$respuesta = sendMessage($chatId,$mensaje);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
							$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 51
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$update = mysqli_query($con, "UPDATE apps_temp SET  status = 51
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
						}
					}else if($resultado[0] ==10){
						$mensaje = "📋 La Fecha   *".$cuerpo."*\n es mayor al maximo de 30 dias 🗓️:\n\n".
						"_Intenta nuevamente_\n".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*_Envia:_*\n".
						"*S*  👉🏻 Cambiar Servicio\n".
						"*L*  👉🏻 Cambiar Localidad\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}
					else if($resultado[0] ==14){
						$mensaje = "📋 La Fecha   *".$cuerpo."* \n no puede ser menor a \n*".$hoy."* 🗓️:\n\n".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*_Envia:_*\n".
						"*S*  👉🏻 Cambiar Servicio\n".
						"*L*  👉🏻 Cambiar Localidad\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}else if($resultado[0] ==0){
						$mensaje = "📋 El formato Fecha   *".$cuerpo."* \n No es valido\n\n".
						"_Intenta nuevamente_\n".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*_Envia:_*\n".
						"*S*  👉🏻 Cambiar Servicio\n".
						"*L*  👉🏻 Cambiar Localidad\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}else if($resultado[0] ==11){
						$mensaje = "📋 Disculpa no laboramos este dias  \n\n".
						"_Intenta nuevamente_\n".
						"_Ejemplo: 🗓️ *".$hoy."*_ \n\n".
						"*_Envia:_*\n".
						"*S*  👉🏻 Cambiar Servicio\n".
						"*L*  👉🏻 Cambiar Localidad\n".
						"*AYUDA ó HELP*  👉🏻 Para más comandos";
						$respuesta = sendMessage($chatId,$mensaje);
					}
				}else if($row_l['lenguaje']==2){
					if($resultado[0] ==12){
						$mensaje = "🕑 *_Select time_*  \n\n".
									$resultado[1]."\n\n".
									"*_Send:_*\n".
									"*MF* 👉🏻 Change date \n".
									"*S*  👉🏻 Change Service\n".
									"*L*  👉🏻 Change Location\n".
									"*HELP*  👉🏻 For more commands";
						//echo $mensaje;
						$respuesta = sendMessage($chatId,$mensaje);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
							$insert = mysqli_query($con, "UPDATE apps_welcome SET  status = 51
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$update = mysqli_query($con, "UPDATE apps_temp SET  status = 51
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
						}
					}else if($resultado[0] ==10){
						$mensaje = "📋 Date *".$cuerpo."*\n is greater than the maximum of 30 days 🗓️:\n\n".
						"_Try again_\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*S*  👉🏻 Change Service\n".
						"*L*  👉🏻 Change Location\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}
					else if($resultado[0] ==14){
						$mensaje = "📋 Date *".$cuerpo."* \n cannot be less than \n*".$hoy."* 🗓️:\n\n".
						"_Try again_\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*S*  👉🏻 Change Service\n".
						"*L*  👉🏻 Change Location\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}else if($resultado[0] ==0){
						$mensaje = "📋 The Date format   *".$cuerpo."* \n It's not valid\n\n".
						"_Try again_\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*S*  👉🏻 Change Service\n".
						"*L*  👉🏻 Change Location\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}else if($resultado[0] ==11){
						$mensaje = "📋 Sorry we don't work today \n\n".
						"_Try again_\n".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*S*  👉🏻 Change Service\n".
						"*L*  👉🏻 Change Location\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}
				}

			}
			else if($consultaw[0]==1 && $consultaw[1]==51){
				$resultado = validar_h($cuerpo,$chatId);
				if($row_l['lenguaje']==1){
					if($resultado !==0){
						echo $resultado;
						$respuesta = sendMessage($chatId,$resultado);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
							$insert = mysqli_query($con, "delete from apps_welcome 
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$insert = mysqli_query($con, "delete from apps_temp 
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
							
						}
					}else if($resultado ==15){
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
					
				}if($row_l['lenguaje']==2){
					if($resultado !==0){
						echo $resultado;
						$respuesta = sendMessage($chatId,$resultado);
						$obj = json_decode($respuesta);
						if($obj->{'sent'}==1){
							$insert = mysqli_query($con, "delete from apps_welcome 
							WHERE chat_id = '".$chatId."'") or die(mysqli_error());
							$insert = mysqli_query($con, "delete from apps_temp 
							WHERE chatID = '".$chatId."'") or die(mysqli_error());
							
						}
					}else if($resultado ==15){
						$mensaje = "🚫 There is no availability ️🕑 for the date:\n\n".
						"_Try another date again_".
						"_Example: 🗓️ *".$hoy."*_ \n\n".
						"*_Send:_*\n".
						"*S*  👉🏻 Change Service\n".
						"*L*  👉🏻 Change Location\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}
					else{
						$mensaje = "🚫 Time Code ️🕑 *".$cuerpo."*  *INVALID*:\n\n".
						"_Try again_".
						"_Example: 🕑 *2*_ \n\n".
						"*_Send:_*\n".
						"*MF* 👉🏻 Change Date\n".
						"*S*  👉🏻 Change Service\n".
						"*L*  👉🏻 Change Location\n".
						"*HELP*  👉🏻 For more commands";
						$respuesta = sendMessage($chatId,$mensaje);
					}
					
				}
					
			}
		}
	}
	}
	}
}
?>