			<?php
			include("../../dist/funciones/conexion.php");
			if($_GET['accion']=='add'){
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_GET["t_codigo"],ENT_QUOTES)));//Escanpando caracteres 
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = $_GET["t_fd"];//Escanpando caracteres 
				$texto	     = mysqli_real_escape_string($con,(strip_tags($_GET["t_orig"],ENT_QUOTES)));//Escanpando caracteres 
				$file		 = mysqli_real_escape_string($con,(strip_tags($_GET["t_file"],ENT_QUOTES)));//Escanpando caracteres 
				//$estado			 = mysqli_real_escape_string($con,(strip_tags($_GET["tclie"],ENT_QUOTES)));//Escanpando caracteres 
				$sexo	     = mysqli_real_escape_string($con,(strip_tags($_GET["t_sex"],ENT_QUOTES)));
				$time	     = mysqli_real_escape_string($con,(strip_tags($_GET["t_time"],ENT_QUOTES)));
				$fe = explode(" ",$fecha_nacimiento);
				$fe2 = explode("/",$fe[0]);
				$fecha = $fe2[2]."-".$fe2[1]."-".$fe2[0]." ".$fe[1].":00";
	
				$cek = mysqli_query($con, "SELECT * FROM apps_bd_market WHERE codigo='$codigo'");
				if(mysqli_num_rows($cek) == 0){
					foreach ($_REQUEST['t_serv'] as $estado){
						$insert = mysqli_query($con, "INSERT INTO apps_bd_market 
															(id_bd,codigo, titulo,fecha,label,url,status,sexo,timeline)
															VALUES('".$estado."','$codigo','$nombres',  '$fecha', '$texto', '$file', '1','".$sexo."','".$time."')") or die(mysqli_error());
						$id= mysqli_insert_id($con);
						if(isset($_REQUEST['t_serv'])){
							foreach ($_REQUEST['tclie'] as $servicios){
						$insert2 = mysqli_query($con, "INSERT INTO apps_bd_market_d 
															(idc,idh)
															VALUES('".$id."','$servicios')") or die(mysqli_error());

						}
						}
						
					}  
						if($insert){
							//echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
							header('Content-type: application/json; charset=utf-8');
							echo json_encode(array("response" => "ok"));
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
			
			if($_GET['accion']=='search'){
				$datos1 = array();
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["busqueda"],ENT_QUOTES)));
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
			

			if($_GET['accion'] == 'delete'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nik = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_bd_market WHERE id='$nik'");
				if(mysqli_num_rows($cek) == 0){
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => '2'
						));
				}else{
					$delete = mysqli_query($con, "DELETE FROM apps_bd_market WHERE id ='$nik'");
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
			if($_GET['accion'] == 'send_c'){
				$telefonos = array();
				$codigo = mysqli_real_escape_string($con,(strip_tags($_GET["idc"],ENT_QUOTES)));
				$cek = mysqli_query($con, "SELECT * FROM apps_bd_market WHERE id ='$codigo'");
				if(mysqli_num_rows($cek) != 0){
					$row = mysqli_fetch_assoc($cek);
					$sql = mysqli_query($con,"select * from apps_clientes where lenguaje = '".$row['id_bd']."'");
					if(mysqli_num_rows($sql)!=0){
						
						$total = mysqli_num_rows($sql);
						while($row2 = mysqli_fetch_assoc($sql)){
							$telefonos[] = array("num"=>$row2['telefono']);
						}
						$formato = explode(".",$row['url']);
						header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"num" => $telefonos,
						"url"=>$row['url'],
						"label"=>$row['label'],
						"total"=>$total,
						"formato"=>$formato[1]
						));
						
					}else{
						header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'no'
						));
					}
				}else{
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'no'
						));
				}
			}
			if($_GET['accion'] == 'reser'){
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["cli"],ENT_QUOTES)));
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
			
			?>
