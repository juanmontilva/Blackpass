$(document).ready(function(){
	var cliente_id = "";
	(function( locali, $, undefined ) {

		//Modal Registro Localidad//
		locali.new_local = function( e){	
				$("#form_loca_modal").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				
					});
		}
		locali.edit_cc = function(x,e){	
			$("#form_loca_modal").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				
			});
			$("#idmarca").val(x);
			$.ajax({
			    url : 'ajax/mod_localidad/save_localidad.php',
			    type : 'GET',
			    data: {
					marca_id_d:x,
					accion: "consultar"
				},
				dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 1
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },
				success: function(json)
                {
					if(json.response=="ok"){
						messagebefore = new PNotify({
                        title: 'Información de estados',
                        text: 'Estados Cargados',
                        type: 'success',
						delay : 1
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
						$("#localidad").val(json.local);
						$("#txtnumero").val(json.num);
					//var pais = [];	
					 $("#s2_country option").each(function(){
						var v1 = $(this).val();
						//$.each(json.local, function(d){
							if (v1 == json.pais){
								$("#s2_country option[value="+v1+"]").attr("selected",true);
								locali.buscar_pro(json.pais);
							}
						//});
					 });
					
					 $("#s2_country").select2();
					 setTimeout(function(){ 
						 $("#sl_ciudades option").each(function(){
							var v2 = $(this).val();
								if (v2 == json.prov){
									//alert("igual "+v2);
									$("#sl_ciudades option[value="+v2+"]").attr("selected",true);
								}
						 }); 
					$("#sl_ciudades").select2();
					 }, 1000);				  
					  $("#accion_l").val('update_l');
					 $.each(json.hora, function(d){
						 if(json.hora[d].ndia==1){
							  $("#i_lun option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_lun option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						 if(json.hora[d].ndia==2){
							  $("#i_mar option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_mar option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						 if(json.hora[d].ndia==3){
							  $("#i_mie option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_mie option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						 if(json.hora[d].ndia==4){
							  $("#i_jue option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_jue option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						 if(json.hora[d].ndia==5){
							  $("#i_vie option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_vie option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						 if(json.hora[d].ndia==6){
							  $("#i_sab option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_sab option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						 if(json.hora[d].ndia==7){
							  $("#i_dom option[value="+json.hora[d].hi+"]").attr("selected",true);
							  $("#f_dom option[value="+json.hora[d].hf+"]").attr("selected",true);
						 }
						
					 });
					 $("select").select2();
					  for(var j=1;j<=7;j++){
						  var marcar = 0;
						  $.each(json.hora, function(i){
								if(j==json.hora[i].ndia){
								 $("#dias").prop("checked", true); 	
								marcar = 1;
								}else{
								  $("#dias").prop("checked", false); 
								  $('input[name=dias]').attr('checked', false);				 
								}   							
							});
							if(marcar==0){
								$("input:checkbox").each(function() {							
								var box = $(this).val();
								if(box==j){
									//$("#dias").prop("checked", false);  
									$("input:checkbox[value="+j+"]").removeAttr("checked");
									 console.log(marcar+"-"+j);
								}
							    								
								});

							}
					  }
					  
					}	
                   
                },				
				error: function(error){
			    	console.log(error);
			    }
			});
		}
		
		//Buscar Estados//
		locali.buscar_pro = function(id_pais, e){
		var combo = document.getElementById('s2_country');
		if(combo.selectedIndex>0){
			//alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);	
			var formData = 'id_category='+combo.options[combo.selectedIndex].value;
			$.ajax({
			    url : 'ajax/mod_localidad/localidad_estado.php',
			    type : 'GET',
			    data: formData,
				cache: false,
				contentType: false,
                processData: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 1
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
			    },
				success: function(datos)
                {
					if(datos!=""){
						messagebefore = new PNotify({
                        title: 'Información de estados',
                        text: 'Estados Cargados',
                        type: 'success',
						delay : 1
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(datos==""){
						messagebefore = new PNotify({
                        title: 'Información de estados',
                        text: 'Nohay informaci\u00f3n de estados',
                        type: 'warning',
						delay : 0
						
                    });
					$(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}	
                    $("#sl_ciudades").html(datos);
                },				
				error: function(error){
			    	console.log(error);
			    }
			});
			}
		}
		//Guardar Localidad//
		locali.save_local  = function(e){
			var idpais = document.getElementById('s2_country');
			var idestado = document.getElementById('sl_ciudades');
			var pais = "1";
			var estado ="81";
			
			if(idpais.selectedIndex>0){
			//alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);	
			pais = idpais.options[idpais.selectedIndex].value;
			}
			if(idestado.selectedIndex>0){
			//alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);	
			estado = idestado.options[idestado.selectedIndex].value;
			}
			if(pais=="" || estado==""){
			 $('#mostrar_error').parent().addClass('has-error has-feedback');
			 return;
			}
			if(estado==""){
			 $('#mostrar_error_p').parent().addClass('has-error has-feedback');
			 return;
			}
			//var pais = 1;
			//var estado = 2;
			var t_localidad = $("#localidad").val();
			if(t_localidad==""){
			 $('#localidad').parent().addClass('has-error has-feedback');
			 return;	
			}
			var t_accion = $("#accion_l").val();
			var selected2 = '';    
			$('input[type=checkbox]').each(function(){
				if (this.checked) {
					selected2 += $(this).val()+', ';
				}
			}); 
			//alert("="+selected2);
			if (selected2 == '') {
				alert('Debes seleccionar al menos una opción.');
				return false;
			}
			var idmarca = $("#idmarca").val();
			var valoresCheck =[];
			$("input[type=checkbox]:checked").each(function(){
				valoresCheck.push(this.value);
			});
			//alert(valoresCheck);
			$.ajax({
			    url : 'ajax/mod_localidad/save_localidad.php',
			    data : { 
					nombre_l: t_localidad,
					id_est: estado,
					id_pais:pais,
					idmarca : idmarca,
					dias:valoresCheck,
					numero:$("#txtnumero").val(),
					i_1 : $("#i_lun").val(),
					f_1 : $("#f_lun").val(),
					i_2 : $("#i_mar").val(),
					f_2 : $("#f_mar").val(),
					i_3 : $("#i_mie").val(),
					f_3 : $("#f_mie").val(),
					i_4 : $("#i_jue").val(),
					f_4 : $("#f_jue").val(),
					i_5 : $("#i_vie").val(),
					f_5 : $("#f_vie").val(),
					i_6 : $("#i_sab").val(),
					f_6 : $("#f_sab").val(),
					i_7 : $("#i_dom").val(),
					f_7 :	$("#f_dom").val(),		    	
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
					if(json.local=='ok'){
							messageEmail = new PNotify({
		                        title: 'Comercio Creado',
		                        text: 'Fue registrado el Comercio: '+t_localidad,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							marca.refresh_list_local(1);
					}else if(json.local=='existe'){
							messageEmail = new PNotify({
		                        title: 'Comercio Existente',
		                        text: 'No se registro el comercio',
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.local=='update'){
							messageEmail = new PNotify({
		                        title: 'Comercio Actualizado',
		                        text: 'Fue actualizada correctamente el comercio: '+t_localidad,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							marca.refresh_list_local(4);
					}else if(json.local=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamenteel comercio: '+t_localidad,
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							marca.refresh_list_local(1);
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando el comercio',
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
		marca.refresh_list_local = function(z){
			$("#form_loca_modal").modal('hide');
            $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_localidad/list_view_local_cc.php?pais_id=2122A&ubicar="+z, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });	
        }
		marca.refresh_list_marca = function(z){

			$("#ajax-content").load("ajax/mod_localidad/list_view_marcas.php?pais_id=2122A&ubicar="+z, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });	
        }
		
		locali.carga_cc = function(id_cc){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_localidad/list_view_local_cc.php?pais_id=2122A&ubicar="+id_cc, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });	
			
		}
		locali.carga_pais= function(id_cc){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_localidad/list_view_local.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });	
			
		}
		locali.status_pais= function(id_pais,status,e){
			var estad="";
			if(status===1){
				estad = "Activar";
			}else if(status===0){
				estad = "Desactivar";
			}
			swal({
						title: "\xbf Deseas "+estad+" el Comercio ?",   
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
						    url : 'ajax/mod_localidad/save_localidad.php',				
						    data : { 
						    	pais:id_pais ,
								stat:status,
								accion: 'upais',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando el estatus del Comercio ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.pais == 'update') {
	        		    	messageEmail = new PNotify({
		                        title: 'Comercio Actualizado',
		                        text: 'Se ha '+estad+' correctamente el comercio: '+json.nombre,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							marca.refresh_list_marca(0);
	    		    	}else if(json.marcad == 'no_updater'){

        		    		messageEmail = new PNotify({
		                        title: 'Comercio no actualizado',
		                        text: 'No se puedo actualizar',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.pais=='con_loca'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se puede actualizar este Comercio ya que tiene :'+json.cantidad+' Localidades Activas',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							marca.refresh_list_marca(0);
						}	
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});			
		}
		locali.del_cc= function(id_cc,status,e){
			
			swal({
						title: "\xbf Deseas Eliminar la Ubicación ?",   
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
						    url : 'ajax/mod_localidad/save_localidad.php',				
						    data : { 
						    	cc:id_cc ,
								stat:status,
								accion: 'delete',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando Ubicacaciones ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Ubicación Eliminada',
		                        text: 'Se ha eliminado correctamente la Ubicación: '+json.nombre,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							locali.carga_cc(json.pais);
	    		    	}else if(json.response == 'no_borrar'){
        		    		messageEmail = new PNotify({
		                        title: 'Ubicación no eliminada',
		                        text: 'No se puedo eliminar Ubicación con datos',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.pais=='con_loca'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se puede Eliminar esta Ubicación ya que tiene :'+json.cantidad+' Servicios',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							marca.refresh_list_local(1);
						}	
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});			
		}
			locali.status_cc = function(id_cc,status,e){
			var estad="";
			if(status===1){
				estad = "Activar";
			}else if(status===0){
				estad = "Desactivar";
			}
			swal({
						title: "\xbf Deseas "+estad+" Ubicación ?",   
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
						    url : 'ajax/mod_localidad/save_localidad.php',				
						    data : { 
						    	cc:id_cc ,
								stat:status,
								accion: 'upcc',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando el estatus de la Ubicación ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.cc == 'update') {
	        		    	messageEmail = new PNotify({
		                        title: 'Ubicación Actualizada',
		                        text: 'Se ha '+estad+' correctamente la Ubicación: '+json.nombre,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							locali.carga_cc(json.pais);
	    		    	}else if(json.cc == 'no_updater'){
        		    		messageEmail = new PNotify({
		                        title: 'Localidad no actualizada',
		                        text: 'No se puedo actualizar',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.pais=='con_loca'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se puede actualizar este Comercio ya que tiene :'+json.cantidad+' Tiendas',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							locali.carga_cc(json.pais);
						}	
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});			
		}
		}( window.locali = window.locali|| {}, jQuery ));
})
		
