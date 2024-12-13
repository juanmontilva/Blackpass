<?php
session_start();
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
$marca = mysqli_real_escape_string($con,(strip_tags($_GET['identificador'],ENT_QUOTES)));
$mmarca= "SELECT * from apps_user_adetails where id_user = $marca";
$query_u = mysqli_query($con,$mmarca);
$semana = semana_i();
if(isset($_GET['start']) && isset($_GET['end']) ){
	//echo ."-".$_GET['fin'];
	if($_GET['start']!="" && $_GET['end']!=""){
	//$ini = explode("/",$_GET['start']);
	//$fin = explode("/",$_GET['end']);
	$fechas[0] = $_GET['start'];
	$fechas[1] = $_GET['end'];
	}if($_GET['mes_b']!=0){
		//echo "mes = ".$_GET['mes_b'];
		$m = $_GET['mes_b'];
		$y =  date("Y")."-".date($m)."-".date("d");
		$first = new DateTime($y);
		$first->modify('first day of this month');
		$fechas[0]= $first->format('Y-m-d'); // imprime por ejemplo: 01/12/2012
		$last = new DateTime($y);
		$last->modify('last day of this month');
		$fechas[1] = $last->format('Y-m-d'); // imprime por ejemplo: 01/12/2012
	}
}

$year = date("Y");
$mes = date("m");
$fecha = date("d/m/Y");
$ci = explode("-",$fechas[0]);
$cf = explode("-",$fechas[1]);
$ci = $ci[2];
$cf = $cf[2];
$dia_g = "";
if($mes<10){
	$mes = substr($mes,1,1);
	
	
}
if($ci<10){
	$ci= substr($ci,1,1);
	
	
}
for($j=$ci;$j<=$cf;$j++){
	$dia_g .= "'".$j."',";
}
$dia_g = substr($dia_g, 0, -1);
$datos = array();
			$resultados = mysqli_fetch_assoc($query_u);
			$operador = $resultados['nombre']." ".$resultados['apellido'];
							$sqlx= mysqli_query($con,"select p.*, l.localidad from
							apps_pay_o p, apps_localidades l 
							where p.operador = '".$operador."'
							and p.fecha BETWEEN  '".$fechas[0]."' and '".$fechas[1]."'
							and l.id_loc = p.idl
							");
							
						
				
?>
<figure class="highcharts-figure">
    <div id="container"></div>
</figure>



		<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Service <?php echo $fechas[0]." al ".$fechas[1];?>'
    },
    subtitle: {
        text: 'Employer:'
    },
    xAxis: {
         categories: [<?php echo $dia_g;?>],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: '($)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} $</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
	
		<?php 
	
		while($row = mysqli_fetch_assoc($sqlx)){
			
			?>
			{
			name: '<?php echo $row['localidad']?>',
			data: [
					<?php
					  $info ="";
					   $dia = $fechas[0];
					   $diaf = $fechas[0];
						for($j=$ci;$j<=$cf;$j++){
						$sqly = mysqli_query($con,"select  sum(p.monto) as venta
							from
							apps_pay_o p, apps_localidades l 
							where p.operador = '".$operador."'
							and p.fecha = '".$dia."'
							and l.id_loc = p.idl
							and p.idl = '".$row['idl']."'");
					
						if(mysqli_num_rows($sqly)==0){
							$info .= "0,";
						}
						else{
							$row2 = mysqli_fetch_assoc($sqly);
							if($row2['venta']!=null){
								$info .= $row2['venta'].",";
							}else{
								$info .= "0,";
							}
							
						}
						
						$dia =  date("Y-m-d",strtotime($dia."+ 1 days"));
						$diaf =  date("Y-m-d",strtotime($diaf."+ 1 days"));
					}
					echo $info;
					?>
				  ]

			},
		<?php 
			}
			?>
			]
});
		</script>
	</body>
</html>
