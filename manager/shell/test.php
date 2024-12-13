<?php
include("dist/funciones/funciones.php");
include('dist/funciones/api_ws.php');
include("dist/funciones/conexion.php");



			$chatId = "573132318907@c.us";
			$mensaje = "Prueba cron";
			sendMessage($chatId,$mensaje);
			
?>