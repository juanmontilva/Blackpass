<?php
	ini_set('display_errors', false);
	ini_set('display_startup_errors', false);
	include("../../dist/funciones/conexion.php");
	include("../../dist/funciones/cript.php");
	include("../../dist/funciones/funciones.php");
	include("../mod_mail/sendqrmail.php");
	include("../mod_qr/index.php");

	
	/** Include PHPExcel */
	include('../../dist/librerias/PHPExcel/vendor/autoload.php');
	
	if($_GET['accion']=='excel'){
		$objPHPExcel = new PHPExcel();
		$cek = mysqli_query($con, "SELECT c.nombres, c.ssn,d.descripcion, d.qr, d.servicio, d.name from apps_clientes c,apps_servicios_d d 
								WHERE 
								d.cod=c.id_cd
								and d.print = 0");

				if(mysqli_num_rows($cek) != 0){
$objPHPExcel = new PHPExcel();
$line = 1;
// Set document properties
$objPHPExcel->getProperties()->setCreator("ITMEDIA")
							 ->setTitle("Codigos QR")
							 ->setSubject("Blackpass")
							 ->setCategory("QR FILES");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nombre')
            ->setCellValue('B2', 'Identificacion')
            ->setCellValue('C1', '4 DIGITOS TARJETA')
            ->setCellValue('D2', 'FILE');

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0);
$filex = "";
			while( $rows = mysqli_fetch_assoc($cek) ) {
					 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $line, $rows['nombres']);
					 $objPHPExcel->getActiveSheet()->SetCellValue('B' . $line, $rows['ssn']);
					 $objPHPExcel->getActiveSheet()->SetCellValue('C' . $line, $rows['descripcion']);
					 $objPHPExcel->getActiveSheet()->SetCellValue('D' . $line, $rows['name']);
						$line++;
						$filex = $rows['name'];
					
					}
//$rutaAbsoluta = "C:/laragon/www/blackpass/manager/ajax/mod_clientes/files/temp/".$filex;
		
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Lista QR');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="qr.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');

header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

			}
	}
	
	
if($_GET['accion']=='zip'){
$dir = 'files/temp';	
$filex = "";	
$cek = mysqli_query($con, "SELECT c.nombres, c.ssn,d.descripcion, d.qr, d.servicio, d.name from apps_clientes c,apps_servicios_d d 
								WHERE 
								d.cod=c.id_cd
								and d.print = 0");
$zip = new ZipArchive();
// Creamos y abrimos un archivo zip temporal
 $zip->open("qr.zip",ZipArchive::CREATE);
				if(mysqli_num_rows($cek) != 0){
					
					while( $rows = mysqli_fetch_assoc($cek) ) {
						if($rows['name']!="")
						//$filex .= '"'.$dir.$rows['name'].'"'.",";
					   //$zip->addFromString("files/temp/".$rows['name'],"files/temp/".$rows['name']);
$zip->addFromString(basename("files/temp/".$rows['name']), file_get_contents("files/temp/".$rows['name']));
					}
					
				}
	//$filex = substr($filex, 0, -1);	

 $zip->close();
 // Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
 header("Content-type: application/octet-stream");
 header("Content-disposition: attachment; filename=qr.zip");
 // leemos el archivo creado
 readfile('qr.zip');
 // Por último eliminamos el archivo temporal creado
 unlink('qr.zip');//Destruye el archivo temporal
 exit;
	}
		if($_POST['accion']=='add'){
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_POST["t_codigo"],ENT_QUOTES)));//Escanpando caracteres 
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_POST["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	     = mysqli_real_escape_string($con,(strip_tags($_POST["t_dir"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_POST["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$plan		 = mysqli_real_escape_string($con,(strip_tags($_POST["tclie"],ENT_QUOTES)));//Escanpando caracteres 
				$mail			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_mail"],ENT_QUOTES)));
				$lg			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_lg"],ENT_QUOTES)));
				$t_direc			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_direc"],ENT_QUOTES)));
				$t_ssn			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_ssn"],ENT_QUOTES)));
				$t_tc			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_tc"],ENT_QUOTES)));
				$razon			 = mysqli_real_escape_string($con,(strip_tags($_POST["razon"],ENT_QUOTES)));
				//$t_ssn = $encriptar($t_ssn);
				if($fecha_nacimiento=="2021-01-01"){
					$fecha_nacimiento = "01/01/2022";
				}else{
					$fecha_nacimiento = explode("/",$fecha_nacimiento);
				}
				
				$identi = $lg."-".$t_ssn;
				$fecha_nacimiento = $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];
				$cek = mysqli_query($con, "SELECT * FROM apps_clientes WHERE codigo='$codigo' or ssn = '".$identi."' ");
				if(mysqli_num_rows($cek) == 0){
												$insert = mysqli_query($con, "INSERT INTO apps_clientes(codigo, nombres,fecha_nacimiento,
														direccion,pais, telefono,tcliente,estado,
														ssn,plan,mail,status,razon)
														VALUES('$codigo','$nombres','$fecha_nacimiento',
														'".$t_direc."','1','$telefono','".$t_tc."','".$direccion."',
														'".$identi."','$plan','$mail','1','".$razon."')");
					
						
						if($insert){
							$id= mysqli_insert_id($con);
							$cadena = cadena();
							$token = $encriptar($cadena);
							$insert_login = mysqli_query($con,"INSERT INTO apps_clientes_login (user_id,token)
															VALUES ($id,'".$token."')");
							if($insert_login){
								$respuesta = enviarmail2($mail,$nombres,$cadena);
								if($respuesta[0]==1){
									header('Content-type: application/json; charset=utf-8');
									echo json_encode(array("response" => "ok"));
								}
							}
															
							//echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							
						}else{
							//echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "2"));
						}
					 
				}else{
					header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ex"));
				}
			}

			if($_POST['accion']=='update'){
				$idcl			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_cl"],ENT_QUOTES)));//Escanpando caracteres  
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_POST["t_codigo"],ENT_QUOTES)));//Escanpando caracteres 			
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_POST["t_fd"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	     = mysqli_real_escape_string($con,(strip_tags($_POST["t_dir"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_POST["numero"],ENT_QUOTES)));//Escanpando caracteres 
				$plan		 = mysqli_real_escape_string($con,(strip_tags($_POST["tclie"],ENT_QUOTES)));//Escanpando caracteres 
				$mail			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_mail"],ENT_QUOTES)));
				$lg			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_lg"],ENT_QUOTES)));
				$t_direc			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_direc"],ENT_QUOTES)));
				$t_ssn			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_ssn"],ENT_QUOTES)));
				$t_tc			 = mysqli_real_escape_string($con,(strip_tags($_POST["t_tc"],ENT_QUOTES)));
				$razon			 = mysqli_real_escape_string($con,(strip_tags($_POST["razon"],ENT_QUOTES)));
				//$t_ssn = $encriptar($t_ssn);
				$fecha_nacimiento = explode("/",$fecha_nacimiento);
				$fecha_nacimiento = $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];
				
				$identi = $lg."-".$t_ssn;
				if($razon==null ||$razon =='') $razon = 0;
				/*echo "UPDATE apps_clientes SET  nombres='$nombres',  
							fecha_nacimiento='$fecha_nacimiento', direccion='$t_direc', 
							codigo = '$codigo' ,telefono='$telefono',
							mail = '$mail',plan = $plan,ssn= '".$identi."',
							estado = '".$direccion."',tcliente = '$t_tc',razon = '".$razon."'
							WHERE id ='$idcl'";*/
				$update = mysqli_query($con, "UPDATE apps_clientes SET  nombres='$nombres',  
							fecha_nacimiento='$fecha_nacimiento', direccion='$t_direc', 
							codigo = '$codigo' ,telefono='$telefono',
							mail = '$mail',plan = $plan,ssn= '".$identi."',
							estado = '".$direccion."',tcliente = '$t_tc',razon = '".$razon."'
							WHERE id ='$idcl'") or die(mysqli_error());
							
							
							
				if($update){
							//echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ok"));
						}else{
							//echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "2"));
						}

			}
			if($_POST['accion']=='search'){
				$datos1 = array();
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_POST["busqueda"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_clientes WHERE codigo like '%$nik%' or nombres like '%$nik%'");
				if(mysqli_num_rows($cek) != 0){
					while($row = mysqli_fetch_assoc($cek)){
					$datos1[] =	array("nombre" => $row['nombres'],
									"codigo" => $row['codigo'],"tcliente" => $row['puesto'],"idc" => $row['id']);
					}
					
					$request_table_m = $datos1;
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"category" => $request_table_m
					));
					
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'no'
					));
				}
			}
			

			if($_POST['accion'] == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_POST["cli"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_clientes WHERE id='$nik'");
				if(mysqli_num_rows($cek) == 0){
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
						));
				}else{
					$delete = mysqli_query($con, "DELETE FROM apps_clientes WHERE id='$nik'");
					if($delete){
						header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok'
						));
					}else{
						header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
						));
					}
				}
			}
			
			if($_POST['accion'] == 'reser'){
			$nik = mysqli_real_escape_string($con,(strip_tags($_POST["cli"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT e.* FROM apps_clientes c, events e 
									WHERE c.id = e.id_cliente and c.codigo  = '$nik' ");
				if(mysqli_num_rows($cek) != 0){
				$i=1;
				$tabla = "";
				while($row = mysqli_fetch_array($registro))
				{
					$tabla.='{"#":"'.$i.'",   "Fecha":"'.$row['start'].'","Servicio":"'.$row['title'].'","Profesional":"'.$row['title'].'"},';		
					$i++;
				}
				$tabla = substr($tabla,0, strlen($tabla) - 1);
					
					/*while($row = mysqli_fetch_assoc($cek)){
					$datos1[] =	array("idh" => $row['idh'],
									"codigo" => $row['numero']);
					}
					
					$request_table_m = $datos1;
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"habitacion" => $request_table_m
					));*/
					echo '{"data":['.$tabla.']}';
				}	
			}
			
	if($_POST['accion'] == 'asg'){
		$nik = mysqli_real_escape_string($con,(strip_tags($_POST["tmp_id_d"],ENT_QUOTES)));
		//echo "sin".$nik."<br>";
		$nik = str_replace(' ', '+', $nik);
		$nik = $desencriptar($nik);
		
		//echo "des".$nik."<br>";
		$cek = mysqli_query($con, "SELECT c.* FROM apps_clientes c 
								WHERE c.id  = '$nik' ");
		
		if(mysqli_num_rows($cek) != 0){
			$row = mysqli_fetch_array($cek);
			if($row['id_cd']=="" || $row['id_cd']== null){

				$sql = mysqli_query($con,"SELECT * FROM apps_servicios_d 
									WHERE id_marca = '".$row['plan']."' 
									and status = 1 
									ORDER BY idh ASC LIMIT 1");
			
				if(mysqli_num_rows($sql) != 0){
					$row2 = mysqli_fetch_array($sql);
					$update = mysqli_query($con,"UPDATE apps_clientes 
							set id_cd = '".$row2['cod']."' WHERE id = $nik ");
					$update2 = mysqli_query($con,"UPDATE apps_servicios_d 
							set status = 2 where cod = '".$row2['cod']."' ");
					if($update){
						$sql3 = mysqli_query($con,"SELECT * FROM apps_xyz 
									WHERE prod like '%".$row2['descripcion']."%' ");
						
						if(mysqli_num_rows($sql3) == 1){
							$row3 = mysqli_fetch_array($sql3);
							$qrcod = generaQR($row3['prod']);
							$updateQR = mysqli_query($con,"UPDATE apps_servicios_d set name = '".$qrcod."' where cod = '".$row2['cod']."'");
							$respuesta = enviarmail($row['mail'],$row['nombres'],$row['id_cd'],$qrcod);
							if($respuesta[0]==1){
								header('Content-type: application/json; charset=utf-8');			
									echo json_encode(array(
								'response' => 'ok',
								"card" => $row2['descripcion'],
								"name"=> $row['nombres']
								));
							}
						}else{
							header('Content-type: application/json; charset=utf-8');			
									echo json_encode(array(
								'response' => '0'
								));
						}
						
						
					}
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						    'response' => '1'
						));
				}
			}else{
				header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						    'response' => '2'
						));
			}
		}else{
			header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						    'response' => '0'
						));
		}
	}
			
			?>
