#!/usr/bin/php5
<?php 
require('../../../vzla/wp-load.php');//libreria para ejecutar wp_mail
$base_url="https://www.guiadetalleres.es/";
include_once ('../cnfg/sms_config_esp.php');
//Script para el cron, a ejecutar todas las noches a partir de las 00:00 horas
$con = mysql_connect("localhost", "webmaster_vzla", "w=@,Fb*D");
mysql_set_charset('utf8',$con);
mysql_select_db("vzla", $con) or die(mysql_error());
$hoy=date("Y-m-d");
$query5 = "SELECT * FROM clients_details WHERE MONTH(STR_TO_DATE(fecha_nacimiento, '%d/%m/%Y')) = MONTH(NOW()) AND DAY(STR_TO_DATE(fecha_nacimiento, '%d/%m/%Y')) = DAY(NOW());";//Informacion del cliente
$result5 = mysql_query($query5,$con);
if ($result5) {
	$num5 = mysql_num_rows($result5);
	
	$alarms5 = array();
	$i = 0;
	while ($rows5 = mysql_fetch_assoc($result5)){
	$alarms5[$i]['client_id'] = $rows5['client_id'];//Obtenemos el ID del cliente
	$alarms5[$i]['nombre'] = $rows5['nombre'];
	$alarms5[$i]['apellidos'] = $rows5['apellidos'];
	$alarms5[$i]['telefono'] = $rows5['telefono'];//telefono del cliente

	$i = $i+1;
	}
	//cada alarma: vehicle_id, matricula, pr_fecha, client_id, nombre, apellidos, taller_id, nombre_taller
	for($i=0;$i<$num5;$i++){	
	//echo $i;
		
		$query = "SELECT  ud.nombre_comer,  ud.telefono,ud.ID
				  FROM wp_users_clients wuc, user_data ud 
				  WHERE  wuc.user_id = ud.ID
				  AND wuc.client_id = ".$alarms5[$i]['client_id'];
		//echo $query."<br>";
		$result = mysql_query($query,$con);
		$row = mysql_fetch_assoc($result);
		if($row){//Validamos que venga la informacion del taller para enviar la informacion
			/*Informacion de Taller (Datos)*/
			$alarms5[$i]['nombre_taller'] = $row['nombre_comer'];
			/*Informacion de Taller (Datos)*/

			$alarms5[$i]['taller_id'] = $row['ID'];//Obtenemos el ID del taller
			
			$buscar   = "Taller";
			$pos = strpos($alarms5[$i]['nombre_taller'], $buscar);
			if ($pos === false){
				$taller = $alarms5[$i]['nombre_taller'];
			}else{
				$taller = str_replace($buscar, "", $alarms5[$i]['nombre_taller']);
			}
			$message = 'te desea un Feliz Cumpleaños ';
			
					$message = "Hola ".$alarms5[$i]['nombre'].", en este día tan especial Taller ".$taller.", ".$message;
					$number = $alarms5[$i]['telefono'];
					$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
					echo $message."<br>";
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
					}else{
						echo "no";
					}
					
				}
			}
		}


?>