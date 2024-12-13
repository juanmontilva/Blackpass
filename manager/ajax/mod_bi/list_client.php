<?php
session_start();
/*define("DB_SERVER", "localhost");
define("DB_USER", "root"); // webmaster_vzla
define("DB_PASS", "1t0l4B"); // w=@,Fb*D
define("DB_NAME", "_bd_shopping_retail_"); */

define("DB_SERVER", "209.126.119.214");
define("DB_USER", "itech_california"); // webmaster_vzla
define("DB_PASS", "california20*"); // w=@,Fb*D
define("DB_NAME", "itech_california"); 

$conexion = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); 
/*$consulta = $obj->consultar("select `apps_cargo`.`cargo`  from `apps_user`, `apps_cargo`  
where `apps_cargo`.`id_cargo` = `apps_user`.`id_cargo` and `apps_user`.`id_user` = '".$_SESSION['uid']."'");*/

$year = date("Y");
$mes = date("m");
$fecha = date("d/m/Y");
				$sql_control = "SELECT * from apps_control_cambio where status = '1' ";
				$query_u = mysqli_query($conexion,$sql_control);
				$resultado	= mysqli_fetch_assoc($query_u);
				$pie_sql= "SELECT p.abre,SUM(v.bruto) as venta FROM `apps_paises` p, apps_marcas_x_pais mp,apps_ventas_d V , 
											apps_tiendas t WHERE p.`cod` = mp.id_pais and mp.status = 1 and p.status = 1 and v.id_marca = mp.id_mp 
											and t.status = 1 and t.id_tienda = v.id_tienda   group by  p.abre order by p.abre ASC";
			//	echo $pie_sql;
				$query_u2 = mysqli_query($conexion,$pie_sql);
			 while($resul =  mysqli_fetch_assoc($query_u2)){
						if($resul['abre']=='VZLA'){
							$monto = $resul['venta']/$resultado['monto'];
						}else{
							$monto = $resul['venta'];
						}
						$datos_pie .= "{name:'".$resul['abre']."',y:".$monto."},";
						//echo $datos_pie;
			}
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_bi/list_pais.php">Ventas por Habitación</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Resumen Global <strong><?php echo $year?> </strong></span>
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
			<div class="box-content ">
			<div id="loading_home6" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
				
			<div id="label_graf_pais"></div>
			
			
			<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-6">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Ventas <strong><?php $year." - ".$mes?>  </strong> </span>
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
			<div class="box-content ">
			<div id="loading_home7" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
			<div id="pie_gra_pais"></div>
			<span>Tipo Cambio para venezuela al <?php $fecha?> <strong><?php echo $resultado['monto']?></strong> Bs x 1USD</span>
			</div>
		</div>
	</div>


	<div class="col-xs-6">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Ventas <strong><?php echo $year." - ".$mes?>  <?php $resultados4['nombre_tienda']?></strong></span>
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
			<div class="box-content ">
			<div id="pie_gra_pais2"></div>
			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	$('select').select2();
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Search');
	});
}
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	bi_graf.const_pais_label()
	 $('#pie_gra_pais').highcharts({
		 
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Distribución Ventas'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                },
				 series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.percentage:.1f}%'
                }
            }
            },
			
            series: [{
                name: 'Distribución Ventas',
                colorByPoint: true,
                data: [ <?php echo $datos_pie?>]
				
            }]
        });
		
		console.log(<?php echo $datos_pie?>);
		
		$('#pie_gra_pais2').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Distribución de Ventas por Habitación'
        },
        subtitle: {
            text: 'Click para ver mas'
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        series: [{
            name: 'Habitaciones',
            colorByPoint: true,
            data: [{
                name: 'Express',
                y: 52.33,
                drilldown: 'Express'
            }, {
                name: 'Corporativo',
                y: 24.77,
                drilldown: 'Corporativo'
            }]
        }],
        drilldown: {
            series: [{
                name: 'Express',
                id: 'Express',
                data: [
                    ['v40.0', 5],
                    ['v41.0', 4.32],
                    ['v42.0', 3.68],
                    ['v39.0', 2.96],
                    ['v36.0', 2.53],
                    ['v43.0', 1.45],
                    ['v31.0', 1.24],
                    ['v35.0', 0.85],
                    ['v38.0', 0.6],
                    ['v32.0', 0.55],
                    ['v37.0', 0.38],
                    ['v33.0', 0.19],
                    ['v34.0', 0.14],
                    ['v30.0', 0.14]
                ]
            }, {
                name: 'Corporativo',
                id: 'Corporativo',
                data: [
                    ['v40.0', 5],
                    ['v41.0', 4.32],
                    ['v42.0', 3.68],
                    ['v39.0', 2.96],
                    ['v36.0', 2.53],
                    ['v43.0', 1.45],
                    ['v31.0', 1.24],
                    ['v35.0', 0.85],
                    ['v38.0', 0.6],
                    ['v32.0', 0.55],
                    ['v37.0', 0.38],
                    ['v33.0', 0.19],
                    ['v34.0', 0.14],
                    ['v30.0', 0.14]
                ]
            }]
        }
    });
$("#loading_home7").hide();
	WinMove();
	console.log();
	
});
</script>

