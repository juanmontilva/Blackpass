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
$mmarca= "SELECT r.*, c.nombres,c.id,c.plan FROM apps_pagos r, apps_clientes c 
			where r.idc = c.id ";
		//	echo $mmarca;
$fecha_p = date("d/m/Y");
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="home.php#ajax/mod_reservas/list_res.php">Gestor de Pagos</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Gestor de Pagos</span>
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
			<br>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
					<thead>
						<tr>
							<th>Registro N°</th>
							<th>Cliente <i class="fa fa-globe"></i></th>
							<th>Membresia <i class="fa fa-bookmark"></i></th>
							<th>Monto <i class="fa fa-dollar"></i></th>
							<th>Fecha <i class="fa fa-user"></i></th>
							<th>Status</th>
							<th>Herramientas <i class="fa fa-flag"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
					$j=0;
						$query_u = mysqli_query($con,$mmarca);
						if(mysqli_num_rows($query_u)!=0){
						 while($resultado = mysqli_fetch_assoc($query_u) ){
							 $j++;
							 $sql = "select h.* from apps_marcas h where h.id_marca  = '".$resultado['plan']."' ";
							$query_sql = mysqli_query($con,$sql);
							$resultado2 = mysqli_fetch_assoc($query_sql);
						?>
						<tr>
							<td><?php echo $j?></td>
							<td>
							<?php echo ($resultado['nombres'])?></td>
							<td>
							<?php echo utf8_encode($resultado2['marca'])?>
							</td>
							<td>
							<?php echo utf8_encode($resultado['monto'])?>
							</td>
							<td>
							<?php echo $resultado['fecha']?>
							</td>
							<td><?php if($resultado['status']==0){
								echo "<div class='alert alert-info'>Rechazada";
							}else if($resultado['status']==1){
								echo "<div class='alert alert-info'>Por Validar";
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
							<?php if($resultado['idc']!=1 && $resultado['status']!=2){?>
							<button type="button" class="btn btn-lg btn-success btn-label-right" onclick="rgistrar_pago(<?php echo $resultado['idc'].",'".$resultado['orden']."'";?>,0);">Registrar Pago<span><i class="fa fa-dollar"></i></span></button></td>
							<?php 
							}else{
							?>
							<button type="button" class="btn btn-lg btn-info btn-label-right" onclick="rgistrar_pago(<?php echo $resultado['idc'].",'".$resultado['orden']."'";?>,1);">Ver<span><i class="fa fa-dollar"></i></span></button></td>
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
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Validar Pago</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
				<div class="box-content">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<div id="form_p" style="display:none;">
						<legend>Validar Recarga</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco Origen</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="borigen" readonly id="borigen" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco Destino</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="bdestino" readonly id="bdestino" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						</div>
						<div id="form_p3" style="display:none;">
						<div class="form-group">
							<label class="col-sm-3 control-label">Contacto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="contac" readonly id="contac" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Dirección</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="dir3" readonly id="dir3" data-toggle="tooltip" data-placement="bottom" title="dir3"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Telefono</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="phone" readonly id="phone" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Hora</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="hora" readonly id="hora" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						</div>
						<div id="form_p2" style="display:none;">
					
						<div class="form-group">
							<label class="col-sm-3 control-label">Referencia</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="referencia" readonly id="referencia" data-toggle="tooltip" data-placement="bottom" title="referencia"> 
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Monto</label>
							<div class="col-sm-5">
								<input type="number" class="form-control" name="montos" id="montos" readonly data-toggle="tooltip" data-placement="bottom" title="J-122345"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Localizador</label>
							<div class="col-sm-5">
								<input type="text" readonly class="form-control" name="localizador" id="localizador" data-toggle="tooltip" data-placement="bottom" title="Telefeono"> 
							</div>
						</div>
					</div>
						
						
						
					</fieldset>
					<div class="form-group" id="botonera">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="clientexy" name="clientexy" value ="">
							<button type="button" class="btn btn-primary" onclick="validar_p(2);">Validar</button>
							<button type="button" class="btn btn-danger" onclick="validar_p(3);">Rechazar</button>
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
		$("#datatable-5").DataTable({
		"paging":   true,
		"ordering": true,
        "info":     true,
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