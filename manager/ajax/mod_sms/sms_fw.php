<?php
require('../../../vzla/wp-load.php');//libreria para ejecutar wp_mail
$base_url="https://www.guiadetalleres.es/";
include_once ('../cnfg/sms_config_esp.php');
//Script para el cron, a ejecutar todas las noches a partir de las 00:00 horas
$con = mysql_connect("localhost", "webmaster_vzla", "w=@,Fb*D");
mysql_set_charset('utf8',$con);
mysql_select_db("vzla", $con) or die(mysql_error());
$hoy=date("Y-m-d");
$idsms = $_POST["id"];
$mes = $_POST["message"];
$contacto = $_POST["contact"];
$smsresult = json_encode(array($contacto));
$pos = strpos($smsresult, '"number"');
					if ($pos == true){
								$rest = substr($smsresult, $pos+13, 9);
								//$rest = substr($smsresult, $pos+10, 12);
								$buscar_2p = strpos($rest, ':');
								$buscar_coma = strpos($rest, '}');
								//echo $rest;
					}
if($rest!="697585861"){				
$query5 = "SELECT * FROM `sms_notificaciones` WHERE phone_client = '".$rest."' ORDER BY id_sms  DESC LIMIT 0,1";
$result5 = mysql_query($query5,$con);
//echo $query5;

if ($result5) {
	$num5 = mysql_num_rows($result5);
	$rows5 = mysql_fetch_assoc($result5);
	$taller_id = $rows5['user_id'];
	$client_id = $rows5['client_id'];
	
	$query6 = "SELECT * FROM clients_details WHERE client_id = '".$client_id."' ";
	$result6 = mysql_query($query6,$con);
	$rows6 = mysql_fetch_assoc($result6);
	$nombre = $rows6['nombre']." ".$rows6['apellidos'];	
		$query = "SELECT  ud.* FROM user_data ud WHERE ud.ID = '".$taller_id."' ";
		$result2 = mysql_query($query,$con);
		$row = mysql_fetch_assoc($result2);
		if($row){
			$taller = $row['nombre_comer'];
			$taller_phone = $row['mobile'];
			$buscar   = "Taller";
			$pos = strpos($taller, $buscar);
			if ($pos === false){
				$taller = $taller;
			}else{
				$taller = str_replace($buscar, "", $taller);
			}		
			if($taller_phone!="" && $taller_phone!=null){	
				$message = "El Cliente: ".$nombre." con el tef: ".$rest." te escribió: ".$mes;
				$number = $taller_phone;
				$taller_telefono 	= $row["telefono"];	 
				$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);	
				$smsresult2 = json_encode(array($result));
				$pos2 = strpos($smsresult2, '"id"');	
				echo "Taller ".$taller." le informa que hemos recibido su mensaje le contestaremos lo antes posible + info llamar al: ".$taller_telefono ;
				$insert_sms_sql = sprintf("INSERT INTO sms_fw (user_id,movil_send,client_id,mensaje,status,fecha,sms_id,tipo_fw) VALUES ('".$taller_id."','".$rest."','".$client_id."','".$message."','send','".$hoy."','".$idsms."','SMS') ");
				mysql_query($insert_sms_sql, $con);
			}
			else{
				$message_em = "El Cliente: ".$nombre." con el tef: ".$rest." te escribió: ".$mes;
				$logo 				= $row["logo"];	 
				$taller_url 		= $row["url"];	
				$taller_email 		= $row["email"];	  
				$taller_telefono 	= $row["telefono"];	 
				$taller_direccion	= $row["direccion"];	 
				$taller_nombre 	= $row["nombre_taller"];				
				$taller_nombre_c = $row["nombre"]." ".$row["apellido"];;
				echo "Taller ".$taller." le informa que hemos recibido su mensaje le contestaremos lo antes posible + info llamar al: ".$taller_telefono ;
			if($logo == ''){
				$logo_img = "";
			}else{
				$logo_img = "<a target=\"_blank\" href=\"".$taller_url."\"> <img src=\"".$base_url."/wp-admin/images/logos/".$logo."\" title=\"Talleres Mecánicos\"> </a><br>";
			}
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
			  <head>
			    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		    
			    <title>Guía de Talleres</title>
			  </head>
			  <body yahoo="" bgcolor="#F1F1F1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
			&#13;
			        <table width="100%" bgcolor="#F1F1F1" border="0" cellpadding="0" cellspacing="0"><tr><td>&#13;
			                    <!--[if (gte mso 9)|(IE)]>
			                    <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
			                        <tr>
			                            <td>
			                                <![endif]-->&#13;
			                                <table align="center" cellpadding="0" cellspacing="0" border="0" style="width: 600px !important; max-width: 600px; background-color: #FFF;" bgcolor="#FFF">
			                                <tr>
			                                	<td bgcolor="#222222" style="padding: 40px 30px 20px;">&#13;
			                                            <table width="70" align="left" border="0" cellpadding="0" cellspacing="0"><tr><td height="75" style="padding: 0 20px 20px 0;">&#13;
			                                                        <img src="https://vzl.guiadetalleres.es/wp-content/themes/directory/design/img/WLogotipoEscudo.png" width="58" height="75" border="0" alt="" /></td>&#13;
			                                                </tr></table><!--[if (gte mso 9)|(IE)]>
			                                            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
			                                                <tr>
			                                                    <td>
			                                                    <![endif]--><table align="left" border="0" cellpadding="0" cellspacing="0" style="width: 425px !important; max-width: 425px;"><tr><td height="70">&#13;
			                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td style="color: #FFF; font-size: 33px; line-height: 38px; font-weight: bold; font-family: sans-serif; padding: 5px 0 0;">&#13;
			                                                                                '.$taller_nombre.'
			                                                                            </td>&#13;
			                                                                        </tr></table></td>&#13;
			                                                            </tr></table><!--[if (gte mso 9)|(IE)]>
			                                                    </td>
			                                                </tr>
			                                            </table>
			                                            <![endif]--></td>&#13;
			                                    </tr><tr><td style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
			                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
			                                            	<tr>
			                                            		<td></td>
			                                            		<td style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
			                                                        Hola <b>Sr(a) '.$taller_nombre_c.', </b> <br><br>
			                                                    </td>&#13;
			                                                </tr>
															<tr>
																<td colspan="5" style="padding-left: 5px;">
																	<ul style="list-style-type: disc">
																	  <li> Cliente: '.$nombre.'</li>
																	  <li> Teléfono: '.$rest.'</li>
																	  <li> Mensaje: "'.$mes.'"</li>			
																	</ul>
																</td>&#13;		                                    
			                                                </tr>
			                                                <tr>
			                                                	<td></td>
			                                                	<td></td>
			                                                </tr>
			                                                <tr>
			                                                	<td></td>
			                                            		<td style=" padding-left: 5px;color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
			                                                    </td>&#13;
			                                                </tr>
			                                                <tr>
			                                                	<td></td>
			                                            		<td style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
			                                                        Muchas gracias.
			                                                    </td>&#13;
			                                                </tr>		                                                
			                                            </table></td>&#13;
			                                    </tr><tr><td style="padding: 30px;">&#13;
			                                            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
			                                                        '.$logo_img.'<a href="mailto:'.$taller_email.'">'.$taller_nombre.'</a> <br>Tel: '.$taller_telefono.'<br>Dirección: '.$taller_direccion.'
			                                                    </td>&#13;
			                                                </tr></table></td>&#13;
			                                    </tr><tr><td bgcolor="#1B88BD" style="padding: 20px 30px 15px;">&#13;
			                                            <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" style="font-family: sans-serif; font-size: 10px; color: #ffffff;">&#13;
			                                                       <a style="color: #FFF; text-decoration: none;">guiadetalleres.es</a> le comunica que de conformidad con la L.O. 15/99 de 13 de diciembre y su desarrollo, le informamos que sus datos se hallan en un fichero informático. Si usted no está conforme con el tratamiento de sus datos, le rogamos lo comunique por escrito a la Atención del Responsable de Seguridad, Ahmad Abdalla Riani de Oliveira con domicilio en la calle Raimundo Fernandez Villaverde, 17 de Madrid pudiendo ejercitar su derecho de acceso, rectificación, cancelación y oposición de sus datos en el mail: <a style="color: #FFF; text-decoration: none;">ali@guiadetalleres.es</a>
			                                                    </td>&#13;
			                                                </tr></table></td>&#13;
			                                    </tr></table><!--[if (gte mso 9)|(IE)]>
			                            </td>
			                        </tr>
			                    </table>
			                    <![endif]--></td>&#13;
			            </tr></table>
			        </body>
				</html>';
				$uid = md5(uniqid(time()));
				$header = "From: ".$taller_nombre." <noreply@guiadetalleres.es>\n";
				$header .= "Reply-To: <".$taller_email.">\n";
				$header .= "MIME-Version: 1.0\n";
				$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";
				$header .= "This is a multi-part message in MIME format.\n";
				$header .= "--".$uid."\n";
				//$header .= "Content-type: text/html;charset=ISO-8859-1\n\n"; //comentar
				$header .= "Content-type: text/html;charset=utf-8\n\n"; //comentar
				//$header .= "Content-Transfer-Encoding: 7bit\n\n"; // descomentar
				$header .= $message."\n\n"; //comentar
				$header .= "--".$uid."\n";
				$header .= "Content-Transfer-Encoding: base64\n";
				$header .= $content."\n\n";
				$header .= "--".$uid."--";
				
				/*$uniqueid= md5(uniqid(time()));
				//indicamos las cabeceras del correo
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "From: <noreply@guiadetalleres.es> \r\n";
				$headers .= "Content-Type: multipart/alternative;boundary=" . $uniqueid. "\r\n";
				*/
				//con la función mail de PHP enviamos el mail.				
				if(mail($taller_email, '=?UTF-8?B?'.base64_encode("Nuevo mensaje del Cliente: ".$nombre).'?=', $message, $header)){
					$statu_fw = json_encode(array('email'=>'ok'));
					// unlink($file);
				}else{
					$statu_fw = json_encode(array('email'=>'failed'));
				}
				$insert_sms_sql = sprintf("INSERT INTO sms_fw (user_id,movil_send,client_id,mensaje,status,fecha,sms_id,tipo_fw) VALUES ('".$taller_id."','".$rest."','".$client_id."','".$message_em."','".$statu_fw."','".$hoy."','".$idsms."','EMAIL') ");
				mysql_query($insert_sms_sql, $con);
			}	
			/*$message = 'te desea un Felíz Cumpleaños ';
					$message = "Hola ".$alarms5[$i]['nombre'].", en este dia tan especial Taller ".$taller.", ".$message;
					$number = $alarms5[$i]['telefono'];
					$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
					$smsresult = json_encode(array($result));
					$pos = strpos($smsresult, '"id"');
					if ($pos == true){
								$rest = substr($smsresult, $pos, 16);
								$buscar_2p = strpos($rest, ':');
								$buscar_coma = strpos($rest, ',');
								$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
								$pos2 = strpos($smsresult, '"status"');		
								$pos3 = strpos($smsresult, '"send_at"');	
								$poresta = $pos3-$pos2;
								$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
								$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (user_id,client_id,tipo_mensaje,status,sms_id,date) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','2','".$rest3."','".$rest2."','".$hoy."') ");
								mysql_query($insert_sms_sql, $con);
					}*/
				}
			}
}
?>	