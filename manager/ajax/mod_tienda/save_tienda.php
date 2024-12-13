<? 
session_start();
		if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
		{
			//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
			//pantalla de login, enviando un codigo de error
			header ("Location: ../../index.php?error=ER001");
			exit;
		}else{
		include("../../dist/funciones/conectarbd.php");
		$_SESSION['perfi'] = mysql_real_escape_string($_SESSION['perfi']);
		$_SESSION['uid'] = mysql_real_escape_string($_SESSION['uid']);
		$obj = new ConexionMySQL();
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
		$tienda = mysql_real_escape_string($_GET['nombre_t']);
		$pmarca = mysql_real_escape_string($_GET['id_ubic']);
		$phone = mysql_real_escape_string($_GET['tef']);
		$razon = mysql_real_escape_string($_GET['rsocial']);
		$mail = mysql_real_escape_string($_GET['email_']);
		$direccion = mysql_real_escape_string($_GET['direc_']);
		$idloclaidad = mysql_real_escape_string($_GET['nlocal_']);
		$metros = mysql_real_escape_string($_GET['mts_']);
		$accionT = mysql_real_escape_string($_GET['accion']);
		
		$locali= $obj->consultar("SELECT * FROM apps_localidades WHERE localidad = '".$idloclaidad."'");
		$resultado2	= $obj->obtener_fila($locali,0) ;
		$sql = "SELECT * FROM apps_localidades WHERE localidad = '".$idloclaidad."'";
		//Registro de Localidad//
		if($accionT=="add"){
		$mmarca= $obj->consultar("select * from apps_tiendas where nombre_tienda = '".$tienda."' and 
								  id_localidad = '".$idloclaidad."' and id_mp = '".$pmarca."' and razon_social = '".$razon."' ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		$resultado	= $obj->obtener_fila($mmarca,0) ;
		if($resultado = $obj->num_rows($mmarca,0)==0){
			$sqli = "INSERT INTO apps_tiendas (id_localidad,id_mp,nombre_tienda,razon_social,direccion,telefono,email,mts2,status) VALUES 
					('".$resultado2['id_loc']."','".$pmarca."','".$tienda."','".$razon."','".$direccion."','".$phone."','".$mail."',
					'".$metros."',1)";
			$obj->ejecutar_sql($sqli);
			$id=mysql_insert_id();
			echo json_encode(array('tienda'=>'ok','sql'=>$sql));
		}else{
			echo json_encode(array('tienda'=>'existe'));
		}
		}
		if($accionT=="edit"){
			$marca = mysql_real_escape_string($_GET['marca_id_d']);
			$mmarca= $obj->consultar("select t.*, m.id_marca, c.localidad from apps_tiendas t, apps_marcas_x_pais mp, apps_marcas m,apps_localidades c 
									 where t.id_mp = mp.id_mp and mp.id_marca = m.id_marca and c.id_loc = t.id_localidad and t.id_tienda = '$marca' ");
			if($resultado = $obj->num_rows($mmarca,0)!=0){
				$resultado	= $obj->obtener_fila($mmarca,0) ;
				echo json_encode(array('tiendar'=>'ok','nrazon'=>$resultado['razon_social'],'tiendan'=>$resultado['nombre_tienda'],
								'phone'=>$resultado['telefono'],'mail'=>$resultado['email'],'dir'=>$resultado['direccion'],
								'locali'=>$resultado['localidad'],'mt'=>$resultado['mts2'],'tiedaid'=>$resultado['id_tienda'],
								'marca'=>$resultado['id_marca'],'mp'=>$resultado['id_mp']));
			}else{
			echo json_encode(array('tiendar'=>''));
			}	
		}
		if($accionT=="update"){
		$marca = mysql_real_escape_string($_GET['tienda_id_']);
		$marcan = mysql_real_escape_string($_GET['marca_name']);
		$url = mysql_real_escape_string($_GET['site']);
		$mmarca= $obj->consultar("select * from apps_tiendas where id_tienda = '$marca' and id_mp = '".$pmarca."' ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		$resultado	= $obj->obtener_fila($mmarca,0) ;
		if($resultado = $obj->num_rows($mmarca,0)!=0){
		
		$sqli = "UPDATE apps_tiendas SET razon_social = '".$razon."', nombre_tienda = '".$tienda."', direccion = '".$direccion."',
				telefono = '".$phone."', email = '".$mail."', mts2 = '".$metros."', id_localidad = '".$resultado2['id_loc']."'
				where id_tienda = '".$marca."' ";
			$obj->ejecutar_sql($sqli);
			$id=mysql_insert_id();
				echo json_encode(array('tienda'=>'update','sql'=>$sqli));
			}
			else{	
			echo json_encode(array('tienda'=>'no_update'));
			}
		}
		if($accionT=="consultar"){
			$marca = mysql_real_escape_string($_GET['marca_id_d']);
			$mmarca= $obj->consultar("select * from apps_tiendas where id_tienda = '$marca' ");
			if($resultado = $obj->num_rows($mmarca,0)!=0){
				$resultado	= $obj->obtener_fila($mmarca,0) ;
				echo json_encode(array('tiendac'=>'ok','nmarca'=>$resultado['nombre_tienda'],'momento'=>$resultado['status']));
			}else{
			echo json_encode(array('tiendac'=>''));
			}	
		}
		if($accionT=="delete"){
			$marca = mysql_real_escape_string($_GET['marca_id_d']);
			$mmarca= $obj->consultar("select * from apps_tiendas where id_tienda = '$marca' ");
			if($resultado = $obj->num_rows($mmarca,0)!=0){
				$resultado	= $obj->obtener_fila($mmarca,0);
				$tventas= $obj->consultar("select * from apps_ventas_d where id_tienda = '$marca' ");
				if($resul= $obj->num_rows($tventas,0)==0){
					$sql_delete = "DELETE FROM apps_tiendas where id_tienda = '$marca'";
					$obj->ejecutar_sql($sql_delete);
					$mmarca2= $obj->consultar("select * from apps_tiendas where id_tienda = '$marca' ");
					if($resultado2 = $obj->num_rows($mmarca2,0)==0){
					echo json_encode(array('tiendad'=>'ok','nmarca'=>$resultado['nombre_tienda'],'loc'=>$resultado['id_mp']));
					}else{
						echo json_encode(array('tiendad'=>'error'));
					}
				}else{
					echo json_encode(array('tiendad'=>'no_borrar'));
				}
			}else{
			echo json_encode(array('tiendad'=>'error'));
			}	
		}
		if($accionT=="update_s"){
			$marca = mysql_real_escape_string($_GET['marca_id_d']);
			$status = mysql_real_escape_string($_GET['actual']);
			$mmarca= $obj->consultar("select * from apps_tiendas where id_tienda = '$marca' ");
			//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
			if($resultado = $obj->num_rows($mmarca,0)!=0){
				$resultado	= $obj->obtener_fila($mmarca,0);
				$tventas= $obj->consultar("select * from apps_ventas_d where id_tienda = '$marca' ");	
				if($resul= $obj->num_rows($tventas,0)==0){
				$sqli = "UPDATE apps_tiendas SET status = '".$status."' WHERE id_tienda = '$marca'  ";
				$obj->ejecutar_sql($sqli);
				$id=mysql_insert_id();
				echo json_encode(array('tiendad'=>'update','loc'=>$resultado['id_mp']));
				}else{
					echo json_encode(array('tiendad'=>'no_update'));
				}
			}else{		
				echo json_encode(array('tiendad'=>'no_update'));
			}
		}
	}
?>

