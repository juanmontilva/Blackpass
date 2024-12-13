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

if($_SESSION['perfi'] ==2){
	$where = " and id_pro = '".$_SESSION['uid']."'";
}else{
	$where = " and 1 = 1";
}

			//echo $mmarca;
$fecha_p = date("d/m/Y");
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="home.php#ajax/mod_marcas/list_view_marcas.php">Registro de Recargas</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Registro de Recargas</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post"  class="form-horizontal">
					<fieldset>
						<legend>Nueva Recarga</legend>
						<div class="form-group" id="titular">
							<label class="col-sm-3 control-label">Numero Tarjeta</label>
							<div class="col-sm-5">
								<input type="text" onblur = "validar_card();" class="form-control" name="tarjeta" id="tarjeta" data-toggle="tooltip" data-placement="bottom" title="Tarjeta"> 
							</div>
						</div>
						<div id="loading" style="margin-top:80px;display:none;margin-left:40%"><img src='img/devoops_getdata.gif'></div>
						<div style="display:none;" id="div_msg"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Forma de Pago</label>
							<div class="col-sm-5">
								<select id="tpc" name ="tpc" class="form-control" onchange="tipo_pago();" disabled>
								<option value="">----</option>
								<option value="1">Zelle</option>
								<option value="2">Efectivo</option>
								<option value="3">TDC/TDD</option>
								<option value="6">Transferencia</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="bancos" style="display:none;">
							<label class="col-sm-3 control-label">Banco</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="banco" id="banco" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group" id="titular_" style="display:none;">
							<label class="col-sm-3 control-label">Titular</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="nombre_c" id="nombre_c" data-toggle="tooltip" data-placement="bottom" title="Quien envia"> 
							</div>
						</div>
						<div class="form-group" id="refere" style="display:none;">
							<label class="col-sm-3 control-label">Referencia</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="refe" id="refe" data-toggle="tooltip" data-placement="bottom" title="Referencia"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Monto</label>
							<div class="col-sm-3">
								<input type="number" disabled class="form-control" name="montos" id="montos" data-toggle="tooltip" data-placement="bottom" title="Monto"> 
							</div>
						</div>
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="id_resv_p" name="id_resv_p" value ="<?php echo cadena();?>">
						<input type="hidden" id="codigo" name="codigo" value ="">
						<input type="hidden" id="ncliente" name="ncliente" value ="">
						<button type="button" id="btn_save" class="btn btn-lg btn-success btn-label-right" onclick="save_pago_res();">Guardar Recarga</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>	
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	TestTable1();
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	/*("#i_lun").select2();
	("#f_lun").select2();*/

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