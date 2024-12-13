<?php
session_start();
if ( !(isset($_SESSION['autenticado'])  && isset($_SESSION['menu']) && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) )) 
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../index.php?error=ER001");
	//exit;
}else{
include("../dist/funciones/funciones.php");
include("../dist/funciones/conexion.php");
$mes = date("M");
$hoys = date("d");
$dia = date("Y-m-d");
$cadena = cadena2();
$where = "";
if($_SESSION['perfi'] ==2){
	$where = " where id = '".$_SESSION['uid']."'";
}else{
	$where = " where 1 = 1";
}
		
		
		
			$sql = "SELECT * from apps_emple_s ".$where."  order by id ASC" ;
		   
		   $sql2 = "SELECT * FROM `apps_ventas_demo` group by unidades order by unidades desc  " ;
			
		//echo $sql;
			$result = mysqli_query($con, $sql);
			$result2 = mysqli_query($con, $sql2);
	
			$semana = semana_i();
			$j=0;
$hoy = date("Y-m-d");
$fi = $hoy." 00:01:00";
$ff = $hoy." 23:59:59";
$fdia = "";
if(isset($_GET['dat'])){
	$fdia =$_GET['dat'];
	$hoy = date("Y")."-".date("m")."-".$fdia;
	$fi = $hoy." 00:01:00";
	$ff = $hoy." 23:59:59";
	$hoys  = $_GET['dat'];
}
$s = semanas();
$mmarca= "SELECT r.*, c.nombres,c.id,c.plan FROM apps_pagos r, apps_clientes c 
			where r.idc = c.id 
			and  fecha BETWEEN  '".$s[0]."' and '".$s[1]."'
			ORDER BY r.status asc";
			//echo $mmarca;
$fecha_p = date("d/m/Y");
$sql_u = mysqli_query($con,"SELECT * FROM apps_localidades where id_pais = 1 and status = 1");
?>
<style>
 /* The heart of the matter */ 
.horizontal-scrollable > .row { 
overflow-x: auto; 
white-space: nowrap; 


}          
.horizontal-scrollable > .row > .col-md-4 { 
   display: inline-block; 
   float: none; 
} 
 ::-webkit-scrollbar {
    width: 1px;
	height:4px;
	background:#666;
	opacity:0.5;
}
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 1px rgba(0,0,0,0.5); 
    border-radius: 5px;
}
::-webkit-scrollbar-thumb {
    border-radius: 5px;
    -webkit-box-shadow: inset 0 0 1px rgba(0,0,0,0.5);
}
.timeline{
	border-style: solid;
    border-color: #cccccc;
	width:100%;
	height :60px;
	border-width: 1px 1px 1px 1px;
}
.cont1{
 float:left;
 background:#dddccc;
 width:8%;
 height :60px;
 padding-left:5px;
 padding-top:12px;
}
.cont2{
 float:left;
 backgroung:#EEEEEE;
 width:92%;
 overflow: hidden;
 text-overflow: ellipsis;
 padding-left:5px;
 height :60px;
 font-size:12px;
 
}
.tituloc{
	color:444444;
	font-size:14px;
}
.icono_t{
    position:absolute;
    bottom:12px;
    right:16px;
	color:red;
}
.icono_t2{
    position:absolute;
    bottom:12px;
    right:34px;
	color:#43A047;
}
.icono_s{
    position:absolute;
    top:12px;
    right:18px;
	color:#43A047;
}
.icono_c{
    position:absolute;
    top:12px;
    right:18px;
	color:#FF4D4D;
}
.dot {
  height: 50px;
  width: 50px;
  background-color: #D6DBD7;
  border-radius: 50%;
  display: inline-block;
  padding-top:15px;
  color:#00BFA5;
  font-size:16px;
  font-weight:bold;
}
.dot a.active{
  height: 50px;
  width: 50px;
  background-color: #444444;
  border-radius: 50%;
  display: inline-block;
  padding-top:15px;
  color:#00BFA5;
  font-size:16px;
  font-weight:bold;
}
</style>
<!--Start Breadcrumb-->
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<ol class="breadcrumb">
			<li><a href="home.php">Home</a></li>
			<li><a href="#">Dashboard</a></li>
		</ol>
	</div>
</div>
<!--End Breadcrumb-->
<!--Start Dashboard 1-->
			<div class="row">
			<div class="col-md-12">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-calendar-o"></i> <span>Ingresos </span><b><?php echo "Del: ".$semana[0]." al ".$semana[1];?></b> </span>
				</div>
				<div class="no-move"></div>
			</div>
				<div id="ow-server-footer">
				<a href="#" class="col-md-4 col-sm-4 alert alert-info  text-center"><span>Confirmado <i class="fa fa-check"></i> </span><b id="m_c"></b> </a>
				<a href="#" class="col-md-4 col-sm-4 alert alert-primary  text-center"><span>Proyectado <i class="fa fa-bar-chart "></i></span> <b id="m_p"></b> </a>
				<a href="#" class="col-md-4 col-sm-4 alert alert-success  text-center"> <span>Total Estimado <i class="fa  fa-dollar "></i></span> <b id="m_e"></b></a>
			</div>
			</div>
			</div>
			<div class="col-md-6">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Registrar Pago</span>
					
				</div>
				
				<div class="no-move"></div>
			</div>
			<div class="box-content">
			
			<div class='alert alert-primary'>Nuevo Pago</div>
			<div style="display:none;" id="div_msg2"class='alert alert-danger'>LA TARJETA NO TIENE SALDO SUFICIENTE</div>
			<div id="loading_home10" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
			<div id="ow-server-footer">
			<div class="form-group">
				<label class="col-sm-4 control-label">Número Tarjeta</label>
				<div class="col-sm-6">
					<input type="text" onblur = "validar_card2();" class="form-control" name="tdcv"  id="tdcv" data-toggle="tooltip" data-placement="bottom" title="N° Tarjeta"> 
				</div>
				<div id="loading" style="margin-top:80px;display:none;margin-left:40%"><img src='img/devoops_getdata.gif'></div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Cliente</label>
					<div class="col-sm-6">
					<input type="text" class="form-control" name="txtcl" id="txtcl" readonly data-toggle="tooltip" data-placement="bottom" title="Nombre del Cliente"> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Saldo (USD)</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="txtsaldo" id="txtsaldo" readonly data-toggle="tooltip" data-placement="bottom" title="Saldo"> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Monto a Pagar</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="monto" id="monto"  data-toggle="tooltip" data-placement="bottom" title="Monto a Pagar"> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Comprobante</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="ordenid" id="ordenid" readonly data-toggle="tooltip" data-placement="bottom" title="Número de Orden"> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Ubicación</label>
					<div class="col-sm-6">
					<select id="ubic" name="ubic" >
					<?php
						while($row_u = mysqli_fetch_assoc($sql_u)){
					?>
					<option value="<?php echo $row_u['id_loc'];?>"><?php echo $row_u['localidad'];?></option>
					<?php
						}
					?>
					</select>
					<br><br>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Descripcion</label>
					<div class="col-sm-8">
						<textarea class="form-control" name="descrip" id="descrip"  data-toggle="tooltip" data-placement="bottom" > </textarea>
					</div>
				</div>
				<input type="hidden" id="ncliente" name="ncliente" value ="">
				<button type="button" id="btn_self" class="btn btn-lg btn-success btn-label-right" onclick="payment_cl();">Procesar Pago</button>
			</div>
			</div>
			</div>
			</div>
			<div class="col-md-6">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Transacciones <?php echo date("Y-m-d");?></span>
				</div>
				<div class="no-move"></div>
			</div>
			<div  class="box-content">
			<table style = "overflow: scroll;" class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-2">
					<thead>
						<tr>
							<th>Comprobante</th>
							<th>Cliente </th>
							<th>Membresia </th>
							<th>Monto </th>
							<th>Ubicación</th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$sqlc = mysqli_query($con,"SELECT p.*, c.nombres, l.localidad, m.identificador
										FROM apps_pay_o p, apps_clientes c, 
										apps_localidades l, apps_marcas m
										WHERE p.fecha = '".$dia."' 
										and c.id = p.idc
										and l.id_loc = p.idl
										and c.plan = m.id_marca
										ORDER BY hora DESC");
						if(mysqli_num_rows($sqlc)!=0){
							while($r2 = mysqli_fetch_assoc($sqlc) ){
						?>
						<tr>
							<td><?php echo $r2['orden'];?></td>
							<td><?php echo $r2['nombres'];?></td>
							<td><?php echo $r2['identificador'];?></td>
							<td><?php echo $r2['monto'];?></td>
							<td><?php echo $r2['localidad'];?></td>
						</tr>
						<?php
							}
						}
						?>
					</tbody>
					
				</table>
			</div>
			</div>
			</div>
		</div>
			<div class="col-xs-12">
					<div class="box">
					<div class="box-header">
						<div class="box-name">
							<i class="fa fa-table"></i>
							<span>Recargas Entrantes <?php echo date("M")." ".$semana[0]." al ".$semana[1];?></span>
						</div>
						<div class="no-move"></div>
					</div>
					<div style="height:790px;">
			  <div >
			  <br>
				<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-5">
					<thead>
						<tr>
							<th>Registro N°</th>
							<th>Cliente <i class="fa fa-user"></i></th>
							<th>Membresia <i class="fa fa-bookmark"></i></th>
							<th>Monto <i class="fa fa-dollar"></i></th>
							<th>Fecha <i class="fa fa-calendar-o"></i></th>
							<th>Status <i class="fa fa-globe"></i></th>
							<th>Herramientas <i class="fa fa-flag"></i></th>
						</tr>
					</thead>
					<tbody>
					<!-- Start: list_row -->
					<?php 
						$query_u = mysqli_query($con,$mmarca);
						if(mysqli_num_rows($query_u)!=0){
							$j=0;
						 while($resultado = mysqli_fetch_assoc($query_u) ){
							 $j++;
							 $sql = "select h.* from apps_marcas h
							 where h.id_marca  = '".$resultado['plan']."' ";
							//echo $sql."<br>";
							$query_sql = mysqli_query($con,$sql);
							$resultado2 = mysqli_fetch_assoc($query_sql);
						?>
						<tr>
							<td><?php echo $j?></td>
							<td>
							<?php echo ($resultado['nombres'])?></td>
							<td>
							<?php echo utf8_encode($resultado2['marca'])?>
							</td>
							<td>
							<?php echo $resultado['monto']?>
							</td>
							<td>
							<?php echo $resultado['fecha']?>
							</td>
							<td><?php if($resultado['status']==0){
								echo "<div class='alert alert-info'>Registrada";
							}else if($resultado['status']==1){
								echo "<div class='alert alert-info'>Por Validar";
							}else if($resultado['status']==2){
								echo "<div class='alert alert-success'>Pago Validado";
							}else if($resultado['status']==3){
								echo "<div class='alert alert-danger'>Pago Rechazado";
							}else if($resultado['status']==4){
								echo "<div class='alert alert-danger'>Cancelada";
							}
							?></div>
							</td>
							<td>
							<?php if($resultado['idc']!=1 && $resultado['status']!=2){?>
							<button type="button" class="btn btn-lg btn-success btn-label-right" onclick="rgistrar_pago(<?php echo $resultado['idc'];?>,<?php echo "'".$resultado['orden']."'";?>)">Validar Pago<span><i class="fa fa-dollar"></i></span></button></td>
							<?php 
							}
							?>
						</tr>
						<?php
							}
						}
						?>
					
					<!-- End: list_row -->
					</tbody>
					
				</table>
					</div>
					</div>
		</div>
	</div>
<!--
<div class="modal" id="form_cambio_modal" tabindex="-1" role="dialog" aria-labelledby="form_cambio_modal" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Validar Pago</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
				<div class="box-content">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<legend>Validar Pago</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco Origen</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="borigen" readonly id="borigen" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco Destino</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="bdestino" readonly id="bdestino" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Referencia</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="referencia" readonly id="referencia" data-toggle="tooltip" data-placement="bottom" title="referencia"> 
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Monto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="montos" id="montos" readonly data-toggle="tooltip" data-placement="bottom" title="J-122345"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Localizador</label>
							<div class="col-sm-5">
								<input type="text" readonly class="form-control" name="localizador" id="localizador" data-toggle="tooltip" data-placement="bottom" title="Telefeono"> 
							</div>
						</div>
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="clientexy" name="clientexy" value ="">
							<button type="button" class="btn btn-primary" onclick="validar_p(2);">Validar</button>
							<button type="button" class="btn btn-danger" onclick="validar_p(3);">Rechazar</button>
						</div>
					</div>
				</form>
			</div>
		</div>

      </div>
  </div>
</div>
</div>
!-->

<div class="modal" id="form_cambio_modal" tabindex="-1" role="dialog" aria-labelledby="form_cambio_modal" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_cambio_modal"><i class="fa fa-globe"></i> Validar Pago</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
				<div class="box-content">
				<form id="form_upd_cambio" name ="form_upd_cambio" method="post" action="validators.html" class="form-horizontal">
					<fieldset>
						<div id="form_p" style="display:none;">
						<legend>Validar Recarga</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco Origen</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="borigen" readonly id="borigen" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banco Destino</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="bdestino" readonly id="bdestino" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						</div>
						<div id="form_p3" style="display:none;">
						<div class="form-group">
							<label class="col-sm-3 control-label">Contacto</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="contac" readonly id="contac" data-toggle="tooltip" data-placement="bottom" title="Banco"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Dirección</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="dir3" readonly id="dir3" data-toggle="tooltip" data-placement="bottom" title="dir3"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Telefono</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="phone" readonly id="phone" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Hora</label>
							<div class="col-sm-5">
								<input type="text" class="form-control"name="hora" readonly id="hora" data-toggle="tooltip" data-placement="bottom" title="Nombre"> 
							</div>
						</div>
						</div>
						<div id="form_p2" style="display:none;">
					
						<div class="form-group">
							<label class="col-sm-3 control-label">Referencia</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="referencia" readonly id="referencia" data-toggle="tooltip" data-placement="bottom" title="referencia"> 
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Monto</label>
							<div class="col-sm-5">
								<input type="number" class="form-control" name="montos" id="montos" readonly data-toggle="tooltip" data-placement="bottom" title="J-122345"> 
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Localizador</label>
							<div class="col-sm-5">
								<input type="text" readonly class="form-control" name="localizador" id="localizador" data-toggle="tooltip" data-placement="bottom" title="Telefeono"> 
							</div>
						</div>
					</div>
						
						
						
					</fieldset>
					<div class="form-group" id="botonera">
						<div class="col-sm-9 col-sm-offset-3">
						<input type="hidden" id="clientexy" name="clientexy" value ="">
							<button type="button" class="btn btn-primary" onclick="validar_p(2);">Validar</button>
							<button type="button" class="btn btn-danger" onclick="validar_p(3);">Rechazar</button>
						</div>
					</div>
				</form>
			</div>
		</div>

      </div>
  </div>
</div>
</div>
<!--End Dashboard 1-->
<!--Start Dashboard 2-->
<!--End Dashboard 2 -->
<!--Modal Logo Marca-->

<script type="text/javascript">
function MakeSelect2(){
	$('select').select2();
}
function AllTables(){
	TestTable1();
	TestTable2();
	LoadSelect2Script(MakeSelect2);
}
$(document).ready(function() {
	// Make all JS-activity for dashboard
	//$("#monitor").load("ajax/mod_home/monitor.php");
	$('#loading_home9').hide(5000);
	$('#loading_home10').hide(5000);
	LoadSelect2Script(MakeSelect2);
	LoadDataTablesScripts(AllTables);
	WinMove();
	data_home();
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
	//$("#tdcv").focus();

});
</script>

<?php 
}
?>