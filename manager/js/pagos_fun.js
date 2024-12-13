	function rgistrar_pago(cliente, codigo,x,e){
		$("#clientexy").val(cliente);
		$("#codigo").val(codigo);
		var tipo_p = 0;
			$.ajax({
						    url : 'ajax/mod_pagos/pagos_script.php',				
						    data : { 
						    	cod:cliente,
								xy:codigo,
								accion: 'consulta',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Consultando Datos ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
						//cerrar_form2(e);
						//ref_tasa(e);
	    		    	if (json.response == 'ok') {
							
	        		    	messageEmail = new PNotify({
		                        title: 'Datos encontrados',
		                        text: 'Cargando',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#borigen").val(json.category[0].origen);
							$("#bdestino").val(json.category[0].destino);
							$("#referencia").val(json.category[0].referencia);
							$("#montos").val(json.category[0].monto);
							$("#localizador").val(codigo);
							$("#contac").val(json.category[0].contac);
							$("#dir3").val(json.category[0].d3);
							$("#phone").val(json.category[0].phone);
							$("#hora").val(json.category[0].hora);
							tipo_p = json.category[0].tp;
							console.log(json.category[0].tp);
								if(json.category[0].tp==2){
									$("#form_p").hide();
									$("#form_p2").show();
									$("#form_p3").hide();
									if(json.category[0].pick==2){
										$("#form_p3").show();
									}
									
								}else{
									$("#form_p").show();
									$("#form_p2").show();
									$("#form_p3").hide();
								}
							
	    		    	}else{
        		    		messageEmail = new PNotify({
		                        title: 'No se encontro datos de pago',
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
		if(x==1){
			$("#botonera").hide();
		}else if(x==0){
			$("#botonera").show();
		}
		
		$("#form_cambio_modal").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				
		});
	 }
	 	function validar_p(x,e){
		var mensaje = "";
		if(x==2){
			mensaje = "VALIDAR";
		}else{
			mensaje = "RECHAZAR";
		}
		var y = $("#localizador").val();
		var codicl = $("#clientexy").val();
					swal({
						title: "\xbf Seguro que desea "+mensaje+" la recarga "+y,   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, "+mensaje+" !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_pagos/pagos_script.php',				
						    data : { 
						    	cod:y,
								cl:codicl,
								accion: 'validar_p',
						    	confirm: x
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Actualizando la Recarga ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
						cerrar_form(e);
	    		    	if (json.response == 'ok') {
							
	        		    	messageEmail = new PNotify({
		                        title: 'Recarga Validada',
		                        text: 'Se validada la recarga : '+y,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							refresh_list_pagos2();
							
	    		    	}else{
        		    		messageEmail = new PNotify({
		                        title: 'Recarga no validada',
		                        text: 'No se puedo validar',
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
	 function tipo_pago(){
		 
		 var selec = $("#tpc").val();
		 if(selec == 1 || selec == 3 || selec == 6){
			 $("#refere").show();
			 $("#bancos").show();
			 $("#titular_").show();
		 }else{
			 $("#refere").hide();
			 $("#bancos").hide();
			$("#titular_").hide();
		 }
		 
	 }
		
		function save_pago_res(e){
			var namec = $("#codigo").val();
			var idc = $("#id_resv_p").val();
			var txtbanco2 = $("#banco").val();
			var cliente2 = document.getElementById("nombre_c").value;
			var txtref = $("#refe").val();
			var txtmonto = $("#montos").val();
			var tipo = document.getElementById("tpc").value;
			var cliente = $("#ncliente").val();
			if(tipo == 1 || tipo ==3){
					if(txtbanco2==""){
					   $('#banco').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#banco').parent().removeClass('has-success has-error');
					}
					if(cliente2==""){
					  $('#nombre_c').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#nombre_c').parent().removeClass('has-success has-error');
					}
					if(txtref==""){
					  $('#refe').parent().addClass('has-error has-feedback');
						return;			  
					}else{
						$('#refe').parent().removeClass('has-success has-error');
					}
			}	
			if(txtmonto==""){
			  $('#montos').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#montos').parent().removeClass('has-success has-error');
			}
			
			swal({
						title: "\xbf Deseas Recargar a "+cliente+"?",   
						text: "La cantidad de "+txtmonto+" USD",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Registrar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_pagos/pagos_script.php',				
						    data : { 
						    	namec : namec,
								idc :idc,
								txtbanco : txtbanco2,
								txttitular : cliente,
								txtref :txtref,
								txtmonto : txtmonto,
								metodo : tipo,
								accion: 'add',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Recargando Saldo a '+cliente+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha registrado la recarga de: '+cliente,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							//$("#form_cambio_modal").hide();
							//$("#form_cambio_modal").modal('hide');//ocultamos el modal
							//$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							//$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							refresh_list_pagos();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Registro no insertado',
		                        text: 'No se puedo recargar'+cliente,
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
		function refresh_list_pagos(){
			$("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_pagos/list_pago.php", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
		}
		function refresh_list_pagos2(){
			$("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/dashboard.php", function(e){
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
		
		function validar_card(e){
			$("#tarjeta").val();
			if($("#tarjeta").val()==""){
				$("#tpc").prop('disabled', true);
				$("#montos").prop('disabled', true);
			  $("#tarjeta").focus();
			  $('#tarjeta').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$("#tpc").prop('disabled', false);
				$("#montos").prop('disabled', false);
				$('#tarjeta').parent().removeClass('has-success has-error');
			}
				$.ajax({
						    url : 'ajax/mod_pagos/pagos_script.php',				
						    data : { 
						    	namec : $("#tarjeta").val(),
								accion: 'confir'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							$("#loading").show();
							$("#btn_save").prop('disabled', true);
							},
					    success : function(json) {
							$("#loading").hide();
							$("#div_msg").show();
								if (json.response == 'ok') {
									$("#btn_save").prop('disabled', false);
									$("#codigo").val(json.xy);
									$("#ncliente").val(json.name);
									$("#div_msg").html("<div class='form-group'><label class='col-sm-3 control-label'>Cliente</label><div class='col-sm-5'><div  class='alert alert-success'>"+json.name+"</div></div></div>");
								}else if(json.response == '0'){
									$("#div_msg").html("<div class='form-group'><label class='col-sm-3 control-label'>Cliente</label><div class='col-sm-5'><div class='alert alert-danger'>LA TARJETA NO SE PUDO VALIDAR</div></div></div>");
								}	
						},
						    error: function(error){
						    	console.log(error);
						    }
						});
		}
		
		function validar_card2(e){
			$("#tdcv").val();
			if($("#tdcv").val()==""){
				$("#btn_self").prop('disabled', true);
				$("#monto").prop('disabled', true);
			  $("#tdcv").focus();
			  $('#tdcv').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$("#monto").prop('disabled', false);
				$('#tdcv').parent().removeClass('has-success has-error');
			}
				$.ajax({
						    url : 'ajax/mod_pagos/pagos_script.php',				
						    data : { 
						    	namec : $("#tdcv").val(),
								accion: 'confir2'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							$("#loading").show();
							$("#btn_self").prop('disabled', true);
							},
					    success : function(json) {
							$("#loading").hide();
							$("#div_msg").show();
								if (json.response == 'ok') {
									$("#btn_self").prop('disabled', false);
									$("#ncliente").val(json.xy);
									$("#txtcl").val(json.name);
									$("#ordenid").val(json.orde);
									$("#txtcl").addClass('alert alert-success');
									$("#txtsaldo").val(json.disp);
									//$("#div_msg").html("<div class='form-group'><label class='col-sm-3 control-label'>Titular</label><div class='col-sm-5'><div  class='alert alert-success'>"+json.name+"</div></div></div>");
								}else if(json.response == '0'){
									$("#div_msg").html("<div class='form-group'><label class='col-sm-3 control-label'>Titular</label><div class='col-sm-5'><div class='alert alert-danger'>LA TARJETA NO SE PUDO VALIDAR</div></div></div>");
								}	
						},
						    error: function(error){
						    	console.log(error);
						    }
						});
		}