<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=utf-8');
// URL for request GET /messages
include("dist/funciones/funciones.php");
include("dist/funciones/api_ws.php");
include("dist/funciones/conexion.php");
include("dist/funciones/cript.php");
date_default_timezone_set('America/Caracas');
/*$APIurl = 'https://api.chat-api.com/instance221818/';
$token = 'hzsdahpwy9wy4la7';
$instanceId = '215059';
$url = 'https://api.chat-api.com/instance'.$instanceId.'/messages?token='.$token.'&last=200';
*/
$fecha = date("Y-m-d");
$chatId = "";
$i=0;
$j=0;
$accion = $_POST['xyz'];
	 if($accion=="vcat"){
		 $datos1 = array();
		 $datos2 = array();
		    $pais = mysqli_real_escape_string($con,(strip_tags($_POST["pais"],ENT_QUOTES)));
			$uid = mysqli_real_escape_string($con,(strip_tags($_POST["uid"],ENT_QUOTES)));
			if($pais==1){
				$pais=68;
			}
			$sql_1 = mysqli_query($con,"SELECT * from apps_marcas m, apps_marcas_x_pais p
			where p.id_marca = m.id_marca and p.id_pais = '$pais'  ");
			if(mysqli_num_rows($sql_1)!=0){
				while($row = mysqli_fetch_assoc($sql_1)){
					$datos1[] =	array("cat" => $row['marca'],
									"codigo" => $row['id_marca']);
					}
					
					$sqlc = mysqli_query($con,"SELECT c.* from apps_clientes_profile c, apps_clientes p
			where p.id = c.idu and p.telefono = '$uid'  ");
			
			while($row2 = mysqli_fetch_assoc($sqlc)){
					$datos2[] =	array("cat2" => $row2['idm']);
					}
					
					$request_table_m = $datos1;
					$request_cat = $datos2;
					$sql_1 = mysqli_query($con,"SELECT * from apps_clientes where telefono = '$uid'  ");
					$row_i = mysqli_fetch_assoc($sql_1);
					header('Content-type: application/json; charset=utf-8');			
						echo json_encode(array(
						'response' => 'ok',
						"cat" => $request_table_m,
						"cat2" => $request_cat,
						"name" => $row_i['nombres'],
						"phone" => $row_i['telefono'],
					));
			}else{
				echo json_encode(array("response" => "2"));
			}
	}else if($accion=="act_cat"){
		$uid = mysqli_real_escape_string($con,(strip_tags($_POST["uid"],ENT_QUOTES)));
		$sqlh2 = mysqli_query($con,"select * from apps_clientes 
			            where telefono = '$uid' ");
			if(mysqli_num_rows($sqlh2)!=0){
				$row2= mysqli_fetch_assoc($sqlh2);
				foreach($_POST['cat'] as $cat){
				$sqlh = mysqli_query($con,"select * from apps_clientes_profile 
							where idm = '$cat' and idu= '".$row2['id']."'");
					if(mysqli_num_rows($sqlh)==0){
						$sql_h = mysqli_query($con,"INSERT INTO apps_clientes_profile 
											(idu,idm) VALUES
											('".$row2['id']."','$cat') ");
					}	
			}
			$sqlh3 = mysqli_query($con,"select * from apps_clientes_profile 
							where  idu= '".$row2['id']."'");
			while($row3=mysqli_fetch_assoc($sqlh3)){
				$nex = "";
				$existe = 0;
				//echo "t = ".$row2['dia']." <br>";
				foreach($_POST['cat'] as $cat2){
					
					if($cat2==$row3['idm']){
						$existe = 1;
					}
				}
				if($existe==0){
					//echo $row2['dia']."<br>";
					$delete = mysqli_query($con,"DELETE from apps_clientes_profile
					where idm = '$cat' and idu= '".$row2['id']."'");
				}
				
			}
			echo json_encode(array('response'=>'ok'));
		}else{
			echo json_encode(array('response'=>'2'));
		}
		
	}

?>