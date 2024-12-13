<?php
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/cript.php");
?>
<!DOCTYPE html>
<html lang="es">
<body>
	<div class="container">
		<div class="content">
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			$nik = str_replace(' ', '+', $nik);
			$nik = $desencriptar($nik);
			$sql = mysqli_query($con, "SELECT * FROM bd_bck_pass_v1.apps_clientes WHERE id='$nik'");
			//echo "SELECT * FROM apps_clientes WHERE id='$nik'";
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			$sql2 = mysqli_query($con,"select * from apps_marcas where id_marca <> 105 
										and status = 1 
										and  tipo_p= '".$row['tcliente']."' ORDER BY marca asc ");
										
										
			$sql3 = mysqli_query($con,"SELECT e.* FROM apps_paises p, apps_provincias e WHERE p.cod = e.id_pais and p.cod = 1 order by e.nombreprovincia asc");
			$ssn = explode("-",$row ['ssn']);
			$f = explode("-",$row['fecha_nacimiento']);
			?>
			<form class="form-horizontal" id="form_edit_cl">
				<div STYLE = "display:none" class="form-group">
					<label class="col-sm-3 control-label">Código</label>
					<div class="col-sm-2">
						<input type="text" name="codigo" id="codigo" value="<?php echo $row ['codigo']; ?>" class="form-control" placeholder="NIK" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tipo Cliente</label>
					<div class="col-sm-4">
						<select name="tc" id="tc" class="form-control">
							<option <?php if ($row['tcliente']==1){echo "selected";} ?> value="1"> Personax</option>
                            <option <?php if ($row['tcliente']==2){echo "selected";} ?> value="2"> Empresa </option> 
						</select>
					</div>
				</div>
				 <?php if ($row['tcliente']==2){?>
				<div class="form-group">
					<label class="col-sm-3 control-label">Razón Social</label>
					<div class="col-sm-4">
						<input type="text" name="rzs" id="rzs" value="<?php echo $row ['razon']; ?>" class="form-control" placeholder="Nombres" required>
					</div>
				</div>
				 <?php 
				 }
				 ?>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nombres</label>
					<div class="col-sm-4">
						<input type="text" name="nombres" id="nombres" value="<?php echo $row ['nombres']; ?>" class="form-control" placeholder="Nombres" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Identificación</label>
					<div class="col-sm-1">
						<select name="fp" id="fp" class="form-control">
							<option  <?php if ($ssn[0]=="V"){echo "selected";}?> value="V">V</option>
                            <option <?php if ($ssn[0]=="E"){echo "selected";}?> value="E">E</option>
							<option <?php if ($ssn[0]=="J"){echo "selected";}?> value="J">J</option> 
							<option <?php if ($ssn[0]=="G"){echo "selected";}?> value="G">G</option> 
						</select>
					</div>
					<div class="col-sm-3">
						<input type="number" name="ssn" value="<?php echo $ssn[1]; ?>" maxlength = "12" maxlength="12" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  id="ssn" class="form-control" placeholder="Nombres" required>
					</div>
				</div>
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Fecha de nacimiento</label>
					<div class="col-sm-4">
						<input type="text" name="fecha_nacimiento" id="fd" value="<?php echo $row['fecha_nacimiento'] ?>" class="input-group date form-control" date="" data-date-format="yyyy-mm-dd" placeholder="0000-00-00" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Estado</label>
					<div class="col-sm-3">
						<select name="direccion" id="direccion" class="form-control">
							<option value=""> ----- </option>
                           <?php
							while($r3 = mysqli_fetch_assoc($sql3)){?>
						   <option value="<?php echo $r3['codprovincia']?>" <?php if ($row['estado']==$r3['codprovincia']){echo "selected";} ?> ><?php echo $r3['nombreprovincia']?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Dirección</label>
					<div class="col-sm-3">
						<input type="text"  name="direc" id="direc" value="<?php echo $row ['direccion']; ?>" class="form-control" placeholder="Dirección" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Teléfono</label>
					<div class="col-sm-3">
						<input type="text" name="telefono" id="telefono" value="<?php echo $row ['telefono']; ?>" class="form-control" placeholder="Teléfono" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">E-mail</label>
					<div class="col-sm-3">
						<input type="mail"  name="mail" id="mail" class="form-control" value="<?php echo $row ['mail']; ?>" placeholder="Mail" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tipo Producto</label>
					<div class="col-sm-3">
						<select name="tcliente" id="tcliente" class="form-control">
							<option value=""> ----- </option>
                           <?php
							while($r2 = mysqli_fetch_assoc($sql2)){?>
						   <option value="<?php echo $r2['id_marca']?>" <?php if ($row['plan']==$r2['id_marca']){echo "selected";} ?>><?php echo $r2['marca']?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
					   <input type="hidden" name="idcl" id="idcl" value="<?php echo $nik;?>" >
					   <input type="hidden" name="accion" id="accion" value="update" >
						<input type="button" onclick="add_cliente()" name="add" id="add" class="btn btn-success" value="Guardar datos">
						
					</div>
				</div>
			</form>
		</div>
	</div>
	<script>
	$('.date').datepicker({
		format: 'dd-mm-yyyy',
		maxDate: '-18Y'
	})
	</script>
</body>
</html>