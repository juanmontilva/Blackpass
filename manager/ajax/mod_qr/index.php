<?php    

   function generaQR($x){
	   //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = "../mod_clientes/files/temp/";
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 10; 
        // user data
        $filename = $PNG_TEMP_DIR.'HCF'.md5($x.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($x, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
 
    //display generated file
    $qr = basename($filename);  
    return $qr;
        
    // benchmark
    QRtools::timeBenchmark();   
   }
    
     

    