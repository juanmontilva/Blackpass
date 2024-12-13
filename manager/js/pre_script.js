$(document).ready(function(){
	var text_sms = "";
	var cliente_id = "";
	var result_pre = 0;
	(function(pre, $, undefined ) {
		pre.calendarInit = function()
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
		pre.impor_ventas = function(id_marcap, e){	
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
		
		pre.distri_master = function(id_marcap, e){	
		$('#form_dis_master')[0].reset();
		$("#form_distri_presu_master").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		}	


				
		pre.mover_pre = function(e){
			var id_marca = $('#txt_idmarca').val();
							$.ajax({
			    url : 'ajax/mod_pre/pre_script.php',
			    data : { 
					idmarca: id_marca,
			    	accion: 'consulta_pvt'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Consultando informaci\u00f3n',
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
					if(json.response=='ok'){
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
							if(json.factu!="0"){
								$("#error_pre_venta").hide();
								$("#txt_pventa").val(json.factu);
								$("#presu_send").attr("disabled", false);
							}else{
								$("#error_pre_venta").show();
								$("#txt_pventa").val('0');
								$("#mensaje_pre").html("<strong>"+$('#titulo_').val()+"</strong> no tiene ventas para el año: "+year_+ " deseas utilizar el ultimo año con ventas ?")
								$("#presu_send").attr("disabled", true);
							}					
							//$("#form_registro_venta_modal").modal('hide');
							//vent.carga_tiendas(json.marca);
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
							$("#presu_send").attr("disabled", true);
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		}
		
		 $("#txt_tcambio input").click(function(event) {
           pre.calcular();
        });
		pre.calcular =  function(e)
        {
            var valor1=validarNumero('#txt_mckp');
            var valor2=validarNumero('#txt_tcambio');
            
            $("#txt_fact").val(valor1*valor2);
			console.log(valor1*valor2);
        }
        // Funcion para validar que el numero sea correcto, y para cambiar el color
        // del marco en caso de error
        function validarNumero(id)
        {
            if($.isNumeric($(id).val()))
            {
                $(id).css('border-color','#808080');
                return parseFloat($(id).val());
            }else if($(id).val()==""){
                $(id).css('border-color','#808080');
                return 0;
            }else{
                $(id).css('border-color','#f00');
                return 0;
            }
        }
		pre.buscar_presu_v = function(e){
			var year_marca = document.getElementById('sl_year');
			var id_marca = $('#txt_idmarca').val();
			
			var year_ = "";
			if(year_marca.selectedIndex>0){
				year_ = year_marca.options[year_marca.selectedIndex].value;				
			}

				$.ajax({
			    url : 'ajax/mod_pre/pre_script.php',
			    data : { 
					idmarca: id_marca,
					year: year_,
			    	accion: 'consulta_pv'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
			    	messagebefore = new PNotify({
                        title: 'Consultando informaci\u00f3n',
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
					if(json.response=='ok'){
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
							if(json.factu!="0"){
								$("#error_pre_venta").hide();
								$("#txt_pventa").val(json.factu);
								$("#txt_fact").val(json.fact);
								$("#txt_obs").val(json.obs);
								$("#txt_mckp").val(json.mack);
								$("#txt_tcambio").val(json.cambi);
								$("#presu_send").attr("disabled", false);
							}else{
								$("#error_pre_venta").show();
								$("#txt_pventa").val('0');
								$("#mensaje_pre").html("<strong>"+$('#titulo_').val()+"</strong> no tiene ventas para el año: "+year_+ " deseas utilizar el ultimo año con ventas ?")
								$("#presu_send").attr("disabled", true);
							}						
							//$("#form_registro_venta_modal").modal('hide');
							//vent.carga_tiendas(json.marca);
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
							$("#presu_send").attr("disabled", true);
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
		pre.borrar_mes = function(id_marca, marca_t,year,mes,e){
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
							pre.carga_ventas_mes(id_marca);
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
		

		pre.save_presu_c = function(e) {
			var preventa = $("#txt_pventa").val();
			var mck = $("#txt_mckp").val();
			var tcambio = $("#txt_tcambio").val();
			var factor = $("#txt_fact").val();
			var obs = $("#txt_obs").val();
			var marcaid = $("#txt_idmarca").val();
			
			if(mck==""){
			  $("#txt_mckp").focus();
			  $('#txt_mckp').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_mckp').parent().removeClass('has-success has-error');
			}
			if(tcambio==""){
			  $("#txt_tcambio").focus();
			  $('#txt_tcambio').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_tcambio').parent().removeClass('has-success has-error');
			}
			if(factor==""){
			  $("#txt_fact").focus();
			  $('#txt_fact').parent().addClass('has-error has-feedback');
			  //$('#username').parent().removeClass('has-success has-error');
				return;			  
		    }else{
				$('#txt_fact').parent().removeClass('has-success has-error');
			}
				
			var year_a = document.getElementById('sl_year');
			var year_2 = "";
			if(year_a.selectedIndex>0){
				year_2 = year_a.options[year_a.selectedIndex].value;				
			}
		
				$.ajax({
			    url : 'ajax/mod_pre/pre_script.php',
			    data : { 
					idmarca: marcaid,
					pventa: preventa,
					mck: mck,
					cambio:tcambio,
					fact:factor,
					obser:obs,
					year:year_2,
			    	accion: 'add_p'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$("#presu_send").attr("disabled", true);
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
					if(json.presu=='ok'){
							messageEmail = new PNotify({
		                        title: 'Presupuesto Registrado',
		                        text: 'Fue cargado con exito su presupuesto ',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_registro_presu").modal('hide');
							pre.refresh_list_pre();
					}if(json.presu=='up'){
							messageEmail = new PNotify({
		                        title: 'Presupuesto Actualizado',
		                        text: 'Fue actualizado con exito su presupuesto ',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_registro_presu").modal('hide');
							pre.refresh_list_pre();
					}
					document.getElementById("form_pre_regis").reset();
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			

		}
		pre.cerrar_form = function() {
			$("#form_registro_presu").modal('hide');
			$('#form_pre_regis')[0].reset();
			$("#error_pre_venta").hide();

			
		}
		pre.cerrar_form4 = function() {
			$("#form_distri_presu").modal('hide');
			$('#form_dis_regis')[0].reset();
			$("#exc_p").html('Exceso Permitido USD 0 ');
			$("#total_p").html('Total Presupuesto USD 0');
			$("#sl_colec").html('');
			$('#sl_colec').append('<option value="-1" selected="selected">-------------</option>'); 
			$("#sl_mar").html('');
			$('#sl_mar').append('<option value="-1" selected="selected">-------------</option>'); 
		}		

		pre.registra_pre_c = function(id_marcap,nombre_m,years, e){	
		$("#error_pre_venta").hide();
		$("#txt_idmarca").val(id_marcap);
		$('#sl_year > option[value="'+years+'"]').attr('selected', 'selected');
		$('#sl_year').select2();
		pre.buscar_presu_v();
		$("#titulo_div").html(nombre_m);
		$("#titulo_").val(nombre_m);
		$("#presu_send").attr("disabled", true);
		$("#form_registro_presu").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
			
		}
		
		pre.search_temp = function(e){	
		 $("#sl_colec").html('');  
		var idmar = document.getElementById('sl_mar');
			var idmar_2 = "";
			if(idmar.selectedIndex>0){
				idmar_2= idmar.options[idmar.selectedIndex].value;				
			}
			
			var year_marca3 = document.getElementById('sl_year2');

			
			var year_3 = "";
			if(year_marca3.selectedIndex>0){
				year_3 = year_marca3.options[year_marca3.selectedIndex].value;				
			}

			
				$.ajax({
			    url : 'ajax/mod_pre/pre_script.php',
			    data : { 
					idmar : idmar_2,
					year: year_3,
			    	accion: 'temp'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,		
			    success : function(json) {
					$("#sl_colec").find("option[value='-1']").remove();
					if(json.tmp=='ok'){
							var tmp = json.tempo.split(",");
							for(var l1=0;l1<tmp.length-1;l1++){	
								var tmp_ = tmp[l1].split("-");							
								$('#sl_colec').append('<option value="'+tmp_[0]+'" >'+tmp_[1]+'</option>'); 
							
							}					
						$("#txt_pcom").val(json.pre);
						$("#sl_colec").select2();
						$("#txt_idpre").val(json.idpre);
						pre_calcular_presupuesto()
						
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		}
		
		pre_calcular_presupuesto = function(e){
			
			var pres_asig = parseFloat($("#txt_pcom").val());		
			var asig = $("#preasig").val();
			asig = asig.split("%");
			var asig_n = parseInt(asig[1]);
			if(asig.length==1){
				if($("#preasig").val()!=""){
					asig_n = parseInt($("#preasig").val());
				}else{
					asig_n  = 15;
				}
				
				
			}
			result_pre = (pres_asig*asig_n)/100;
			result_pre = parseFloat(result_pre);
			$("#txt_asig").val(result_pre.toFixed(2));
			var asig4 = $("#exc_p").html();
			var contar = asig4.length;
			if(contar>22){
				asig4 =asig4.slice(22,contar); 
				if(asig4==0 || asig4=="" || asig4===""){
					asig4	 = 0;
				}
				var asig_n4 = parseFloat(asig4);
			}else{
				var asig_n4 = 0;
			}

			if(asig_n4>0){
				result_pre = result_pre+asig_n4;
			}
			$("#total_p").html("Total Presupuesto USD "+result_pre.toFixed(2));
			
			pre_validar_presupuesto_ex();
		}
		
		pre_validar_presupuesto_ex = function(e){
		
			var pres_asig2 = parseFloat($("#txt_pcom").val());
			var asig5 = $("#total_p").html();
			var contar2 = asig5.length;
			asig5 =asig5.slice(22,contar2); 
			var validar_ex = pres_asig2-asig5;
			if(pres_asig2<asig5){
				$("#distri_send").attr("disabled", true);
				$("#error_pre_exceso").show();
				$("#mensaje_pre_exce").html("Esta seguro de asignar un presupuesto mayor al total disponible? Quedara con un presupuesto negativo de USD "+validar_ex.toFixed(2));
			}else{
				$("#error_pre_exceso").hide();
				pre.activar_exce();
				console.log("1");
			}

		$("#total_presu_r").val (validar_ex.toFixed(2));
			
		}
		
		pre.activar_exce  = function(e){
			if ($('#sl_colec').val().trim() === '') {
				$("#distri_send").attr("disabled", true);
			} else {
				$("#distri_send").attr("disabled", false);
				$("#error_pre_exceso").hide();
			}


		}
		
		pre_calcular_presupuesto_ex = function(e){
			var pres_asig2 = parseFloat($("#txt_pcom").val());
			var asig2 = $("#preasig").val();
			asig2 = asig2.split("%");
			var asig_n2 = parseInt(asig2[1]);
			
			var asig3 = $("#excesig").val();
			asig3 = asig3.split("%");
			var asig_n3 = parseInt(asig3[1]);
			asig_n2 = asig_n2+asig_n3;
			var result_pre2 = (pres_asig2*asig_n2)/100;
			result_pre2 = parseFloat(result_pre2).toFixed(2);
			var yasif = parseFloat($("#txt_asig").val());
			result_pre2 = result_pre2-yasif
			//result_pre2 = parseFloat(result_pre2).toFixed(2);
			var result_pre3 =  yasif+result_pre2;
			$("#exc_p").html(" Exceso Permitido USD "+result_pre2.toFixed(2)).addClass('success');
			
			console.log("dd="+result_pre3);
			$("#total_p").html('');
			$("#total_p").html("Total Presupuesto USD "+result_pre3); 
			pre_validar_presupuesto_ex();
		}
		
		pre.save_d_tmp = function(e){	
			var idmarc = document.getElementById('sl_mar');
			var idmar_22 = "";
			if(idmarc.selectedIndex>0){
				idmar_22= idmarc.options[idmarc.selectedIndex].value;				
			}
			
			var year_4 = document.getElementById('sl_year2');
			var year_33 = "";
			if(year_4.selectedIndex>0){
				year_33 = year_4.options[year_4.selectedIndex].value;				
			}
			
			console.log("colec="+colec3);
			var presu = $("#txt_asig").val();
			var idpresu = $("#txt_idpre").val();
			var exceso = $("#exc_p").html();
			var exceso4 = 0;
			var contar3 = exceso.length;
			if(contar3>22){
				exceso =exceso.slice(22,contar3); 
				if(exceso==0 || exceso=="" || exceso===""){
					exceso	 = 0;
				}
				exceso4 = parseFloat(exceso);
			}else{
				exceso4 = 0;
			}
			
			var colec3 = $('select[id=sl_colec]').val();
			
				$.ajax({
			    url : 'ajax/mod_pre/pre_script.php',
			    data : { 
					year : year_33,
					marca: idmar_22,
					colec: colec3,
					exceso:exceso4,
					pres:presu,
					idpre: idpresu,
			    	accion: 'save_d_t'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					
			    	messagebefore = new PNotify({
                        title: 'Enviando informaci\u00f3n',
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
					if(json.response=='ok'){
						messagebefore = new PNotify({
                        title: 'Presupuesto asignado',
                        text: 'Se asigno el presupuesto a la temporada...',
                        type: 'success',
						delay : 0
						
                    });	
					pre.cerrar_form4();
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		
		
		
		
		$("#distri_send").attr("disabled", true);
		$("#form_distri_presu").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		
		}
		
		
		pre.new_pred = function(years, e){	
		$('#sl_year2 > option[value="'+years+'"]').attr('selected', 'selected');
		$('#sl_year2').select2();
		
				$.ajax({
			    url : 'ajax/mod_pre/pre_script.php',
			    data : { 
					year : years,
			    	accion: 'marcas'
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					
			    	messagebefore = new PNotify({
                        title: 'Cargando informaci\u00f3n',
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
					if(json.presu=='ok'){
							var marcas = json.marca.split(",");
							for(var l=0;l<marcas.length-1;l++){
								var marca_ = marcas[l].split("-");
				
								$('#sl_mar').append('<option value="'+marca_[0]+'" selected="selected">'+marca_[1]+'</option>'); 
							}
								$("#sl_mar").select2();
						
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
		
		
		
		
		$("#distri_send").attr("disabled", true);
		$("#form_distri_presu").modal({
				show: true,
				backdrop: 'static',
				keyboard: false,
				history: false,
				closer: false
		
			});
		
		}
		

			
		pre.refresh_list_pre = function(){
            $("#ajax-content").css({
                "opacity": 0.4
            });
		$("#ajax-content").load("ajax/mod_pre/add_pre.php", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
			
        }
		





		}( window.pre = window.pre|| {}, jQuery ));
});
