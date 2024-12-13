<?php
include("../../dist/funciones/funciones.php");
include("../../dist/funciones/conectarbd.php");
include("../../dist/funciones/cript.php");
//error_reporting(E_ALL);
session_start();
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../../index.php?error=ER001");
	//exit;
}else{

$_SESSION['perfi'] = $_SESSION['perfi'];
$_SESSION['uid'] = $_SESSION['uid'];
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
		$accionT = $_GET['accion'];
		if($accionT=="add_s"){
		$marca = mysqli_real_escape_string($con,(strip_tags($_GET['idmarca'],ENT_QUOTES)));
		$numero = mysqli_real_escape_string($con,(strip_tags($_GET['marca_name'],ENT_QUOTES)));
		$precio =  mysqli_real_escape_string($con,(strip_tags($_GET['tarif'],ENT_QUOTES)));;
		$descrip = mysqli_real_escape_string($con,(strip_tags($_GET['descrip'],ENT_QUOTES)));
		//$tiempo = mysqli_real_escape_string($con,(strip_tags($_GET['t_tiempo'],ENT_QUOTES)));		
	
		$mmarca= "SELECT  m.identificador, h.*  from apps_servicios_d h , apps_marcas m
				  WHERE m.id_marca  = '$marca' 
				  and m.id_marca = h.id_marca" ;
		$query_u = mysqli_query($con,$mmarca);
		//$resultado	= $obj->obtener_fila($mmarca,0) ;
		//$presenm = $_GET['presen'];
		$tiempo = 0;
			if(mysqli_num_rows($query_u)!=0){
			$resultado	= mysqli_fetch_array($query_u);
			$fk = mysqli_num_rows($query_u)+1;
			for ($i=0;$i<$numero;$i++) {
				$codi = $resultado['identificador'].$fk;
				$color = randomColor();
				$servi = cadena();
				$servi2 = $encriptar($servi);
				$qr = tarjetaQR();
				$qr2 = $encriptar($qr);
				$less = substr($servi, -5);
				$sqli = "INSERT INTO apps_servicios_d 
				(servicio,precio,descripcion,tiempo,id_marca,cod,color,status,qr)
				VALUES ('".$servi2."','0', '0','365',
				'$marca','".$codi."','".$less."','1','".$qr2."') ";
				//echo $sqli;
				
				mysqli_query($con, $sqli);
				$sql3 = "INSERT INTO apps_xyz (cod,prod)
										VALUES ('".$servi."','".$qr."') ";
				mysqli_query($con, $sql3);
				$id= mysqli_insert_id($con);
				$mensaje = $resultado['id_marca'];
				}
				echo json_encode(array('marca'=>'ok','msg'=>$mensaje));
			}
			else{	
			echo json_encode(array('marca'=>'no_update'));
			}
		}
		if($accionT=="add"){
		$marca =  mysqli_real_escape_string($con,(strip_tags($_GET['marca_name'],ENT_QUOTES)));
		//$logo = $_GET['t_logo'];
		//$url = $_GET['site'];

		$local_id = $_GET['site'];
		$url = "";
		
		$identity = $_GET['identi'];
		$numero = $_GET['numero'];
		$tipo_p = $_GET['tipo_p'];
		
		$thab = "0";
		$mmarca= "select * from apps_marcas where marca like '".$marca."%'" ;
		$query_u = mysqli_query($con,$mmarca);
		//$resultado	= $obj->obtener_fila($mmarca,0) ;
		//$presenm = $_GET['presen'];
		//echo $mmarca;
		
		if(mysqli_num_rows($query_u)==0){
			$identifi= "select * from apps_marcas where identificador = '".$identity."' ";
			$query_u2 = mysqli_query($con,$identifi);
	
			if(mysqli_num_rows($query_u2)==0){
				$sqli = "INSERT INTO apps_marcas (marca,status,logo,identificador,thab,tipo_p) 
				VALUES ('".$marca."','1','".$url."','".$identity."','".$thab."','".$tipo_p."')";
				 if(mysqli_query($con, $sqli)){
					$id= mysqli_insert_id($con);
				
				$j=1;
				for ($i=0;$i<$numero;$i++) {
						$hbi = $identity.$j;
						$servi = cadena();
						$servi2 = $encriptar($servi);
						$qr = tarjetaQR();
						$color = randomColor();
						$qr2 = $encriptar($qr);
						$less = substr($servi, -5);
						$less2 = substr($qr, -4);
						$sql2 = "INSERT INTO apps_servicios_d (id_marca,servicio,status,cod,color,qr,descripcion)
									VALUES ('$id','".$servi2."','1','".$hbi."','".$less."','".$qr2."','".$less2."') ";
						//echo $sql2."</br>";
						mysqli_query($con, $sql2);
						$j++;
						$sql3 = "INSERT INTO apps_xyz (cod,prod)
									VALUES ('".$servi."','".$qr."') ";
						mysqli_query($con, $sql3);
				}
				foreach ($_REQUEST['site'] as $option_value)
				{
					$sql3 = "INSERT INTO apps_marcas_x_pais (id_marca,id_pais,status)
							VALUES ('$id','".$option_value."','1') ";
						//echo $sql2."</br>";
					mysqli_query($con, $sql3);
				}
				}
				echo json_encode(array('marca'=>'ok'));
			}else{
				echo json_encode(array('identi'=>'yes'));
			}

		}else{
		
			echo json_encode(array('marca'=>'existe'));
		}
		}
		
		
				if($accionT=="update"){
		$marca = mysqli_real_escape_string($con,(strip_tags($_GET['idmarca'],ENT_QUOTES)));
		$marcan = mysqli_real_escape_string($con,(strip_tags($_GET['marca_name'],ENT_QUOTES)));
		$identity = $_GET['identi'];
		$thab = 0;
		$mmarca= "SELECT count(h.id_marca) as cantidad, m.* FROM apps_marcas m, apps_servicios_d h 
				  WHERE m.id_marca = h.id_marca and m.id_marca  = '$marca' " ;
		$query_u = mysqli_query($con,$mmarca);
		//$resultado	= $obj->obtener_fila($mmarca,0) ;
		//$presenm = $_GET['presen'];
		//echo $mmarca;
			if(mysqli_num_rows($query_u)!=0){
			$identifi= "select * from apps_marcas where identificador = '".$identity."' and id_marca <> '$marca' ";
			$query_u2 = mysqli_query($con,$identifi);
		//echo $identifi;	
			$resultado3	= mysqli_fetch_array($query_u2) ;
			if(mysqli_num_rows($query_u2)==0){
			$sqli = "UPDATE apps_marcas SET identificador = '".$identity."', 
			marca = '".$marcan."',
			thab = '".$thab."'  WHERE id_marca = '$marca'  ";
			//echo $sqli;
			mysqli_query($con, $sqli);
			$id= mysqli_insert_id($con);
			
			
			$numero = $_GET['numero'];
			$resultado	= mysqli_fetch_array($query_u) ;
			
			if($numero>$resultado['cantidad']){
				
				for ($i=$resultado['cantidad'];$i<$numero;$i++) {
						$j=$i+1;
						$hbi = $identity.$j;
						$servi = cadena();
						$servi2 = $encriptar($servi);
						$qr = tarjetaQR();
						$color = randomColor();
						$qr2 = $encriptar($qr);
						$less = substr($servi, -5);
						$less2 = substr($qr, -4);
						$sql2 = "INSERT INTO apps_servicios_d (id_marca,servicio,status,cod,color,qr,descripcion)
									VALUES ('$marca','".$servi2."','1','".$hbi."','".$less."','".$qr2."','".$less2."') ";
						//echo $sql2."</br>";
						mysqli_query($con, $sql2);
						$j++;
						$sql3 = "INSERT INTO apps_xyz (cod,prod)
									VALUES ('".$servi."','".$qr."') ";
						mysqli_query($con, $sql3);
				}
				$mensaje = 0;		
			}else{
				$mensaje = "1";
			}
			foreach ($_REQUEST['site'] as $option_value)
				{
					$identifi= "select * from apps_marcas_x_pais 
					where id_marca = '$marca' and id_pais = '$option_value' ";
					$query_u2 = mysqli_query($con,$identifi);
					if(mysqli_num_rows($query_u2)==0){
							$sql3 = "INSERT INTO apps_marcas_x_pais (id_marca,id_pais,status)
									VALUES ('$marca','".$option_value."','1') ";
								//echo $sql2."</br>";
							mysqli_query($con, $sql3);
					}
				}
			$sqls =  "select * from apps_marcas_x_pais 
					where id_marca = '$marca' ";
			$query_u3 = mysqli_query($con,$sqls);
			$existe = $noe = "";
					while($row2 = mysqli_fetch_assoc($query_u3)){
						$existe = 0;
						foreach ($_REQUEST['site'] as $option_value)
						{
							if($option_value == $row2['id_pais']){
								//echo " - select = ".$row2['id_pais']."<br>";
								$existe = 1;
							}
							else if($option_value != $row2['id_pais']){
								
								$noe .= $row2['id_pais']."<br>"; 
								$delete = "delete from apps_marcas_x_pais 
								where id_marca = '$marca' and id_pais = '".$row2['id_pais']."' ";
								//mysqli_query($con, $delete);
								
							}
						}
						if($existe==0){
							$delete = "delete from apps_marcas_x_pais 
								where id_marca = '$marca' and id_pais = '".$row2['id_pais']."' ";
							//echo $delete;
							mysqli_query($con, $delete);
						}
						
					}
						//echo $existe."<br>".$noe;
				echo json_encode(array('marca'=>'update','msg'=>$mensaje));
			}else{
				echo json_encode(array('identi'=>'yes'));
			}
			
				
			}
			else{	
			echo json_encode(array('marca'=>'no_update'));
			}
		}
		
		if($accionT=="edit"){

			$marca = $_GET['marca_id_d'];
			$mmarca= "SELECT count(h.id_marca) as cantidad, m.* FROM apps_marcas m, apps_servicios_d h 
						WHERE m.id_marca = h.id_marca and m.id_marca = '$marca' ";
			$query_u = mysqli_query($con,$mmarca);
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u);
				
				$sql_l = "SELECT * FROM apps_marcas_x_pais WHERE id_marca = '".$resultado['id_marca']."' ";
				//echo $sql_l;
				$locales = array();
				$query_l = mysqli_query($con,$sql_l);
				if(mysqli_num_rows($query_l)!=0){
					while($row = mysqli_fetch_assoc($query_l)){
						$locales[] = array("loc"=>$row['id_pais']);
					}
				}
				echo json_encode(array('marcae'=>'ok','nmarca'=>$resultado['marca'],
						'iden'=>$resultado['identificador'],
						 'cant'=>$resultado['cantidad'],
						 'r3'=>$resultado['thab'],
						 'local'=>$locales));
			}else{
			echo json_encode(array('marcae'=>''));
			}	
		}
		if($accionT=="edit_ser"){

			$marca = $_GET['marca_id_d'];
			$mmarca= "SELECT dh.*, h.marca,h.identificador FROM apps_servicios_d dh, apps_marcas h WHERE 
						h.id_marca = dh.id_marca and dh.idh = '$marca' ";
			$query_u = mysqli_query($con,$mmarca);
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u);
				echo json_encode(array('marcae'=>'ok','nmarca'=>$resultado['servicio'],
						'precio'=>$resultado['precio'],'iden'=>$resultado['cod'],
						 'marca'=>$resultado['marca'],
						 'ids'=>$resultado['idh'],
						 'desc'=>$resultado['descripcion']));
			}else{
			echo json_encode(array('marcae'=>''));
			}	
		}
		
		
		if($accionT=="consultar"){
			$marca = $_GET['marca_id_d'];
			$sql = "";
			$mmarca= "select * from apps_marcas where id_marca = '$marca' ";
			$query_u = mysqli_query($con,$mmarca);
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u);
				echo json_encode(array('marcac'=>'ok','nmarca'=>$resultado['marca'],'momento'=>$resultado['status']));
			}else{
			echo json_encode(array('marcac'=>$sql));
			}	
		}
		if($accionT=="consultar_s"){
			$marca = $_GET['marca_id_d'];
			$mmarca= "select * from apps_servicios_d where idh = '$marca' ";
			$query_u = mysqli_query($con,$mmarca);
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u);
				echo json_encode(array('marcac'=>'ok','tar'=>$resultado['color'],'nmarca'=>$resultado['servicio'],'momento'=>$resultado['status']));
			}else{
			echo json_encode(array('marcac'=>$sql));
			}	
		}
		if($accionT=="delete"){
			$marca = $_GET['marca_id_d'];
			$mmarca= "select * from apps_marcas where id_marca = '$marca' ";
			$query_u = mysqli_query($con,$mmarca);
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u);
				$sqlmp= "select * from apps_servicios_d where id_marca = '$marca' ";
				//echo $sqlmp;	
				$query_u2 = mysqli_query($con,$sqlmp);
				if(mysqli_num_rows($query_u2)==0){
					//$sql_delete = "UPDATE FROM apps_marcas set status = 0 where id_marca = '$marca'";
					$sql_delete = "DELETE FROM apps_marcas where id_marca = '$marca'";
					mysqli_query($con, $sql_delete);

					$mmarca2= "select * from apps_marcas where id_marca = '$marca'";
					$query_u3 = mysqli_query($con,$mmarca2);
					if(mysqli_num_rows($query_u2)==0){
					echo json_encode(array('marcad'=>'ok','nmarca'=>$resultado['marca']));
					}else{
						echo json_encode(array('marcad'=>'no_borrar'));
					}
				}else{
					echo json_encode(array('marcad'=>'no_borrar','nmarca'=>$resultado['marca']));
				}
			}else{
			echo json_encode(array('marcac'=>''));
			}	
		}
			if($accionT=="delete_s"){
			$marca = $_GET['marca_id_d'];
			$mmarca= "select * from apps_servicios_d where idh = '$marca' ";
			$query_u = mysqli_query($con,$mmarca);
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u);
					$sql_delete = "delete from apps_servicios_d  where idh = '$marca'";
					mysqli_query($con, $sql_delete);

					$mmarca2= "select * from apps_servicios_d where idh = '$marca'  ";
					$query_u3 = mysqli_query($con,$mmarca2);
					if(mysqli_num_rows($query_u3)==0){
					echo json_encode(array('marcad'=>'ok','msg'=>$resultado['id_marca'],'nmarca'=>$resultado['servicio']));
					}else{
						echo json_encode(array('marcad'=>'no_borrar'));
					}
			}else{
				echo json_encode(array('marcac'=>''));
			}	
		}

		if($accionT=="update_sr"){
		$marca = mysqli_real_escape_string($con,(strip_tags($_GET['idmarca'],ENT_QUOTES)));
		$marcan = mysqli_real_escape_string($con,(strip_tags($_GET['marca_name'],ENT_QUOTES)));
		$precio =  mysqli_real_escape_string($con,(strip_tags($_GET['tarif'],ENT_QUOTES)));;
		$descrip = mysqli_real_escape_string($con,(strip_tags($_GET['descrip'],ENT_QUOTES)));
		//$tiempo = mysqli_real_escape_string($con,(strip_tags($_GET['t_tiempo'],ENT_QUOTES)));		
		$mmarca= "SELECT *  from apps_servicios_d h 
				  WHERE h.idh  = '$marca' " ;
		$query_u = mysqli_query($con,$mmarca);
		//$resultado	= $obj->obtener_fila($mmarca,0) ;
		//$presenm = $_GET['presen'];
		//echo $mmarca;
		$tiempo = 0;
			if(mysqli_num_rows($query_u)!=0){
			$resultado	= mysqli_fetch_array($query_u);
			$sqli = "UPDATE apps_servicios_d SET 
			servicio = '".$marcan."', precio = '".$precio."',
			descripcion = '".$descrip."', tiempo = '".$tiempo."' WHERE idh = '$marca'  ";
			//echo $sqli;
			mysqli_query($con, $sqli);
			$id= mysqli_insert_id($con);
			$mensaje = $resultado['id_marca'];
				echo json_encode(array('response'=>'update','msg'=>$mensaje));
			}
			else{	
			echo json_encode(array('marca'=>'no_update'));
			}
		}
		if($accionT=="update_sr_d"){
		$marca = mysqli_real_escape_string($con,(strip_tags($_GET['marca_id_d'],ENT_QUOTES)));			
		$actual = mysqli_real_escape_string($con,(strip_tags($_GET['actual'],ENT_QUOTES)));
		$mmarca= "SELECT *  from apps_servicios_d h 
				  WHERE h.idh  = '$marca' " ;
		$query_u = mysqli_query($con,$mmarca);
		//$resultado	= $obj->obtener_fila($mmarca,0) ;
		//$presenm = $_GET['presen'];
		//echo $mmarca;
			if(mysqli_num_rows($query_u)!=0){
			$resultado	= mysqli_fetch_array($query_u);
			$sqli = "UPDATE apps_servicios_d SET 
			status = '".$actual."' WHERE idh = '$marca'  ";
			//echo $sqli;
			mysqli_query($con, $sqli);
			$id= mysqli_insert_id($con);
			$mensaje = $resultado['id_marca'];
				echo json_encode(array('servicio'=>'update','msg'=>$mensaje));
			}
			else{	
			echo json_encode(array('servicio'=>'no_update'));
			}
		}
		if($accionT=="update_s"){
		$marca = $_GET['marca_id_d'];
		$status = $_GET['actual'];
		//$url = $_GET['site'];
		$mmarca= "select * from apps_marcas where id_marca = '$marca'  ";
		$query_u = mysqli_query($con,$mmarca);

			if(mysqli_num_rows($query_u)!=0){	
				$sqlmp= "select * from apps_marcas_x_pais mp, apps_tiendas t 
										where t.id_mp = mp.id_mp and mp.id_marca = '$marca' ";
				$query_u2 = mysqli_query($con,$sqlmp);
				if(mysqli_num_rows($query_u2)==0){
					$sqli = "UPDATE apps_marcas SET status = '".$status."' WHERE id_marca = '$marca'  ";
					mysqli_query($con, $sqli);
					echo json_encode(array('marcad'=>'update'));
				}else{
					echo json_encode(array('marcad'=>'no_update'));
				}
		}else{		
			echo json_encode(array('marcad'=>'no_update'));
		}
		}
		if($accionT=="logo"){
		$marca = $_GET['id_marc'];
		$logo = $_GET['logo'];
		$mmarca= "select * from apps_marcas where id_marca = '$marca'  ";
		$query_u = mysqli_query($con,$mmarca);
		if(mysqli_num_rows($query_u)!=0){	
		$resultado = mysqli_fetch_array($query_u);
			$sqli = "UPDATE apps_marcas SET logo = '".$logo ."' WHERE id_marca = '$marca'  ";
				if(mysqli_query($con, $sqli))
				$id= mysqli_insert_id($con);
			echo json_encode(array('logo'=>'ok','mmarca'=>$resultado['marca']));

		}else{
			echo json_encode(array('logo'=>''));
		}
		}
		
		if($accionT=="delete_m_p"){
			$marca = $_GET['marca_id_d'];
			$idpais = $_GET['pais_id_d'];
			
			$mmarca= "SELECT *,p.pais from apps_localidades l, apps_marcas_x_pais mp, 
						apps_paises p WHERE l.id_loc = mp.id_pais and mp.id_pais = '$idpais' 
						and mp.id_marca = '$marca' and p.cod = l.id_pais ";
			$query_u = mysqli_query($con,$mmarca);
			
			if(mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u) ;
				echo json_encode(array('marcae'=>'ok','nmarca'=>$resultado['localidad'],'pais'=>$resultado['pais']));
			}else{
			echo json_encode(array('marcae'=>'no'));
			}	
		}
		if($accionT=="delete_m_p_ok"){
			$marca = mysqli_real_escape_string($con,(strip_tags($_GET['marca_id_d'],ENT_QUOTES)));
			$idpais = mysqli_real_escape_string($con,(strip_tags($_GET['pais_id_d'],ENT_QUOTES)));
			$st = mysqli_real_escape_string($con,(strip_tags($_GET['statu'],ENT_QUOTES)));
			
			$mmarca= "SELECT l.*,m.marca,mp.* from apps_localidades l, apps_marcas_x_pais mp,apps_marcas m
			WHERE l.id_loc = mp.id_pais and mp.id_pais = '$idpais' 
			and mp.id_marca = '$marca'
			and m.id_marca = mp.id_marca";
			$query_u = mysqli_query($con,$mmarca);
			if($resultado = mysqli_num_rows($query_u)!=0){
				$resultado	= mysqli_fetch_array($query_u) ;
				    $mtienda= "select * from apps_emple_s where id_local = '".$resultado['id_loc']."' ";
					$query_t = mysqli_query($con,$mtienda);
					if($resu = mysqli_num_rows($query_t)==0){
					$sql2 = "UPDATE apps_marcas_x_pais set status = '$st' where id_marca = $marca and id_pais = $idpais ";
					//echo $sql2;
					mysqli_query($con, $sql2);
					$versta= "SELECT status from apps_marcas_x_pais where id_marca = $marca and id_pais = $idpais  ";
						$query_tv = mysqli_query($con,$versta);
						if($resultado2 = mysqli_num_rows($query_tv)!=0){
							$resultado2	= mysqli_fetch_assoc($query_tv) ;
							//if($resultado2['status']==0){
								echo json_encode(array('marcad'=>'ok','vino'=>$resultado2['status'],'nmarca'=>$resultado['marca'],'pais'=>$resultado['localidad']));
							/*}else{
								echo json_encode(array('marcad'=>'no','nmarca'=>$resultado['localidad'],'pais'=>$resultado['localidad']));
							}*/
						}
					}else{
						echo json_encode(array('marcad'=>'no','nmarca'=>$resultado['marca'],'pais'=>$resultado['localidad']));
					}
			}else{
			echo json_encode(array('marcad'=>'no'));
			}	
		}
}
?>

