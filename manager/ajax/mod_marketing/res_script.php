<?php
header('Access-Control-Allow-Origin: *');
include("conexion.php");
$nik = mysqli_real_escape_string($con,(strip_tags($_GET["ctl"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT e.start as Fecha, e.title as Servicio, 
									e.title as Profesional
									FROM empleados c, events e 
									WHERE c.id = e.id_cliente and c.codigo  = '".$nik."' ");
				if(mysqli_num_rows($cek) != 0){
				$i=1;
				$tabla = "";

					
					while($row = mysqli_fetch_assoc($cek)){
						 $array[] = $row;
					/*$datos1[] =	array("#" => $i,
									"Fecha" => $row['start'],
									"Servicio"=>$row['title'],
									"Profesional"=>$row['title']);*/
					}
					$dataset = array(
					"echo" => 1,
					"totalrecords" => count($array),
					"totaldisplayrecords" => count($array),
					"data" => $array
				);

				echo json_encode($dataset);
					
				}	
?>