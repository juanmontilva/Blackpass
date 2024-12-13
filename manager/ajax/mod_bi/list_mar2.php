<?php
session_start();
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/funciones.php");
$mmarca= "SELECT * from apps_user_adetails order by nombre asc";
$query_u = mysqli_query($con,$mmarca);
$semana = semana_i();
$m=0;
if(isset($_GET['ini']) && isset($_GET['fin']) ){
	//echo ."-".$_GET['fin'];
	if($_GET['ini']!="" && $_GET['fin']!=""){
			$ini = explode("/",$_GET['ini']);
	$fin = explode("/",$_GET['fin']);
	$fechas[0] = $ini[2]."-".$ini[1]."-".$ini[0];
	$fechas[1] = $fin[2]."-".$fin[1]."-".$fin[0];
	}if($_GET['mes_b']!=""){
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
else{
	$fechas = semanas2();
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
					<span>Barra Herramientas</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content ">
			<div class="col-xs-3 alert alert-ligth  text-center"><p><i class="fa fa-calendar"></i></p><b><?php echo "Del: ".$semana[0]." al ".$semana[1];?></b></div>
			
			<div class="col-xs-3 alert alert-ligth  text-center">
				<div class="form-group">
					<label class="col-sm-4 control-label">Month</label>
					<div class="col-sm-6">
						<select id="mes_b"  onchange="consulta_f();">
						<option value="">---</option>
						<?php for($i=1;$i<=$mes;$i++){?>
						
						<option value="<?php echo $i; ?>" ><?php echo $i;?></option>';
						<?php	
							}
							?>
						</select>
					</div>
				</div>				
			</div>
			<div class="col-xs-6 alert alert-ligth  text-center">
			<div class="form-group">
					<label class="col-sm-4 control-label">Date period</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="date3_example" placeholder="Date period">
					</div>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="date3-1_example" placeholder="Date period" onchange="consulta_f();">
						
					</div>
				</div>
			</div>
			<br><br>
			</div>
		</div>
	</div>
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Reporte por Empleados <?php echo $fechas[0]." al ".$fechas[1];?></span>
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
			 		<?php 
						$i=0;
						$b = 10;
						while($resultados = mysqli_fetch_assoc($query_u)){
							$operador = $resultados['nombre']." ".$resultados['apellido'];
							$localid = mysqli_query($con,"select p.* from
							apps_pagos p
							where p.registrado = '".$operador."'
							and p.fecha BETWEEN  '".$fechas[0]."' and '".$fechas[1]."'
							and status = 2");
							
	
							if(mysqli_num_rows($localid)!=0){
								$b++;
								
						?>
				<div class="col-md-4 col-sm-4 alert alert-info  text-center"> <?php echo strtoupper($operador);?> </div>
				<br>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-<?php echo $b;?>">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Cajero<i class="fa fa-bookmark"></i></th>
							<th>Transacción<i class="fa fa-bookmark"></i></th>
							<th>Monto <i class="fa fa-usd"></i></th>
							<th> <i class="fa fa-cog"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						
						while($row2 = mysqli_fetch_assoc($localid)){
							?>
						<tr>
							<td><?php echo $row2['fecha'];?></td>
							<td><?php echo strtoupper($operador);?></td>
							<td><?php echo $row2['orden'];?></td>
							<td><?php echo number_format($row2['monto'],2 ,",", ".");?> USD </td>
							<td><button type="button" class="btn btn-default btn-primary" onclick="ver_grafica('<?php echo ($resultados['id_user']);?>','<?php echo $resultados['nombre'];?>','<?php echo $fechas[0];?>','<?php echo $fechas[1];?>');">Graficar <i class="fa fa-chart"></i></span></button></td>
						</tr>
							
					<?php 
						}?>
					<!-- End: list_row -->
					</tbody>
				</table>
				<br>
				<?php
								}
							}
							?>
			</div>
		</div>
	</div>
	<div class="col-xs-12">
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
			<div id="loading_home8" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
			<figure class="highcharts-figure">
				<div id="label_sales"></div>
			</figure>
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
function consulta_f(){
		var ini = $("#date3_example").val();
		var fin = $("#date3-1_example").val();
		var mes = $("#mes_b").val();
		var dataString = 'inic='+ ini + '&fin=' + fin + '&mes=' + mes;
  //alert (dataString);return false;
		  $.ajax({
			type: "GET",
			url: "home.php#ajax/mod_bi/list_mar2.php",
			data: dataString,
			success: function() {
			  $("#ajax-content").css({
                "opacity": 0.4
				});
			$("#ajax-content").load("ajax/mod_bi/list_mar2.php?ini="+ini+"&fin="+fin+"&mes_b="+mes, function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});		  
			  
			  $('#message').html("<h2>Contact Form Submitted!</h2>")
			  .append("<p>We will be in touch soon.</p>")
			  .hide()
			  .fadeIn(1500, function() {
				$('#message').show("loading_home6");
			  });
			}
		  });
		  //return false;
	}
function AllTables(){

	LoadSelect2Script(MakeSelect2);
}
function ver_grafica(x,y,fi,ff){
	$("#marca_p").html(y);
			$("#form_report_marcas").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
			});
			graf_employer(x,fi,ff);
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
	LoadTimePickerScript(AllTimePickers);
	// Add Drag-n-Drop feature
	WinMove();
});
Highcharts.chart('label_sales', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Sales <?php echo $fechas[0]." al ".$fechas[1];?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [<?php echo $dia_g;?>]
    },
    yAxis: {
        title: {
            text: 'Mount ($ USD)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [
	<?php 
		$sql4 = mysqli_query($con,"SELECT * from apps_user_adetails order by nombre asc");
		while($row4 = mysqli_fetch_assoc($sql4)){
				
	?>
	{
        name: '<?php echo $row4['nombre']?>',
        data: [
			<?php 
			
			$operador = $row4['nombre']." ".$row4['apellido'];
			    $info ="";
			   $dia = $fechas[0];
			  $diaf = $fechas[0];
			for($j=$ci;$j<=$cf;$j++){
			$sql5 = mysqli_query($con,"select sum(p.monto) as total
							from
							apps_pagos p
							where p.registrado = '".$operador."'
							and p.fecha = '".$dia."'
							and p.status = 2");
			
				if(mysqli_num_rows($sql5)!=0){
					$row5 = mysqli_fetch_assoc($sql5);
				//while($row5 = mysqli_fetch_assoc($sql5)){
					  if( $row5['total']!=null){
						  $info .= $row5['total'].",";
					  }else{
						  $info .= "0,";
					  }
					 
					//}
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
	],
	responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }
});

$('#loading_home8').hide(5000);
	function graf_employer(x,y,z,e){
		var datos = "";	
		$.ajax({
			url:'ajax/mod_bi/ventas_x_emplyer2.php',
			data:{
				"identificador":x,
				'start':y,
				'end':z,
				'mes_b':<?php echo $m;?>,
				'accion' : 'parse'
				},
				type : 'GET',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay :2000
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					},	
				success : function(data) {
						$("#graficar_ventas_bar").html(data);
				}
			});
	}
	<?php for($a=10;$a<=$b;$a++){?>
	$("#datatable-<?php echo $a;?>").DataTable({
		"paging":   true,
		"ordering": true,
        "info":     true,
			  language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
		})	
	<?php 
	}
	?>		
</script>
