<?php
define("DB_SERVER", "localhost");
define("DB_USER", "24hopenv"); // webmaster_vzla
define("DB_PASS", "GkP6bxloBYcSDMk3"); // w=@,Fb*D
define("DB_NAME", "bd_reservas_v1"); 
require_once('../../dist/funciones/funciones.php');
$conexion = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); 
			//echo $mmarca;
$fecha_p = date("d/m/Y");
$sql = "select * from apps_horarios";
$query_u = mysqli_query($conexion,$sql);
$resultado = mysqli_fetch_assoc($query_u);
$from = '08:00';
$to = '20:00';
$dia_i = strtotime('+22 hour', strtotime( date("Y-m-d h:i:s")  ));
$dia_i = date("Y-m-d h:i:s",$dia_i);

$dia_f = strtotime('+23 hour', strtotime($dia_i));
$dia_f = date("Y-m-d h:i:s",$dia_f);
$mmarca= "SELECT * FROM events WHERE 
						start BETWEEN  '".$dia_i."' and '".$dia_f."'  ";
//echo $mmarca;						
$query_u = mysqli_query($conexion,$mmarca);
$dateTest = new DateTime('midnight');
 $resultado = mysqli_fetch_assoc($query_u);
	 $h = explode(" ",$resultado['start']);
	 $h = explode(":",$h[1]);
	 $hora = $h[0].":".$h[1];
	 for ($a = 1; $a <= 21; $a++) {
		$input = $dateTest->format('H:i');
		if($hora!=$input && hourIsBetween($from, $to, $input) ==1 ){
			echo $hora." =  $from <= $input <= $to -> " . (hourIsBetween($from, $to, $input) ? 'Yes' : 'No') . "<br>";
		}
		$ver = (hourIsBetween($from, $to, $input) ? 'Yes' : 'No');
		//echo "$from <= $input <= $to -> " . (hourIsBetween($from, $to, $input) ? 'Yes' : 'No') . "<br>";
		$dateTest->modify("+1 hour");
		//echo hourIsBetween($from, $to, $input);
	}
	echo "vuelta <br>";
 


?>

