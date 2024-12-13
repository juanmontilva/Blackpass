<?php
header('Access-Control-Allow-Origin: *');
// URL for request GET /messages
include("dist/funciones/conexion.php");
date_default_timezone_set('America/Los_Angeles');
$APIurl = 'https://api.chat-api.com/instance221818/';
$token = 'hzsdahpwy9wy4la7';
$instanceId = '215059';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
$fecha = date("Y-m-d");
$chatId = "";
$i=0;
$j=0;

	
			$sql_ = mysqli_query($con, "SELECT * FROM apps_clientes where estado = 1
						ORDER BY RAND() LIMIT 250 ");
			$banner = "miercoles-cabello.png"; // bu
        if(mysqli_num_rows($sql_) != 0){
			$k=mysqli_num_rows($sql_);
           while($row2 = mysqli_fetch_assoc($sql_)){
			   $i++;
			   			$body = "*ALERTA COMERCIAL*\n\n".
						"_Hola, *".$row2['nombres']."*_ \n\n".
						 "🛍️ *ESTILO Y TENDENCIAS SALON & SPA* \n\n".
						 "🏷️ 25% de descuento a las 10 primeras personas en llamar antes de las 6:00 de la tarde. \n".
						 "🔖 _Codigo promoción_: *PROMO231* (mencionelo a nuestro personal). \n\n".
						 "📍 _LOCAL 1, Doral 3317,Florida_ \n".
						 "☎️ _Ph 786 xxx xx xx_ \n";

			$primer = substr($row2['telefono'], 0, 1);
           //$chatId = $row2['telefono'];
		   if($primer!=4){
				 $chatId = $row2['telefono'];
			}else{
				 $chatId = "58".$row2['telefono'];
			}
			$respuesta =  sendFile($chatId,$banner,($body));
		  //$respuesta = sendMessage($chatId,utf8_encode($body));	
           //$obj =$respuesta;
		   $obj = json_decode($respuesta);
			echo $obj->{'message'}."<br>"; // 12345
			if($obj->{'sent'}==1){
			  
			echo "Procesado: $i de $k: ".$row2['telefono']."<br>";
			}else{
				$j++;
			echo "No Procesado: $j =  ".$row2['telefono']."<br>";	
			
			}
		   flush();
            ob_flush();
           //sleep(1);
           }
        }
function sendFile($chatId,$file,$caption){

   

      if(isset($file)){
         $data = array(
         'phone'=>$chatId,
         'body'=>'http://192.169.156.225/bot/file/'.$file,
         'filename'=>'http://192.169.156.225/bot/file/'.$file,
         'caption'=>$caption
        );
      $enviar = sendRequest('sendFile',$data);
	  return $enviar;
	 }
}
/* FUNCION PARA PREPARAR EL MENSAJE*/
function sendRequest($method,$data){
	$u = 'https://api.chat-api.com/instance215059/';
	$t = 'hzsdahpwy9wy4la7';
   $url = $u.$method.'?token='.$t;
   if(is_array($data)){ $data = json_encode($data);}
     $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $data
                  ]]);
    $response = file_get_contents($url,false,$options);
    file_put_contents('requests.log',$response.PHP_EOL,FILE_APPEND);
	return $response;


}
/* FUNCION PARA ENVIAR EL MENSAJE WS*/
function sendMessage($chatId, $text){
         $data = array('chatId'=>$chatId,'body'=>$text);
         $enviar = sendRequest('message',$data);
		 return $enviar;
}
function saludo(){
	$saludo = "";
	$date = date ("H");
    if ($date < 12){
		$saludo = "*Buenos dias!*";
		return $saludo;
	} 
    else if ($date < 18){
		$saludo = "*Buenas tardes!*";
		return $saludo;
	} 
    else{
		$saludo = "*Buenas noches!*";
		return $saludo;
	}
}/* FUNCION PARA MOSTRAR LAS ADS*/

?>