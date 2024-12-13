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

if($_SESSION['perfi']==1){
	$where = "1=1";
	$from = "";
	$requerir = "";
}else{
	$where= "apps_user_acceso.`id_menu` = apps_menu.id_menu and apps_user_acceso.id_user = '".$_SESSION['uid']."' ";
	$from = ",apps_user_acceso";
	$requerir = ", apps_user_acceso.id_acceso";
}
$ubic = mysql_real_escape_string($_GET["ubicar"]);

$mmarca= $obj->consultar("SELECT t.*, m.marca,(CASE when t.status ='0'  then 'Inactiva' when t.status ='1' then 'Activa' END) AS Status
						  FROM apps_tiendas t, apps_marcas m, apps_marcas_x_pais px
						  where t.id_mp = '".$ubic."' and px.id_marca = m.id_marca and px.id_mp = t.id_mp");
$sql= $obj->consultar("SELECT  m.marca, p.bandera  FROM apps_tiendas t, apps_marcas m, apps_marcas_x_pais px, apps_paises p
						  where t.id_mp = '".$ubic."' and px.id_marca = m.id_marca and px.id_mp = t.id_mp and px.id_pais = p.cod");
$res = $obj->obtener_fila($sql,0)

?>
<body onload="vent.calendarInit();">
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#" onclick="vent.refresh_list_tiendas();">Manager Ventas</a></li>
			<li><a href="#">Tiendas de <?=$res['marca']?></a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Manager Tiendas  <strong> <?=$res['marca']?>  </strong><img class="img-rounded" src="<?=$res['bandera']?>" ></span>
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
							<th>Razon Social <i class="fa fa-globe"></i></th>
							<th>Tienda <i class="fa fa-bookmark"></i></th>
							<th>Facturado <i class="fa fa-usd"></i></th>
							<th>Facturado x Mts2 <i class="fa fa-usd"></i></th>
							<th><i class="fa fa-cog"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<? 
						$i=0;
						while($resultados = $obj->obtener_fila($mmarca,0)){
							$localid= $obj->consultar("select sum(total) as facturado from apps_ventas_d where id_tienda = '".$resultados['id_tienda']."'");
							$resultados2 = $obj->obtener_fila($localid,0);
							$tienda= $obj->consultar("select * from apps_tiendas where id_tienda = '".$resultados['id_tienda']."'");
							$resultados3 = $obj->obtener_fila($tienda,0);
							$i++;
						?>
						<tr>
							<td><?=$i?></td>
							<td><a class="ajax-link" style="cursor:pointer" onclick="vent.view_ventas_mes(<?=$resultados['id_mp']?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" ><?=utf8_encode($resultados['razon_social'])?></a></td>
							<td><a class="ajax-link" style="cursor:pointer" onclick="vent.view_ventas_mes(<?=$resultados['id_mp']?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" ><?=utf8_encode($resultados['nombre_tienda'])?></a></td>
							<td><?=number_format($resultados2['facturado'],2 ,",", ".")?> 
							</td>
							<td><?=number_format($resultados2['facturado']/$resultados3['mts2'],2 ,",", ".")?></td>
							<td><button type="button" class="btn btn-default btn-primary" onclick="ver_grafica2('<?=utf8_encode($resultados['pais'])?>','<?=$resultados['marca']?>');">Graficar <i class="fa fa-chart"></i></span></button>
							</td>
						</tr>
					<?}?>
					
					<!-- End: list_row -->
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="form_report_marcas2" tabindex="-1" role="dialog" aria-labelledby="form_report_marcas2" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_report_marcas2"><i class="fa  fa-dollar"></i> <div id="marca_p2"></div></h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
			<div id="graficar_ventas_bar2" style="min-width: 750px; height: 500px; margin: 0 auto"></div>
			<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-dolar"></i>
					<span id="marca_p3"> </span>
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
			<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-3">
					<thead>
						<tr>
							<th>Dia</th>
							<th>Vendido</th>
							<th>Meta</th>
							<th>Diferencia Meta</th>
						</tr>
					</thead>
					<tbody>
					<?for ($i=1;$i<=31;$i++){?>
						<tr>
							<td><?=$i?></td>
							<td><?=$i*30*(50/3)*1?></td>
							<td><?=$i*30*(10/3)*1?></td>
							<td><?=($i*30*(50/3)*1)-($i*30*(10/3)*1)?></td>
						</tr>
					<?}?>
					</tbody>

				</table>
				</div>
				</div>
				</div>
			</div>
		</div>
      </div>

  </div>
</div>
</div>

</body>
<!-- fin modal logo marca-->
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	LoadSelect2Script(MakeSelect2);
	TestTable3();
}
function MakeSelect2(){
	$('select').select2();
	
}

function ver_grafica2(x,y){
	$("#marca_p2").html(x+" "+y);
	$("#marca_p3").html("Detalles Venta para"+x+" "+y);
			$("#form_report_marcas2").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	$('#input_date').datepicker({setDate: new Date()});
	$('.form-control').tooltip();
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	// Sortable for elements
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes	
});
$(function(){
        $("input[name='file']").on("change", function(){
            var formData = new FormData($("#form_ventas_import")[0]);
            var ruta = "ajax/mod_ventas/add_file.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta_file").html(datos);
                }
            });
        });
		
		 $('#graficar_ventas_bar2').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Ventas del Mes '
        },
       
        xAxis: {
            categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12','13',14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30]
        },
        yAxis: {
            title: {
                text: 'Vendido ($)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '',
            data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6,17.0, 36.9, 29.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 29.6, 25.2, 26.5, 23.3, 18.3, 13.9, 19.6]
        }]
    });
     });
</script>

<?
}
?>