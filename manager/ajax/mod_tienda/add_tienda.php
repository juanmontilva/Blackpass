<?
session_start();
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
$idmpais = mysql_real_escape_string($_GET["marca"]);
$acc = mysql_real_escape_string($_GET["accion_"]);
$mmarca= $obj->consultar("select px.*, m.marca from apps_marcas_x_pais px, apps_marcas m
						where px.id_mp = $idmpais and m.id_marca = px.id_marca");
		//$sql1="select * from apps_marcas where marca like '%".$marca."%' ";
if($resultado2 = $obj->num_rows($mmarca,0)!=0){	
	$resultado2	= $obj->obtener_fila($mmarca,0) ;
	$loca= $obj->consultar("select * from apps_localidades where id_pais = '".$resultado2['id_pais']."' and status = 1");
}
?>

<form id="defaultForm"  action="validar.html" method="post"  class="form-horizontal">
					<fieldset>
						<legend>Datos de Registro</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Razon Social</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" required name="txtrazon" id="txtrazon"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
							</div>
						</div>
							<div class="form-group">
							<label class="col-sm-3 control-label">Nombre Tienda</label>
							<div class="col-sm-5">
								<input type="text" placeholder="<?=$resultado2['marca']?>-" class="form-control" required name="txtnombre" id="txtnombre"  style="text-transform:uppercase;" readonly> 
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Información Tienda</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Telefono</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtphone" id= "txtphone" placeholder="0212-1231212" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-5">
								<input type="email" class="form-control" name="txtemail" id= "txtemail" placeholder="TIENDA@<?=$resultado2['marca']?>.COM" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" />
								<input type="hidden" class="form-control" name="accion" id= "accion" value="add" />
								<input type="hidden" class="form-control" name="idmarca" id= "idmarca" value="<?=$resultado2['id_marca']?>" />
								<input type="hidden" class="form-control" name="marca" id= "marca" value="<?=$resultado2['marca']?>" />
							    <input type="hidden" class="form-control" name="ubicaid" id= "ubicaid" value="<?=$idmpais ?>" />
							    <input type="hidden" class="form-control" name="idtienda" id= "idtienda" value="" />
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Localidad</legend>
						<div class="form-group">
							<label class="col-sm-3 control-label">Dirección</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="txtdir" id= "txtdir" placeholder="PISO 1 LOCAL 1" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Selección de Localidad</label>
							<div class="col-sm-5">
							<select id="s2_local"  class="populate placeholder" onchange="tien.nombre_tienda(this);" onfocus="this.selectedIndex = -1;">
							<option>--Selecciona--</option>
							<? 
									$i=0;
									while($resultados3 = $obj->obtener_fila($loca,0)){
									$i++;
							?>
								<option  value="<?=$resultados3['localidad']?>"><?=$resultados3['localidad']?></option>
									<?
									}?>								
							</select>
							<div id="mostrar_error"></div>
							<input type="hidden" class="form-control" name="nump" id= "nump" value="<?=$i?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Mts2</label>
							<div class="col-sm-5">
								<input type="number" class="form-control" name="txtmt" id= "txtmt" placeholder="20" />
							</div>
						</div>
					</fieldset>
					<div class="form-group">
						<div class="col-sm-9 col-sm-offset-3">
							<button type="button" class="btn btn-primary btn-label-right" onclick="tien.save_tienda();">Guardar<span><i class="fa fa-save"></i></span></button>
						</div>
					</div>
				</form>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	LoadSelect2Script(MakeSelect2);
}

function MakeSelect2(){
	$('select').select2();
	$('.dataTables_filter').each(function(){
		$(this).find('label input[type=text]').attr('placeholder', 'Search');	
	});
}
$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	WinMove();
	// Create jQuery-UI tabs
	// Sortable for elements
	$("#ui-spinner").spinner();
	// Add Drag-n-Drop to boxes	
});

     
</script>				
<?}	?>			