<?php

// Conexion a la base de datos
require_once('bdd.php');
require_once('../../dist/funciones/api_ws.php');
require_once('../../dist/funciones/funciones.php');
if (isset($_POST['title']) && isset($_POST['start']) ){
	$title = $_POST['title'];
	$start = $_POST['start'];
	$hora = $_POST['hora'];
	$locali = $_POST['locali'];
	$idc = $_POST['idc'];
	$n_hab = $_POST['servi'];
	$id_pro = $_POST['pro'];
	$localizador = cadena();
	//echo $idc;
	$fech = $start." ".$hora.":00";
	$end = strtotime('+1 hour', strtotime($fech));
	$end = date("M/d/Y H:i:s",$end);
	$sqli = "SELECT * FROM events where id_cliente = '$idc' and  idh = '$n_hab' 
			and start = '$fech' and id_local = '$locali' and localizado = '$localizador' ";
	//echo $sqli;
	$req = $bdd->prepare($sqli);
	$req->execute();
	$cuenta_col = $req->rowCount();
	//echo "=".$cuenta_col;
	if($cuenta_col==0){
	$title ="Reserva: ".$title;
	$sql = "INSERT INTO events(id_cliente,title, start, end,id_pro,idh,id_local,localizador) values 
							('$idc','$title', '$fech', '$end', '$id_pro','$n_hab','$locali','$localizador')";
	//echo $sql;
	$query = $bdd->prepare($sql);
		if ($query == false) {
	 print_r($bdd->errorInfo());
	 die ('Erreur prepare');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}else{
		//informacion del comercio//
		$sql_m = "select * from apps_comercio";
		$req = $bdd->prepare($sql_m);
		$req->execute();
		$row = $req->fetchObject(__CLASS__);
		echo "t=".$row['titulo'];
		//informacion del cliente
		$sql_c = "select * from apps_clientes where codigo = '$idc'";
		$req2 = $bdd->prepare($sql_c);
		$req2->execute();
		$row2 = $req2->fetchAll();
		//informacion de la cita//
		$sql_s = "SELECT s.servicio, l.* 
					FROM apps_servicios_d s, apps_marcas_x_pais mp, apps_localidades l 
					WHERE l.id_loc = mp.id_pais 
					and s.id_marca = mp.id_marca 
					and l.id_loc = '$locali' 
					and s.idh = '$n_hab'";
		$req3 = $bdd->prepare($sql_s);
		$req3->execute();
		$rows = $req3->fetchAll();
		
		$sql_e = "SELECT e.nombres FROM apps_emple_s_d d, apps_emple_s e 
					WHERE e.id = d.id_e 
					and d.id_s = '$n_hab'
					and d.id_e = '$id_pro' ";
		$req4 = $bdd->prepare($sql_e);
		$req4->execute();
		$rows4 = $req4->fetchAll();
		$mensaje =  utf8_encode($row['titulo'])."\n\n".
					saludo()." *".$row2['nombres']."\n".
					"Reserva: *".$localizador."* \n".
					"Local: *".$row3['localidad']."*\n".
					"Servicio: *".utf8_encode($row3['servicio'])."*\n".
					"Fecha y Hora: *".$fech."*\n".
					"Profesional: *".$row4['profesional']."*\n".
					"Por favor se le recuerda llegar 5min antes de su cita";
		$send = sendMessage($row['telefono'],$u,utf8_encode($mensaje));
		die ('OK');
	}
	}else{
		die ('EX');
	}
    
	


}
//header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
