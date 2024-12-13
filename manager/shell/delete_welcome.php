<?php
include("dist/funciones/funciones.php");
include('dist/funciones/api_ws.php');
include("dist/funciones/conexion.php");


$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
$cadena = substr(str_shuffle($permitted_chars), 0, 36);			

$date= date('Y-m-d'); 
$newDate = strtotime ( '+24 hour' , strtotime ($date) ) ; 
$newDate = date ( 'Y-m-d' , $newDate); 
$fecha_f = $newDate;
$fecha_i = $date;
	$sql = mysqli_query($con,"delete from apps_welcome WHERE fecha < '".$fecha_f."' ");
			$chatId = "573132318907@c.us";
			$mensaje = "Se borroron los welconme menores a $fecha_f";
			sendMessage($chatId,$mensaje);
			
?>