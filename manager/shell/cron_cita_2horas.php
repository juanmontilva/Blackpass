<?php
include("dist/funciones/funciones.php");
include('dist/funciones/api_ws.php');
include("dist/funciones/conexion.php");
function ads(){
$nombres = 	$file_ads  = $texto = "";
	include("../dist/funciones/conexion.php");	
	$cek = mysqli_query($con, "SELECT * FROM apps_ads  WHERE 
						status = 2  ORDER BY rand() LIMIT 1 ");
		if(mysqli_num_rows($cek) == 0){
			$file_ads = "logow.png";
			
			$texto =  "Disculpa en este momento no tenemos promocines activas";
		}else{
			$row = mysqli_fetch_assoc($cek);
			$texto = $row['texto'];
			$file_ads = $row['file'];
		}

		return ($file_ads);
}


$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
$cadena = substr(str_shuffle($permitted_chars), 0, 36);			
	$mensaje = "";
	$local = array();
	$j=1;
	
	$ciudad = $localidad = "";
	$sql_m = mysqli_query($con,"select * from apps_comercio");
	$row3 = mysqli_fetch_assoc($sql_m);

$date= date('Y-m-d H:i:s'); 
$newDate = strtotime ( '+2 hour' , strtotime ($date) ) ; 
$newDate = date ( 'Y-m-d H:i:s' , $newDate); 
$fecha_f = $newDate;
$fecha_i = $date;
echo $fecha_i." - ".$fecha_f;
	$sql = mysqli_query($con,"SELECT e.*, s.servicio, l.localidad, p.nombres as prof, c.nombres 
								FROM apps_emple_s p, events e, apps_servicios_d s,
								apps_clientes c, apps_localidades l
								WHERE 
								start BETWEEN  '".$fecha_i."' and '".$fecha_f."'
								and s.idh = e.idh
								and c.id = e.id_cliente
								and p.id = e.id_pro
								and l.id_loc = e.id_local
								ORDER BY start ASC");
	
	
	if(mysqli_num_rows($sql) != 0){
		
		

			$sql_c = mysqli_query($con,"SELECT p.* FROM apps_localidades l, 
								apps_provincias p WHERE p.codprovincia = l.id_provincia 
								and p.id_pais = 4 group by p.codprovincia");

			while($row= mysqli_fetch_assoc($sql)){
				$sql1 = mysqli_query($con, "SELECT * FROM apps_clientes  WHERE id = '".$row['id_cliente']."'");
			    $row2 = mysqli_fetch_assoc($sql1);
				$mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
								saludo()." *".strtoupper($row2['nombres'])."*\n".
								"_Te recordamos que tu cita esta proxima_ \n\n".
								"👉🏻 Localizador: *".$row['localizador']."* \n".
								"♦️ Servicio: *".$row['servicio']."* \n".
								"🗓️ Fecha: *".$row['start']."* \n".
								"📍 Localidad: *".$row['localidad']."* \n\n";
								"Recuerda que puedes escribir *AYUDA o HELP* en cualquier momento\n".
								"*MC*     👉🏻   _Cambiar Cita_  🕟\n".
								"*CC*      👉🏻  _Cancelar Cita_  🚫\n".
								"*PC*      👉🏻  _Pagar Cita_ 💲\n".
								"*ADS*   👉🏻    _Ofertas_ 🏷️ \n";
								
			$file = ads();
			$chatId = $row2['telefono']."@c.us";
			sendFile($chatId,$file,$mensaje);
			//echo $mensaje. "<br>";
			}
			$chatId = "573132318907@c.us";
			$mensaje = "cita 2 enviadas recordatorio";
			sendMessage($chatId,$mensaje);
			
			//echo $mensaje. "<br>";
			
}else{
	$chatId = "573132318907@c.us";
			$mensaje = "no hay citas a 2 horas";
			sendMessage($chatId,$mensaje);
}