<?php
session_start();
include("../script/dist/funciones/funciones.php");
//if (!isset($_SESSION['autenticado']) && isset($_SESSION['uid']) && isset($_SESSION['tc'])  && ($_SESSION['xy'])!=0  ) 
if (!(isset($_SESSION['autenticado']) && isset($_SESSION['uid']) && isset($_SESSION['tc'])  && ($_SESSION['xy'])!=0  ) )

{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: sign-in.html?error=ER001&".cadena2());
	//exit;
}else{
include("../script/dist/funciones/api_ws.php");
include("../script/dist/funciones/conexion.php");
include("../script/dist/funciones/cript.php");
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
		
$semana = semana_i();

$sqlc = mysqli_query($con,"SELECT p.*, c.nombres, l.localidad, m.identificador
										FROM apps_pay_o p, apps_clientes c, 
										apps_localidades l, apps_marcas m
										WHERE p.idc = '".$_SESSION['xy']."' 
										and c.id = p.idc
										and l.id_loc = p.idl
										and c.plan = m.id_marca
										ORDER BY hora DESC");

$sql = mysqli_query($con,"SELECT * FROM apps_zzz where idc = '".$_SESSION['xy']."' ");
$sql2 = mysqli_query($con,"SELECT SUM(monto) as monto FROM apps_pagos where idc = '".$_SESSION['xy']."' and status <> 2 ");
$saldo = $monto = 0;
if(mysqli_num_rows($sql)==0){
	$saldo = 0;
}else{
	$row = mysqli_fetch_assoc($sql);
	$saldo = $row['zzz'];
}
if(mysqli_num_rows($sql2)==0 ){
	$monto = 0;
}else{
	$row2 = mysqli_fetch_assoc($sql2);
	if( $row2['monto']!=null ||  $row2['monto']!=0)
	$monto = $row2['monto'];
	else
	$monto = 0;
}
$fecha_p = date("d/m/Y");
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
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.5" rel="stylesheet" />
	
</head>

<body style ="background:#ffffff;" id="ajax-content" class="g-sidenav-show ">
  <aside  style ="background:#ffffff;"  class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      
    </div>
    <hr class="horizontal dark mt-4">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul  class="navbar-nav">
    <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Mi Menú</h6>
        </li>
		 <li class="nav-item">
          <a style ="background:#000000; color:#ffffff;"  class="nav-link  active " href="../pages/home.php?<?php echo cadena2()."&fe=".$encriptar($_SESSION['xy'])?>">
            <div style="background:#ffffff;" class="icon icon-shape icon-sm shadow border-radius-md  text-center me-2 d-flex align-items-center justify-content-center">
              <i style="color:#000000;" class="fa fa-home fa-2"></i>
            </div>
            <span style="color:#ffffff;" class="nav-link-text ms-1">Home</span>
          </a>
        </li>
		<br>
        <li class="nav-item">
          <a style ="background:#000000; color:#ffffff;"  class="nav-link  active " href="../pages/billing.php?<?php echo cadena2()."&fe=".$encriptar($_SESSION['xy'])?>">
            <div style="background:#ffffff;" class="icon icon-shape icon-sm shadow border-radius-md  text-center me-2 d-flex align-items-center justify-content-center">
              <i style="color:#000000;" class="fa fa-dollar fa-2"></i>
            </div>
            <span style="color:#ffffff;" class="nav-link-text ms-1">Mis Recargas</span>
          </a>
        </li>
		<br>
		<li class="nav-item">
          <a style ="background:#000000; color:#FFFFFF;"  class="nav-link  active " href="../pages/transaccion.php?<?php echo cadena2()."&fe=".$encriptar($_SESSION['xy'])?>">
            <div style="background:#FFFFFF;" class="icon icon-shape icon-sm shadow border-radius-md  text-center me-2 d-flex align-items-center justify-content-center">
              <i style="color:#000000;" class="fa fa-university fa-2"></i>
            </div>
            <span class="nav-link-text ms-1">Transferir Saldo</span>
          </a>
        </li>
	<?php if($_SESSION['tc']==2){?>
	<br>
		<li class="nav-item">
          <a style ="background:#000000; color:#FFFFFF;"  class="nav-link  active " href="../pages/new_group.php?<?php echo cadena2()."&fe=".$encriptar($_SESSION['xy'])?>">
            <div style="background:#FFFFFF;" class="icon icon-shape icon-sm shadow border-radius-md  text-center me-2 d-flex align-items-center justify-content-center">
              <i style="color:#000000;" class="fa fa-users fa-2"></i>
            </div>
            <span class="nav-link-text ms-1">Afiliar Empleados</span>
          </a>
        </li>
	<?php 
	}
	?>
<br>

        <li  class="nav-item">
          <a style ="background:#000000; color:#FFFFFF;" class="nav-link  active" href="../pages/profile.php?<?php echo cadena2();?>">
            <div style="background:#FFFFFF;" class="icon icon-shape icon-sm shadow border-radius-md  text-center me-2 d-flex align-items-center justify-content-center">
              <i style="color:#000000;"  class="fa fa-address-card fa-2"></i> 
            </div>
            <span class="nav-link-text ms-1">Mi Perfil</span>
          </a>
        </li>
 
      </ul>
    </div>

  </aside>
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none position-absolute px-4 w-100 z-index-2">
      <div class="container-fluid py-1">

        <div class="collapse navbar-collapse me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">
         
          <ul style="margin-top:20px;" class="navbar-nav justify-content-end">
           
            <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                  <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                  </div>
                </a>
              </a>
            </li>

          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid">
	
      <div class="page-header min-height-200 border-radius-xl mt-4" style="background:#000000">
        
		<span></span>
		<a href="home.php?<?php echo cadena();?>"><img width="90%" style="margin-top:-50px;margin-left:20px;z-index:1000"  src="../assets/img/logos/logo.png"></a>
		
      </div>
	  
      <div  class="card card-body mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
          <div  class="col-auto">
            <div style="background:#000000;" class="avatar avatar-xl position-relative">
              
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
			<div id="load_name" style="display:none;">
			<img src="../assets/img/loading.gif">
			</div>
              <?php echo saludo();?><h5 id="name_label" class="mb-1">
                
              </h5>
              
            </div>
          </div>
          <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 active "  href="home.php?<?php echo cadena2();?>"  aria-selected="true">
                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                          <g transform="translate(1716.000000, 291.000000)">
                            <g transform="translate(603.000000, 0.000000)">
                              <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                              </path>
                              <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                              <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                            </g>
                          </g>
                        </g>
                      </g>
                    </svg>
                    <span class="ms-1">Home</span>
                  </a>
                </li>
				<li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 "  href="out.php?<?php echo cadena2();?>" >
                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 40 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <title>Cerra Sesión</title>
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF" fill-rule="nonzero">
                          <g transform="translate(1716.000000, 291.000000)">
                            <g transform="translate(154.000000, 300.000000)">
                              <path class="color-background" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                              <path class="color-background" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
                              </path>
                            </g>
                          </g>
                        </g>
                      </g>
                    </svg>
                    <span class="ms-1">Cerrar Sesion</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid py-4">
	      <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Mi Saldo</p>
                    <h5 class="font-weight-bolder mb-0">
                      $<?php echo $saldo;?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Mis Puntos</p>
                    <h5 class="font-weight-bolder mb-0">
                      0 
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Recargas Pendientes</p>
                    <h5 class="font-weight-bolder mb-0">
                      $ <?php echo $monto;?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-12 mt-4">
          <div class="card mb-4">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-1">Mis Transacciones</h6>
              <div id="load_promo" style="display:none;">
				<img src="../assets/img/loading.gif">
			  </div>
            </div>
			     <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr width="100%">
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comercio</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fecha</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Monto</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comprobante</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
						if(mysqli_num_rows($sqlc)!=0){
							while($r2 = mysqli_fetch_assoc($sqlc) ){
				  ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../assets/img/hotel.png" class="avatar avatar-sm me-3" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $r2['localidad'];?></h6>
                           
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $r2['fecha'];?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="badge badge-sm bg-gradient-success"><?php echo $r2['monto'];?></span>
                      </td>
                      <td class="align-middle text-center">
                        <?php echo $r2['orden'];?>
                      </td>
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
       <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                Black Pass VIP <i class="fa fa-heart"></i> by
                <a href="https://www.itmedia.com.ve/" class="font-weight-bold" >ITMEDIA.</a>
              </div>
            </div>
            
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/jquery/jquery-3.2.1.min.js"></script>
  <script src="../assets/js/core/jquery-ui/jquery-ui.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/funciones.js"></script>
  <script src="../assets/js/modal.js"></script>
  <script>
  $(document).ready(function () {
		var uuid = 123211; 
        // validar_s(uuid);
      });
	$("#name_label").html("<?php echo $_SESSION['nameu'];?>");
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>

<?php
}
?>