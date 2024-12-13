$(document).ready(function(){
	var text_sms = "";
	var cliente_id = "";
	(function(vent, $, undefined ) {
		vent.calendarInit = function()
		{
			$('#fecha_i').mask("9999-99-9999",{placeholder:" "});
			$('#fecha_f').mask("9999-99-9999",{placeholder:" "});
			$('#fecha_o').mask("9999-99-9999",{placeholder:" "});
			$('.input-daterange').datepicker({
				  format: 'yyyy-mm-dd',
                language: 'es',
                //startDate: ''+now+'',
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true
			});
			$('.input-daterange').on("hide.bs.modal", function(e){
				e.stopPropagation();
			})
		}
		vent.impor_ventas = function(id_marcap, e){	
		$('#form_ventas_import')[0].reset();
		$("#respuesta_file").html('');
		$( "#respuesta_file" ).empty();
		$("#error_id_tienda").hide();
		$('#idtienda2').attr({ value: id_marcap }); 
		$("#form_ventas_import_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}
		
		vent.borrar_mes = function(id_marca, marca_t,year,mes,e){
				swal({
						title: "\xbf Deseas Eliminar las ventas de :"+mes+"-"+year+" "+marca_t+" ?",   
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
						    url : 'ajax/mod_ventas/ventas_script.php',				
						    data : { 
						    	marca_id_d:id_marca ,
								mes:mes,
								year:year,
								accion: 'delete_ventas_mes',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Validando permisos ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.delete == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Ventas Borradas',
		                        text: 'Se ha borrado correctamente las ventas ',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							vent.carga_ventas_mes(id_marca);
	    		    	}else if(json.delete == 'permiso'){

        		    		messageEmail = new PNotify({
		                        title: 'Error',
		                        text: 'No tiene permisos para borrar todas las ventas.',
		                        type: 'error'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else{
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'No se pudo borrar la información.',
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
			
		}
		
		vent.borrar_todas = function(id_marca, marca_t,e){
						swal({
						title: "\xbf Deseas Eliminar las ventas de : "+marca_t+" ?",   
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
						    url : 'ajax/mod_ventas/ventas_script.php',				
						    data : { 
						    	marca_id_d:id_marca ,
								accion: 'delete_ventas_all',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Validando permisos ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.delete == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Ventas Borradas',
		                        text: 'Se ha borrado correctamente las ventas ',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							vent.carga_ventas_r();
	    		    	}else if(json.delete == 'permiso'){

        		    		messageEmail = new PNotify({
		                        title: 'Error',
		                        text: 'No tiene permisos para borrar todas las ventas.',
		                        type: 'error'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else{
	    		    		messageEmail = new PNotify({
		                        title: 'Ocurri\u00f3 un problema',
		                        text: 'No se pudo borrar la información.',
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
			
		}
		vent.genera_plantilla = function(e){	
		   var tienda = $("#idtienda").val();
		   var fechai = $("#fecha_i").val();
		   var fechaf = $("#fecha_f").val();
		   
		   if(tienda != "" && fechai != "" && fechaf != ""){
			   	    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 4000	
                    });
			var url_request = "tienda="+tienda+"&fecha_i="+fechai+"&fecha_f="+fechaf;
			window.open("ajax/mod_rep/crea_plantilla_ventas.php?tipo=1&"+url_request,'_self'); 
			$("#form_ventas_planti_modal").modal('hide');
			}
		   			
		}
		vent.save_venta = function(e) {
			var tienda_id = $("#idtienda_venta").val();
			var tfecha = $("#fecha_o").val();
			var tpeizas = $("#txt_piezas").val();
			var tticke = $("#txt_tick").val();
			var tbruto = $("#txt_bruto").val();
			var tiva = $("#txt_iva").val();
			var neto = $("#txt_neto").val();
			
			if(tfecha==""){
			  $("#fecha_o").focus();
			  $('#fecha_o').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#fecha_o').parent().removeClass('has-success has-error');
			}
			if(tpeizas==""){
			  $("#txt_piezas").focus();
			  $('#txt_piezas').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_piezas').parent().removeClass('has-success has-error');
			}
			if(tticke==""){
			  $("#txt_tick").focus();
			  $('#txt_tick').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_tick').parent().removeClass('has-success has-error');
			}
			if(tbruto==""){
			  $("#txt_bruto").focus();
			  $('#txt_bruto').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_bruto').parent().removeClass('has-success has-error');
			}
			if(tiva==""){
			  $("#txt_iva").focus();
			  $('#txt_iva').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_iva').parent().removeClass('has-success has-error');
			}
			if(neto==""){
			  $("#txt_neto").focus();
			  $('#txt_neto').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_neto').parent().removeClass('has-success has-error');
			}
			
				$.ajax({
			    url : 'ajax/mod_ventas/ventas_script.php',
			    data : { 
					idtienda: tienda_id,
					fecha_v: tfecha,
					piezas: tpeizas,
					tick:tticke,
					bruto:tbruto,
					iva:tiva,
					tt:neto,
			    	accion: 'add_v'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#ventas_send").attr("disabled", true);
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
					if(json.ventas=='ok'){
							messageEmail = new PNotify({
		                        title: 'Ventas Registrada',
		                        text: 'Fue cargado con exito su venta ',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_registro_venta_modal").modal('hide');
							vent.carga_tiendas(json.marca);
					}else {
						messageEmail = new PNotify({
		                        title: 'Error al Asignar',
		                        text: 'Ocurrio un error asignando la venta',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#ventas_send").attr("disabled", false);
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			

		}
		vent.cerrar_form = function() {
			$("#form_ventas_import_modal").modal('hide');
			$('#form_ventas_import')[0].reset();
			$("#respuesta_file").html('');
			$("#respuesta_file").hide();
			$("#tabla_ventas ").html('');
			$("#error_id_tienda").hide();
			
		}
				
		vent.plantilla_ventas = function(id_marcap, e){	
		vent.aisgnaid(id_marcap);
		
		vent.calendarInit();
		$("#form_ventas_planti_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		vent.registr_venta = function(id_marcap, e){	
		$("#idtienda_venta").val(id_marcap);
		vent.calendarInit();
		$("#form_registro_venta_modal").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		
		
		
		
		vent.aisgnaid = function(id_tienda, e){	
		$("#idtienda").val(id_tienda);
		}
		
			vent.mover_file = function(id,e){
				var idtienda_mov = $("#movertienda").val();
				vent.save_file(idtienda_mov);
			}
		
			vent.save_file = function(id,e){	
			var campos1, campos2, campos3, campos4, campos5, campos6, campos7;
			$("#tabla_ventas tr").each(function (index) 
			{	$(this).css("background-color", "#ECF8E0");
				var campo1, campo2, campo3, campo4, campo5, campo6, campo7;
				
				$(this).children("td").each(function (index2) 
				{
				
					switch (index2) 
					{
						case 0: campo1 = $(this).text();
								break;
						case 1: campo2 = $(this).text();
								break;
						case 2: campo3 = $(this).text();
								break;
						case 3: campo4 = $(this).text();
								break;
						case 4: campo5 = $(this).text();
								break;
						case 5: campo6 = $(this).text();
								break;
						case 6: campo7 = $(this).text();
								break;
					}
					$(this).css("background-color", "#ECF8E0");
				})
				console.log(campo1 + ' - ' + campo2 + ' - ' + campo3);

				campos1 = campos1+","+campo1;
				campos2 = campos2+","+campo2;
				campos3 = campos3+","+campo3;
				campos4	= campos4+","+campo4;
				campos5	= campos5+","+campo5;
				campos6 = campos6+","+campo6;
				campos7	= campos7+","+campo7;

			})
			var t_marca = "";
			if(id==""){
				 t_marca = $("#idtienda2").val();
			}else{
				t_marca = id;
			}
			
			var t_logo = $("#excelfile").val();
				$.ajax({
			    url : 'ajax/mod_ventas/ventas_script.php',
			    data : { 
					idtienda: t_marca,
					tienda: campos1,
					fecha_v: campos2,
					piezas: campos3,
					tick:campos4,
					bruto:campos5,
					iva:campos6,
					tt:campos7,
			    	accion: 'add'
			    },	
			    type : 'POST',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#fileupload").attr("disabled", true);
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
					if(json.ventas=='ok'){
							messageEmail = new PNotify({
		                        title: 'Ventas Cargadas',
		                        text: 'Fue cargado con exito sus ventas: ',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_ventas_import_modal").modal('hide');
							vent.carga_tiendas(json.marca);
					}else {
						messageEmail = new PNotify({
		                        title: 'Error al Asignar',
		                        text: 'Ocurrio un error asignando logo a la marca',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#fileupload").attr("disabled", false);
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		}
		vent.nombre_ventda = function(e){
			var combo = document.getElementById('s2_local');
			if(combo.selectedIndex>0){
				var nombre = $("#marca").val()+" "+combo.options[combo.selectedIndex].value;
				$('#txtnombre').attr({ value: nombre }); 
				
			}

		}
		vent.save_ventda = function(e){
			var t_ventda = $("#txtnombre").val();
			var t_razon = $("#txtrazon").val();
			var idlocal = document.getElementById('s2_local');
			var id_ubic = $("#ubicaid").val();
			var t_accion = $("#accion").val();
			var email = $("#txtemail").val();
			var mts = $("#txtmt").val();
			var direc = $("#txtdir").val();
			var tef = $("#txtphone").val();
			var nlocalidad= $("#local_").val();
			var ventdaid= $("#idventda").val();
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
			if(t_ventda==""){
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
			    url : 'ajax/mod_ventda/save_ventda.php',
			    data : { 
					nombre_t: t_ventda,
					id_ubic:id_ubic,
					tef:tef,
					rsocial:t_razon,
					email_:email,
					direc_:direc,
					nlocal_:local_,
					mts_:mts,
					ventda_id_:ventdaid,
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
					if(json.ventda=='ok'){
							messageEmail = new PNotify({
		                        title: 'Tienda Creada',
		                        text: 'Fue registrada la ventda: '+t_ventda,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_ventda_modal").modal('hide');
							vent.refresh_list_ventdas();
					}else if(json.ventda=='existe'){
							messageEmail = new PNotify({
		                        title: 'Tienda ya Existe',
		                        text: 'No se registro la ventda '+t_ventda,
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.ventda=='update'){
							messageEmail = new PNotify({
		                        title: 'Tienda Actualizada',
		                        text: 'Fue actualizada correctamente la ventda: '+t_ventda,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_ventda_modal").modal('hide');
							vent.refresh_list_ventdas();
					}else if(json.local=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la ventda: '+t_ventda,
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
		                        text: 'Ocurrio un error registrando la ventda '+t_ventda,
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
			
		vent.refresh_list_tiendas = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_ventas/add_venta.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		
		vent.carga_tiendas = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_tiendas_ventas.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		vent.carga_ventas_mes = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas_mes.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
		}
		vent.carga_ventas_tien_mes = function(id_tiend,year,mes){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas_mes_tiendas.php?pais_id=2122A&ubicar="+id_tiend+"&mas="+year+"&mass="+mes, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}
		
		vent.carga_ventas_tien_dia = function(id_tiend,year,mes){
			$("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas_dia.php?pais_id=2122A&ubicar="+id_tiend+"&mas="+year+"&mass="+mes, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}
		
		
		vent.carga_tiendas_ventas_det = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas_det.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		vent.carga_tiendas_ventas_det2 = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_bi/list_view_ventas_det.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		vent.url_ventas = function(id_marcap){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas.php?pais_id=2122A&ubicar="+id_marcap, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	
		
		vent.carga_tiendas_ventas_det = function(view_ventas_mes2){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas_mes.php?pais_id=2122A&ubicar="+view_ventas_mes2, function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}
		
		
		
		
		vent.carga_ventas_r = function(){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_ventas/list_view_ventas.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
		}
		vent.activar_check  = function(e){	
			var marcado = $("#delete_all").prop("checked");
			if(marcado==true){
				for(var l=1;l<=31;l++){
					$("#delete_v"+l).prop("checked", true);
				}
			}else{
				for(var l=1;l<=31;l++){
					$("#delete_v"+l).prop("checked", false);
				}
			}				 
		}
		vent.edit_ventda = function(ventda_id, e){		
			$('#accion').attr({ value: '' });	
			var fileName ="";	
				$.ajax({
			    url : 'ajax/mod_ventda/save_ventda.php',
			    data : { 
			    	marca_id_d:ventda_id ,
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
					if(json.ventdar=='ok'){

						$("#cargar").load("ajax/mod_ventda/add_ventda.php?marca="+json.mp+"&pai=aaasssc1&accion_=update",
						function(e){
							$('#txtrazon').attr({ value: json.nrazon }); 		
							$('#txtnombre').attr({ value: json.ventdan }); 
							$('#txtphone').attr({ value: json.phone }); 
							$('#txtemail').attr({ value: json.mail}); 
							$('#txtdir').attr({ value: json.dir }); 
							//$('#s2_local').attr({ value: json.locali }); 
							$('#txtmt').attr({ value: json.mt }); 
							$('#idventda').attr({ value: json.tiedaid }); 
							$('#idmarca').attr({ value: json.marca }); 
							$('#accion').attr({ value: 'update' });	

							 $('#s2_local > option[value="'+json.locali+'"]').attr('selected', 'selected');
							}
						);
						$("#form_ventda_modal").modal({
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
					}else if(json.ventdar==''){
						messageEmail = new PNotify({
		                        title: 'Tienda No modificable',
		                        text: 'No se puede modificar la ventda ',
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
		
		vent.delet_ventda = function(ventda_id, e){
			
			$.ajax({
			    url : 'ajax/mod_ventda/save_ventda.php',
			    data : { 
			    	marca_id_d:ventda_id ,
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
					if(json.ventdac=='ok'){
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
						    url : 'ajax/mod_ventda/save_ventda.php',				
						    data : { 
						    	marca_id_d:ventda_id ,
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
	    		    	if (json.ventdad == 'ok') {
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
							vent.carga_ventda(json.loc);
	    		    	}else if(json.ventdad == 'no_borrar'){

        		    		messageEmail = new PNotify({
		                        title: 'Tienda no Borrada',
		                        text: 'No puede borrar ventda con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.ventdad == 'error'){
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
		vent.status_ventda = function(marca_id, e){
			var marcas="";
			$.ajax({
			    url : 'ajax/mod_ventda/save_ventda.php',
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
					if(json.ventdac=='ok'){
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
						    url : 'ajax/mod_ventda/save_ventda.php',				
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
	    		    	if (json.ventdad == 'update') {
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
							vent.carga_ventda(json.loc);
	    		    	}else if(json.ventdad == 'no_update'){

        		    		messageEmail = new PNotify({
		                        title: 'Estaus no actualizado',
		                        text: 'No puede '+status+' ventda con Datos.',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
        		    	}else if(json.ventdad == 'error'){
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
		}( window.vent = window.vent|| {}, jQuery ));
});
