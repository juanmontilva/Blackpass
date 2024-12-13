<?php

include("../admin.php");
include_once ('../cnfg/db_config.php');
//include_once ('../cnfg/sms_config_dev.php');
include_once ('../cnfg/sms_config_esp.php');
$base_url = "https://dev.guiadetalleres.es";
$idOt = $_GET['idOt'];
$stipo = $_GET['tipo_'];
$hoy=date("Y-m-d");
$user_id = get_current_user_id();
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME); 
if ($mysqli->connect_errno) {
            echo "Error MySQLi: ("&nbsp. $mysqli->connect_errno.") " . $mysqli->connect_error;
            exit();
 }
 $mysqli->set_charset("utf8");
if($stipo==2){
 	$qry 	= " SELECT 	ud.nombre_comer taller_nombre,"
			. "			ud.ID user_id,"
 			. "			o.cli_nombre cliente_nombre,"
 			. "			o.cli_apellidos cliente_apellido,"
			. "			o.client_id client_id,"
 			. "			o.cli_tel cliente_movil,"
 			. "			o.mat veh_matricula,"
 			. "			o.mar veh_marca,"
 			. "			o.num_ot num_ot"
 			. "	  FROM ot o, user_data ud"
 			. "	 WHERE o.user_id=ud.ID"
 			. "	   AND o.id=".$idOt;

     $taller_nombre = "";
     $num_ot = "";

     $cliente_empresa = "";
     $cliente_nombre = "";
     $cliente_apellido = "";
     $cliente_movil = "";

     $veh_matricula = "";
     $veh_marca = "";
     $veh_modelo = "";
	 


    if ($result = mysqli_query($mysqli, $qry)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result)){                                              

                         $taller_nombre 	= $row["taller_nombre"];	 
					     $num_ot	        = $row["num_ot"];	  
					     $cliente_nombre 	= $row["cliente_nombre"];	 
					     $cliente_apellido 	= $row["cliente_apellido"];	 
					     $cliente_movil 	= $row["cliente_movil"];
					     $veh_matricula 	= $row["veh_matricula"];	 
					     $veh_marca 		= $row["veh_marca"];
						 $client_id			=$row["client_id"];
						 $taller_id			=$row["user_id"];
                    }
                    mysqli_free_result($result);
            }
    } 
if($cliente_movil == ''){
	echo json_encode(array('sms'=>'no_movil'));
}else{
    $number = $cliente_movil;
	$cliente = $cliente_nombre." ".$cliente_apellido;
    $message = 'Taller '.$taller_nombre.' le informa que el vehiculo con mat: '.$veh_matricula.' le fue asignada la OT N: '.$num_ot;
   // $result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
		echo json_encode(array('smsr'=>'ok','movil'=>$cliente_movil,'mensaje'=>$message,'cliente'=>$cliente,'taller_id'=>$taller_id,'client_id'=>$client_id,'tipo'=>$stipo));

	}
}else if($stipo==1){
	
	$qry 	= " SELECT 	ud.nombre_comer taller_nombre,"
 			. "			r.cli_nombre cliente_nombre,"
			. "			r.taller_id taller_id,"
			. "			r.cli_client_id  cli_client_id,"
 			. "			r.cli_apellidos cliente_apellido,"
 			. "			r.cli_telefono cliente_movil,"
 			. "			r.veh_matricula veh_matricula,"
 			. "			r.num_recepcion num_recepcion"
 			. "	  FROM recepciones r, user_data ud"
 			. "	 WHERE r.taller_id=ud.ID"
 			. "	   AND r.recepcion_id =".$idOt;

     $taller_nombre = "";
     $num_rec = "";

     $cliente_empresa = "";
     $cliente_nombre = "";
     $cliente_apellido = "";
     $cliente_movil = "";

     $veh_matricula = "";
     $veh_marca = "";
     $veh_modelo = "";


    if ($result = mysqli_query($mysqli, $qry)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result)){                                              

                         $taller_nombre 	= $row["taller_nombre"];	 
					     $num_rec	        = $row["num_recepcion"];	  
					     $cliente_nombre 	= $row["cliente_nombre"];	 
					     $cliente_apellido 	= $row["cliente_apellido"];	 
					     $cliente_movil 	= $row["cliente_movil"];
					     $veh_matricula 	= $row["veh_matricula"];	 
					     $veh_marca 		= $row["veh_marca"];
						 $taller_id			=$row["taller_id"];
						 $client_id     =$row["cli_client_id"];
                    }
                    mysqli_free_result($result);
            }
    } 
if($cliente_movil == ''){
	echo json_encode(array('smsr'=>'no_movil','tipo'=>$qry ));
}else{
    $number = $cliente_movil;
	
    $message = 'Taller '.$taller_nombre.' le informa que el vehiculo con mat: '.$veh_matricula.' le fue asignada la Recepción N: '.$num_rec;
    $cliente = $cliente_nombre." ".$cliente_apellido;
	echo json_encode(array('smsr'=>'ok','movil'=>$cliente_movil,'mensaje'=>$message,'cliente'=>$cliente,'taller_id'=>$taller_id,'client_id'=>$client_id,'tipo'=>$stipo));

	}
	
	
}else if($stipo==3){
	
	$qry 	= " SELECT 	ud.nombre_comer taller_nombre,"
			. "			ud.telefono telefono   		,"
			. "			ud.ID user_id,"
 			. "			p.cli_nombre cliente_nombre,"
 			. "			p.cli_apellidos cliente_apellido,"
			. "			p.client_id client_id,"
			. "			p.total total,"
 			. "			p.cli_tel cliente_movil,"
 			. "			p.mat veh_matricula,"
 			. "			p.num_pre num_pre"
 			. "	  FROM presupuesto p, user_data ud"
 			. "	 WHERE p.user_id=ud.ID"
 			. "	   AND p.id =".$idOt;

     $taller_nombre = "";
     $presupuesto_id = "";
     $cliente_empresa = "";
     $cliente_nombre = "";
     $cliente_apellido = "";
     $cliente_movil = "";
     $veh_matricula = "";
     $veh_marca = "";
     $veh_modelo = "";
	 $p_total = "";
	 $telef = "";


    if ($result = mysqli_query($mysqli, $qry)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result)){                                              

                         $taller_nombre 	= $row["taller_nombre"];	 
					     $presupuesto_id	= $row["num_pre"];	  
					     $cliente_nombre 	= $row["cliente_nombre"];	 
					     $cliente_apellido 	= $row["cliente_apellido"];	 
					     $cliente_movil 	= $row["cliente_movil"];
					     $veh_matricula 	= $row["veh_matricula"];	 
					     $veh_marca 		= $row["veh_marca"];
						 $p_total			= $row["total"];
						 $telef				= $row["telefono"];
						 $client_id			=$row["client_id"];
						 $taller_id			=$row["user_id"];
                    }
                    mysqli_free_result($result);
            }
    } 
if($cliente_movil == ''){
	echo json_encode(array('smsr'=>'no_movil','tipo'=>$qry ));
}else{
    $number = $cliente_movil;
    $message = 'Taller '.$taller_nombre.': ha realizado el presupuesto N: '.$presupuesto_id.' del vehículo con mat: '.$veh_matricula. ' por valor: '.$p_total. '€ +info: '.$telef;
    $cliente = $cliente_nombre." ".$cliente_apellido;
	echo json_encode(array('smsr'=>'ok','movil'=>$cliente_movil,'mensaje'=>$message,'cliente'=>$cliente,'taller_id'=>$taller_id,'client_id'=>$client_id,'tipo'=>$stipo));


	}
	
	
}else if($stipo==4){
	
	$qry 	= " SELECT 	ud.nombre_comer taller_nombre,"
			."			ud.telefono telefono   		,"
 			. "			f.cli_nombre cliente_nombre,"
			. "			f.total total,"
			. "			ud.ID user_id,"
			. "			f.client_id client_id,"
 			. "			f.cli_apellidos cliente_apellido,"
 			. "			f.cli_tel cliente_movil,"
 			. "			f.mat veh_matricula,"
 			. "			f.num_fact num_fact"
 			. "	  FROM factura f, user_data ud"
 			. "	 WHERE f.user_id=ud.ID"
 			. "	   AND f.id =".$idOt;

     $taller_nombre = "";
     $num_fact = "";
     $cliente_empresa = "";
     $cliente_nombre = "";
     $cliente_apellido = "";
     $cliente_movil = "";
     $veh_matricula = "";
     $veh_marca = "";
     $veh_modelo = "";


    if ($result = mysqli_query($mysqli, $qry)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result)){                                              

                         $taller_nombre 	= $row["taller_nombre"];	 
					     $num_fact			= $row["num_fact"];	  
					     $cliente_nombre 	= $row["cliente_nombre"];	 
					     $cliente_apellido 	= $row["cliente_apellido"];	 
					     $cliente_movil 	= $row["cliente_movil"];
					     $veh_matricula 	= $row["veh_matricula"];	 
					     $veh_marca 		= $row["veh_marca"];
						 $f_total 			= $row["total"];
						 $telef				= $row["telefono"];
						 $client_id			= $row["client_id"];
						 $taller_id			= $row["user_id"];
                    }
                    mysqli_free_result($result);
            }
    } 
if($cliente_movil == ''){
	echo json_encode(array('smsr'=>'no_movil','tipo'=>$qry ));
}else{
    $number = $cliente_movil;
    $message = 'Taller '.$taller_nombre.' informa que puede pasar a recoger su vehículo con mat: '.$veh_matricula.' el total de la factura  es: '.$f_total. '€ +info: '.$telef;
    $cliente = $cliente_nombre." ".$cliente_apellido;
	echo json_encode(array('smsr'=>'ok','movil'=>$cliente_movil,'mensaje'=>$message,'cliente'=>$cliente,'taller_id'=>$taller_id,'client_id'=>$client_id,'tipo'=>$stipo));


	}
	
	
}else if($stipo==5){
	$mens = $_GET['mensa'];
	
	
	$qry 	= "SELECT 	* from clients_details where client_id =".$idOt;
 
     $taller_nombre = "";
     $num_fact = "";
     $cliente_empresa = "";
     $cliente_nombre = "";
     $cliente_apellido = "";
     $cliente_movil = "";
     $veh_matricula = "";
     $veh_marca = "";
     $veh_modelo = "";


    if ($result = mysqli_query($mysqli, $qry)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result)){                                              

                         //$taller_nombre 	= $row["taller_nombre"];	 
					     $cliente_nombre 	= $row["nombre"];	 
					     $cliente_apellido 	= $row["apellido"];	 
					     $cliente_movil 	= $row["telefono"];
						 $client_id 	= $row["client_id"];
                    }
                    mysqli_free_result($result);
            }
    } 
	
	$qry2 	= "SELECT ud.nombre_comer,ud.ID   FROM user_data ud WHERE ud.id = '".$user_id."' ";
	if ($result2 = mysqli_query($mysqli, $qry2)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result2)){                                              

                         $taller_nombre 	= $row["nombre_comer"];	 
						 $taller_id 	= $row["ID"];	 
                    }
                    mysqli_free_result($result2);
            }
    } 
	
	
if($cliente_movil == ''){
	echo json_encode(array('smsr'=>'no_movil','tipo'=>$qry ));
}else{
    $number = $cliente_movil;
    $message = "Taller ".$taller_nombre.': '.$mens;
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
								$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (user_id,client_id,tipo_mensaje,status,sms_id,date,phone_client,body) VALUES ('".$taller_id."','".$client_id."','".$stipo."','".$rest3."','".$rest2."','".$hoy."','".$cliente_movil."','".$message."') ");
								mysql_query($insert_sms_sql, $con);
					}else{
						echo "no";
					}
			
			sleep(15);
					
					$id = $rest2;
					$result = $smsGateway->getMessage($id); 
					$smsresult2 = json_encode(array($result));
					$pos = strpos($smsresult2, '"id"');
					if ($pos == true){
								$rest = substr($smsresult2, $pos, 16);
								$buscar_2p = strpos($rest, ':');
								$buscar_coma = strpos($rest, ',');
								$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
								$pos2 = strpos($smsresult2, '"status"');		
								$pos3 = strpos($smsresult2, '"send_at"');	
								$poresta = $pos3-$pos2;
								$rest3 = substr($smsresult2, $pos2+10, $poresta-12);							
								$update_sms_sql = sprintf("UPDATE sms_notificaciones set status =  '".$rest3."' where sms_id = '".$rest2."'");
								mysql_query($update_sms_sql, $con);
								echo json_encode(array('smsr'=>'ok','resp'=>$rest3));
					}
					
	}
}else if($stipo==6){
	$mens = $_GET['mensa'];
	
	
	$qry 	= "SELECT 	* from clients_details where client_id =".$idOt;
 
     $taller_nombre = "";
     $num_fact = "";
     $cliente_empresa = "";
     $cliente_nombre = "";
     $cliente_apellido = "";
     $cliente_movil = "";
     $veh_matricula = "";
     $veh_marca = "";
     $veh_modelo = "";


    if ($result = mysqli_query($mysqli, $qry)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result)){                                              

                         //$taller_nombre 	= $row["taller_nombre"];	 
					     $cliente_nombre 	= $row["nombre"];	 
					     $cliente_apellido 	= $row["apellidos"];	 
					     $cliente_movil 	= $row["telefono"];
                    }
                    mysqli_free_result($result);
            }
    } 
	
	$qry2 	= "SELECT ud.nombre_comer   FROM user_data ud WHERE ud.id = '".$user_id."' ";
	if ($result2 = mysqli_query($mysqli, $qry2)) { 
            if (!mysqli_affected_rows($mysqli) == 0) {
                    while($row = mysqli_fetch_assoc($result2)){                                              

                         $taller_nombre 	= $row["nombre_comer"];	 
                    }
                    mysqli_free_result($result2);
            }
    } 
	
	
if($cliente_movil == ''){
	echo json_encode(array('smsr'=>'no_movil','tipo'=>$qry ));
}else{
    $number = $cliente_movil;
    $taller_nombre = $taller_nombre." ".$mens;
	$nombrec = $cliente_nombre." ".$cliente_apellido;
	echo json_encode(array('smsr'=>'ok','movil'=>$cliente_movil,'cliente'=>$nombrec,'mensaje'=>$taller_nombre));

	}
}else if($stipo==7){
	$mmovil = $_GET['movil'];
	$mmensaje = $_GET['mensa'];
	$mot = $_GET['idOt'];
	$mtaller = $_GET['taller'];
	$mclien = $_GET['clien'];
	$mtipo = $_GET['tip'];
	if($mmovil!="" || $mmovil!=null){
			$result = $smsGateway->sendMessageToNumber($mmovil, $mmensaje, $deviceID);
			
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
								$insert_sms_sql = sprintf("INSERT INTO sms_notificaciones (user_id,client_id,tipo_mensaje,status,sms_id,date,phone_client,body) VALUES ('".$mtaller."','".$mclien."','".$mtipo."','".$rest3."','".$rest2."','".$hoy."','".$cliente_movil."','".$message."') ");
								mysql_query($insert_sms_sql, $con);
					}else{
						//echo "no";
					}
					sleep(15);
					$id = $rest2;
					$result = $smsGateway->getMessage($id); 
					$smsresult2 = json_encode(array($result));
					$pos = strpos($smsresult2, '"id"');
					if ($pos == true){
								$rest = substr($smsresult2, $pos, 16);
								$buscar_2p = strpos($rest, ':');
								$buscar_coma = strpos($rest, ',');
								$rest2 = substr($rest, $buscar_2p+2, ($buscar_coma-3)-$buscar_2p);
								$pos2 = strpos($smsresult2, '"status"');		
								$pos3 = strpos($smsresult2, '"send_at"');	
								$poresta = $pos3-$pos2;
								$rest3 = substr($smsresult2, $pos2+10, $poresta-12);							
								$update_sms_sql = sprintf("UPDATE sms_notificaciones set status =  '".$rest3."' where sms_id = '".$rest2."'");
								mysql_query($update_sms_sql, $con);
								echo json_encode(array('smsr'=>'ok','resp'=>$rest3));
					}
					
					
	}else{
			echo json_encode(array('smsr'=>'no_movil'));
	}

}
?>
