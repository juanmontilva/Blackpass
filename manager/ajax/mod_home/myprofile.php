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

$mmarca  = mysqli_query($con,"select * from apps_paises where status = 1 order BY pais ASC  ");
$paises = mysqli_query($con,"SELECT p.* FROM `apps_provincias` pv, apps_paises p WHERE p.cod = pv.`id_pais` 
							and p.status = 1 group by cod  order BY p.pais ASC");

?>
<div class="modal" id="form_loca_modal" tabindex="-1" role="dialog" aria-labelledby="form_loca_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_loca_modal"><i class="fa fa-globe"></i> Nueva Localidad</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
				<form id="defaultForm"  action="validar.html" method="post"  class="form-horizontal">
					<fieldset>
						<legend>Ubicacion</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Pais</label>
							<div class="col-sm-5">
								<select class="populate placeholder"  name="s2_country" id="s2_country" onchange="locali.buscar_pro(this);" onfocus="this.selectedIndex = -1;">
									<option value="">-- Select a Pais --</option>
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
						<div class="form-group">
							<label class="col-sm-3 control-label">Estado</label>
							<div class="col-sm-5">
								<select  class="populate placeholder" name="sl_ciudades" id="sl_ciudades">							 
								 <div id="subcategory">
									<option value="">-- Select a Estado --</option>
								</div>	
								</select>
							</div>
							<div id="mostrar_error"></div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Información</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nombre Sitio</label>
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
					<fieldset>
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
							<button type="button" class="btn btn-success btn-label-right" onclick="locali.save_local();">Guardar<span><i class="fa fa-save"></i></span></button>
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
//	TestTable1();


	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	/*("#i_lun").select2();
	("#f_lun").select2();*/
	$("#s2_country").select2();
	
	 $("select").select2();
	  $("#sl_ciudades").select2({
		 allowClear: true,
		 minimumInputLength: 3,
		 placeholder: "Selecciona Ciudad",
		 minimumResultsForSearch: Infinity
	 });
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	$('.form-control').tooltip();
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	$('#txtnumero').numeric();
 
	
});


</script>
<?php
}
?>