#!/usr/bin/php5
<?php 
$base_url="https://www.guiadetalleres.es/";
include_once ('../cnfg/sms_config_esp.php');
//Script para el cron, a ejecutar todas las noches a partir de las 00:00 horas
$con = mysql_connect("localhost", "webmaster_vzla", "w=@,Fb*D");
mysql_set_charset('utf8',$con);
mysql_select_db("vzla", $con) or die(mysql_error());
$hoy=date("Y-m-d");
// Calculos
$vehicles_sql = sprintf("SELECT DISTINCT car_reg FROM maintenance ORDER BY maintenance_date ASC");
$vehicles_res = mysql_query($vehicles_sql, $con);
if ($vehicles_res) {
	if (mysql_num_rows($vehicles_res) > 0) {
		while ($car_reg_obj = mysql_fetch_object($vehicles_res)) {
			// Calculos
			$maintenance_sql = sprintf("SELECT * FROM maintenance
			WHERE car_reg = '%s' ORDER BY maintenance_date ASC",
			mysql_real_escape_string($car_reg_obj->car_reg));
			//echo 	$maintenance_sql."<br>";
			$maintenance_res = mysql_query($maintenance_sql, $con);
			if ($maintenance_res) {
				if (mysql_num_rows($maintenance_res) > 0) {
					$num_reg = mysql_num_rows($maintenance_res);
					$last_reg = $num_reg-1;
					$maintenance_obj = array();
					while ($maintenance_row = mysql_fetch_object($maintenance_res)) {
						array_push($maintenance_obj, $maintenance_row);
					}
				}
			}else{
				echo mysql_error();
			}
			if ($maintenance_obj[$last_reg-1]->maintenance_date) {
				// Dias después de la última revisión
				$fecha_ant = new DateTime($maintenance_obj[$last_reg]->maintenance_date);
				$fecha_act = new DateTime($maintenance_obj[$last_reg-1]->maintenance_date);
				$dias_ultima_rev = $fecha_act->diff($fecha_ant);
				$dias_ultima_rev = $dias_ultima_rev->format('%a');

				if($dias_ultima_rev != 0){
					
					// Media de KM diarios
					$media_km = ($maintenance_obj[$last_reg]->km - $maintenance_obj[$last_reg-1]->km) / $dias_ultima_rev;
					$media_km = number_format($media_km, 2, ".", "");
					// Fecha de proxima revisión
					$days_prox_rev = ($maintenance_obj[$last_reg]->km_next_rev - $maintenance_obj[$last_reg]->km) / $media_km;
					$days_prox_rev = round($days_prox_rev);
					
					$hoy = strtotime($maintenance_obj[$last_reg]->maintenance_date);
					if($days_prox_rev >= 365 && $days_prox_rev < 730){
						$fecha_prox_rev = date("Y-m-d", strtotime("365 days", $hoy));
					}elseif($days_prox_rev >= 730){
						$fecha_prox_rev = date("Y-m-d", strtotime("730 days", $hoy));
					}else if ($days_prox_rev < 365){
						$fecha_prox_rev = date("Y-m-d", strtotime($days_prox_rev." days", $hoy));
					}
					
					// Dias para la próxima revisión
					$date_act = new DateTime(date("Y-m-d"));
					$date_prox = new DateTime($fecha_prox_rev);
					$days_last = $date_prox->diff($date_act);
					$days_last = $days_last->format('%a');
					$days_rest = $days_last;
				}
			}elseif($maintenance_obj[$last_reg]->maintenance_date){		
				// Fecha para proxima revisión
				if ($maintenance_obj[$last_reg]->next_rev == 1) {
					$date_to_time = strtotime($maintenance_obj[$last_reg]->maintenance_date);
					$fecha_prox_rev = strtotime("+1 year", $date_to_time);
					$date_ant = new DateTime($maintenance_obj[$last_reg]->maintenance_date);
					$date_act = new DateTime(date("Y-m-d"));
					$date_prox = new DateTime(date("Y-m-d", $fecha_prox_rev));
					$days_last = $date_act->diff($date_ant);
					$days_last = $days_last->format('%a');
					$days_complete = $date_ant->diff($date_prox);
					$days_complete = $days_complete->format('%a');
					$days_rest = $days_complete - $days_last;
				}elseif($maintenance_obj[$last_reg]->next_rev == 2){
					$date_to_time = strtotime($maintenance_obj[$last_reg]->maintenance_date);
					$fecha_prox_rev = strtotime("+2 year", $date_to_time);
					$date_ant = new DateTime($maintenance_obj[$last_reg]->maintenance_date);
					$date_act = new DateTime(date("Y-m-d"));
					$date_prox = new DateTime(date("Y-m-d", $fecha_prox_rev));
					$days_last = $date_act->diff($date_ant);
					$days_last = $days_last->format('%a');
					$days_complete = $date_ant->diff($date_prox);
					$days_complete = $days_complete->format('%a');

					$days_rest = $days_complete - $days_last;
				}
			}
			if($days_rest == 15){
				//Alarma de previsión de mantenimiento
				/**********************************************************************************************************************************/
				$query = "SELECT cd.client_id,cd.nombre, cd.apellidos, cd.email,cd.telefono FROM  clients_details cd, clients_vehicles cv
						WHERE cd.client_id = cv.client_id and cv.vehicle_id = ".$maintenance_obj[$last_reg]->vehicle_id; //Informacion del cliente
				$result = mysql_query($query,$con);
				$row = mysql_fetch_assoc($result);
				$query = "SELECT mail_id 
						  FROM clients_mails
						  WHERE client_id=".$maintenance_obj[$last_reg]->client_id; // Obtenemos la configuraciones del cliente
				
				$result = mysql_query($query, $con);
				if (result) {
					$configuraciones_cliente = array();
					while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del cliente en arreglo
						$configuraciones_cliente[] = $rows['mail_id'];
					}
				}

				$name_client = $row['nombre'];
				$last_name_client = $row['apellidos'];
				$client_email = $row['email']; // Email del cliente
				$number =  $row['telefono'];
				$query = "SELECT wu.ID, wu.user_nicename, wu.user_email, ud.nombre_comer, ud.nombre, ud.apellido, ud.direccion, ud.telefono, ud.email, ud.url, ud.logo 
						  FROM wp_users wu , wp_users_clients wuc, user_data ud 
						  WHERE  wuc.user_id = wu.ID
						  AND wu.ID = ud.ID 
						  AND wuc.client_id = ".$maintenance_obj[$last_reg]->client_id;
				//echo $query."<br>";
				$result = mysql_query($query,$con);
				if ($result) {
					$row = mysql_fetch_assoc($result);
				}

				if($row){//Validamos que venga la informacion del taller para enviar la informacion

					$nice_taller = $row['user_nicename'];
					$taller_email = $row['user_email'];

					/*Informacion de Taller (Datos)*/
					$nombre_taller = $row['nombre_comer'];
					$propietario = $row['nombre'].' '.$row['apellido'];
					$direccion = $row['direccion'];
					$telefono = $row['telefono'];
					$taller_url = $row['url'];
					$taller_logo = $row['logo'];
					/*Informacion de Taller (Datos)*/

					/*$query = "SELECT mail_id 
							  FROM user_mail 
							  WHERE user_id=".$maintenance_obj[$last_reg]->user_id; // Obtenemos la configuraciones del taller

					$result = mysql_query($query, $con);
					if ($result) {
						$configuraciones = array();
						while ($rows = mysql_fetch_assoc($result)){ // Asignamos configuraciones del taller en arreglo
							$configuraciones[] = $rows['mail_id'];
						}
					}
					*/

					$hoy = date("Y-m-d");
					$fecha = date('d/m/Y',strtotime($hoy.' +'.$days_rest.' days'));

					//	if(is_null($configuraciones) || is_null($configuraciones_cliente) ){
							echo "sasa";
							//enviar estos datos por email al taller
							$message = "Taller: ".$nombre_taller." le recuerda que su veh  mat ".$maintenance_obj[$last_reg]->car_reg." deberia hacerle revision el ".$fecha;
							$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
							echo $message."<br>";
							$smsresult = json_encode(array($result));
							$pos = strpos($smsresult, '"id"');
							if ($pos == true){
										$rest = substr($smsresult, $pos, 16);
										//echo $rest."<br>";
										$buscar_2p = strpos($rest, ':');
										
										$buscar_coma = strpos($rest, ',');
										//echo $buscar_2p." ".$buscar_coma;
										$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
										$pos2 = strpos($smsresult, '"status"');		
										$pos3 = strpos($smsresult, '"send_at"');	
										$poresta = $pos3-$pos2;
										$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
										$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (user_id,client_id,tipo_mensaje,status,sms_id,date,phone_client,body) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','1','".$rest3."','".$rest2."','".$hoy."','".$number."','".$message."') ");
										mysql_query($insert_sms_sql, $con);
										//echo $insert_sms_sql;
										//echo "si".$posicion_coincidencia;
							}
						/*}
						
						elseif(!in_array(9, $configuraciones) && !in_array(10, $configuraciones)){//Verificamos que no este sileciado este email (general)
							if(!in_array(9, $configuraciones_cliente) && !in_array(10, $configuraciones_cliente)){//Verificamos que no este sileciado este email (individual)
								//enviar estos datos por email al taller
								$message = "Taller: ".$nombre_taller." le recuerda que su veh  mat ".$maintenance_obj[$last_reg]->car_reg." deberia hacerle revision el ".$fecha;
								$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
								echo $message."<br>";
								$smsresult = json_encode(array($result));
								if ($pos == true){
										$rest = substr($smsresult, $pos, 16);
										//echo $rest."<br>";
										$buscar_2p = strpos($rest, ':');
										
										$buscar_coma = strpos($rest, ',');
										//echo $buscar_2p." ".$buscar_coma;
										$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
										$pos2 = strpos($smsresult, '"status"');		
										$pos3 = strpos($smsresult, '"send_at"');	
										$poresta = $pos3-$pos2;
										$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
										$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (id_taller,id_cliente,tipo_mensaje,status,sms_id) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','1','".$rest3."','".$rest2."') ");
										mysql_query($insert_sms_sql, $con);
										//echo $insert_sms_sql;
										//echo "si".$posicion_coincidencia;
							}
								// $client_email
							}		
						}*/
					
				}
			}
		} // End while
	}
}
/**********************************************************************************************************************************/
//Alarma de previsión de ITV (Si pasa la ITV)
/**********************************************************************************************************************************/

$query2 = "SELECT vd.fecha_itv, vd.vehicle_id
FROM vehicles_details vd
WHERE vd.fecha_itv = ( SELECT DATE_FORMAT (NOW() + INTERVAL 15 DAY ,'%d/%m/%Y') )";

//echo $query2;
$result2 = mysql_query($query2, $con);
if ($result2) {
	$num2 = mysql_num_rows($result2);
	$alarms2 = array();
	$i = 0;
	while ($rows2 = mysql_fetch_assoc($result2)){
		$alarms2[$i]['vehicle_id'] = $rows2['vehicle_id'];
		$alarms2[$i]['fecha_itv'] = $rows2['fecha_itv'];
		$i = $i+1;
	}
	//cada alarma: vehicle_id, matricula, pr_fecha, client_id, nombre, apellidos, taller_id, nombre_taller
	for($i=0;$i<$num2;$i++) {
		$veh_obs_sql = sprintf("SELECT id_obs, paso_itv FROM vehicles_observations WHERE vehicle_id = '%s' ORDER BY id_obs DESC LIMIT 1", mysql_real_escape_string($alarms2[$i]['vehicle_id']));
		$veh_obs_res = mysql_query($veh_obs_sql, $con);
		if(!$veh_obs_res){
			$query = "SELECT matricula FROM vehicles_details WHERE vehicle_id = ".$alarms2[$i]['vehicle_id'];
			$result = mysql_query($query,$con);
			$row = mysql_fetch_assoc($result);
			$alarms2[$i]['matricula'] = $row['matricula'];
			$query = "SELECT cd.client_id,cd.nombre, cd.apellidos, cd.email, cd.telefono as clie_tel FROM  clients_details cd, clients_vehicles cv
					WHERE cd.client_id = cv.client_id and cv.vehicle_id = ".$alarms2[$i]['vehicle_id'];//Informacion del cliente
			$result = mysql_query($query,$con);
			$row = mysql_fetch_assoc($result);
			$alarms2[$i]['client_id'] = $row['client_id'];//Obtenemos el ID del cliente
			$query = "SELECT mail_id 
					  FROM clients_mails
					  WHERE client_id=".$alarms2[$i]['client_id'];//Obtenemos la configuraciones del cliente	
			$result = mysql_query($query,$con);
			while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del cliente en arreglo
				$configuraciones_cliente[] = $rows['mail_id'];
			}
			$alarms2[$i]['nombre'] = $row['nombre'];
			$alarms2[$i]['apellidos'] = $row['apellidos'];
			$alarms2[$i]['client_email'] = $row['email'];//Email del cliente
			$alarms2[$i]['clie_tel'] = $row['clie_tel']
			/*$query = "SELECT wu.ID, wu.user_nicename, wu.user_email FROM wp_users wu , wp_users_clients wuc 
					WHERE  wuc.user_id = wu.ID AND wuc.client_id = ".$alarms2[$i]['client_id'];*/
			$query = "SELECT wu.ID, wu.user_nicename, wu.user_email, ud.nombre_comer, ud.nombre, ud.apellido, ud.direccion, ud.telefono, ud.email, ud.url, ud.logo 
					  FROM wp_users wu , wp_users_clients wuc, user_data ud 
					  WHERE  wuc.user_id = wu.ID
					  AND wu.ID = ud.ID 
					  AND wuc.client_id = ".$alarms2[$i]['client_id'];
			$result = mysql_query($query,$con);
			$row = mysql_fetch_assoc($result);
			if($row){//Validamos que venga la informacion del taller para enviar la informacion
				$alarms2[$i]['taller'] = $row['user_nicename'];
				$alarms2[$i]['email'] = $row['email'];
				$alarms2[$i]['telefono'] = $row['telefono'];
				/*Informacion de Taller (Datos)*/
				$alarms2[$i]['nombre_taller'] = $row['nombre_comer'];
				$alarms2[$i]['propietario'] = $row['nombre'].' '.$row['apellido'];
				$alarms2[$i]['direccion'] = $row['direccion'];
				$alarms2[$i]['telefono'] = $row['telefono'];
				$alarms2[$i]['taller_email'] = $row['email'];
				$alarms2[$i]['taller_url'] = $row['url'];
				$alarms2[$i]['taller_logo'] = $row['logo'];
				/*Informacion de Taller (Datos)*/
				$alarms2[$i]['taller_id'] = $row['ID'];//Obtenemos el ID del taller
				$query = "SELECT mail_id 
						  FROM user_mail 
						  WHERE user_id=".$alarms2[$i]['taller_id'];//Obtenemos la configuraciones del taller
				$result = mysql_query($query,$con);
				while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del taller en arreglo
					$configuraciones[] = $rows['mail_id'];
				}
				//sleep('1');
				$fecha = $alarms2[$i]['fecha_itv'];		
				$message = "Taller: ".$alarms2[$i]['nombre_taller']." le recuerda que su veh  mat ".$alarms2[$i]['matricula']." debería pasar su próxima ITV el ".$fecha;				
				if ($alarms2[$i]['taller_email'] != '') {
					$result = $smsGateway->sendMessageToNumber($alarms2[$i]['clie_tel'], $message, $deviceID);
					echo $message."<br>";
					$smsresult = json_encode(array($result));
						if ($pos == true){
								$rest = substr($smsresult, $pos, 16);
												//echo $rest."<br>";
								$buscar_2p = strpos($rest, ':');							
								$buscar_coma = strpos($rest, ',');
								//echo $buscar_2p." ".$buscar_coma;
								$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
								$pos2 = strpos($smsresult, '"status"');		
								$pos3 = strpos($smsresult, '"send_at"');	
								$poresta = $pos3-$pos2;
								$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
								$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (user_id,client_id,tipo_mensaje,status,sms_id,date,phone_client,body) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','3','".$rest3."','".$rest2."','".$hoy."','".$number."','".$message."') ");
								mysql_query($insert_sms_sql, $con);
									//echo $insert_sms_sql;
									//echo "si".$posicion_coincidencia;
						}
				}
			}
		}else{
			$veh_obs_ob = mysql_fetch_object($veh_obs_res);
			if($veh_obs_ob->paso_itv == 0){
				$query = "SELECT matricula FROM vehicles_details WHERE vehicle_id = ".$alarms2[$i]['vehicle_id'];
				$result = mysql_query($query,$con);
				$row = mysql_fetch_assoc($result);
				$alarms2[$i]['matricula'] = $row['matricula'];
				$query = "SELECT cd.client_id,cd.nombre, cd.apellidos, cd.email, cd.telefono as clie_tel FROM  clients_details cd, clients_vehicles cv
						WHERE cd.client_id = cv.client_id and cv.vehicle_id = ".$alarms2[$i]['vehicle_id'];//Informacion del cliente
				$result = mysql_query($query,$con);
				$row = mysql_fetch_assoc($result);
				$alarms2[$i]['client_id'] = $row['client_id'];//Obtenemos el ID del cliente
				$query = "SELECT mail_id 
						  FROM clients_mails
						  WHERE client_id=".$alarms2[$i]['client_id'];//Obtenemos la configuraciones del cliente			
				$result = mysql_query($query,$con);
				while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del cliente en arreglo
					$configuraciones_cliente[] = $rows['mail_id'];
				}
				$alarms2[$i]['nombre'] = $row['nombre'];
				$alarms2[$i]['apellidos'] = $row['apellidos'];
				$alarms2[$i]['client_email'] = $row['email'];//Email del cliente
				$alarms2[$i]['clie_tel'] = $row['clie_tel'];
				/*$query = "SELECT wu.ID, wu.user_nicename, wu.user_email FROM wp_users wu , wp_users_clients wuc 
						WHERE  wuc.user_id = wu.ID AND wuc.client_id = ".$alarms2[$i]['client_id'];*/
				$query = "SELECT wu.ID, wu.user_nicename, wu.user_email, ud.nombre_comer, ud.nombre, ud.apellido, ud.direccion, ud.telefono, ud.email, ud.url, ud.logo 
						  FROM wp_users wu , wp_users_clients wuc, user_data ud 
						  WHERE  wuc.user_id = wu.ID
						  AND wu.ID = ud.ID 
						  AND wuc.client_id = ".$alarms2[$i]['client_id'];

				$result = mysql_query($query,$con);
				$row = mysql_fetch_assoc($result);
				if($row){//Validamos que venga la informacion del taller para enviar la informacion
					$alarms2[$i]['taller'] = $row['user_nicename'];
					$alarms2[$i]['email'] = $row['email'];
					/*Informacion de Taller (Datos)*/
					$alarms2[$i]['nombre_taller'] = $row['nombre_comer'];
					$alarms2[$i]['propietario'] = $row['nombre'].' '.$row['apellido'];
					$alarms2[$i]['direccion'] = $row['direccion'];
					$alarms2[$i]['telefono'] = $row['telefono'];
					$alarms2[$i]['taller_email'] = $row['email'];
					$alarms2[$i]['taller_url'] = $row['url'];
					$alarms2[$i]['taller_logo'] = $row['logo'];
					/*Informacion de Taller (Datos)*/
					$alarms2[$i]['taller_id'] = $row['ID'];//Obtenemos el ID del taller
					$query = "SELECT mail_id 
							  FROM user_mail 
							  WHERE user_id=".$alarms2[$i]['taller_id'];//Obtenemos la configuraciones del taller
					$result = mysql_query($query,$con);
					while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del taller en arreglo
						$configuraciones[] = $rows['mail_id'];
					}


					//sleep('1');
					$fecha = $alarms2[$i]['fecha_itv'];
					$message = "Taller: ".$alarms2[$i]['nombre_taller']." le recuerda que su veh  mat ".$alarms2[$i]['matricula']." debería pasar su próxima ITV el ".$fecha;
					
					if ($alarms2[$i]['taller_email'] != '') {
						$result = $smsGateway->sendMessageToNumber($alarms2[$i]['clie_tel'], $message, $deviceID);
						echo $message."<br>";
						$smsresult = json_encode(array($result));
							if ($pos == true){
									$rest = substr($smsresult, $pos, 16);
													//echo $rest."<br>";
									$buscar_2p = strpos($rest, ':');							
									$buscar_coma = strpos($rest, ',');
									//echo $buscar_2p." ".$buscar_coma;
									$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
									$pos2 = strpos($smsresult, '"status"');		
									$pos3 = strpos($smsresult, '"send_at"');	
									$poresta = $pos3-$pos2;
									$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
									$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (user_id,client_id,tipo_mensaje,status,sms_id,date,phone_client,body) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','3','".$rest3."','".$rest2."','".$hoy."','".$number."','".$message."') ");
									mysql_query($insert_sms_sql, $con);
										//echo $insert_sms_sql;
										//echo "si".$posicion_coincidencia;
							}
					}
				}
			}
		}
	}	
}


/**********************************************************************************************************************************/
//Alarma de previsión de ITV (No pasa la ITV)
/**********************************************************************************************************************************/
$query3 = "SELECT DISTINCT vd.fecha_itv, vd.vehicle_id, vo.observacion
FROM vehicles_details vd, vehicles_observations vo 
WHERE vd.vehicle_id = vo.vehicle_id 
AND vo.paso_itv = 1
AND vd.fecha_itv = ( SELECT DATE_FORMAT (NOW() + INTERVAL 15 DAY ,'%d/%m/%Y') )";
//echo $query2;
$result3 = mysql_query($query3,$con);
if ($result3) {
	$num3 = mysql_num_rows($result3);
	$alarms3 = array();
	$i = 0;
	while ($rows3 = mysql_fetch_assoc($result3)){
	$alarms3[$i]['vehicle_id'] = $rows3['vehicle_id'];
	$alarms3[$i]['fecha_itv'] = $rows3['fecha_itv'];
	$alarms3[$i]['observacion'] = $rows3['observacion'];
	$i = $i+1;
	}
	//cada alarma: vehicle_id, matricula, pr_fecha, client_id, nombre, apellidos, taller_id, nombre_taller
	for($i=0;$i<$num3;$i++){
		$query = "SELECT matricula FROM vehicles_details WHERE vehicle_id = ".$alarms3[$i]['vehicle_id'];
		$result = mysql_query($query,$con);
		$row = mysql_fetch_assoc($result);
		$alarms3[$i]['matricula'] = $row['matricula'];
		$query = "SELECT cd.client_id,cd.nombre, cd.apellidos, cd.email, cd.telefono as telefono_c FROM  clients_details cd, clients_vehicles cv
				WHERE cd.client_id = cv.client_id and cv.vehicle_id = ".$alarms3[$i]['vehicle_id'];//Informacion del cliente
		$result = mysql_query($query,$con);
		$row = mysql_fetch_assoc($result);
		$alarms3[$i]['client_id'] = $row['client_id'];//Obtenemos el ID del cliente
		$query = "SELECT mail_id 
				  FROM clients_mails
				  WHERE client_id=".$alarms3[$i]['client_id'];//Obtenemos la configuraciones del cliente

		$result = mysql_query($query,$con);

		while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del cliente en arreglo
			$configuraciones_cliente[] = $rows['mail_id'];
		}
		$alarms3[$i]['nombre'] = $row['nombre'];
		$alarms3[$i]['apellidos'] = $row['apellidos'];
		$alarms3[$i]['client_email'] = $row['email'];//Email del cliente
		$alarms3[$i]['telefono_c'] = $row['telefono_c'];
		$number = $alarms3[$i]['telefono_c'];
		$query = "SELECT wu.ID, wu.user_nicename, wu.user_email, ud.nombre_comer, ud.nombre, ud.apellido, ud.direccion, ud.telefono, ud.email, ud.url, ud.logo 
				  FROM wp_users wu , wp_users_clients wuc, user_data ud 
				  WHERE  wuc.user_id = wu.ID
				  AND wu.ID = ud.ID 
				  AND wuc.client_id = ".$alarms3[$i]['client_id'];

		$result = mysql_query($query,$con);
		$row = mysql_fetch_assoc($result);
		if($row){//Validamos que venga la informacion del taller para enviar la informacion
			$alarms3[$i]['taller'] = $row['user_nicename'];
			$alarms3[$i]['email'] = $row['user_email'];
			/*Informacion de Taller (Datos)*/
			$alarms3[$i]['nombre_taller'] = $row['nombre_comer'];
			$alarms3[$i]['propietario'] = $row['nombre'].' '.$row['apellido'];
			$alarms3[$i]['direccion'] = $row['direccion'];
			$alarms3[$i]['telefono'] = $row['telefono'];
			$alarms3[$i]['taller_email'] = $row['email'];
			$alarms3[$i]['taller_url'] = $row['url'];
			$alarms3[$i]['taller_logo'] = $row['logo'];
			/*Informacion de Taller (Datos)*/
			$alarms3[$i]['taller_id'] = $row['ID'];//Obtenemos el ID del taller
			$query = "SELECT mail_id 
					  FROM user_mail
					  WHERE user_id=".$alarms3[$i]['taller_id'];//Obtenemos la configuraciones del taller
			$result = mysql_query($query,$con);
			while ($rows = mysql_fetch_assoc($result)){//Asignamos configuraciones del taller en arreglo
				$configuraciones[] = $rows['mail_id'];
			}
			//sleep('1');
			$fecha = $alarms3[$i]['fecha_itv'];
			$message = "Taller: ".$alarms2[$i]['nombre_taller']." le recuerda que su veh  mat ".$alarms2[$i]['matricula']." deberia pasar su próxima ITV el el ".$fecha;
				if(is_null($configuraciones) || is_null($configuraciones_cliente)){
					//enviar estos datos por email al taller
					$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
					echo $message."<br>";
					$smsresult = json_encode(array($result));
						if ($pos == true){
								$rest = substr($smsresult, $pos, 16);
												//echo $rest."<br>";
								$buscar_2p = strpos($rest, ':');							
								$buscar_coma = strpos($rest, ',');
								//echo $buscar_2p." ".$buscar_coma;
								$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
								$pos2 = strpos($smsresult, '"status"');		
								$pos3 = strpos($smsresult, '"send_at"');	
								$poresta = $pos3-$pos2;
								$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
								$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (id_taller,id_cliente,tipo_mensaje,status,sms_id) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','4','".$rest3."','".$rest2."') ");
								mysql_query($insert_sms_sql, $con);
									//echo $insert_sms_sql;
									//echo "si".$posicion_coincidencia;
						}
					// $alarms2[$i]['client_email']
				}elseif(!in_array(9, $configuraciones) && !in_array(11, $configuraciones)){//Verificamos que no este sileciado este email (general)
					if(!in_array(9, $configuraciones_cliente) && !in_array(11, $configuraciones_cliente)){//Verificamos que no este sileciado este email (individual)
						//enviar estos datos por email al taller
						$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
					echo $message."<br>";
					$smsresult = json_encode(array($result));
						if ($pos == true){
								$rest = substr($smsresult, $pos, 16);
												//echo $rest."<br>";
								$buscar_2p = strpos($rest, ':');							
								$buscar_coma = strpos($rest, ',');
								//echo $buscar_2p." ".$buscar_coma;
								$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
								$pos2 = strpos($smsresult, '"status"');		
								$pos3 = strpos($smsresult, '"send_at"');	
								$poresta = $pos3-$pos2;
								$rest3 = substr($smsresult, $pos2+10, $poresta-12);							
								$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (id_taller,id_cliente,tipo_mensaje,status,sms_id) VALUES ('".$alarms5[$i]['taller_id']."','".$alarms5[$i]['client_id']."','4','".$rest3."','".$rest2."') ");
								mysql_query($insert_sms_sql, $con);
									//echo $insert_sms_sql;
									//echo "si".$posicion_coincidencia;
						}
					}
				}
			
		}	
	}
}
