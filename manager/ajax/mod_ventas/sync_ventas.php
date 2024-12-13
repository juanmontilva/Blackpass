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

$mmarca= $obj->consultar("SELECT p.pais,m.marca,p.bandera ,mp.id_mp FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m  WHERE 
							mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod 
							and mp.status = 1");
$paises= $obj->consultar("SELECT p.* FROM `apps_provincias` pv, apps_paises p WHERE p.cod = pv.`id_pais` 
							and p.status = 1 group by cod  order BY p.pais ASC");

?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_tienda/list_view_tiendas.php">Manager Tiendas</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Manager Tiendas</span>
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
				    <div class="panel-body">
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="border-collapse:collapse;">
			

							<tbody>
							<?
								$k=0;
								
								while($resul2 = $obj->obtener_fila($mmarca,0)){
									$k2=0;
									$mdeta= $obj->consultar("select * from apps_tiendas where status = 1 and id_mp = '".$resul2['id_mp']."' ");
									$k++;?>
							<tr data-toggle="collapse" data-target="#demo<?=$k?>" class="accordion-toggle">
								<td><button class="btn btn-success btn-xs"><i class="fa fa-eye-slash"></span></button></td>
								<td><b><?=utf8_encode($resul2['marca']." ".$resul2['pais'])?> </b></td>

							</tr>
							<tr>
								<td colspan="3" class="hiddenRow">
								<div id="demo<?=$k?>" class="accordian-body collapse">
								<fieldset>
								<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
									<thead>
										<tr>
											<th>#</th>
											<th>Tienda</th>
											<th>Activar Sync</th>
										</tr>
									</thead>
									<tbody>		
									<legend><b><?=utf8_encode($resul2['marca'])?>  <img class="img-rounded" src="<?=$resul2['bandera']?>" alt="<?=$resul2['pais']?>" ></b></legend>			
									<?while($resul3 = $obj->obtener_fila($mdeta,0)){
										$k2++;?>
										<tr>
											<td><?=$k2?></td>
											<td><?echo $resul3['nombre_tienda'];?></td>
											<td>
											<div class="toggle-switch toggle-switch-success">
												<label>
													<input type="checkbox" 	id="id_tienda" name="id_tienda" value="<?=$resul3['id_tienda']?>">
													<div class="toggle-switch-inner"></div>
													<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
												</label>	
											</div></td>
										</tr>
									<?}?>
									
									</tbody>
								</table>			
								</fieldset>
								</div>
								
								</td>
							</tr>
							<?}?>
							</tbody>
							</table>
						</div>
			</div>
		</div>
	</div>
</div>





<!-- fin modal logo marca-->
<script type="text/javascript">
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
	// Load TimePicker plugin and callback all time and date pickers
	LoadTimePickerScript(AllTimePickers);
	// Create jQuery-UI tabs
	$("#tabs").tabs();
	var icons = {
		header: "ui-icon-circle-arrow-e",
		activeHeader: "ui-icon-circle-arrow-s"
	};
	$("#accordion").accordion({icons: icons });
	// Make accordion feature of jQuery-UI
	// Create UI spinner
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes
	LoadDataTablesScripts(AllTables);
	WinMove();
});
</script>

<?
}
?>