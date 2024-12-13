<!DOCTYPE html>
<?php
session_start();

//Validar que el usuario este logueado y exista un UID
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../../index.php?error=ER001");
	//exit;
}else{
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
$fecha_i = date("Y-m-d");
$fecha_f = $fecha_i." 22:00:00";
$fecha_i = $fecha_i." 08:00:00";
$where = "";
if($_SESSION['perfi'] ==2){
	$where = " and e.id_pro = '".$_SESSION['uid']."'";
}else{
	$where = " and 1 = 1";
}

$sql = mysqli_query($con,"SELECT e.*, s.servicio, p.nombres as prof, c.nombres FROM apps_emple_s p, events e, apps_servicios_d s,apps_clientes c 
							WHERE 
						    start BETWEEN  '".$fecha_i."' and '".$fecha_f."'
							and s.idh = e.idh
							and c.id = e.id_cliente
							and p.id = e.id_pro ".$where." 
							ORDER BY start ASC");
							
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link href="css/jquery.timespace.light.css" rel="stylesheet">
	<style>
		#timeline, #timelineClock {
			box-sizing: border-box;
			padding: 10px;
			width: 100%;
		}
	</style>
		<script src="js/jquery.timespace.js"></script>
</head>
<body>
	<h4 class="page-header">Monitor Citas</h4>
	<div id="timelineClock"></div>
	<!--<h1>Linea de Tiempo Reservas</h1>
	<div id="timeline"></div>-->
<script>
	$(function () {
		
		$('#timelineClock').timespace({
			
			// Set the time suffix function for displaying as '12 A.M.'
			timeSuffixFunction: s => ' ' + s[0].toUpperCase() + '.' + s[1].toUpperCase() + '.',
			selectedEvent: -1,
			startTime: 8,
			endTime: 22,
			data: {
				headings: [
					{start: 8, end: 12, title: 'Mañana'},
					{start: 12, end: 18, title: 'Tarde'},
					{start: 18, end: 22, title: 'Noche'},
				],
				events: [
				<?php while($row= mysqli_fetch_assoc($sql)){
					$hora_i = explode(" ",$row['start']);
					$hora_f = explode(" ",$row['end']);
					$hora_i2 = explode(":",$hora_i[1]);
					$hora_f2 = explode(":",$hora_f[1]);
					$hi = $hf = "";
					$serv = $row['servicio'];
					$client = $row['nombres'];
					$prof = $row['prof'];
					if($hora_i2=="30"){
						$hi = $hora_i2[0].".5";
					}else{
						$hi = $hora_i2[0];
					}
					if($hora_f2=="30"){
						$hf = $hora_f2[0].".5";
					}else{
						$hf = $hora_f2[0];
					}
									
					?>
					{start: <?php echo $hi;?>, end: <?php echo $hf;?>, title: <?php echo "'".$row['localizador']."'";?>, description: $("<p><a href='#' class='col-xs-4 col-sm-2 btn-warning text-center'><?php echo $row['localizador'];?></a><br>"
							+"<p><strong>Servicio:<?php echo $serv?></strong></p>"
							+"<p><strong>Cliente:<?php echo $client?></strong></p>"
							+"<p><strong>Profesional:<?php echo $prof?></strong></p>")},
				<?php 
					}
				?>
				]
			},
			
		});

		
	});
</script>
</body>
</html>
<?php 
}
?>