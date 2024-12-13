<?php 
session_start();
		if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
		{
			//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
			//pantalla de login, enviando un codigo de error
			header ("Location: ../../index.php?error=ER001");
			exit;
		}else{
		include("../../dist/funciones/conectarbd.php");
		/*$consulta = $obj->consultar("select `apps_cargo`.`cargo`  from `apps_user`, `apps_cargo`  
		where `apps_cargo`.`id_cargo` = `apps_user`.`id_cargo` and `apps_user`.`id_user` = '".$_SESSION['uid']."'");*/
		if($_SESSION['perfi']==1){
			$where = "1=1";
			$from = "";
			$requerir = "";
		}else{
			$where= "apps_user_acceso.`id_menu` = apps_menu.id_menu and apps_user_acceso.id_user = '".$_SESSION['uid']."' ";
			$from = ",apps_user_acceso";
			$requerir = ", apps_user_acceso.id_acceso";
		}
		$accionT = mysqli_real_escape_string($con,(strip_tags($_GET["accion"],ENT_QUOTES)));
		//Registro de Localidad//
		if($accionT=="add"){
		$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));
		$estado = mysqli_real_escape_string($con,(strip_tags($_GET["id_est"],ENT_QUOTES)));
		$pais = mysqli_real_escape_string($con,(strip_tags($_GET["id_pais"],ENT_QUOTES)));
		$numero = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));	
		$mmarca= mysqli_query($con,"select * from apps_localidades where localidad like '%".$localidad."%' ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		$resultado	= mysqli_fetch_assoc($mmarca) ;
		if($resultado = mysqli_num_rows($mmarca)==0){
			$sqli = mysqli_query($con,"INSERT INTO apps_localidades (numero,localidad,status,id_pais,id_provincia) VALUES ('".$numero."','".$localidad."','1','".$pais."','".$estado."')");
			//$id=mysql_insert_id();
			$id = mysqli_insert_id($con);
			for($j=1;$j<=7;$j++){
				$hi = mysqli_real_escape_string($con,(strip_tags($_GET["i_".$j],ENT_QUOTES)));
				$hf = mysqli_real_escape_string($con,(strip_tags($_GET["f_".$j],ENT_QUOTES)));
				switch($j){
					case 1 : $dia = "Lunes";
					break;
					case 2 : $dia = "Martes";
					break;
					case 3 : $dia = "Miercoles";
					break;
					case 4 : $dia = "Jueves";
					break;
					case 5 : $dia = "Viernes";
					break;
					case 6 : $dia = "Sabado";
					break;
					case 7 : $dia = "Domingo";
					break;
				}
				$sql_h = mysqli_query($con,"INSERT INTO apps_horarios (id_s,dia,hora_i,hora_f) VALUES
										('$id','$dia','$hi','$hf') ");

			}
			echo json_encode(array('local'=>'ok','sql'=>$sqli));
		}else{
			echo json_encode(array('local'=>'existe'));
		}
		}
		if($accionT=="update_l"){
		$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["nombre_l"],ENT_QUOTES)));
		$estado = mysqli_real_escape_string($con,(strip_tags($_GET["id_est"],ENT_QUOTES)));
		$pais = mysqli_real_escape_string($con,(strip_tags($_GET["id_pais"],ENT_QUOTES)));
		$numero = mysqli_real_escape_string($con,(strip_tags($_GET["numero"],ENT_QUOTES)));	
		$idl = mysqli_real_escape_string($con,(strip_tags($_GET["idmarca"],ENT_QUOTES)));	
		$mmarca= mysqli_query($con,"select * from apps_localidades where id_loc = '".$idl."' ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		$resultado	= mysqli_fetch_assoc($mmarca) ;
		if($resultado = mysqli_num_rows($mmarca)!=0){
			$sqli = mysqli_query($con,"UPDATE apps_localidades SET numero = '".$numero."',
			localidad = '".$localidad."',id_pais = '".$pais."',id_provincia = '".$estado."' 
			where id_loc = '$idl' ");
			//$id=mysql_insert_id();
			$id = mysqli_insert_id($con);
			foreach($_GET['dias'] as $dia_s){
				switch($dia_s){
					case 1 : $dia = "Lunes";
					break;
					case 2 : $dia = "Martes";
					break;
					case 3 : $dia = "Miercoles";
					break;
					case 4 : $dia = "Jueves";
					break;
					case 5 : $dia = "Viernes";
					break;
					case 6 : $dia = "Sabado";
					break;
					case 7 : $dia = "Domingo";
					break;
				}
				$hi = mysqli_real_escape_string($con,(strip_tags($_GET["i_".$dia_s],ENT_QUOTES)));
				$hf = mysqli_real_escape_string($con,(strip_tags($_GET["f_".$dia_s],ENT_QUOTES)));

				//echo $dia_s."</br>";
				$sqlh = mysqli_query($con,"select * from apps_horarios where id_s = '$idl' and dia = '$dia'");
				//echo "select * from apps_horarios where ids = '$idl' ";
				if(mysqli_num_rows($sqlh)!=0){
					$row= mysqli_fetch_assoc($sqlh);
						if($row['dia']==$dia){
							//echo "dia= ".$dia."<br>";
							$sql_u = mysqli_query($con,"UPDATE apps_horarios set 
									hora_i = '$hi', hora_f = '$hf' where id_s = '$idl' and dia = '$dia'");
						}
				}else{
					$sql_h = mysqli_query($con,"INSERT INTO apps_horarios (id_s,dia,hora_i,hora_f) VALUES
										('$idl','$dia','$hi','$hf') ");
				}
				
			}
			$sqlh = mysqli_query($con,"select * from apps_horarios where id_s = '$idl' ");
			while($row2=mysqli_fetch_assoc($sqlh)){
				$nex = "";
				$existe = 0;
				//echo "t = ".$row2['dia']." <br>";
				foreach($_GET['dias'] as $dia_s){
					switch($dia_s){
						case 1 : $dia = "Lunes";
						break;
						case 2 : $dia = "Martes";
						break;
						case 3 : $dia = "Miercoles";
						break;
						case 4 : $dia = "Jueves";
						break;
						case 5 : $dia = "Viernes";
						break;
						case 6 : $dia = "Sabado";
						break;
						case 7 : $dia = "Domingo";
						break;
					}
					if($dia==$row2['dia']){
						$existe = 1;
					}
				}
				if($existe==0){
					//echo $row2['dia']."<br>";
					$delete = mysqli_query($con,"DELETE from apps_horarios where id_s = '$idl' and dia = '".$row2['dia']."'");
				}
				
			}
			echo json_encode(array('local'=>'ok','sql'=>$sqli));
		}else{
			echo json_encode(array('local'=>'existe'));
		}
		}
		//Validad Localidd//
		if($accionT=="consultar"){
			$horario = array();
			$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["marca_id_d"],ENT_QUOTES)));
			$mmarca= mysqli_query($con,"select * from apps_localidades where id_loc = '$localidad' ");
			if(mysqli_num_rows($mmarca)!=0){
				$resultado	= mysqli_fetch_assoc($mmarca) ;
				$sql = mysqli_query($con,"select * from apps_horarios where id_s = '$localidad'");
				while($row = mysqli_fetch_assoc($sql)){
					$dian = 0;
					switch($row['dia']){
						case "Lunes" : $dian = 1;
						break;
						case "Martes" : $dian = 2;
						break;
						case "Miercoles" : $dian = 3;
						break;
						case "Jueves" : $dian = 4;
						break;
						case "Viernes" : $dian = 5;
						break;
						case "Sabado" : $dian = 6;
						break;
						case "Domingo" : $dian = 7;
						break;
					}
					$horario[] = array("dia"=>$row['dia'],
									  "hi"=>$row['hora_i'],
									  "hf"=>$row['hora_f'],
									  "ndia"=>$dian); 
				}
				echo json_encode(array('response'=>'ok',
								'local'=>$resultado['localidad'],
								'num'=>$resultado['numero'],
								'pais'=>$resultado['id_pais'],
								"hora"=>$horario,
								'prov'=>$resultado['id_provincia']));
			}else{
			echo json_encode(array('marcac'=>$sql));
			}	
		}if($accionT=="delete"){
			$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["cc"],ENT_QUOTES)));
			$mmarca= mysqli_query($con,"select * from apps_localidades where id_loc = '$localidad' ");
			if($resultado = mysqli_num_rows($mmarca)!=0){
				$resultado	= mysqli_fetch_assoc($mmarca) ;
				$sql_delete = mysqli_query($con,"DELETE FROM apps_localidades where id_loc = '$localidad'");
				//$obj->ejecutar_sql($sql_delete);
				$mmarca2= mysqli_query($con,"select * from apps_localidades where id_loc = '$localidad' ");
				if($resultado2 = mysqli_num_rows($mmarca2)==0){
				echo json_encode(array('response'=>'ok','nombre'=>$resultado['localidad'],
									"pais"=>$resultado['id_pais']));
				}else{
					echo json_encode(array('response'=>'no_borrar'));
				}
			}else{
			echo json_encode(array('response'=>''));
			}	
		}
		//Edicion de Localidad//
		if($accionT=="edit"){
			$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["marca_id_d"],ENT_QUOTES)));
			$mmarca= mysqli_query($con,"select * from apps_marcas where id_marca = '$marca' ");
			if($resultado = mysqli_num_rows($mmarca)!=0){
				$resultado	= mysqli_fetch_assoc($mmarca) ;
				echo json_encode(array('marcae'=>'ok','nmarca'=>$resultado['marca'],'url'=>$resultado['url']));
			}else{
			echo json_encode(array('marcae'=>''));
			}	
		}
		//Actualizacion de Localidad//
		if($accionT=="update"){
		$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["id_marc"],ENT_QUOTES)));
		$marcan = mysqli_real_escape_string($con,(strip_tags($_GET["marca_name"],ENT_QUOTES)));
		$url = mysqli_real_escape_string($con,(strip_tags($_GET["site"],ENT_QUOTES)));
		$mmarca= mysqli_query($con,"select * from apps_marcas where id_marca = '$marca'  ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		$resultado	= mysqli_fetch_assoc($mmarca) ;
		if($resultado = mysqli_num_rows($mmarca)!=0){
			$sqli = mysqli_query($con,"UPDATE apps_marcas SET marca = '".$marcan."', url = '".$url."' WHERE id_marca = '$marca'  ");
			//$obj->ejecutar_sql($sqli);
			echo json_encode(array('marca'=>'update'));
		}else{	
			echo json_encode(array('marca'=>'no_update'));
		}
		}
	//Creacion de Logo//
		if($accionT=="logo"){
		$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["id_marc"],ENT_QUOTES)));
		$logo = mysqli_real_escape_string($con,(strip_tags($_GET["logo"],ENT_QUOTES)));
		$mmarca= mysqli_query($con,"select * from apps_marcas where id_marca = '$marca'  ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		if($resultado = mysqli_num_rows($mmarca)!=0){	
		$resultado	= mysql_fetch_assoc($mmarca) ;
			$sqli = mysqli_query($con,"UPDATE apps_marcas SET logo = '".$logo ."' WHERE id_marca = '$marca'  ");
			echo json_encode(array('logo'=>'ok','mmarca'=>$resultado['marca']));

		}else{
			echo json_encode(array('logo'=>''));
		}
		}
		//actualizar estatus pais
		if($accionT=="upais"){
		$st = mysqli_real_escape_string($con,(strip_tags($_GET["stat"],ENT_QUOTES)));
		$pais = mysqli_real_escape_string($con,(strip_tags($_GET["pais"],ENT_QUOTES)));
		$mmarca= mysqli_query($con,"select * from apps_paises where cod = '$pais'  ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		
		if($resultado = mysqli_num_rows($mmarca)!=0){
			$resultado	= mysqli_fetch_assoc($mmarca) ;
			
			$localidades= mysqli_query($con,"SELECT COUNT(*) as cantidad 
										FROM `apps_localidades` l, apps_paises p, apps_provincias pv 
										WHERE p.cod = l.id_pais and l.id_provincia = pv.codprovincia 
										and p.cod = '".$pais."' and l.status = 1 order by pv.nombreprovincia,localidad asc ");
			$resultado4	= mysqli_fetch_assoc($localidades);
				if($resultado4['cantidad']<=0){
					$sqli = mysqli_query($con,"UPDATE apps_paises SET status = '".$st."'WHERE cod = '$pais'  ");
					echo json_encode(array('pais'=>'update','nombre'=>$resultado['pais']));
				}else if($resultado4['cantidad']>0){
					echo json_encode(array('pais'=>'con_loca','nombre'=>$resultado['pais'],'cantidad'=>$resultado4['cantidad']));
				}

		}else{	
			echo json_encode(array('pais'=>'no_update'));
		}
		}
		//actualizar estatus Localidad o CC
		if($accionT=="upcc"){
		//$localidad = mysqli_real_escape_string($con,(strip_tags($_GET["id_marc"],ENT_QUOTES)));
		$st = mysqli_real_escape_string($con,(strip_tags($_GET["stat"],ENT_QUOTES)));
		$cc = mysqli_real_escape_string($con,(strip_tags($_GET["cc"],ENT_QUOTES)));
		$mmarca= mysqli_query($con,"select * from apps_localidades where id_loc = '$cc'  ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		//echo "select * from apps_localidades where id_loc = '$cc'  ";
		if(mysqli_num_rows($mmarca)!=0){
			$resultado	= mysqli_fetch_assoc($mmarca) ;
			
			$localidades= mysqli_query($con,"SELECT COUNT(*) as cantidad 
										FROM `apps_localidades` l, apps_paises p, apps_provincias pv 
										WHERE p.cod = l.id_pais and l.id_provincia = pv.codprovincia 
										and p.cod = '".$cc."' and l.status = 1 order by pv.nombreprovincia,localidad asc ");
			//$resultado4	= $obj->obtener_fila($localidades,0);
				//if($resultado4['cantidad']<=0){
					$sqli = mysqli_query($con,"UPDATE apps_localidades SET status = '".$st."'WHERE id_loc = '$cc'  ");
					echo json_encode(array('cc'=>'update','nombre'=>$resultado['localidad'],'pais'=>$resultado['id_pais']));
				//}else if($resultado4['cantidad']>0){
					//echo json_encode(array('pais'=>'con_loca','nombre'=>$resultado['pais'],'cantidad'=>$resultado4['cantidad']));
			//	}

		}else{	
			echo json_encode(array('cc'=>'no_update','pais'=>$resultado['id_pais']));
		}
		}
	}
?>

