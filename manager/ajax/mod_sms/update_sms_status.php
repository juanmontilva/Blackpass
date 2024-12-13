<?
require('../../../vzla/wp-load.php');//libreria para ejecutar wp_mail
$base_url="https://www.guiadetalleres.es/";
include_once ('../cnfg/sms_config_esp.php');
//Script para el cron, a ejecutar todas las noches a partir de las 00:00 horas
$con = mysql_connect("localhost", "webmaster_vzla", "w=@,Fb*D");
mysql_set_charset('utf8',$con);
mysql_select_db("vzla", $con) or die(mysql_error());
$idsms = $_POST['id'];
$sms_status = $_POST['status'];

$query5 = "SELECT * FROM sms_notificaciones";//Informacion del sms
$result5 = mysql_query($query5,$con);
$i = 0;
while ($rows5 = mysql_fetch_assoc($result5)){
	$alarms5[$i]['id_sms'] = $rows5['sms_id'];//Obtenemos el ID del taller
	$id = $alarms5[$i]['id_sms'];
	$result = $smsGateway->getMessage($id); 
	//echo json_encode(array($result))."<br>";
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
								$update_sms_sql = sprintf("UPDATE sms_notificaciones set status =  '".$rest3."' where sms_id = '".$rest2."'");
								mysql_query($update_sms_sql, $con);
								//echo $update_sms_sql."<br>";
					}
	$i++;
}

?>