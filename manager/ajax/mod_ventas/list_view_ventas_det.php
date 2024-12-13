<? 
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
$_SESSION['perfi'] = mysql_real_escape_string($_SESSION['perfi']);
$_SESSION['uid'] = mysql_real_escape_string($_SESSION['uid']);
$obj = new ConexionMySQL();
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
$ubic = mysql_real_escape_string($_GET["ubicar"]);

$mmarca= $obj->consultar("SELECT t.*, m.marca,(CASE when t.status ='0'  then 'Inactiva' when t.status ='1' then 'Activa' END) AS Status
						  FROM apps_tiendas t, apps_marcas m, apps_marcas_x_pais px
						  where t.id_mp = '".$ubic."' and px.id_marca = m.id_marca and px.id_mp = t.id_mp");
$sql= $obj->consultar("SELECT  m.marca, p.bandera  FROM apps_tiendas t, apps_marcas m, apps_marcas_x_pais px, apps_paises p
						  where t.id_mp = '".$ubic."' and px.id_marca = m.id_marca and px.id_mp = t.id_mp and px.id_pais = p.cod");
$res = $obj->obtener_fila($sql,0)
?>
<body onload="vent.calendarInit();">
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#" onclick="vent.refresh_list_tiendas();">Manager Ventas</a></li>
			<li><a href="#">Tiendas de <?=$res['marca']?></a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Manager Tiendas  <strong> <?=$res['marca']?>  </strong><img class="img-rounded" src="<?=$res['bandera']?>" ></span>
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
			<div style="margin-left:87%">
			
			 </div>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-1">
					<thead>
						<tr>
							<th>ID</th>
							<th>Razon Social <i class="fa fa-globe"></i></th>
							<th>Tienda <i class="fa fa-bookmark"></i></th>
							<th>Facturado <i class="fa fa-usd"></i></th>
							<th><i class="fa fa-cog"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<? 
						$i=0;
						while($resultados = $obj->obtener_fila($mmarca,0)){
							$localid= $obj->consultar("select sum(total) as facturado from apps_ventas_d where id_tienda = '".$resultados['id_tienda']."'");
							$resultados2 = $obj->obtener_fila($localid,0);
							$i++;
						?>
						<tr>
							<td><?=$i?></td>
							<td><a class="ajax-link" style="cursor:pointer" onclick="vent.view_ventas_mes(<?=$resultados['id_mp']?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" ><?=utf8_encode($resultados['razon_social'])?></a></td>
							<td><a class="ajax-link" style="cursor:pointer" onclick="vent.view_ventas_mes(<?=$resultados['id_mp']?>);"  data-toggle="tooltip" data-placement="bottom" title="Ver Detalles" ><?=utf8_encode($resultados['nombre_tienda'])?></a></td>
							<td><?=number_format($resultados2['facturado'],2 ,",", ".")?> 
							</td>
							<td><div class="btn-group">
										<a class="btn btn-default" href="#"><i class="fa fa-gear"></i> Acciones</a>
										<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
										<span class="fa fa-caret-down"></span></a>
										<ul class="dropdown-menu">
											<li><a href="#" onclick="vent.plantilla_ventas ('<?=$resultados['id_tienda']?>');"><i class="fa fa-download"></i> Genera Plantilla</a></li>
											<li><a href="#" onclick="vent.impor_ventas('<?=$resultados['id_tienda']?>');"><i class="fa fa-file-excel-o"></i> Importar Ventas</a></li>
											<li><a href="#" onclick="vent.registr_venta('<?=$resultados['id_tienda']?>');"><i class="fa  fa-shopping-cart"></i> Registrar Ventas</a></li>
										</ul>
									</div>
							</td>
						</tr>
					<?}?>
					
					<!-- End: list_row -->
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="form_tienda_modal" tabindex="-1" role="dialog" aria-labelledby="form_tienda_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_tienda_modal"><i class="fa fa-globe"></i> Nueva Tienda</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
						<div class="box-content">
						<div id="cargar"></div>
						</div>
			</div>
		</div>

      </div>
  </div>
</div>
</div>


<div class="modal" id="form_registro_venta_modal" tabindex="-1" role="dialog" aria-labelledby="form_registro_venta_modal" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_registro_venta_modal"><i class="fa fa-globe"></i> Registro de Ventas</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
						<form action="#" class="form-inline" name="form_ventas_regis" id="form_ventas_regis" autocomplete="off">
					<div class="form-group">
						<label class="col-sm-2 control-label">Fecha</label>
						<div class="col-sm-2" style="z-index: 104000 !important;">
							<div>
							<input type='text' name="fecha_o" id="fecha_o" class="form-control input-daterange" required >
							<div id="respuesta_form"></div>
						   </div>
						</div>
					</div>
					<div class="form-group">					
						<label class="col-sm-2 control-label">Piezas</label>
						<div class="col-sm-2" >
							<div>
							<input type='number' name="txt_piezas" id="txt_piezas" class="form-control" required >
						   </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Ticket</label>
						<div class="col-sm-2" >
							<div>
							<input type='number' name="txt_tick" id="txt_tick" class="form-control"  required>
						   </div>
						</div>
						<br>
						<br>
					</div>
					<div class="form-group">					
						<label class="col-sm-2 control-label">Bruto</label>
						<div class="col-sm-2" >
							<div>
							<input type='number' name="txt_bruto" id="txt_bruto" class="form-control" required placeholder = "0.0" >
						   </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">IVA</label>
						<div class="col-sm-2" >
							<div>
							<input type='number' name="txt_iva" id="txt_iva" class="form-control" required  placeholder = "0.0">
						   </div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Neto</label>
						<div class="col-sm-2" >
							<div>
							<input type='number' name="txt_neto" id="txt_neto" class="form-control"  required placeholder = "0.0">
						   </div>
						</div>
						<br>
						<br>
					</div>
					<input type ="hidden" id="idtienda_venta" name="idtienda_venta" value ="">
					</form>
			</div>
		</div>
      </div>
	  <div class="modal-footer">
      	<button type="button" id="ventas_send" class="btn btn-primary btn-label-right" onclick="vent.save_venta();">Registrar<span><i class="fa fa-usd"></i></span></button>
		<button type="button" class="btn btn-default btn-danger" data-dismiss="modal" aria-label="Close">Cerrar</button>	
      </div>
  </div>
</div>
</div>

<div class="modal" id="form_ventas_import_modal" tabindex="-1" role="dialog" aria-labelledby="form_ventas_import_modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_ventas_import_modal"><i class="fa  fa-dollar"></i> Registro de Ventas</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">

					<form method="post"  class="form-inline" name="form_ventas_import" id="form_ventas_import" enctype="multipart/form-data">
							Cargar Excel: <input type="file" name="file" id="file">
							<input type="hidden" id="idtienda2" name="idtienda2">
						 </form>
						 <br>
						 <div id="loading_file" style="margin-top:80px;display:none;margin-left:40%"><img src='img/devoops_getdata.gif'></div>
						<table style="display:none;" class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-footer">
							<thead>
								<tr>
									<th>Total Piezas <i class="fa fa-bookmark"></i> <div id="piez"></div></th>
									<th>Total Ticket <i class="fa  fa-pencil-square-o"></i> <div id="tick"></div></th>
									<th>Bruto <i class="fa fa-usd"></i> <div id="bt"></div></th>
									<th>IVA <i class="fa fa-usd"></i> <div id="iva"></div></th>
									<th>Total <i class="fa fa-usd"></i> <div id="totl"></div></th>
								</tr>
							</thead>
						</table>
						 <div class="alert alert-danger alert-dismissible" id="error_id_tienda" style="display:none;">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-ban"></i> Alert!</h4>
							Esta intentado cargar un documento correspondiente a otra tienda. Desea Moverlo a la tienda correspondiente? 
								<button type="button" id="moverfile" class="btn btn-primary btn-label-right" onclick="vent.mover_file();">Mover<span><i class="fa  fa-retweet"></i></span></button>
								<button type="button" class="btn btn-danger btn-label-right" data-dismiss="alert" aria-hidden="true">Cerrar<span><i class="fa  fa-close"></i></span></button>	
								<input type ="hidden" id="movertienda" name="movertienda" value ="">
						 </div>
						 <div id="respuesta_file"></div>
				</form>
			</div>
		</div>
      </div>
	  	<div class="modal-footer">
      	<button type="button" id="fileupload" class="btn btn-primary btn-label-right" onclick="vent.save_file();">Cargar Ventas<span><i class="fa fa-upload"></i></span></button>
		<button type="button" class="btn btn-default btn-danger" onclick="vent.cerrar_form();"  id="btn_cerar_mark">Cerrar</button>	
      </div>
  </div>
</div>
</div>
<div class="modal" id="form_ventas_planti_modal" tabindex="-1" role="dialog" aria-labelledby="form_ventas_planti_modal" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_ventas_planti_modal"><i class="fa  fa-dollar"></i> Registro de Ventas</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
				<form action="#" class="form-inline" name="form_ventas_planti" id="form_ventas_planti" autocomplete="off">
					<div class="form-group">
						<label class="col-sm-2 control-label">Desde</label>
						<div class="col-sm-2" style="z-index: 104000 !important;">
							<div>
							<input type='text' name="fecha_i" id="fecha_i" class="form-control input-daterange"  >
						   </div>
						</div>
					</div>
					<div class="form-group">					
						<label class="col-sm-2 control-label">Hasta</label>
						<div class="col-sm-2" style="z-index: 104000 !important;">
							<div>
							<input type='text' name="fecha_f" id="fecha_f" class="form-control input-daterange"  >
						   </div>
						</div>
						<br>
						<br>
					</div>
					</form>
					<br>
					<div class="form-group">	
					<div class="col-sm-2">
						<button type="submit" class="btn btn-warning btn-label-left" onclick="vent.genera_plantilla();">
							<span><i class="fa  fa-download"></i></span>
								Descargar
						</button>
					</div>
					</div>
					<br>
					<br>
					<input type="hidden" id="idtienda" name ="idtienda" value ="">
				
			</div>
		</div>
      </div>

  </div>
</div>
</div>
</body>
<!-- fin modal logo marca-->
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	$('select').select2();
	
}
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	$('#input_date').datepicker({setDate: new Date()});
	$('.form-control').tooltip();
	// Add Drag-n-Drop feature
	WinMove();
	// Create jQuery-UI tabs
	// Sortable for elements
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes	
});
$(function(){
        $("input[name='file']").on("change", function(){
            var formData = new FormData($("#form_ventas_import")[0]);
            var ruta = "ajax/mod_ventas/add_file.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta_file").html(datos);
                }
            });
        });
     });
</script>

<?
}
?>