<?php
session_start();
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: index.php?error=ER001");
	//exit;
}else{
include("../../dist/funciones/funciones.php");
include("../../dist/funciones/conectarbd.php");
include("../../dist/funciones/cript.php");

/*$consulta = $obj->consultar("select `apps_cargo`.`cargo`  from `apps_user`, `apps_cargo`  
where `apps_cargo`.`id_cargo` = `apps_user`.`id_cargo` and `apps_user`.`id_user` = '".$_SESSION['uid']."'");*/
$semana = semana_i();

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
if($cf==1 || $cf =="01"){
	$cf = 31;
}
$totalv = 0;

				$sql_control = "SELECT * from apps_control_cambio where status = '1' ";
				$query_u = mysqli_query($con,$sql_control);
				$resultado	= mysqli_fetch_assoc($query_u);
				$pie_sql= "SELECT SUM(p.monto) as venta,  m.marca
							FROM  apps_pagos p, 
							apps_clientes c, apps_marcas m
							WHERE 
							p.idc = c.id
							and m.id_marca = c.plan
							and p.fecha BETWEEN '".$fechas[0]."' and '".$fechas[1]."'
							and  p.status = 2
							GROUP by m.id_marca";
			//echo $pie_sql;
				$query_u2 = mysqli_query($con,$pie_sql);
				$datos_pie = "";
			 while($resul =  mysqli_fetch_assoc($query_u2)){
						
							$monto = $resul['venta'];
		
						$datos_pie .= "{name:'".$resul['marca']."',y:".$monto."},";
						//echo $datos_pie;
			}
			if(mysqli_num_rows($query_u2)==0){
				$datos_pie = "{name:'sin datos',y:0}";
			}
	$sqls = mysqli_query($con,"SELECT SUM(p.monto) as venta,m.*
							FROM  apps_pagos p, 
							apps_clientes c, apps_marcas m
							WHERE 
							p.idc = c.id
							and m.id_marca = c.plan
							and p.fecha BETWEEN '".$fechas[0]."' and '".$fechas[1]."'
							and p.status = 2
							GROUP by m.id_marca ");
		
	$sqlt = mysqli_query($con,"select sum(s.precio) as total from apps_marcas m,apps_servicios_d s,
										events e where m.id_marca = s.id_marca
										and s.idh = e.idh
										and  start BETWEEN  '".$fechas[0]."' and '".$fechas[1]."'");
										
	$rowt = mysqli_fetch_assoc($sqlt);
	if(mysqli_num_rows($sqlt)==0){
		$totalv = 1;
	}else if(mysqli_num_rows($sqlt)>0){
		if($rowt['total']!="" && $rowt['total']!=0){
			$totalv = $rowt['total'];
		}else{
			$totalv = 1;
		}
		
	}
	
	//$query_s = mysqli_query($con,$sql_control);
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
			<li><a href="home.php#ajax/mod_bi/list_pais.php">Ingresos por Clientes</a></li>
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
					<label class="col-sm-4 control-label">Mes</label>
					<div class="col-sm-6">
						<select id="mes_b"  class="form-control" onchange="consulta_f();">
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
					<label class="col-sm-4 control-label">Periodo</label>
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
	
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Ingresos por Clientes <strong><?php echo $year." - ".$mes?>  </strong></span>
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
			<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
			<thead>
				<tr>
					<th>Producto</th>
					<th>Total Recargado</th>
					<th>Tipo Cliente</th>
					<th>Recarga Promedio</th>
				</tr>
			</thead>
			<tbody>
				<?php
				  $sql = mysqli_query($con,"SELECT SUM(p.monto) as venta,c.id, c.nombres, m.marca
											FROM  apps_pagos p, 
											apps_clientes c,
											apps_marcas m
											WHERE 
											p.idc = c.id
											and c.plan = m.id_marca
											and p.fecha BETWEEN '".$fechas[0]."' and '".$fechas[1]."'
											GROUP by p.idc");
					while($row = mysqli_fetch_assoc($sql)){
						$suma = $h=0;
						$sql2 = mysqli_query($con,"select * from apps_pagos where
											idc = '".$row['id'] ."'
											and fecha BETWEEN '".$fechas[0]."' and '".$fechas[1]."'");
							while($row2 = mysqli_fetch_assoc($sql2)){
								$suma = $suma + $row2['monto'];
								$h++;
							}
							$promedio = $suma/$h;
						echo '
						<tr>
							<td>'.$row['nombres'].'</td>
							<td>'.$row['venta'].' USD</td>
							<td>'.$row['marca'].'</td>
							<td>'.number_format($promedio, 2, '.', '').' USD</td>
						</tr>
						';
					}
				?>
				</tbody>
			</table>
			
			</div>
		</div>
	</div>




</div>



<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function consulta_f(){
		var ini = $("#date3_example").val();
		var fin = $("#date3-1_example").val();
		var mes = $("#mes_b").val();
		var dataString = 'inic='+ ini + '&fin=' + fin + '&mes=' + mes;
  //alert (dataString);return false;
		  $.ajax({
			type: "GET",
			url: "home.php#ajax/mod_bi/list_reg.php",
			data: dataString,
			success: function() {
			  $("#ajax-content").css({
                "opacity": 0.4
				});
			$("#ajax-content").load("ajax/mod_bi/list_reg.php?ini="+ini+"&fin="+fin+"&mes_b="+mes, function(e){
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
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	function AllTables(){
	TestTable1();
}
	

$('#loading_home6').hide(3000);
$('#loading_home7').hide(5000);
$('#loading_home8').hide(7000);
	WinMove();
	console.log();
	LoadTimePickerScript(AllTimePickers);
	LoadDataTablesScripts(AllTables);
	$("#datatable-5").DataTable({
		"paging":   false,
		"ordering": true,
        "info":     false,
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

$("#datatable-6").DataTable({
		"paging":   false,
		"ordering": true,
        "info":     false,
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
});
</script>
<?php
}
?>
