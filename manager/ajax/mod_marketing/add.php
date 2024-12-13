<?php
session_start();
include("../../dist/funciones/funciones.php");
//Validar que el usuario este logueado y exista un UID
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../../index.php?error=ER001");
	//exit;
}else{
include("../../dist/funciones/conexion.php");

$sql = mysqli_query($con, "SELECT * FROM apps_marcas WHERE status = 1 and id_marca <> 105");
?>
<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" media="screen">
 <script type="text/javascript" src="dist/emoji/prettify.js"></script>
  <link rel="stylesheet" type="text/css" href="dist/emoji/emojionearea.min.css" media="screen">
  <script type="text/javascript" src="dist/emoji/emojionearea.js"></script>
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
			<li><a href="home.php#ajax/mod_marcas/list_view_marcas.php">Registro Campañas</a></li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-bookmark"></i>
					<span>Registro de Campañas</span>
				</div>
				<div class="no-move"></div>
			</div>
			<div class="col-xs-6">
			<div class="box-content no-padding">
			<form method="post" id="registro_c" class="form-horizontal" enctype="multipart/form-data">
				<div class="form-group">
					<label class="col-sm-6 control-label">Identificación</label>
					<div class="col-sm-6">
						<input type="text" name="codigo" id="codigo"  class="form-control" value ="<?php echo cadena(6)?>" readonly required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label">Titulo</label>
					<div class="col-sm-6">
						<input type="text" name="nombres" id="nombres" class="form-control" placeholder="Campaña" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label">Fecha de Envio</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="datetime_example" placeholder="Date and Time">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label">Idioma</label>
					<div class="col-sm-6">
						<select id="bd" name="bd" multiple >
						<option value="">-Selecciona una BD</option>
						<option value="1">Español</option>
						<option value="2">English</option>
						
						</select>
					</div>
					</div>
					<div class="form-group">
								<label class="col-sm-6 control-label">Sexo</label>
								<div class="col-sm-6">
									<select id="sex" name="sex"  >
									<option value="A">ALL</option>
									<option value="F">F</option>
									<option value="M">M</option>
									
									</select>
								</div>
					</div>
					<div class="form-group">
								<label class="col-sm-6 control-label">Servicios</label>
								<div class="col-sm-6">
									<select id="serv" name="serv" multiple>
									<option value="">-</option>
									<?php
									while($row3=mysqli_fetch_assoc($sql) ){?>
									<option value="<?php echo $row3['id_marca']?>"><?php echo $row3['marca']?></option>
									<?php
									}
									?>
									
									</select>
								</div>
					</div>
					<div class="form-group" style="display:none">
								<label class="col-sm-6 control-label">TimeLine</label>
								<div class="col-sm-6">
									<select id="time" name="time" >
									<option value="">-</option>
									<option value="1" select>Polizas Vencida</option>
									<option value="15">Faltan <= 15Dias </option>
									<option value="30">Faltan <= 30Dias </option>
									<option value="2">Cumpleaños</option>
									
									</select>
								</div>
					</div>
				
				<div class="form-group">
					<label class="col-sm-6 control-label">Texto</label>
					<div class="col-sm-6">
					  <div class="col-6">
					   
						<textarea id="example1" name="example1">
						Lorem ipsum dolor 😍 sit amet, consectetur 👻 adipiscing elit, 🖐 sed do eiusmod tempor ☔ incididunt ut labore et dolore magna aliqua 🐬.
						</textarea>
					  </div>
					  <div class="col-6">
						<div id="container"></div>
					  </div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-6 control-label">Multimedia</label>
					<div class="col-sm-6">
					  <input type="file" name="file" id="file">
					 
					</div>
				</div>
				<input type="hidden" name="accion" id="accion" value="add" >
				<div style="margin-left:5%;" class="form-group">
					<label class="col-sm-6 control-label">&nbsp;</label>
					<div class="col-sm-12">
						<button type="button" onclick="add_campa()" name="add" id="add" class="btn btn-lg btn-success" ><i class='fa fa-save'></i>  Crear Campaña</button>
					</div>
				</div>
			</form>
			</div>
		</div>
		<div class="col-xs-3"></div>
		<div class="col-xs-3" style = "z-index:0;">
		<div class="box">
		<div class="box-content no-padding" style="background:#EDE6DC;">
		<div style ="width:270px; height:380px; background:#EDE6DC;" id="respuesta"></div>
		<div class="col-xs-12" id="texto_c" style="background:#EDE6DC;"> </div>
		</div>
		</div>
		</div>
	</div>
</div>
	
	<script>
function AllTables(){
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	$('select').select2();
}
	LoadTimePickerScript(AllTimePickers);
	$('.date').datepicker({
		format: 'dd-mm-yyyy hh:mm:00',
	})
	LoadDataTablesScripts(AllTables);
	$(document).ready(function() {
		
      /*$("#demo1").emojioneArea({
        container: "#container",
		pickerPosition: "left",
        hideSource: false,
        useSprite: false
      });*/
	   $("#example1").emojioneArea({
		  autoHideFilters: true,
		  pickerPosition: "left",
		});
    });
	function copiar_text(){
		 $('#example1').clone().appendTo('#texto_c');
	}
	
	$(function(){
        $("input[name='file']").on("change", function(){
            var formData = new FormData($("#registro_c")[0]);
            var ruta = "ajax/mod_marketing/add_logo.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta").html(datos);
					copiar_text();
                }
            });
        });
     });
	</script>
</body>
</html>
<?php
}
?>