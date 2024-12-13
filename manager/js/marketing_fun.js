var cliente_cod = 0;
function rand_code(chars, lon){
code = "";
for (x=0; x < lon; x++)
{
rand = Math.floor(Math.random()*chars.length);
code += chars.substr(rand, 1);
}
return code;
}

caracteres = "0123456789abcdefghijklmnopqrstuvwzfABCDEFGHIJKLMNOPQRSTUVWXYZ=$-";
longitud = 46;
		function ver_campa(cl){
				$("#Modal_v_cl").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#carga_data_cl").load("ajax/mod_marketing/profile.php?nik="+cl);
		}
		function cerrar_modal(){
			  $("#form_send_ws").modal('hide');//ocultamos el modal
			  //$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  //$('.modal-backdrop').remove();//eliminamos el backdrop del modal
		}
		function probar_campa(cl){
				$("#form_send_ws").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#input_c").val(cl);
		}
		
		/*function update_cliente(cl){
				$("#Modal_v_cl").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#carga_data_cl").load("ajax/mod_marketing/edit.php?nik="+cl);
		}*/
		function info_campa(cl,nom,e){
			cliente_cod = cl;
			$("#camp_id").val(cl);
				$("#Modal_v_cl2").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#name_cl").html(nom);
		  // res_cliente(cl);
		  		$.ajax({
			    url : 'ajax/mod_marketing/clientes_script.php',
			    data : { 
					idc: cl,
			    	accion: 'send_c'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },		
			    success : function(json) {
					if(json.response=='ok'){
							messageEmail = new PNotify({
		                        title: 'Cargando Información',
		                        text: 'Fueron cargado los datos de: '+nom,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							 var telefonos = [];	
							  $.each(json.num, function(d){
                               telefonos.push(json.num[d].num);
							     $('#global').append("<div id='mensajes'>"+json.num[d].num+"</div>");
                            });
							$('#cantidad').html("<div class='alert alert-success'><strong>Cantidad de Mensajes: "+json.total+" </strong></div>");
							if(json.formato=="MP4"){
								$('#url').html("<video width='270' height='380' src='ajax/mod_marketing/files/"+json.url+"' controls></video>");
							}else{
								$('#url').html("<img width='240' height='380' src='ajax/mod_marketing/files/"+json.url+"'>");
							}
							
							$('#label').html("<div class='alert alert-info'><strong>Mensaje</strong>:"+json.label+"</div>");
					
					}else if(json.response=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la localidad: '+t_puesto,
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							//marca.refresh_list_local();
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando al cliente',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		   
		}
		/*function res_cliente(cliente_cod){
			var cadena = rand_code(caracteres, 120);
			$("#carga").load("ajax/mod_marketing/view_res.php?id="+cadena+"&nik="+cliente_cod);
			
		}*/
		function add_campa(e){
			var t_fd = $("#datetime_example").val();
			//alert($.trim($("#example1").val()));
			var t_orig = $.trim($("#example1").val());
			var t_nombres = $("#nombres").val();
			var t_codigo = $("#codigo").val();
			var estado = $("#bd").val();
			var sex = $("#sex").val();
			var serv = $("#serv").val();
			var time = $("#time").val();
			if(estado==""){
			 $('#mostrar_error_p').parent().addClass('has-error has-feedback');
			// return;
			}

			if(t_codigo==""){
			 $('#codigo').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#codigo').parent().addClass('has-success has-feedback');
			}
			if(estado==""){
			 $('#bd').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#bd').parent().addClass('has-success has-feedback');
			}
			if(t_fd==""){
			 $('#datetime_example').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#datetime_example').parent().addClass('has-success has-feedback');
			}
			if(t_nombres==""){
			 $('#nombres').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#nombres').parent().addClass('has-success has-feedback');
			}	
			var t_accion = $("#accion").val();
			var t_file = $("#logofile").val();
			if(t_file=="" || t_file==null)t_file = "";
			//var idmarca = $("#idmarca").val();
			$.ajax({
			    url : 'ajax/mod_marketing/clientes_script.php',
			    data : { 
					nombre_l: t_nombres,
					t_fd:t_fd,
					t_orig:t_orig,
					tclie:estado,
					t_codigo:t_codigo,
					t_file:t_file,
					t_time:time,
					t_sex:sex,
					t_serv:serv,
			    	accion: t_accion
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#add').attr("disabled", true);
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },		
			    success : function(json) {
					$('#add').attr("disabled", false);
					if(json.response=='ok'){
							messageEmail = new PNotify({
		                        title: 'Cliente Creado',
		                        text: 'Fue registrado el cliente: '+t_nombres,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							//$("#form_loca_modal").modal('hide');
							refresh_list_camp();
							 $("#registro_c")[0].reset();
							
					}else if(json.response=='ex'){
							messageEmail = new PNotify({
		                        title: 'El cliente Existente',
		                        text: 'No se registro el cliente',
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.response=='update'){
							messageEmail = new PNotify({
		                        title: 'Localidad Actualizada',
		                        text: 'Fue actualizada correctamente la localidad: '+t_puesto,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							//marca.refresh_list_local();
					}else if(json.response=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la localidad: '+t_puesto,
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							//marca.refresh_list_local();
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando al cliente',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
		function delete_campa(cl,namec,e){
			swal({
						title: "\xbf Deseas eliminar a "+namec+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Eliminar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_marketing/clientes_script.php',				
						    data : { 
						    	cli:cl ,
								accion: 'delete',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Eliminando a '+cl+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha eliminado correctamente la Campaña: '+namec,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							refresh_list_camp();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Registro no eliminado',
		                        text: 'No se puedo eliminar '+namec,
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}	
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});	
		}
		

		function procesar_campa_send(cl,e){
			var idc = $("#camp_id").val();
				$.ajax({
			    url : 'ajax/mod_ws/ws_script.php',
			    data : { 
				    id: rand_code(caracteres, longitud),
					idc:idc,
			    	accion: 'send'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#btn_send').attr("disabled", true);
					$("#loader").show();
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
					messagebefore = new PNotify({
                        title: 'Enviando Campaña',
                        text: 'Esto puede demorar, cierre esta ventana, te avisare al enviar por completo',
                        type: 'success',
						delay : 5000
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					setTimeout(function () {
						 close_modal_camp();
						}, 3000, $(this));
				  
			    },		
			    success : function(json) {
					$('#btn_send').attr("disabled", false);
					$("#loader").hide();
					//close_modal_camp();
					if(json.response=='ok'){
							messageEmail = new PNotify({
		                        title: 'Cargando data',
		                        text: 'Campaña enviada',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					
							  $('#respuesta').append(json.respuesta);
							  
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al Enviar',
		                        text: 'Ocurrio un error enviando el mensaje',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$('#respuesta').append("No se pudo enviar su mensaje");
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		}
		function probar_campa_send(e){
			var busqueda = $("#telefono").val();
			var idc = $("#input_c").val();
				$.ajax({
			    url : 'ajax/mod_ws/ws_script.php',
			    data : { 
					phone:busqueda,
					idc:idc,
			    	accion: 'test'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#btn_send').attr("disabled", true);
					$("#loader").show();
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },		
			    success : function(json) {
					$('#btn_send').attr("disabled", false);
					$("#loader").hide();
					if(json.response=='ok'){
							messageEmail = new PNotify({
		                        title: 'Cargando data',
		                        text: 'Estoy cargando el resultado',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
	
							  $('#respuesta').append(json.respuesta);
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al Enviar',
		                        text: 'Ocurrio un error enviando el mensaje',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$('#respuesta').append("No se pudo enviar su mensaje");
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
		
		
		function close_modal_camp(){
			
			$("#Modal_v_cl2").modal('hide');//ocultamos el modal
			$("#Modal_v_cl2").modal('hide');//ocultamos el modal
			
			  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  $('.modal-backdrop').remove();//eliminamos el backdrop del modal
            $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_marketing/index.php?FLHO9M15Z7CSUJYIX3EKV2BRQ80AGND46PTW", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
					
		}
		function refresh_list_camp(){
			$("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_marketing/index.php", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
		}
function validar_n(){
	$('#telefono').numeric();
	if($("#telefono").val().length<10){
		$('#add').attr("disabled", true);
		$('#telefono').parent().addClass('has-error has-feedback');
		messageEmail = new PNotify({
		          title: 'Teléfono INVALIDO',
		          text: 'Ingrese un numero Valido',
		          type: 'error',
				delay : 4000
		});
	}else if($("#telefono").val().length>=10){
		$('#add').attr("disabled", false);
	    $('#telefono').parent().removeClass('has-error has-feedback');
	}
	 
}	
		
function rand_code(chars, lon){
code = "";
for (x=0; x < lon; x++)
{
rand = Math.floor(Math.random()*chars.length);
code += chars.substr(rand, 1);
}
return code;
}
var caracteres = "0123456789ABCDEFGHIJKLMNOPQRSTYVWXYZ";
var longitud = 20;