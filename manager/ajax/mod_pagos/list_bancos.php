<?php
session_start();
define("DB_SERVER", "209.126.119.214");
define("DB_USER", "itech_california"); // webmaster_vzla
define("DB_PASS", "california20*"); // w=@,Fb*D
define("DB_NAME", "itech_california"); 

$conexion = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); 


$mmarca= "SELECT * FROM apps_bancos WHERE status= '1'";
			//echo $mmarca;
$fecha_p = date("d/m/Y");
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
			<button type="button" class="btn btn-success btn-label-right" onclick="cambio.add_price_request();">Add <span><i class="fa fa-arrow-circle-right"></i></span></button>
			 </div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>ID</th>
							<th>Banco <i class="fa fa-globe"></i></th>
							<th>Cuenta <i class="fa fa-bookmark"></i></th>
							<th>Titular <i class="fa fa-user"></i></th>
							<th>Identi <i class="fa fa-user"></i></th>
							<th>Tipo <i class="fa fa-usd"></th>
							<th>Medio <i class="fa fa-flag"></i></th>
							<th>Status</th>
							<th>Herramientas <i class="fa fa-flag"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$query_u = mysqli_query($conexion,$mmarca);
						if(mysqli_num_rows($query_u)!=0){
						 while($resultado = mysqli_fetch_assoc($query_u) ){
						?>
						<tr>
							<td><?php echo $resultado['id']?></td>
							<td>
							<?php echo utf8_encode($resultado['banco'])?></td>
							<td>
							<?php echo $resultado['cuenta']?>
							</td>
							<td>
							<?php echo $resultado['titular']?>
							</td>
							<td>
							<?php echo $resultado['identificacion']?>
							</td>
							<td><?php if($resultado['tipo']==1){
								echo "Transferencia";
							}else{
								echo "PagoMovil";
							}?></td>
							<td><?php if($resultado['tipo_c']=='A'){
								echo "Ahorro";
							}else{
								echo "Corriente";
							}?></td>
							<td><?php if($resultado['status']==1){
								echo "Activa";
							}else{
								echo "Suspendida";
							}?></td>
							
							<td><button type="button" class="btn btn-primary btn-label-right" onclick="cambio.update_price(<?php $resultado['id']?>,'<?=utf8_encode($resultado['banco'])?>');">Update<span><i class="fa fa-bookmark"></i></span></button></td>
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
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Agregar Forma Pago</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
				<div class="box-content">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Nuevo Registro</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtcambio" id="txtcambio" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Titular</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="titular" id="titular" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Cuenta</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="banco" id="banco" data-toggle="tooltip" data-placement="bottom" title="N° Cuenta"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tipo Cuenta</label>
							<div class="col-sm-5">
								<select id="tpc" name ="tpc">
								<option value="A">Ahorros</option>
								<option value="C">Corriente</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Identificación</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="cedrif" id="cedrif" data-toggle="tooltip" data-placement="bottom" title="J-122345"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Telefono</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="cel" id="cel" data-toggle="tooltip" data-placement="bottom" title="Telefeono"> 
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
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Agregar Medio</h4>
      </div>
      <div class="modal-body">
		<div class="box">
				<div class="box-content">
				<form id="form_add_cambio" name ="form_add_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Nuevo Registro</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtcambio" id="txtcambio" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Titular</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="titular" id="titular" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Cuenta</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="banco" id="banco" data-toggle="tooltip" data-placement="bottom" title="N° Cuenta"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tipo Cuenta</label>
							<div class="col-sm-5">
								<select id="tpc" name ="tpc">
								<option value="A">Ahorros</option>
								<option value="C">Corriente</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Identificación</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="cedrif" id="cedrif" data-toggle="tooltip" data-placement="bottom" title="J-123456789" placeholder="J-12345679"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Telefono</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="cel" id="cel" data-toggle="tooltip" data-placement="bottom" title="Telefeono"> 
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Tipo Pago </legend>
						<div class="form-group">
						<div id="mostrar_error_cam" class="form-group has-error has-feedback"></div>
							<label class="col-sm-3 control-label">Selecciona un Pais</label>
							<div class="col-sm-5">
							
								<select id="sl_pais_c" name = "sl_pais_c">
								<option value="0">Transferencia</option>
								<option value="1">PagoMovil</option>
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

