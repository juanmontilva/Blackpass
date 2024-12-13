<?php
include("../../dist/funciones/conexion.php");
?>
<!DOCTYPE html>
			<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$nik = mysqli_real_escape_string($con,(strip_tags($_GET["nik"],ENT_QUOTES)));
			
			$sql = mysqli_query($con, "SELECT w.*, m.marca as bd FROM apps_bd_market w, apps_marcas m 
										WHERE w.id_bd = m.id_marca and w.id='$nik'");
										
			if(mysqli_num_rows($sql) == 0){
				//header("Location: index.php");
			}else{
				$row = mysqli_fetch_assoc($sql);
				$tipo = explode(".",$row['url']);
			}
			$src = $row['url'];
			if(isset($_GET['aksi']) == 'delete'){
				$delete = mysqli_query($con, "DELETE FROM apps_bd_market WHERE id='$nik'");
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
					<th>Titulo Campaña</th>
					<th><?php echo $row['titulo']; ?></th>
				</tr>
				<tr>
					<th>Base de Datos</th>
					<th><?php echo $row['bd']; ?></th>
				</tr>
				<tr>
					<th>Fecha Envio</th>
					<th><?php echo $row['fecha']; ?></th>
				</tr>
				<tr>
					<th>Probar Campaña</th>
					<th><?php echo "<div class='col-xs-3'><button type='button' onclick='probar_campa(".$row['id'].")' class='btn btn-lg btn-success' ><i class='fa fa-play'></i></button></div>" ?></th>
				</tr>
			</table>
			<?php
			if($tipo[1] != 'MP4'){
			echo "<img width='240' height='380' src='ajax/mod_marketing/files/$src'>";
			echo "<div class='col-xs-3'>".$row['label']."</div>";
			}else{
				echo "<video width='270' height='380' src='ajax/mod_marketing/files/$src' controls></video>";
				echo "<div class='col-xs-3'>".$row['label']."</div>";
			}

			?>
		
<div class="modal" id="form_send_ws" tabindex="-1" role="dialog" aria-labelledby="form_send_ws" aria-hidden="true">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="form_send_ws"><i class="fa fa-bookmark"></i>Probar Campaña</h4>
      </div>
      <div class="modal-body">
		<div class="box">
	
			<div class="box-content">
						<p></p>
						<div class="box-content">
							<div class="form-group">
							<label class="col-sm-12 control-label">Numero Telefono </label>
							
							<div class="col-sm-5">
								<input type="text" class="form-control" name="telefono" id="telefono" data-toggle="tooltip" data-placement="bottom" placeholder="584141234567"> 
								<input type="hidden" id="input_c" value="">
							</div>
							<div class="col-sm-3">
							<button type="button" class="btn btn-lg btn-success btn-label-right" id="btn_send" onclick="probar_campa_send();"><i class="fa fa-send"></i></button>
							</div>
							<br><br>
						</div>
						  
						</div>
						<div id="loader" style="display:none;margin-left:40%;">
							<img src="dist/img/loading.gif">
						</div>
						<div id="respuesta"></div>
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      </div>
  </div>
</div>
</div>
<script>
$('#telefono').numeric();
</script>