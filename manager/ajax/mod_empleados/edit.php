<?php
include("../../dist/funciones/conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<body>
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			$sql = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE id='$nik'");
			$sql_p = mysqli_query($con, "SELECT * FROM apps_provincias WHERE id_pais = 4");
			$sql_l = mysqli_query($con, "SELECT * FROM apps_localidades WHERE id_loc = ");
			$sql_m = mysqli_query($con, "SELECT * FROM apps_marcas WHERE status = 1 and id_marca <> 105");
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			if(isset($_POST['save'])){
				$codigo		     = mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));//Escanpando caracteres 
				$nombres		     = mysqli_real_escape_string($con,(strip_tags($_POST["nombres"],ENT_QUOTES)));//Escanpando caracteres 
				$lugar_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_POST["lugar_nacimiento"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nacimiento	 = mysqli_real_escape_string($con,(strip_tags($_POST["fecha_nacimiento"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	     = mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));//Escanpando caracteres 
				$telefono		 = mysqli_real_escape_string($con,(strip_tags($_POST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
				$puesto		 = mysqli_real_escape_string($con,(strip_tags($_POST["puesto"],ENT_QUOTES)));//Escanpando caracteres 
				$estado			 = mysqli_real_escape_string($con,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres  
				
				$update = mysqli_query($con, "UPDATE apps_emple_s SET nombres='$nombres', lugar_nacimiento='$lugar_nacimiento', fecha_nacimiento='$fecha_nacimiento', direccion='$direccion', telefono='$telefono', puesto='$puesto', estado='$estado' WHERE codigo='$nik'") or die(mysqli_error());
				if($update){
					header("Location: edit.php?nik=".$nik."&pesan=sukses");
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo guardar los datos.</div>';
				}
			}
			
			if(isset($_GET['pesan']) == 'sukses'){
				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos han sido guardados con éxito.</div>';
			}
			?>
			<div class="row">
	<div class="col-xs-12">
	<form class="form-horizontal" id="registro_e">
	<div id="tabs" class="selector">
			<ul>
				<li><a href="#tabs-1">Información <i class="fa  fa-info"></i></a></li>
				<li><a href="#tabs-2">Locación de Trabajo <i class="fa  fa-info"></i></a></li>
				<li><a href="#tabs-3">Servicios Prestados <i class="fa  fa-heart"></i></a></li>
			</ul>
		<div id="tabs-1" class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Registro de Empleados</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
				<div class="form-group">
					<label class="col-sm-3 control-label">Doc Identidad</label>
					<div class="col-sm-2">
						<input type="text" name="codigo" id="codigo" onblur="validar_camp2(this.id);" value="<?php echo $row ['codigo']; ?>"  class="form-control" placeholder="12345679" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombres</label>
					<div class="col-sm-4">
						<input type="text" name="nombres" id="nombres" onblur="validar_camp2(this.id);" value="<?php echo $row ['nombres']; ?>" class="form-control" placeholder="Nombres" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Fecha de nacimiento</label>
					<div class="col-sm-4">
						<input type="text" id="fd" name="fecha_nacimiento" onblur="validar_camp2(this.id);" value="<?php echo $row ['fecha_nacimiento']; ?>" class="input-group date form-control" date="" data-date-format="dd-mm-yyyy" placeholder="00-00-0000" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Dirección</label>
					<div class="col-sm-3">
						<textarea name="direccion" id="direccion" class="form-control" value="<?php echo $row['direccion']; ?>"><?php echo $row['direccion']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Teléfono</label>
					<div class="col-sm-3">
						<input type="text" onchange="validar_ne();" name="telefono" id="telefono" value="<?php echo $row ['telefono']; ?>" class="form-control" placeholder="Teléfono" required>
					</div>
				</div>
			</div>
		</div>
		<div id="tabs-2" >
			<div class="box">
				<div class="box-header">
					<div class="box-name">
						<i class="fa  fa-heart"></i>
						<span>Locación</span>
					</div>
					<div class="no-move"></div>
				</div>
				<div class="box-content no-padding">
					 <div class="panel-body">
					 	<legend>Ubicacion</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Ciudad</label>
							<div class="col-sm-5">
								<select class="populate placeholder"  name="s2_city" id="s2_city" onchange="localidades_emp(this);" onfocus="this.selectedIndex = -1;">
									
									<?php 
									$i=0;
									$sql_c = mysqli_query($con,"SELECT id_provincia FROM apps_localidades WHERE id_loc = '".$row['id_local']."' ");
									$row_p = mysqli_fetch_assoc($sql_c);
									echo "<script>localidades_emp('".$row_p['id_provincia']."')</script>";
									while($resultados3 = mysqli_fetch_assoc($sql_p)){
											?>
										<option  value="<?php echo $resultados3['codprovincia'];?>" <?php if ($resultados3['codprovincia']==$row_p['id_provincia']){echo "selected";} ?> ><?php echo utf8_encode($resultados3['nombreprovincia']);?></option>
									<?php
									}?>
								</select> 
							</div>
							<div id="mostrar_error_p"></div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tienda</label>
							<div class="col-sm-5">
								<select  class="populate placeholder" name="sl_tiendas" id="sl_tiendas">							 
								 <div id="subtiendas">
									<option value="">-- Select Tienda --</option>
								</div>	
								</select>
							</div>
							<div id="mostrar_error"></div>
							</div>
					 </div>
				</div>
			</div>
		</div>
		<div id="tabs-3" >
			<div class="box">
				<div class="box-header">
					<div class="box-name">
						<i class="fa  fa-heart"></i>
						<span>Servicios Prestados</span>
					</div>
					<div class="no-move"></div>
				</div>
			<div class="box-content no-padding">
					<div id="mostrar_error_check" class="bg-danger" style="display:none;">Debes marcas un mes al menos</div>
					    <div class="panel-body">
							<table class="table table-striped table-bordered table-hover table-heading no-border-bottom" style="border-collapse:collapse;">
			

							<tbody>
							<?php
								$k=0;
								
								while($resul2 = mysqli_fetch_assoc($sql_m)){
									$k2=0;
									$mdeta= mysqli_query($con,"select * from apps_servicios_d where status = 1 and id_marca = '".$resul2['id_marca']."' ");
									$k++;
									
									?>
							<tr data-toggle="collapse" data-target="#demo<?php echo $k;?>" class="accordion-toggle">
								<td><a class="btn btn-xs btn-success"><i class="fa fa-eye-slash fa-2x"></span></a></td>
								<td><b><?php echo utf8_encode($resul2['marca']);?></b></td>

							</tr>
							<tr>
								<td colspan="3" class="hiddenRow">
								<div id="demo<?php echo $k;?>" class="accordian-body collapse">
								<fieldset>
								<table class="table table-striped table-bordered table-hover table-heading no-border-bottom">
									<thead>
										<tr>
											<th>#</th>
											<th>Permiso</th>
											<th>Activar</th>
										</tr>
									</thead>
									<tbody>		
									<legend><b><?php echo utf8_encode($resul2['marca']);?></b></legend>			
									<?php while($resul3 = mysqli_fetch_assoc($mdeta)){
										$k2++;
										$sqld= mysqli_query($con,"select * from apps_emple_s_d where id_s = '".$resul3['idh']."' and id_e = '".$row['id']."' ");
										?>
										<tr>
											<td><?php echo $k2?></td>
											<td><?php echo $resul3['servicio'];?></td>
											<td>
											<div class="toggle-switch toggle-switch-success">
												<label>
													<input type="checkbox" <?php if(mysqli_num_rows($sqld)!=0){ echo "checked"; }?> 	id="meses[]" name="meses[]" value="<?=$resul3['idh']?>">
													<div class="toggle-switch-inner"></div>
													<div class="toggle-switch-switch"><i class="fa fa-check"></i></div>
												</label>	
											</div></td>
										</tr>
									<?php
									}
									?>
									
									</tbody>
								</table>			
								</fieldset>
								</div>
								
								</td>
							</tr>
							<?php
							}
							?>
							</tbody>
							</table>
						</div>
					</div>
				
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-6">
				<input type="hidden" name="accion" id="accion" value="update" >
				<input type="hidden" name="ide" id="ide" value="" >
				<input type="button" onclick="add_emple()" name="add" id="add" class="btn btn-lg btn-success" value="Guardar datos">			
			</div>
			</div>
		</div>
			
	   </div>
	</form>
</div>
</div>

	<script>
	
	$('#telefono').numeric();
	$('.date').datepicker({
		format: 'dd-mm-yyyy',
	})
	function MakeSelect2(){
	$("select").select2();
	 $("#s2_city").select2({
		 allowClear: true,
		 minimumInputLength: 3,
		 placeholder: "Selecciona Ciudad",
		 minimumResultsForSearch: Infinity
	 });
}
	$(document).ready(function() {
	// Load TimePicker plugin and callback all time and date pickers
	LoadTimePickerScript(AllTimePickers);
	LoadSelect2Script(MakeSelect2);
	// Create jQuery-UI tabs
	$("#tabs").tabs();
   //$( ".selector" ).tabs( "disable", "#tabs-3" );
	var icons = {
		header: "ui-icon-circle-arrow-e",
		activeHeader: "ui-icon-circle-arrow-s"
	};
	$("#accordion").accordion({icons: icons });
	// Make accordion feature of jQuery-UI
	// Create UI spinner
	$("#ui-spinner").spinner();

});
	</script>
</body>
</html>