<?php
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
include('../../dist/funciones/api_ws.php');
	if($_GET['accion']=='del_event' ){
		$cl = mysqli_real_escape_string($con,(strip_tags($_GET['cli'],ENT_QUOTES)));
			$sql = "SELECT e.* FROM  events e WHERE localizador = '".$cl."'" ;
			$result = mysqli_query($con, $sql);
			if(mysqli_num_rows($result)!=0){
				$update = mysqli_query($con,"delete from events where localizador = '".$cl."'");
				if($update){
					header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
					echo json_encode(array("response" => "ok"));
				}else{
					header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
					echo json_encode(array("response" => "2"));
				}
			}else{
				header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
					echo json_encode(array("response" => "no"));
			}
	}		
	if($_GET['accion']=='add_event' ){
	$title = $_GET['title'];
	$start = $_GET['start'];
	$hora = $_GET['hora'];
	$locali = $_GET['locali'];
	$idc = $_GET['idc'];
	$n_hab = $_GET['servi'];
	$id_pro = $_GET['pro'];
	$localizador = cadena();
	//echo $idc;
	//$fech = $start." ".$hora.":00";
	$fech = $start;
	$sql_s = mysqli_query($con,"select m.* from apps_servicios_d s, apps_marcas m
						where s.id_marca = m.id_marca
						and s.idh = '$n_hab'  ");
	$row_s = mysqli_fetch_assoc($sql_s);
	$end = strtotime('+60 minute', strtotime($fech));
	
	$end = date("Y-m-d H:i:s",$end);
	$sqli = mysqli_query($con,"SELECT * FROM events where id_cliente = '$idc' and  idh = '".$row_s['id_marca']."' 
			and start = '$fech' and id_local = '$locali' ");
	
	
	if(mysqli_num_rows($sqli)==0){
	$title ="Reserva: ".$title;
	$insert = mysqli_query($con,"INSERT INTO events(id_cliente,title, start, end,id_pro,idh,id_local,localizador,status) values 
							('$idc','$title', '$fech', '$end', '$id_pro','".$row_s['id_marca']."','$locali','$localizador',0)");
		
			if($insert){
				//informacion del comercio//
				$sql_m = mysqli_query($con,"select * from apps_comercio");
				$row = mysqli_fetch_assoc($sql_m);
				//informacion del cliente
				$sql_c = mysqli_query($con,"select * from apps_clientes where id = '$idc'");
				//echo "select * from apps_clientes where id = '$idc'";
				$row2 = mysqli_fetch_assoc($sql_c);
				//informacion de la cita//
				$sql_s = mysqli_query($con,"SELECT s.servicio,s.precio, l.* 
							FROM apps_servicios_d s, apps_marcas_x_pais mp, 
							apps_localidades l, apps_marcas m
							WHERE l.id_loc = mp.id_pais 
							and s.id_marca = mp.id_marca 
							and l.id_loc = '$locali' 
							and s.idh = '$n_hab'  
							and s.id_marca = m.id_marca");
							
				$row3 = mysqli_fetch_assoc($sql_s);
				
				$sql_e = mysqli_query($con,"SELECT e.nombres FROM apps_emple_s_d d, apps_emple_s e 
							WHERE e.id = d.id_e 
							and d.id_s = '$n_hab'
							and d.id_e = '$id_pro' ");
				$row4 = mysqli_fetch_assoc($sql_e);
				$mensaje =  utf8_encode($row['titulo'])."\n\n".
							saludo()." *".strtoupper($row2['nombres'])."*\n".
							"Appointment: *".$localizador."* \n".
							"Location: *".$row3['localidad']."*\n".
							"Service: *".($row3['servicio'])."*\n".
							"Date: *".$fech."*\n".
							"Executive: *".$row4['nombres']."*\n".
							"Please be reminded to arrive 5 min before your appointment";
				$send = sendMessage($row2['telefono'],($mensaje));
				header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok',
								"disponi" => ''
							));
					}else{
						header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => 'ex'
									));
					}
			}
		}
				
				if($_GET['accion']=='ver_h'){
					$clie = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
					$loca = mysqli_real_escape_string($con,(strip_tags($_GET["local"],ENT_QUOTES)));
					$serv = mysqli_real_escape_string($con,(strip_tags($_GET["serv"],ENT_QUOTES)));
					$fecha = mysqli_real_escape_string($con,(strip_tags($_GET["fech"],ENT_QUOTES)));
					$pro = mysqli_real_escape_string($con,(strip_tags($_GET["pro"],ENT_QUOTES)));
					$hoy_es = saber_dia($fecha);
					
					$sql_h = mysqli_query($con,"select * from apps_horarios 
									where id_s = '$loca' and dia = '$hoy_es' ");
					
					$row_h = mysqli_fetch_assoc($sql_h);
					//echo $row_h['hora_i'];
					$from = $row_h['hora_i'];
					$to = $row_h['hora_f'];
					$fecha_i = $fecha." ".$row_h['hora_i'].":00";
					$fecha_f = $fecha." ".$row_h['hora_f'].":00";
					$sql_f = mysqli_query($con,"SELECT * FROM events WHERE 
						    start BETWEEN  '".$fecha_i."' and '".$fecha_f."' 
							and idh =  '$serv' and id_local = '$loca' and id_pro = '$pro' ");
					$dateTest = new DateTime($fecha_i);
					$i = explode(":",$row_h['hora_i']);
					$i = $i[0];
					$f = explode(":",$row_h['hora_f']);
					$f = $f[0];
					$h_disponible = array();
					$hd = array();
					$lista_h = array();
					if(mysqli_num_rows($sql_f) != 0){
						$row_f = mysqli_fetch_assoc($sql_f);
						$h = explode(" ",$row_f['start']);
						$h = explode(":",$h[1]);
						$hora = $h[0].":".$h[1];
						for ($a = $i; $a <= $f; $a++) {
						$input = $dateTest->format('H:i');
						$f_validar = $fecha." ".$input.":00";
						$sql_dh = mysqli_query($con,"SELECT * FROM events WHERE 
									start = '$f_validar' and end <= '$fecha_f' and idh =  '$serv' 
									and id_local = '$loca' and id_pro = '$pro' ");
						if(mysqli_num_rows($sql_dh)!=0 ){
							$rowh = mysqli_fetch_assoc($sql_dh);
							$hi = explode(" ",$rowh['start']);
							$hf = explode(" ",$rowh['end']);
							$hd[] = intervaloHora( $hi[1], $hf[1] );
						}			
						else if(mysqli_num_rows($sql_dh)==0 ){
							$h_disponible[] = $input;
						}
						//echo "$from <= $input <= $to -> " . (hourIsBetween($from, $to, $input) ? 'Yes' : 'No') . "<br>";
						$dateTest->modify("+1 hour");
						}
						//sacamos las horas entre rangos
							foreach ($hd as $v) {
								foreach($v as $y){
									$lista_h[] = $y;
								}
							}
						  $lista_simple = array_values(array_unique($lista_h));
						    foreach ($h_disponible as $z) {
								if(in_array($z, $lista_simple)) {
									//echo $z."\n";
								}else{
									$array_h[] = array("hora"=>$z);
								}
								
							}
							$resultado = array_diff_assoc($lista_simple, $h_disponible);
							//print_r($resultado);
					}else{
						
						for ($a = $i; $a <= $f; $a++) {
						$input = $dateTest->format('H:i');
						$array_h[] =	array("hora" => $input);
						$dateTest->modify("+1 hour");
						}
					}
					//$request_table_m = $h_disponible;
					$request_table_m = $array_h;
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"disponi" => $request_table_m
					));

				}
				if($_GET['accion']=='search_h'){
				$datos1 = array();
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nhab"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT s.* FROM apps_servicios_d s, apps_marcas_x_pais mp, apps_localidades l WHERE 
									 s.id_marca = mp.id_marca
									 and l.id_loc = mp.id_pais
									 and mp.id_pais = '$nik' and mp.status = 1 and s.id_marca <> 101");
				//echo "SELECT * FROM apps_servicios_d WHERE id_marca = '$nik' and status = 1";
				if(mysqli_num_rows($cek) != 0){
					while($row = mysqli_fetch_assoc($cek)){
					$datos1[] =	array("idh" => $row['idh'],
									"codigo" => $row['servicio']);
					}
					
					$request_table_m = $datos1;
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"habitacion" => $request_table_m
					));
					
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'no'
					));
				}
			}
			
			if($_GET['accion']=='transfrir'){
				$cita = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
				$prof = mysqli_real_escape_string($con,(strip_tags($_GET["prof"],ENT_QUOTES)));
				$sql = mysqli_query($con,"select * from events where id = '$cita' ");
				if(mysqli_num_rows($sql)!=0){
					$update = mysqli_query($con,"update events set id_pro = '$prof' where id = '$cita'");
					if($update){
						header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok'
						));
					}else{
						header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
						));
					}
				}
			}
				if($_GET['accion']=='search_e'){
				$datos1 = array();
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nhab"],ENT_QUOTES)));
				$local = mysqli_real_escape_string($con,(strip_tags($_GET["local"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT e.* FROM apps_emple_s e, apps_emple_s_d d 
						where e.id = d.id_e and d.id_s = '$nik' and e.estado = 1 and e.id_local = '$local'  ");
				//echo "SELECT * FROM apps_servicios_d WHERE id_marca = '$nik' and status = 1";
				if(mysqli_num_rows($cek) != 0){
					while($row = mysqli_fetch_assoc($cek)){
					$datos1[] =	array("idh" => $row['id'],
									"codigo" => $row['nombres']);
					}
					
					$request_table_m = $datos1;
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"habitacion" => $request_table_m
					));
					
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'no'
					));
				}
			}
?>