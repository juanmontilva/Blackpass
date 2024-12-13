<?php

session_start();
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../../index.php?error=ER001");
	//exit;
}else{
include("../../dist/funciones/conectarbd.php");
$_SESSION['perfi'] = $_SESSION['perfi'];
$_SESSION['uid'] = $_SESSION['uid'];

/*$consulta = $obj->consultar("select `apps_cargo`.`cargo`  from `apps_user`, `apps_cargo`  
where `apps_cargo`.`id_cargo` = `apps_user`.`id_cargo` and `apps_user`.`id_user` = '".$_SESSION['uid']."'");*/


$mmarca= "select * from apps_marcas WHERE id_marca<> '105' order BY marca ASC";
$sql_p = "SELECT * FROM apps_localidades WHERE id_pais = 1 ";
$query_u = mysqli_query($con,$mmarca);
$query_p = mysqli_query($con,$sql_p);
?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="home.php#ajax/mod_marcas/list_view_marcas.php">Gestor de Productos</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Gestor de Productos</span>
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
			<div style="margin-left:87%">
			<button type="button" class="btn btn-lg btn-info btn-label-right" onclick="marca.new_marc();">Nuevo Producto<span><i class="fa fa-plus-square"></i></span></button>
			 </div>
				<?php }?>
			 <br>
				<table width="100%" class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Tarjetas</th>
							<th>Localidad</th>
							<th>Estatus</th>
							<th>Acción</th>

						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$i=0;
						
						$row ="";
						$logo = "";
						while($row = mysqli_fetch_array($query_u))
						{ 
			 	
						$i++;
						$sqlpai = "SELECT count(id_marca) as cantidad FROM apps_servicios_d WHERE id_marca = '".$row['id_marca']."'" ;
						$query_ = mysqli_query($con,$sqlpai);
						$row2 = mysqli_fetch_array($query_);
						if( $row['status']==1){
							$estado = "Activa";
						}else{
							$estado = "Inactiva";
						}
						if( $row['logo']=="" || $row['logo']==null){
							$logo= "img/50_50.png";
						}else{
							$logo= "ajax/mod_marcas/files/".$row['logo'];
						}
						?>
						<tr>
							<td><a  style="cursor:pointer"  alt="Cambiar Logo" onclick="marca.logo('<?php echo$row['id_marca']?>');"><img class="img-rounded" src="<?php echo$logo?>" alt="Cambiar Logo" ></a><?php echo utf8_encode($row['marca']);?></td>
							<td><?php if($row2['cantidad']>0){
									if($row['status']==1){ 
									?>
									<a class="btn btn-lg btn-info btn-label-center" class="ajax-link" style="cursor:pointer" onclick="marca.carga_hab(<?php echo $row['id_marca'];?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" >
							<?php echo $row2['cantidad'];?>
							</a><?php 
									}
									if($row['status']==0){ 
									echo $row2['cantidad'];
									}
							}
							if($row2['cantidad']==0){
								echo $row2['cantidad'];
							}
							?>
							</td>
							<td width="15%"><?php 
								$sql_2 = "SELECT l.*, mp.id_marca,mp.status as estado FROM apps_localidades l,apps_marcas_x_pais mp
								WHERE l.id_loc = mp.id_pais and id_marca = '".$row['id_marca']."' ";
								$query_2 = mysqli_query($con,$sql_2);
								while($row4 = mysqli_fetch_array($query_2))
								{ 
								if($row4['estado']==0)$estatus_l = 1;else $estatus_l = 0;
								?>
								<div class="form-group">
								<div style="float:left;"><?php echo $row4['localidad'];?>  </div>
								<div  style="float:left;"class="toggle-switch toggle-switch-success">
								
									<label>
										<input onclick="marca.detect_chck(<?php echo $row4['id_marca'];?>,<?php echo $row4['id_loc'];?>,<?php echo $estatus_l;?>);" type="checkbox" <?php if($row4['estado'] ==1) echo 'checked';?>>
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
								</div>
								<?php
								echo "<br>";
								
								}
								?> 
							</td>
							<td><?php
								if($row['status']==1){?>
								<div class="alert alert-success">
								<?php }else{ ?>
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
											<li><a onclick="marca.edit_marc('<?php echo $row['id_marca']?>');"><i class="fa fa-pencil fa-fw"></i> Editar</a></li>
											<li><a href="#" onclick="marca.delet_marc('<?php echo$row['id_marca']?>');"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a></li>
											<li><a  onclick="marca.status_marc('<?php echo$row['id_marca']?>');"><?php if($row['status']==1){?><i class="fa fa-ban fa-fw"></i> Desactivar<?php } else{?><i class="fa fa-check-square-o"></i> Activar<?php } ?></a></li>
										</ul>
									</div>
							<?php }
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
<div class="modal" id="form_sms_modal" tabindex="-1" role="dialog" aria-labelledby="form_sms_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_sms_modal"><i class="fa fa-bookmark"></i> Nuevo Producto</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
				<div class="box-content">
				<form id="form_add_marcas" name ="form_add_marcas"  action="validar.html" method="post"  class="form-horizontal">
					<fieldset>
						<legend>Datos de Registro</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nombre Producto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" required name="txtmarca" id="txtmarca" onclick="marca.id_marca();"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Identificador de Producto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtidmarca" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"   maxlength="3" id="txtidmarca" data-toggle="tooltip" data-placement="bottom" title="Identificador"> 
								<label>
								<div id="sugerencias" style="display:none"></div>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Cantidad Tarjetas</label>
							<div class="col-sm-5">
								<input type="number" class="form-control" name="cantidad" id= "cantidad"  />

							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tipo Producto</label>
							<div class="col-sm-5">
								<select class="populate placeholder"   name="tipo_p" id="tipo_p"  onfocus="this.selectedIndex = -1;">
									<option value="1">Xpress</option>
									<option value="2">Corporativo</option>
								</select> 

							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Ubicaciones</legend>
							<div class="form-group">
							<label class="col-sm-3 control-label">Ubicación</label>
							<div class="col-sm-5">
								<select class="populate placeholder" multiple  name="s2_local" id="s2_local"  onfocus="this.selectedIndex = -1;">
									<option value="">-- Selecciona las Ubicaciones --</option>
									<?php 
									$i=0;
									while($resultados3 = mysqli_fetch_array($query_p)){
									?>
										<option  value="<?php echo $resultados3['id_loc'];?>"><?php echo utf8_encode($resultados3['localidad']);?></option>
									<?php
									}?>
								</select> 
							</div>
							<div id="mostrar_error_p"></div>
						</div>
								<input type="hidden" class="form-control" name="accion" id= "accion" value="add" />
								<input type="hidden" class="form-control" name="idmarca" id= "idmarca" value="add" />
						
					</fieldset>

					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<button type="button" id="btn_sx" class="btn btn-lg btn-success btn-label-right" onclick="marca.save_marc();">Guardar<span><i class="fa fa-save"></i></span></button>
						</div>
					</div>
					<div id="loading_tc" style="margin-top:80px;display:none;margin-left:40%"><img src='img/devoops_getdata.gif'></div>
				</form>
			</div>
			</div>
		</div>

      </div>
  </div>
</div>
</div>


<!--Modal Logo Marca-->
<div class="modal" id="form_logo_modal" tabindex="-1" role="dialog" aria-labelledby="form_logo_modal" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_logo_modal"><i class="fa fa-bookmark"></i> Logo</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
						<p></p>
						<div class="alert alert-primary">
						<p>La imagen debe medir 500px por 500px Máximo</p>
						</div>
						<div  class="box-content">
							<form method="post" id="formulario" enctype="multipart/form-data">
							<legend>Cargar Logo</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">Logo</label>
								<div class="col-sm-12">
								<input type="file" name="file" id="file">
								<input type="hidden" id="marcaid" name="marcaid">
								</div>
								<br><br>
							</div>
							</form>
						  <div id="respuesta"></div>
						</div>
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	<button type="button" class="btn btn-lg btn-primary btn-label-right" onclick="marca.save_logos();">Asignar Logo<span><i class="fa fa-save"></i></span></button>
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
function DemoSelect2(){
	$('#s2_pais').select2({placeholder: "Select Países"});
}
function MakeSelect2(){
	$('select').select2();
	
}
$(document).ready(function() {
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
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	LoadSelect2Script(DemoSelect2);
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	$("#tabs").tabs();
	// Sortable for elements
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes	
	
	 
 //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })
});

$(function(){
        $("input[name='file']").on("change", function(){
            var formData = new FormData($("#formulario")[0]);
            var ruta = "ajax/mod_marcas/add_logo.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta").html(datos);
                }
            });
        });
     });       
</script>
<?php
}
?>
