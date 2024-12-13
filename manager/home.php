<?php
session_start();

//Validar que el usuario este logueado y exista un UID
if ( !(isset($_SESSION['autenticado'])  && isset($_SESSION['menu']) && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) )) 
{
	
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: index.php?error=ER001");
	exit;
}else{
include("dist/funciones/conectarbd.php");

/*$consulta = $obj->consultar("select `apps_cargo`.`cargo`  from `apps_user`, `apps_cargo`  
where `apps_cargo`.`id_cargo` = `apps_user`.`id_cargo` and `apps_user`.`id_user` = '".$_SESSION['uid']."'");*/
//$_SESSION['perfi'] = 1;
if($_SESSION['perfi']==1 || $_SESSION['perfi']==2){
	$where = "1=1";
	$from = "";
	$requerir = "";
}else{
	//$_SESSION['uid'] = 18;
	$where= "apps_user_acceso.`id_menu` = apps_menu.id_menu and apps_user_acceso.id_user = '".$_SESSION['menu']."' ";
	$from = ",apps_user_acceso";
	$requerir = ", apps_user_acceso.id_acceso";
	/*$where = "1=1";
	$from = "";
	$requerir = "";*/
	
}
//echo "sesion= ".$_SESSION['uid'];
$menu = mysqli_query($con,"select apps_menu.* ".$requerir."  from apps_menu ".$from." where ".$where."  
and  estatus = 1 order by orden asc");

/*echo "select apps_menu.* ".$requerir."  from apps_menu ".$from." where ".$where."  
and  estatus = 1 order by orden asc";*/

$hoy=date("d/m/Y");

		if($_SESSION['foto']=="" || $_SESSION['foto']==null){
				$foto= "img/icono_user.png";
		}else{
			$foto= "ajax/mod_user/files/".$_SESSION['foto'];
		}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
		<title>Black Pass Manager</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link href="plugins/bootstrap/bootstrap-theme.css" rel="stylesheet">
		<link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="dist/font-awesome/css/font-awesome.min.css">
		<link href="plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
		<link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
		<link href='plugins/fullcalendar/main.min.css' rel='stylesheet' />
		<link href="plugins/xcharts/xcharts.min.css" rel="stylesheet">
		<link href="plugins/select2/select2.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="dist/animatedModal/css/animate.min.css">
		<link rel="stylesheet" type="text/css" href="dist/pnotify/pnotify.custom.min.css">
		<link rel="stylesheet" type="text/css" defer href="dist/sweetalert/dist/sweetalert.css">
		<link rel="stylesheet" href="plugins/datepicker/bootstrap-datepicker.css">
		<link rel="stylesheet" href="plugins/jquery/jquery.dataTables.min.css">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="plugins/iCheck/all.css">

	
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
				<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
				<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
<body>
<!--Start Header-->
<div id="screensaver">
	<canvas id="canvas"></canvas>
	<i class="fa fa-lock" id="screen_unlock"></i>
</div>
<div id="modalbox">
	<div class="devoops-modal">
		<div class="devoops-modal-header">
			<div class="modal-header-name">
				<span><img src="img/50_50.png"></span>
			</div>
			<div class="box-icons">
				<a class="close-link">
					<i class="fa fa-times"></i>
				</a>
			</div>
		</div>
		<div class="devoops-modal-inner">
		</div>
		<div class="devoops-modal-bottom">
		</div>
	</div>
</div>
<header class="navbar">
	<div class="container-fluid expanded-panel">
		<div class="row">
			<div id="logo" class="col-xs-12 col-sm-2">
				<a href="home.php"><img src="img/50_50.png"></a>
			</div>
			<div id="top-panel" class="col-xs-12 col-sm-10">
				<div class="row">
					<div class="col-xs-8 col-sm-4">
						<a href="#" class="show-sidebar">
						  <i style="color:#fff;"class="fa fa-bars"></i>
						</a>
						
					</div>
					<div class="col-xs-4 col-sm-8 top-panel-right">
						<ul class="nav navbar-nav pull-right panel-menu">

							<li class="dropdown">
								<a href="#" class="dropdown-toggle account" data-toggle="dropdown">
									<div class="avatar">
										<img src="<?php echo $foto;?>" class="img-rounded" alt="avatar" />
									</div>
									<i class="fa fa-angle-down pull-right"></i>
									<div class="user-mini pull-right">
										<span class="welcome">Hola,</span>
										<span><?php echo $_SESSION['usuario'];?></span>
									</div>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">
											<i class="fa fa-user"></i>
											<span>Perfil</span>
										</a>
									</li>
							
									<li>
										<a href="out.php">
											<i class="fa fa-power-off"></i>
											<span>Logout</span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!--End Header-->
<!--Start Container-->
<div id="main" class="container-fluid">
	<div class="row">
		<div id="sidebar-left" class="col-xs-2 col-sm-2">
			<ul class="nav main-menu">
				<li>
					<a href="ajax/dashboard.php" class="active ajax-link">
						<i class="fa fa-dashboard"></i>
						<span class="hidden-xs">Dashboard</span>
					</a>
				</li>
				
				<?php
			 while($resultados = mysqli_fetch_assoc($menu)){
			if($_SESSION['perfi']==1 || $_SESSION['perfi']==2){
				$where2 = " id_menu = '".$resultados['id_menu']."'";
				$from2 = "";
				$requerir2 = "";
			}else{
				$where2= " m.id_menu = '".$resultados['id_menu']."'  and `id_acceso` = '".$resultados['id_acceso']."'  GROUP by m.id_details";
				$from2 = ",apps_user_adetails, apps_user_acceso a ";
				//echo "select apps_menu_details.* from apps_menu_details ".$from2." where estatus = '1' and ".$where2." ";
			}
			$smenu = mysqli_query($con,"select m.* from apps_menu_details m  ".$from2." where estatus = '1' and ".$where2." "); 
			?>
			   <li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="<?php echo $resultados['icono'];?>"></i>
						<span><?php echo ($resultados['menu']);?></span>
					</a>
					<ul class="dropdown-menu">
					<?php if(mysqli_num_rows($smenu)>0 )
					{
					while($sresultado= mysqli_fetch_assoc($smenu)){	
					 
					?>	
						<li><a class="ajax-link" href="<?php echo $sresultado['url'];?>"><i class="<?php echo $sresultado['icono'];?>"></i> <?php echo ($sresultado['detalle']);?></a></li>
					 <?php
					 }
					}?>
					</ul>
				</li>
            <?php
			}
			?>
			<div  class="col-xs-12">
          <br><br>
		  <p  style="color:#000000;">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> <a href ="https://www.itmedia.com.ve/">ITMEDIA</a>.
			
          </p>
        </div>
			</ul>
		</div>
		<!--Start Content-->
		<div id="content" class="col-xs-12 col-sm-10">
			<div class="preloader">
				<img src="img/devoops_getdata.gif" class="devoops-getdata" alt="preloader"/>
			</div>
			<div id="ajax-content"></div>
		</div>
		<!--End Content-->
	</div>
</div>
<!--End Container-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="http://code.jquery.com/jquery.js"></script>-->
<!--<script src="plugins/jquery/jquery-2.1.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>-->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/jquery/jquery.numeric.js"></script>
<script src="plugins/jquery/jquery.expander.js"></script>
<!--<script src="plugins/select2/select2.js">-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<script src="plugins/jquery/jquery.dataTables2.js" charset="utf8" type="text/javascript"></script>

<script src="plugins/justified-gallery/jquery.justifiedgallery.min.js"></script>
<script src="plugins/tinymce/tinymce.min.js"></script>
<script src="plugins/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript" language="javascript" defer src="dist/animatedModal/js/animatedModal.min.js"></script>
<script type="text/javascript" language="javascript" defer src="dist/pnotify/pnotify.custom.min.js"></script>
<script type="text/javascript" language="javascript" defer src="plugins/moment/moment.min.js" ></script>
<script type="text/javascript" language="javascript" defer src="plugins/datepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" language="javascript" defer src="plugins/datepicker/bootstrap-datetimepicker.es.js" ></script>
<script type="text/javascript" language="javascript" defer src="dist/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" language="javascript" defer src="plugins/jquery.maskedinput.min.js"></script>
<script type="text/javascript" language="javascript" defer src="plugins/html5form.min.js"></script>
<!-- FullCalendar -->
	<script src='plugins/moment.min.js'></script>
	<script src='plugins/fullcalendar/fullcalendar.min.js'></script>
	<script src='plugins/fullcalendar/fullcalendar.js'></script>
	
<script src='plugins/fullcalendar/main.min.js'></script>
<script src='plugins/fullcalendar/locale/es.js'></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="plugins/code/highcharts.js"></script>
<script src="plugins/code/modules/data.js"></script>
<script src="plugins/code/modules/drilldown.js"></script>
<script src="plugins/code/modules/exporting.js"></script>
<script src="plugins/code/modules/export-data.js"></script>
<script src="plugins/code/modules/accessibility.js"></script>
<!-- All functions for this theme + document.ready processing -->

<script src="js/add_marca.js"></script>
<script src="js/localidades_fun.js"></script>
<script src="js/tiendas_fun.js"></script>
<script src="js/tempo_fun.js"></script>
<script src="js/user_fun.js"></script>
<script src="js/ventas_script.js"></script>
<script src="js/pre_script.js"></script>
<script src="js/clientes_fun.js?v5989989"></script>
<script src="js/emple_fun.js"></script>
<script src="js/homescript.js"></script>
<script src="js/reservas_fun.js"></script>
<script src="js/report_graf.js"></script>	
<script src="js/pagos_fun.js"></script>
<script src="js/marketing_fun.js"></script>
<script src="js/periodo_fun.js"></script>
<script src="js/controlcambio.js"></script>
<script src="js/devoops.js"></script>
	<!-- jQuery Mapael -->
<script type="text/javascript">
function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
$(document).ready(function(){
   $(document).on("keydown", disableF5);
});
</script>

</body>
</html>
<?php
 }

?>