$(document).ready(function(){
	
	(function(cambio, $, undefined){
		
		
	cambio.update_price  = function(cambio,paism,fecha_p,paiscod,e){
		$("#txtcambio").val(cambio);
		$("#pais_tasa").html(paism+" al "+fecha_p);
		$("#pais_tasa_").val(paiscod);
		$("#form_cambio_modal").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				
		});
	}
	
	cambio.add_price_request  = function(cambio,paism,fecha_p,paiscod,e){
		$("#form_cambio_modal_add").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				
		});
		
	}
	
	cambio.add_price_insert = function(fecha_p,e){
		var monto_i = $("#txtcambio_i").val();
		var sl_pais_c = document.getElementById('sl_pais_c');
		var pais_s = "";
		if(monto_i==""){
			  $("#txtcambio_i").focus();
			  $('#txtcambio_i').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txtcambio_i').parent().removeClass('has-success has-error');
			}
		if(sl_pais_c.selectedIndex>0){
				pais_s = sl_pais_c.options[sl_pais_c.selectedIndex].value;		
			}
			if(pais_s==""){
				$('#mostrar_error_cam').parent().addClass('has-error has-feedback');
				return;
			}else{
				$('#mostrar_error_cam').parent().removeClass('has-success has-error'); 
			}
			
					$.ajax({
						    url : 'ajax/mod_cambio/cambio_script.php',				
						    data : { 
						    	cod:pais_s,
								mont:monto_i,
								accion: 'addcambio',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Agregando el tipo de cambio ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
						cerrar_form2(e);
						ref_tasa(e);
	    		    	if (json.response == 'ok') {
							
	        		    	messageEmail = new PNotify({
		                        title: 'Tasa Aagregada',
		                        text: 'Tasa actulizada a : '+monto_i+ 'x 1USD',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							
							
	    		    	}else{
        		    		messageEmail = new PNotify({
		                        title: 'Tasa no actualizada',
		                        text: 'No se puedo actualizar',
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
	cambio.update_price_request  = function(cambio,paism,fecha_p,e){
		var tasa = $("#txtcambio").val();
		var pais_tasa = $("#pais_tasa_").val();
					swal({
						title: "\xbf Seguro cambiar la tasa actual a "+tasa+ " x 1USD",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Cambiar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_cambio/cambio_script.php',				
						    data : { 
						    	cod:pais_tasa,
								mont:tasa,
								accion: 'upcambio',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando el tipo de cambio ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
						cerrar_form(e);
						ref_tasa(e);
	    		    	if (json.response == 'ok') {
							
	        		    	messageEmail = new PNotify({
		                        title: 'Tasa Actualizada',
		                        text: 'Tasa actulizada a : '+tasa+ 'x 1USD',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							
							
	    		    	}else{
        		    		messageEmail = new PNotify({
		                        title: 'Tasa no actualizada',
		                        text: 'No se puedo actualizar',
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
	
	function ref_tasa(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_cambio/list_view.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		function cerrar_form() {
			$("#form_cambio_modal").modal('hide');
			$('#form_upd_cambio')[0].reset();
			$("#pais_tasa").html('');
			$("#pais_tasa_").html('');
		}

		function cerrar_form2() {
			$("#form_cambio_modal_add").modal('hide');
			$('#form_add_cambio')[0].reset();

		}
	
		}( window.cambio = window.cambio|| {}, jQuery ));
})
	