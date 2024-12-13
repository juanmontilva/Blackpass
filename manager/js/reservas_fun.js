		function buscar_horas(e){
			//alert("xx");
			var servicio = document.getElementById('N_hab').value;
			var cliente = document.getElementById('title').value;
			var localidad = document.getElementById('t_sev').value;
			var fecha = document.getElementById('start').value;
			var prof = document.getElementById('id_prof').value;
			if(servicio == "" || cliente == "" || localidad ==""){
				messageEmail = new PNotify({
		                        title: 'ERROR',
		                        text: 'Debe seleccionar: \n - Cliente \n - Servicio \n - Localidad',
		                        type: 'error',
								delay : 4000
		                    });
				return
			}
			$.ajax({
			    url : 'ajax/mod_reservas/reservas_script.php?accion=1',
			    data : { 
					cli:cliente,
					local:localidad,
					serv:servicio,
					fech:fecha,
					pro: prof,
			    	accion: 'ver_h'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					//$('#btn_sc').attr("disabled", true);
					$("#loader").show();
					$('#mhora').hide();
					$("#mhora").prop('selectedIndex', -1);
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
					//$('#btn_sc').attr("disabled", false);
					$("#loader").hide();
					
					if(json.response=='ok'){
						$('#mhora option').remove();
						
							messageEmail = new PNotify({
		                        title: 'Cargando data',
		                        text: 'Estoy cargando su busqueda',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							var j = 1;
							 var horas = [];	
							 $("#mhora").show();
							  $.each(json.disponi, function(d){
                               horas.push(json.disponi[d].hora);
							     $('#hr_d').append('<option value='+json.disponi[d].hora+' >'+json.disponi[d].hora+'</option>');

							    j=j+1;
								//$('#carga_cat').prepend(list_event).addClass('product-slider owl-carousel');
								 //$('#carga_busqueda').append(list_event);
                            });
					}				
					else {
						$('#N_hab option').remove(); // clear all values
						$('#id_prof option').remove(); // clear all values
						messageEmail = new PNotify({
		                        title: 'Error al Buscar',
		                        text: 'No hay servicios disponibles',
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
		function buscar_s(e){
			
			var t_hab = document.getElementById('t_sev').value;
				$.ajax({
			    url : 'ajax/mod_reservas/reservas_script.php',
			    data : { 
					nhab:t_hab,
			    	accion: 'search_h'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					//$('#btn_sc').attr("disabled", true);
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
					//$('#btn_sc').attr("disabled", false);
					$("#loader").hide();
					
					if(json.response=='ok'){
						$('#N_hab option').remove(); // clear all values
						$('#id_prof option').remove(); // clear all values
							messageEmail = new PNotify({
		                        title: 'Cargando data',
		                        text: 'Estoy cargando su busqueda',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							var j = 1;
							 var habitacion = [];	
							  $('#N_hab').append('<option value="">--</option>');
							  $('#N_hab').select2();
							  $.each(json.habitacion, function(d){
                               habitacion.push(json.habitacion[d].idh);
							   
							     $('#N_hab').append('<option value='+json.habitacion[d].idh+' >'+json.habitacion[d].codigo+'</option>');

							    j=j+1;
								//$('#carga_cat').prepend(list_event).addClass('product-slider owl-carousel');
								 //$('#carga_busqueda').append(list_event);
                            });
					}				
					else {
						$('#N_hab option').remove(); // clear all values
						$('#id_prof option').remove(); // clear all values
						messageEmail = new PNotify({
		                        title: 'Error al Buscar',
		                        text: 'No hay servicios disponibles',
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
		function buscar_e(e){
			
			var t_hab = document.getElementById('N_hab').value;
				$.ajax({
			    url : 'ajax/mod_reservas/reservas_script.php',
			    data : { 
					nhab:t_hab,
					local: document.getElementById('t_sev').value,
			    	accion: 'search_e'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					//$('#btn_sc').attr("disabled", true);
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
					//$('#btn_sc').attr("disabled", false);
					$("#loader").hide();
					
					if(json.response=='ok'){
						$('#id_prof option').remove(); // clear all values
							messageEmail = new PNotify({
		                        title: 'Cargando data',
		                        text: 'Estoy cargando su busqueda',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							var j = 1;
							 var habitacion = [];	
							 
							  $.each(json.habitacion, function(d){
                               habitacion.push(json.habitacion[d].idh);
							     $('#id_prof').append('<option value='+json.habitacion[d].idh+' >'+json.habitacion[d].codigo+'</option>');

							    j=j+1;
								//$('#carga_cat').prepend(list_event).addClass('product-slider owl-carousel');
								 //$('#carga_busqueda').append(list_event);
                            });
					}				
					else {
						$('#id_prof option').remove(); // clear all values
						messageEmail = new PNotify({
		                        title: 'ERROR EN PROFESIONALES',
		                        text: 'No hay profesionales disponibles',
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