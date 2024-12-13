<?php
session_start();
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: index.php?error=ER001");
	//exit;
}else{
define("DB_SERVER", "localhost");
define("DB_USER", "24hopenv"); // webmaster_vzla
define("DB_PASS", "GkP6bxloBYcSDMk3"); // w=@,Fb*D
define("DB_NAME", "bd_ebs_bot"); 

$conexion = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); 

if($_SESSION['perfi'] ==2){
	$where = " and id_pro = '".$_SESSION['uid']."'";
}else{
	$where = " and 1 = 1";
}
$mmarca= "SELECT r.*, c.* FROM events r, apps_clientes c 
			where r.id_cliente = c.id ".$where." ";
			//echo $mmarca;
$fecha_p = date("d/m/Y");
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_reservas/list_res.php">Manager Reservas</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Manager Reservas</span>
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
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>ID</th>
							<th>Cliente <i class="fa fa-globe"></i></th>
							<th>Servicio <i class="fa fa-bookmark"></i></th>
							<th>Fecha <i class="fa fa-user"></i></th>
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
							 $sql = "select h.* from apps_marcas h where h.id_marca  = '".$resultado['idh']."' ";
							//echo $sql."<br>";
							$query_sql = mysqli_query($conexion,$sql);
							$resultado2 = mysqli_fetch_assoc($query_sql);
						?>
						<tr>
							<td><?php echo $resultado['id']?></td>
							<td>
							<?php echo utf8_encode($resultado['nombres'])?></td>
							<td>
							<?php echo utf8_encode($resultado2['marca'])?>
							</td>
							<td>
							<?php echo $resultado['start']?>
							</td>
							<td><?php if($resultado['status']==0){
								echo "<div class='alert alert-info'>Registrada";
							}else if($resultado['status']==1){
								echo "<div class='alert alert-success'>Pago Registrado";
							}else if($resultado['status']==2){
								echo "<div class='alert alert-success'>Pago Validado";
							}else if($resultado['status']==3){
								echo "<div class='alert alert-danger'>Pago Rechazado";
							}else if($resultado['status']==4){
								echo "<div class='alert alert-danger'>Cancelada";
							}
							?></div>
							</td>
							<td>
							<?php if($resultado['id_cliente']!=1 && $resultado['status']!=2){?>
							<button type="button" class="btn btn-lg btn-success btn-label-right" onclick="rgistrar_pago(<?php echo $resultado['id_cliente'].",'".$resultado['localizador']."'";?>);">Registrar Pago<span><i class="fa fa-dollar"></i></span></button></td>
							<?php 
							}
							?>
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
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Registrar Pago</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
				<div class="box-content">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Nuevo Pago</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Forma de Pago</label>
							<div class="col-sm-5">
								<select id="tpc" name ="tpc" onchange="tipo_pago();">
								<option value="">----</option>
								<option value="1">Zelle</option>
								<option value="2">Cash</option>
								<option value="3">TDC/TDD</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="banco" style="display:none;">
							<label class="col-sm-3 control-label">Banco</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtbanco" id="txtbanco" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group" id="titular" style="display:none;">
							<label class="col-sm-3 control-label">Titular</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="titular" id="titular" data-toggle="tooltip" data-placement="bottom" title="Quien envia"> 
							</div>
						</div>
						<div class="form-group" id="refere" style="display:none;">
							<label class="col-sm-3 control-label">Referencia</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="ref" id="ref" data-toggle="tooltip" data-placement="bottom" title="Referencia"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Monto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="monto" id="monto" data-toggle="tooltip" data-placement="bottom" title="Monto"> 
							</div>
						</div>
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="id_resv_p" name="id_resv_p" value ="">
						<input type="hidden" id="codigo" name="codigo" value ="">
						<button type="button" class="btn btn-lg btn-success" onclick="save_pago_res();">Update</button>
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
	TestTable1();
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	/*("#i_lun").select2();
	("#f_lun").select2();*/
	 $("select").select2();
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	$('.form-control').tooltip();
	// Add Drag-n-Drop feature
	WinMove();

});
</script>
<?php
}
?>