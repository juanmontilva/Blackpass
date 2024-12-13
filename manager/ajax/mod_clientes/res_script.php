<?php

include("../../dist/funciones/conexion.php");
include("../../dist/funciones/cript.php");
$acc = mysqli_real_escape_string($con,(strip_tags($_GET["accion"],ENT_QUOTES)));
if($acc==2){
	
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["ctl"],ENT_QUOTES)));
	//$per = mysqli_real_escape_string($con,(strip_tags($_GET["per"],ENT_QUOTES)));
	$per = 2021;
	$nik = str_replace(' ', '+', $nik);
	$nik= $desencriptar($nik);
	$mes = date('m');
	$mesv = $mes+1;
	$mesv2 = $mes-1;
	//echo "mes: ".$mes." - ".$mesv;
				$cek = mysqli_query($con, "SELECT fecha as Fecha,orden as Orden,
								monto as Monto,
								CASE 
								WHEN status = 0  THEN 'CANCELADA'
								WHEN status = 1  THEN 'SIN VALIDAR'
								WHEN status = 2  THEN 'VALIDADA'
								END AS Estatus
								FROM apps_pagos  
								WHERE idc = $nik
								ORDER BY fecha DESC");
				if(mysqli_num_rows($cek) != 0){
				$i=1;
				$tabla = "";
					while($row = mysqli_fetch_assoc($cek)){
						 $array[] = $row;
					/*$datos1[] =	array("#" => $i,
									"Fecha" => $row['start'],
									"Servicio"=>$row['title'],
									"Profesional"=>$row['title']);*/
					}
					$dataset = array(
					"echo" => 1,
					"totalrecords" => count($array),
					"totaldisplayrecords" => count($array),
					"data" => $array
				);

				echo json_encode($dataset);
					
				}else{
					echo json_encode(0);
				}
}else if($acc==3){
	$acc = $desencriptar($acc);
	$nik = mysqli_real_escape_string($con,(strip_tags($_GET["ctl"],ENT_QUOTES)));
	//$per = mysqli_real_escape_string($con,(strip_tags($_GET["per"],ENT_QUOTES)));
	$per = 2021;
	$nik= $desencriptar($nik);
	$mes = date('m');
	$mesv = $mes+1;
	$mesv2 = $mes-1;
	//echo "mes: ".$mes." - ".$mesv;
				$cek = mysqli_query($con, "SELECT p.orden as Orden,p.monto as Monto,
										 l.localidad as Comercio,p.fecha as Fecha
										FROM apps_pay_o p, apps_clientes c, 
										apps_localidades l, apps_marcas m
										WHERE p.idc = '".$nik."' 
										and c.id = p.idc
										and l.id_loc = p.idl
										and c.plan = m.id_marca
										ORDER BY hora DESC");
				if(mysqli_num_rows($cek) != 0){
				$i=1;
				$tabla = "";
					while($row = mysqli_fetch_assoc($cek)){
						 $array[] = $row;
					/*$datos1[] =	array("#" => $i,
									"Fecha" => $row['start'],
									"Servicio"=>$row['title'],
									"Profesional"=>$row['title']);*/
					}
					$dataset = array(
					"echo" => 1,
					"totalrecords" => count($array),
					"totaldisplayrecords" => count($array),
					"data" => $array
				);

				echo json_encode($dataset);
					
				}else{
					echo json_encode(0);
				}
}
?>	