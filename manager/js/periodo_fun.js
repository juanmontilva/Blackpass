function valida_periodos(e){
	var fecha = new Date();
	var ano = fecha.getFullYear();
	$.ajax({
				url : 'ajax/mod_periodos/periodo_script.php',				
						    data : { 
						    	periodo:ano,
								accion: 'validar'
						    	
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Validando periodo '+ano+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha validado el periodo: '+ano,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							if(json.stado==1){
								$("#information").show();
							}
							if(json.stado==0){
								ano = ano+1;
        		    		    periodos_new(ano);
							}
							
	    		    	}else if(json.response == '1'){
							if(json.stado==1)
							periodos_new(ano);
							else if(json.stado==0)
							ano = ano+1;
        		    		periodos_new(ano);
        		    	}		
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
				
}	
	
	function periodos_new(n,e){
			var namec = $("#codigo").val();
			swal({
						title: "\xbf Deseas Crear el periodo "+n+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Crear !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_periodos/periodo_script.php',				
						    data : { 
						    	periodo:n,
								accion: 'open',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Creando periodo '+n+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
							});
							$("#loading_p").show();
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
							$("#loading_p").hide();
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha registrado el periodo : '+n,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							refresh_list_periodos();
							/*$("#form_cambio_modal").hide();
							$("#form_cambio_modal").modal('hide');//ocultamos el modal
							$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							refresh_list_periodos();*/
							$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Registro no insertado',
		                        text: 'No se puedo pagar '+namec,
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
		
		function close_periodo(p,e){
			swal({
						title: "\xbf Deseas Cerrar el periodo "+p+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Cerrar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_periodos/periodo_script.php',				
						    data : { 
						    	periodo:p,
								accion: 'close',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Cerrando periodo '+p+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 2500
							});
							$("#loading_p").show();
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
							$("#loading_p").hide();
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha Cerrado el periodo : '+p,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							refresh_list_periodos();
							/*$("#form_cambio_modal").hide();
							$("#form_cambio_modal").modal('hide');//ocultamos el modal
							$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							refresh_list_periodos();*/
							$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Cierre no realizado',
		                        text: 'No se puedo cerrar '+p,
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
		
		function refresh_list_periodos(){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_periodos/?token_id=2122sKSKDSKDADKJAKDSJDKSDKASMUESCSWWSXSXSXSA", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}		