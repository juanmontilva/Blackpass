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



$mmarca= "SELECT p.pais,p.moneda,m.marca,p.bandera ,mp.id_mp FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m  WHERE 
							mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod 
							and mp.status = 1";
$query_u = mysqli_query($conexion,$mmarca);
$paises= "SELECT p.* FROM `apps_provincias` pv, apps_paises p WHERE p.cod = pv.`id_pais` 
							and p.status = 1 group by cod  order BY p.pais ASC";
$query_u2 = mysqli_query($conexion,$paises);
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_tienda/list_view_ventas.php">Ventas Registradas</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Reporte de Ventas por Eventos</span>
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
			<p></p>
			<div style="margin-left:87%">
			
			 </div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>ID</th>
							<th>Evento <i class="fa fa-bookmark"></i></th>
							<th>Facturado <i class="fa fa-usd"></i></th>
							<th> <i class="fa fa-cog"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<? 
						$i=0;
						while($resultados = mysqli_fetch_assoc($query_u)){
							$localid= "select count(*) as cantidad from apps_tiendas where id_mp = '".$resultados['id_mp']."'";
							
							$query_u3 = mysqli_query($conexion,$localid);
							$resultados2 = mysqli_fetch_assoc($query_u);
							$i++;
							$fatu= "select sum(total) as facturado from apps_ventas_d where id_marca= '".$resultados['id_mp']."'";
							$query_u4 = mysqli_query($conexion,$fatu);
							$resultados3 = mysqli_fetch_assoc($query_u4);
							if($resultados3['facturado']>0){
						?>
						<tr>
							<td><?php echo $i?></td>
							<td>
							</td>
							<td><?php echo number_format($resultados3['facturado'],2 ,",", ".")?> <?php echo $resultados['moneda']?></td>
							<td><button type="button" class="btn btn-default btn-primary" onclick="ver_grafica('<?=utf8_encode($resultados['pais'])?>','<?=$resultados['marca']?>');">Graficar <i class="fa fa-chart"></i></span></button></td>
						</tr>
							<?php
							}
							}
							?>
					
					<!-- End: list_row -->
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="form_report_marcas" tabindex="-1" role="dialog" aria-labelledby="form_report_marcas" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_report_marcas"><i class="fa  fa-dollar"></i> <div id="marca_p"></div></h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
			<div id="graficar_ventas_bar" style="min-width: 750px; height: 500px; margin: 0 auto"></div>
			<div id="container_vts_mts" style="min-width: 750px; height: 500px; margin: 0 auto"></div>
			
			</div>
		</div>
      </div>

  </div>
</div>
</div>




<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
var pa = "";
var ma = "";
function AllTables(){
	TestTable1();
	LoadSelect2Script(MakeSelect2);
}
function ver_grafica(x,y){
	$("#marca_p").html(x+" "+y);
			$("#form_report_marcas").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
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
	WinMove();
});
	
       $(function () {
    // Create the chart
    $('#graficar_ventas_bar').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },

        xAxis: {
            type: 'Marcas'
        },
        yAxis: {
            title: {
                text: 'Total por Tiendas'
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

        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'TIENDA 1 LARCOMAR',
                y: 56.33,
                drilldown: 'TIENDA 1 LARCOMAR'
            }]
        }],
        drilldown: {
            series: [{
                name: 'Microsoft Internet Explorer',
                id: 'Microsoft Internet Explorer',
                data: [
                    [
                        'v11.0',
                        24.13
                    ],
                    [
                        'v8.0',
                        17.2
                    ],
                    [
                        'v9.0',
                        8.11
                    ],
                    [
                        'v10.0',
                        5.33
                    ],
                    [
                        'v6.0',
                        1.06
                    ],
                    [
                        'v7.0',
                        0.5
                    ]
                ]
            }]
        }
    });
	
	    $('#container_vts_mts').highcharts({
        title: {
            text: 'Ventas x Mts2 Diaria',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: ICG',
            x: -20
        },
        xAxis: {
            categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,26,28,29,30,31]
        },
        yAxis: {
            title: {
                text: 'Ventas x Mts2'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '$'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Tienda 1',
            data: [127.0, 126.9, 149.5, 114.5, 128.2, 211.5, 252.2]
        }]
    });
});
</script>
