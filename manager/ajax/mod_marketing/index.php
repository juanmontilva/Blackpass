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
			<li><a href="home.php#ajax/mod_marcas/list_view_marcas.php">Gestor de Eventos</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Lista de Eventos</span>
				</div>
				<div class="no-move"></div>
			</div>
			<br>
			<div class="box-content no-padding">
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
			<thead>
				<tr>
					<th>Código</th>
					<th>Nombre</th>
					<th>Fecha</th>
					<th>Estatus</th>
                    <th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($filter){
					$sql = mysqli_query($con, "SELECT * FROM apps_bd_market ORDER BY fecha desc");
				}else{
					$sql = mysqli_query($con, "SELECT c.* FROM apps_bd_market c ORDER BY fecha desc");
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						$nombre = "'".$row['titulo']."'";
						$codcl = "'".$row['id']."'";
						echo '
						<tr>
							<td>'.$row['codigo'].'</td>
							<td>'.$row['titulo'].'</td>
							<td>'.$row['fecha'].'</td>
							<td>';
							if($row['status'] == '1'){
								echo '<div class="alert alert-primary">Programada</div>';
							}
                            else if ($row['status'] == '2' ){
								echo '<div class="alert alert-info">En Proceso</div>';
							}
                            else if ($row['status'] == '3' ){
								echo '<div class="alert alert-success">Enviada</div>';
							}
						echo '
							</td>
							<td>';
							if ($row['status'] == '1' ){
							echo	'<a onclick ="info_campa('.$row['id'].','.$nombre.')"  title="Información de Usuario	" class="btn btn-success "><i class="fa fa-play" ></i></a>';
							}echo '
								<!--<button onclick ="update_cliente('.$row['codigo'].')"  title="Editar datos" class="btn btn-primary "><span class="glyphicon glyphicon-edit"></span></button>-->
								<a onclick ="ver_campa('.$codcl.')"  title="Información de Campaña	" class="btn btn-info "><i class="fa fa-info-circle" ></i></a>
								<a onclick ="delete_campa('.$codcl.','.$nombre.')" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['titulo'].'?\')" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
<div class="modal" id="Modal_v_cl" tabindex="-1" role="dialog" aria-labelledby="form_logo_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_v_cl"><i class="fa fa-bookmark"></i> Datos de la Campaña</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content" id="carga_data_cl">
		
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	
      </div>
  </div>
</div>
</div>
<div class="modal" id="Modal_v_cl2" tabindex="-1" role="dialog" aria-labelledby="form_logo_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_v_cl2"><i class="fa fa-bookmark"></i> Información de la Campaña</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search"></i>
					<span id="name_cl"></span>
					
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
			
			<div id="cantidad"></div>
			<div id="url"></div>
			
			<div id="label"></div>
			<div id='global' class='col-xs-2 col-sm-2'>
			</div>
			<br><br><br><br><br><br><br><br>
			<input type="hidden" id="camp_id" value = "0"/>
			<div class="text-center">
			<button type="button" class="btn btn-lg btn-success btn-label-right" id="btn_send" onclick="procesar_campa_send();"><i class="fa fa-send"></i>Procesar Campaña</button>
			</div>
			<br>
			</div>	
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
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	
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