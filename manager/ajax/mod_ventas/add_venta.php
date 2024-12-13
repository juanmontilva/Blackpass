<?php 
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
$_SESSION['perfi'] = $_SESSION['perfi'];
$_SESSION['uid'] = $_SESSION['uid'];

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

$mmarca= mysqli_query($con,"SELECT p.pais,m.marca,p.bandera ,mp.id_mp FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m  WHERE 
							mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod 
							and mp.status = 1");
$paises= mysqli_query($con,"SELECT p.* FROM `apps_provincias` pv, apps_paises p WHERE p.cod = pv.`id_pais` 
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
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>ID</th>
							<th>Pais <i class="fa fa-globe"></i></th>
							<th>Marca <i class="fa fa-bookmark"></i></th>
							<th>Tiendas <i class="fa fa-flag"></i></th>
							<th>Facturado <i class="fa fa-usd"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$i=0;
						while($resultados = mysqli_fetch_assoc($mmarca)){
							$localid= mysqli_query($con,"select count(*) as cantidad from apps_tiendas where id_mp = '".$resultados['id_mp']."'");
							$resultados2 = mysqli_fetch_assoc($localid);
							$i++;
							$fatu= $obj->consultar("select sum(total) as facturado from apps_ventas_d where id_marca= '".$resultados['id_mp']."'");
							$resultados3 = mysqli_fetch_assoc($fatu);
							if($resultados2['cantidad']>0){
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td>
							<img class="img-rounded" src="<?php echo $resultados['bandera'];?>" alt="Cambiar Logo" ><?php echo utf8_encode($resultados['pais']);?></td>
							<td>
							<?php if($resultados2['cantidad']>0){?>
							<a class="ajax-link" style="cursor:pointer" onclick="vent.carga_tiendas(<?php echo $resultados['id_mp'];?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" >
							<?php echo $resultados['marca'];?>
							</a>
							<?php }else{
								echo $resultados['marca'];
							}?>
							</td>
							<td>
							<?php if($resultados2['cantidad']>0){?>
							<a class="ajax-link" style="cursor:pointer" onclick="vent.carga_tiendas(<?php echo $resultados['id_mp'];?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" >
							<?php echo $resultados2['cantidad'];?>
							</a>
							<?php }else{
								echo $resultados2['cantidad'];
							}?>
							</td>
							<td><?php echo number_format($resultados3['facturado'],2, ",", ".");?> Bs</td>
						</tr>
						<?php }}?>
					
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

<?php 
}
?>