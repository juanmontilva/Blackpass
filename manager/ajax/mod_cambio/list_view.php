<?php 
session_start();
include("../../dist/funciones/conectarbd.php");
$mmarca= "SELECT p.* FROM `apps_paises` p  where p.status = 1";
$sql_pais = "Select p.* from `apps_paises` p where not exists (select t2.id_pais from apps_control_cambio t2 where t2.id_pais = p.cod)";
$query_u = mysqli_query($con,$sql_pais);
$query_u3 = mysqli_query($con,$mmarca);
$fecha_p = date("d/m/Y");
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_tienda/list_view_tiendas.php">Gestor Control Cambiario</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Gestor Control Cambiario</span>
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
			<button type="button" class="btn btn-lg btn-success btn-label-right" onclick="cambio.add_price_request();">Add <span><i class="fa fa-arrow-circle-right"></i></span></button>
			 </div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>Registro N°</th>
							<th>Pais <i class="fa fa-globe"></i></th>
							<th>Fecha <i class="fa fa-bookmark"></i></th>
							<th>Cambio <i class="fa fa-usd"></th>
							<th>Estatus <i class="fa fa-flag"></i></th>
							<th>Herramientas <i class="fa fa-flag"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$i=0;
						while($resultados = mysqli_fetch_assoc($query_u3)){
							$localid= "select * from apps_control_cambio where id_pais ='".$resultados['cod']."' and status = 1";
							$query_u2 = mysqli_query($con,$localid);
							$resultados2 = mysqli_fetch_assoc($query_u2) ;
							$i++;
							if(mysqli_num_rows($query_u)>0 ){
						?>
						<tr>
							<td><?php echo $i?></td>
							<td>
							<img class="img-rounded" src="<?php echo $resultados['bandera']?>" alt="Cambiar Logo" ><?=utf8_encode($resultados['pais'])?></td>
							<td>
							<?php echo $resultados2['fecha']?>
							</td>
							<td>
							<?php echo number_format($resultados2['monto'],2, ",", ".")?> <?php echo $resultados['moneda']?> x 1USD
							</td>
							<td>Vigente </td>
							<td><button type="button" class="btn btn-lg btn-primary btn-label-right" onclick="cambio.update_price(<?php echo $resultados2['monto']?>,'<?php echo utf8_encode($resultados['pais'])?>','<?php echo $fecha_p?>',<?php echo $resultados['cod']?>);">Update<span><i class="fa fa-bookmark"></i></span></button></td>
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
<div class="modal" id="form_cambio_modal" tabindex="-1" role="dialog" aria-labelledby="form_cambio_modal" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Actualizar Cmbio</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
				<div class="box-content">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Tasa de Cambio para <span id="pais_tasa"></span></legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tasa de Cambio x 1USD</label>
							<div class="col-sm-5">
								<input type="number" class="form-control" name="txtcambio" id="txtcambio" data-toggle="tooltip" data-placement="bottom" title="Cambio"> 
							</div>
						</div>
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="pais_tasa_" name="pais_tasa_" value ="">
							<button type="button" class="btn btn-primary" onclick="cambio.update_price_request();">Update</button>
						</div>
					</div>
				</form>
			</div>
		</div>

      </div>
  </div>
</div>
</div>

<div class="modal" id="form_cambio_modal_add" tabindex="-1" role="dialog" aria-labelledby="form_cambio_modal_add" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Agregar Cambio</h4>
      </div>
      <div class="modal-body">
		<div class="box">
				<div class="box-content">
				<form id="form_add_cambio" name ="form_add_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Tasa de Cambio para </legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tasa de Cambio x 1USD</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtcambio_i" id="txtcambio_i" value = "1" data-toggle="tooltip" data-placement="bottom" title="Cambio"> 
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Pais </legend>
						<div class="form-group">
						<div id="mostrar_error_cam" class="form-group has-error has-feedback"></div>
							<label class="col-sm-3 control-label">Selecciona un Pais</label>
							<div class="col-sm-5">
							
								<select id="sl_pais_c" name = "sl_pais_c">
								<option value=""></option>
								<?php while($resultados3 = mysqli_fetch_assoc($query_u)){?>
								<option value="<?php echo $resultados3['cod']?>"><?php echo$resultados3['pais']?></option>
								<?php
								}
								?>
								</select>
							</div>
						</div>
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="pais_tasa_" name="pais_tasa_" value ="">
							<button type="button" class="btn btn-primary btn-label-right" onclick="cambio.add_price_insert('<?=$fecha_p?>');">Add <span><i class="fa fa-save"></i></button>
						</div>
					</div>
				</form>
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