$(document).ready(function(){
	var text_sms = "";
	var cliente_id = "";
	(function(usua, $, undefined ) {

		usua.new_usuario_s = function(e){	
		$("#form_super_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		usua.update_user = function(a,b,e){	
		$("#form_super_e_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			$("#carga_data_cl").load("ajax/mod_user/edit.php?nik="+a);
			$("#name_").html(b);
		}
		usua.new_usuario_u = function(e){
		$("#form_user_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		usua.foto = function(usuari_id, e){	
		$('#usuarioid').attr({ value: usuari_id }); 
		$("#form_foto_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}		
		usua.delete_user = function(cl,namec,st,e){	
		  var estad="";
			if(st===1){
				estad = "Activar";
			}else if(st===0){
				estad = "Desactivar";
			}
			swal({
						title: "\xbf Deseas "+estad+" a "+namec+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, "+estad+" !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_user/save_user.php',				
						    data : { 
						    	cli:cl ,
								stat:st,
								accion: 'delete',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: estad+' a '+cl+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha '+estad+' correctamente el cliente: '+namec,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							usua.refresh_list_usuario();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Registro no '+estad,
		                        text: 'No se puedo '+estad+' a '+namec,
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
		
		
			usua.save_foto = function(e){	
			var t_usuario = $("#usuarioid").val();
			var t_foto = $("#logofile").val();
				$.ajax({
			    url : 'ajax/mod_user/save_user.php',
			    data : { 
					foto: t_foto,
					id_user: t_usuario,
			    	accion: 'foto'
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
					if(json.foto=='ok'){
							messageEmail = new PNotify({
		                        title: 'Logo Asignado',
		                        text: 'Fue asignado la foto correctamente a : '+json.mmarca,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_foto_modal").modal('hide');
							usua.refresh_list_usuario();
					}else {
						messageEmail = new PNotify({
		                        title: 'Error al Asignar',
		                        text: 'Ocurrio un error asignando la foto al usuario',
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
		
			usua.save_user_sp= function(id,e){
			var t_nombre = $("#txtnombre_u").val();
			var t_apellido = $("#txtpellido").val();
			var t_phone = $("#txtphone_u").val();
			var t_mail = $("#txtemail_u").val();	
			var temp_pais = document.getElementById('s2_pais_u').value;
			var t_accion = $("#accion").val();
			var userid= $("#iduser").val();		
			var tuser = 0;
				 tuser= document.getElementById('t_user').value;
			var meses ="";

			if(temp_pais==""){
				$('#mostrar_error_us').parent().addClass('has-error has-feedback');
				 return;
			}else{
				$('#mostrar_error_us').parent().removeClass('has-success has-error'); 
			}
		
			
			if(t_nombre==""){
				$('#txtnombre_u').parent().addClass('has-error has-feedback');
				
				return;
			}else{
				$('#txtnombre_u').parent().removeClass('has-success has-error'); 
			}
			if(t_apellido==""){
				$('#txtpellido').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtpellido').parent().removeClass('has-success has-error'); 
			}
			if(t_mail==""){
				$('#txtemail_u').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtemail_u').parent().removeClass('has-success has-error'); 
			}
			if(t_phone==""){
				$('#txtphone_u').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtphone_u').parent().removeClass('has-success has-error'); 
			}


			$.ajax({
			    url : 'ajax/mod_user/save_user.php',
			    data : { 
					nombre_t: t_nombre,
					apelli: t_apellido,
					id_ubic:temp_pais,
					correo:t_mail,
					tef:t_phone,
					tuser:tuser,
			    	accion: t_accion
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#upd').attr("disabled", true);
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 5
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },		
			    success : function(json) {
					$('#upd').attr("disabled", false);
					if(json.tuser=='ok'){
							messageEmail = new PNotify({
		                        title: 'Super Usuario',
		                        text: 'Fue registrado el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'success',
								delay : 3
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_super_modal").modal('hide');
							usua.refresh_list_usuario();
					}else if(json.tuser=='existe'){
							messageEmail = new PNotify({
		                        title: 'Usuario ya Existe',
		                        text: 'No se registro el usuario '+t_nombre+' '+t_apellido,
		                        type: 'warning',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.tuser=='update'){
							messageEmail = new PNotify({
		                        title: 'Usuario Actualizado',
		                        text: 'Fue actualizada correctamente el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'success',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							
							usua.refresh_list_usuario();
					}else if(json.tuser=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'error',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'error',
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
							
		function 	cerrar_modal(){
			$("#form_super_e_modal").modal('hide');//ocultamos el modal
			  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  $('.modal-backdrop').remove();//eliminamos el backdrop del modal
			
		}
		
		
		    usua.upda_user_sp= function(e){
			var t_nombre = $("#txtnombre_u2").val();
			var t_apellido = $("#txtpellido2").val();
			var t_phone = $("#txtphone_u2").val();
			var t_mail = $("#txtemail_u2").val();	
			var temp_pais = document.getElementById('s2_pais_u2').value;
			var t_accion = $("#accion2").val();
			var userid= $("#iduser").val();
			//alert (t_nombre);
			var tuser = 0;
			tuser= document.getElementById('t_user2').value;
			var meses ="";
			if(temp_pais==""){
				$('#mostrar_error_us').parent().addClass('has-error has-feedback');
				 return;
			}else{
				$('#mostrar_error_us').parent().removeClass('has-success has-error'); 
			}
		
			
			if(t_nombre==""){
				$('#txtnombre_u').parent().addClass('has-error has-feedback');
				
				return;
			}else{
				$('#txtnombre_u').parent().removeClass('has-success has-error'); 
			}
			if(t_apellido==""){
				$('#txtpellido').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtpellido').parent().removeClass('has-success has-error'); 
			}
			if(t_mail==""){
				$('#txtemail_u').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtemail_u').parent().removeClass('has-success has-error'); 
			}
			if(t_phone==""){
				$('#txtphone_u').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtphone_u').parent().removeClass('has-success has-error'); 
			}


			$.ajax({
			    url : 'ajax/mod_user/save_user.php',
			    data : { 
					nombre_t: t_nombre,
					apelli: t_apellido,
					id_ubic:temp_pais,
					correo:t_mail,
					tef:t_phone,
					tuser:tuser,
					userid:userid,
			    	accion: t_accion
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#upd').attr("disabled", true);
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 5
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },		
			    success : function(json) {
					$('#upd').attr("disabled", false);
					if(json.tuser=='ok'){
							messageEmail = new PNotify({
		                        title: 'Super Usuario',
		                        text: 'Fue registrado el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'success',
								delay : 3
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							cerrar_modal();
							
							usua.refresh_list_usuario();
					}else if(json.tuser=='existe'){
							messageEmail = new PNotify({
		                        title: 'Usuario ya Existe',
		                        text: 'No se registro el usuario '+t_nombre+' '+t_apellido,
		                        type: 'warning',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.tuser=='update'){
							messageEmail = new PNotify({
		                        title: 'Usuario Actualizado',
		                        text: 'Fue actualizada correctamente el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'success',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_super_modal").modal('hide');
							usua.refresh_list_usuario();
					}else if(json.tuser=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'error',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando el usuario: '+t_nombre+' '+t_apellido,
		                        type: 'error',
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
		
		
		usua.refresh_list_usuario = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_user/list_view_user.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		
		
		}( window.usua = window.usua|| {}, jQuery ));
});
