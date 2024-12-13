<?php
include("dist/funciones/funciones.php");
include('dist/funciones/api_ws.php');
function ads($date,$telo,$clin){
$nombres = 	$file_ads  = $texto = "";
	include("dist/funciones/conexion.php");	
	$cek = mysqli_query($con, "SELECT * FROM apps_ads  WHERE 
						status = 2  ORDER BY rand() LIMIT 1 ");
		$sql_m = mysqli_query($con,"select * from apps_comercio");
		$row3 = mysqli_fetch_assoc($sql_m);
		if(mysqli_num_rows($cek) == 0){
			$file_ads = "logow.png";
			
			$texto =  "Disculpa en este momento no tenemos promocines activas";
		}else{
			$row = mysqli_fetch_assoc($cek);
			$texto = $row['texto'];
			$file_ads = $row['file'];
		}
		$sql1 = mysqli_query($con, "SELECT * FROM apps_clientes WHERE telefono = '".$telo."' ");
			if(mysqli_num_rows($sql1) != 0){
				$row2 = mysqli_fetch_assoc($sql1);
				$nombres = $row2['nombres'];
			}else{
				$nombres = $clin;
			}
		   $mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
				saludo()." *".strtoupper($nombres)."*\n".
				$texto."\n".
				"Si deseas agendar un servicio, envia la palabra: *CITA*";
		return array($mensaje,$file_ads);
}
function consulta_welcome($x,$y,$date,$tel,$b,$name,$chatin){

	
include("dist/funciones/conexion.php");
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
$cadena = substr(str_shuffle($permitted_chars), 0, 36);			
	$mensaje = "";
	$local = array();
	$j=1;
	
	$ciudad = $localidad = "";
	$sql_m = mysqli_query($con,"select * from apps_comercio");
	$row = mysqli_fetch_assoc($sql_m);
	$sql1 = mysqli_query($con, "SELECT * FROM apps_clientes WHERE telefono = '".$tel."' ");
	if(mysqli_num_rows($sql1) != 0){
		
		$cek = mysqli_query($con, "SELECT * FROM apps_welcome  WHERE 
						  chat_id = '".$y."'");
     $row2 = mysqli_fetch_assoc($sql1);
	 if($row2['lenguaje']==1){
					if(mysqli_num_rows($cek) == 0){
					$sql_c = mysqli_query($con,"SELECT p.*, x.pais FROM apps_localidades l, 
										apps_provincias p, apps_paises x 
										WHERE p.codprovincia = l.id_provincia
										and x.cod = l.id_pais and l.status = 1
										and (p.id_pais = 4 || p.id_pais = 8) group by p.codprovincia");
					if(mysqli_num_rows($sql_c) >= 1){
						while($row_c=mysqli_fetch_assoc($sql_c)){
							
							$ciudad .= "♦️ *_".$row_c['nombreprovincia'].", ".$row_c['pais']."_* \n\n".
									   "*COD*        *LOCALIDAD*\n";
							$sql_l = mysqli_query($con,"SELECT * FROM apps_localidades 
													where status = 1 and id_provincia = '".$row_c['codprovincia']."'");
							if(mysqli_num_rows($sql_l) !=0){
								while($row_l=mysqli_fetch_assoc($sql_l)){
									//echo $j."\n";
									$localidad .= "*".$j."*       👉🏻  ".$row_l['localidad']."\n";
									$j = $j+1;
									$local[] = $row_l['id_loc'];
								}
							}
							$ciudad = $ciudad."".$localidad."\n";
							$localidad = "";
						}
					}

					$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
										saludo()." *".strtoupper($row2['nombres'])."*\n".
										"_Por favor selecciona una Localidad para tu cita_ \n\n".
										$ciudad."\n\n".
										"Recuerda que puedes escribir *AYUDA o HELP* en cualquier momento\n".
										"*LG*   👉🏻    _Cambiar Idioma_ 🌀️ \n";
					return array($mensaje,$local); // si existe el cliente y no hay sesion
			}else{
				$row2 = mysqli_fetch_assoc($cek);
				return array(1,$row2['status']);
			} 
	 }else  if($row2['lenguaje']==2){
		 			if(mysqli_num_rows($cek) == 0){
					$sql_c = mysqli_query($con,"SELECT p.* FROM apps_localidades l, 
										apps_provincias p WHERE p.codprovincia = l.id_provincia 
										and l.status = 1
										and (p.id_pais = 4 || p.id_pais = 8 )group by p.codprovincia");
					if(mysqli_num_rows($sql_c) >= 1){
						while($row_c=mysqli_fetch_assoc($sql_c)){
							
							$ciudad .= "♦️ *_".$row_c['nombreprovincia']."_* \n\n".
									   "*COD*        *LOCATION*\n";
							$sql_l = mysqli_query($con,"SELECT * FROM apps_localidades 
													where status = 1 and id_provincia = '".$row_c['codprovincia']."'");
							if(mysqli_num_rows($sql_l) !=0){
								while($row_l=mysqli_fetch_assoc($sql_l)){
									//echo $j."\n";
									$localidad .= "*".$j."*       👉🏻  ".$row_l['localidad']."\n";
									$j = $j+1;
									$local[] = $row_l['id_loc'];
								}
							}
							$ciudad = $ciudad."".$localidad."\n";
							$localidad = "";
						}
					}

					$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
										saludo2()." *".strtoupper($row2['nombres'])."*\n".
										"_Please select a location for your appointment_ \n\n".
										$ciudad."\n\n".
										"Remember that you can write *HELP* at any time\n".
										"*LG*   👉🏻     _Change Language_ 🌀️ \n";
					return array($mensaje,$local); // si existe el cliente y no hay sesion
			}else{
				$row2 = mysqli_fetch_assoc($cek);
				return array(1,$row2['status']);
			}
	 }
	

}else if(mysqli_num_rows($sql1) == 0){
	$sql_1 = mysqli_query($con,"SELECT * from aux_clientes where chatid = '$y' ");
	if(mysqli_num_rows($sql_1)==0){
				 $mensaje = "*Envia / Send*:\n\n".
	             "*1*  👉🏻 Español\n".
				 "*2*  👉🏻 English";
			
			return array($mensaje,300); // si existe el cliente y no hay sesion
	}else if(mysqli_num_rows($sql_1)==1){
		$row = mysqli_fetch_assoc($sql_1);
		$idoma = $row['idioma'];
		
		if($row['paso']==0){
			//sendMessage($y,$chatin);
			$sql_m = mysqli_query($con,"select * from apps_comercio");
			$row = mysqli_fetch_assoc($sql_m);
			if($chatin==1){
				$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo()." *".strtoupper($name)."*\n".
								"_Bienvenido a 24hOpen nuestra plataforma de citas 24/7_ \n\n".
								"Por favor ingresa tú nombre";
			}else if($chatin==2){
				$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo2()." *".strtoupper($name)."*\n".
								"_Welcome to 24hOpen our 24/7 dating platform_ \n\n".
								"Please enter your name";
			}else{
				 $mensaje = "*Envia / Send*:\n\n".
	             "*1*  👉🏻 Español\n".
				 "*2*  👉🏻 English";
			}
			//sendMessage($y,$mensaje);
			return array($mensaje,3); // si existe el cliente y no hay sesion
		}
		if($row['paso']==1){
			if($idoma==1){
			$mensaje =  "♦️ _Gracias, *".strtoupper($chatin)."*_ \n\n".
						 "_Por favor selecciona tu sexo ⚧️_ \n\n".
						 "*1* 👉🏻  🚺 Mujer\n\n".
						 "*2* 👉🏻  🚹 Hombre \n\n";
			}else if($idoma==2){
				$mensaje =  "♦️ _Tranks, *".strtoupper($chatin)."*_ \n\n".
						 "_Please select your gender ⚧️_ \n\n".
						 "*1* 👉🏻  🚺 Woman\n\n".
						 "*2* 👉🏻  🚹 Man \n\n";
			}
			return array($mensaje,4);
		}else if($row['paso']==2){
			
			if(($chatin==1 || strtoupper($chatin) =="MUJER" || strtoupper($chatin) =="WOMAN") || ($chatin==2 || strtoupper($chatin) =="HOMBRE") || strtoupper($chatin) =="MAN"){
				if($idoma==1){
				$mensaje =  "♦️ _Gracias, *".$row['nombre']."*_\n\n".
						 "_Por favor dinos tú fecha 🗓️ de nacimiento_\n\n".
						 "_No olvidaremos tú cumpleaños:_ \n\n".
						 "Formato Fecha : dd/mm/yyyy ( *".date('d/m/Y')."* )";
				}else if($idoma==2){
					$mensaje =  "♦️ _Tranks, *".$row['nombre']."*_\n\n".
						 "_Please tell us your date 🗓️ of birth_\n\n".
						 "_We will not forget your birthday:_ \n\n".
						 "Date Format : dd/mm/yyyy ( *".date('d/m/Y')."* )";
				}
				echo $mensaje;
				return array($mensaje,6);
			}else{
				 if($idoma==1){
					 $mensaje =  "🚫 _Dato invalido, *".$row['nombre']."*_ \n\n".
						 "_Por favor selecciona tu sexo ⚧️_ \n\n".
						 "*1* 👉🏻  🚺 Mujer\n\n".
						 "*2* 👉🏻  🚹 Hombre\n\n";
				 }else  if($idoma==2){
					 $mensaje =  "🚫 _Invalid data, *".$row['nombre']."*_ \n\n".
						 "_Please select your gender ⚧️_ \n\n".
						 "*1* 👉🏻  🚺 Woman\n\n".
						 "*2* 👉🏻  🚹 Man\n\n";
				 }
				
				return array($mensaje,5);
			}
			
		}else if($row['paso']==3){
			$fechax = explode("/",$chatin);
			$mesx = "";
			/*switch(strtoupper($fechax[0])){
				case 'JAN' : $mesx = "01";
				break;
				case 'ENE' : $mesx = "01";
				break;
				case 'FEB' : $mesx = "02";
				break;
				case 'MAR' : $mesx = "03";
				break;
				case 'APR' : $mesx = "04";
				break;
				case 'ABR' : $mesx = "04";
				break;
				case 'MAY' : $mesx = "05";
				break;
				case 'JUN' : $mesx = "06";
				break;
				case 'JUL' : $mesx = "07";
				break;
				case 'AUG' : $mesx = "08";
				break;
				case 'AGO' : $mesx = "08";
				break;
				case 'SEP' : $mesx = "09";
				break;
				case 'OCT' : $mesx = "10";
				break;
				case 'NOV' : $mesx = "11";
				break;
				case 'DEC' : $mesx = "12";
				break;
				case 'DIC' : $mesx = "12";
				break;
			}
			*/
			//echo "mes".$mesx;
			//$x = $fechax[1]."/".$mesx."/".$fechax[2];
			//$fi = $fechax[2]."-".$fechax[1]."-".$fechax[0];
			$x = $fechax[0]."/".$fechax[1]."/".$fechax[2];
			$validar = (validateDate($x, 'd/m/Y') ? '1' : '0'); 
			/*echo "x=".$x;
			$fi = $fechax[2]."-".$mesx."-".$fechax[1];
			$validar = (validateDate($x, 'd/m/Y') ? '1' : '0'); */
			if($validar == 1){
				if($idoma==1){
						$mensaje =  "♦️ _Gracias, *".$row['nombre']."*_  \n\n".
						 "_Tú registro finalizo con éxito_  \n\n".
						 "_¿ Como te puedo ayudar el dia de hoy ?:_\n".
						 "Selecciona una opción del menú:\n\n".
						 " - *CITA*\n".
						 " - *OFERTAS*\n";
				}else if($idoma==2){
						$mensaje =  "♦️ _Tranks, *".$row['nombre']."*_  \n\n".
						 "_Your registration was successful_  \n\n".
						 "_¿ How can I help you today? ?:_\n".
						 "Select a menu option:\n\n".
						 " - *APPOINTMENT*\n".
						 " - *ADS*\n";
				}

				echo $mensaje;
				return array($mensaje,7,$fi);
			}else{
				if($idoma==1){
					$mensaje =  "🚫 _Fecha Invalida, *".$row['nombre']."*_  \n\n".
						 "_Por favor dinos tú fecha 🗓️ de nacimiento_  \n\n".
						 "Formato Fecha dd/mm/yyyy: ( *".date('d/m/Y')."* )";
				}else if($idoma==2){
					$mensaje =  "🚫 _Invalid Date, *".$row['nombre']."*_  \n\n".
						 "_Please tell us your date 🗓️ of birth_  \n\n".
						 "Date Format dd/mm/yyyy: ( *".date('d/m/Y')."* )";
				}
				
				return array($mensaje,8);
			}	
		}	
	}
}
}
function consulta_s($x,$y){
include("dist/funciones/conexion.php");
$servicios = $titulo = "" ;

		$sql_a = mysqli_query($con,"select * from  aux_local a, apps_welcome w
						where a.ref = '$x' 
						and a.idw = w.id
						and w.chat_id = '$y'   ");
		/*	$sqlll = "select * from  aux_local a, apps_welcome w
						where a.ref = '$x' 
						and a.idw = w.id
						and w.chat_id = '$y'   ";
		sendMessage($y,$sqlll);*/
		if(mysqli_num_rows($sql_a)!=0){
			 $row_a = mysqli_fetch_assoc($sql_a);
			 $sql_s = mysqli_query($con,"SELECT m.* FROM apps_marcas_x_pais mp, apps_marcas m 
								WHERE m.id_marca = mp.id_marca 
								and mp.id_pais  = '".$row_a['localidad']."'  ");
				$sql_lo = mysqli_query($con,"SELECT localidad FROM apps_localidades
									where id_loc = '".$row_a['localidad']."' and status = 1 ");
			 if(mysqli_num_rows($sql_s)!=0){
				 $row_lo = mysqli_fetch_assoc($sql_lo);
				 $titulo = "📍 *Localidad:* _".$row_lo['localidad']."_ \n\n";
				 while($row=mysqli_fetch_assoc($sql_s)){
					 $titulo .= "♦️ *_".$row['marca']."_* \n\n";
					 $sql = mysqli_query($con,"SELECT * FROM apps_servicios_d 
							where id_marca = '".$row['id_marca']."' and status = 1");
					while($row2=mysqli_fetch_assoc($sql)){
						$servicios .= "*".$row2['cod']."*   👉🏻    ".$row2['servicio']."\n";
					 }
					 $titulo = $titulo."".$servicios."\n";
					 $servicios ="";
				 }
			 }
			 return ($titulo);
		}else{
			return 0;
		}					
}
function consulta_s_d($x,$y){
$k = 1;
include("dist/funciones/conexion.php");
$profesional = $titulo = "" ;
$sql_a = mysqli_query($con,"select * from  apps_servicios_d where cod = '".$x."' and status = 1");
$sql_x = mysqli_query($con,"select w.id,a.localidad from  aux_local a, apps_welcome w, apps_temp t
						where a.idw = w.id
						and w.chat_id = '$y'
						and	t.chatID = w.chat_id
						and t.local = a.ref");	
	if(mysqli_num_rows($sql_x)!=0){	
		$row2 = mysqli_fetch_assoc($sql_x);
		$tele2 = explode("@",$y);
		$sql_i = mysqli_query($con,"select * from apps_clientes where telefono = '".$tele2[0]."'");
		$row_i = mysqli_fetch_assoc($sql_i);
		if(mysqli_num_rows($sql_a)!=0){
			 $row = mysqli_fetch_assoc($sql_a);
			 if($row_i['lenguaje']==1){
					$titulo = "♦️ *_".$row['servicio']."*_ \n\n".
					 $row['descripcion']."\n\n".
					 "💲 Tarifa Basica: *".$row['precio']." $*".
					 "🕑 Duración: *".$row['precio']." min*";
				$sql = mysqli_query($con,"SELECT e.nombres,e.id FROM apps_emple_s_d d, 
											apps_emple_s e, apps_localidades l, apps_temp t,  
											apps_servicios_d s WHERE 
											e.id = d.id_e 
											AND d.id_l = l.id_loc 
											and d.id_s = s.idh 
											AND s.cod = '".$x."'
											AND l.id_loc = '".$row2['localidad']."'
											AND	t.chatID = '$y'
											AND l.status = 1");
						
						//sendMessage($y,$yx);
				 while($row3 = mysqli_fetch_assoc($sql)){
						$profesional .= "*".$k."*  👉🏻   ".$row3['nombres']."\n".
						"            ♻️ _*Perfil Profesional*_\n".
						"            https://www.instagram.com \n\n";
						
						$insert_aux = mysqli_query($con,"INSERT INTO aux_prof (idw,idp,ref)
									VALUES ('".$row2['id']."','".$row3['id']."','$k') ");
						$k++;
						
				 }
					 $titulo = "♦️  *".$row['servicio']."* \n\n".
					 $row['descripcion']."\n\n".
					 "💲 Tarifa Basica: *".$row['precio']." $*\n".
					 "🕑 Duración: *".$row['precio']." min*\n\n".
					 " 💇🏻‍♀️ *_Seleccione un Profesional_*  💇🏻‍♂️:\n\n".
					 $profesional."\n".
					 "*_Envia:_*\n".
					 "*S*  👉🏻 Cambiar Servicio\n".
					 "*L*  👉🏻 Cambiar Localidad\n".
					 "*AYUDA ó HELP*  👉🏻 Para más comandos";
					 //echo $titulo;
					 return  $titulo; 
			 }else  if($row_i['lenguaje']==2){
					$titulo = "♦️ *_".$row['servicio']."*_ \n\n".
					 $row['descripcion']."\n\n".
					 "💲 Basic Price: *".$row['precio']." $*".
					 "🕑 Duration: *".$row['precio']." min*";
				$sql = mysqli_query($con,"SELECT e.nombres,e.id FROM apps_emple_s_d d, 
											apps_emple_s e, apps_localidades l, apps_temp t,  
											apps_servicios_d s WHERE 
											e.id = d.id_e 
											AND d.id_l = l.id_loc 
											and d.id_s = s.idh 
											AND s.cod = '".$x."'
											AND l.id_loc = '".$row2['localidad']."'
											AND	t.chatID = '$y'
											AND l.status = 1");
						
						
				 while($row3 = mysqli_fetch_assoc($sql)){
						$profesional .= "*".$k."*  👉🏻   ".$row3['nombres']."\n".
						"            ♻️ _*Professional Profile*_\n".
						"            https://www.instagram.com \n\n";
						
						$insert_aux = mysqli_query($con,"INSERT INTO aux_prof (idw,idp,ref)
									VALUES ('".$row2['id']."','".$row3['id']."','$k') ");
						$k++;
						
				 }
					 $titulo = "♦️  *".$row['servicio']."* \n\n".
					 $row['descripcion']."\n\n".
					 "💲 Basic Price: *".$row['precio']." $*\n".
					 "🕑 Duration: *".$row['precio']." min*\n\n".
					 " 💇🏻‍♀️ *_Select a Professional_*  💇🏻‍♂️:\n\n".
					 $profesional."\n".
					 "*_Send:_*\n".
					 "*S*  👉🏻 Change Service\n".
					 "*L*  👉🏻 Change Location\n".
					 "*HELP*  👉🏻 For more commands";
					 //echo $titulo;
					 return  $titulo; 
			 }
				 
		}
		return 0;
	}else{
		return 0;
	}
}
function validar_p($x,$y){
include("dist/funciones/conexion.php");
$profesional = $titulo = "" ;

$sql_x = mysqli_query($con,"select w.id,a.idp from  aux_prof a, apps_welcome w
						where a.idw = w.id
						and w.chat_id = '$y' and ref = '$x' ");
if(mysqli_num_rows($sql_x)!=0){
	$row = mysqli_fetch_assoc($sql_x);
	$sql_a = mysqli_query($con,"select * from  apps_emple_s where id = '".$row['idp']."'");	
	if(mysqli_num_rows($sql_a)!=0){
		$row2 = mysqli_fetch_assoc($sql_a);
		return $row2['id'];
		
	}else{
		return 0;
	}
}else{
	return 0;
}					
}

function validar_f($x,$y){
	
include("dist/funciones/conexion.php");
	$hoy_s = date("d/m/Y");
	//$x =  date("d/m/Y", strtotime($x));
	//$fechax = explode("/",$x);
	$fechax = explode("/",$x);
	$mesx = 0;
	/*switch(strtoupper($fechax[0])){
								case 'JAN' : $mesx = "01";
				break;
				case 'ENE' : $mesx = "01";
				break;
				case 'FEB' : $mesx = "02";
				break;
				case 'MAR' : $mesx = "03";
				break;
				case 'APR' : $mesx = "04";
				break;
				case 'ABR' : $mesx = "04";
				break;
				case 'MAY' : $mesx = "05";
				break;
				case 'JUN' : $mesx = "06";
				break;
				case 'JUL' : $mesx = "07";
				break;
				case 'AUG' : $mesx = "08";
				break;
				case 'AGO' : $mesx = "08";
				break;
				case 'SEP' : $mesx = "09";
				break;
				case 'OCT' : $mesx = "10";
				break;
				case 'NOV' : $mesx = "11";
				break;
				case 'DEC' : $mesx = "12";
				break;
				case 'DIC' : $mesx = "12";
				break;
				
			}*/
	/*if($mesx=0){
		 return array(0,$dias_t);
	}*/
	$x = $fechax[0]."/".$fechax[1]."/".$fechax[2];
	//$x = $fechax[1]."/".$mesx."/".$fechax[2];
	$enva = $x." a ".$hoy_s;
	//sendMessage($y,$enva);
	//$valida = var_dump(validateDate($x, 'd/m/Y'));
	$validar = (validateDate($x, 'd/m/Y') ? '1' : '0'); 
	if($validar == 1){
		$fecha_actual = date("Y-m-d");
		$fecha_e = explode("/",$x);
		$fecha_entrada = $fecha_e[2]."-".$fecha_e[1]."-".$fecha_e[0];
		
		if(saber_dia($fecha_entrada)=="Domingo"){
			 return array(11,$dias_t);
		}else{
		//$dias_t = dias_transcurridos($fecha_entrada,$fecha_actual);
		$fecha_fin = date("d-m-Y",strtotime($fecha_actual."+ 30 days"));
		$dias_t = verifica_rango($fecha_actual,$fecha_fin,$fecha_entrada);
		 
		//sendMessage($y,$dias_t);
		if($dias_t == 0){
				return array(14,$dias_t);
		}else if ($dias_t==30){
			return array(10,$dias_t);
		}
		else if($dias_t==1){
			$f = explode("/",$x);
			$f = $f[2]."-".$f[1]."-".$f[0]." 00:00:00";
			$update = mysqli_query($con, "UPDATE apps_temp SET  status = 4, fecha_i = '".$f."' 
			WHERE chatID = '".$y."'") or die(mysqli_error());
			
			$sql = mysqli_query($con,"SELECT * FROM apps_temp where chatID = '$y'");
			if(mysqli_num_rows($sql)!=0){
				
				$row = mysqli_fetch_assoc($sql);
				$sql2 = mysqli_query($con,"SELECT localidad FROM aux_local a, apps_welcome w
							where w.id = a.idw and a.ref = '".$row['local']."'
							and w.chat_id = '$y' ");
				
				$qdia = saber_dia($fecha_entrada);
				$sqlv_d = mysqli_query($con,"select h.* from apps_horarios h,aux_local a
							where a.localidad = h.id_s and
							a.ref = '".$row['local']."' and dia = '$qdia' ");
							$xy = "select h.* from apps_horarios h,aux_local a
							where a.localidad = h.id_s and
							a.ref = '".$row['local']."' and dia = '$qdia' ";
				if(mysqli_num_rows($sqlv_d)==0){
					//sendMessage($y,$xy);
					 return array(11,$dias_t);
				}else if(mysqli_num_rows($sql2)!=0){
					
					$row2 = mysqli_fetch_assoc($sql2);
					$sql3 = mysqli_query($con,"select * from apps_servicios_d where cod = '".$row['serv']."'");
					if(mysqli_num_rows($sql3)!=0){
					$row3 = mysqli_fetch_assoc($sql3);
					$sql4 = mysqli_query($con,"SELECT idp FROM aux_prof a, apps_welcome w
							where w.id = a.idw and a.idp = '".$row['prof']."'
							and w.chat_id = '$y' ");
							
						if(mysqli_num_rows($sql4)!=0){
							$row4 = mysqli_fetch_assoc($sql4);
							
							$respuesta = horas_d($f,$y,$row2['localidad'],$row3['idh'],$row4['idp']);
							return array(12,$respuesta);
						}
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
	include("dist/funciones/conexion.php");
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
		
}
function validar_h($x,$y){
include("dist/funciones/conexion.php");
$sql_a = mysqli_query($con,"select * from  aux_horas a, apps_welcome w
						where a.ref = '$x' 
						and a.idw = w.id
						and w.chat_id = '$y'   ");
		
		if(mysqli_num_rows($sql_a)!=0){
			 $row_a = mysqli_fetch_assoc($sql_a);
			 $sql_x = mysqli_query($con,"SELECT * FROM apps_temp where chatID = '$y'");
			 if(mysqli_num_rows($sql_x)!=0){
				 $row_x = mysqli_fetch_assoc($sql_x);
				 $localizador = cadena();
				//echo $idc;
				$hora = $row_a['hora'];
				$n_hab = $row_x['serv'];
				$sql2 = mysqli_query($con,"SELECT localidad FROM aux_local a, apps_welcome w
							where w.id = a.idw and a.ref = '".$row_x['local']."'
							and w.chat_id = '$y' ");
				$row2=mysqli_fetch_assoc($sql2);
				$phone = explode("@",$y);
				$phone = $phone[0];
				$sql3 = mysqli_query($con,"SELECT * FROM apps_clientes where telefono = '".$phone."' ");
				if(mysqli_num_rows($sql3)!=0){
					$row3 = mysqli_fetch_assoc($sql3);
					$locali = $row2['localidad'];
					$f = explode(" ",$row_x['fecha_i']);
					$f = $f[0];
					$fech =$f." ".$hora.":00";
					$idc = $row3['id'];
					$id_pro = $row_x['prof'];
					$sql_s = mysqli_query($con,"select * from apps_servicios_d
										where cod = '$n_hab'  ");
					$row_s = mysqli_fetch_assoc($sql_s);
					$end = strtotime('+'.$row_s['tiempo'].' minute', strtotime($fech));
					
					$end = date("Y-m-d H:i:s",$end);
					$sqli = mysqli_query($con,"SELECT * FROM events where id_cliente = '$idc' and  idh = '$n_hab' 
							and start = '$fech' and id_local = '$locali' ");
					if(mysqli_num_rows($sqli)==0){
						$title ="Reserva: ".$localizador;
						$insert = mysqli_query($con,"INSERT INTO events(id_cliente,title, start, end,id_pro,idh,id_local,localizador) values 
												('$idc','$title', '$fech', '$end', '$id_pro','".$row_s['idh']."','$locali','$localizador')");

					if($insert){
								//informacion del comercio//
								$sql_m = mysqli_query($con,"select * from apps_comercio");
								$row = mysqli_fetch_assoc($sql_m);
								//informacion del cliente
								$sql_c = mysqli_query($con,"select * from apps_clientes where id = '$idc'");
								//echo "select * from apps_clientes where id = '$idc'";
								$row2 = mysqli_fetch_assoc($sql_c);
								//informacion de la cita//
								$sql_s = mysqli_query($con,"SELECT s.servicio,s.precio,s.tiempo, l.* 
											FROM apps_servicios_d s, apps_marcas_x_pais mp, apps_localidades l 
											WHERE l.id_loc = mp.id_pais 
											and s.id_marca = mp.id_marca 
											and l.id_loc = '$locali' 
											and s.cod = '$n_hab'
											and l.status = 1");
								$row5 = mysqli_fetch_assoc($sql_s);
								
								$sql_e = mysqli_query($con,"SELECT e.nombres FROM apps_emple_s_d d, apps_emple_s e 
											WHERE e.id = d.id_e 
											and d.id_s = '".$row_s['idh']."'
											and d.id_e = '$id_pro' ");
								$row4 = mysqli_fetch_assoc($sql_e);
								$fh = explode(" ",$fech);
								$horas = $fh[1];
								$fech =  date("M/d/Y", strtotime($fh[0]));
								if($row3['lenguaje']==1){
											$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
													saludo()." *".strtoupper($row3['nombres'])."*\n".
													"📋  _Reserva_: *".$localizador."* \n\n".
													"📍  _Local_: *".$row5['localidad']."*\n\n".
													"♦️  _Servicio_: *".($row5['servicio'])."*\n\n".
													"🗓️  _Fecha y Hora_: *".$fech." a las ".$horas ."*\n\n".
													"💇🏻‍♀️ _Profesional_: *".$row4['nombres']."*\n\n".
													"💲  _Tarifa_: *".$row5['precio']." $*\n\n".
													"🕑  _Duración_: *".$row5['tiempo']." min*\n\n".
													"Recuerda llegar 5min antes de su cita\n _Puedes escribir_ *AYUDA o HELP* en cualquier momento\n\n".
													"*MC*     👉🏻   _Cambiar Cita_  🕟\n".
													"*CC*      👉🏻  _Cancelar Cita_  🚫\n".
													"*PC*      👉🏻  _Pagar Cita_ 💲\n".
													"*ADS*   👉🏻    _Ofertas_ 🏷️ \n";
										}else if($row3['lenguaje']==2){
											$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
													saludo2()." *".strtoupper($row3['nombres'])."*\n".
													"📋  _Reservation_: *".$localizador."* \n\n".
													"📍  _Location_: *".$row5['localidad']."*\n\n".
													"♦️  _Service_: *".($row5['servicio'])."*\n\n".
													"🗓️  _Date and Time_: *".$fech." ".$horas ."*\n\n".
													"💇🏻‍♀️ _Professional_: *".$row4['nombres']."*\n\n".
													"💲  _Price_: *".$row5['precio']." $*\n\n".
													"🕑  _Duration_: *".$row5['tiempo']." min*\n\n".
													"Remember to arrive 5min before your appointment\n _You can write_ *HELP* anytime\n\n".
													"*MC*     👉🏻   _Change Appointment_  🕟\n".
													"*CC*      👉🏻  _Cancel Appointment_  🚫\n".
													"*PC*      👉🏻  _Pay Appointment_ 💲\n".
													"*ADS*   👉🏻    _Offers_ 🏷️ \n";
										}
								return $mensaje;
							}else{
								return 0;
							}
					}
				}
			 }
			}else{
				return 0;
			}
}
//--------------INICIO CANCELAR CITA---------------------------//
function validar_cita($y,$num){
	include("dist/funciones/conexion.php");
	$i = 0;
	$orden = "";
	$fech = date("Y-m-d H:i:s");
	    $sql_m = mysqli_query($con,"select * from apps_comercio");
		$row3 = mysqli_fetch_assoc($sql_m);
		$sql1 = mysqli_query($con, "SELECT * FROM apps_clientes WHERE telefono = '".$num."' ");
			if(mysqli_num_rows($sql1) != 0){
			$row2 = mysqli_fetch_assoc($sql1);
				$sql = mysqli_query($con,"SELECT e.*,s.servicio, l.localidad  from events e, 
				apps_servicios_d s, apps_localidades l
				where e.idh = s.idh 
				and e.id_local = l.id_loc
				and e.id_cliente = '".$row2['id']."'
				and e.status = 0
				and l.status = 1");
				if(mysqli_num_rows($sql)!=0){
					while($row = mysqli_fetch_assoc($sql)){
						$i++;
						$fh = explode(" ",$row['start']);
						$horas = $fh[1];
						$fech =  date("M/d/Y", strtotime($fh[0]));
						if($row2['lenguaje']==1){
							$orden .= "*".$i."*       👉🏻  _*".$row['localizador']."*_ \n\n".
						"      ♦️ Servicio: *".$row['servicio']."* \n".
						"      🗓️ Fecha: *".$fech." ".$horas."* \n".
						"      📍 Localidad: *".$row['localidad']."* \n\n";
						}else if($row2['lenguaje']==2){
							$orden .= "*".$i."*       👉🏻  _*".$row['localizador']."*_ \n\n".
						"      ♦️ Service: *".$row['servicio']."* \n".
						"      🗓️ Date: *".$fech." ".$horas."* \n".
						"      📍 Location: *".$row['localidad']."* \n\n";
						}
						
						$insert_aux = mysqli_query($con,"INSERT INTO aux_anulacion
						(chatid,codigo,ref,cliente)
						VALUES ('".$y."','".$row['localizador']."','$i','".$row2['id']."') ");
						
					}
					$insert2 = mysqli_query($con,"INSERT INTO apps_reservas_anu 
								(cliente,fecha,chatid,status,tipo) 
								VALUES ('".$row2['id']."','".$fech."','".$y."','0','0')");

				}
				if($row2['lenguaje']==1){
				$mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
								saludo()." *".strtoupper($row2['nombres'])."*\n".
								"_Por favor selecciona la Cita a cancelar_ \n\n".
								"*COD     LOCALIZADOR* \n\n".
								$orden;	
				}else if($row2['lenguaje']==2){
					$mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
								saludo2()." *".strtoupper($row2['nombres'])."*\n".
								"_Please Select the Appointment to Cancel_ \n\n".
								"*COD     LOCATOR* \n\n".
								$orden;	
				}
				return $mensaje;
			}else if(mysqli_num_rows($sql1) == 0){
				if($row2['lenguaje']==1){
				$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo()." *".strtoupper($row2['nombres'])."*\n".
								"_Disculpa no tienes Citas programdas_ \n".
								"_¿Deseas agendar 🗓️ una cita?_";
				}else if($row2['lenguaje']==2){
					$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo2()." *".strtoupper($row2['nombres'])."*\n".
								"_Sorry you have no appointments scheduled_ \n".
								"_¿You want to schedule an appointment 🗓️?_";
				}
			}
}
function anular_res($y,$cuerpo){
	include("dist/funciones/conexion.php");
	$orden  = "";
	$sql_ = mysqli_query($con,"select * from aux_anulacion where chatid = '$y' and ref = '$cuerpo' ");
	$l ="select * from aux_anulacion where chatid = '$y' and ref = '$cuerpo' ";
	//sendMessage($y,$l);
	if(mysqli_num_rows($sql_)==0){
		//sendMessage($y,"55");
		$sql2 = mysqli_query($con,"select * from aux_anulacion where chatid = '$y' ");
		$row2 = mysqli_fetch_assoc($sql2);
		//$sql_c = mysqli_query($con,"select * from apps_clientes where id = '".$row2['cliente']."'");
		//$row_c = mysqli_fetch_assoc($sql_c);
		$sql = mysqli_query($con,"SELECT e.*,s.servicio, l.localidad  from events e, 
				apps_servicios_d s, apps_localidades l
				where e.idh = s.idh 
				and e.id_local = l.id_loc
				and e.id_cliente = '".$row2['cliente']."'
				and e.status = 0
				and l.status = 1");
				if(mysqli_num_rows($sql)!=0){
					while($row = mysqli_fetch_assoc($sql)){
						$i++;
						$fh = explode(" ",$row['start']);
						$horas = $fh[1];
						$fech =  date("M/d/Y", strtotime($fh[0]));
						$orden .= "*".$i."*       👉🏻  _*".$row['localizador']."*_ \n\n".
						"      ♦️ Servicio: *".$row['servicio']."* \n".
						"      🗓️ Fecha: *".$fech." ".$horas."* \n".
						"      📍 Localidad: *".$row['localidad']."* \n\n";
						$insert_aux = mysqli_query($con,"INSERT INTO aux_anulacion
						(chatid,codigo,ref,cliente)
						VALUES ('".$y."','".$row['localizador']."','$i','".$row2['id']."') ");
						
					}
				}
		return array(55,$orden);
	}else{
		
		$row = mysqli_fetch_assoc($sql_);
		$sql_c = mysqli_query($con,"select * from apps_clientes where id = '".$row['cliente']."'");
		$row_c = mysqli_fetch_assoc($sql_c);
		if($row_c['lenguaje']==1){
			$mensaje = "_Estimado *".$row_c['nombres']."*_ \n".
		"Voy a cancelar la Cita con localizador: *".$row['codigo']."* \n\n".
		"_Para culminar por favor dinos porque cancelas la cita_ \n\n".
		"*1*   👉🏻   _No puedes asistir en la fecha_ \n".
		"*2*   👉🏻   _El precio del servicio es alto_ \n".
		"*3*   👉🏻   _Recibi un mal servicio_ \n".
		"*4*   👉🏻   _Lo voy hacer en otro lugar_ \n";
		}else if($row_c['lenguaje']==2){
			$mensaje = "_Estimated *".$row_c['nombres']."*_ \n".
		"I will cancel the Appointment with pager: *".$row['codigo']."* \n\n".
		"_To finish please tell us why you are canceling the appointment_ \n\n".
		"*1*   👉🏻   _You cannot attend on the date_ \n".
		"*2*   👉🏻   _The price of the service is high_ \n".
		"*3*   👉🏻   _I received bad service_ \n".
		"*4*   👉🏻   _I will do it in another place_ \n";
		}
		
		$xx = mysqli_query($con,"select * from apps_reservas_anu where status = 0 and chatid = '$y' and tipo = 0 ");
		$rowxx = mysqli_fetch_assoc($xx);
		$update = mysqli_query($con,"UPDATE apps_reservas_anu SET codigo = '".$row['codigo']."',status = 1
								where chatid = '$y' and id = '".$rowxx['id']."' ");
		$k = "UPDATE apps_reservas_anu SET codigo = '".$row['codigo']."',status = 1
								where chatid = '$y' id = '".$rowxx['id']."'  ";
									//sendMessage($y,$k);
		$update = mysqli_query($con,"UPDATE events SET status = '4'
								where localizador = '".$row['codigo']."' and id_cliente = '".$row['cliente']."'");
		$delete = mysqli_query($con,"delete from aux_anulacion where chatid = '$y'");
		return array(100,$mensaje);
	}
}
function encuesta_anul($y,$x){
	include("dist/funciones/conexion.php");
	$orden  = "";
	if($x==1 || $x==2 || $x==3 || $x==4){
		$sqlx = mysqli_query($con,"select * from apps_reservas_anu where chatid = '".$y."' and tipo = 0 ORDER BY id DESC");
		$rowx = mysqli_fetch_assoc($sqlx);
		
		$update = mysqli_query($con,"UPDATE apps_reservas_anu SET motivo = '$x', status = 2
								where chatid = '$y' and codigo = '".$rowx['codigo']."'");
			$mensaje = "❗ _La Cita con Localizador *".$rowx['codigo']."* fue cancelada con exíto_";					
		return $mensaje;
	}else{
		$mensaje = "🚫 _Por favor selecciona un motivo para cancelar_ \n\n";
		return $mensaje;
	}
	
}
//----------- FIN ANULAR CITA--------------//

//INICIO MODIFICAR CITA------------------------------//
function modif_cita($y,$num){
	include("dist/funciones/conexion.php");
	$i = 0;
	$orden = "";
	$fech = date("Y-m-d H:i:s");
	    $sql_m = mysqli_query($con,"select * from apps_comercio");
		$row3 = mysqli_fetch_assoc($sql_m);
		$sql1 = mysqli_query($con, "SELECT * FROM apps_clientes WHERE telefono = '".$num."' ");
			if(mysqli_num_rows($sql1) != 0){
			$row2 = mysqli_fetch_assoc($sql1);
				$sql = mysqli_query($con,"SELECT e.*,s.servicio, l.localidad  from events e, 
				apps_servicios_d s, apps_localidades l
				where e.idh = s.idh 
				and e.id_local = l.id_loc
				and e.id_cliente = '".$row2['id']."'
				and e.status = 0
				and l.status = 1");
				$lengua = $row2['lenguaje'];
				if(mysqli_num_rows($sql)!=0){
					while($row = mysqli_fetch_assoc($sql)){
						$i++;
						$fh = explode(" ",$row['start']);
						$horas = $fh[1];
						$fech =  date("M/d/Y", strtotime($fh[0]));
						if($lengua==1){
							$orden .= "*".$i."*       👉🏻  _*".$row['localizador']."*_ \n\n".
						"      ♦️ Servicio: *".$row['servicio']."* \n".
						"      🗓️ Fecha: *".$fech." ".$horas."* \n".
						"      📍 Localidad: *".$row['localidad']."* \n\n";
						}else if($lengua==2){
							$orden .= "*".$i."*       👉🏻  _*".$row['localizador']."*_ \n\n".
						"      ♦️ Service: *".$row['servicio']."* \n".
						"      🗓️ Date: *".$fech." ".$horas."* \n".
						"      📍 Location: *".$row['localidad']."* \n\n";
						}
						
						$insert_aux = mysqli_query($con,"INSERT INTO aux_anulacion
						(chatid,codigo,ref,cliente)
						VALUES ('".$y."','".$row['localizador']."','$i','".$row2['id']."') ");
						
					}
					$insert2 = mysqli_query($con,"INSERT INTO apps_reservas_anu 
								(cliente,fecha,chatid,status,tipo) 
								VALUES ('".$row2['id']."','".$fech."','".$y."','0','1')");

				}
				if($lengua==1){
					$mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
								saludo()." *".strtoupper($row2['nombres'])."*\n".
								"_Por favor selecciona la Cita a Mdificar_ \n\n".
								"*COD     LOCALIZADOR* \n\n".
								$orden;		
				}else if($lengua==2){
					$mensaje =  "🛍️ *".utf8_encode($row3['titulo'])."* \n\n".
								saludo2()." *".strtoupper($row2['nombres'])."*\n".
								"_Please select the Appointment to Modify_ \n\n".
								"*COD     LOCATOR* \n\n".
								$orden;		
				}
										
				return $mensaje;
			}else{
				if($lengua==1){
					$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo()." *".strtoupper($row2['nombres'])."*\n".
								"_Disculpa no tienes Citas programdas_ \n".
								"_¿Deseas agendar 🗓️ una cita?_";
				}else if($lengua==2){
					$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
								saludo2()." *".strtoupper($row2['nombres'])."*\n".
								"_Sorry you have no appointments scheduled_ \n".
								"_¿You want to schedule an appointment 🗓️?_";
				}
				
			}
}
function modif_res($y,$cuerpo){
	$i=0;
	include("dist/funciones/conexion.php");
	$orden  = "";
	$sql_ = mysqli_query($con,"select * from aux_anulacion where chatid = '$y' and ref = '$cuerpo' ");
	$l ="select * from aux_anulacion where chatid = '$y' and ref = '$cuerpo' ";
	//sendMessage($y,$l);
	if(mysqli_num_rows($sql_)==0){
		//sendMessage($y,"55");
		$sql2 = mysqli_query($con,"select * from aux_anulacion where chatid = '$y' ");
		$row2 = mysqli_fetch_assoc($sql2);
		$sql = mysqli_query($con,"SELECT e.*,s.servicio, l.localidad  from events e, 
				apps_servicios_d s, apps_localidades l
				where e.idh = s.idh 
				and e.id_local = l.id_loc
				and e.id_cliente = '".$row2['cliente']."'
				and e.status = 0
				and l.status = 1");
				if(mysqli_num_rows($sql)!=0){
					while($row = mysqli_fetch_assoc($sql)){
						$fh = explode(" ",$row['start']);
						$horas = $fh[1];
						$fech =  date("M/d/Y", strtotime($fh[0]));
						$i++;
						$orden .= "*".$i."*       👉🏻  _*".$row['localizador']."*_ \n\n".
						"      ♦️ Servicio: *".$row['servicio']."* \n".
						"      🗓️ Fecha: *".$fech." ".$horas."* \n".
						"      📍 Localidad: *".$row['localidad']."* \n\n";
						$insert_aux = mysqli_query($con,"INSERT INTO aux_anulacion
						(chatid,codigo,ref,cliente)
						VALUES ('".$y."','".$row['localizador']."','$i','".$row2['id']."') ");
						
					}
				}
		return array(56,$orden);
	}else{
		$fecha_dia = new DateTime();
		$fecha_dia = $fecha_dia->modify('first day of this month');
		$fecha_dia = $fecha_dia->format('M/d/Y');
		$row = mysqli_fetch_assoc($sql_);
		$sql_c = mysqli_query($con,"select * from apps_clientes where id = '".$row['cliente']."'");
		$row_c = mysqli_fetch_assoc($sql_c);
		$mensaje = "_Estimado *".$row_c['nombres']."*_ \n".
		"Ingresa la nueva fecha para la Cita con localizador: *".$row['codigo']."* \n\n".
		"_Ejemplo:_ *".$fecha_dia."*";
		$xx = mysqli_query($con,"select * from apps_reservas_anu where status = 0 and chatid = '$y' and tipo = 1 ");
		$rowxx = mysqli_fetch_assoc($xx);
		$update = mysqli_query($con,"UPDATE apps_reservas_anu SET codigo = '".$row['codigo']."',status = 1
								where chatid = '$y' and id = '".$rowxx['id']."' ");
		$k = "UPDATE apps_reservas_anu SET codigo = '".$row['codigo']."',status = 1
								where chatid = '$y' id = '".$rowxx['id']."'  ";
									//sendMessage($y,$k);
		/*$update = mysqli_query($con,"UPDATE events SET status = '4'
								where localizador = '".$row['codigo']."' and id_cliente = '".$row['cliente']."'");
		//$delete = mysqli_query($con,"delete from aux_anulacion where chatid = '$y'");*/
		return array(101,$mensaje);
	}
}
function cita_modf($y,$x,$numero){
	include("dist/funciones/conexion.php");
	$orden  = "";
	
	$hoy_s = date("d/m/Y");
	$fecha_w = date("Y-m-d");
	//$x =  date("d/m/Y", strtotime($x));
	$fechax = explode("/",$x);
	$mesx = 0;
	switch(strtoupper($fechax[0])){
				case 'JAN' : $mesx = "01";
				break;
				case 'ENE' : $mesx = "01";
				break;
				case 'FEB' : $mesx = "02";
				break;
				case 'MAR' : $mesx = "03";
				break;
				case 'APR' : $mesx = "04";
				break;
				case 'ABR' : $mesx = "04";
				break;
				case 'MAY' : $mesx = "05";
				break;
				case 'JUN' : $mesx = "06";
				break;
				case 'JUL' : $mesx = "07";
				break;
				case 'AUG' : $mesx = "08";
				break;
				case 'AGO' : $mesx = "08";
				break;
				case 'SEP' : $mesx = "09";
				break;
				case 'OCT' : $mesx = "10";
				break;
				case 'NOV' : $mesx = "11";
				break;
				case 'DEC' : $mesx = "12";
				break;
				case 'DIC' : $mesx = "12";
				break;
			}
	$x = $fechax[1]."/".$mesx."/".$fechax[2];
	$enva = $x." a ".$hoy_s;
	$validar = (validateDate($x, 'd/m/Y') ? '1' : '0'); 
	if($validar == 1){
		$fecha_actual = date("Y-m-d");
		$fecha_e = explode("/",$x);
		$fecha_entrada = $fecha_e[2]."-".$fecha_e[1]."-".$fecha_e[0];
		if(saber_dia($fecha_entrada)=="Domingo"){
			 return array(11,$dias_t);
		}else{
		//$dias_t = dias_transcurridos($fecha_entrada,$fecha_actual);
		$fecha_fin = date("d-m-Y",strtotime($fecha_actual."+ 30 days"));
		$dias_t = verifica_rango($fecha_actual,$fecha_fin,$fecha_entrada);
		
		$qdia = saber_dia($fecha_entrada);
				$sqlv_d = mysqli_query($con,"select h.* from apps_horarios h
							where  h.id_s  = '68' and dia = '$qdia' ");
							$xy = "select h.* from apps_horarios h
							where  h.id_s  = '68' and dia = '$qdia' ";
				if(mysqli_num_rows($sqlv_d)==0){
					//sendMessage($y,$xy);
					 return array(11,$dias_t);
				}
		 
		
		if($dias_t == 0){
				return array(14,$dias_t);
		}else if ($dias_t==30){
			return array(10,$dias_t);
		}
		else if($dias_t==1){
			
			$f = explode("/",$x);
			$f = $f[2]."-".$f[1]."-".$f[0]." 00:00:00";
			/*$update = mysqli_query($con, "UPDATE apps_temp SET  status = 4, fecha_i = '".$f."' 
			WHERE chatID = '".$y."'") or die(mysqli_error());*/
			
			$sql_w = mysqli_query($con,"SELECT * FROM apps_welcome where chat_id = '$y' 
							 ");
			if(mysqli_num_rows($sql_w)==0){
				$update = mysqli_query($con, "INSERT INTO apps_welcome (chat_id,fecha,mensaje,numero,status,id_m) VALUES 
					('".$y."','".$f."','','".$numero."','4',
					'0')") or die(mysqli_error());
				$idw= mysqli_insert_id($con);
				$insert_tmp = mysqli_query($con,"INSERT INTO apps_temp (chatID,idw,status,fecha_i)
											VALUES ('$y','$idw','4','".$f."') ");
			}
			else{
				$fecha_w1 = $fecha_w." 00:00:00";
				$a = "UPDATE apps_temp SET  status = 4
			    WHERE chatID = '".$y."'";
				//sendMessage($y,$a);
				$update = mysqli_query($con, "UPDATE apps_temp SET  status = 4
			    WHERE chatID = '".$y."'") or die(mysqli_error());
				if(!$update){
					$insert_tmp = mysqli_query($con,"INSERT INTO apps_temp (chatID,idw,status,fecha_i)
											VALUES ('$y','$idw','4','".$f."') ");

				}
			}
			
			$sql_ = mysqli_query($con,"select * from apps_reservas_anu where chatid = '$y' and tipo = 1 order by id desc LIMIT 1 ");
			$g = "select * from apps_reservas_anu where chatid = '$y' and tipo = 1 order by id desc LIMIT 1 ";
			//sendMessage($y,$g);
			if(mysqli_num_rows($sql_)!=0){
				
				$row_ = mysqli_fetch_assoc($sql_);
				$sql = mysqli_query($con,"SELECT * FROM events where localizador = '".$row_['codigo']."'");
				if(mysqli_num_rows($sql)!=0){
					//sendMessage($y,"paso e");
					$row2 = mysqli_fetch_assoc($sql);
					//sendMessage($y,"paso");
					$update = mysqli_query($con,"UPDATE apps_reservas_anu SET status = 3
								where chatid = '$y' and tipo = '1' ");
								$respuesta = horas_d($f,$y,$row2['id_local'],$row2['idh'],$row2['id_pro']);
								//sendMessage($y,$respuesta);
								return array(102,$respuesta);
				}
			}	
		}
	 } //finaliza validar q no sea domingo
	}else{
		return array(57,$dias_t);
	}
	
}

function modif_cita_h($y,$x,$z){
include("dist/funciones/conexion.php");
$sql_a = mysqli_query($con,"select * from  aux_horas a, apps_welcome w
						where a.ref = '$x' 
						and a.idw = w.id
						and w.chat_id = '$y'   ");
	$tele = explode("@",$y);
	if(mysqli_num_rows($sql_a)!=0){
			 $row_a = mysqli_fetch_assoc($sql_a);
			 $sqly = mysqli_query($con,"select * from apps_reservas_anu where chatid = '$y'
								and status <> 2 ORDER by id desc");
			 if(mysqli_num_rows($sqly)!=0){
				 	
				 $rowy = mysqli_fetch_assoc($sqly);
				 $sql_x = mysqli_query($con,"SELECT * FROM events where localizador = '".$rowy['codigo']."'");
					if(mysqli_num_rows($sql_x)!=0){
						
						 $row_x = mysqli_fetch_assoc($sql_x);
						 $localizador = $rowy['codigo'];
						//echo $idc;
						$hora = $row_a['hora'];
						$n_hab = $row_x['idh'];
						$sql3 = mysqli_query($con,"SELECT * FROM apps_clientes where id = '".$row_x['id_cliente']."' ");
						if(mysqli_num_rows($sql3)!=0){
							
							$row3 = mysqli_fetch_assoc($sql3);
							$locali = $row_x['id_local'];
							$sql_t = mysqli_query($con,"select * from apps_temp where chatID = '$y' ");
							$row_f = mysqli_fetch_assoc($sql_t);
							$f = explode(" ",$row_f['fecha_i']);
							$f = $f[0];
							$fech =$f." ".$hora.":00";
							$idc = $row3['id'];
							$id_pro = $row_x['id_pro'];
							$sql_s = mysqli_query($con,"select * from apps_servicios_d
												where idh = '$n_hab'  ");
							$row_s = mysqli_fetch_assoc($sql_s);
							//sendMessage($y,$s);
							$end = strtotime('+'.$row_s['tiempo'].' minute', strtotime($fech));
							$end = date("Y-m-d H:i:s",$end);
								$title ="Reserva: ".$localizador;
								$insert = mysqli_query($con,"UPDATE events set start ='".$fech."', end = '".$end."'
														where localizador = '$localizador' ");
							$update = "UPDATE events set start ='".$fech."', end = '".$end."'
														where localizador = '$localizador' ";
						
							if($insert){
									//sendMessage($y,$update);
										//informacion del comercio//
										$sql_m = mysqli_query($con,"select * from apps_comercio");
										$row = mysqli_fetch_assoc($sql_m);
										//informacion del cliente
										
										//informacion de la cita//
										$sql_s = mysqli_query($con,"SELECT s.servicio,s.precio,s.tiempo, l.* 
													FROM apps_servicios_d s, apps_marcas_x_pais mp, apps_localidades l 
													WHERE l.id_loc = mp.id_pais 
													and s.id_marca = mp.id_marca 
													and l.id_loc = '$locali' 
													and s.idh = '$n_hab'
													and l.status = 1");
											
										//sendMessage($y,$r);			
										$row5 = mysqli_fetch_assoc($sql_s);
										
										$sql_e = mysqli_query($con,"SELECT e.nombres FROM apps_emple_s_d d, apps_emple_s e 
													WHERE e.id = d.id_e 
													and d.id_s = '".$row_s['idh']."'
													and d.id_e = '$id_pro' ");
										$row4 = mysqli_fetch_assoc($sql_e);
										$fh = explode(" ",$fech);
										$horas = $fh[1];
										$fech =  date("M/d/Y", strtotime($fh[0]));
										if($row3['lenguaje']==1){
											$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
													saludo()." *".strtoupper($row3['nombres'])."*\n".
													"📋  _Reserva_: *".$localizador."* \n\n".
													"📍  _Local_: *".$row5['localidad']."*\n\n".
													"♦️  _Servicio_: *".($row5['servicio'])."*\n\n".
													"🗓️  _Fecha y Hora_: *".$fech." a las ".$horas ."*\n\n".
													"💇🏻‍♀️ _Profesional_: *".$row4['nombres']."*\n\n".
													"💲  _Tarifa_: *".$row5['precio']." $*\n\n".
													"🕑  _Duración_: *".$row5['tiempo']." min*\n\n".
													"Recuerda llegar 5min antes de su cita\n _Puedes escribir_ *AYUDA o HELP* en cualquier momento\n\n".
													"*MC*     👉🏻   _Cambiar Cita_  🕟\n".
													"*CC*      👉🏻  _Cancelar Cita_  🚫\n".
													"*ADS*   👉🏻    _Ofertas_ 🏷️ \n";
										}else if($row3['lenguaje']==2){
											$mensaje =  "🛍️ *".utf8_encode($row['titulo'])."* \n\n".
													saludo2()." *".strtoupper($row3['nombres'])."*\n".
													"📋  _Reservation_: *".$localizador."* \n\n".
													"📍  _Location_: *".$row5['localidad']."*\n\n".
													"♦️  _Service_: *".($row5['servicio'])."*\n\n".
													"🗓️  _Date and Time_: *".$fech." ".$horas ."*\n\n".
													"💇🏻‍♀️ _Professional_: *".$row4['nombres']."*\n\n".
													"💲  _Price_: *".$row5['precio']." $*\n\n".
													"🕑  _Duration_: *".$row5['tiempo']." min*\n\n".
													"Remember to arrive 5min before your appointment\n _You can write_ *HELP* anytime\n\n".
													"*MC*     👉🏻   _Change Appointment_  🕟\n".
													"*CC*      👉🏻  _Cancel Appointment_  🚫\n".
													"*ADS*   👉🏻    _Offers_ 🏷️ \n";
										}
										
										return array(104,$mensaje);
							}else{
								//sendMessage($y,"nada");
								return array(105,"hola");
							}
					}
				}
			 }
	}else{
		//sendMessage($y,"epale");
		return array(106,"hola");
	}
}
function cambiar_idioma($y,$x,$z){
	include("dist/funciones/conexion.php");
	$update = mysqli_query($con,"update apps_clientes set lenguaje = '$x' where telefono = '$z'");
	if($update) return 1;
	else return;
}
function ver_servicios($y,$z){
	include("dist/funciones/conexion.php");
	$sql_z = mysqli_query($con,"select * from apps_clientes where telefono = '$z'");
	if(mysqli_num_rows($sql_z)!=0){
		$rowz = mysqli_fetch_assoc($sql_z);
		$sql_m = mysqli_query($con,"select * from apps_comercio");
		$rowc = mysqli_fetch_assoc($sql_m);
			 $sql_s = mysqli_query($con,"SELECT m.* FROM apps_marcas m 
								WHERE m.status = 1 and id_marca <> 105");
			if(mysqli_num_rows($sql_s)!=0){
				 while($row=mysqli_fetch_assoc($sql_s)){
					 $titulo .= "♦️ *_".$row['marca']."_* \n\n";
					 $sql = mysqli_query($con,"SELECT * FROM apps_servicios_d 
							where id_marca = '".$row['id_marca']."' and status = 1");
					while($row2=mysqli_fetch_assoc($sql)){
						$servicios .= "*".$row2['cod']."*   👉🏻    ".$row2['servicio']."\n";
					 }
					 $titulo = $titulo."".$servicios."\n";
					 $servicios ="";
				 }
			 }	
			 
		if($rowz['lenguaje']==1){
			
			 $mensaje =  "🛍️ *".utf8_encode($rowc['titulo'])."* \n\n".
						saludo()." *".strtoupper($rowz['nombres'])."*\n".
						$titulo."\n".
						"*MC*     👉🏻   _Cambiar Cita_  🕟\n".
						"*CC*      👉🏻  _Cancelar Cita_  🚫\n".
						"*PC*      👉🏻  _Pagar Cita_ 💲\n".
						"*ADS*   👉🏻    _Ofertas_ 🏷️ \n";
		}else{
			
			 $mensaje =  "🛍️ *".utf8_encode($rowc['titulo'])."* \n\n".
						saludo2()." *".strtoupper($rowz['nombres'])."*\n".
						$titulo."\n".
						"*MC*     👉🏻   _Change Appointment_  🕟\n".
						"*CC*      👉🏻  _Cancel Appointment_  🚫\n".
						"*ADS*   👉🏻    _Offers_ 🏷️ \n";
		}
		
	}else{
		
			 $mensaje =  "🛍️ *".utf8_encode($rowc['titulo'])."* \n\n".
						saludo()." *".strtoupper($rowz['nombres'])."*\n".
						$titulo."\n".
						"*MC*     👉🏻   _Cambiar Cita_  🕟\n".
						"*CC*      👉🏻  _Cancelar Cita_  🚫\n".
						"*ADS*   👉🏻    _Ofertas_ 🏷️ \n";
	}
	return $mensaje;
}
?>