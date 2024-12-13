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
include("../../dist/funciones/cript.php");
$filter="";
$codigo = "";
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
			<BR>
			<div style="width:15%;margin-left:85%" id="print"><div class="alert alert-dark" onclick="download_exl();">Crear Excel</div><div class="alert alert-warning" onclick="download_zip();">Descargar Zip</div></div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
			<thead>
				<tr>
					<th>Registro N°</th>
					<th>Nombre</th>
					<th>Tipo Cliente</th>
					<th>Estatus</th>
					<th>Tarjeta</th>
                    <th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$j=0;
				if($filter){
					$sql = mysqli_query($con, "SELECT * FROM apps_clientes WHERE estado='$filter' and id <> 1 ORDER BY codigo ASC");
				}else{
					$sql = mysqli_query($con, "SELECT * FROM apps_clientes where id <> 1 ORDER BY id ASC");
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					
					while($row = mysqli_fetch_assoc($sql)){
						$j++;
						$nombre = "'".$row['nombres']."'";
						$codcl = $row['id'];
						$rowc = "";
						$empresa = "";
						$sql3 = mysqli_query($con, "SELECT * FROM apps_marcas where id_marca = '".$row['plan']."'");
						$row3 = mysqli_fetch_assoc($sql3);
						
						$sql3x = mysqli_query($con, "SELECT * FROM apps_servicios_d where cod = '".$row['id_cd']."'");
						$row3x = mysqli_fetch_assoc($sql3x);
						
						$cl = "'".$encriptar($codcl)."'";
						$razon = "";
						if($row['razon']!="0" && $row['tcliente']==2){
							$razon = $row['razon'];
						}
						if($row['dependiente']!=0){
							$sqlc = mysqli_query($con,"select razon from apps_clientes 
								where id = '".$row['dependiente']."' ");
							if(mysqli_num_rows($sqlc)!=0){
								$rowc =  mysqli_fetch_assoc($sqlc);
								$razon = $row['nombres']."( ".$rowc['razon']." )";
							}
						}else if($row['tcliente']==1 && $row['razon']=="0" ){
							$razon = $row['nombres'];
						}
						//$cl = str_replace('+', '', $cl);
						echo '
						<tr>
							<td>'.$j.'</td>
							<td><a onclick ="info_cliente('.$cl.','.$nombre.')"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> '.$razon.'</a></td>
							<td>'.$row3['marca'].'</td>
							<td>';
							if($row['status'] == '1'){
								echo '<div class="alert alert-success">ACTIVO</div>';
							}
                            else if ($row['status'] == '2' ){
								echo '<div class="alert alert-warning">MORA</div>';
							}
                            else if ($row['status'] == '3' ){
								echo '<div class="alert alert-danger">SUSPENDIDA</div>';
							}
						echo '
							</td>
							
							<td>';
							if(mysqli_num_rows($sql3x)!=0){
							if($row3x['print'] == 1){
								echo '<div class="alert alert-primary">IMPRESA</div>';
							}
                            else {
								echo '<div class="alert alert-warning">POR IMPRIMIR</div>';
							}
							}
						echo '
							</td>
							
							<td>';
								if($_SESSION['perfi']==1){
								echo '<button onclick ="update_cliente('.$cl.')"  title="Editar datos" class="btn btn-primary "><span class="glyphicon glyphicon-edit"></span></button>'.
								'<a onclick ="info_cliente('.$cl.','.$nombre.')"  title="Información de Usuario	" class="btn btn-info "><i class="fa fa-info-circle" ></i></a>'.
								'<a onclick ="delete_cliente('.$row['id'].','.$nombre.')" title="Eliminar" onclick="return confirm(\'Esta seguro de borrar los datos '.$row['nombres'].'?\')" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
								}else if($_SESSION['perfi']==2){
								echo '<button onclick ="update_cliente('.$cl.')"  title="Editar datos" class="btn btn-primary "><span class="glyphicon glyphicon-edit"></span></button>'.
								'<a onclick ="info_cliente('.$cl.','.$nombre.')"  title="Información de Usuario	" class="btn btn-info "><i class="fa fa-info-circle" ></i></a>';
								}else if($_SESSION['perfi']==3){
								echo '<a onclick ="info_cliente('.$cl.','.$nombre.')"  title="Información de Usuario	" class="btn btn-info "><i class="fa fa-info-circle" ></i></a>';
								}
							echo '</td>'.
						'</tr>';
						$no++;
						$codigo = $row['id'];
					}
				}
				?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="Modal_v_cl" tabindex="-1" role="dialog" aria-labelledby="Modal_v_cl" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_v_cl"><i class="fa fa-bookmark"></i> Datos del Cliente</h4>
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
	     <button type="button" class="close" onclick="cerrar_tax2();">&times;</span></button>
        <h4 class="modal-title" id="Modal_v_cl2"><i class="fa fa-bookmark"></i> información del Cliente</h4>
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
				<div id="tabs">
					<ul id="menu_l">
						<li><a style="color:#000;"  href="#tabs-1">Tarjeta</a></li>
						<li><a style="color:#000;"  href="#tabs-2" >Recargas</a></li>
						<li><a style="color:#000;"   href="#tabs-3" >Consumos</a></li>
					</ul>
					<div id="tabs-1"></div>
					<div id="tabs-2"></div>
					<div id="tabs-3"></div>						
				</div>
			</div>	
			</div>
		</div>
      </div>
	  <div class="modal-footer"></div>
  </div>
</div>
</div>
<div class="modal" id="Modal_v_tax" tabindex="-1" role="dialog" aria-labelledby="Modal_v_tax" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" onclick="cerrar_tax();"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_v_tax"><i class="fa fa-bookmark"></i> Tax </h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<form method="post"  class="form-inline" name="form_tax_import" id="form_tax_import" enctype="multipart/form-data">
				<br>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tipo Cliente</label>
					<div class="col-sm-4">
						<select name="year" id="year" class="form-control">
							<option value="2018">2018</option>
                            <option value="2019">2019</option>
							<option value="2020">2020</option> 
							<option value="2021">2021</option> 
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-12 control-label">Selecciona un Archivo</label>
				<div class="col-sm-6">
						<input type="file" name="file" id="file">
						<input type="hidden" id="client" name="client">
				</div>
				</div>
			 </form>
			<div id ="respuesta_file"></div>
		</div>
      </div>
	  <div class="modal-footer"></div>
  </div>
</div>
</div><!--fin modal tax-->
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
		$(this).find('label input[type=text]').attr('placeholder', 'Buscar');
	});
}
$(document).ready(function() {
	$("#tabs").tabs();
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
<?php 
}
?>