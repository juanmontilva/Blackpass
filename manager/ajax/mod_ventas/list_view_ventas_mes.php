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
$marcaid = $_GET['ubicar'];
$mmarca= $obj->consultar("SELECT p.pais,p.moneda,m.marca,p.bandera ,mp.id_mp FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m  WHERE 
							mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod and mp.id_mp = '".$marcaid."'");
$resultados = $obj->obtener_fila($mmarca,0);
$paises= $obj->consultar("SELECT MONTH(FECHA) AS MES,YEAR(FECHA) AS YEAR,SUM(`total`) as TOTAL, SUM(`iva`) AS IVA,SUM(`bruto`) AS BRUTO 
							FROM `apps_ventas_d` WHERE `id_marca` = '".$marcaid."' GROUP BY MONTH(FECHA),YEAR(FECHA) 
							ORDER BY YEAR(FECHA) ,MONTH(FECHA) ASC ");

?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#" onclick="vent.url_ventas();">Ventas Registradas</a></li>
			<li><a href="home.php#ajax/mod_ventas/list_view_ventas_mes.php">Ventas Mensual <?=$resultados['marca']?></a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Detalle de Ventas Mensual para  <strong><?=$resultados['marca']?></strong> <img class="img-rounded" src="<?=$resultados['bandera']?>" alt="Cambiar Logo" ></span>
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
							<th>Año</th>
							<th>Mes</th>
							<th>Bruto<i class="fa fa-globe"></i></th>
							<th>IVA <i class="fa fa-bookmark"></i></th>
							<th>Neto <i class="fa fa-flag"></i></th>
							<th> <i class="fa fa-cof"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<? 
						$i=0;
						while($resultados2 = $obj->obtener_fila($paises,0)){
							
						?>
						<tr>
							<td><?=$resultados2['YEAR']?></td></td>
							<td>
							<?=$resultados2['MES']?></td>
							<td>
							<a class="ajax-link" style="cursor:pointer" onclick="vent.carga_ventas_tien_mes(<?=$resultados['id_mp']?>,'<?=$resultados2['YEAR']?>','<?=$resultados2['MES']?>');"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" >
							<?=number_format($resultados2['BRUTO'],2, ",", ".")?> <?=$resultados['moneda']?>
							</a>
							</td>
							<td>
							<a class="ajax-link" style="cursor:pointer" onclick="vent.carga_ventas_tien_mes(<?=$resultados['id_mp']?>,'<?=$resultados2['YEAR']?>','<?=$resultados2['MES']?>');"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" >
							<?=number_format($resultados2['IVA'],2, ",", ".")?> <?=$resultados['moneda']?>
							</a>
							</td>
							<td><?=number_format($resultados2['TOTAL'],2, ",", ".")?> <?=$resultados['moneda']?></td>
							<td><button type="button" class="btn btn-default btn-danger" onclick="vent.borrar_mes(<?=$resultados['id_mp']?>,'<?=$resultados['marca']?>','<?=$resultados2['YEAR']?>','<?=$resultados2['MES']?>');">Borrar Ventas <i class="fa fa-trash-o"></i></span></button></td>
						</tr>
					<?}?>
					<!-- End: list_row -->
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>
<!-- fin modal logo marca-->
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
	WinMove();
});
</script>
<?
}
?>