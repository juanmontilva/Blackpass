<?php
/* FUNCION PARA ENVIAR FILE*/
function sendFile($chatId,$file,$caption){
   
      if(isset($file)){
         $data = array(
         'phone'=>$chatId,
         'body'=>'http://192.169.156.225/24hopen/ajax/mod_marketing/files/'.$file,
         'filename'=>'http://192.169.156.225/24hopen/ajax/mod_marketing/files/'.$file,
         'caption'=>$caption
        );
      $enviar = sendRequest('sendFile',$data);
	  return $enviar;
	 }
}
/* FUNCION PARA PREPARAR EL MENSAJE*/
function sendRequest($method,$data){
$u = 'https://api.chat-api.com/instance350141/';
	$t = 'd058euuknbi3rty8';
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
         $data = array('phone'=>$chatId,'body'=>$text);
         $enviar = sendRequest('message',$data);
		 return $enviar;
}
//FUNCION LOCALIZACION
function geo($chatId,$lat1,$lng,$location){
        $data = array(
        'lat'=>$lat1,
        'lng'=>$lng,
        'address'=>$location,
        'chatId'=>$chatId
                        );
      $respu = sendRequest('sendLocation',$data);
  }
?>