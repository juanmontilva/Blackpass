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
include("../../dist/funciones/cript.php");
//include("../../dist/funciones/convertir_mes.php");

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

$mmarca= mysqli_query($con,"SELECT p.pais,m.marca,p.bandera ,mp.id_mp FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m  WHERE 
							mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod 
							and mp.status = 1");
$pais_marcas= mysqli_query($con,"SELECT  CONCAT(p.pais , ' ', m.marca) AS pmarca, px.id_mp  FROM `apps_marcas_x_pais` px, 
						apps_marcas m, apps_paises p WHERE px.`id_marca` = m.`id_marca` and px.`id_pais` = p.cod 
						and px.`status` = 1 and p.`status` = 1");
$loca= mysqli_query($con,"select * from apps_paises where status = 1");							
$luser= mysqli_query($con,"SELECT p.pais as localidad, u.perfil, ud.*,(CASE when u.estado ='0'  then 'Inactivo' when u.estado ='1' then 'Activo' END) AS Status 
					FROM `app_user` u, apps_user_adetails ud, apps_paises p 
					where u.`id_user` = ud.`id_user` 
					and u.manager = 0
					and p.cod = ud.pais");

$consulta = mysqli_query($con,"select *  from apps_menu where estatus = 1 ");

?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#">Sistema</a></li>
			<li><a href="#">Gestor Usuarios</a></li>
		</ol>
	</div>
</div>
<div class="row">

<div class="col-xs-12 ">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-users"></i>
					<span>Gestor Usuarios</span>
				</div>
				<div class="box-icons pull-right">
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
			<div class="box-content">
				<div class="box-content no-padding">
					<?php if($_SESSION['perfi']==1){?>
					<div style="margin-left:82%">
					<div class="btn btn-lg btn-group">
						<a class="btn btn-lg btn-info btn-label-right"><i class="fa fa-plus-circle"></i> Usuario</a>
						<a class="btn btn-lg btn-info dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="fa fa-caret-down"></span></a>
							<ul class="dropdown-menu">
								<li><a  onclick="usua.new_usuario_s(this);"><i class="fa fa-group"></i> Nuevo Usuario</a></li>
							</ul>
					</div>
					
					</div>
					<?php 
					}
					?>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">

					<thead>
						<tr>
							<th>Registro N°</th>
							<th>Nombre</th>
							<th>Tipo User</th>
							<th>Localidad</th>
							<th>Ultimo Acceso</th>
							<th>Status</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$i=0;
						while($resultados = mysqli_fetch_assoc($luser)){
							$detalle= mysqli_query($con,"SELECT * FROM `apps_acceso_user` WHERE `id_user` = '".$resultados['id_user']."' order by `id_acceso` desc ");
							$i++;
							$resultados2 = mysqli_fetch_assoc($detalle);
							if($resultado2 = mysqli_num_rows($detalle)==0){
								$acceso = "No inicio sesión";
							}else{
								$acceso = $resultados2['fecha']." a las ".$resultados2['hora'];
							}
							switch ($resultados["perfil"]){
								
								case 1: 
								$tipo = "Super Usuario";
								break;
								case 2:
								$tipo = "Supervisor";
								break;
								case 3: 
								$tipo = "Cajero";
								break;
							}
							if($resultados['foto']=="" || $resultados['foto']==null){
							$foto= "img/icono_user.png";
							}else{
								$foto= "ajax/mod_user/files/".$resultados['foto'];
							}
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><img class="img-rounded"  style="cursor:pointer"  onclick="usua.foto('<?php echo $resultados['id_user'];?>');" src="<?php echo $foto;?>" alt=""><?php echo $resultados['nombre']." ".$resultados['apellido'];?></td>
							<td><?php echo $tipo;?></td>
							<td><?php echo $resultados['localidad'];?></td>
							<td><h6><?php echo $acceso;?></h6></td>
							<td><?php echo $resultados['Status'];?></td>
							<td>
							<div class="btn btn-lg btn-group">
										<a class="btn btn-lg btn-default" href="#"><i class="fa fa-gear"></i> Acciones</a>
										<a class="btn btn-lg btn-success dropdown-toggle" data-toggle="dropdown" href="#">
										<span class="fa fa-caret-down"></span></a>
										<ul class="dropdown-menu">
										<li><a onclick="usua.update_user('<?php echo $encriptar($resultados['id_user']);?>','<?php echo $resultados['nombre']." ".$resultados['apellido'];?>')" ><i class="fa fa-pencil fa-fw"></i> Editar</a></li>
										<li><?php if($resultados['Status']=="Activo"){?> <a  onclick="usua.delete_user(<?php echo $resultados['id_user'];?>,'<?php echo $resultados['nombre']." ".$resultados['apellido'];?>',0);" ><i class="fa fa-ban fa-fw"></i> Desactivar<?php }else{?><a  onclick="usua.delete_user(<?php echo $resultados['id_user'];?>,'<?php echo $resultados['nombre']." ".$resultados['apellido'];?>',1);" ><i class="fa fa-check-square-o"></i> Activar<?php }?></a></li>
										</ul>
							</div>
							</td>
						</tr>
					<?php 
					}
					?>
				</table>
</div>
					
			
			</div>
		</div>
	</div>

</div>
<div class="modal" id="form_super_modal" tabindex="-1" role="dialog" aria-labelledby="form_super_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_super_modal"><i class="fa fa-user"></i> Gestor de Usuario</h4>
      </div>
      <div class="modal-body">
		<div class="box">
						<div class="box-content">
						<form id="defaultForm"  action="validar.html" method="post"  class="form-horizontal">
							<fieldset>
								<legend>Datos de Registro</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">Nombre</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" placeholder="Nombre"  name="txtnombre_u" id="txtnombre_u"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Apellido</label>
									<div class="col-sm-5">
										<input type="text" placeholder="Apellido" class="form-control"  name="txtpellido" id="txtpellido" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
									</div>
								</div>
							</fieldset>
							<fieldset>
							
								<legend>Información Acceso</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">Tipo Usuario</label>
									<div class="col-sm-5">
									<select id="t_user" name="t_user"  class="populate placeholder" >
									<option value="1">Administrador</option>	
									<option value="2">Supervisor</option>
									<option value="3">Cajero</option>								
									</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Telefono</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" name="txtphone_u" id= "txtphone_u" placeholder="+584243204592" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Email</label>
									<div class="col-sm-5">
										<input type="email" class="form-control" name="txtemail_u" id= "txtemail_u" placeholder="correo@micorreo.com" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" />
										<input type="hidden" class="form-control" name="accion" id= "accion" value="add" />
										<input type="hidden" class="form-control" name="idsup" id= "idsup" value="" />
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>Localidad</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">Selección Localidad</label>
									<div class="col-sm-5">
									<select id="s2_pais_u"  class="populate placeholder" >
									<?php
											$i=0;
											while($resultados3 = mysqli_fetch_assoc($loca)){
											$i++;
									?>
										<option  value="<?php echo $resultados3['cod'];?>"><?php echo $resultados3['pais'];?></option>
											<?php
											}?>								
									</select>
									<div id="mostrar_error_us"></div>
									</div>
								</div>
							</fieldset>
							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-3">
								   
									<button type="button"  id="upd" name ="upd"  class="btn btn-lg btn-success btn-label-right" onclick="usua.save_user_sp();">Guardar<span><i class="fa fa-save"></i></span></button>
								</div>
							</div>
				</form>
			</div>
		</div>

      </div>
  </div>
</div>
</div>
<!-- fin modal Nuevo  Usuario!-->


<!--modal edit user -->
<div class="modal" id="form_super_e_modal" tabindex="-1" role="dialog" aria-labelledby="form_super_e_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_super_modal"><i class="fa fa-user"></i> <span id="name_"></span></h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div id="carga_data_cl" class="box-content"></div>
		</div>

      </div>
  </div>
</div>
</div>
<!-- fin modal edit user-->


<!--Modal Logo Marca-->
<div class="modal" id="form_foto_modal" tabindex="-1" role="dialog" aria-labelledby="form_foto_modal" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_foto_modal"><i class="fa fa-bookmark"></i> Foto para: <?=$_SESSION['usuario']?></h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
						<p></p>
						<div class="box-content">
							<form method="post" id="formulario" enctype="multipart/form-data">
							Subir imagen: <input type="file" name="file" id="file">
							<input type="hidden" id="usuarioid" name="usuarioid">
						 </form>
						  <div id="respuesta"></div>
						</div>
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	<button type="button" class="btn btn-primary btn-label-right" onclick="usua.save_foto();">Asignar Foto<span><i class="fa fa-save"></i></span></button>
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
	$('select').select2();
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Search');
	});
}
$(document).ready(function() {
	// Load TimePicker plugin and callback all time and date pickers
	LoadTimePickerScript(AllTimePickers);
	// Create jQuery-UI tabs
	$("#tabs").tabs();
	var icons = {
		header: "ui-icon-circle-arrow-e",
		activeHeader: "ui-icon-circle-arrow-s"
	};
	$("#accordion").accordion({icons: icons });
	// Make accordion feature of jQuery-UI
	// Create UI spinner
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes
	LoadDataTablesScripts(AllTables);
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
	WinMove();
});
$(function(){
        $("input[name='file']").on("change", function(){
            var formData = new FormData($("#formulario")[0]);
            var ruta = "ajax/mod_user/add_foto.php";
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
