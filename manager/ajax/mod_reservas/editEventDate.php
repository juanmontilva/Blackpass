<?php

// Conexion a la base de datos
require_once('bdd.php');
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
include('../../dist/funciones/api_ws.php');
if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
	
	
	$id = $_POST['Event'][0];
	$start = $_POST['Event'][1];
	$end = $_POST['Event'][2];

	$sql = "UPDATE events SET  start = '$start', end = '$end' WHERE id = $id ";

	
	$query = $bdd->prepare( $sql );
	if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Error');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Error');
	}else{
		$sqlc = mysqli_query($con,"select * from events where id = $id");
		$rowc = mysqli_fetch_assoc($sqlc);
		$fh = explode(" ",$rowc['start']);
		$horas = $fh[1];
		$fech =  date("M/d/Y", strtotime($fh[0]));
		$sql_m = mysqli_query($con,"select * from apps_comercio");
				$row = mysqli_fetch_assoc($sql_m);
				//informacion del cliente
				$sql_c = mysqli_query($con,"select * from apps_clientes where id = '".$rowc['id_cliente']."'");
				//echo "select * from apps_clientes where id = '$idc'";
				$row2 = mysqli_fetch_assoc($sql_c);
				//informacion de la cita//
				$sql_s = mysqli_query($con,"SELECT s.servicio,s.precio,s.tiempo, l.* 
							FROM apps_servicios_d s, apps_marcas_x_pais mp, apps_localidades l 
							WHERE l.id_loc = mp.id_pais 
							and s.id_marca = mp.id_marca 
							and l.id_loc = '".$rowc['id_local']."' 
							and s.idh = '".$rowc['idh']."'");
				$row3 = mysqli_fetch_assoc($sql_s);
				
				$sql_e = mysqli_query($con,"SELECT e.nombres FROM apps_emple_s_d d, apps_emple_s e 
							WHERE e.id = d.id_e 
							and d.id_s = '".$rowc['idh']."'
							and d.id_e = '".$rowc['id_pro']."' ");
				$row4 = mysqli_fetch_assoc($sql_e);
					if($row2['lenguaje']==1){
						$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
						saludo()." *".strtoupper($row2['nombres'])."*\n\n".
							"	_Te informamos que tu CITA fue cambiada_\n\n".
							"📋  _Reserva_: *".$rowc['localizador']."* \n\n".
							"📍  _Local_: *".$row3['localidad']."*\n\n".
							"♦️  _Servicio_: *".($row3['servicio'])."*\n\n".
							"🗓️  _Fecha y Hora_: *".$fech." a las ".$horas ."*\n\n".
							"💇🏻‍♀️ _Profesional_: *".$row4['nombres']."*\n\n".
							"💲  _Tarifa_: *".$row3['precio']." $*\n\n".
							"🕑  _Duración_: *".$row3['tiempo']." min*\n\n".
							"Recuerda llegar 5min antes de su cita\n _Puedes escribir_ *AYUDA o HELP* en cualquier momento\n\n".
							"*MC*     👉🏻   _Cambiar Cita_  🕟\n".
							"*CC*      👉🏻  _Cancelar Cita_  🚫\n".
							"*ADS*   👉🏻    _Ofertas_ 🏷️ \n";
					}else if($row2['lenguaje']==2){
						$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo2()." *".strtoupper($row2['nombres'])."*\n\n".
								"_We inform you that your APPOINTMENT has been changed_\n\n".
								"📋  _Reservation_: *".$rowc['localizador']."* \n\n".
								"📍  _Location_: *".$row3['localidad']."*\n\n".
								"♦️  _Service_: *".($row3['servicio'])."*\n\n".
								"🗓️  _Date and Time_: *".$fech." ".$horas ."*\n\n".
								"💇🏻‍♀️ _Professional_: *".$row4['nombres']."*\n\n".
								"💲  _Price_: *".$row3['precio']." $*\n\n".
								"🕑  _Duration_: *".$row3['tiempo']." min*\n\n".
								"Remember to arrive 5min before your appointment\n _You can write_ *HELP* anytime\n\n".
								"*MC*     👉🏻   _Change Appointment_  🕟\n".
								"*CC*      👉🏻  _Cancel Appointment_  🚫\n".
								"*ADS*   👉🏻    _Offers_ 🏷️ \n";
					}
							
				$send = sendMessage($row2['telefono'],($mensaje));

		
		
		die ('OK');
	}

}
//header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
