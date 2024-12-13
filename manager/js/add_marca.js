$(document).ready(function(){
	var text_sms = "";
	var cliente_id = "";
	(function( marca, $, undefined ) {
	
		marca.new_marc = function( e){	
		$('#form_add_marcas')[0].reset();
		document.getElementById("form_add_marcas").reset();
		$("#form_sms_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		marca.new_servi = function(idx,e){	
		$('#form_add_servi')[0].reset();
		$("#idmarca").val(idx);
		document.getElementById("form_add_servi").reset();
		$("#form_servi_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		
		marca.id_marca = function( e){	
			$('#txtmarca').keyup(function() {
					var chars = $(this).val().length;
					var cadena = $("#txtmarca").val().substr(0,2);
					$("#txtidmarca").val(cadena);
				});		
		}
		
		marca.logo = function(marca_id, e){	
		$('#marcaid').attr({ value: marca_id }); 
		$("#form_logo_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}
		marca.detect_chck = function(marca_id,id_pai,estado, e){	
			//alert(marca_id+" "+id_pai);
			var estatus = estado;
			var mensaje = mensaje2 = "";
			if(estatus==0){
				mensaje = "Desactivar";
				mensaje2 = "Desactivando";
			}else{
				mensaje = "Activar";
				mensaje2 = "Activando";
			}
				$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_id_d:marca_id ,
					pais_id_d:id_pai ,
			    	accion: 'delete_m_p'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					},	
				success : function(json) {
						if (typeof messagebefore !== 'undefined') {
							messagebefore.remove();
						}
						if(json.marcae=='ok'){
							swal({
								title: "\xbf Deseas "+mensaje+" "+json.nmarca+" del Comercio:",   
								text: json.pais,   
								type: "warning",  
								showCancelButton: true,   
								cancelButtonText: "Cancelar",
								confirmButtonColor: "#008C23",   
								confirmButtonText: "Si, "+mensaje+" !",   
								closeOnConfirm: true 
							}, 
						function(){ 
							$.ajax({
								url : 'ajax/mod_marcas/save_marca.php',			
								data : { 
									marca_id_d:marca_id ,
									pais_id_d:id_pai ,
									accion: 'delete_m_p_ok',
									statu :estatus,
									confirm: 'ok'
								},
								type : 'GET',
								dataType : 'json',
								beforeSend: function(){
								messageEmail = new PNotify({
								title: 'Realizando solicitud ',
								text: 'Por favor espere.',
								type: 'info',
								delay : 4000
									});
								},
						success : function(json) {
							if (json.marcad == 'ok') {
								messageEmail = new PNotify({
									title: 'Local '+mensaje2,
									text: 'Se ha '+mensaje2+' correctamente '+json.nmarca+' de '+json.pais,
									type: 'success'
								});
								$(e).css({
									'color':'#337ab7', 
									'pointer-events': 'auto',
									'cursor': 'pointer'
								});
								marca.refresh_list_servi_d();
							}else if(json.marcad == 'no'){
								messageEmail = new PNotify({
									title: 'Localidad no desnviculada',
									text: 'No se puedo '+mensaje2+' '+json.nmarca+' de '+json.pais+ '. Tiene empleados activos',
									type: 'error'
								});
								$(e).css({
									'color':'#337ab7', 
									'pointer-events': 'auto',
									'cursor': 'pointer'
								});
							}else if(json.pais=='con_loca'){
								messageEmail = new PNotify({
									title: 'Error Actualizando',
									text: 'No se puede actualizar esta localidad ya que tiene :'+json.cantidad+' Localidades Activas',
									type: 'error',
									delay : 4000
								});
								$(e).css({
									'color':'#337ab7', 
									'pointer-events': 'auto',
									'cursor': 'pointer'
								});
								marca.refresh_list_servi_d();
								}	
							},
								error: function(error){
									console.log(error);
								}
							});
							
						});	
						marca.refresh_list_servi_d();						
					}else{
						messageEmail = new PNotify({
									title: 'Error actualizando',
									text: 'No se puedo eliminar',
									type: 'error'
								});
								$(e).css({
									'color':'#337ab7', 
									'pointer-events': 'auto',
									'cursor': 'pointer'
								});
						
						
					}
				}
			});
		}

		
		marca.save_marc = function(e){
			var t_marca = $("#txtmarca").val();
			var t_logo = $("#tlogo").val();
			var t_localidad = $("#s2_local").val();
			//alert (t_localidad);
			var t_accion = $("#accion").val();
			var idmarca = $("#idmarca").val();
			
			//alert(thab);
			if($("#txtmarca").val()==""){
			  $("#txtmarca").focus();
			  $('#txtmarca').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#txtmarca').parent().removeClass('has-success has-error');
			}
			if($('#s2_local').val() =="" || $('#s2_local').val() == null){
			 messagebefore = new PNotify({
                        title: 'ERROR',
                        text: 'Por favor, selecciona Tienda',
                        type: 'error',
						delay : 3
						});
			  return;
			}
			if($("#txtidmarca").val()==""){
			  $("#txtidmarca").focus();
			  $('#txtidmarca').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#txtidmarca').parent().removeClass('has-success has-error');
			}
			if($("#cantidad").val()==""){
			  $("#cantidad").focus();
			  $('#cantidad').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#cantidad').parent().removeClass('has-success has-error');
			}
			var tipo_p = $('#tipo_p').val();
			var fileName ="";	
				$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_name:t_marca ,
					site: t_localidad,
					numero:$("#cantidad").val(),
					identi: $("#txtidmarca").val(),
					idmarca:$("#idmarca").val(),
					tipo_p:tipo_p,
			    	accion: t_accion
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#btn_sx").prop('disabled', true);
					$("#loading_tc").show();
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
					$("#loading_tc").hide();
					
					if(json.marca=='ok'){
						$("#btn_sx").prop('disabled', false);
							messageEmail = new PNotify({
		                        title: 'Servicio Creado',
		                        text: 'Fue creada correctamente el servicio: '+t_marca,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					$("#form_sms_modal").modal('hide');
					marca.refresh_list_clients();
					}else if(json.identi=='yes'){
						$("#btn_sx").prop('disabled', false);
						messageEmail = new PNotify({
		                        title: 'Identificador Existente',
		                        text: 'Verifique el identificador: "'+$("#txtidmarca").val()+'"',
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							 $("#txtidmarca").focus();
							$('#txtidmarca').parent().addClass('has-error has-feedback');
							var cadena2 = $("#txtmarca").val().substr(1,1);
							var cadena3 = $("#txtmarca").val().substr(2,1);
							$("#sugerencias").html(cadena2+" - "+cadena3);
							$("#sugerencias").show();
							
					}
					else if(json.marca=='existe'){
						messageEmail = new PNotify({
		                        title: 'Servicio Existente',
		                        text: 'No se registro el servicio',
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.marca=='update'){
						if(json.msg==1){
							messageEmail = new PNotify({
		                        title: 'Servicio Actualizado',
		                        text: 'Fue actualizada correctamente el servicio: '+t_marca,
		                        type: 'success',
								delay : 4000
		                    });
						}else if(json.msg==10){
							messageEmail = new PNotify({
		                        title: 'Precaución',
		                        text:  'Debe eliminar de forma individual los sub servicios de '+t_marca,
		                        type: 'warning',
								delay : 50000
		                    });
						}else{
							messageEmail = new PNotify({
		                        title: 'Error',
		                        text:  'No se pudo actualizar el servicio '+t_marca,
		                        type: 'error',
								delay : 12000
		                    });
						}
							
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					$("#form_sms_modal").modal('hide');
					marca.refresh_list_servi_d();
					}else if(json.marca=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la marca: '+t_marca,
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					$("#form_sms_modal").modal('hide');
					marca.refresh_list_servi_d();
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando la marca',
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
		
				
		marca.save_serv = function(e){
			//alert("x");
			var t_marca = $("#txtmarca").val();
			var t_tarifa = $("#tarifa").val();
			var t_accion = $("#accion").val();
			var t_tiempo = $("#tiempo").val();
			//alert(t_accion);
			if($("#txtmarca").val()==""){	   
			  $("#txtmarca").focus();
			  $('#txtmarca').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#txtmarca').parent().removeClass('has-success has-error');
			}
	
				$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_name:t_marca ,
					idmarca:$("#idmarca").val(),
					tarif:t_tarifa,
					descrip: $("#descrip").val(),
					t_tiempo:t_tiempo,
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
					if(json.marca=='ok'){
							messageEmail = new PNotify({
		                        title: 'Servicio Creado',
		                        text: 'Fue creada correctamente el servicio: '+t_marca,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					$("#form_servi_modal").modal('hide');
					marca.carga_hab(json.msg);
					}
					else if(json.marca=='existe'){
						messageEmail = new PNotify({
		                        title: 'Servicio Existente',
		                        text: 'No se registro el ervicio',
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.response=='update'){
						if(json.msg!=0){
							messageEmail = new PNotify({
		                        title: 'Servicio Actualizado',
		                        text: 'Fue actualizado correctamente el servicio: '+t_marca,
		                        type: 'success',
								delay : 4000
		                    });
						}else{
							messageEmail = new PNotify({
		                        title: 'Error',
		                        text:  'No se pudo actualizar el servicio '+t_marca,
		                        type: 'error',
								delay : 12000
		                    });
						}
							
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					$("#form_servi_modal").modal('hide');
					marca.carga_hab(json.msg);
					}else if(json.response=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente el servicio: '+t_marca,
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					$("#form_servi_modal").modal('hide');
					marca.carga_hab(json.msg);
					}				

				},
				error: function(error){
			    	console.log(error);
			    }
			});
		}
			marca.edit_servi = function(marca_id, e){		
			$('#accion').attr({ value: '' });	
			var fileName ="";	
				$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_id_d:marca_id ,
			    	accion: 'edit_ser'
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
					if(json.marcae=='ok'){
						
						$("#form_servi_modal").modal({
							show: true,
							backdrop: 'static',
							keyboard: false,
							history: false,
							closer: false
							
						});
						console.log("="+json.cant);
						console.log("="+json.precio);
					$('#txtmarca').attr({ value: json.nmarca }); 		
					$('#tarifa').attr({ value: json.precio }); 
					$('#idmarca').attr({ value: marca_id }); 
					$('#txtidmarca').attr({ value: json.iden }); 
					$('#descrip').val($('#descrip').val()+ json.desc);
					$('#accion').attr({ value: 'update_sr' });	
					/*for(i=0;i<6;i++){
						$('#s2_pais').append('<option value="foo" selected="selected">Foo</option>');
					}
					*/
					}else if(json.marca==''){
						messageEmail = new PNotify({
		                        title: 'Marcar No modificable',
		                        text: 'No se puede modificar la marca',
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
			marca.edit_marc = function(marca_id, e){		
			$('#accion').attr({ value: '' });	
			var fileName ="";	
				$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_id_d:marca_id ,
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
					if(json.marcae=='ok'){
						
						$("#form_sms_modal").modal({
							show: true,
							backdrop: 'static',
							keyboard: false,
							history: false,
							closer: false
							
						});
						console.log("="+json.cant);
						console.log("="+json.precio);
					$('#txtmarca').attr({ value: json.nmarca }); 		
					$('#cantidad').attr({ value: json.cant }); 
					$('#idmarca').attr({ value: marca_id }); 
					$('#txtidmarca').attr({ value: json.iden }); 
					var local = [];	
					 $("#s2_local option").each(function(){
						var v = $(this).val();
						$.each(json.local, function(d){
                             local.push(json.local[d].loc);
							if ( v == json.local[d].loc ){
								$("#s2_local option[value="+v+"]").attr("selected",true);
							}
						});
					 });
					 $("#s2_local").select2();
					$('#accion').attr({ value: 'update' });	
					}else if(json.marca==''){
						messageEmail = new PNotify({
		                        title: 'Servicio No modificable',
		                        text: 'No se puede modificar el servicio',
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
		
		
		marca.refresh_list_clients = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_marcas/list_view_marcas.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		marca.refresh_list_servi_d = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_marcas/list_view_marcas.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }

			marca.delet_servi = function(marca_id, e){
			$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_id_d:marca_id ,
			    	accion: 'consultar_s'
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
					if(json.marcac=='ok'){
					swal({
						title: "\xbf Deseas Eliminar la Tarjeta culminada en: ",   
						text: json.tar,   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Eliminar!",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_marcas/save_marca.php',				
						    data : { 
						    	marca_id_d:marca_id ,
								accion: 'delete_s',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',

						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Eliminando '+json.tar,
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.marcad == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Tarjeta Eliminada',
		                        text: 'Se ha eliminado correctamente',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							marca.carga_hab(json.msg);
	    		    	}else if(json.marcad == 'no_borrar'){

        		    		messageEmail = new PNotify({
		                        title: 'Tarjeta no Eliminada',
		                        text: 'No puede elimianar tarjeta con Datos, puede desactivarla.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else{
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La Tarjeta no se pudo eliminar.',
		                        type: 'error'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
	    		    	}
						marca.refresh_list_servi();
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
		marca.delet_marc = function(marca_id, e){
			$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
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
					if(json.marcac=='ok'){
					swal({
						title: "\xbf Deseas Eliminar el Producto: ",   
						text: json.nmarca,   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Eliminar!",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_marcas/save_marca.php',				
						    data : { 
						    	marca_id_d:marca_id ,
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
	    		    	if (json.marcad == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Producto Eliminado',
		                        text: 'Se ha eliminado correctamente '+json.nmarca,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							marca.refresh_list_servi_d();
	    		    	}else if(json.marcad == 'no_borrar'){

        		    		messageEmail = new PNotify({
		                        title: 'Tarjeta no Eliminada',
		                        text: 'No puede eliminar un Producto con Datos, puede desactivarla.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else{
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'El Producto no se pudo eliminar.',
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
			marca.status_servi = function(marca_id, e){
			var marcas= name= "";
			$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
			    	marca_id_d:marca_id ,
			    	accion: 'consultar_s'
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
					if(json.marcac=='ok'){
						var status = "";
						name = json.tar;
						var act = 0;
						if(json.momento==1 || json.momento==2){
							status = "Bloquear";
							act = 0;
						}else if(json.momento==0){
							status = "Activar";
							act = 2;
						}
					swal({
						title: "\xbf Deseas "+status+" la tarjeta culminada en: ",   
						text: json.tar,   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, "+status+"!",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_marcas/save_marca.php',				
						    data : { 
						    	marca_id_d:marca_id ,
								accion: 'update_sr_d',
								actual:act,
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',

						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando '+name,
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
						
						marcas = json.nmarca;
	    		    },
	    		    success : function(json) {
	    		    	if (json.servicio == 'update') {
	        		    	messageEmail = new PNotify({
		                        title: 'Tarjeta Culminada en:',
		                        text: name+' '+status+' correctamente ',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							marca.carga_hab(json.msg);
	    		    	}else if(json.servicio == 'no_update'){

        		    		messageEmail = new PNotify({
		                        title: 'Estatus no actualizado',
		                        text: 'No puede '+status+' Tarjeta con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else{
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La Tarjeta no se pudo '+status+'.',
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
		marca.status_marc = function(marca_id, e){
			var marcas="";
			$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
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
					if(json.marcac=='ok'){
						var status = "";
						var act = 0;
						if(json.momento==1){
							status = "Desactivar el Producto";
							act = 0;
						}else if(json.momento==0){
							status = "Activar el Producto";
							act = 1;
						}
					swal({
						title: "\xbf Deseas "+status+" : "+json.nmarca+" ?",   
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
						    url : 'ajax/mod_marcas/save_marca.php',				
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
	    		    	if (json.marcad == 'update') {
	        		    	messageEmail = new PNotify({
		                        title: 'Estatus Cambiado',
		                        text: status+' correctamente '+marcas,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							marca.refresh_list_servi_d();
	    		    	}else if(json.marcad == 'no_update'){

        		    		messageEmail = new PNotify({
		                        title: 'Estaus no actualizado',
		                        text: 'No puede '+status+' marca con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else{
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La Marca no se pudo '+status+'.',
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
		
		marca.carga_hab = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_marcas/list_view_ventas.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		
			marca.save_logos = function(e){	
			var t_marca = $("#marcaid").val();
			var t_logo = $("#logofile").val();
				$.ajax({
			    url : 'ajax/mod_marcas/save_marca.php',
			    data : { 
					logo: t_logo,
					id_marc: t_marca,
			    	accion: 'logo'
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
					if(json.logo=='ok'){
							messageEmail = new PNotify({
		                        title: 'Logo Asignado',
		                        text: 'Fue asignado el logo correctamente al producto: '+json.mmarca,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_logo_modal").modal('hide');
							marca.refresh_list_clients();
					}else {
						messageEmail = new PNotify({
		                        title: 'Error al Asignar',
		                        text: 'Ocurrio un error asignando logo al producto',
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
		
		}( window.marca = window.marca|| {}, jQuery ));
});

function isImage(extension)
{
    switch(extension.toLowerCase()) 
    {
        case 'jpg': case 'gif': case 'png': case 'jpeg':
            return true;
        break;
        default:
            return false;
        break;
    }
}


