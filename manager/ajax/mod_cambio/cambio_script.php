<?php
session_start();
		if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
		{
			//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
			//pantalla de login, enviando un codigo de error
			header ("Location: ../dshopretail/index.php?error=ER001");
			exit;
		}else{
		//include("../../dist/funciones/convertir_mes.php");
		include("../../dist/funciones/conexion.php");
		$_SESSION['perfi'] = $_SESSION['perfi'];
		$_SESSION['uid'] = $_SESSION['uid'];

		$consulta = "select pais from apps_user_adetails where `id_user` = '".$_SESSION['uid']."'";
		$query_u = mysqli_query($con,$consulta);
		$resultado	= mysqli_fetch_assoc($query_u) ;
		$pais_user = $resultado['pais'];
		if($_SESSION['perfi']==1){
			$where = "  ";
			$from = "";
			$requerir = "";
		}else{
			$where= "  and  mp.id_pais = '".$pais_user ."' ";
			$from = ",apps_user_acceso";
			$requerir = ", apps_user_acceso.id_acceso";
		}
		$fecha = date("Y-m-d");
		$accionT =  $_GET['accion'];
		if($accionT=="upcambio"){	
		$pais =  $_GET['cod'];
		$monto =  $_GET['mont'];
						$sqli = "UPDATE apps_control_cambio set status = 0 where id_pais = '".$pais."'" ;
						mysqli_query($con, $sqli);
						 
						$sqli2 = "INSERT INTO apps_control_cambio (fecha,monto,status,id_pais) VALUES 
						('".$fecha."','".$monto."',1,'".$pais."')";
						//echo $sqli."<br>";
						mysqli_query($con, $sqli2);
																  

				header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok'
				));

		}
		if($accionT=="addcambio"){	
		$pais =  $_GET['cod'];
		$monto =  $_GET['mont'];
						
						 
						$sqli = "INSERT INTO apps_control_cambio (fecha,monto,status,id_pais) VALUES 
						('".$fecha."','".$monto."',1,'".$pais."')";
						//echo $sqli."<br>";
						$obj->ejecutar_sql($sqli);
																  

				header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok'
				));

		}
		
	}
	
?>

