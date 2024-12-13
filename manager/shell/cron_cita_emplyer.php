<?php
include("dist/funciones/funciones.php");
include('dist/funciones/api_ws.php');
include("dist/funciones/conexion.php");


$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
$cadena = substr(str_shuffle($permitted_chars), 0, 36);			
	$mensaje = "";
	$local = array();
	$j=1;
	
	$ciudad = $localidad = "";
	$sql_m = mysqli_query($con,"select * from apps_comercio");
	$row3 = mysqli_fetch_assoc($sql_m);

$date= date('Y-m-d'); 
$newDate = strtotime ( '+1 day' , strtotime ($date) ) ; 
$newDate = date ('Y-m-d' , $newDate); 
$fecha_f = $newDate." 23:59:00";
$fecha_i = $newDate." 00:07:00";
$fecha = $newDate;
echo $fecha_i." - ".$fecha_f."<br>";
	$sql = mysqli_query($con,"SELECT * FROM apps_emple_s where estado = 1 ");
	
	
	if(mysqli_num_rows($sql) != 0){
			while($row= mysqli_fetch_assoc($sql)){	
				$sql_c = mysqli_query($con,"SELECT e.*, s.precio, s.servicio, l.localidad, p.nombres as prof, c.nombres 
								FROM apps_emple_s p, events e, apps_servicios_d s,
								apps_clientes c, apps_localidades l
								WHERE 
								start BETWEEN  '".$fecha_i."' and '".$fecha_f."'
								and s.idh = e.idh
								and c.id = e.id_cliente
								and p.id = e.id_pro
								and l.id_loc = e.id_local
								and p.id = '".$row['id']."'
								ORDER BY start ASC");
							
					if(mysqli_num_rows($sql_c)!=0){
						$fech =  date("M/d/Y", strtotime($fecha));
						$mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
									saludo()." *".strtoupper($row['nombres'])."*\n".
									"_Te recordamos los servicios del_:\n  *".$fech."*\n\n";
					while($row2= mysqli_fetch_assoc($sql_c)){
						$fh = explode(" ",$row2['start']);
						$hora = $fh[1];
						
						
									$mensaje .= "Cliente: *".$row2['nombres']."* \n".
									"👉🏻 Localizador: *".$row2['localizador']."* \n".
									"♦️ Servicio: *".$row2['servicio']."* \n".
									"💲 Precio: *".$row2['precio']."* \n\n".
									"⏱️ Hora: *".$hora."*\n".
									"-------------------------------------------------\n";
					}
						
					//$chatId = "573132318907@c.us";			
					$chatId = $row['telefono']."@c.us";
					sendMessage($chatId,$mensaje);
					echo $mensaje. "<br>";
				}
				
			$mensaje = "";
			}
			$chatId = "573132318907@c.us";
			$mensaje = "Recordatorio Empleados";
			//sendMessage($chatId,$mensaje);
			
			//echo $mensaje. "<br>";
			
}else{
	$chatId = "573132318907@c.us";
			$mensaje = "No hay recordatorio empleados";
			sendMessage($chatId,$mensaje);
}