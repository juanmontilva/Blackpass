	var marcas_graf_ventas = "";
	var marcas_graf_ventas2 = "";
		var data_1 = "";
		var data_2 = "";
		var data_3 = "";
		var data_4 = "";
		var data_5 = "";
		var c_dias = "";
		var datos_f = "";

function number_format(amount, decimals) {

    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');

    return amount_parts.join(',');
}

function data_home(){
		$.ajax({
			   //url : '/dshopretail/datos.php',
				url : 'ajax/mod_home/home_script.php',
			    data : { 
			    	accion: 'group_data'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#loading_home4").show();
			    	messagebefore = new PNotify({
                        title: 'Consultando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
			    },		
			    success : function(json) {
					$("#loading_home4").hide();
					if(json.response=='ok'){
							$("#total_c").html(json.total);	
							$("#anul_c").html(json.anu);
							$("#conc_c").html(json.paga);
							$("#hoy_c").html(json.hoy);
							$("#m_c").html(json.mc+ " USD");
							$("#m_p").html(json.mp+ " USD");
							$("#m_e").html(json.mt+ " USD");
												
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
    
}

setInterval(refresh_list_home, 500000);
setInterval(data_home, 500000);

function refresh_list_home(){
	
	
	$("#ajax-content").css({
                "opacity": 0.4
     });
	$("#ajax-content").load("ajax/dashboard.php", function(e){
		 $("#ajax-content").css({
			"opacity": 1
		});
	});
	$('.modal').removeClass('show');
	$('body').removeClass('modal-open');
	$('.modal-backdrop').remove();
}



function refresh_list_home2(){
	$('.modal').removeClass('show');
	$('body').removeClass('modal-open');
	$('.modal-backdrop').remove();
	$('#ModalEdit').modal('hide')
	$("#ajax-content").css({
                "opacity": 0.4
     });
	$("#ajax-content").load("ajax/mod_reservas/index.php", function(e){
		 $("#ajax-content").css({
			"opacity": 1
		});
	});
}

function cargar_info_r(x,y){
	$('.modal').removeClass('show');
	$('body').removeClass('modal-open');
	$('.modal-backdrop').remove();
		messageEmail = new PNotify({
	        title: 'Procesando ',
	        text: 'Por favor espere.',
	        type: 'info',
			delay : 200
	    });
		
	$("#ajax-content").css({
                "opacity": 0.4
     });
	$("#ajax-content").load("ajax/dashboard.php?id="+x+"&dat="+y, function(e){
		 $("#ajax-content").css({
			"opacity": 1
		});
	});
	messageEmail = new PNotify({
	        title: 'Datos Procesados',
	        text: 'Se cargo los datos de las Transacciones.',
	        type: 'success',
			delay : 4000
	    });
}





function update_home_r(){
			$.ajax({
			   //url : '/dshopretail/datos.php',
				url : 'ajax/mod_home/update_reservas.php',
			    data : { 
			    	accion: 'update'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#loading_home4").show();
			    	messagebefore = new PNotify({
                        title: 'Consultando informaci\u00f3n Citas',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 0
						
                    });
			    },		
			    success : function(json) {
					$("#loading_home4").hide();
					if(json.response=='ok'){
						messagebefore = new PNotify({
                        title: 'Citas Actualizadas',
                        text: 'Por favor, espere...',
                        type: 'success',
						delay : 0
						
                    });	
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
}

function payment_cl(e){
			var cl = $("#tdcv").val();
			var monto = $("#monto").val();
			var client =  $("#ncliente").val();
			var orden =  $("#ordenid").val();
			var saldo =  $("#txtsaldo").val();
			var ubi =    $("#ubic").val();
			var descrip =   $('textarea#descrip').val();
			monto = parseInt(monto);
			if($("#tdcv").val()=="" || $("#tdcv").val().length<16){
			  $("#btn_self").prop('disabled', true);
			  $("#tdcv").focus();
			  $('#tdcv').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#tdcv').parent().removeClass('has-success has-error');
			}
			if(monto=="" || monto==0 || monto ==null){
			  $("#monto").focus();
			  $('#monto').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#monto').parent().removeClass('has-success has-error');
			}if(client=="" || client==0 || client ==null){
			  $("#tdcv").focus();
			  $('#tdcv').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#tdcv').parent().removeClass('has-success has-error');
			}
			if(saldo<monto){
				$("#div_msg2").show();
				$("#btn_self").prop('disabled', true);
				return;
			}
						swal({
						title: "\xbf Cobrar a la tarjeta "+cl+"?",   
						text: "La cantidad de "+monto+" USD",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Cobrar!",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_pagos/pagos_script.php',				
						    data : { 
						    	sl : monto,
								idc :client,
								ord : orden,
								descr : descrip,
								sald:saldo,
								lg:ubi,
								accion: 'pays',
								
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Procesando Cobro'+cl+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Transaccion Aprobada: ',
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
							refresh_list_home();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '0'){

        		    		messageEmail = new PNotify({
		                        title: 'Transaccion Declinada',
		                        text: 'No se puedo cobrar',
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
