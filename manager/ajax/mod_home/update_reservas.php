<?php
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
$fecha_i = date("Y-m-d H:i:s");
$fecha_f = date("Y-m-d H:i:s");

$date = new DateTime();
$date->modify('+1 hours');
$x = $date->format('Y-m-d H:i:s');
if($_GET['accion']=="update"){
	//SELECT * FROM `events` WHERE `end` <= '2021-02-03 19:46:00' and status = 0
	$sql = mysqli_query($con,"UPDATE events set status = 4 where end <= '".$x."' and status = 0") ;
//echo "UPDATE events set status = 4 where end <= '".$x."'";	
	if($sql){
		header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
			 echo json_encode(array("response" => "ok"));
	}else{
		header('Content-type: application/json; charset=utf-8');
			//echo json_encode($menu);
			 echo json_encode(array("response" => "no"));
	}
}

//$sql = mysqli_query($con,"    SELECT * FROM `events` WHERE `end` <= '2021-02-03 19:46:00' and status = 0") ;
							

?>