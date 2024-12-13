<?php
session_start();
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: index.php?error=ER001");
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
		
$sql = mysqli_query($con,"SELECT * from apps_marcas where status = 1 and id_marca <> 105  order by marca ASC") ;
$semana = semana_i();
$j=0;
$hoy = date("Y-m-d");
$fi = $hoy." 00:01:00";
$ff = $hoy." 23:59:59";
$fdia = "";
$diah = date("d");
if(isset($_GET['dat'])){
	$fdia =$_GET['dat'];
	$hoy = date("Y")."-".date("m")."-".$fdia;
	$fi = $hoy." 00:01:00";
	$ff = $hoy." 23:59:59";
	$hoys  = $_GET['dat'];
	$diah = $_GET['dat'];
}

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
			<div class="col-md-8">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span><?php echo "Del: ".$semana[0]." al ".$semana[1]." ".date("M");?></span>
				</div>
				<div class="no-move"></div>
			</div>
			<div id="ow-server-footer">
			<div id="loading_home4" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
				<a href="#" class="col-md-3 col-sm-3 alert alert-warning text-center"><i class="fa fa-sun-o"></i> <b id="hoy_c"></b> <span>Citas Hoy </span></a>
				<a href="#" class="col-md-3 col-sm-3 alert alert-primary text-center"><i class="fa fa-calendar"></i> <b id="total_c"></b> <span>Vencimiento</span></a>
				<a href="#" class="col-md-3 col-sm-3 alert alert-success text-center"><i class="fa fa-certificate"></i> <b id="conc_c"></b> <span>Renovadas</span></a>
				<a href="#" class="col-md-3 col-sm-3 alert alert-danger text-center"><i class="fa fa-arrow-down"></i> <b id="anul_c"></b> <span>Perdidas</span></a>
			</div>
			</div>
			</div>
			<div class="col-md-4">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Revenue Lost <?php echo "".$semana[0]." al ".$semana[1]." ".date("M");?></span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="box-content">
			<div id="loading_home10" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
			<div id="ow-server-footer">
			<a href="#" style ="background:#FF4D4D;color:#fff;" class="alert-danger text-center"><i class="fa fa-exclamation"></i> <b id="hoy_c"></b> <span>0 USD </span></a>
			</div>
			</div>
			</div>
			</div>
			
			<div class="col-md-8">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-calendar-o"></i> <span>Revenue </span><b><?php echo "Del: ".$semana[0]." al ".$semana[1];?></b> </span>
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
			<div class="col-md-4">
			<div class="box"> 
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-table"></i>
					<span>Recovered <?php echo "".$semana[0]." al ".$semana[1]." ".date("M");?></span>
				</div>
				<div class="no-move"></div>
			</div >
			<div class="box-content">
			<div id="loading_home9" style="dispaly:none"><img src="img/devoops_getdata.gif"></div>
			<div id="ow-server-footer">
			<a href="#" style="background:#00B259;color:#fff;" class="text-center"><i class="fa fa-line-chart"></i> <b id="hoy_c"></b> <span>0 USD </span></a>
			</div>
			</div>
			</div>
			</div>
			</div>
			

			
			
			<div class="col-xs-12">
					<div class="box">
					<div class="box-header">
						<div class="box-name">
							<i class="fa fa-table"></i>
							<span>Vencimiento Semana <?php echo "".$semana[0]." al ".$semana[1]." ".date("M");?></span>
						</div>
						<div class="no-move"></div>
					</div>
					<div class="box-content" style="height:750px;">
					<div class="col-xs-12 text-center">
					<div class="horizontal-scrollable" style="height:60px;">
						<div class="row">
						<!--<button type="button" class="btn btn-info btn-app" onclick="marca.save_marc();">-<span><i class="fa fa-backward fa-2x"></i></span></button>
						<a href="dashboard.php" class="btn btn-success btn-app" ><span><i class="fa fa-sun-o fa-2x"></i></span></a>
						<button type="button" class="btn btn-info btn-app" onclick="marca.save_marc();">+<span><i class="fa fa-forward fa-2x"></i></span></button>
						-->
						<?php for($k=$semana[0];$k<=$semana[1];$k++){?>
						<a class="dot" <?php if($hoys==$k)echo "style='background:#444;'";?> onclick="cargar_info_r('<?php echo $cadena;?>',<?php echo $k;?>)"><div><?php echo $k;?></div></a>
						<?php 
						}
						?>
						</div>
						<div class="no-move"></div>
					</div>
			</div>
					<div class="horizontal-scrollable"> 
					<div class="row">
				<?php while($row3 = mysqli_fetch_assoc($sql)){
					
						
					$sql3 = mysqli_query($con,"SELECT c.codigo,c.nombres,c.fecha_pago, d.* 
								FROM apps_periodo p,apps_clientes c,apps_periodo_d d 
								WHERE p.ano = '".date("Y")."' 
								and c.id = d.idc 
								and p.id = d.idp 
								and c.plan = '".$row3['id_marca']."'
								and d.mes = '".date("m")."' 
								and c.fecha_pago = '".$diah."'  
								ORDER BY c.nombres ASC") ;
						
							if(mysqli_num_rows($sql3)!=0){
								$mesv = date("m");
					?>
					
				<div class="col-md-4">
					<div class="box">
						<div>
							<div style="background:#F5F5F5; opacity: 0.8;">
								<img width="10%" src = "ajax/mod_user/files/person.png">
								<span><b style ="color:#666"><?php echo $row3['marca'];?></b></span>
							</div>
							<div class="no-move"></div>
						</div>
						<?php 
							while($row=mysqli_fetch_assoc($sql3)){
							$fpago = $row['fecha_pago'];
							$status = $row['status'];
							$display = $display2 = "none";
							if($status==2 || $status ==1){
								$display = "block";
							}else if($status==3){
								$display2 = "block";
							}
							?>
						<div class="box-content">
							<div class="timeline">
							<div class="cont1" style="background:#00D9D9;">
							<div class="checkbox">
							<label>
								<input type="checkbox" <?php if($row['status']!=0) echo "checked"." disabled";?> onclick="hpagos(<?php echo $row['codigo']?>,'<?php echo $row['nombres']?>','<?php echo $mesv?>','<?php echo date("Y")?>');">
								<i class="fa fa-square-o small "></i>
							</label>
						</div>
							</div>
							<div class="cont2" style="background:#eeeeee">
							<span class="icono_s" style="display:<?php echo $display;?>;"><i alt="PAGADA" class="fa fa-check fa-2x"></i></span>
							<span class="icono_c" style="display:<?php echo $display2;?>;"><i class="fa fa-close fa-2x"></i></span>
							<p><b class="tituloc"></b>  <?php echo $row['nombres']?>
							
							</p>
							
							<p><?php echo "Fecha de Pago: ". $fpago;?></p>
							</div>
							</div>
						</div>
						<?php 
								}
							
						?>
					</div>
					
				</div>
			<?php
					}
				}
			?>
			</div>
			</div>
		</div>
		</div>
	</div>

<!--End Dashboard 1-->
<!--Start Dashboard 2-->
<!--End Dashboard 2 -->
<!--Modal Logo Marca-->
<div class="modal" id="Modal_transfer" tabindex="-1" role="dialog" aria-labelledby="Modal_transfer" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_transfer"><i class="fa fa-bookmark"></i>Transferir Cita</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
				 <div class="form-group">
						<label for="title" class="col-sm-2 control-label">Actual</label>
						<div class="col-sm-10">
						  <input type="text" name="profn" class="form-control" id="profn" readonly>
						</div>
				</div>
				<div class="form-group">
					<label for="title" class="col-sm-2 control-label">Transferir a:</label>
					 <div class="col-sm-10">
					 <select name="color" class="populate placeholder" id="id_prof">
						<option value="">Seleccionar</option>
						<?php $sqlp = mysqli_query($mysqli,"select * from apps_emple_s where estado = 1");
						while($rowp = mysqli_fetch_assoc($sqlp)){?>
						<option value="<?php echo $rowp['id'];?>"><?php echo $rowp['nombres'];?></option>
						<?php
						}
						?>
					</select>
					</div>
					</br></br></br></br>
				</div>				  
				  <input type="hidden" name="idcita" class="form-control" id="idcita">
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	<button type="button" class="btn btn-success btn-lg btn-label-right" onclick="transferir_cita_a();">Transferir Cita<span><i class="fa fa-save"></i></span></button>
      </div>
  </div>
</div>
</div>
<script type="text/javascript">
function MakeSelect2(){
	$('select').select2();
}
$(document).ready(function() {
	// Make all JS-activity for dashboard
	$("#monitor").load("ajax/mod_home/monitor.php");
	$('#loading_home9').hide(5000);
	$('#loading_home10').hide(5000);
	LoadSelect2Script(MakeSelect2);
	WinMove();
	data_home();

});
</script>
<?php 
}
?>