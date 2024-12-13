<?php
$dias = date("Y-m-d");
session_start();
		if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
		{
			//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
			//pantalla de login, enviando un codigo de error
			header ("Location: ../../index.php?error=ER001");
			exit;
		}else{
		include("../../dist/funciones/funciones.php");
		include("../../dist/funciones/conexion.php");
//echo "Conexión exitosa!";
		if($_SESSION['perfi']==1){
			$where = " and p.cod = mp.id_pais ";
			$from = "";
			$requerir = "";
		}
		$fecha = date("Y-m-d");
		$accionT =  $_GET['accion'];
		if($accionT=="group_data"){	
			$s = semanas();
			$where = "";
			if($_SESSION['perfi'] ==2){
				$where = " and e.id_pro = '".$_SESSION['uid']."'";
			}else{
				$where = " and 1 = 1";
			}
			$sql = "SELECT * FROM  apps_pagos
				WHERE fecha BETWEEN  '".$s[0]."' and '".$s[1]."' " ;
			$result = mysqli_query($con, $sql);
			$paga = $prog = $canc = $conf = $i = $dia = 0;
			$totalm = $proyec = $anul = $confir = 0;
			while($row2 = mysqli_fetch_assoc($result)) {
				if($row2['status']==0){
					$prog = $prog+1; 
					$anul = $anul + $row2['monto'];
				}else if($row2['status']==1){
					$conf = $conf+1; 
					$proyec = $proyec + $row2['monto'];
				}else if($row2['status']==2){
					$paga = $paga+1; 
					$confir = $confir + $row2['monto'];
				}
				$h = explode(" ",$row2['fecha']);
				//echo $h[0]."<br>";
				//echo "hoy es: ".$dias."<br>";
				if(strcmp($h[0],$dias)==0){
					$dia = $dia +1;
				}
				$i++;
				$totalm = $totalm + $row2['monto'];
			}
			$totalm = $totalm - $anul;
			$total = $i;
			header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
			 echo json_encode(array("response" => "ok","prog" => $prog,
									"anu"=>$canc,
									"paga"=>$paga,
									"conf"=>$conf,
									"total"=>$total,
									"hoy"=>$dia,
									"mc"=>number_format($confir, 2, '.', ','),
									"mp"=>number_format($proyec, 2, '.', ','),
									"mt"=>number_format($totalm, 2, '.', ','),
									"ma"=>number_format($anul, 2, '.', ',')));
		}
		if($accionT=="ventas_dia"){	
				$sql = "SELECT * from apps_ventas_demo where fecha = '".$dia."'  order by unidades DESC " ;
				$result = mysqli_query($con, $sql);

		$tienda = array(); //creamos un array
		while($resultados2 = mysqli_fetch_array($result)) 
		{ 
			
				$tienda[] = array("marca"=>utf8_decode($resultados2['MARCA']),'razon'=>utf8_decode($resultados2['razon']),'ubicacion'=>utf8_decode($resultados2['ubicacion']),
							'unidades'=>$resultados2['unidades'],'importe_1'=>$resultados2['importe_1'],'importe_2'=>$resultados2['importe_2'],
							'precio_p'=>$resultados2['precio_p']);
				 // echo "<img class='img-rounded' src='".$logo."'><br>";
				 // echo "<img class='img-rounded' src='".$result3['bandera']."'>";						
		}
			header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
			 echo json_encode(array("response" => "ok","table_m" => $tienda ));
		}
		
		if($accionT=="update_c"){
			$cl = mysqli_real_escape_string($con,(strip_tags($_GET['cli'],ENT_QUOTES)));
			$sql = "SELECT e.* FROM  events e WHERE localizador = '".$cl."'" ;
			$result = mysqli_query($con, $sql);
			if(mysqli_num_rows($result)!=0){
				$update = mysqli_query($con,"UPDATE events set status = 2 where localizador = '".$cl."'");
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
		if($accionT=="ventas_mes"){	
				$sql = "SELECT * from apps_ventas_demo  order by unidades DESC " ;
				$result = mysqli_query($con, $sql);

		$tienda = array(); //creamos un array
		while($resultados2 = mysqli_fetch_array($result)) 
		{ 
			
				$tienda[] = array("marca"=>utf8_decode($resultados2['MARCA']),'razon'=>utf8_decode($resultados2['razon']),'ubicacion'=>utf8_decode($resultados2['ubicacion']),
							'unidades'=>$resultados2['unidades'],'importe_1'=>$resultados2['importe_1'],'importe_2'=>$resultados2['importe_2'],
							'precio_p'=>$resultados2['precio_p']);
				 // echo "<img class='img-rounded' src='".$logo."'><br>";
				 // echo "<img class='img-rounded' src='".$result3['bandera']."'>";						
		}
			header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
			 echo json_encode(array("response" => "ok","table_m2" => $tienda ));
		}
		

	}
	
?>

