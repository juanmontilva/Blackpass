<?php
session_start();

//Validar que el usuario este logueado y exista un UID
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: ../../index.php?error=ER001");
	//exit;
}else{
include("../../dist/funciones/conexion.php");
$codigo		     = mysqli_real_escape_string($con,(strip_tags($_GET["codi"],ENT_QUOTES)));
?>
		 <div id="btn_tax" style="margin-left:77%;margin-bottom:10px;">
		 <button type="button" class="btn btn-lg btn-info btn-label-right" onclick="new_tax(<?php echo $codigo;?>);">Cargar New Tax<span><i class="fa fa-plus-square"></i></span></button>
		 </div>
						<table class="table table-striped table-bordered" id="datatable-1">
							<thead>
									<th id="year">Año</th>
									<th>Tax</th>
							</thead>
							<tbody>
							<?php 
							    $sql4 = mysqli_query($con,"SELECT * FROM apps_cl_tax where idc = $codigo ");
								while($row4 = mysqli_fetch_assoc($sql4)){?>
								<tr>
								   <td><?php echo $row4['year'];?></td>
								   <td><a target ="blank" href = "ajax/mod_clientes/<?php echo $row4['file'];?>"><i class="fa fa-download"></i></a></td>
								</tr>
							<?php 
								}
							?>
							</tbody>
						</table>
<?php
}
?>