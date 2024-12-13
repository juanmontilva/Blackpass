<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Black Pass Manager</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="plugins/bootstrap/bootstrap.css" rel="stylesheet">
		<link href="plugins/bootstrap/bootstrap-theme.css" rel="stylesheet">
		<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
		<link href="plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
		<link href="plugins/xcharts/xcharts.min.css" rel="stylesheet">
		<link href="plugins/select2/select2.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<script src="plugins/jquery/jquery-2.1.0.min.js"></script>
		<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="plugins/bootstrap/bootstrap.min.js"></script>
		<script src="js/funciones.js"></script>
		 <script src="js/modal.js"></script>
		 <script src="dist/js/jquery.validate.js"></script>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
<body style="background:#000000;">
<div class="container-fluid">
	<div  id="page-login" class="row">
	<div style="background:#000000;"><br>
	<div style="background:#ffffff">.</div>
	</div>
		<div  class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<br><br>
			<div class="text-center">
			<img  src="img/logo.png" alt="logo"  />
			</div>
			<br><br>
			<div class="box">
				<div class="box-content_login ">
					<div class="text-center">
						<h3 class="page-header">Iniciar Sesión</h3>
					</div>
					<div class="form-group">
						<label class="control-label">Usuario</label>
						<input type="text" class="form-control" name="username" id="username" required />
					</div>
					<div class="form-group">
						<label class="control-label">Contraseña</label>
						<input type="password" class="form-control" name="password" id="password" required/>
						<div id="pass" style="display:none">
						<label class="centered info">Ingrese su Contraseña.
						</label>
						</div>
					</div>
					<div class="text-center">
						 <a class="btn btn-lg btn-primary btn-label-left" onclick="validar();">Acceso <i class="fa  fa-lock"></i> </a>
					</div>
				</div>
			</div>
		</div>
		
		<div  class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
          <br><br><br><br>
		  <p  style="margin-left:30%;color:#ffffff;">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> <a href ="https://www.itmedia.com.ve/">ITMEDIA</a>.
			
          </p>
        </div>
	</div>
	
	
  
       
		<footer>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
        </nav>
      </footer>
</body>
</html>
