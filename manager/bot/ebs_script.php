<?php
header('Content-Type: text/html; charset=UTF-8');
include("funciones/funciones.php");
include('funciones/api_ws.php');

function consulta_welcome($a,$b,$c,$d,$e){
include("funciones/conexion.php");
include('funciones/cript.php');	
	 $mensaje = $banner = "";
	 $aux = 0;
	$sql = mysqli_query($con,"SELECT * FROM apps_clientes where telefono = '".$a."' ");
	$sql2 = mysqli_query($con, "SELECT * FROM apps_welcome  WHERE 
						  chat_id = '".$b."' and fecha = '".$c."'");
	if(mysqli_num_rows($sql) == 0 ){  // VALIDAMOS SI EL CLIENTE EXISTE
		if(mysqli_num_rows($sql2) == 0){ // MENSAJE DE BIENVENIDA
				$mensaje = "*Bienvenido a EBS /Welcome to EBS* \n\n".
				"*Envia / Send*:\n\n".
	             "*1*  👉🏻 Español\n".
				 "*2*  👉🏻 English";
				 $aux = 1;
				 $banner = "1.png"; 
		}else{ //SINO EXISTE PERO YA SE DIO BIENVENIDA
			$row = mysqli_fetch_assoc($sql2);
			if($row['status']==1){
				$respuesta = paso1($a,$b,$e);
				return array("b",$respuesta);
			}else if($row['status']==2){
				$respuesta = paso2($a,$b,$e);
				return array("c",$respuesta);
			}else if($row['status']==3){
				$respuesta = paso3($a,$b,$e);
				return array("d",$respuesta);
			}
			
		}
				
	}else{
		
		$row2 = mysqli_fetch_assoc($sql);
		if(mysqli_num_rows($sql2) == 0){
			 if($row2['lenguaje']==1){
				 $banner = "1.png"; 
				 $nombre = $row2['nombres'];
				 $mensaje = saludo()." *$nombre* \n\n".
							"Bienvenido a *EBS*\n\n".
							"¿Como podemos ayudarte el dia de hoy?\n\n".
							"Escribe el Numero de la opcion a realizar:\n\n".
							"*1*  👉🏻 Hacer una Cita\n".
							"*2*  👉🏻 Solicitar Información\n".
							"*3*  👉🏻 Descargar Tax".
							"Escribe *INICIO* para repetir el mensaje";
			 }else if($row2['lenguaje']==2){
				 $nombre = $row2['nombres'];
				 $banner = "2.png"; 
				 $mensaje = saludo2()." *$nombre* \n\n".
							"Welcome to *EBS*\n\n".
							"How can we help you today?\n\n".
							"Write the Number of the option to perform:\n\n".
							"*1*  👉🏻 Make an appointment\n".
							"*2*  👉🏻 Ask for information\n".
							"*3*  👉🏻 Download Tax".
							"Type *HOME* to repeat the message";
			 }
			 $aux = 2;
		}else{
			$row = mysqli_fetch_assoc($sql2);
			if($row['status']==0 && $e==1){
				$mensaje = "";
				$respuesta = opc_cita($a,$b,$e,$row2['lenguaje']);
				return array("e",$respuesta,$row['id']);
			}else if($row['status']==2){ // SELECCION DE SERVICIO Y SOLICITUD DE FECHA
				$respuesta = validar_s($e);
				if($respuesta==0){
					 if($row2['lenguaje']==1){
					 $mensaje = "📋 *CODIGO* incorrecto 🚫  \n\n".
									"Por favor ingresa el *CODIGO* del servicio: \n\n".
									"_Ejemplo: *BO*_";
					 }else  if($row2['lenguaje']==2){
						 $mensaje = "📋 Incorrect *CODE* 🚫  \n\n".
										"Please enter the *CODE* of the service: \n\n".
										"_Example: *BO*_";
					 }
				}else{
					if($row2['lenguaje']==1){
						$mensaje = "📅 Indica la fecha que deseas ser atendido\n".
									"⚠️ El formato de fecha debe ser *MM/DD/AAAA* ⚠️ ";
					}else if($row2['lenguaje']==2){
						$mensaje = "📅 Indicate the date you want to be attended\n".
									"⚠️ The date format must be *MM/DD/AAAA* ⚠️ ";
					}
					
				}		
				return array("f",$mensaje,$row['id']);
			}else if($row['status']==3){ 
				$respuesta = ver_agenda($a,$b,$e,$row2['lenguaje']);
				if($respuesta[0]==10){
					return array("g",$respuesta[1],$row2['lenguaje'],$respuesta[0]);
				}else if($respuesta[0]==11 || $respuesta[0]==14 || $respuesta[0]==15 || $respuesta[0]==0){
					return array("g",0,$row2['lenguaje'],$respuesta[0]);
				}
			}else if($row['status']==4){ 
				 $respuesta = validar_h($a,$b,$e,$row2['lenguaje']);
				 sendMessage($a,"entra");
				 if($respuesta[0]==1){
					 return array("h",$respuesta[1],$row2['lenguaje'],$respuesta[0]);
				 }if($respuesta[0]==0){
					 return array("h",$row2['lenguaje'],$respuesta[0]);
				 }
			}else if($row['status']==0 && $e==3){
				if($row2['lenguaje']==1){
					 $mensaje = "📋 *Sección de Tax*  \n\n".
									"_Necesito validar unos datos por seguridad!_ \n\n".
									"🔐Ingresa los 4 Ultimos digitos de tu SSN🔐";
				}else  if($row2['lenguaje']==2){
						 $mensaje = "📋 *Tax Section* \n\n".
										"_I need to validate some data for security!_ \n\n".
										"🔐Enter the last 4 digits of your SSN 🔐";
				}
				return array("i",$mensaje);
			}else if($row['status']==21 ){
				$key = 0;
				$sql2 = mysqli_query($con, "SELECT * FROM apps_clientes  WHERE 
						  telefono = '".$a."' and ssn = '".$encriptar($e)."'");
				
				if(mysqli_num_rows($sql2) == 1){
					$key = 1;
					if($row2['lenguaje']==1){
					 $mensaje = "📋 *Gracias!!*  \n\n".
									"¿Que año deseas consulta?\n\n".
									"⚠️_Solo manejo informacion de los 4 ultimos años_⚠️\n:".
									"2018\n".
									"2019\n".
									"2020\n".
									"2021\n";
					}else  if($row2['lenguaje']==2){
							 $mensaje = "📋 *Thanks* \n\n".
											"What year do you want to consult? \ N \ n".
											"⚠️_I only handle information from the last 4 years_⚠️ \ n".
											"2018\n".
											"2019\n".
											"2020\n".
											"2021\n";
					}
				}else{
					if($row2['lenguaje']==1){
						$mensaje = "🚫Disculpa no logre validar tu información🚫 \n".
									"Por favor llama a tu asesora para mayor información";
					}else if($row2['lenguaje']==2){
						$mensaje = "🚫Sorry I could not validate your information🚫 \n".
									"Please call your advisor for more information";
					}
				}
				return array("j",$mensaje,$key);		
			}else if($row['status']==31 ){
				$tax_f = "";
				$key = 0;
				$sql = mysqli_query($con,"SELECT t.file FROM apps_clientes c, apps_cl_tax t
							WHERE c.id = t.idc
							and t.year = '".$e."'
							and c.telefono = '$a'");
					$lola = "SELECT t.file FROM apps_clientes c, apps_cl_tax t
							WHERE c.id = t.idc
							and t.year = '".$e."'
							and c.telefono = '$a'";
				sendMessage($a,$lola);
				if(mysqli_num_rows($sql2) == 1){
					$row = mysqli_fetch_assoc($sql);
					$tax_f = $row['file'];
					$key = 1;
				}else{
					$key = 0;
					if($row2['lenguaje']==1){
						$tax_f = "😔Disculpa no logre encontrar la información😔 \n".
									"Por favor llama a tu asesora para mayor información\n\n".
									"Si deseas consultar otro año escribelo ahora\n".
									"⚠️_Solo manejo informacion de los 4 ultimos años_⚠️\n:".
									"2018\n".
									"2019\n".
									"2020\n".
									"2021\n";;
					}else if($row2['lenguaje']==2){
						$tax_f = "😔Sorry I couldn't find the information😔 \n".
									"Please call your advisor for more information".
									"If you want to consult another year or write it now \n".
									"⚠️_I only handle information from the last 4 years_⚠️ \ n".
											"2018\n".
											"2019\n".
											"2020\n".
											"2021\n";
					}
				}
				return array("k",$tax_f,$key);
			}
		}
	}
	return array("a",$mensaje,$aux, $banner);
}
function paso1($a1,$b1,$c1){
	$mensaje = "";
	
		if($c1==1){
			$mensaje = "Por favor marca la siguiente opción;\n".
					   "*1*  👉🏻   Persona 🙋🏻‍♂️\n".
                       "*2*  👉🏻   Negocio 🏭\n";					   
		}else if($c1==2){
			$mensaje = "Please check the next option;\n".
					   "*1*  👉🏻   Person 🙋🏻‍♂️\n".
                       "*2*  👉🏻   Business 🏭\n";					   
		}
		return ($mensaje);
}
function paso2($a1,$b1,$c1){
	include("funciones/conexion.php");
	$mensaje = "";
	$sql_1 = mysqli_query($con,"SELECT * from aux_clientes where chatid = '$b1' ");
	if(mysqli_num_rows($sql_1)!=0){
		$row = mysqli_fetch_assoc($sql_1);
		if($row['idioma']==1){
			$mensaje = "🙋🏻‍♂�?*Por favor ingresa tu nombre*:\n";					   
		}else if($c1==2){
			$mensaje = "🙋🏻‍♂�?*Please enter your name*:\n";					   
		}
	}
		
		return ($mensaje);
}
function paso3($a1,$b1,$c1){
	include("funciones/conexion.php");

	$mensaje = "";
	$sql_1 = mysqli_query($con,"SELECT * from aux_clientes where chatid = '$b1' ");
	if(mysqli_num_rows($sql_1)!=0){
		$row = mysqli_fetch_assoc($sql_1);
			if($row['idioma']==1){
				 $banner = "1.png"; 
				 $nombre = $row['nombre'];
				 $mensaje = saludo()." *$nombre* \n\n".
							"Bienvenido a *EBS*\n\n".
							"¿Como podemos ayudarte el dia de hoy?\n\n".
							"Escribe el Numero de la opcion a realizar:\n\n".
							"*1*  👉🏻 Hacer una Cita\n".
							"*2*  👉🏻 Solicitar Información\n";
			 }else if($c1==2){
				 $nombre = $row['nombre'];
				 $banner = "2.png"; 
				 $mensaje = saludo2()." *$nombre* \n\n".
							"Welcome to *EBS*\n\n".
							"How can we help you today?\n\n".
							"Write the Number of the option to perform:\n\n".
							"*1*  👉🏻 Make an appointment\n".
							"*2*  👉🏻 Ask for information\n";
			 }
		
		
	}
		
		return ($mensaje);
}
function opc_cita($a2,$b2,$c2,$d2){
	if($c2==1){
		$r = consulta_s($b2,$c2,$d2);
		return $r;
	}
}
function consulta_s($x,$y,$z){
include("funciones/conexion.php");
$servicios = $titulo = "" ;
	
			 $sql_s = mysqli_query($con,"SELECT m.* FROM apps_marcas_x_pais mp, apps_marcas m 
								WHERE m.id_marca = mp.id_marca ");
				
			 if(mysqli_num_rows($sql_s)!=0){
				 while($row=mysqli_fetch_assoc($sql_s)){
					 $sql = mysqli_query($con,"SELECT * FROM apps_servicios_d 
							where id_marca = '".$row['id_marca']."' and status = 1");
					//while($row2=mysqli_fetch_assoc($sql)){
						$servicios .= "*".$row['identificador']."*   👉🏻    ".$row['marca']."\n";
					 //}
					 if($z==1){
						 $titulo = "📋 _Escribe el *CODIGO* del servicio_: \n\n".
									"*COD*         *SERVICIO*\n\n";
					 }else if($z==2){
						$titulo = "📋 _Write the *CODE* of the service_: \n\n".
									"*COD*         *SERVICE*\n\n";
					 }
					 
					 $titulo = $titulo."".$servicios."\n";
				 }
			 }
			 return ($titulo);
							
}
function validar_s($x){
	include("funciones/conexion.php");

			 $sql_s = mysqli_query($con,"SELECT m.* FROM apps_marcas m 
								WHERE m.identificador = '".$x."' ");
			 return mysqli_num_rows($sql_s);
}
function ver_agenda($x,$y,$z,$zz){
		
include("funciones/conexion.php");
	$hoy_s = date("m/d/Y");
	//$x =  date("d/m/Y", strtotime($x));
	//$fechax = explode("/",$x);
	$fechax = explode("/",$z);
	$mesx = 0;

	$z = $fechax[0]."/".$fechax[1]."/".$fechax[2];
	//$x = $fechax[1]."/".$mesx."/".$fechax[2];
	$enva = $z." a ".$hoy_s;
	//sendMessage($y,$enva);
	//$valida = var_dump(validateDate($x, 'd/m/Y'));
	$validar = (validateDate($z, 'm/d/Y') ? '1' : '0'); 
	if($validar == 1){
		$fecha_actual = date("Y-m-d");
		$fecha_e = explode("/",$z);
		$fecha_entrada = $fecha_e[2]."-".$fecha_e[0]."-".$fecha_e[1];
		$dias_t = dias_transcurridos($fecha_entrada,$fecha_actual);
		if(saber_dia($fecha_entrada)=="Domingo"){
			 return array(15,$dias_t);
		}else{
		//$dias_t = dias_transcurridos($fecha_entrada,$fecha_actual);
		$fecha_fin = date("m-d-Y",strtotime($fecha_actual."+ 30 days"));
		$dias_t = verifica_rango($fecha_actual,$fecha_fin,$fecha_entrada);
		 
		//sendMessage($y,$dias_t);
		if($dias_t == 0){
				return array(14,$dias_t);
		}else if ($dias_t==30){
			return array(11,$dias_t);
		}
		else if($dias_t==1){
			$f = explode("/",$z);
			$f = $f[2]."-".$f[0]."-".$f[1]." 00:00:00";
			$update = mysqli_query($con, "UPDATE apps_temp SET  status = 4, fecha_i = '".$f."' 
			WHERE chatID = '".$y."'") or die(mysqli_error());
			
			$sql = mysqli_query($con,"SELECT * FROM apps_temp where chatID = '$y'");
			if(mysqli_num_rows($sql)!=0){
				
				$row = mysqli_fetch_assoc($sql);

				$qdia = saber_dia($fecha_entrada);
				$sqlv_d = mysqli_query($con,"select h.* from apps_horarios h
							where  dia = '$qdia' ");
		
				if(mysqli_num_rows($sqlv_d)==0){
					sendMessage($y,$qdia);
					// return array(11,$dias_t);
				}else {
					
					$sql3 = mysqli_query($con,"select * from apps_marcas where identificador = '".$row['serv']."'");
					if(mysqli_num_rows($sql3)!=0){
					$row3 = mysqli_fetch_assoc($sql3);
					$respuesta = horas_d($f,$y,'68',$row3['id_marca'],'17');
					//sendMessage($y,$respuesta);
					return array(10,$respuesta);
					}
				}
				
			}
		}
		}
	}else{
		return array(0,$dias_t);
	}
}
function horas_d($fecha,$chatid,$loca,$serv,$pro){
	include("funciones/conexion.php");
	$hoy_es = saber_dia($fecha);
	$horas_v = "";
	$sql_h = mysqli_query($con,"select * from apps_horarios 
				where id_s = '$loca' and dia = '$hoy_es' ");					
	$row_h = mysqli_fetch_assoc($sql_h);
	//echo $row_h['hora_i'];
	$from = $row_h['hora_i'];
	$to = $row_h['hora_f'];
	$fecha = explode(" ",$fecha);
	$fecha = $fecha[0];
	$fecha_i = $fecha." ".$row_h['hora_i'].":00";
	//echo $fecha_i;
	$fecha_f = $fecha." ".$row_h['hora_f'].":00";
	$sql_f = mysqli_query($con,"SELECT * FROM events WHERE 
				    start BETWEEN  '".$fecha_i."' and '".$fecha_f."' 
					 and id_local = '$loca' and id_pro = '$pro' ");
					/*$sql_f = mysqli_query($con,"SELECT * FROM events WHERE 
				    start BETWEEN  '".$fecha_i."' and '".$fecha_f."' 
					and idh =  '$serv' and id_local = '$loca' and id_pro = '$pro' ");*/
	$dateTest = new DateTime($fecha_i);
	$i = explode(":",$row_h['hora_i']);
	$i = $i[0];
	$f = explode(":",$row_h['hora_f']);
	$f = $f[0];
	$h_disponible = array();
	$hd = array();
	$lista_h = array();
	$a = 0;
	if(mysqli_num_rows($sql_f) != 0){
	$row_f = mysqli_fetch_assoc($sql_f);
	$h = explode(" ",$row_f['start']);
	$h = explode(":",$h[1]);
	$hora = $h[0].":".$h[1];
	for ($a = $i; $a <= $f; $a++) {
		$input = $dateTest->format('H:i');
		$f_validar = $fecha." ".$input.":00";
		$sql_dh = mysqli_query($con,"SELECT * FROM events WHERE 
				start = '$f_validar' and end <= '$fecha_f'  
				and id_local = '$loca' and id_pro = '$pro' ");
			/*	$sql_dh = mysqli_query($con,"SELECT * FROM events WHERE 
				start = '$f_validar' and end <= '$fecha_f' and idh =  '$serv' 
				and id_local = '$loca' and id_pro = '$pro' ");*/
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
		}else{						
			for ($a = $i; $a <= $f; $a++) {
				$input = $dateTest->format('H:i');
				$array_h[] =	array("hora" => $input);
				$dateTest->modify("+1 hour");
			}
		}

					//$request_table_m = $h_disponible;
		$request_table_m = $array_h;
		$e=0;
		$sql_x = mysqli_query($con,"SELECT * FROM apps_welcome where chat_id = '".$chatid."'");
		$row_x = mysqli_fetch_assoc($sql_x);
		foreach($request_table_m as $p){
			
			foreach($p as $d){
				$e++;
				if($e<10){
					$horas_v .= "*".$e."*    👉🏻    ".$d."\n";
				}else{
					$horas_v .= "*".$e."*  👉🏻    ".$d."\n";
				}
				 
				$insert_aux = mysqli_query($con,"INSERT INTO aux_horas (idw,hora,ref)
								VALUES ('".$row_x['id']."','".$d."','$e') ");
				
			}
			
		}
		//sendMessage($chatid,"hola '$e'");
		if($e>0){
			return $horas_v;
		}else{
			return 15;
		}
		//echo $horas_v;
		//sendMessage($chatid,$horas_v);
		
}
function validar_h($w,$x,$y,$z){
include("funciones/conexion.php");
$mensaje = "";
$sql_a = mysqli_query($con,"select * from  aux_horas a, apps_welcome w
						where a.ref = '$y' 
						and a.idw = w.id
						and w.chat_id = '$x'   ");
		
		if(mysqli_num_rows($sql_a)!=0){
			 $row_a = mysqli_fetch_assoc($sql_a);
			 $sql_x = mysqli_query($con,"SELECT * FROM apps_temp where chatID = '$x'");
			 if(mysqli_num_rows($sql_x)!=0){
				 $row_x = mysqli_fetch_assoc($sql_x);
				 $localizador = cadena();
				//echo $idc;
				$hora = $row_a['hora'];
				$n_hab = $row_x['serv'];

				$phone = explode("@",$y);
				$phone = $phone[0];
				$sql3 = mysqli_query($con,"SELECT * FROM apps_clientes where telefono = '".$w."' ");
				if(mysqli_num_rows($sql3)!=0){
					$row3 = mysqli_fetch_assoc($sql3);
					$locali = 68;
					$f = explode(" ",$row_x['fecha_i']);
					$f = $f[0];
					$fech =$f." ".$hora.":00";
					$idc = $row3['id'];
					$id_pro = 17;
					$sql_s = mysqli_query($con,"select * from apps_marcas
										where identificador = '$n_hab'  ");
					$row_s = mysqli_fetch_assoc($sql_s);
					$end = strtotime('+60 minute', strtotime($fech));
					
					$end = date("Y-m-d H:i:s",$end);
					$sqli = mysqli_query($con,"SELECT * FROM events where id_cliente = '$idc' and  idh = '$n_hab' 
							and start = '$fech' and id_local = '$locali' ");
					if(mysqli_num_rows($sqli)==0){
						$title ="Reserva: ".$localizador;
						
						$insert = mysqli_query($con,"INSERT INTO events(id_cliente,title, start, end,id_pro,idh,id_local,localizador) values 
												('$idc','$title', '$fech', '$end', '$id_pro','".$row_s['id_marca']."','$locali','$localizador')");
						
					if($insert){
								//informacion del comercio//
								$sql_m = mysqli_query($con,"select * from apps_comercio");
								$row = mysqli_fetch_assoc($sql_m);
								//informacion del cliente
								$sql_c = mysqli_query($con,"select * from apps_clientes where id = '$idc'");
								//echo "select * from apps_clientes where id = '$idc'";
								$row2 = mysqli_fetch_assoc($sql_c);
								//informacion de la cita//
								$sql_s = mysqli_query($con,"SELECT m.marca, l.* 
											FROM apps_marcas m, apps_marcas_x_pais mp, apps_localidades l 
											WHERE l.id_loc = mp.id_pais 
											and m.id_marca = mp.id_marca 
											and l.id_loc = '$locali' 
											and m.identificador = '$n_hab'
											and m.status = 1");
								$row5 = mysqli_fetch_assoc($sql_s);
								
								$sql_e = mysqli_query($con,"SELECT e.nombres FROM  apps_emple_s e 
											WHERE e.id  = '$id_pro' ");
								$row4 = mysqli_fetch_assoc($sql_e);
								$fh = explode(" ",$fech);
								$horas = $fh[1];
								$fech =  date("M/d/Y", strtotime($fh[0]));
								if($row3['lenguaje']==1){
											$mensaje =  "???*".utf8_encode($row['titulo'])."* \n\n".
													saludo()." *".strtoupper($row3['nombres'])."*\n".
													"??  _Reserva_: *".$localizador."* \n\n".
													"??  _Local_: *OFICINA EBS*\n\n".
													"??  _Servicio_: *".($row5['marca'])."*\n\n".
													"??? _Fecha y Hora_: *".$fech." a las ".$horas ."*\n\n".
													"?????��?_Profesional_: *".$row4['nombres']."*\n\n".
													"??  _Tiempo_: *60 min*\n\n".
													"Recuerda llegar 5min antes de su cita\n _Puedes escribir_ *AYUDA o HELP* en cualquier momento\n\n".
													"*MC*     ????   _Cambiar Cita_  ??\n".
													"*CC*     ????  _Cancelar Cita_  ??\n";
										}else if($row3['lenguaje']==2){
											$mensaje =  "???*".utf8_encode($row['titulo'])."* \n\n".
													saludo2()." *".strtoupper($row3['nombres'])."*\n".
													"?? _Reservation_: *".$localizador."* \n\n".
													"??  _Location_: *EBS*\n\n".
													"??  _Service_: *".($row5['marca'])."*\n\n".
													"??? _Date and Time_: *".$fech." ".$horas ."*\n\n".
													"?????��_Professional_: *".$row4['nombres']."*\n\n".
													"??  _Duration_: *60 min*\n\n".
													"Remember to arrive 5min before your appointment\n _You can write_ *HELP* anytime\n\n".
													"*MC*     ????   _Change Appointment_  ??\n".
													"*CC*      ????  _Cancel Appointment_  ??\n";
										}
								return array (1,$mensaje);
							}else{
								return array(0,$mensaje);
								//sendMessage($x,"error2");
							}
					}
				}
			 }
			}else{
				return array(0,$mensaje);
				//sendMessage($x,"error1");
			}
}
?>