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
$idpais= mysqli_real_escape_string($con,(strip_tags($_GET["ubicar"],ENT_QUOTES)));//Escanpando caracteres ($_GET['ubicar']);
$mmarca= mysqli_query($con,"SELECT p.pais,pv.nombreprovincia,l.id_loc,l.localidad,l.status, l.numero 
						 FROM `apps_localidades` l, apps_paises p, apps_provincias pv 
						WHERE p.cod = l.id_pais and l.id_provincia = pv.codprovincia 
						and p.cod = '".$idpais."'  order by pv.nombreprovincia,localidad asc ");
$paises= mysqli_query($con,"SELECT p.* FROM `apps_provincias` pv, apps_paises p WHERE p.cod = pv.`id_pais` 
							and p.status = 1 group by cod  order BY p.pais ASC");						

?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="#" onclick="locali.carga_pais();">Manager Localidades</a></li>
			<li><a href="#">Listado Ubicaciones</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-flag"></i>
					<span>Gestor Ubicación</span>
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
			 <button type="button" class="btn btn-lg btn-info btn-label-right" onclick="locali.new_local();">Nueva Ubicación<span><i class="fa fa-bookmark"></i></span></button>
			 </div>
			<?php }?>
			 <br>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
					<thead>
						<tr>
							<th>Registro N°</th>
							<th>Comercio <i class="fa fa-globe"></i></th>
							<th>Ubicación <i class="fa fa-flag"></i></th>
							<th>Whatsaap <i class="fa fa-building-o"></i></th>
							<th>Estatus <i class="fa fa-band"></i></th>
							<th><i class="fa fa-cog"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$i=0;
						while($resultados = mysqli_fetch_assoc($mmarca)){
							$localid= mysqli_query($con,"select count(*) as cantidad from apps_tiendas where id_localidad = '".$resultados['id_loc']."'");
							$resultados2 = mysqli_fetch_assoc($localid);
							$i++;
							if( $resultados['status']==1){
							$estado = "Activa";
							}else{
								$estado = "Inactiva";
							}
						?>
						<tr>
							<td><?php echo $i?></td>
							<td><?php echo $resultados['pais']?></td>
							<td><?php echo utf8_encode($resultados['localidad'])?></td>
							<td><?php echo $resultados['numero'] ?></td>
							<td><?php
								if($resultados['status']==1){?>
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
										<li><a onclick="locali.edit_cc('<?php echo $resultados['id_loc']?>');"><i class="fa fa-pencil fa-fw"></i> Editar</a></li>
										<li><a href="#" onclick="locali.del_cc('<?php echo $resultados['id_loc']?>');"><i class="fa fa-trash-o fa-fw"></i> Eliminar</a></li>
										<li><?php  if($resultados['status']==0){?><a  onclick="locali.status_cc('<?php echo $resultados['id_loc']?>',1);"><i class="fa fa-check-square-o"></i> Activar<?php } else{?><a  onclick="locali.status_cc('<?php echo $resultados['id_loc']?>',0);"><i class="fa fa-ban fa-fw"></i> Desactivar<?php } ?></a></li>
									</ul>
							</div>
							<?php }?>
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
<div class="modal" id="form_loca_modal" tabindex="-1" role="dialog" aria-labelledby="form_loca_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_loca_modal"><i class="fa fa-globe"></i> Nueva Ubicación</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
				<form id="defaultForm"  action="validar.html" method="post"  class="form-horizontal">
					<fieldset>
						<legend>Ubicacion</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Comercio</label>
							<div class="col-sm-5">
								<select class="populate placeholder"  name="s2_country" id="s2_country"  onfocus="this.selectedIndex = -1;">
									<option value="">-- Selecciona el Comercio--</option>
									<?php 
									$i=0;
									while($resultados3 = mysqli_fetch_assoc($paises)){
									?>
										<option  value="<?php echo $resultados3['cod'];?>"><?php echo utf8_encode($resultados3['pais']);?></option>
									<?php
									}?>
								</select> 
							</div>
							<div id="mostrar_error_p"></div>
						</div>
						<div  style="display:none;"  class="form-group">
							<label class="col-sm-3 control-label">Estado</label>
							<div class="col-sm-5">
								<select  class="populate placeholder" name="sl_ciudades" id="sl_ciudades">							 
								 <div id="subcategory">
									<option value="48">-- Select a Estado --</option>
								</div>	
								</select>
							</div>
							<div id="mostrar_error"></div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Información</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nombre de la Ubicación</label>
							<div class="col-sm-5">
								<input type="text" id="localidad" name="localidad" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  placeholder="Centro Comercial / Mall" data-toggle="tooltip" data-placement="bottom" title="Centro Comercial / Mall">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Numero Whatsapp</label>
							<div class="col-sm-5">
								<input type="text" id="txtnumero" name="txtnumero" class="form-control" style="text-transform:uppercase;"   placeholder="Whatsaap" data-toggle="tooltip" data-placement="bottom" title="Whatsaap">
							</div>
						</div>
					</fieldset>
					<fieldset style="display:none;" >
						<legend>Horario</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Lun</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input type="checkbox" id="dia_s[]" name="dia_s[]" value="1"  checked >
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_lun">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==8) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_lun">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==20) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Mar	</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input id="dia_s[]" name = "dia_s[]" type="checkbox" checked value="2">
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_mar">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==8) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_mar">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==20) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Mier</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input id="dia_s[]" name = "dia_s[]"  type="checkbox" checked value="3">
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_mie">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==8) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_mie">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==20) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >Juev</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input id="dia_s[]" name = "dia_s[]" type="checkbox" checked value="4">
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_jue">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==8) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_jue">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==20) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >Vier</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input id="dia_s[]" name = "dia_s[]" type="checkbox" checked value="5">
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_vie">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==8) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_vie">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==20) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" >Sab</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input id="dia_s[]" name = "dia_s[]" type="checkbox" checked value="6">
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_sab">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==8) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_sab">
							<?php for($i=6;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==20) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Dom</label>
							<div class="col-sm-5">
								<div class="toggle-switch toggle-switch-success">
									<label>
										<input id="dia_s[]" name = "dia_s[]"  type="checkbox" value="7">
										<div class="toggle-switch-inner"></div>
										<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
									</label>
								</div>
							</div>
							<div class="col-sm-2">
							<select id="i_dom">
							<?php for($i=0;$i<=22;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==0) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
							<div class="col-sm-2">
							<select id="f_dom">
							<?php for($i=0;$i<=24;$i++){?>
								<option value='<?php echo $i;?>' <?php if($i==0) echo 'selected'; ?>><?php echo $i;?></option>
							<?php 
							}
							?>
							</select>
							</div>
						</div>
						
							<input type="hidden" class="form-control" name="accion_l" id= "accion_l" value="add" />
							<input type="hidden" class="form-control" name="idmarca" id= "idmarca" value="" />
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<button type="button" class="btn btn-success btn-lg btn-label-right" onclick="locali.save_local();">Guardar <span><i class="fa fa-save"></i></span></button>
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
	$("#s2_country").select2();
	
	// $("select").select2();
	  $("#sl_ciudades").select2({
		 allowClear: true,
		 minimumInputLength: 3,
		 placeholder: "Selecciona Ciudad",
		 minimumResultsForSearch: Infinity
	 });
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
	$('.form-control').tooltip();
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
 
	
});

</script>
<?php
}
?>