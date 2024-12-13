<?php
date_default_timezone_set('America/New_York');
function semana_i(){
 $date = strtotime(date("d"));
$first = strtotime('last Sunday +1 day ');
$last = strtotime('next Saturday +0 day ');

$sini = date('d', $first);
$sfin = date('d', $last);
 return array ($sini,$sfin);
}



function randomColor() {
    $str = '#';
    for($i = 0 ; $i < 6 ; $i++) {
        $randNum = rand(0 , 15);
        switch ($randNum) {
            case 10: $randNum = 'A'; break;
            case 11: $randNum = 'B'; break;
            case 12: $randNum = 'C'; break;
            case 13: $randNum = 'D'; break;
            case 14: $randNum = 'E'; break;
            case 15: $randNum = 'F'; break;
        }
        $str .= $randNum;
    }
    return $str;
}

function semanas(){
$date = strtotime(date("Y-m-d"));
$first = strtotime('last Sunday +1 day');
$last = strtotime('next Saturday +7 day');

$sini = date('Y-m-d', $first);
$sfin = date('Y-m-d', $last);
 return array ($sini,$sfin);
}

function semanas2(){
$date = strtotime(date("Y-m-d"));
$first = strtotime('last Sunday +1 day');
$last = strtotime('next Saturday +0 day');

$sini = date('Y-m-d', $first);
$sfin = date('Y-m-d', $last);
 return array ($sini,$sfin);
}
 
function hourIsBetween($from, $to, $input) {
    $dateFrom = DateTime::createFromFormat('!H:i', $from);
    $dateTo = DateTime::createFromFormat('!H:i', $to);
    $dateInput = DateTime::createFromFormat('!H:i', $input);
    if ($dateFrom > $dateTo) $dateTo->modify('+1 day');
    return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
}
function verifica_rango($date_inicio, $date_fin, $date_nueva) {
   $date_inicio = strtotime($date_inicio);
   $date_fin = strtotime($date_fin);
   $date_nueva = strtotime($date_nueva);
   if (($date_nueva >= $date_inicio) && ($date_nueva <= $date_fin)){
	   return 1; 
   }else  if ($date_nueva > $date_fin){
	   return 30;
   }else if($date_nueva < $date_inicio){
	    return 0;
   }
	 
  
}
function saber_dia($fecha){
$dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
$dia = $dias[(date('N', strtotime($fecha))) - 1];
return $dia;	
}
function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function compara_fecha($x){
$fecha_actual = strtotime(date("d-m-Y",time()));
$x = strtotime(date("d-m-Y H:i:s",time()));
$fecha_entrada = strtotime($x);

if($fecha_actual = $fecha_entrada){
        return 1;
}else{
        return 0;
}
}

function intervaloHora($hora_inicio, $hora_fin, $intervalo = 60) {

    $hora_inicio = new DateTime( $hora_inicio );
    $hora_fin    = new DateTime( $hora_fin );
    $hora_fin->modify('+1 second'); // Añadimos 1 segundo para que nos muestre $hora_fin

    // Si la hora de inicio es superior a la hora fin
    // añadimos un día más a la hora fin
    if ($hora_inicio > $hora_fin) {

        $hora_fin->modify('+1 day');
    }

    // Establecemos el intervalo en minutos        
    $intervalo = new DateInterval('PT'.$intervalo.'M');

    // Sacamos los periodos entre las horas
    $periodo   = new DatePeriod($hora_inicio, $intervalo, $hora_fin);        

    foreach( $periodo as $hora ) {

        // Guardamos las horas intervalos 
        $horas[] =  $hora->format('H:i');
    }

    return $horas;
}

function getRandomCode(){
    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ.*$-_#";
    $su = strlen($an) - 1;
    return substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1);
}
function cadena(){
	$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
return substr(str_shuffle($permitted_chars), 0, 8);
}
function cadena2(){
	$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
return substr(str_shuffle($permitted_chars), 1, 64);
}
function tarjetaQR(){
	$permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
// Output: video-g6swmAP8X5VG4jCi.mp4
return substr(str_shuffle($permitted_chars), 1, 16);
}
function saludo(){
	$saludo = "";
	$date = date ("H");
    if ($date < 12){
		$saludo = "☀️  *Buenos dias!*";
		return $saludo;
	} 
    else if ($date < 18){
		$saludo = "☀️  *Buenas tardes!*";
		return $saludo;
	} 
    else{
		$saludo = "🌙 *Buenas noches!*";
		return $saludo;
	}
}
function saludo2(){
	$saludo = "";
	$date = date ("H");
    if ($date < 12){
		$saludo = "☀️  *Good Morning!*";
		return $saludo;
	} 
    else if ($date < 18){
		$saludo = "☀️  *Good Afternoon!*";
		return $saludo;
	} 
    else{
		$saludo = "🌙 *Good Night!*";
		return $saludo;
	}
}
function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

class diasEntre {

private $fecha_desde, $fecha_hasta;

function __construct ( $d, $h )
{
$this->fecha_desde = $d;
$this->fecha_hasta = $h;

} // fin constructor

/*

Es buena práctica usar constructor para garantizar que el objeto se instancie consistentemente y en caso de error detener la ejecución a tiempo antes de perder el control con consecuencias imprevisibles para la humanidad. Podríamos llamar a métodos para validar en este caso las fechas que esperamos etc

*/

function completarFechas ()
{
$a = array ();

$dif = $this->diferenciaDias ();

/*

$dif almacena un entero que contiene el nro de días que hay entre las fechas dadas. Vamos al método: diferenciaDias()en esta misma clase, nos vemos allá.

*/

$siguiente_dia = $this->siguienteDia ( $this->fecha_desde );

for ($i = 1; $i <= $dif - 1; $i++)

{
array_push ($a, $siguiente_dia);
$siguiente_dia = $this->siguienteDia ( $siguiente_dia );
}

return ($a);

} // fin getFechasBase



private function diferenciaDias ()
{

/*

Importante: setear (en mi caso) America/Argentina/Buenos_Aires en date.timezone del php.ini o arrojará horror! Bueno aquí estamos, observen como obtenemos el número de días que existe entre las dos fechas dadas con date_diff más sobre date_diff:http://phpenquerandi.blogspot.com/2011/12/datediff-para-obtener-facilmente-el.html

*/

$dia_desde = new DateTime( $this->fecha_desde );
$dia_hasta = new DateTime( $this->fecha_hasta );
$intervalo = $dia_desde->diff( $dia_hasta );
$dias = $intervalo->format('%a');

return ( $dias );

} //m fin diferenciaDias



private function siguienteDia ( $d )
{

$date = new DateTime( $d );
$date->add (new DateInterval('P1D'));

/*

podrías sumar una semana P1W o dos meses P2M etc usando el formato para Date http://www.php.net/manual/en/function.date.php

*/

return ( $date->format('Y-m-d') );

} // fin siguienteDia


} // fin clase dias_entre


?>