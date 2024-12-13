<?php
include("../../dist/funciones/funciones.php");
date_default_timezone_set('America/Los_Angeles');
$mes = date("M");
$dia = date("Y-m-d");
		$hostname="localhost";
		$username="24hopenv";
		$password="GkP6bxloBYcSDMk3";
		$database="bd_reservas_v1";
		$mysqli = new mysqli($hostname, $username,$password, $database);
		if ($mysqli -> connect_errno) {
		die( "Fallo la conexión a MySQL: (" . $mysqli -> mysqli_connect_errno() 
		. ") " . $mysqli -> mysqli_connect_error());
		}
		else
			$sql = "SELECT * from apps_emple_s order by nombres ASC" ;
		    $s = semanas();
			$sql2 = "SELECT e.*, c.nombres, s.servicio,s.color FROM  events e, apps_servicios_d s, apps_clientes c
							WHERE 
						    start BETWEEN  '".$s[0]."' and '".$s[1]."'
							and s.idh = e.idh 
							and e.id_cliente = c.id";
			$sql3 = "SELECT * FROM apps_control_cambio where status = 1  " ;
		//echo $sql;
			$result = mysqli_query($mysqli, $sql);
			$result2 = mysqli_query($mysqli, $sql2);
			$result3 = mysqli_query($mysqli, $sql3);
			$j=0;
			$json = "";
			//
			while($row2 = mysqli_fetch_assoc($result2)){
				$title = "Cliente: ".$row2['nombres']."\ Servicio: ".$row2['servicio'];
				$json .= '{"resourceId":"'.$row2['id_pro'].'","title":"'.$title.'","start":"'.$row2['start'].'","end":"'.$row2['end'].'","color": "'.$row2['color'].'"},';

			}
			$json = substr($json, 0, -1);
			
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
  <style>
    html, body {
      margin: 0;
      padding: 0;
      font-family: Helvetica;
      font-size: 11px ;
    }
	th{
		font-size:10px !important;
		color:#008C23 !important;
	}
    #calendar {
      max-width: 1100px;
      margin: 40px auto;
    }
	
  </style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="../../plugins/bootstrap/bootstrap-theme.css" rel="stylesheet">
<link href='../../plugins/fullcalendar/main.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.5.1/main.min.js'></script>

  
  <script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      //timeZone: 'UTC',
      initialView: 'resourceTimeGridDay',
	   schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      resources: [
	  <?php while($row = mysqli_fetch_assoc($result)){?>
        { id: "<?php echo $row['id']?>", title: "<?php echo $row['nombres']?>" },
		<?php
		  }
		  ?>
      ],
      events:  [<?php echo $json;?>],
	  editable: true
	});

    calendar.render();
  });
  
  

</script>
</head>
<body>
  <div id='calendar'></div>
</body>
	</html>
