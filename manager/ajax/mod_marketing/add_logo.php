<?php
if (isset($_FILES["file"]))
{
    $file = $_FILES["file"];
    $nombre = $file["name"];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
	//echo  $ruta_provisional."<br>";
    $size = $file["size"];
    $dimensiones = getimagesize($ruta_provisional);
    $width = $dimensiones[0];
    $height = $dimensiones[1];
    $carpeta = "files/";
    
    if ($tipo != 'image/jpg' && $tipo != 'video/mp4' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
    {
      echo "Error, el archivo no es una imagen"; 
    }
    else if ($size > 10240*10240)
    {
      echo "Error, el tamaño máximo permitido es un 1MB";
    }
    else if (($width >2048 || $height > 2048) && $tipo != 'video/mp4')
    {
        echo "Error la anchura y la altura maxima permitida es 500px";
    }
    else if(($width < 60 || $height < 60) && $tipo != 'video/mp4')
    {
        echo "Error la anchura y la altura mínima permitida es 60px";
    }
    else
    {
        $src = $carpeta.$nombre;
        move_uploaded_file($ruta_provisional, $src);
		if($tipo != 'video/mp4'){
		echo "<img width='240' height='380' src='ajax/mod_marketing/$src'>";
		echo "<input type='hidden' id='logofile' name='logofile' value='$nombre'>";
		}else{
			echo "<video width='270' height='380' src='ajax/mod_marketing/$src'  controls></video>";
			echo "<input type='hidden' id='logofile' name='logofile' value='$nombre'>";
		}

    }
}
