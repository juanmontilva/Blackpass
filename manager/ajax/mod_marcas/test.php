<?
		include("../../dist/funciones/conectarbd.php");
		$_SESSION['perfi'] = mysql_real_escape_string($_SESSION['perfi']);
		$_SESSION['uid'] = mysql_real_escape_string($_SESSION['uid']);
		$obj = new ConexionMySQL();
		$fecha = date("Y-m-d");
		$hora = date("H:m:s");
		$sql2 = "INSERT INTO apps_test (fecha,hora) VALUES ('".$fecha."','".$hora."') ";
		$obj->ejecutar_sql($sql2);
?>