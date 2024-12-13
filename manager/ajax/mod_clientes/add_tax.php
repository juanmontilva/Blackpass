<?php
require_once('../../dist/funciones/funciones.php');
require_once('../../dist/funciones/conexion.php');
include("../../dist/funciones/cript.php");
if (isset($_FILES["file"]))
{
    $file = $_FILES["file"];
    $nombre = $file["name"];
    $tipo = $file["type"];
    $ruta_provisional = $file["tmp_name"];
    $size = $file["size"];
    $tipo2 = explode(".",  $nombre);
    $tipo3 =  $tipo2[1];
    $carpeta = "files/";
	$borrar_file =  $nombre;
    $nombre= str_replace(' ','_',$nombre); 
    if ($tipo !='application/pdf')
    {
      echo "Error, el archivo no es PDF"; 
    }
    else if ($size > 1024*1024*15)
    {
      echo "Error, el tamaño máximo permitido es  15MB";
    }
   
    else
    {
		
		$codigo	= mysqli_real_escape_string($con,(strip_tags($_POST["client"],ENT_QUOTES)));
		$year	= mysqli_real_escape_string($con,(strip_tags($_POST["year"],ENT_QUOTES)));
        $src = $carpeta.cadena2().$nombre;
        		$cek = mysqli_query($con, "SELECT * FROM apps_clientes WHERE id ='$codigo'");

				if(mysqli_num_rows($cek) != 0){
					$sql = mysqli_query($con,"SELECT * FROM apps_cl_tax where idc = $codigo and  year = $year");
						if(mysqli_num_rows($sql) == 0){
							$insert = mysqli_query($con,"INSERT INTO apps_cl_tax
													(idc,year,file) VALUES ($codigo,$year,'".$src."')");
							if($sql){
							echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							move_uploaded_file($ruta_provisional, $src);
							}else{
								echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
							}
						
						}else{
							$update = mysqli_query($con,"UPDATE apps_cl_tax set file = '".$src."'
													WHERE idc = $codigo and  year = $year");
							if($update){
							echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							move_uploaded_file($ruta_provisional, $src);
							
							echo json_encode(array("response" => "ok"));
							}else{
								echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
							}
						
						}	
				}else{
					header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ex"));
				}

		//echo "<input type='hidden' id='excelfile' name='excelfile' value='$nombre'>";
    }

}
?>