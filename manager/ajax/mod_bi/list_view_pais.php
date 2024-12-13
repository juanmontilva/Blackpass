<? 
session_start();

//Validar que el usuario este logueado y exista un UID
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
$pais = $_GET['ubicar'];
if($_SESSION['perfi']==1){
			$where = "  ";
			$from = "";
			$requerir = "";
		}else{
			$where= "  and  mp.id_pais = '".$pais_user ."' ";
			$from = ",apps_user_acceso";
			$requerir = ", apps_user_acceso.id_acceso";
		}
$year = date("Y");
$mes = date("m");
$fecha = date("d/m/Y");
$datos_tienda ="";
				$sql_pais= $obj->consultar("SELECT p.abre from `apps_paises` p where p.`cod` = '".$pais."' and status = '1' ");
				$resultado3	= $obj->obtener_fila($sql_pais,0) ;
				
				$sql_control = $obj->consultar("SELECT sum(v.bruto) vtotal FROM apps_ventas_d v,apps_marcas_x_pais mp WHERE mp.id_mp = v.id_marca and mp.id_pais = 1  ");
				$resultado	= $obj->obtener_fila($sql_control,0) ;
				
				$barra_sql= $obj->consultar("SELECT m.marca,mp.id_mp FROM apps_marcas_x_pais mp, apps_marcas m WHERE m.id_marca = mp.id_marca and mp.id_pais  = '".$pais."' order by m.marca ASC");
			 while($resul1 = $obj->obtener_fila($barra_sql,0)){
							
						$marca = $resul1['marca'];
						$sql_facturado = $obj->consultar("SELECT sum(bruto) as vendido FROM apps_ventas_d WHERE id_marca = ".$resul1['id_mp']." ");
						$resultado1	= $obj->obtener_fila($sql_facturado,0) ;
						if($resultado1['vendido']==""){
							$monto = 0;
						}else{
							$monto = $resultado1['vendido']*100/$resultado['vtotal'];
						}
						if($resultado1['vendido']!=""){
						$sql_tiendas = $obj->consultar("SELECT * FROM APPS_TIENDAS WHERE ID_MP = '".$resul1['id_mp']."'");
						$datos_tienda =  '{ "name": "'.$marca.'", "id": "'.$marca.'", "data": [';
						}
						while($resul2 = $obj->obtener_fila($sql_tiendas,0)){
							 
							 $sql_venta_t = $obj->consultar("SELECT sum(v.bruto) venta_t FROM apps_ventas_d v WHERE id_tienda = '".$resul2['id_tienda']."' ");
							 $resultado2  = $obj->obtener_fila($sql_venta_t,0);
							 $monto_t = $resultado2['venta_t']*100/$resultado1['vendido'];
							 $datos_tienda1 .=  '[ "'.$resul2['nombre_tienda'].'", '.$monto_t.'   ],'; 
							
						 }
						
						$datos_tienda2 = '] },';
						$datos_barra .= '{"name":"'.$marca.'","y":'.$monto.',"drilldown":"'.$marca.'" },';
			}
			$datos_barra = substr($datos_barra, 0, -1);
			 $datos_tienda1 = substr($datos_tienda1, 0, -1);
			 $datos_tienda2 = substr($datos_tienda2, 0, -1);
			 $datos_t = $datos_tienda.$datos_tienda1.$datos_tienda2;
			//echo  $datos_t;
			$pais_name = $resultado3['abre'];
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_bi/list_pais.php">REPORTE VENTAS PAIS </a></li>
			<li><a href="#">REPORTE VENTAS <?php echo $pais_name;?></a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>REPORTE VENTAS  <strong><?php echo $pais_name." - ". $year;?>  </strong></span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
					<a class="close-link">
						<i class="fa fa-times"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
			<div id="drill_gra_pais" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
</div>





<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings

$(document).ready(function() {

	Highcharts.chart('drill_gra_pais', {
		 
    chart: {
        type: 'column'
    },
    title: {
        text: 'RESUMEN VENTAS PARA <? echo $periodo." para ". $pais_name ?>'
    },
    subtitle: {
        text: 'Clic en la Marca para detalles'
    },
    xAxis: {
        type: 'Marca'
    },
    yAxis: {
        title: {
            text: 'Total por Marca'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    "series": [
        {
            "name": "MARCAS",
            "colorByPoint": true,
            "data": [

               <?php echo $datos_barra; ?> 
            ]
        }
    ],
    "drilldown": {
        "series": [
           <?php echo $datos_t;?>
        ]
    }
    });

	console.log();
	
});
</script>

<?
}
?>

