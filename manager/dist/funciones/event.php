<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_hotel = "localhost";
$database_hotel = "gymnospro2015";
$username_hotel = "root";
$password_hotel = "1t0l4B";
$hotel = mysql_pconnect($hostname_hotel, $username_hotel, $password_hotel) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php 
if (is_file("includes/funciones.php")){
 include("includes/funciones.php");
}
else
 {
	 include("funciones2.php");
	}
?>