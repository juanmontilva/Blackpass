<?php
session_start();

//Validar que el usuario este logueado y exista un UID
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../../index.php?error=ER001");
	//exit;
}else{
include("../../dist/funciones/conexion.php");
$sqlc = mysqli_query($con,"select * from apps_clientes ORDER BY id desc LIMIT 1");
$rowc = mysqli_fetch_assoc($sqlc);
$sql2 = mysqli_query($con,"select * from apps_marcas where id_marca <> 105 
										and status = 1 
										and  tipo_p= '".$rowc['tcliente']."' ORDER BY marca asc ");
$sql3 = mysqli_query($con,"SELECT e.* FROM apps_paises p, apps_provincias e WHERE p.cod = e.id_pais and p.cod = 1 order by e.nombreprovincia asc");
$id = $rowc['id']+1;
$id = "0000".$id;
$fecha_actual = date("d-m-Y");
$fpermi = date("d-m-Y",strtotime($fecha_actual."- 18 year"));

?>
<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="utf-8">
<style>
.selecte {
     background: #ffffff;
     border: 1;
	 border-color:#cccccc;
     font-size: 14px;
     height: 30px;
     padding: 5px;
     width: auto;
	 border-radius: 5px;
  }
</style>
</head>
<body>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="home.php#ajax/mod_marcas/list_view_marcas.php">Registro Clientes</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Registro de Clientes</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content no-padding">
			<form class="form-horizontal" id="registro_c">
				<div style ="display:none;" class="form-group">
					<label class="col-sm-3 control-label">Código Cliente</label>
					<div class="col-sm-2">
						<input type="text" name="codigo" id="codigo"  class="form-control" value="<?php echo $id; ?>" placeholder="12345679" readonly >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tipo Cliente</label>
					<div class="col-sm-3">
						<select name="tc" id="tc" class="form-control" onblur = "validar_t();">
							<option value="-"> Seleccione</option>
							<option value="1"> Persona</option>
                            <option value="2"> Empresa </option> 
						</select>
					</div>
				</div>
				<div id="razon" style="display:none;" class="form-group">
					<label  class="col-sm-3 control-label">Razón Social</label>
					<div class="col-sm-3">
						<input type="text" name="rzs" id="rzs"  class="form-control"  placeholder="Razón Social"  >
					</div>
				</div>
				<div class="form-group">
					<label  id="nombre_l" class="col-sm-3 control-label">Nombres</label>
					<div class="col-sm-3">
						<input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Identificación</label>
					<div class="col-sm-1">
						<select name="fp" id="fp" class="selecte" >
							
						</select>
					</div>
					<div class="col-sm-3">
						<input type="number" name="ssn" maxlength = "12" maxlength="12" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  id="ssn" class="form-control" placeholder="123456789" required>
					</div>
				</div>
				
				
				<div id="nacimiento" class="form-group">
					<label class="col-sm-3 control-label">Fecha de nacimiento</label>
					<div class="col-sm-3">
						<input type="text"  id="fd" name="fecha_nacimiento"  class="input-group date form-control" date="" data-date-format="yyyy-mm-dd" placeholder="00-00-0000" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Estado</label>
					<div class="col-sm-3">
						<select name="direccion" id="direccion" class="form-control">
							<option value=""> ----- </option>
                           <?php
							while($r3 = mysqli_fetch_assoc($sql3)){?>
						   <option value="<?php echo $r3['codprovincia']?>"><?php echo $r3['nombreprovincia']?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Dirección</label>
					<div class="col-sm-3">
						<input type="text"  name="direc" id="direc" class="form-control" placeholder="Dirección" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">E-mail</label>
					<div class="col-sm-3">
						<input type="mail"  name="mail" id="mail" class="form-control" placeholder="Mail" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Teléfono</label>
					<div class="col-sm-3">
						<input type="number"  name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tipo Producto</label>
					<div class="col-sm-3">
						<select name="tcliente" id="tcliente" class="form-control">
							<option value=""> ----- </option>
                           <?php
							while($r2 = mysqli_fetch_assoc($sql2)){?>
						   <option value="<?php echo $r2['id_marca']?>"><?php echo $r2['marca']?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				
				<input type="hidden" name="accion" id="accion" value="add" >
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="button" onclick="add_cliente()" name="add" id="add" class="btn btn-success" value="Registrar datos">
						<a href="index.php" class="btn btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>	
	<script>
	//$('#telefono').numeric();
	$('.date').datepicker({
		format: 'dd-mm-yyyy',
		maxDate: '-18Y'
	})
	function MakeSelect2(){
	/*("#i_lun").select2();
	("#f_lun").select2();*/
	
}


	</script>
</body>
</html>
<?php
}
?>