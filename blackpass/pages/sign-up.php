<?php
session_start();

include("../script/dist/funciones/funciones.php");
include("../script/dist/funciones/api_ws.php");
include("../script/dist/funciones/conexion.php");
include("../script/dist/funciones/cript.php");
$sqlc = mysqli_query($con,"select * from apps_clientes ORDER BY id desc LIMIT 1");
$rowc = mysqli_fetch_assoc($sqlc);
$sql2 = mysqli_query($con,"select * from apps_marcas where id_marca <> 105 and id_marca<>112 and id_marca<> 113 ORDER BY marca asc ");
$id = $rowc['id']+1;
$id = "0000".$id;
$fecha_actual = date("Y-m-d");
$fpermi = date("Y-m-d",strtotime($fecha_actual."- 18 year"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">

  <title>
     Black Pass VIP
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../assets/css/modal.css" rel="stylesheet" />
  <link href="../assets/js/core/jquery-ui/jquery-ui.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons  --> 
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>


  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=<?php echo cadena();?>" rel="stylesheet" />
</head>
<body style="background:#000000;">
  <div class="container">
    <div class="row" >
      <div class="col-12" >
        <!-- Navbar -->
        <nav 	style="background:#000000;" class="navbar navbar-expand-lg  top-0 z-index-3 shadow position-absolute my-3 py-4 start-0 end-0 mx-4">
          <div  class="container-fluid">
             <div class="row">
				<div class="col-3"></div>
				  <div class="col-6">
				  <img  src="../assets/img/logos/logo.png">
				  </div>
				</div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
	<br>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-8 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-white">Bienvenido</h3>
                </div>
				<div style="display:none;" class="col-xs-12 col-sm-12 alert alert-success text-center" id="mensaje">
				  <h6 id="respuesta" class="text-white font-weight-bolder ms-2"></h6>
				</div>
                <div class="card-body">
				 <form  role="form" id="registro_c">  
				 <div class="form-group">
					<label style="color:#ffffff;font-family: 'Open Sans';" class="col-sm-12">Tipo Cliente</label>
					<div class="col-sm-12">
						<select name="tc" id="tc" class="form-control" onblur = "validar_t();">
							<option value="">---------</option>
							<option value="1"> Persona</option>
                            <option value="2"> Empresa </option> 
						</select>
					</div>
				</div>
				<div id="razon" style="display:none;" class="form-group">
					<label style="color:#ffffff; " class="col-sm-12 control-label">Razón Social</label>
					<div class="col-sm-12">
						<input type="text" name="rzs" id="rzs"  class="form-control"  placeholder="Razón Social"  >
					</div>
				</div>
				<div style="display:none;" class="form-group">
					<label style="color:#ffffff; " class="col-sm-6 control-label">Código Cliente</label>
					<div class="col-sm-6">
						<input type="text" name="codigo" id="codigo"  class="form-control" value="<?php echo $id; ?>" placeholder="12345679" readonly >
					</div>
				</div>
				<div class="form-group">
					<label style="color:#ffffff;" id="nombre_l" class="col-sm-12 control-label">Nombres</label>
					<div class="col-sm-12">
						<input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres" required>
					</div>
				</div>
				
				<div class="form-group">
					<label style="color:#ffffff;" class="col-sm-6 control-label">Identificación</label>
					<div class="col-sm-12">
						<div style="float:left">
						<select name="fp" id="fp" class="form-control">
							<option value="">----</option>
                            
						</select>
					</div>
					<div style="float:left">
						<input type="number" name="ssn" maxlength = "12" maxlength="12" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  id="ssn" class="form-control" placeholder="123456789" required>
					</div>
					</div>
				</div>
				<div id="nacimiento" class="form-group">
					<label style="color:#ffffff;" class="col-sm-6 control-label">Fecha de nacimiento</label>
					<div class="col-sm-12">
						<input type="date"  max="<?php echo $fpermi;?>" id="datepicker" name="fecha_nacimiento" class="input-group date form-control" data-date-format="dd-mm-yyyy" placeholder="00-00-0000" required>
					</div>
				</div>
				
				<div class="form-group">
					<label style="color:#ffffff;" class="col-sm-3 control-label">Dirección</label>
					<div class="col-sm-12">
						<input type="text"  name="direc" id="direc" class="form-control" placeholder="Dirección" required>
					</div>
				</div>
				<div class="form-group">
					<label style="color:#ffffff;" class="col-sm-3 control-label">E-mail</label>
					<div class="col-sm-12">
						<input type="mail"  name="mail" id="mail" class="form-control" placeholder="Mail" required>
					</div>
				</div>
				<div class="form-group">
					<label style="color:#ffffff;" class="col-sm-3 control-label">Teléfono</label>
					<div class="col-sm-12">
						<input type="number" onblur="validar_n();" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required>
					</div>
				</div>
				<div class="form-group">
					<label style="color:#ffffff;" class="col-sm-12 control-label">Tipo Producto</label>
					<div class="col-sm-12">
						<select name="tcliente" id="tcliente" class="form-control">
                           <option value="">----</option>
						</select>
					</div>
				</div>
				
				<input type="hidden" name="accion" id="accion" value="add" >
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-12">
					    <button type="button" id="btnFetch" onclick="add_cliente();" class="spinner-button btn bg-gradient-warning w-100 mt-4 mb-0">Registrame</button>
					</div>
				</div>
			</form>
                </div>
                
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-2">
    <div class="container">
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p  style="color:#ffffff;"class="mb-0">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script>  <a href="https://www.itmedia.com.ve/" class="font-weight-bold" >ITMEDIA.</a>
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
   <script src="../assets/js/core/jquery/jquery-3.2.1.min.js"></script>
   <script src="../assets/js/core/jquery-ui/jquery-ui.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/registre.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }


	</script>
  <script src="../assets/js/soft-ui-dashboard.js?v=1.0.4"></script>
</body>

</html>