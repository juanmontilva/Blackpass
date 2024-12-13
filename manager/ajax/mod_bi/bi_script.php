<?php
session_start();
		if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
		{
			//En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
			//pantalla de login, enviando un codigo de error
			header ("Location: ../../index.php?error=ER001");
			exit;
		}else{
		include("../../dist/funciones/conexion.php");
				$fecha = date("Y-m-d");
		$accionT =  $_GET['accion'];
		if($accionT=="label_pais"){	
			$datos1 = array();
			$datos2 = array();
			$datos3 = array();
			$r_mes = $_GET['mes'];
			if($r_mes==""){
				$r_mes = date("m");
			}
			$mmarca= $obj->consultar("SELECT p.abre,p.bandera, p.cod, COUNT(mp.id_marca) as marcas, COUNT(t.id_mp) as tiendas 
										FROM `apps_paises` p, apps_marcas_x_pais mp,apps_tiendas t 
										WHERE p.`cod` = mp.id_pais and mp.status = 1 and p.status = 1 and t.id_mp = mp.id_mp 
										and t.status = 1 ".$where." group by p.abre order by p.abre ASC ");
			
						  while($request_table_m = mysql_fetch_object($mmarca) ){
								array_push($datos1, $request_table_m);
						 }
				$ventas= $obj->consultar("SELECT p.abre, p.cod, p.moneda,  SUM(v.bruto) as vendido,AVG(v.bruto) as promedio 
											FROM `apps_paises` p, apps_marcas_x_pais mp,apps_ventas_d V , apps_tiendas t
											WHERE p.`cod` = mp.id_pais and mp.status = 1 and p.status = 1 and v.id_marca = mp.id_mp 
											and t.status = 1 and t.id_tienda = v.id_tienda ".$where." group by  p.abre order by p.abre ASC");
													  
						  while($request_ventas = mysql_fetch_object($ventas) ){
								array_push($datos2, $request_ventas);
						 }
						 
						$ventas_mes= $obj->consultar("SELECT p.abre, p.cod, p.moneda,  SUM(v.bruto) as vendido
											FROM `apps_paises` p, apps_marcas_x_pais mp,apps_ventas_d V , apps_tiendas t
											WHERE p.`cod` = mp.id_pais and mp.status = 1 and p.status = 1 and v.id_marca = mp.id_mp 
											and t.status = 1 and t.id_tienda = v.id_tienda ".$where." and  MONTH(v.fecha)= '".$r_mes."' group by  p.abre order by p.abre ASC");
													  
						  while($request_ventas_m = mysql_fetch_object($ventas_mes) ){
								array_push($datos3, $request_ventas_m);
						 }
						 
				$request_table_m = $datos1;
				$request_ventas = $datos2;
				$request_ventas_m = $datos3;
				header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					"infop" => $request_table_m,
					"infov" =>$request_ventas,
					"infov_m" =>$request_ventas_m
				));

		}
		if($accionT=="group_pie_pais"){	
				$datos4 = array();
				$mmarca= $obj->consultar("SELECT p.abre,SUM(v.bruto) as vendido
											FROM `apps_paises` p, apps_marcas_x_pais mp,apps_ventas_d V , apps_tiendas t
											WHERE p.`cod` = mp.id_pais and mp.status = 1 and p.status = 1 and v.id_marca = mp.id_mp 
											and t.status = 1 and t.id_tienda = v.id_tienda ".$where." group by  p.abre order by p.abre ASC");
			
						  while($request_table_m = mysql_fetch_object($mmarca) ){
								array_push($datos4, $request_table_m);
						 }
					$request_table_m = $datos4;	 
			header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					"infov2" => $request_table_m,
				));

		}
		if($accionT=="group_dat_word"){	
		$datos1 = array();
			$mmarca= $obj->consultar("SELECT p.abre,p.moneda,v.fecha,m.`marca`,SUM(piezas_v) as piezas,SUM(bruto) as vendido,SUM(n_ticket) as ticket FROM `apps_marcas` m, apps_marcas_x_pais mp,apps_ventas_d v,apps_paises p
									  WHERE m.`id_marca` = mp.`id_marca` and mp.id_mp = v.`id_marca` and v.fecha = '".$fecha."' 
									 ".$where." group by p.abre order by vendido DESC  ");

						  while($request_table_m = mysql_fetch_object($mmarca) ){
								array_push($datos1, $request_table_m);
						 }
						 
				$request_table_m = $datos1;
				header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					"pais_v" => $request_table_m
				));
		
		}
		if($accionT=="parse"){
			$datos = array();
			$marca = mysqli_real_escape_string($con,(strip_tags($_GET['identificador'],ENT_QUOTES)));
			$sqlx = mysqli_query($con,"select sum(s.precio) as total, s.servicio
							from apps_servicios_d s, events e 
							where e.idh = s.idh and e.id_pro = '".$marca."'
							group by e.start");
					/*echo "select sum(s.precio) as total, s.servicio
							from apps_servicios_d s, events e 
							where e.idh = s.idh and e.id_pro = '".$marca."'
							group by e.start";*/
					while($row= mysqli_fetch_assoc($sqlx)){
						$datos[] = array("monto"=>$row['total'],
										"servi"=>$row['servicio']);
					}	
			$request_table_m = $datos;
			$x = "1,2,3,4,5,6,7,8,9,10";
				header('Content-type: application/json; charset=utf-8');			
					echo json_encode(array(
					'response' => 'ok',
					"venta" => $request_table_m,
					"label"=>$x
				));
		}
		if($accionT=="group_graf_word"){	
		$mes = date("m");
		$year = date("Y");
		/*$datos1 = array();
		$datos2 = array();
		$datos3 = array();
		$datos4 = array();
		$datos5 = array();*/
		$k=0;	
		$sql1 = $obj->consultar("SELECT p.abre,p.cod FROM `apps_paises` p ");
		 while($resultados2 = $obj->obtener_fila($sql1,0)){
					$l=0;
					$mmarca= $obj->consultar("SELECT p.abre,SUM(bruto) as vendido
									FROM `apps_marcas` m, apps_marcas_x_pais mp,apps_ventas_d v,apps_paises p WHERE mp.id_mp = v.`id_marca` 
									".$where." and mp.id_pais = '".$resultados2['cod']."' and p.cod = mp.id_pais and MONTH(v.fecha)= '".$mes."'
									and YEAR(v.fecha)= '".$year."' group by p.abre,v.fecha order by v.fecha ASC ");
				/*echo "SELECT p.abre,v.fecha ,SUM(bruto) as vendido
									FROM `apps_marcas` m, apps_marcas_x_pais mp,apps_ventas_d v,apps_paises p WHERE mp.id_mp = v.`id_marca` 
									".$where." and mp.id_pais = '".$resultados2['cod']."' and p.cod = mp.id_pais and MONTH(v.fecha)= '".$mes."'
									and YEAR(v.fecha)= '".$year."' group by p.abre,v.fecha order by vendido DESC  "."<br>";*/
			if($resultado = $obj->num_rows($mmarca,0)!=0){
				if($k==0){
					
					while($resultados = $obj->obtener_fila($mmarca,0)){
						$pais1 = $resultados['abre'];
						$array[] = array(intval($resultados['vendido']));
					 }
				}
				if($k==1){
					
					 while($resultados = $obj->obtener_fila($mmarca,0)){
						 $pais2 = $resultados['abre'];
						 $array2[] = array(intval($resultados['vendido']));
					 }
				}
				if($k==2){
					
					 while($resultados = $obj->obtener_fila($mmarca,0)){
						 $pais3 = $resultados['abre'];
						$array3[] = array(intval($resultados['vendido']));
					 }
				}
				if($k==3){
					
						 while($resultados = $obj->obtener_fila($mmarca,0)){
							 $pais4 = $resultados['abre'];
						 $array4[] = array(intval($resultados['vendido']));
					 }
				}
				if($k==4){
					
					 while($resultados = $obj->obtener_fila($mmarca,0)){
						 $pais5 = $resultados['abre'];
						 $array5[] = array(intval($resultados['vendido']));
					 }
				}
				
				$k++;
				}
		 }
		    $cdias = UltimoDia($year,$mes);
			header("content-type: application/json"); 
			echo  json_encode(array('response'=>'ok','dias'=>$cdias,'pais1'=>$pais1,'pais2'=>$pais2,'pais3'=>$pais3,'pais4'=>$pais4,'pais5'=>$pais5,'data1'=>$array,'data2'=>$array2,'data3'=>$array3,'data4'=>$array4,'data5'=>$array5));    
			
		}

	}
	
?>

