<?php
session_start();
include("../../dist/funciones/conectarbd.php");
$_SESSION['perfi'] = $_SESSION['perfi'];
$_SESSION['uid'] = $_SESSION['uid'];
/*$consulta = $obj->consultar("select `apps_cargo`.`cargo`  from `apps_user`, `apps_cargo`  
where `apps_cargo`.`id_cargo` = `apps_user`.`id_cargo` and `apps_user`.`id_user` = '".$_SESSION['uid']."'");*/

$idm = $_GET['ubicar'];
$mmarca= "SELECT dh.*, h.marca FROM apps_servicios_d dh, apps_marcas h WHERE 
						h.id_marca = dh.id_marca and dh.id_marca = $idm 
						order by status desc";
						//echo $mmarca;
$query_u = mysqli_query($con,$mmarca);
$mmarca2= "SELECT  h.* FROM  apps_marcas h WHERE 
						h.id_marca = $idm ";
						//echo $mmarca;
$query_m = mysqli_query($con,$mmarca2);
$row_m = mysqli_fetch_array($query_m);
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a onclick="marca.refresh_list_clients();">Tarjetas</a></li>
			<li><?php echo $row_m['marca'];?></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span><?php echo $row_m['marca'];?></span>
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
			<?php if($_SESSION['perfi']==1){?>
			<div style="margin-left:77%">
			<button type="button" class="btn btn-lg btn-info btn-label-right" onclick="marca.new_servi(<?php echo $row_m['id_marca'];?>);">Adicional Tarjetas <?php echo $row_m['marca'];?><span><i class="fa fa-plus-square"></i></span></button>
			 </div>
			<?php }?>
			 <br>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
					<thead>
						<tr>
							<th>ID</th>
							<th>SERIAL (5 ULTIMOS)<i class="fa fa-globe"></i></th>
							<th>Cliente <i class="fa fa-bookmark"></i></th>
							<th>Estatus <i class="fa fa-flag"></i></th>
							<th>Acciones</th>
							
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php
						$i=0;
						while($row = mysqli_fetch_array($query_u))
						{
							$i++;
							$cliente = "";
							$fatu= "select nombres from apps_clientes where id_cd= '".$row['cod']."'";
							$query_u2 = mysqli_query($con,$fatu);
							if(mysqli_num_rows($query_u2)!=0){
								$resultados3 = mysqli_fetch_array($query_u2);
								$cliente = $resultados3['nombres'];
							}else{
								$cliente = "NO ASIGNADA";
							}
							
							
							
							if( $row['status']==2){
							$estado = "Asignada";
							}else if( $row['status']==1){
								$estado = "Sin Asignar";
							}else{
								$estado = "Bloqueada";
							}
						?>
						<tr>
							<td><?php echo $i?></td>
							<td><?php echo $row['color']?></td>
							<td> <?php echo $cliente;?> </td>
							<td><?php
								if($row['status']==2){?>
								<div class="alert alert-success">
								<?php }
								if($row['status']==1){
								?>
								<div class="alert alert-warning">
								<?php
								}if($row['status']==0){ ?>
								<div class="alert alert-danger">
								<?php
									}
									echo $estado?>
								</div>
								</td>
							<td>
							<?php if($_SESSION['perfi']==1){?>
							<div class="btn btn-lg btn-group">
										<a class="btn btn-lg btn-default" href="#"><i class="fa fa-gear"></i> Acciones</a>
										<a class="btn btn-lg btn-success dropdown-toggle" data-toggle="dropdown" href="#">
										<span class="fa fa-caret-down"></span></a>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="marca.delet_servi('<?php echo$row['idh']?>');"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a></li>
											<li><a  onclick="marca.status_servi('<?php echo$row['idh']?>');"><?php if($row['status']==1 || $row['status']==2){?><i class="fa fa-ban fa-fw"></i> Bloquear<?php } else{?><i class="fa fa-check-square-o"></i> Activar<?php } ?></a></li>
										</ul>
							</div>
							<?php 
							}
							?>
							</td>
						</tr>
							<?php
							}
							?>
					
					<!-- End: list_row -->
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="form_servi_modal" tabindex="-1" role="dialog" aria-labelledby="form_servi_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_servi_modal"><i class="fa fa-globe"></i><?php echo $row_m['marca'];?></h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
							<form id="form_add_servi" name ="form_add_servi"  action="validar.html" method="post"  class="form-horizontal">
					<fieldset>
						<legend>Datos de Registro</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Cantidad</label>
							<div class="col-sm-5">
								<input type="number" class="form-control" required name="txtmarca" id="txtmarca"   style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
							</div>
						</div>
						<!--<div class="form-group">
							<label class="col-sm-3 control-label">Identificador Servicio</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" readonly name="txtidmarca" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"   maxlength="3" id="txtidmarca" data-toggle="tooltip" data-placement="bottom" title="Identificador"> 
								<label>
								<div id="sugerencias" style="display:none"></div>
								</label>
							</div>
						</div>-->
						<div style="display:none;" class="form-group">
							<label class="col-sm-3 control-label">Tarifa</label>
							<div class="col-sm-5">
								<input type="number" value="1" class="form-control"    name="descrip" id="descrip">
							</div>
						</div>
						<div style="display:none;" class="form-group">
							<label class="col-sm-3 control-label">Tarifa</label>
							<div class="col-sm-5">
							<input type="number" class="form-control" name="tarifa" id= "tarifa"/>
								<input type="hidden" class="form-control" name="accion" id= "accion" value="add_s" />
								<input type="hidden" class="form-control" name="idmarca" id= "idmarca" value="add" />
							</div>
						</div>
					
					</fieldset>

					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<button type="button" class="btn btn-lg btn-success btn-label-right" onclick="marca.save_serv();">Guardar<span><i class="fa fa-save"></i></span></button>
						</div>
					</div>
				</form>
			</div>
		</div>

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

