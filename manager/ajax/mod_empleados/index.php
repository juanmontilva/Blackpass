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
$filter="";
if($filter){
	$sql = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE estado='$filter' ORDER BY codigo ASC");
}else{
	$sql = mysqli_query($con, "SELECT * FROM apps_emple_s ORDER BY codigo ASC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
</head>
<body>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="home.php#ajax/mod_marcas/list_view_marcas.php">Registro Empleados</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Registro de Empleados</span>
				</div>
				<div class="no-move"></div>

			</div>
			<div class="box-content no-padding">			
			<div style="margin-left:87%">
			<br>
			<button type="button" class="btn btn-lg btn-info btn-label-right" onclick="new_emple();">Add Empleado<span><i class="fa fa-plus-square"></i></span></button>
			 </div>
			
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
			<thead>
				<tr>
					<th>Código</th>
					<th>Nombre</th>
					<th>Teléfono</th>
					<th>Tipo Cliente</th>
                    <th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
				
				if(mysqli_num_rows($sql) == 0){
					echo 'No hay datos.';
				
				?>
				</tbody>
				<?php
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						$nombre = "'".$row['nombres']."'";
						$codcl = "'".$row['codigo']."'";
						echo '
						<tr>
							<td>'.$row['codigo'].'</td>
							<td><span class="glyphicon glyphicon-user" aria-hidden="true"></span> '.$row['nombres'].'</td>
							<td>'.$row['telefono'].'</td>
							<td>';
							if($row['estado'] == '1'){
								echo '<div class="alert alert-success">Activo</div>';
							}
                            else if ($row['estado'] == '2' ){
								echo '<div class="alert alert-warning">Suspendido</div>';
							}
                            else if ($row['estado'] == '3' ){
								echo '<div class="alert alert-danger">Retirado</div>';
							}
						echo '
							</td>
							<td>

								<button onclick ="update_emple('.$row['id'].')"  title="Editar datos" class="btn btn-primary "><span class="glyphicon glyphicon-edit"></span></button>
								<button onclick ="permiso_emple('.$row['id'].')"  title="Permiso" class="btn btn-warning "><i class="fa fa-pause"></i></button>
								<a onclick ="delete_emple('.$codcl.','.$nombre.')" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombres'].'?\')" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
							</td>
						</tr>
						';
						$no++;
					}
				}
				?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="Modal_p_ep" tabindex="-1" role="dialog" aria-labelledby="Modal_p_ep" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_p_ep"><i class="fa fa-bookmark"></i> Datos del Empleado</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
				<div class="form-group">
					<label class="col-sm-4 control-label">Periodo Permiso</label>
					<div class="col-sm-4">
						<input type="text"  class="form-control"  style="z-index:1151 !important" id="fecha_ip" placeholder="Desde">
					</div>
					<div class="col-sm-4">
						<input type="text" class="form-control" style="z-index:1151 !important" id="fecha_fp" placeholder="Hasta">
					</div>
				</div>
				<br><br><br>
				<input type="hidden" id="emple_p" value = "0">
				
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	<button onclick="add_per_emple()" name="add" id="add" class="btn btn-lg btn-success" ><i class="fa fa-save fa-x2"></i> Registrar Permiso</button>	
      </div>
  </div>
</div>
</div>
<div class="modal" id="Modal_v_ep" tabindex="-1" role="dialog" aria-labelledby="Modal_v_ep" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_v_ep"><i class="fa fa-bookmark"></i> Datos del Empleado</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content" id="carga_data_ep">
		
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	
      </div>
  </div>
</div>
</div>

</body>
</html>


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
	$("#tabs").tabs();
	LoadTimePickerScript(AllTimePickers);
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	

 
});
</script>
<?php 
}
?>