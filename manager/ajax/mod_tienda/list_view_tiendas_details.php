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
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="#" onclick="tien.refresh_list_tiendas();">Manager Tiendas</a></li>
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
							<th>Estatus <i class="fa fa-flag"></i></th>
							<th><i class="fa fa-cog"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<? 
						$i=0;
						while($resultados = $obj->obtener_fila($mmarca,0)){
							//$localid= $obj->consultar("select count(*) as cantidad from apps_tiendas where id_mp = '".$resultados['id_mp']."'");
							//$resultados2 = $obj->obtener_fila($localid,0);
							$i++;
						?>
						<tr>
							<td><?=$i?></td>
							<td><?=utf8_encode($resultados['razon_social'])?></td>
							<td><?=utf8_encode($resultados['nombre_tienda'])?></td>
							<td><?=$resultados['Status']?>
							</td>
							<td><div class="btn-group">
										<a class="btn btn-default" href="#"><i class="fa fa-gear"></i> Acciones</a>
										<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
										<span class="fa fa-caret-down"></span></a>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="tien.edit_tienda('<?=$resultados['id_tienda']?>');"><i class="fa fa-pencil fa-fw"></i> Edit</a></li>
											<li><a href="#" onclick="tien.delet_tienda('<?=$resultados['id_tienda']?>');"><i class="fa fa-trash-o fa-fw"></i> Delete</a></li>
											<li><a href="#" onclick="tien.status_tienda('<?=$resultados['id_tienda']?>');"><?if($resultados['Status']=="Activa"){?><i class="fa fa-ban fa-fw"></i> Ban<?}else{?><i class="fa fa-check-square-o"></i> Act<?}?></a></li>
										</ul>
									</div>
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
<div class="modal" id="form_tienda_modal" tabindex="-1" role="dialog" aria-labelledby="form_tienda_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_tienda_modal"><i class="fa fa-globe"></i> Nueva Tienda</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
						<div class="box-content">
						<div id="cargar"></div>
						</div>
			</div>
		</div>

      </div>
  </div>
</div>
</div>

<!-- fin modal logo marca-->
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	$('select').select2();
	
}
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	$('.form-control').tooltip();
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	// Sortable for elements
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes	
});
</script>

<?
}
?>