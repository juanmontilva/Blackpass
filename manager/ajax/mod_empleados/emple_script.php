			<?php
			include("../../dist/funciones/conexion.php");
			include("../../dist/funciones/api_ws.php");
			include("../../dist/funciones/funciones.php");
			if($_GET['accion']=='add'){
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_GET["t_codigo"],ENT_QUOTES)));//Escanpando caracteres 
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_GET["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$local			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_local"],ENT_QUOTES)));//Escanpando caracteres 

				$cek = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE codigo='$codigo'");
				if(mysqli_num_rows($cek) == 0){
						$insert = mysqli_query($con, "INSERT INTO apps_emple_s(codigo, nombres,  fecha_nacimiento, direccion, telefono,  estado,id_local)
															VALUES('$codigo','$nombres',  '$fecha_nacimiento', '$direccion', '$telefono', '1','$local')") or die(mysqli_error());
						if($insert){
							$id = mysqli_insert_id($con);
							if(!empty($_GET['servi'])){
							$check = explode(",",$_GET['servi']);
							// Ciclo para mostrar las casillas checked checkbox.
								foreach($check as $selected){
								  $insert_s = mysqli_query($con,"INSERT INTO apps_emple_s_d 
											(id_e,id_l,id_s) VALUES 
											('$id','$local','$selected')") ;
								}
							}
							$chatId = $telefono."@c.us";
							$mensaje = saludo()." *".$nombres."* \n".
							"_Has sido registrado en la plataforma *24hOpen*_ \n 
							_como profesional de la empresa que perteneces_.";
							sendMessage($chatId,$mensaje);
							//echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ok"));
						}else{
							//echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "2"));
						}
					 
				}else{
					header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ex"));
				}
			}
				if($_GET['accion']=='update'){
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_GET["t_codigo"],ENT_QUOTES)));//Escanpando caracteres 
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_GET["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				//$direccion	     = mysqli_real_escape_string($con,(strip_tags($_GET["t_dir"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$local			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_local"],ENT_QUOTES)));//Escanpando caracteres 
				$ide			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_ide"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion ="EBS";
				$cek = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE id ='$ide'");
				if(mysqli_num_rows($cek) != 0){
						$insert = mysqli_query($con, "UPDATE apps_emple_s set codigo = '$codigo', nombres = '$nombres',  
													fecha_nacimiento = '$fecha_nacimiento', direccion = '$direccion', 
													telefono = '$telefono', id_local = '$local'
													where id = '$ide' ") or die(mysqli_error());

						if($insert){
							$id = mysqli_insert_id($con);
							if(!empty($_GET['servi'])){
							$check = substr($_GET['servi'], 0, -1);
							$check = explode(",",$check);
							
							// Ciclo para mostrar las casillas checked checkbox.
								foreach($check as $selected){
									$sql_s = mysqli_query($con,"SELECT * FROM apps_emple_s_d
												where id_e = '$ide' and id_s = '$selected'");
									
									if(mysqli_num_rows($sql_s)==0){
										$row = mysqli_fetch_array($sql_s);
										if($selected!=0 && $selected !=""){
									    $insert_s = mysqli_query($con,"INSERT INTO apps_emple_s_d 
											(id_e,id_l,id_s) VALUES 
											('$ide','$local','$selected')") ;
										}

									}
								  
								}
								$sql_s = mysqli_query($con,"SELECT * FROM apps_emple_s_d
												where id_e = '$ide'");
									while($row = mysqli_fetch_assoc($sql_s)){
										$existe = 0;
										$ids = 0;
										foreach($check as $selected){
											if($selected==$row['id_s']){
												$existe = 1;
											}
										}
										if($existe==0){
											$delete = mysqli_query($con,"delete from apps_emple_s_d
														where id_e = '$ide' and id_s = '".$row['id_s']."' ");
										}
									}
									
							}
							$chatId = $telefono."@c.us";
							$mensaje = saludo()." *".$nombres."* \n".
							"_Se actualizaron tus servicio en *24hOpen*_ \n 
							_como profesional de la empresa que perteneces_.";
							sendMessage($chatId,$mensaje);
							//echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ok"));
						}else{
							//echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "2"));
						}
					 
				}else{
					header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ex"));
				}
			}
			
			if($_GET['accion']=='search'){
				$datos1 = array();
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["busqueda"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE codigo like '%$nik%' or nombres like '%$nik%'");
				if(mysqli_num_rows($cek) != 0){
					while($row = mysqli_fetch_assoc($cek)){
					$datos1[] =	array("nombre" => $row['nombres'],
									"codigo" => $row['codigo'],"tcliente" => $row['puesto'],"idc" => $row['id']);
					}
					
					$request_table_m = $datos1;
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"category" => $request_table_m
					));
					
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'no'
					));
				}
			}
			
				if($_GET['accion']=='search_h'){
				$datos1 = array();
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nhab"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_habitacion WHERE id_marca = '$nik' and status = 1");
				if(mysqli_num_rows($cek) != 0){
					while($row = mysqli_fetch_assoc($cek)){
					$datos1[] =	array("idh" => $row['idh'],
									"codigo" => $row['numero']);
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
			if($_GET['accion'] == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE codigo='$nik'");
				if(mysqli_num_rows($cek) == 0){
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
						));
				}else{
					$delete = mysqli_query($con, "DELETE FROM apps_emple_s WHERE codigo='$nik'");
					if($delete){
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
				if($_GET['accion'] == 'pause'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
				$fi = $_GET["fi"];
				$ff = $_GET["ff"];
				$fi = explode(" ",$fi);
				$fi2 = explode("/",$fi[0]);
				$fi = $fi2[2]."-".$fi2[1]."-".$fi2[0]." ".$fi[1].":00";
				$ff = explode(" ",$ff);
				$ff2 = explode("/",$ff[0]);
				$ff = $ff2[2]."-".$ff2[1]."-".$ff2[0]." ".$ff[1].":00";
				$cek = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE id='$nik'");
				if(mysqli_num_rows($cek) != 0){
					$insert = mysqli_query($con,"insert into events (title,id_cliente,id_pro,start,end,	idh,id_local,localizador)
					VALUES ('PERMISO','1','".$nik."','".$fi."','".$ff."','231','68','L0000')");
					/*echo "insert into events (title,id_cliente,id_pro,start,end,	idh,id_local,localizador)
					VALUES ('PERMISO','1','".$nik."','".$fi."','".$ff."','231','68','L0000'";
					*/
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
			
			if($_GET['accion'] == 'reser'){
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT e.* FROM apps_emple_s c, events e 
									WHERE c.id = e.id_cliente and c.codigo  = '$nik' ");
				if(mysqli_num_rows($cek) != 0){
				$i=1;
				$tabla = "";
				while($row = mysqli_fetch_array($registro))
				{
					$tabla.='{"#":"'.$i.'",   "Fecha":"'.$row['start'].'","Servicio":"'.$row['title'].'","Profesional":"'.$row['title'].'"},';		
					$i++;
				}
				$tabla = substr($tabla,0, strlen($tabla) - 1);
					echo '{"data":['.$tabla.']}';
				}	
			}
			
			?>
