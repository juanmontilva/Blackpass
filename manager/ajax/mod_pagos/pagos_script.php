<?php
session_start();
		if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) &&  isset($_SESSION['local']) ) )
		{
			//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
			//pantalla de login, enviando un codigo de error
			header ("Location: ../../index.php?error=ER001");
			exit;
		}else{
		include("../../dist/funciones/conexion.php");
		include("../../dist/funciones/funciones.php");
		include("../../dist/funciones/cript.php");
		$fecha = date("Y-m-d");
		$accionT =  mysqli_real_escape_string($con,(strip_tags($_GET['accion'],ENT_QUOTES)));
		if($accionT=="upcambio"){	
		$pais =  $_GET['cod'];
		$monto =  $_GET['mont'];
						$sqli = "UPDATE apps_control_cambio set status = 0 where id_pais = '".$pais."'" ;
						$obj->ejecutar_sql($sqli);
						 
						$sqli = "INSERT INTO apps_control_cambio (fecha,monto,status,id_pais) VALUES 
						('".$fecha."','".$monto."',1,'".$pais."')";
						//echo $sqli."<br>";
						$obj->ejecutar_sql($sqli);
																  

				header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok'
				));

		}
		if($accionT=="add"){	
		
		 $txtbanco = mysqli_real_escape_string($con,(strip_tags($_GET['txtbanco'],ENT_QUOTES)));
		 $txtmetodo = mysqli_real_escape_string($con,(strip_tags($_GET['metodo'],ENT_QUOTES)));
		 $txttitular = mysqli_real_escape_string($con,(strip_tags($_GET['txttitular'],ENT_QUOTES)));
		 $txtref = mysqli_real_escape_string($con,(strip_tags($_GET['txtref'],ENT_QUOTES)));
		 $txtmonto = mysqli_real_escape_string($con,(strip_tags($_GET['txtmonto'],ENT_QUOTES)));
		 $namec = mysqli_real_escape_string($con,(strip_tags($_GET['namec'],ENT_QUOTES)));
		 $idc = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
		 $fecha = date("Y-m-d");
		 if($txtmetodo==2){
			 $referencia = "CH".$idc;
		 }else{
			 $referencia = $idc;
		 }
		 
		 	if($txtmetodo==6){
					$sqlc = mysqli_query($con,"SELECT * from apps_control_cambio
											where status = 1 and id_pais = 1");
					if(mysqli_num_rows($sqlc)!=0){
						$row2 =  mysqli_fetch_assoc($sqlc);
						$cambio = $row2['monto'];
						$txtmonto = $txtmonto/$row2['monto'];
					}
					//$monto = $row['monto']/$row2['monto'];
				}else{
					$txtmonto = $txtmonto;
				}
			$sql = mysqli_query($con,"select * from apps_clientes where id = '".$namec."' ");
			if(mysqli_num_rows($sql)!=0){
				$sqli = mysqli_query($con,"INSERT INTO apps_pagos 
						(fecha,monto,idc,orden,status,metodo,banco,registrado,referencia) VALUES 
						('".$fecha."','".$txtmonto."','".$namec."','".$idc."','2',
						'".$txtmetodo."','".$txtbanco."','".$_SESSION['usuario']."','".$referencia."')");
				
				if($sqli){
					
					$update = mysqli_query($con,"SELECT * FROM apps_zzz where idc = '".$namec."' ");
					if(mysqli_num_rows($update)==0){
						$insert = mysqli_query($con,"INSERT INTO apps_zzz (idc,zzz)
										VALUES ('".$namec."','".$txtmonto."')");
						if($insert){
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok'
							));
						}
					}else{
						
						$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz +$txtmonto )
										WHERE idc = '".$namec."' ");
						if($update){
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok'
							));
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
						'response' => '2'
				));	
			}		

		}
		if($accionT=="confir"){
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["namec"],ENT_QUOTES)));
		   $nik2 = $encriptar($nik);
		   $nik = substr($nik, -4);
		    
			$cek = mysqli_query($con, "SELECT c.* FROM apps_clientes c, apps_xyz x, apps_servicios_d d
									WHERE c.id_cd = d.cod
									and d.descripcion = '".$nik."'
									and d.qr = '".$nik2."'
									 GROUP BY c.id");

		   if(mysqli_num_rows($cek)==1){
			   $row = mysqli_fetch_array($cek);
			   header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					'name'=> $row['nombres'],
					'xy'=>$row['id']
				));
		   }else{
			    header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => '0'
				));
		   }
		
	}
	if($accionT=="confir2"){
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["namec"],ENT_QUOTES)));
		   $nik2 = $encriptar($nik);
		   $nik = substr($nik, -4);
		    
			$cek = mysqli_query($con, "SELECT c.* FROM apps_clientes c, apps_xyz x, apps_servicios_d d
									WHERE c.id_cd = d.cod
									and d.descripcion = '".$nik."'
									and d.qr = '".$nik2."'
									 GROUP BY c.id");

		   if(mysqli_num_rows($cek)==1){
			   $row = mysqli_fetch_array($cek);
			   $sql = mysqli_query($con,"SELECT * from apps_zzz where idc = '".$row['id']."'");
				$saldo = 0;
			   if(mysqli_num_rows($sql)==1){
					$row2 = mysqli_fetch_array($sql);
					$saldo = intval($row2['zzz']);
				}else{
					$saldo = 0;
				}
			   header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					'name'=> $row['nombres'],
					'xy'=>$row['id'],
					'disp'=>$saldo,
					'orde'=>cadena()
				));
		   }else{
			    header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => '0'
				));
		   }
		
	}
		if($accionT=="consulta"){	

		 $valor = mysqli_real_escape_string($con,(strip_tags($_GET['cod'],ENT_QUOTES)));
		 $ord = mysqli_real_escape_string($con,(strip_tags($_GET['xy'],ENT_QUOTES)));
		//$idr = explode("-",$valor);
		$mmarca= "SELECT p.* FROM apps_pagos p where p.idc = '".$valor."' 
		        and orden = '".$ord."'";
			//echo $mmarca;
			$query_u = mysqli_query($con,$mmarca);
			$datos1 = array();
			if(mysqli_num_rows($query_u)!=0){

				 while($resultado = mysqli_fetch_assoc($query_u) ){
					 
				$datos1[] = array('origen'=>$resultado['banco'],
						'destino'=>$resultado['banco'],'referencia'=>$resultado['referencia'],
						 'monto'=>intval($resultado['monto']),
						 'fecha'=>$resultado['fecha'],
						 'orden'=>$resultado['orden'],
						 'tp'=>$resultado['metodo'],
						 'hora'=>$resultado['hora'],
						 'phone'=>$resultado['phone'],
						 'd3'=>$resultado['direccion'],
						 'contac'=>$resultado['contacto'],
						 'pick'=>$resultado['pickup']);
				 } 
					$request_table_m = $datos1;
					header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					"category" => $request_table_m));
			}else{
			echo json_encode(array('response'=>'no'));
			}	
	}
	
		if($accionT=="validar_p"){	
		 $valor = mysqli_real_escape_string($con,(strip_tags($_GET['cl'],ENT_QUOTES)));
		 $ord = mysqli_real_escape_string($con,(strip_tags($_GET['cod'],ENT_QUOTES)));
		 $confirm= $_GET['confirm'];

		$mmarca= "SELECT p.* FROM apps_pagos p where p.idc = '".$valor."' 
		        and orden = '".$ord."'";
			$query_u = mysqli_query($con,$mmarca);
	
			if(mysqli_num_rows($query_u)!=0){
				$row =  mysqli_fetch_assoc($query_u);
				if($row['metodo']==6){
					$sqlc = mysqli_query($con,"SELECT * from apps_control_cambio
											where status = 1 and id_pais = 1");
					if(mysqli_num_rows($sqlc)!=0){
						$row2 =  mysqli_fetch_assoc($sqlc);
						$cambio = $row2['monto'];
						$monto = $row['monto']/$row2['monto'];
					}
					$monto = $row['monto']/$row2['monto'];
				}else{
					$monto = $row['monto'];
				}
				
				$sqli = "UPDATE apps_pagos set monto = '".$monto."', status = $confirm,fecha = '".$fecha."' ,registrado = '".$_SESSION['usuario']."'
				where idc = '".$valor."' and orden = '".$ord."'";
				mysqli_query($con, $sqli);
				$update = mysqli_query($con,"SELECT * FROM apps_zzz where idc = '".$valor."' ");
					if(mysqli_num_rows($update)==0){
						$insert = mysqli_query($con,"INSERT INTO apps_zzz (idc,zzz)
										VALUES ('".$valor."','".$monto."')");
						
						if($insert){
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok'
							));
						}
					}else{
						$sqli2 = "UPDATE apps_zzz set zzz = (zzz+'".$monto."') where idc = '".$valor."'";
						mysqli_query($con, $sqli2);
						echo json_encode(array('response'=>'ok'));
					}
			}else{
				echo json_encode(array('response'=>'no'));
			}
	}
		if($accionT=="pays"){
			$fecha = date("Y-m-d");
			$hora = date("H:i:s");
			 $monto = mysqli_real_escape_string($con,(strip_tags($_GET['sl'],ENT_QUOTES)));
			 $saldo = mysqli_real_escape_string($con,(strip_tags($_GET['sald'],ENT_QUOTES)));
			 $cl = mysqli_real_escape_string($con,(strip_tags($_GET['idc'],ENT_QUOTES)));
			 $orden = mysqli_real_escape_string($con,(strip_tags($_GET['ord'],ENT_QUOTES)));
			 $descrip = mysqli_real_escape_string($con,(strip_tags($_GET['descr'],ENT_QUOTES)));
			 $ubicacion = mysqli_real_escape_string($con,(strip_tags($_GET['lg'],ENT_QUOTES)));
		     $cek = mysqli_query($con, "SELECT c.* FROM apps_clientes c
										WHERE id = $cl");
			if(mysqli_num_rows($cek)==1){
				
				$sql = mysqli_query($con,"SELECT * FROM apps_zzz where idc = $cl");
				if(mysqli_num_rows($sql)==1){
					
					$row =  mysqli_fetch_assoc($sql);
					if($row['zzz']>=$monto){
					
						$insert = mysqli_query($con,"INSERT INTO apps_pay_o
						(idc, orden, fecha, hora, monto, saldo, status, descrip, 
						operador,idl) VALUES 
						($cl,'".$orden."','".$fecha."','".$hora."','".$monto."','".$saldo."',
						1,'".$descrip."','".$_SESSION['usuario']."','".$ubicacion."')");
					    
						if($insert){
							$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz-$monto)
								where idc = '".$cl."'");
							if($update){
								header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok',
								"confirmacion" => $orden));
							}else{
								header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0'));
							}
						}else{
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0'));
						}
					}else{
						header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0'));
					}
				}else{
					header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0'));
				}
			}else{
				header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0'));
			}
		}
}
	
?>

