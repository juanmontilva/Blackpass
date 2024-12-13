<link href="css/style.css" rel="stylesheet">
<style type="text/css">
.card_m{
border: 1px solid #ffffff;
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
background:#000000;
width:380px;
height:280px;
color:#ffffff;
}
.card_m1{
	padding-top:0px;
	font-size:16px;
	font-weight: bold;
	margin-right:40px;
}
.card_m2{
	margin-top:5px;
	font-size:16px;
	font-weight: bold;
	margin-left:40px;
	box-shadow: 0 1px 2px #272727;
}
.card_m3{
	margin-top:-25px;
	font-size:20px;
	font-weight: bold;
	margin-left:40px;
	box-shadow: 0 1px 2px #272727;
}
</style>
<?php
error_reporting(0);
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/cript.php");

$acc = mysqli_real_escape_string($con,(strip_tags($_GET["accion"],ENT_QUOTES)));
$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));

//$nik = str_replace('+', '', $nik);
$nik = str_replace(' ', '+', $nik);
//echo "desencrip2=".$nik."<br>";
$nik= $desencriptar($nik);
//echo "despues: ".$nik;
$cek = mysqli_query($con, "SELECT c.*,d.descripcion from apps_clientes c,apps_servicios_d d 
								WHERE 
								c.id = $nik and
								d.cod=c.id_cd");

if(mysqli_num_rows($cek) != 0){
$row = mysqli_fetch_assoc($cek);
$less = substr($row['descripcion'], -4);
?>
<div style="height:300px;">
<div class="card_m">
<div class="card_m1"><img width="30%" src="img/logo.png"></div>
<div class="card_m3">XXXX-XXXX-XXXX-<?php echo $less;?></div>
<div class="card_m2">
<?php echo $row['nombres']?>
</div>
</div>
</div>
<?php
}else{
	$nik = "'".$encriptar($nik)."'";
?>
<div id="btn-cd" class="alert alert-warning">NO TIENE TARJETA ASIGNADA
<p><button onclick ="asignar_card(<?php echo $nik?>);"  title="Asignar Tarjeta" class="btn btn-primary "><span class="glyphicon glyphicon-edit"></span></button></p>
</div>
<div id="loader_card" style="margin-top:80px;display:none;margin-left:40%"><img src='img/devoops_getdata.gif'></div>
<div style="height:300px;">
<div id="tarjeta" style ="display:none;" class="card_m">
<div class="card_m1"><img width="30%" src="img/logo.png"></div>
<div class="card_m3">XXXX-XXXX-XXXX-<span id="ncard"></span></div>
<div class="card_m2">
<span id="nname"></span>
</div>
</div>
</div>
<?php 
}
?>
