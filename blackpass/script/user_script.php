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
if (!isset($_SESSION['autenticado']) && isset($_SESSION['uid']) && isset($_SESSION['tc'])  && ($_SESSION['xy'])!=0  ) 
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: sign-in.html?error=ER001&".cadena2());
	//exit;
}else{
$fecha = date("Y-m-d");
$dia = date("Y-m-d H:i:s");
$chatId = "";
$i=0;
$j=0;
$accion="";

$accion = $_GET['xyz'];

if($accion=="p2p"){	
		
		 $txtbanco = mysqli_real_escape_string($con,(strip_tags($_GET['txtbanco'],ENT_QUOTES)));
		 $txtmetodo = mysqli_real_escape_string($con,(strip_tags($_GET['metodo'],ENT_QUOTES)));
		 $txttitular = mysqli_real_escape_string($con,(strip_tags($_GET['NROTLF'],ENT_QUOTES)));
		 $txtref = mysqli_real_escape_string($con,(strip_tags($_GET['NROREFERENCIA'],ENT_QUOTES)));
		 $txtmonto = mysqli_real_escape_string($con,(strip_tags($_GET['TOTAL '],ENT_QUOTES)));
		 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
		 $idc = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
		 $result = mysqli_real_escape_string($con,(strip_tags($_GET['MSGDES'],ENT_QUOTES)));
		 $error = mysqli_real_escape_string($con,(strip_tags($_GET['MSGERROR '],ENT_QUOTES)));
		if($error==1000){
		 $fecha = date("Y-m-d");
		 if($txtmetodo==2){
			 $referencia = "CH".cadena();
		 }else{
			 $referencia = $txtref;
		 } 
		 $idc = $desencriptar($idc);
			$usua_cl = "CL-".$_SESSION['uid'];
			$sql = mysqli_query($con,"select * from apps_clientes where id = '".$idc."' ");
			if(mysqli_num_rows($sql)!=0){
				$sqli = mysqli_query($con,"INSERT INTO apps_pagos 
						(fecha,monto,idc,orden,status,metodo,banco,registrado,
						referencia,pickup,contacto,phone,hora,direccion) VALUES 
						('".$fecha."','".$txtmonto."','".$idc."','".cadena()."','1',
						'".$txtmetodo."','".$txtbanco."','".$usua_cl."',
						'".$referencia."',$pick,
						'".$contac."','".$phone."','".$hora."','".$d2."')");
				
				if($sqli){
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
							
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
				));	
			}		
		}else{
			header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
				));	
		}
		}

	 if($accion=="vaudi"){
		    $uid = mysqli_real_escape_string($con,(strip_tags($_GET["tuser"],ENT_QUOTES)));
			
			$sql_1 = mysqli_query($con,"SELECT * from apps_clientes where mail = '$uid'  ");
			if(mysqli_num_rows($sql_1)!=0){
				$row_i = mysqli_fetch_assoc($sql_1);
				echo json_encode(array("response" => "ok","pais"=>$row_i['pais'],"us"=>$row_i['nombres'],
									"device"=>$row_i['telefono'],"token"=>cadena2()));
			}else{
				echo json_encode(array("response" => "2"));
			}
	}
 if($accion=="validar"){
		    $uid = mysqli_real_escape_string($con,(strip_tags($_GET["tuser"],ENT_QUOTES)));
			$pass = mysqli_real_escape_string($con,(strip_tags($_GET["tmp"],ENT_QUOTES)));
			$pass = explode(".",$pass);
			//echo "sin:".$pass[1]."<br>";
			$pass = $encriptar($pass[1]);
			//echo "con:".$pass."<br>";
			$sql_1 = mysqli_query($con,"SELECT c.*,c.nombres from apps_clientes c, apps_clientes_login l
				where c.mail = '$uid' 
				and c.id = l.user_id
				and l.token= '".$pass."'");
			if(mysqli_num_rows($sql_1)!=0){
				$row_i = mysqli_fetch_assoc($sql_1);
				$_SESSION['autenticado']    = 'SI';
				$_SESSION['uid']  = $row_i['nombres'];
				$_SESSION['nameu']  = $row_i['nombres'];
				$_SESSION['xy']  = $row_i['id'];
				$_SESSION['tc']  = $row_i['tcliente'];
				$_SESSION['perfi']  = 1;
				echo json_encode(array("response" => "ok","pais"=>$row_i['pais'],"us"=>$row_i['nombres'],
									"device"=>$row_i['telefono'],"token"=>cadena2()));
			}else{
				echo json_encode(array("response" => "2"));
			}
	}
	
if($accion=="addr"){	
		
		 $txtbanco = mysqli_real_escape_string($con,(strip_tags($_GET['txtbanco'],ENT_QUOTES)));
		 $txtmetodo = mysqli_real_escape_string($con,(strip_tags($_GET['metodo'],ENT_QUOTES)));
		 $txttitular = mysqli_real_escape_string($con,(strip_tags($_GET['txttitular'],ENT_QUOTES)));
		 $txtref = mysqli_real_escape_string($con,(strip_tags($_GET['txtref'],ENT_QUOTES)));
		 $txtmonto = mysqli_real_escape_string($con,(strip_tags($_GET['txtmonto'],ENT_QUOTES)));
		 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
		 $idc = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
		 $pick = mysqli_real_escape_string($con,(strip_tags($_GET['pick'],ENT_QUOTES)));
		 $phone = mysqli_real_escape_string($con,(strip_tags($_GET['phone'],ENT_QUOTES)));
		 $contac = mysqli_real_escape_string($con,(strip_tags($_GET['contacto'],ENT_QUOTES)));
		 $d2 = mysqli_real_escape_string($con,(strip_tags($_GET['direc2'],ENT_QUOTES)));
		 $hora = mysqli_real_escape_string($con,(strip_tags($_GET['hora'],ENT_QUOTES)));
		 $fecha = date("Y-m-d");
		 if($txtmetodo==2){
			 $referencia = "CH".cadena();
		 }else{
			 $referencia = $txtref;
		 }
		 $idc = $desencriptar($idc);
			$usua_cl = "CL-".$_SESSION['uid'];
			$sql = mysqli_query($con,"select * from apps_clientes where id = '".$idc."' ");
			if(mysqli_num_rows($sql)!=0){
				$sqli = mysqli_query($con,"INSERT INTO apps_pagos 
						(fecha,monto,idc,orden,status,metodo,banco,registrado,
						referencia,pickup,contacto,phone,hora,direccion) VALUES 
						('".$fecha."','".$txtmonto."','".$idc."','".cadena()."','1',
						'".$txtmetodo."','".$txtbanco."','".$usua_cl."',
						'".$referencia."',$pick,
						'".$contac."','".$phone."','".$hora."','".$d2."')");
				
				if($sqli){
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
							
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
				));	
			}		

		}
		if($accion=="pass"){
			$tarjeta = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
			$txtmail = mysqli_real_escape_string($con,(strip_tags($_GET['txtmail'],ENT_QUOTES)));
			$tarjeta = substr($tarjeta, 12); 
			$sql = mysqli_query($con,"SELECT c.* FROM apps_clientes c, apps_servicios_d s
								where c.id_cd = s.cod
								and s.descripcion = '".$tarjeta."'
								and c.mail = '".$txtmail."' and c.status = 1");
			if(mysqli_num_rows($sql)!=0){
				$cadena = cadena();
				$token = $encriptar($cadena);
				$row = mysqli_fetch_assoc($sql);	
				$nombres = $row['nombres'];
				$update = mysqli_query($con,"UPDATE apps_clientes_login set token = '".$token."' 
					where user_id = '".$row['id']."' ");
					if($update){
						$respuesta = enviarmail2($txtmail,$nombres,$cadena,2);
						if($respuesta[0]==1){
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' =>'ok'));
						}else{
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "2"));
						}
					}
			}else{
				header('Content-type: application/json; charset=utf-8');			
				echo json_encode(array(
				'response' => '2'
				));	
			}
		}
		
		if($accion=="trv"){
			 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
			 $sql = mysqli_query($con,"SELECT s.descripcion FROM apps_clientes c,apps_servicios_d s 
									WHERE c.id_cd = s.cod
									and c.id = '".$namec."' ");

			if(mysqli_num_rows($sql)==1){
				$row = mysqli_fetch_assoc($sql);	
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						'card'=>$row['descripcion']
			   ));	
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
			   ));
			}
		}
	
		if($accion=="trf2"){	//para recargas corporativas P2P
			 $txtmonto = mysqli_real_escape_string($con,(strip_tags($_GET['txtmonto'],ENT_QUOTES)));
			 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
			 $idc = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
			 $tar = substr($idc, 14); 
			 $fecha = date("Y-m-d");
			 $referencia = "SALDO CLIENTE-".$_SESSION['xy'];
			 $usua_cl = "CL-".$_SESSION['xy'];
			 $sqlt = mysqli_query($con,"select c.id from
								 apps_clientes c, apps_servicios_d s
								 WHERE c.id_cd = s.cod
								 and descripcion = '".$tar."' ");

			$sql = mysqli_query($con,"select * from apps_clientes where id = '".$_SESSION['xy']."' ");
			if(mysqli_num_rows($sql)!=0){
				$sql_s = mysqli_query($con,"SELECT * from apps_zzz where idc = '".$_SESSION['xy']."'");
					if(mysqli_num_rows($sql_s)!=0){
						$row_s = mysqli_fetch_assoc($sql_s);
						if($row_s['zzz']>=$txtmonto){
							if(mysqli_num_rows($sqlt)!=0){
									 $trans = cadena();
									 $row2 = mysqli_fetch_assoc($sqlt);
									 if($row2['id']!=$idc){
										  $insert_s = mysqli_query($con,"INSERT INTO
									 apps_yyy (orden,send,receive,monto,fecha,tipo,status)
									 VALUES ('".$trans."','".$_SESSION['xy']."','".$row2['id']."',$txtmonto,
									 '".$dia."','1','1')");
									 if($insert_s){
										 $sqli = mysqli_query($con,"INSERT INTO apps_pagos 
											(fecha,monto,idc,orden,status,metodo,banco,registrado,
											referencia,pickup) VALUES 
											('".$fecha."','".$txtmonto."','".$row2['id']."','".$trans."','2',
											'4','0','".$usua_cl."',
											'".$referencia."',0)");
												  if($sqli){
														$update = mysqli_query($con,"SELECT * FROM apps_zzz where idc = '".$row2['id']."' ");
														if(mysqli_num_rows($update)==0){
															$insert = mysqli_query($con,"INSERT INTO apps_zzz (idc,zzz)
																			VALUES ('".$row2['id']."','".$txtmonto."')");
															$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz -$txtmonto )
																			WHERE idc = '".$_SESSION['xy']."' ");
															if($insert){
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
														}else{
															
															$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz +$txtmonto )
																			WHERE idc = '".$row2['id']."' ");
															$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz -$txtmonto )
																			WHERE idc = '".$_SESSION['xy']."' ");
															if($update){
																header('Content-type: application/json; charset=utf-8');			
																	echo json_encode(array(
																	'response' => 'ok'
																));
															}else{
																header('Content-type: application/json; charset=utf-8');			
																	echo json_encode(array(
																	'response' => 'ok'
																));	
															}
														}
												  }else{
													  header('Content-type: application/json; charset=utf-8');			
															echo json_encode(array(
															'response' => '3' // NO SE INSERTO LA ORDEN DE PAGO
														));	
												  }
											 }
									 }else{
										 header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '8' // CUENTA INVALIDA
										));
									 }
									
								 }else{
									 header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '5' // CUENTA INVALIDA
									));
								 }
						}else{
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '4' // FONDO INSUFICIENTE
							));
						}
					}else{
						header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '4' // FONDO INSUFICIENTE
							));
					}		
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
				));	
			}
		}
		if($accion=="trf"){	
		
		 $txtbanco = mysqli_real_escape_string($con,(strip_tags($_GET['txtbanco'],ENT_QUOTES)));
		 $txtmetodo = mysqli_real_escape_string($con,(strip_tags($_GET['metodo'],ENT_QUOTES)));
		 $txttitular = mysqli_real_escape_string($con,(strip_tags($_GET['txttitular'],ENT_QUOTES)));
		 $txtref = mysqli_real_escape_string($con,(strip_tags($_GET['txtref'],ENT_QUOTES)));
		 $txtmonto = mysqli_real_escape_string($con,(strip_tags($_GET['txtmonto'],ENT_QUOTES)));
		 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
		 $idc = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
		  $pick = mysqli_real_escape_string($con,(strip_tags($_GET['pick'],ENT_QUOTES)));
		 $tar = substr($namec, 12); 
		 //echo $tar;
		 $fecha = date("Y-m-d");
		 $idc = str_replace(' ', '+', $idc);
		 $idc = $desencriptar($idc);
		 if($txtmetodo==2){
			 $referencia = "CH".cadena();
		 }else if($txtmetodo==4){
			  $referencia = "SALDO CLIENTE-".$idc;
		 }
		 else{
			 $referencia = $txtref;
		 }
			$usua_cl = "CL-".$idc;
			$sqlt = mysqli_query($con,"select c.id from
								 apps_clientes c, apps_servicios_d s
								 WHERE c.id_cd = s.cod
								 and descripcion = '".$tar."' ");
			$sql = mysqli_query($con,"select * from apps_clientes where id = '".$idc."' ");
			if(mysqli_num_rows($sql)!=0){
				 if($txtmetodo==4){ // si es con saldo en cuenta
					$sql_s = mysqli_query($con,"SELECT * from apps_zzz where idc = '".$idc."'");
						if(mysqli_num_rows($sql_s)!=0){
						 $row_s = mysqli_fetch_assoc($sql_s);	
							 if($row_s['zzz']>=$txtmonto){
								 
								 if(mysqli_num_rows($sqlt)!=0){
									 $trans = cadena();
									 $row2 = mysqli_fetch_assoc($sqlt);
									 if($row2['id']!=$idc){
										  $insert_s = mysqli_query($con,"INSERT INTO
									 apps_yyy (orden,send,receive,monto,fecha,tipo,status)
									 VALUES ('".$trans."','".$idc."','".$row2['id']."',$txtmonto,
									 '".$dia."','1','1')");
									 if($insert_s){
										 $sqli = mysqli_query($con,"INSERT INTO apps_pagos 
											(fecha,monto,idc,orden,status,metodo,banco,registrado,
											referencia,pickup) VALUES 
											('".$fecha."','".$txtmonto."','".$row2['id']."','".$trans."','2',
											'".$txtmetodo."','".$txtbanco."','".$usua_cl."',
											'".$referencia."',0)");
												  if($sqli){
														$update = mysqli_query($con,"SELECT * FROM apps_zzz where idc = '".$row2['id']."' ");
														if(mysqli_num_rows($update)==0){
															$insert = mysqli_query($con,"INSERT INTO apps_zzz (idc,zzz)
																			VALUES ('".$row2['id']."','".$txtmonto."')");
															$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz -$txtmonto )
																			WHERE idc = '".$idc."' ");
															if($insert){
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
														}else{
															
															$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz +$txtmonto )
																			WHERE idc = '".$row2['id']."' ");
															$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz -$txtmonto )
																			WHERE idc = '".$idc."' ");
															if($update){
																header('Content-type: application/json; charset=utf-8');			
																	echo json_encode(array(
																	'response' => 'ok'
																));
															}else{
																header('Content-type: application/json; charset=utf-8');			
																	echo json_encode(array(
																	'response' => 'ok'
																));	
															}
														}
												  }else{
													  header('Content-type: application/json; charset=utf-8');			
															echo json_encode(array(
															'response' => '3' // NO SE INSERTO LA ORDEN DE PAGO
														));	
												  }
											 }
									 }else{
										 header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '8' // CUENTA INVALIDA
										));
									 }
									
								 }else{
									 header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '5' // CUENTA INVALIDA
									));
								 }
						}else{
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '4' // FONDO INSUFICIENTE
							));
						}
					}else{
						header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '4' // FONDO INSUFICIENTE
							));
					}						
				 }else{
					 if(mysqli_num_rows($sqlt)!=0){
						$trans = cadena();
						$row2 = mysqli_fetch_assoc($sqlt);	
						if($row2['id']!=$idc){
						 $insert_s = mysqli_query($con,"INSERT INTO
								 apps_yyy (orden,send,receive,monto,fecha,tipo,status)
								 VALUES ('".$trans."','".$idc."','".$row2['id']."',$txtmonto,
								 '".$dia."','1','2')");
							if($insert_s){
								$sqli = mysqli_query($con,"INSERT INTO apps_pagos 
								(fecha,monto,idc,orden,status,metodo,banco,registrado,
								referencia,pickup) VALUES 
								('".$fecha."','".$txtmonto."','".$row2['id']."','".cadena()."','1',
								'".$txtmetodo."','".$txtbanco."','".$usua_cl."',
								'".$referencia."',$pick)");
								if($sqli){
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
							}else{
								header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '2'
									));	
							}
						}else{
							header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '8'
									));	
						}
					}else{
						header('Content-type: application/json; charset=utf-8');			
										echo json_encode(array(
										'response' => '5'
									));	
					}
				 }
							
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
				));	
			}		

		}
}
?>