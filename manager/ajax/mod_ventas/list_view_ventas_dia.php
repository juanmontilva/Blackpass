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
$marcaid = $_GET['ubicar'];
$year = $_GET['mas'];
$mes = $_GET['mass'];
$tienda_sql= $obj->consultar("SELECT t.id_tienda,t.nombre_tienda,p.pais,m.marca,p.bandera ,mp.id_mp ,
						(CASE when t.status ='0'  then 'Inactiva' when t.status ='1' then 'Activa' END) AS Status
						FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m, apps_tiendas t 
						WHERE mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod and t.id_tienda = '".$marcaid."' 
						and t.id_mp = mp.id_mp");
$resultados4 = $obj->obtener_fila($tienda_sql,0);	
$mmarca= $obj->consultar("SELECT p.pais,m.marca,p.bandera ,mp.id_mp FROM `apps_marcas_x_pais` mp, apps_paises p, apps_marcas m  WHERE 
							mp.`id_marca` = m.id_marca and mp.`id_pais` = p.cod and mp.id_mp = '".$resultados4['id_mp']."'");
$resultados = $obj->obtener_fila($mmarca,0);
$paises= $obj->consultar("SELECT * FROM `apps_ventas_d` WHERE `id_tienda` = '".$marcaid."'  and MONTH(FECHA) = '".$mes."' and YEAR(FECHA) = '".$year."'
							ORDER BY FECHA ASC ");

?>
<div class="row">
	<div id="breadcrumb" class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Dashboard</a></li>
			<li><a href="#" onclick="vent.url_ventas();">Ventas Registradas</a></li>
			<li><a href="#" onclick="vent.carga_ventas_mes(<?php echo $resultados['id_mp'];?>)">Ventas Mensual <?php echo $resultados['marca'];?></a></li>
			<li><a href="#" onclick="vent.carga_ventas_mes(<?php echo $resultados['id_mp'];?>)">Ventas <?php echo $year." -".$mes;?>-<?php echo $resultados['marca'];?></a></li>
			<li><a href="home.php#ajax/mod_ventas/list_view_ventas_mes.php">Ventas <?php echo $year." -".$mes;?>-<?php echo $resultados4['nombre_tienda'];?></a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-shopping-cart"></i>
					<span>Detalle de Ventas Mensual para <strong><?php echo $resultados4['nombre_tienda'];?></strong> <img class="img-rounded" src="<?php echo $resultados['bandera'];?>" alt="<?php echo $resultados['pais'];?>" ></span>
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
			<div style="margin-left:70%;" >
			<button type="button" id="ventas_send" class="btn btn-primary btn-label-right" onclick="vent.registr_venta('<?php echo $resultados4['id_tienda'];?>');">Registrar<span><i class="fa fa-shopping-cart"></i></span></button>
			<button type="button" class="btn btn-success btn-label-right" >Guardar <span><i class="fa fa-save"></i></span></button>	
			<button type="button" class="btn btn-danger btn-label-right" >Borrar <span><i class="fa fa-trash-o"></i></span></button>	
			</div>
			<table class="table beauty-table table-hover">
					<thead>
						<tr align="center">
							<th>Borrar <input type="checkbox" id="delete_all" name = "delete_all" onclick="vent.activar_check();"></th>
							<th>Dia</th>
							<th>Piezas</th>
							<th>Ticket</th>
							<th>Bruto</th>
							<th>IVA</th>
							<th>NETO</th>
						</tr>
					</thead>
					<tbody>
						<?php  
						$i=0;
						while($resultados = $obj->obtener_fila($paises,0)){
							$i++;
						?>
						<tr align="center">
							<td><input type="checkbox" id="delete_v<?php echo $i;?>" name = "delete_v<?php echo $i;?>"></td>
							<td><input type="text" value="<?php echo $resultados['fecha'];?>"></td>
							<td><input type="number" value="<?php echo $resultados['piezas_v'];?>"></td>
							<td><input type="number" value="<?php echo $resultados['n_ticket'];?>"></td>
							<td><input type="number" value="<?php echo $resultados['bruto'];?>"></td>
							<td><input type="number" value="<?php echo $resultados['iva'];?>"></td>
							<td><input type="number" value="<?php echo $resultados['total'];?>"></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
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

<!-- fin modal logo marca-->
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
$('.beauty-table').each(function(){
		// Run keyboard navigation in table
		$(this).beautyTables();
		// Nice CSS-hover row and col for current cell
		$(this).beautyHover();
	});
	// Attach to click action for create JSON data from tables.
	$('.beauty-table-to-json').on('click', function(e){
		e.preventDefault();
		var table = $(this).closest('.box').find('table');
		Table2Json(table);
	});
</script>
<?php 
}
?>