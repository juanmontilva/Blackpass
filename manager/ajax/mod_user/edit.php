<?php
include("../../dist/funciones/conexion.php");
include("../../dist/funciones/cript.php");

			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			$nik = str_replace(' ', '+', $nik);
			$nik = $desencriptar($nik);
			$sql = mysqli_query($con, "select * from app_user where id_user = '$nik'");
			//echo "SELECT * FROM apps_clientes WHERE id='$nik'";
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			$sql2 = mysqli_query($con,"select * from apps_user_adetails where id_user = '".$row['id_user']."' ");
			$sql3 = mysqli_query($con,"SELECT p.* FROM apps_paises p where p.status = 1");
			if(mysqli_num_rows($sql2)!=0){
				$row2 = mysqli_fetch_assoc($sql2);
			}
			$f = explode("-",$row2['fcumple']);
?>
	<form id="editForm"  class="form-horizontal">
							<fieldset>
								<legend>Datos de Registro</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">Nombre</label>
									<div class="col-sm-5">
										<input type="text" value = "<?php echo $row2['nombre']?>" class="form-control" placeholder="Nombre"  name="txtnombre_u2" id="txtnombre_u2"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Apellido</label>
									<div class="col-sm-5">
										<input type="text" value = "<?php echo $row2['apellido']?>"  placeholder="Apellido" class="form-control"  name="txtpellido2" id="txtpellido2" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
									</div>
								</div>
							</fieldset>
							<fieldset>
							
								<legend>Información Acceso</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">Tipo Usuario</label>
									<div class="col-sm-5">
									<select id="t_user2" name="t_user2"  class="form-control">
									<option value="1" <?php if ($row['perfil']==1) echo "selected"; ?> >Administrador</option>	
									<option value="2" <?php if ($row['perfil']==2) echo "selected"; ?> >Supervisor</option>
									<option value="3" <?php if ($row['perfil']==3) echo "selected"; ?> >Cajero</option>								
									</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Telefono</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" value = "<?php echo $row2['mobile']?>" name="txtphone_u2" id= "txtphone_u2" placeholder="+584243204592" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Email</label>
									<div class="col-sm-5">
										<input type="email" class="form-control" value = "<?php echo $row2['mail']?>" name="txtemail_u2" id= "txtemail_u2" placeholder="correo@micorreo.com" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" />
										
										<input type="hidden" class="form-control" name="iduser" id= "iduser" value="<?php echo $encriptar($row2['id_user']);?>" />
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>Localidad</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">Selección Localidad</label>
									<div class="col-sm-5">
									<select id="s2_pais_u2"  class="form-control" >
									<?php
											$i=0;
											while($resultados3 = mysqli_fetch_assoc($sql3)){
											$i++;
									?>
										<option <?php if ($row2['pais']==$resultados3['cod']) echo "selected"; ?>  value="<?php echo $resultados3['cod'];?>"><?php echo $resultados3['pais'];?></option>
											<?php
											}?>								
									</select>
									<div id="mostrar_error_us"></div>
									</div>
								</div>
							</fieldset>
							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-3">
								 <input type="hidden" name="idcl" id="idcl" value="<?php echo $nik;?>" >
									<input type="hidden" name="accion2" id="accion2" value="update" >
									<button type="button" id="upd" name ="upd" class="btn btn-lg btn-success btn-label-right" onclick="usua.upda_user_sp();">Guardar<span><i class="fa fa-save"></i></span></button>
								</div>
							</div>
				</form>
<script>


</script>