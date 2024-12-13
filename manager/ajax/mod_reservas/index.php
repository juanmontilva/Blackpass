<?php
session_start();
if ( ! ($_SESSION['autenticado'] == 'SI' && isset($_SESSION['uid']) &&  isset($_SESSION['usuario']) &&  isset($_SESSION['perfi']) ) )
{
    //En caso de que el usuario no este autenticado, crear un formulario y redireccionar a la
    //pantalla de login, enviando un codigo de error
	header ("Location: index.php?error=ER001");
	//exit;
}else{
require_once('bdd.php');
require_once('../../dist/funciones/funciones.php');
include("../../dist/funciones/conexion.php");
if($_SESSION['perfi'] ==2){
	$where = " and p.id = '".$_SESSION['uid']."'";
}else{
	$where = " and 1 = 1";
}
	$sql = "SELECT e.*, s.color, s.servicio, m.marca, c.nombres as cliente, p.nombres as prof
	FROM events e, apps_servicios_d s, apps_clientes c, apps_emple_s p, apps_marcas m 
	where  e.idh = m.id_marca 
	and m.id_marca = s.id_marca 
	and c.id = e.id_cliente 
	and e.id_pro = p.id ".$where." ";

$req = $bdd->prepare($sql);
$req->execute();

$events = $req->fetchAll();

$sql2 = "SELECT * from apps_localidades  where status = 1 and id_pais = 4";

$req2 = $bdd->prepare($sql2);
$req2->execute();

$events2 = $req2->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
 
    <style>
    body {
        padding-top: 0px;
        
    }
	#calendar {
		max-width: 100%;
	}
	.col-centered{
		float: none;
		margin: 10px auto;
	}
    </style>

</head>

<body>


    <!-- Page Content -->

        <!-- /.row -->
		 <div id="calendar" class="col-centered">
		<!-- Modal -->
		<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		 <div class="modal-dialog modal-lg">
			<div class="modal-content">
			<div class="modal-header">
			
			<form class="form-horizontal" method="POST" action="addEvent.php">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Evento</h4>
			  </div>
			  <div class="modal-body">
				
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label"><a  onclick="sear_cl();">Cliente  </a><a  onclick="sear_cl();"><i class="fa fa-user"></i></a></label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" placeholder="Titulo" readonly>
					</div>
				  </div>
				  	<div class="form-group">
					<label for="color" class="col-sm-2 control-label">Localidad</label>
					<div class="col-sm-6">
					  <select name="t_hab" class="populate placeholder" id="t_sev" onchange="buscar_s()">
						  <option value="">Seleccionar</option>
						  
						  <?php foreach($events2 as $event2): 
			
						 ?>
						 <option value="<?php echo $event2['id_loc']; ?>"><?php echo $event2['localidad']; ?></option>
						  <?php endforeach; ?>
						 </select>
					</div>
				   </div>
				   <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Servicio</label>
					<div class="col-sm-6">
					  <select name="N_hab" class="populate placeholder" id="N_hab" onchange="buscar_e()" onfocus="this.selectedIndex = -1;">
					  <option value="">Seleccionar</option>
					  
					  </select>
					</div>
				   </div>
				  <div class="form-group">
					<label for="color" class="col-sm-2 control-label">Profesional</label>
					<div class="col-sm-6">
					  <select name="color" class="populate placeholder" id="id_prof">
						<option value="">Seleccionar</option>
						  						  
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="start" class="col-sm-2 control-label">Fecha Inicial</label>
					<div class="col-sm-10">
					   <input type="text" name="start" class="form-control" id="start" >
					 <!-- <input type="date" onblur="buscar_horas();" name="start" class="form-control" id="start" >-->
					</div>
				  </div>
				  <div class="form-group" id="mhora" style="display:none;">
					<label for="color" class="col-sm-2 control-label">Hora</label>
					<div class="col-sm-6">
					  <select name="hr_d" class="populate placeholder" id="hr_d">
						<option value="">Seleccionar</option>
						  						  
						</select>
					</div>
				  </div>
				   <input type="hidden" name="end" class="form-control" id="end" >
				  <!--<div class="form-group">
					<label for="end" class="col-sm-2 control-label">Fecha Final</label>
					<div class="col-sm-10">
					  <input type="text" name="end" class="form-control" id="end" >
					</div>
				  </div>-->
				<input type="hidden" name="idc" class="form-control" id="idc" >
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
				<button type="button" onclick="add();" class="btn btn-success">Guardar</button>
			  </div>
			</form>
			</div>
		  </div>
		</div>
		</div>
		
<div class="modal" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="ModalEdit" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalEdit"><i class="fa fa-bookmark"></i>Ver Cita</h4>
      </div>
			<div class="modal-body">
				 <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Localizador</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="title" readonly>
					</div>
				  </div>
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Cliente</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="cliente" readonly>
					</div>
				  </div>
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Servicio</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="servi" readonly>
					</div>
				  </div>
				 
				  <div class="form-group">
					<label for="title" class="col-sm-2 control-label">Profesional</label>
					<div class="col-sm-10">
					  <input type="text" name="title" class="form-control" id="prof" readonly>
					</div>
				  </div>
					<div id="transfer" style="display:none">			  
				    <input type="hidden" name="localizador"  class="form-control" id="localizador">
					<div class="form-group">
						<label for="title" class="col-sm-2 control-label">Actual</label>
						<div class="col-sm-10">
						  <input type="text" name="profn" class="form-control" id="profn" readonly>
						</div>
					</div>
					<div class="form-group">
						<label for="title" class="col-sm-2 control-label">Transferir a:</label>
						 <div class="col-sm-10">
						 <select onchange="transferir_cita_a2();" name="color2" class="populate placeholder" id="id_prof2">
							<option value="">Seleccionar</option>
							<?php $sqlp = mysqli_query($con,"select * from apps_emple_s where estado = 1");
							while($rowp = mysqli_fetch_assoc($sqlp)){?>
							<option value="<?php echo $rowp['id'];?>"><?php echo $rowp['nombres'];?></option>
							<?php
							}
							?>
						</select>
						</div>
					</div>				  
				  <input type="hidden" name="idcita2" class="form-control" id="idcita2">
				</div>
			</div>
			</br></br></br></br></br></br></br></br></br>
				  <div class="modal-footer">
					<div id="botones_d" style="float:left;margin-left:40px;"></div>
					<div id="botones_d2" style="float:left;margin-left:10px;"></div>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
					<button style="display:none;" id="btn_transf" type="button" class="btn btn-success btn-lg btn-label-right" onclick="transferir_cita_a2();">Transferir Cita<span><i class="fa fa-save"></i></span></button>
				  </div>
		</div>
	</div>
</div>		
		<!-- Modal -->
		<div class="modal fade" id="Modallistacli" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		 <div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" onclick="cerrar_md1()"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" ><i class="fa fa-globe"></i> Listado de Clientes</h4>
				</div>
			 <div class="form-group">
					 <p></p>
					<label for="title" class="col-sm-2 control-label">Buscar</label>
					<div class="col-sm-10">
						<div style="float:left;" class="col-sm-5">
						  <input type="text" name="bcliente"  class="form-control" id="bcliente" placeholder="Ingrese un dato">
						 
						</div>
						<div style="float:left;" class="col-sm-3">
						  <button type="button" onclick="buscar_client()" id="btn_sc" class="btn btn-success"> <i class="fa fa-book"></i> </button>
						  <button type="button" onclick="add_clientx()" id="btn_sc" class="btn btn-success"> <i class="fa fa-user-plus"></i> </button>
						</div>
					</div>
			</div>
			</br></br></br>
			<div class="table-responsive">
				<div id="loader" style="display:none;margin-left:40%;">
					<img src="dist/img/loading.gif">
				</div>
			<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="datatable-3">
			<thead>
				<tr>
                    <th>No</th>
					<th>Código</th>
					<th>Nombre</th>
					<th>Tipo Cliente</th>
                    <th>Acciones</th>
				</tr>
				</thead>
				<tbody id="carga_busqueda">
		
				</tbody>
			</table>
			</div>
			</div>
		  </div>
		</div>

	<!--Modal Logo Marca-->
<div class="modal" id="Modal_transfer" tabindex="-1" role="dialog" aria-labelledby="Modal_transfer" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
	     <button type="button" class="close" id="Modal_transfer" onclick="closeDialog(this.id);" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="Modal_transfer"><i class="fa fa-bookmark"></i>Transferir Cita</h4>
      </div>
      <div class="modal-body">
		<div class="box">
			<div class="box-content">
				 <div class="form-group">
						<label for="title" class="col-sm-2 control-label">Actual</label>
						<div class="col-sm-10">
						  <input type="text" name="profn" class="form-control" id="profn" readonly>
						</div>
				</div>
				<div class="form-group">
					<label for="title" class="col-sm-2 control-label">Transferir a:</label>
					 <div class="col-sm-10">
					 <select name="color" class="populate placeholder" id="id_prof">
						<option value="">Seleccionar</option>
						<?php $sqlp = mysqli_query($con,"select * from apps_emple_s where estado = 1");
						while($rowp = mysqli_fetch_assoc($sqlp)){?>
						<option value="<?php echo $rowp['id'];?>"><?php echo $rowp['nombres'];?></option>
						<?php
						}
						?>
					</select>
					</div>
					</br></br></br></br>
				</div>				  
				  <input type="hidden" name="idcita" class="form-control" id="idcita">
			</div>
		</div>
		
      </div>
	  <div class="modal-footer">
      	<button type="button" class="btn btn-success btn-lg btn-label-right" onclick="transferir_cita_a();">Transferir Cita<span><i class="fa fa-save"></i></span></button>
      </div>
  </div>
</div>
</div>

	<script>
	$('button').tooltip();
			function add(){
				var t_ccliente = $("#idc").val();
				if(t_ccliente==""){
				 $('#idc').parent().addClass('has-error has-feedback');
				// return;	
				}
			$.ajax({
			 url: 'ajax/mod_reservas/reservas_script.php',
			 type: "GET",
			 dataType : 'json',
			 cache: false,
			 data: {
					title : $("#title").val(),
					start : $("#start").val(),
					hora : $("#hr_d").val(),
					locali : $("#t_sev").val(),
					idc : $("#idc").val(),
					pro : $("#id_prof").val(),
					accion: 'add_event',
					servi: document.getElementById('N_hab').value
					},
			
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Creando Reserva ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
						
				
	    		    },
			 success: function(json) {
					if(json.response == 'ok'){
						messageEmail = new PNotify({
		                        title: 'Reserva Registrada',
		                        text: ' Reserva Codigo #00001',
		                        type: 'success'
		                    });
						refresh_list_reserva();
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
			function refresh_list_reserva(){
			  $("#ModalAdd").modal('hide');//ocultamos el modal
			  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  $('.modal-backdrop').remove();//eliminamos el backdrop del modal
            $("#ajax-content").css({
                "opacity": 0.4
            });
				$("#ajax-content").load("ajax/mod_reservas/index.php", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
					
				}
function AllTables(){
	LoadSelect2Script(MakeSelect2);
}
function MakeSelect2(){
	$('select').select2();
	
}
	$(document).ready(function() {
       LoadDataTablesScripts(AllTables);
		var date = new Date();
       var yyyy = date.getFullYear().toString();
       var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
       var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();
		
		$('#calendar').fullCalendar({
			 defaultView: 'agendaFourDay',
			header: {
				 language: 'es',
				//left: 'prev,next today',
				//center: 'title',
				center: 'agendaFourDay,month' ,
			},
			businessHours: {
				start: '07:00', // hora final
				end: '20:00', // hora inicial
				dow: [ 1, 2, 3, 4, 5 , 6] // dias de semana, 0=Domingo
			  },
			 views: {
				agendaFourDay: {
				  type: 'agenda',
				  duration: { days: 7 },
				  buttonText: '7 Dias'
				},
				basicDay: {
				  type: 'agenda',
				  duration: { days: 1 },
				  buttonText: 'Hoy'
				}
			  },
			defaultDate: yyyy+"-"+mm+"-"+dd,
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				
				$('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
				$('#ModalAdd').modal('show');
			},
			eventRender: function(event, element) {
				element.bind('dblclick', function() {
					$('#ModalEdit #id').val(event.id);
					$('#ModalEdit #title').val(event.title);
					$('#ModalEdit #localizador').val(event.localizador);
					$('#ModalEdit #cliente').val(event.cliente);
					$('#ModalEdit #prof').val(event.profesional);
					$('#ModalEdit #servi').val(event.servicio);
					
					var botn_del = '<button onclick="delete_cita2('+event.id+','+"'"+event.cliente+"'"+','+"'"+event.localizador+"'"+')"  type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar Cita" ><i class="fa fa-trash fa-lg"></i></button>';
					var botn_del2 = '<button onclick="transfer_cita2('+event.id+','+"'"+event.profesional+"'"+','+"'"+event.localizador+"'"+')" type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Transferir Profesional"><i class="fa fa-random fa-lg"></i></button>'
					$('#ModalEdit #botones_d').html(botn_del);
					$('#ModalEdit #botones_d2').html(botn_del2);
					$('#ModalEdit').modal('show');
				});
			},
			eventDrop: function(event, delta, revertFunc) { // si changement de position

				edit(event);

			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

				edit(event);

			},
			events: [
			<?php foreach($events as $event): 
			
				$start = explode(" ", $event['start']);
				$end = explode(" ", $event['end']);
				if($start[1] == '00:00:00'){
					$start = $start[0];
				}else{
					$start = $event['start'];
				}
				if($end[1] == '00:00:00'){
					$end = $end[0];
				}else{
					$end = $event['end'];
				}
			?>
				{
					id: '<?php echo $event['id']; ?>',
					title: '<?php echo $event['localizador']; ?>',
					start: '<?php echo $start; ?>',
					end: '<?php echo $end; ?>',
					localizador: '<?php echo $event['localizador']; ?>',
					color: '<?php echo $event['color']; ?>',
					profesional: '<?php echo $event['prof']; ?>',
					cliente: '<?php echo $event['cliente']; ?>',
					servicio: "<?php echo $event['servicio']; ?>",
				},
			<?php endforeach; ?>
			]
		});
		

		
		function edit(event){
			start = event.start.format('YYYY-MM-DD HH:mm:ss');
			if(event.end){
				end = event.end.format('YYYY-MM-DD HH:mm:ss');
			}else{
				end = start;
			}
			
			id =  event.id;
			
			Event = [];
			Event[0] = id;
			Event[1] = start;
			Event[2] = end;
			
			$.ajax({
			 url: 'ajax/mod_reservas/editEventDate.php',
			 type: "POST",
			 data: {Event:Event},
			 beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando Cita',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 10000
	                    });
	    	},
			 success: function(rep) {
					
					
					if(rep == 'OK'){
						messageEmail = new PNotify({
		                        title: 'Reserva Actualizada',
		                        text: ' Reserva Codigo '+event.localizador,
		                        type: 'success'
		                    });
						refresh_list_reserva();
					}else{
						messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La reserva no se pudo actualizar',
		                        type: 'error'
		                    });
		                  
					}
					
				}
			});
		}
		


		}
		
	);

</script>
	</body>
	
</html>
<?php 
}
?>