<?php
session_start();
include("dist/funciones/conectarbd.php");
$data = $_GET;
if($data['op'] == 'validar'){
	$vuser = mysqli_real_escape_string($con,(strip_tags($data['tuser'],ENT_QUOTES)));
	$cla = mysqli_real_escape_string($con,(strip_tags($data['tpass'],ENT_QUOTES)));
	$cons1 = mysqli_query($con,"select CONCAT (nombre,' ',apellido) AS nombreu, ud.foto, ud.mobile, u.perfil,u.id_user,u.localidad 
							from app_user u,`apps_user_adetails` ud where u.userbi = '".$vuser."' and u.clavebi = PASSWORD('".$cla."') 
							and u.estado = '1' and u.id_user = ud.`id_user` and estado = '1' ");
							
	

if(mysqli_num_rows($cons1)==0){
	
	header('Content-type: application/json; charset=utf-8');
	echo json_encode(array("response" => "no"));
}else{
	$resultados = mysqli_fetch_assoc($cons1);
	//$resultados = $obj->obtener_fila($cons1,0);
	//$user =$resultados['userbi'];
	//$tok =$resultados['token'];
	$fecha = date("Y-m-d");
	$hora=date("H:i:s");
	 $_SESSION['autenticado']    = 'SI';
        //Crear una variable para guardar el ID del usuario para tenerlo siempre disponible
		
		$_SESSION['usuario']  = $resultados['nombreu'];
		$_SESSION['perfi']  = $resultados['perfil'];
		if($resultados['perfil']==1){
			$_SESSION['uid']   = $resultados['id_user'];
			$_SESSION['menu'] = $resultados['id_user'];
			$_SESSION['local'] = $resultados['localidad'];
		}else if($resultados['perfil']==2 || $resultados['perfil']==3){
				$_SESSION['uid']   = $resultados['id_user'];
				$_SESSION['menu']   = $resultados['id_user'];
				$_SESSION['local'] = $resultados['localidad'];
		}
		
		$_SESSION['foto'] = $resultados['foto'];
		/*$_SESSION['foto']  = $foto;
		$_SESSION['perfi']  = $perfil;
	*/
	
	
	$sql2 =  mysqli_query($con,"INSERT INTO apps_acceso_user(id_user,fecha,hora) 
					VALUES ('".$resultados['id_user']."','".$fecha."','".$hora."') ");
	header('Content-type: application/json; charset=utf-8');
	echo json_encode(array("response" => "ok","uid" => $resultados['id_user'],"perfi" => $resultados['perfil'],"token_aleatorio" => $_SESSION['usuario']));
}
}
?>