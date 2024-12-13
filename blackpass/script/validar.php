<?php
session_start();
include("dist/funciones/conectarbd.php");
$data = $_GET;
if($data['op'] == 'validar'){
	$vuser = mysqli_real_escape_string($con,(strip_tags($data['tuser'],ENT_QUOTES)));
	$cla = mysqli_real_escape_string($con,(strip_tags($data['tpass'],ENT_QUOTES)));
	$cons1 = mysqli_query($con,"select ud.nombres,ud.telefono,u.token  
							from apps_clientes ud,apps_clientes_login u
							where ud.telefono = '".$vuser."' and u.token = PASSWORD('".$cla."') 
							and ud.estado = '1' and ud.id = u.user_id ");
							
	

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
		
		$_SESSION['usuario']  = $resultados['nombres'];
		$_SESSION['perfi']  = 1;
		if($resultados['perfil']==1){
			$_SESSION['uid']   = $resultados['id_user'];
			$_SESSION['menu'] = $resultados['id_user'];
		}
		
		
	
	$sql2 =  mysqli_query($con,"INSERT INTO apps_acceso_user(id_user,fecha,hora) 
					VALUES ('".$resultados['user_id	']."','".$fecha."','".$hora."') ");
	header('Content-type: application/json; charset=utf-8');
	echo json_encode(array("response" => "ok","uid" => $resultados['user_id	'],"perfi" => $resultados['perfil'],"token_aleatorio" => $_SESSION['usuario']));
}
}
?>