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
		include("../../dist/funciones/funciones.php");
		include("../../dist/funciones/cript.php");
		include("../../dist/funciones/api_ws.php");
		include("../mod_mail/sendqrmail.php");
		//$_SESSION['perfi'] = mysql_real_escape_string($_SESSION['perfi']);
		//$_SESSION['uid'] = mysql_real_escape_string($_SESSION['uid']);
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
		$accionT = mysqli_real_escape_string($con,(strip_tags($_GET['accion'],ENT_QUOTES)));
		$cumple =date("Y-m-d");
				//Registro de Localidad//
		if($accionT=="add"){
		$nombre = mysqli_real_escape_string($con,(strip_tags($_GET['nombre_t'],ENT_QUOTES)));
		$pellido = mysqli_real_escape_string($con,(strip_tags($_GET['apelli'],ENT_QUOTES)));
		$pais = mysqli_real_escape_string($con,(strip_tags($_GET['id_ubic'],ENT_QUOTES)));
		$email = mysqli_real_escape_string($con,(strip_tags($_GET['correo'],ENT_QUOTES)));
		$tef = mysqli_real_escape_string($con,(strip_tags($_GET['tef'],ENT_QUOTES)));
		$locali= mysqli_query($con,"SELECT * FROM apps_user_adetails WHERE mail = '".$email."' and nombre = '".$nombre."'");
		echo 
		$resultado2	=  mysqli_fetch_assoc($locali) ;
		$sql = "SELECT * FROM apps_localidades WHERE localidad = '".$pais."'";

		$tuser = mysqli_real_escape_string($con,(strip_tags($_GET['tuser'],ENT_QUOTES)));
		$mmarca= mysqli_query($con,"SELECT * FROM apps_user_adetails WHERE mail = '".$email."' and nombre = '".$nombre."'");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		$resultado	=  mysqli_fetch_assoc($mmarca) ;
		if($resultado = mysqli_num_rows($mmarca)==0){
			$pass= cadena();
			$sqli = mysqli_query($con,"INSERT INTO app_user (manager,userbi,clavebi,user_tok,perfil,estado,localidad) 
			VALUES ('0','".$email."', PASSWORD('".$pass."'),PASSWORD('".getRandomCode()."'),'$tuser',1,'".$pais."')");
			$id=mysqli_insert_id($con);
	
					$sql2 = mysqli_query($con,"INSERT INTO apps_user_adetails (id_user,nombre,apellido,mail,mobile,fcumple,pais) 
					VALUES ('$id','".$nombre."','".$pellido."','".$email."','".$tef."','".$cumple."','".$pais."') ");
					$sql3 = mysqli_query($con,"INSERT INTO apps_user_acceso (id_menu,id_user)
										VALUES ('8',$id)");
					$sql3 = mysqli_query($con,"INSERT INTO apps_user_acceso (id_menu,id_user)
										VALUES ('15',$id)");
					$number = $tef;
					$message = "Esimado: ".$nombre.' se te asigno un usuario en el sistema 24hOpen, hemos enviado a tu email la información de acceso';
					//$result = $smsGateway->sendMessageToNumber($number, $message, $deviceID);
			$chatid = $tef."@c.us";
			$mensaje = $message."\n".
					   "*Usuario:* ".$tef."\n".
					   "*Clave:* ".$pass."\n".
					   "*Acceso: * http://app.24hopen.com/ve/manager/";
			//sendMessage($chatid,$mensaje);	
					$nombre = $nombre." ".$pellido;
					$respuesta = enviar_user($nombre,$email,$pass);
					if($respuesta[0]==1){
									header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>$respuesta[1]));
					}else{
						header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>"0"));
					}
					
			
		}else{
			echo json_encode(array('tuser'=>'existe'));
		}
		}
		
		if($accionT=="update"){

		$nombre = mysqli_real_escape_string($con,(strip_tags($_GET['nombre_t'],ENT_QUOTES)));
		$pellido = mysqli_real_escape_string($con,(strip_tags($_GET['apelli'],ENT_QUOTES)));
		$pais = mysqli_real_escape_string($con,(strip_tags($_GET['id_ubic'],ENT_QUOTES)));
		$email = mysqli_real_escape_string($con,(strip_tags($_GET['correo'],ENT_QUOTES)));
		$tef = mysqli_real_escape_string($con,(strip_tags($_GET['tef'],ENT_QUOTES)));
		$user = mysqli_real_escape_string($con,(strip_tags($_GET['userid'],ENT_QUOTES)));
		$tuser = mysqli_real_escape_string($con,(strip_tags($_GET['tuser'],ENT_QUOTES)));
		$user = $desencriptar($user);
		$sql= mysqli_query($con,"SELECT * FROM apps_user_adetails ud, app_user u 
								WHERE u.id_user = ud.id_user
								and u.id_user = '".$user."'");

		if(mysqli_num_rows($sql)!=0){
			$row	=  mysqli_fetch_assoc($sql);
		
		$update1 = mysqli_query ($con,"UPDATE  apps_user_adetails set
									nombre = '$nombre',apellido = '$pellido',
									mail = '$email',mobile = '$tef',
									pais = '$pais'
									where id_user = '".$row['id_user']."'");
			/*echo "UPDATE  apps_user_adetails set
									nombre = '$nombre',apellido = '$pellido',
									mail = '$email',mobile = '$tef',
									pais = '$pais'
									where id_user = '".$row['id_user']."'";*/
			if($update1){
				$pass= cadena();
				$sqli = mysqli_query($con,"UPDATE app_user SET 
										userbi = '$email',
										clavebi = PASSWORD('".$pass."'),
										user_tok = PASSWORD('".getRandomCode()."'),
										perfil = '$tuser',
										localidad = '$pais'
										where id_user = '".$row['id_user']."'");
				
				if($sqli){
					$nombre = $nombre." ".$pellido;
					$respuesta = enviar_user($nombre,$email,$pass);
					if($respuesta[0]==1){
									header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>$respuesta[1]));
					}else{
						header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>"0"));
					}
				}else{
					header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>"0"));
				}

			}else{
				header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>"1"));
			}
		}else{
			header('Content-type: application/json; charset=utf-8');
								    echo json_encode(array('tuser'=>"0"));
		}
		

		}

		if($accionT=="delete"){
			$uid = mysqli_real_escape_string($con,(strip_tags($_GET['cli'],ENT_QUOTES)));
			$status = mysqli_real_escape_string($con,(strip_tags($_GET['stat'],ENT_QUOTES)));
			
			$msql= mysqli_query($con,"select * from app_user where id_user = '$uid' ");
			if(mysqli_num_rows($msql)!=0){
				
				$sql_delete = mysqli_query($con,"update app_user set estado = '".$status."'
						where id_user = '$uid'");
					//echo $sql_delete;
					if($sql_delete){
					echo json_encode(array('response'=>'ok'));
					}else{
						echo json_encode(array('response'=>'error'));
					}
			}else{
			echo json_encode(array('response'=>'error'));
			}	
		}

		
		if($accionT=="foto"){
		$marca = mysql_real_escape_string($_GET['id_user']);
		$logo = mysql_real_escape_string($_GET['foto']);
		$mmarca= $obj->consultar("select * from apps_user_adetails where id_user = '$marca'  ");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
		if($resultado = $obj->num_rows($mmarca,0)!=0){	
		$resultado	= $obj->obtener_fila($mmarca,0) ;
			$sqli = "UPDATE apps_user_adetails SET foto = '".$logo ."' WHERE id_user = '$marca'  ";
			$obj->ejecutar_sql($sqli);
			$id=mysql_insert_id();
			echo json_encode(array('foto'=>'ok','mmarca'=>$resultado['nombre']." ".$resultado['apellido']));

		}else{
			echo json_encode(array('foto'=>''));
		}
		}
	}
?>


