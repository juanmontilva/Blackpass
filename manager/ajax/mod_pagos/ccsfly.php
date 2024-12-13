<?php
ini_set('display_errors', 0);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, OPTIONS');

		include("../../dist/funciones/conexion.php");
		include("../../dist/funciones/funciones.php");
		include("../../dist/funciones/cript.php");
		include("../../dist/funciones/api_ws.php");
		$fecha = date("Y-m-d");
		$accionT =  mysqli_real_escape_string($con,(strip_tags($_POST['accion'],ENT_QUOTES)));
		$codigo =  mysqli_real_escape_string($con,(strip_tags($_POST['clt'],ENT_QUOTES)));
		if($accionT=="validar_t"){
			$fecha = date("Y-m-d");
			$hora = date("H:i:s");
			$monto = 300;

			 $codigo = $encriptar($codigo);
		     $cek = mysqli_query($con, "SELECT c.* FROM apps_servicios_d s, apps_clientes c WHERE 
									c.id_cd = s.cod
									and s.qr = '".$codigo."'
									and s.status = 2");
			if(mysqli_num_rows($cek)==1){
				$row_c =  mysqli_fetch_assoc($cek);	
				$sql = mysqli_query($con,"SELECT * FROM apps_zzz where idc = '".$row_c['id']."'");
				if(mysqli_num_rows($sql)==1){
					$cl = $row_c['id'];
					$row =  mysqli_fetch_assoc($sql);
					$saldo = $row['zzz']-$monto;
					if($row['zzz']>=$monto){
					    $orden = cadena();
						
						$descrip = "FLY";
						$insert = mysqli_query($con,"INSERT INTO apps_pay_o
						(idc, orden, fecha, hora, monto, saldo, status, descrip, 
						operador,idl) VALUES 
						($cl,'".$orden."','".$fecha."','".$hora."','".$monto."','".$saldo."',
						1,'".$descrip."','ADMIN','76')");
					    
						if($insert){
							$update = mysqli_query($con,"UPDATE apps_zzz set zzz = (zzz-$monto)
								where idc = '".$cl."'");
							if($update){
							$primer = substr($row_c['telefono'], 0, 1);
							   if($primer!=4){
									 $chatId = "+".$row_c['telefono'];
								}else{
									 $chatId = "+58".$row_c['telefono'];
								}
								$phone = $chatId;
								$chatId = $phone."@c.us";
								$file = "logo4.jpg";
								$caption = "📲 ALERTAS *CARACAS FLY* \n\n".
									 saludo()." *".strtoupper($row_c['nombres'])."*\n\n".
									 "_Hemos procesado su pago_\n\n".
									 "*Detalles de la transaccion:*\n".
									 "_________________________________\n".
									 "_Descontado_: *".$monto." USD*"."\n".
									  "_Hora de ingreso_: *".$hora."*\n".
									 "_Saldo restante_: *".$saldo." USD*"."\n\n".
									 "_Siguenos en nuestras redes sociales_:\n".
									 "https://www.instagram.com/caracasfly/ \n";
								sendFile($chatId,$file,$caption);
								header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => 'ok',
								"confirmacion" => $orden,
								"rest"=>$saldo));
							}else{
								$primer = substr($row_c['telefono'], 0, 1);
							   if($primer!=4){
									 $chatId = "+".$row_c['telefono'];
								}else{
									 $chatId = "+58".$row_c['telefono'];
								}
								$phone = $chatId;
								$chatId = $phone."@c.us";
								$file = "logo4.jpg";
								$caption = "📲 ALERTAS *CARACAS FLY* \n\n".
									 saludo()." *".strtoupper($row_c['nombres'])."*\n\n".
									 "_No logramos procesar su pago_\n\n".
									 "*Detalles de la transaccion:*\n".
									 "_________________________________\n".
									 "_Descontado_: *0.00 USD*"."\n".
									 "_Saldo restante_: *".$saldo." USD*"."\n\n".
									 "_Siguenos en nuestras redes sociales_:\n".
									 "https://www.instagram.com/caracasfly/ \n";
								sendFile($chatId,$file,$caption);
								header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0',
								'respuesta'=>'No tiene tiempo de vuelo disponible'));
							}
						}else{
							header('Content-type: application/json; charset=utf-8');			
								echo json_encode(array(
								'response' => '0'));
						}
					}else{
						$primer = substr($row_c['telefono'], 0, 1);
							   if($primer!=4){
									 $chatId = "+".$row_c['telefono'];
								}else{
									 $chatId = "+58".$row_c['telefono'];
								}
								$phone = $chatId;
								$chatId = $phone."@c.us";
								$file = "logo4.jpg";
								$caption = "📲 ALERTAS *CARACAS FLY* \n\n".
									 saludo()." *".strtoupper($row_c['nombres'])."*\n\n".
									 "_No logramos procesar su pago_\n\n".
									 "*Detalles de la transaccion:*\n".
									 "_________________________________\n".
									 "_Descontado_: *0.00 USD*"."\n".
									 "_Saldo restante_: *".$saldo." USD*"."\n\n".
									 "_Siguenos en nuestras redes sociales:\n".
									 "https://www.instagram.com/caracasfly/ \n";
								sendFile($chatId,$file,$caption);
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

	
?>

