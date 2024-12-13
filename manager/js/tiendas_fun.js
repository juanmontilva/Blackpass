$(document).ready(function(){
	var text_sms = "";
	var cliente_id = "";
	(function( tien, $, undefined ) {
	
		tien.new_tienda = function(id_marcap, e){	
		$("#form_tienda_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			$("#cargar").load("ajax/mod_tienda/add_tienda.php?marca="+id_marcap+"&pai=aaasssc1&accion_=add");
		}
		tien.logo = function(marca_id, e){	
		$('#marcaid').attr({ value: marca_id }); 
		$("#form_logo_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}
		
			tien.nombre_tienda = function(e){
			var combo = document.getElementById('s2_local');
			if(combo.selectedIndex>0){
				var nombre = $('#txtrazon').val()+" "+combo.options[combo.selectedIndex].value;
				$('#txtnombre').attr({ value: nombre }); 
				$("#txtnombre").removeAttr("readonly");
			}

		}
		tien.save_tienda = function(e){
			var t_tienda = $("#txtnombre").val();
			var t_razon = $("#txtrazon").val();
			var idlocal = document.getElementById('s2_local');
			var id_ubic = $("#ubicaid").val();
			var t_accion = $("#accion").val();
			var email = $("#txtemail").val();
			var mts = $("#txtmt").val();
			var direc = $("#txtdir").val();
			var tef = $("#txtphone").val();
			var nlocalidad= $("#local_").val();
			var tiendaid= $("#idtienda").val();
			var local_ = "";
			if(idlocal.selectedIndex>0){
				local_ = idlocal.options[idlocal.selectedIndex].value;		
			}
			if(local_==""){
				$('#mostrar_error').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#mostrar_error').parent().removeClass('has-success has-error'); 
			}
			if(t_tienda==""){
				$('#txtnombre').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtnombre').parent().removeClass('has-success has-error'); 
			}
			if(direc==""){
				$('#txtdir').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtdir').parent().removeClass('has-success has-error'); 
			}
			if(t_razon==""){
				$('#txtrazon').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtrazon').parent().removeClass('has-success has-error'); 
			}
			$.ajax({
			    url : 'ajax/mod_tienda/save_tienda.php',
			    data : { 
					nombre_t: t_tienda,
					id_ubic:id_ubic,
					tef:tef,
					rsocial:t_razon,
					email_:email,
					direc_:direc,
					nlocal_:local_,
					mts_:mts,
					tienda_id_:tiendaid,
			    	accion: t_accion
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
					if(json.tienda=='ok'){
							messageEmail = new PNotify({
		                        title: 'Tienda Creada',
		                        text: 'Fue registrada la tienda: '+t_tienda,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_tienda_modal").modal('hide');
							tien.refresh_list_tiendas();
					}else if(json.tienda=='existe'){
							messageEmail = new PNotify({
		                        title: 'Tienda ya Existe',
		                        text: 'No se registro la tienda '+t_tienda,
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.tienda=='update'){
							messageEmail = new PNotify({
		                        title: 'Tienda Actualizada',
		                        text: 'Fue actualizada correctamente la tienda: '+t_tienda,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_tienda_modal").modal('hide');
							tien.refresh_list_tiendas();
					}else if(json.local=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la tienda: '+t_tienda,
		                        type: 'error',
								delay : 4000
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
		                        text: 'Ocurrio un error registrando la tienda '+t_tienda,
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
			
		tien.refresh_list_tiendas = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_tienda/list_view_tiendas.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		
		tien.carga_tienda = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_tienda/list_view_tiendas_details.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		tien.edit_tienda = function(tienda_id, e){		
			$('#accion').attr({ value: '' });	
			var fileName ="";	
				$.ajax({
			    url : 'ajax/mod_tienda/save_tienda.php',
			    data : { 
			    	marca_id_d:tienda_id ,
			    	accion: 'edit'
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
					if(json.tiendar=='ok'){

						$("#cargar").load("ajax/mod_tienda/add_tienda.php?marca="+json.mp+"&pai=aaasssc1&accion_=update",
						function(e){
							$('#txtrazon').attr({ value: json.nrazon }); 		
							$('#txtnombre').attr({ value: json.tiendan }); 
							$('#txtphone').attr({ value: json.phone }); 
							$('#txtemail').attr({ value: json.mail}); 
							$('#txtdir').attr({ value: json.dir }); 
							//$('#s2_local').attr({ value: json.locali }); 
							$('#txtmt').attr({ value: json.mt }); 
							$('#idtienda').attr({ value: json.tiedaid }); 
							$('#idmarca').attr({ value: json.marca }); 
							$('#accion').attr({ value: 'update' });	

							 $('#s2_local > option[value="'+json.locali+'"]').attr('selected', 'selected');
							}
						);
						$("#form_tienda_modal").modal({
							show: true,
							backdrop: 'static',
							keyboard: false,
							history: false,
							closer: false
							
						});

					/*for(i=0;i<6;i++){
						$('#s2_pais').append('<option value="foo" selected="selected">Foo</option>');
					}
					*/
					}else if(json.tiendar==''){
						messageEmail = new PNotify({
		                        title: 'Tienda No modificable',
		                        text: 'No se puede modificar la tienda ',
		                        type: 'warning',
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
		
		tien.delet_tienda = function(tienda_id, e){
			
			$.ajax({
			    url : 'ajax/mod_tienda/save_tienda.php',
			    data : { 
			    	marca_id_d:tienda_id ,
			    	accion: 'consultar'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 4000
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },	success : function(json) {
			    	if (typeof messagebefore !== 'undefined') {
					    messagebefore.remove();
					}
					if(json.tiendac=='ok'){
					swal({
						title: "\xbf Deseas Eliminar a: "+json.nmarca+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Eliminar!",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_tienda/save_tienda.php',				
						    data : { 
						    	marca_id_d:tienda_id ,
								accion: 'delete',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',

						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Eliminando '+json.nmarca,
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.tiendad == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Tienda Borrada',
		                        text: 'Se ha borrado correctamente '+json.nmarca,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							tien.carga_tienda(json.loc);
	    		    	}else if(json.tiendad == 'no_borrar'){

        		    		messageEmail = new PNotify({
		                        title: 'Tienda no Borrada',
		                        text: 'No puede borrar tienda con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.tiendad == 'error'){
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La Tienda no se pudo borrar.',
		                        type: 'error'
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
					}else{
						messageEmail = new PNotify({
		                        title: 'Error',
		                        text: 'Ocurrio un problema, intente luego', // cuando no existe la marca
		                        type: 'error'
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
		tien.status_tienda = function(marca_id, e){
			var marcas="";
			$.ajax({
			    url : 'ajax/mod_tienda/save_tienda.php',
			    data : { 
			    	marca_id_d:marca_id ,
			    	accion: 'consultar'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 4000
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },	success : function(json) {
			    	if (typeof messagebefore !== 'undefined') {
					    messagebefore.remove();
					}
					if(json.tiendac=='ok'){
						var status = "";
						var act = 0;
						if(json.momento==1){
							status = "Suspender";
							act = 0;
						}else if(json.momento==0){
							status = "Activar";
							act = 1;
						}
					swal({
						title: "\xbf Deseas "+status+" a: "+json.nmarca+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, "+status+"!",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_tienda/save_tienda.php',				
						    data : { 
						    	marca_id_d:marca_id ,
								accion: 'update_s',
								actual:act,
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',

						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando '+json.nmarca,
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
						
						marcas = json.nmarca;
	    		    },
	    		    success : function(json) {
	    		    	if (json.tiendad == 'update') {
	        		    	messageEmail = new PNotify({
		                        title: 'Estatus Cambiado',
		                        text: marcas+' '+status+' correctamente ',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							tien.carga_tienda(json.loc);
	    		    	}else if(json.tiendad == 'no_update'){

        		    		messageEmail = new PNotify({
		                        title: 'Estaus no actualizado',
		                        text: 'No puede '+status+' tienda con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.tiendad == 'error'){
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La Tienda no se pudo '+status+'.',
		                        type: 'error'
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
					}else{
						messageEmail = new PNotify({
		                        title: 'Error',
		                        text: 'Ocurrio un problema, intente luego', // cuando no existe la marca
		                        type: 'error'
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
		}( window.tien = window.tien|| {}, jQuery ));
});
