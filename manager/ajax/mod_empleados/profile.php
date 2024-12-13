<?php
include("../../dist/funciones/conexion.php");
?>
<!DOCTYPE html>
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			
			$sql = mysqli_query($con, "SELECT * FROM apps_emple_s WHERE codigo='$nik'");
			if(mysqli_num_rows($sql) == 0){
				header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
			}
			
			if(isset($_GET['aksi']) == 'delete'){
				$delete = mysqli_query($con, "DELETE FROM apps_emple_s WHERE codigo='$nik'");
				if($delete){
					echo '<div class="alert alert-danger alert-dismissable">><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Data berhasil dihapus.</div>';
				}else{
					echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Data gagal dihapus.</div>';
				}
			}
			?>
			
			<table width ="50%" class="table table-striped table-bordered" id="datatable-3">
				<tr>
					<th>Código</th>
					<th><?php echo $row['codigo']; ?></th>
				</tr>
				<tr>
					<th>Cliente</th>
					<th><?php echo $row['nombres']; ?></th>
				</tr>
				<tr>
					<th>Origen</th>
					<th><?php echo $row['lugar_nacimiento'].', '.$row['fecha_nacimiento']; ?></th>
				</tr>
				<tr>
					<th>Dirección</th>
					<th><?php echo $row['direccion']; ?></th>
				</tr>
				<tr>
					<th>Teléfono</th>
					<th><?php echo $row['telefono']; ?></th>
				</tr>
				<tr>
					<th>Puesto</th>
					<th><?php echo $row['puesto']; ?></th>
				</tr>
				<tr>
					<th>Estado</th>
					<th>
						<?php 
							if ($row['estado']==1) {
								echo "Empresa";
							} else if ($row['estado']==2){
								echo "Particular";
							} else if ($row['estado']==3){
								echo "VIP";
							}
						?>
					</th>
				</tr>
				
			</table>
			
		
			<!--<a href="edit.php?nik=<?php echo $row['nik']; ?>" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Editar datos</a>
			<a href="profile.php?aksi=delete&nik=<?php echo $row['nik']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de borrar los datos <?php echo $row['nombres']; ?>')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar</a>-->

<script>
 $('#datatable-3').DataTable()
</script>