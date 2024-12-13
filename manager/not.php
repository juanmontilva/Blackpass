<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title>24Hopen</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="plugins/bootstrap/bootstrap-theme.css" rel="stylesheet">
		<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="dist/pnotify/pnotify.custom.min.css">
		<link href="css/style.css" rel="stylesheet">
<div class="row">
<div class="col-xs-12">
<div class="col-xs-6">
	<div class="box">
	<div class="box-header">
	</div>
		<div class="box-content">
		<div class="form-group">
			<label class="col-sm-3 control-label">Identificación</label>
			<div class="col-sm-4">
			<input type="text" name="codigo" id="codigo"  class="form-control" placeholder="12345679" required>
			</div>
				<button id="btn_cotizar" onclick="asend();" class="btn btn-lg btn-success btn-label-center"><span><i class="fa fa-credit-card"></i></span> Enviar</button>
		</div>
		</div>

	</div>
</div>
	<div class="col-xs-6">
	<div class="box">
		<div class="box-header">
		Chat Bot		
		</div>
	<div class="box-content" style="height:1000px;">
		<div class="col-xs-6" id="izq">
		xxxx
		</div>
		<div class="col-xs-6"  id="der">
		dddd
		</div>
	</div>
	</div>
</div>
</div>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" defer src="dist/pnotify/pnotify.custom.min.js"></script>

<script>
function asend(){
	var respuesta = $("#codigo").val();
	var left = '<div  style="margin-left:0px;margin-top:25px;background:#BFDFFF;">'+respuesta+'</div>';
	$("#izq").append(left);
				$.ajax({
					 url: 'execute.php',
					 type: "GET",
					 dataType : 'json',
					 cache: false,
					 data: {
							respuesta : respuesta
							},
					
									beforeSend: function(){
									messageEmail = new PNotify({
									title: 'Enviando Mensaje ',
									text: 'Por favor espere.',
									type: 'info',
									delay : 4000
								});
								
						
							},
					 success: function(json) {
							if(json.response == 'ok'){
								messageEmail = new PNotify({
										title: 'Cargando datos',
										text: ' Datos Cargados',
										type: 'success'
									});
								var der = '<div  style="margin-left:40%;background:#ccc;margin-top:20px;">'+json.mensaje+'</div>';
								$("#der").append(der);
							}else{
								messageEmail = new PNotify({
										title: 'Ocurri\u00f3 un problema',
										text: 'La reserva no se pudo crear',
										type: 'error'
									});
								  
							}
						}
			});
	

}
</script>