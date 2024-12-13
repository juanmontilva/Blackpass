$(document).ready(function(){
	var text_sms = "";
	var cliente_id = "";
	(function( temp, $, undefined ) {
	
		temp.new_temporada = function(e){	
		$("#form_tempo_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			$("#cargar_tmp").load("ajax/mod_tempo/add_temp.php?marca=2&pai=aaasssc1&accion_=add");
		}
		temp.logo = function(marca_id, e){	
		$('#marcaid').attr({ value: marca_id }); 
		$("#form_logo_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}
		

		temp.save_tempo= function(e){
			var t_tempo = $("#txtnomb").val();
			var temp_marca = document.getElementById('s2_temp');
			var identificador = document.getElementById('s2_identi');
			//var id_ubic = $("#ubicaid").val();
			var t_accion = $("#accion").val();
			var tiendaid= $("#idtienda").val();
			var tempoid= $("#idtempo").val();
			var local_ = "";
			var identi = "";
			var meses ="";
			if ($('input[name="meses"]').is(':checked')) {
				document.getElementById('mostrar_error_check').style.display = 'none';
				meses = $('input[name="meses"]').serializeArray();
			}
			else{
				document.getElementById('mostrar_error_check').style.display = 'block';
				return;
			}
			if(temp_marca.selectedIndex>0){
				local_ = temp_marca.options[temp_marca.selectedIndex].value;				
			}
			if(identificador.selectedIndex>0){
				identi = identificador.options[identificador.selectedIndex].value;				
			}
			if(local_==""){
				$('#mostrar_error').parent().addClass('has-error has-feedback');
				 return;
			}else{
				$('#mostrar_error').parent().removeClass('has-success has-error'); 
			}
			if(identi==""){
				$('#mostrar_error2').parent().addClass('has-error has-feedback');
				 return;
			}else{
				$('#mostrar_error2').parent().removeClass('has-success has-error'); 
			}
			if(t_tempo==""){
				$('#txtnomb').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#txtnomb').parent().removeClass('has-success has-error'); 
			}


			$.ajax({
			    url : 'ajax/mod_tempo/save_temp.php',
			    data : { 
					nombre_t: t_tempo,
					id_ubic:local_,
					mes_:meses,
					tempo:tempoid,
					identifi: identi,
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
					if(json.tempo=='ok'){
							messageEmail = new PNotify({
		                        title: 'Temporada Creada',
		                        text: 'Fue registrada la temporada: '+t_tempo,
		                        type: 'success',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_tempo_modal").modal('hide');
							temp.refresh_list_tempo();
					}else if(json.tempo=='existe'){
							messageEmail = new PNotify({
		                        title: 'Temporada ya Existe',
		                        text: 'No se registro la temporada '+t_tempo,
		                        type: 'warning',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.tempo=='update'){
							messageEmail = new PNotify({
		                        title: 'Temporada Actualizada',
		                        text: 'Fue actualizada correctamente la temporada: '+t_tempo,
		                        type: 'success',
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_tempo_modal").modal('hide');
							temp.refresh_list_tempo();
					}else if(json.tempo=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la temporada: '+t_tempo,
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
		                        text: 'Ocurrio un error registrando la tienda '+t_tempo,
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
		temp.edit_tempo = function(tempo_id, e){		
			$('#accion').attr({ value: '' });	
			var fileName ="";	
				$.ajax({
			    url : 'ajax/mod_tempo/save_temp.php',
			    data : { 
			    	tmp_id_d:tempo_id ,
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
					if(json.tempor=='ok'){

						$("#cargar_tmp").load("ajax/mod_tempo/add_temp.php?marca="+json.tmp+"&pai=aaasssc1&accion_=update",
						function(e){
							$('#txtnomb').attr({ value: json.temporada }); 		
							$('#idtempo').attr({ value: json.tmp }); 
							$('#accion').attr({ value: 'update' });	
							$('#s2_temp > option[value="'+json.marcap+'"]').attr('selected', 'selected');
							$('#s2_identi > option[value="'+json.identi+'"]').attr('selected', 'selected');
							 var nmes = json.mes_;
							
							 nmes = nmes.split("-");
							$("input:checkbox").val([nmes[0],nmes[1],nmes[2],nmes[3],nmes[4],nmes[5],nmes[6],nmes[7],nmes[8],nmes[9],nmes[10],nmes[11],nmes[12]]); 
							
							}
						);
						$("#form_tempo_modal").modal({
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

		temp.validar = function(id,e){
			console.log("id="+id);
			var identifica = document.getElementById('s2_temp');
			var iden = "";
			if(identifica.selectedIndex>0){
				iden = identifica.options[identifica.selectedIndex].value;				
			}
				$.ajax({
			    url : 'ajax/mod_tempo/save_temp.php',
			    data : { 
			    	tmp_id_d:iden,
			    	accion: 'validar'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Validando informaci\u00f3n',
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
					if(json.validar=='ok'){
							 var nmes = json.mes_;
							console.log(json.mes_);
							 nmes = nmes.split("-");
							
							for(k=1;k<=12;k++){
								$.each(nmes,function(index,contenido){
									if(k==contenido){
									console.log("k="+contenido);
									$("input:checkbox").val(contenido).prop("disabled", true); 
									}else{
										$("input:checkbox").val(contenido).prop("disabled",false); 
									}
								});
								
							}
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
		
		temp.refresh_list_tempo = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_tempo/list_view_tempo.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		
		temp.carga_tienda = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_tienda/list_view_tiendas_details.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		temp.delet_tempo = function(id_marcap,e){
			
			$.ajax({
			    url : 'ajax/mod_tempo/save_temp.php',
			    data : { 
			    	marca_id_d:id_marcap ,
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
					if(json.tempoc=='ok'){
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
						    url : 'ajax/mod_tempo/save_temp.php',				
						    data : { 
						    	marca_id_d:id_marcap ,
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
	    		    	if (json.tempod == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Temporada Borrada',
		                        text: 'Se ha borrado correctamente '+json.nmarca,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							temp.refresh_list_tempo();
	    		    	}else if(json.tempod == 'no_borrar'){

        		    		messageEmail = new PNotify({
		                        title: 'Temporada no Borrada',
		                        text: 'No puede borrar temporadas con Datos.',
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
		                        text: 'La Temporada no se pudo borrar.',
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
		
		temp.status_tempo = function(id_marcap,e){
			
			$.ajax({
			    url : 'ajax/mod_tempo/save_temp.php',
			    data : { 
			    	marca_id_d:id_marcap ,
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
					if(json.tempoc=='ok'){
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
						    url : 'ajax/mod_tempo/save_temp.php',				
						    data : { 
						    	marca_id_d:id_marcap,
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
	    		    	if (json.tempod == 'update') {
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
							temp.refresh_list_tempo();
	    		    	}else if(json.tempod == 'no_update'){

        		    		messageEmail = new PNotify({
		                        title: 'Estaus no actualizado',
		                        text: 'No puede '+status+' Temporada con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.tempod == 'error'){
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'La Temporada no se pudo '+status+'.',
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
		
		}( window.temp = window.temp|| {}, jQuery ));
});

function asignar_card(x,e){
	var cadena = rand_code(caracteres,longitud);
		$.ajax({
			    url : 'ajax/mod_clientes/clientes_script.php',
			    data : { 
			    	tmp_id_d:x,
					token:cadena,
			    	accion: 'asg'
			    },	
			    type : 'POST',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#loader_card").show();
			    	messagebefore = new PNotify({
                        title: 'Validando informaci\u00f3n',
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
					$("#loader_card").hide();
					if(json.response=='ok'){
					$("#tarjeta").show();
					$("#btn-cd").hide();
					$("#ncard").html(json.card);
					$("#nname").html(json.name);
					}else if(json.response==0){
							messagebefore = new PNotify({
							title: 'ERROR',
							text: 'Ocurrio un ERROR...',
							type: 'error',
							delay : 2000
							
						});
						$(e).css({
									'color':'#337ab7', 
									'pointer-events': 'auto',
									'cursor': 'pointer'
								});
					}else if(json.response==1){
							messagebefore = new PNotify({
							title: 'ERROR',
							text: 'Ocurrio un con la Asignación...',
							type: 'error',
							delay : 2000
							
						});
						$(e).css({
									'color':'#337ab7', 
									'pointer-events': 'auto',
									'cursor': 'pointer'
								});
					}else if(json.response==0){
							messagebefore = new PNotify({
							title: 'ERROR',
							text: 'El cliente ya tiene una tarjeta...',
							type: 'info',
							delay : 2000
							
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
function llenar(s){
	
	var llena = "1,"+"2";
	return llena;
}