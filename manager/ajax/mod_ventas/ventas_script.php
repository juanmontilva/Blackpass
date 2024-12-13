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
		$accionT = $_GET['accion'];
		if($accionT==""){
			$accionT = $_POST['accion'];
		}
		//Registro de Ventas//
		if($accionT=="add"){
		$idtienda = $_POST['idtienda'];
		$fecha = $_POST['fecha_v'];
		$piez = $_POST['piezas'];
		$tic = $_POST['tick'];
		$brut = $_POST['bruto'];
		$impuesto = $_POST['iva'];
		$total = $_POST['tt'];
		$hora = date("H:i:s");
		$tienda_f = $_POST['tienda'];
		$fechas = explode(",", $fecha);
		$piez = explode(",", $piez);
		$tic =  explode(",", $tic);
		$brut =  explode(",", $brut);
		$impuesto =  explode(",", $impuesto);
		$total =  explode(",", $total);
		$j=0;
		$k=0;
		if($idtienda==""){
				$tienda_f =  explode(",", $tienda_f);
				$idtienda = $tienda_f[3];
		}
		$locali= $obj->consultar("SELECT id_mp FROM apps_tiendas WHERE id_tienda = '".$idtienda."'");
		$resultado2	= $obj->obtener_fila($locali,0) ;
		for ($i=3;$i<count($fechas);$i++) {

			$mmarca= $obj->consultar("select * from apps_ventas_d where id_tienda = $idtienda and fecha = '".$fechas[$i]."'");
			//echo "select * from apps_ventas_d where id_tienda = $idtienda and fecha = '".$fechas[$i]."'"."<br>";
			$resultado	= $obj->obtener_fila($mmarca,0) ;
			if($resultado = $obj->num_rows($mmarca,0)==0){
			$sqli = "INSERT INTO apps_ventas_d (id_tienda,id_marca,fecha,hora,bruto,iva,total,piezas_v,n_ticket,tipo_registro) VALUES 
					('".$idtienda."','".$resultado2['id_mp']."','".$fechas[$i]."','".$hora."','".$brut[$i]."','".$impuesto[$i]."','".$total[$i]."','".$piez[$i]."','".$tic[$i]."','1')";
			//echo $sqli."<br>";
			$obj->ejecutar_sql($sqli);
			$id=mysql_insert_id();
			if($id!=""){
				$j+=1;
				$id="";
			}
			
		}else{
			$sqli = "UPDATE apps_ventas_d set bruto = '".$brut[$i]."',iva = '".$impuesto[$i]."',total= '".$total[$i]."', piezas_v = '".$piez[$i]."',n_ticket = '".$tic[$i]." 
					where  id_tienda = '".$idtienda."' and id_marca = '".$resultado2['id_mp']."' fecha '".$fechas[$i]."' " ;
			$obj->ejecutar_sql($sqli);
			$k+=1;
		}
		}
		
		echo json_encode(array('ventas'=>'ok','inser'=>$j,'update'=>$k,'marca'=>$resultado2['id_mp']));
		}
		
		
		if($accionT=="add_v"){
		$idtienda = $_GET['idtienda'];
		$fecha = $_GET['fecha_v'];
		$piez = $_GET['piezas'];
		$tic = $_GET['tick'];
		$brut = $_GET['bruto'];
		$impuesto = $_GET['iva'];
		$total = $_GET['tt'];
		$accionT = $_GET['accion'];
		$hora = date("H:i:s");
		$tienda_f = $_GET['tienda'];
		$locali= $obj->consultar("SELECT id_mp FROM apps_tiendas WHERE id_tienda = '".$idtienda."'");
		$resultado2	= $obj->obtener_fila($locali,0) ;
			$mmarca= $obj->consultar("select * from apps_ventas_d where id_tienda = $idtienda and fecha = '".$fecha."'");
			$resultado	= $obj->obtener_fila($mmarca,0) ;
			if($resultado = $obj->num_rows($mmarca,0)==0){
			$sqli = "INSERT INTO apps_ventas_d (id_tienda,id_marca,fecha,hora,bruto,iva,total,piezas_v,n_ticket,tipo_registro) VALUES 
					('".$idtienda."','".$resultado2['id_mp']."','".$fecha."','".$hora."','".$brut."','".$impuesto."','".$total."','".$piez."','".$tic."','".$total."')";
			//echo $sqli."<br>";
			$obj->ejecutar_sql($sqli);
			$id=mysql_insert_id();
			if($id!=""){
				$j+=1;
				$id="";
			}
			
		}else{
			$sqli = "UPDATE apps_ventas_d set bruto = '".$brut."',iva = '".$impuesto."',total= '".$total."', piezas_v = '".$piez."',n_ticket = '".$tic." 
					where  id_tienda = '".$idtienda."' and id_marca = '".$resultado2['id_mp']."' fecha '".$fechas."' " ;
			$obj->ejecutar_sql($sqli);
			$k+=1;
		}

		
		echo json_encode(array('ventas'=>'ok','marca'=>$resultado2['id_mp']));
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
		if($accionT=="delete_ventas_all"){
			if($_SESSION['perfi']==1){
			$marca = mysql_real_escape_string($_GET['marca_id_d']);
				$tventas= $obj->consultar("select * from apps_ventas_d where id_marca = '$marca' ");
				if($resul= $obj->num_rows($tventas,0)!=0){
					$sql_delete = "DELETE FROM apps_ventas_d where id_marca = '$marca'";
					$obj->ejecutar_sql($sql_delete);
					$mmarca2= $obj->consultar("select * from apps_ventas_d where id_marca = '$marca' ");
					if($resultado2 = $obj->num_rows($mmarca2,0)==0){
					echo json_encode(array('delete'=>'ok'));
					}else{
						echo json_encode(array('delete'=>'error'));
					}
				}else{
					echo json_encode(array('delete'=>'no_borrar'));
				}
			}else{
				echo json_encode(array('delete'=>'permiso'));
			}
		}
		if($accionT=="delete_ventas_mes"){
			if($_SESSION['perfi']==1){
			$marca = mysql_real_escape_string($_GET['marca_id_d']);
			$mes = mysql_real_escape_string($_GET['mes']);
			$year = mysql_real_escape_string($_GET['year']);
				$tventas= $obj->consultar("select * from apps_ventas_d where id_marca = '$marca' and YEAR(fecha) = '".$year."' and MONTH(fecha) = '".$mes."' ");
				if($resul= $obj->num_rows($tventas,0)!=0){
					$sql_delete = "DELETE FROM apps_ventas_d where id_marca = '$marca' and YEAR(fecha) = '".$year."' and MONTH(fecha) = '".$mes."' ";
					$obj->ejecutar_sql($sql_delete);
					$mmarca2= $obj->consultar("select * from apps_ventas_d where id_marca = '$marca' and YEAR(fecha) = '".$year."' and MONTH(fecha) = '".$mes."' ");
					if($resultado2 = $obj->num_rows($mmarca2,0)==0){
					echo json_encode(array('delete'=>'ok'));
					}else{
						echo json_encode(array('delete'=>'error'));
					}
				}else{
					echo json_encode(array('delete'=>'no_borrar'));
				}
			}else{
				echo json_encode(array('delete'=>'permiso'));
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

