<?php
session_start();

header('Content-type: application/json; charset=utf-8');
// URL for request GET /messages
include("dist/funciones/funciones.php");
include("dist/funciones/api_ws.php");
include("dist/funciones/conexion.php");
include("dist/funciones/cript.php");
include("dist/mail/sendqrmail.php");
include("dist/qr/index.php");
date_default_timezone_set('America/Caracas');
/*$APIurl = 'https://api.chat-api.com/instance221818/';
$token = 'hzsdahpwy9wy4la7';
$instanceId = '215059';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
*/
$fecha = date("Y-m-d");
$chatId = "";
$i=0;
$j=0;
$accion="";
			if($_GET['accion']=='update_e'){
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_GET["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$mail			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_mail"],ENT_QUOTES)));
				$lg			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_lg"],ENT_QUOTES)));
				$t_ssn			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_ssn"],ENT_QUOTES)));
				$t_tc			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_cl"],ENT_QUOTES)));
				$identi = $lg."-".$t_ssn;
				$t_tc = str_replace(' ', '+', $t_tc);
				$t_tc = $desencriptar($t_tc);
				$sql = mysqli_query($con,"SELECT * FROM apps_clientes
									where id = '".$t_tc."' ");
				if(mysqli_num_rows($sql)!=0){
					$sql2 = mysqli_query($con,"SELECT * FROM apps_clientes
									where (mail =  '".$mail."' or telefono = '".$telefono."')
									and id <> '".$t_tc."' ");
					if(mysqli_num_rows($sql2)==0){
						$update = mysqli_query($con,"UPDATE apps_clientes set 
										nombres = '$nombres',fecha_nacimiento = '$fecha_nacimiento',
										mail = '$mail', telefono = '$telefono',
										ssn = '$identi' where id = '".$t_tc."' ");
						if($update){
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' =>'ok'
							));
						}else{
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' =>'2'
							));
						}
					}else{
						header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' =>'ex'
							));
					}
				}else{
					header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' =>'3'
							));
				}
			}
		if($_GET['accion']=='add'){
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_GET["t_codigo"],ENT_QUOTES)));//Escanpando caracteres 
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_GET["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$plan		 = mysqli_real_escape_string($con,(strip_tags($_GET["plan"],ENT_QUOTES)));//Escanpando caracteres 
				$mail			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_mail"],ENT_QUOTES)));
				$lg			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_lg"],ENT_QUOTES)));
				$t_direc			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_direc"],ENT_QUOTES)));
				$t_ssn			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_ssn"],ENT_QUOTES)));
				$t_tc			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_tc"],ENT_QUOTES)));
				$razon			 = mysqli_real_escape_string($con,(strip_tags($_GET["razon"],ENT_QUOTES)));
				//$t_ssn = $encriptar($t_ssn);
				$direccion = 73;
				//$fecha_nacimiento = explode("/",$fecha_nacimiento);
				$identi = $lg."-".$t_ssn;
				//$fecha_nacimiento = $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];
				$cek = mysqli_query($con, "SELECT * FROM apps_clientes WHERE codigo='$codigo' or ssn = '".$identi."' ");
				if(mysqli_num_rows($cek) == 0){
		
							$insert = mysqli_query($con, "INSERT INTO apps_clientes(codigo, nombres,fecha_nacimiento,
														direccion,pais, telefono,tcliente,estado,
														ssn,plan,mail,status,razon)
														VALUES('$codigo','$nombres','$fecha_nacimiento',
														'".$t_direc."','1','$telefono','".$t_tc."','".$direccion."',
														'".$identi."','$plan','$mail','1','".$razon."')");
					
						if($insert){
							$id= mysqli_insert_id($con);
							$cadena = cadena();
							$token = $encriptar($cadena);
							$insert_login = mysqli_query($con,"INSERT INTO apps_clientes_login (user_id,token)
															VALUES ($id,'".$token."')");
							if($insert_login){
								$respuesta = enviarmail2($mail,$nombres,$cadena,1);
								if($respuesta[0]==1){
									
								$sql = mysqli_query($con,"SELECT * FROM apps_servicios_d 
									WHERE id_marca = '".$plan."' 
									and status = 1 
									ORDER BY idh ASC LIMIT 1");
									if(mysqli_num_rows($sql) != 0){
										$row2 = mysqli_fetch_array($sql);
										$update = mysqli_query($con,"UPDATE apps_clientes 
												set id_cd = '".$row2['cod']."' WHERE id = $id ");
										
										$update2 = mysqli_query($con,"UPDATE apps_servicios_d 
												set status = 2 where cod = '".$row2['cod']."' ");
										if($update){
											$sql3 = mysqli_query($con,"SELECT * FROM apps_xyz 
														WHERE prod like '%".$row2['descripcion']."%' ");
											
											if(mysqli_num_rows($sql3) == 1){
												$row3 = mysqli_fetch_array($sql3);
												$qrcod = generaQR($row3['prod']);
												$respuesta = enviarmailqr($mail,$nombres,$row2['cod'],$qrcod);
												if($respuesta[0]==1){
													header('Content-type: application/json; charset=utf-8');			
													echo json_encode(array(
													'response' =>'ok',
													"card" => $row2['descripcion'],
													"name"=> $nombres
													));
												}else{
													header('Content-type: application/json; charset=utf-8');
													echo json_encode(array("response" => $respuesta[0]));
												}
											}else{
												header('Content-type: application/json; charset=utf-8');			
														echo json_encode(array(
													'response' => '0'
													));
											}
											
											
										}
									}
									
								}
							}
															
							
						}else{
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '2'
							));
						}
					 
				}else{
					header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ex"));
				}
			}
		if($_GET['accion']=='add_e'){
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_GET["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$plan		 = mysqli_real_escape_string($con,(strip_tags($_GET["plan"],ENT_QUOTES)));//Escanpando caracteres 
				$mail			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_mail"],ENT_QUOTES)));
				$lg			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_lg"],ENT_QUOTES)));
				$t_ssn			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_ssn"],ENT_QUOTES)));
				$t_tc			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_tc"],ENT_QUOTES)));
				$patrono			 = mysqli_real_escape_string($con,(strip_tags($_GET["t_cl"],ENT_QUOTES)));
				//$t_ssn = $encriptar($t_ssn);
				$patrono = str_replace(' ', '+', $patrono);
				$direccion = 73;
				$patrono = $desencriptar($patrono);
				//$fecha_nacimiento = explode("/",$fecha_nacimiento);
				$identi = $lg."-".$t_ssn;
				//$fecha_nacimiento = $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];
				$cek = mysqli_query($con, "SELECT * FROM apps_clientes WHERE id= '$patrono'  ");
				if(mysqli_num_rows($cek) != 0){
						$cek = mysqli_query($con, "SELECT * FROM apps_clientes 
											WHERE mail ='".$mail."' or ssn = '".$identi."' 
											or telefono = '".$telefono."'");

						if(mysqli_num_rows($cek) == 0){
							$insert = mysqli_query($con, "INSERT INTO apps_clientes(codigo, nombres,fecha_nacimiento,
													direccion,pais, telefono,tcliente,estado,
													ssn,plan,mail,status,dependiente)
													VALUES('$telefono','$nombres','$fecha_nacimiento',
													'NINGUNA','1','$telefono','1','".$direccion."',
													'".$identi."','$plan','$mail','1','".$patrono."')");
							if($insert){
								$id= mysqli_insert_id($con);
								$cadena = cadena();
								$token = $encriptar($cadena);
								$insert_login = mysqli_query($con,"INSERT INTO apps_clientes_login (user_id,token)
																VALUES ($id,'".$token."')");
								$update_c = mysqli_query($con,"UPDATE apps_clientes set codigo = $id
															where id = $id");
								if($insert_login){
									$respuesta = enviarmail2($mail,$nombres,$cadena);
									if($respuesta[0]==1){
										
									$sql = mysqli_query($con,"SELECT * FROM apps_servicios_d 
										WHERE id_marca = '".$plan."' 
										and status = 1 
										ORDER BY idh ASC LIMIT 1");
										if(mysqli_num_rows($sql) != 0){
											$row2 = mysqli_fetch_array($sql);
											$update = mysqli_query($con,"UPDATE apps_clientes 
													set id_cd = '".$row2['cod']."' WHERE id = $id ");
											
											$update2 = mysqli_query($con,"UPDATE apps_servicios_d 
													set status = 2 where cod = '".$row2['cod']."' ");
											if($update){
												$sql3 = mysqli_query($con,"SELECT * FROM apps_xyz 
															WHERE prod like '%".$row2['descripcion']."%' ");
												
												if(mysqli_num_rows($sql3) == 1){
													$row3 = mysqli_fetch_array($sql3);
													$qrcod = generaQR($row3['prod']);
													$respuesta = enviarmailqr($mail,$nombres,$row2['cod'],$qrcod);
													if($respuesta[0]==1){
														header('Content-type: application/json; charset=utf-8');			
														echo json_encode(array(
														'response' =>'ok',
														"card" => $row2['descripcion'],
														"name"=> $nombres
														));
													}else{
														header('Content-type: application/json; charset=utf-8');
														echo json_encode(array("response" => "2"));
													}
												}else{
													header('Content-type: application/json; charset=utf-8');			
															echo json_encode(array(
														'response' => '0'
														));
												}
												
												
											}
										}
										
									}
								}
																
								
							}else{
								header('Content-type: application/json; charset=utf-8');			
									echo json_encode(array(
									'response' => '2'
								));
							}
						}else{
							header('Content-type: application/json; charset=utf-8');			
									echo json_encode(array(
									'response' => 'ex'
								));
						}

					 
				}else{
					header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "3"));
				}
			}
?>
