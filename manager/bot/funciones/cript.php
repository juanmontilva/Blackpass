<?php
//Configuración del algoritmo de encriptación

//Debes cambiar esta cadena, debe ser larga y unica
//nadie mas debe conocerla
$clave  = 'C9fBxl1EWtYTL1M8jfstw5hGZIijm4BHkxVyUO7SR83WnTLg91QMl0wDtF6pA2dcNzJEKuYrbqseCPoXf.C9fBxl1EWtYTL1M8jfstw5hGZIijm4BHkxVyUO7S,avR83WnTLg91QMl0wDtF6pA2dcNzJEKuYrbqseCPoXfC9fBxl1EWtYTL1M8jfstw5hGZIijm4BHkxVyUO7Sg91QMl0wDtF6pA2dcNzJEKuYrbqseCPoXf.C9fBxl1EWtYTL1M8jfstw5hGZIijm4BHkxVyUO7S,avR83WnTLg91QMl0wDtF6pA2dcNzJEKuYrbqseCPoXfC';

//Metodo de encriptación
$method = 'aes-256-cbc';

// Puedes generar una diferente usando la funcion $getIV()
$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

 /*
 Encripta el contenido de la variable, enviada como parametro.
  */
 $encriptar = function ($valor) use ($method, $clave, $iv) {
     return openssl_encrypt ($valor, $method, $clave, false, $iv);
 };

 /*
 Desencripta el texto recibido
 */
 $desencriptar = function ($valor) use ($method, $clave, $iv) {
     $encrypted_data = base64_decode($valor);
     return openssl_decrypt($valor, $method, $clave, false, $iv);
 };

 /*
 Genera un valor para IV
 */
 $getIV = function () use ($method) {
     return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
 };