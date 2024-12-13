var cliente_cod = 0;
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
function close_modal_cl(){
	//alert("xx");
	$("#Modal_v_cl2").hide();
}
function cerrar_tax(){
	$("#Modal_v_tax").hide();
}
function new_tax(x){
	$("#Modal_v_tax").show();
	$("#client").val(x);
	$("#Modal_v_tax").modal({
		show: true,
		backdrop: 'static',
		keyboard: false,
		history: false,
		closer: false
	});
}
function cerrar_tax2(){
	$("#Modal_v_cl2").hide();
	$("#Modal_v_cl2").modal('hide');//ocultamos el modal
	$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
	$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}


		function ver_cliente(cl){
			
				$("#Modal_v_cl").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#carga_data_cl").load("ajax/mod_clientes/profile.php?nik="+cl);
		}
		function update_cliente(cl){
				$("#Modal_v_cl").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#carga_data_cl").load("ajax/mod_clientes/edit.php?nik="+cl);
		}
		function info_cliente(cl,nom){
			cliente_cod = cl;
			$("#Modal_v_cl2").show();
				$("#Modal_v_cl2").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#name_cl").html(nom);
		   $("#btn_tax").html('<button type="button" class="btn btn-lg btn-info btn-label-right" onclick="new_tax('+cliente_cod+');">Cargar New Tax<span><i class="fa fa-plus-square"></i></span></button>');
		  // alert("x1");
		  
		   res_cliente(cl);
		   
		}
		function res_cliente(cliente_cod){
			//var cadena = rand_code(caracteres, 120);
			var cadena = rand_code(caracteres,longitud);
			//alert("x1"+cadena+"-"+cliente_cod);
			$("#tabs-1").load("ajax/mod_clientes/view_card.php?id="+cadena+"&nik="+cliente_cod+"&accion=view");
			$("#tabs-2").load("ajax/mod_clientes/view_res.php?id="+cadena+"&nik="+cliente_cod);
			$("#tabs-3").load("ajax/mod_clientes/view_trans.php?id="+cadena+"&nik="+cliente_cod);
		}
		/*function fact_clientes(cliente_cod){
			var cadena = rand_code(caracteres,longitud);
			$("#tabs-3").load("ajax/mod_clientes/view_trans.php?id="+cadena+"&nik="+cliente_cod);
		}*/
function validar_t(){
	//alert("ddd");
	var tp = $("#tc").val();
	

		if(tp==1){
		$('#fp').children().remove();
		$('#fp').prepend("<option value='V' >V</option>");
		$('#fp').prepend("<option value='E' >E</option>");
		$('#tcliente').children().remove();
		$("#razon").hide();	
		$("#nacimiento").show();
		$("#nombre_l").html("Nombres");
		$('#tcliente').prepend("<option value='111' >XPRES</option>");
		$('#tcliente').prepend("<option value='113' >XPRES CORTESIA</option>");
		$('#tcliente').prepend("<option value='114' >GIFT CARD</option>");
	}if(tp==2){
		$('#fp').children().remove();
		$('#fp').prepend("<option value='J' >J</option>");
		$('#fp').prepend("<option value='G' >G</option>");
		$('#tcliente').children().remove();
		$("#nacimiento").hide();
		$("#datepicker").val('2021-01-01');
		$("#razon").show();	
		$("#nombre_l").html("Persona Contacto");
		$('#tcliente').prepend("<option value='109' >Corporativa</option>");
		$('#tcliente').prepend("<option value='109' >Corporativa Cortesia</option>");
		$('#tcliente').prepend("<option value='114' >GIFT CARD</option>");
	}
	
	
	
	
	
}
		function add_cliente(e){
			//alert(document.getElementById('tcliente').value);
			//alert(document.getElementById('tcliente').value);
			var estado = document.getElementById('tcliente').value;
			console.log("="+estado);
			var pais="";
			var t_tf = $("#telefono").val();
			var t_dir = document.getElementById("direccion").value;
			var t_tc = document.getElementById("tc").value;
			console.log("tipo cliente "+ t_tc);
			var t_fd = $("#fd").val();
			var t_direc = $("#direc").val();
			var t_nombres = $("#nombres").val();
			var t_codigo = $("#codigo").val();
			var t_mail = $("#mail").val();
			var t_lg = $("#fp").val();
			var t_ssn = $("#ssn").val();
			var razon = 0;
			//t_codigo = t_codigo.replace('-', '');
			if(estado==""){
			 $('#mostrar_error_p').parent().addClass('has-error has-feedback');
			// return;
			}
			//var pais = 1;
			//var estado = 2;
			if(t_tc==2){
				if($("#rzs").val()==""){
				 $('#rzs').parent().addClass('has-error has-feedback');
				  $('#rzs').focus();
				 return;	
				}else{
					$('#rzs').parent().removeClass('has-error has-feedback');
					$('#rzs').parent().addClass('has-success has-feedback');
					razon = $("#rzs").val();
					t_fd = "2021-01-01";
				}
			}
			if(t_codigo==""){
			 $('#codigo').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#codigo').parent().addClass('has-success has-feedback');
			}
			if(t_nombres==""){
			 $('#nombres').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#nombres').parent().removeClass('has-error has-feedback');
				 $('#nombres').parent().addClass('has-success has-feedback');
			}
			if(t_ssn=="" || t_ssn.length<4){
			 $('#ssn').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#ssn').parent().removeClass('has-error has-feedback');
				 $('#ssn').parent().addClass('has-success has-feedback');
				 
			}
			if(t_dir==""){
			 $('#direcccion').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#direcccion').parent().removeClass('has-error has-feedback');
				 $('#direcccion').parent().addClass('has-success has-feedback');
				 $('#direcccion').focus();
				 
			}
			
			if(t_tf=="" ||  t_tf.length!=11){
			 $('#telefono').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				  $('#telefono').parent().removeClass('has-error has-feedback');
				 $('#telefono').parent().addClass('has-success has-feedback');
			}
			if(t_mail==""){
			 $('#mail').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#mail').parent().removeClass('has-error has-feedback');
				 $('#mail').parent().addClass('has-success has-feedback');
			}
			if(t_lg==""){
			 $('#fp').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#fp').parent().removeClass('has-error has-feedback');
				 $('#fp').parent().addClass('has-success has-feedback');
			}
		
			
			var t_accion = $("#accion").val();
			var cliente = 0;
			if(t_accion=="update"){
				cliente = $("#idcl").val();
			}else{
				cliente = 0;
			}
			//var idmarca = $("#idmarca").val();
			$.ajax({
			    url : 'ajax/mod_clientes/clientes_script.php',
			    data : { 
					nombre_l: t_nombres,
					t_dir:t_dir,
					t_fd:t_fd,
					numero:t_tf,
					tclie:estado,
					t_codigo:t_codigo,
					t_cl : cliente,
					t_mail : t_mail,
					t_lg : t_lg,
					t_direc:t_direc,
					t_ssn:t_ssn,
					t_tc:t_tc,
					razon:razon,
			    	accion: t_accion
					
			    },	
			    type : 'POST',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#add').attr("disabled", true);
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
					$('#add').attr("disabled", false);
					if(json.response=='ok'){
							messageEmail = new PNotify({
		                        title: 'Cliente Creado',
		                        text: 'Fue registrado el cliente: '+t_nombres,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#Modal_v_cl").modal('hide');
							refresh_list_client();
							 $("#form_edit_cl")[0].reset();
					}else if(json.response=='ex'){
							messageEmail = new PNotify({
		                        title: 'El cliente Existente',
		                        text: 'No se registro el cliente',
		                        type: 'warning',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
					}else if(json.response=='update'){
							messageEmail = new PNotify({
		                        title: 'Localidad Actualizada',
		                        text: 'Fue actualizada correctamente la localidad: '+t_puesto,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							//marca.refresh_list_local();
					}else if(json.response=='no-update'){
							messageEmail = new PNotify({
		                        title: 'Error Actualizando',
		                        text: 'No se actualizo correctamente la localidad: '+t_puesto,
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#form_loca_modal").modal('hide');
							//marca.refresh_list_local();
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al registrar',
		                        text: 'Ocurrio un error registrando al cliente',
		                        type: 'error',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});

							
							$('#fp').removeClass("form-control");
							$('#fp').val(t_lg);
							//$('#fp').addClass("form-control");
								 
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
		function delete_cliente(cl,namec,e){
			swal({
						title: "\xbf Deseas eliminar a "+namec+" ?",   
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
						    url : 'ajax/mod_clientes/clientes_script.php',				
						    data : { 
						    	cli:cl ,
								accion: 'delete',
						    	confirm: 'ok'
						    },
						    type : 'POST',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Eliminando a '+cl+' ',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha eliminado correctamente el cliente: '+namec,
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							refresh_list_client();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Registro no eliminado',
		                        text: 'No se puedo eliminar '+namec,
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
		
	function sear_cl ( e){	
	$("#Modallistacli").modal({
	show: true,
	backdrop: 'static',
	keyboard: false,
	history: false,
	closer: false
		
	});
		}
		
		function cerrar_md1(){
			
			$("#Modallistacli").modal('hide');//ocultamos el modal
			  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  $('.modal-backdrop').remove();//eliminamos el backdrop del modal
			
		}
		
		function buscar_client(e){
			var busqueda = $("#bcliente").val();
				$.ajax({
			    url : 'ajax/mod_clientes/clientes_script.php',
			    data : { 
					busqueda:busqueda,
			    	accion: 'search'
			    },	
			    type : 'POST',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#btn_sc').attr("disabled", true);
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
					$('#btn_sc').attr("disabled", false);
					$("#loader").hide();
					if(json.response=='ok'){
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
							 var category = [];	
							 
							  $.each(json.category, function(d){
                               category.push(json.category[d].nombre);
							     list_event = '<tr>'+
											  '<td>'+j+'</td>'+
											  '<td>'+json.category[d].codigo+'</td>'+
											  '<td>'+json.category[d].nombre+'</td>'+
											  '<td>'+json.category[d].tcliente+'</td>'+
											  '<td><button type="button" onclick="reser_client('+"'"+json.category[d].codigo+"'"+','+json.category[d].idc+')" id="btn_sc" class="btn btn-primary"> <i class="fa fa-play"></i> </button></td>'+
											  '</tr>';
							    j=j+1;
								//$('#carga_cat').prepend(list_event).addClass('product-slider owl-carousel');
								 $('#carga_busqueda').append(list_event);
                            });
					}				
					else {
						messageEmail = new PNotify({
		                        title: 'Error al Buscar',
		                        text: 'Ocurrio un error buscando clientes',
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
		function reser_client(x,y){
			$("#title").val(x);
			$("#idc").val(y);
			$("#Modallistacli").modal('hide');//ocultamos el modal
			  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  $('.modal-backdrop').remove();//eliminamos el backdrop del modal
			
		}

		function add_clientx(){
			
			$("#ModalAdd").modal('hide');//ocultamos el modal
			$("#Modallistacli").modal('hide');//ocultamos el modal
			
			  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
			  $('.modal-backdrop').remove();//eliminamos el backdrop del modal
            $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_clientes/add.php", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
					
		}
		function refresh_list_client(){
			$("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_clientes/index.php", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
		}
function validar_n(){
	$('#telefono').numeric();
	if($("#telefono").val().length<10){
		$('#add').attr("disabled", true);
		$('#telefono').parent().addClass('has-error has-feedback');
		messageEmail = new PNotify({
		          title: 'Teléfono INVALIDO',
		          text: 'Ingrese un numero Valido',
		          type: 'error',
				delay : 4000
		});
	}else if($("#telefono").val().length>=10){
		$('#add').attr("disabled", false);
	    $('#telefono').parent().removeClass('has-error has-feedback');
	}
	 
}	
		
function genera_plantilla(e){	
		   var tienda = $("#tcliente").val();
		   var fechai = $("#direccion").val();

		   
		   if(tienda != "" && fechai != ""){
			   	    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 4000	
                    });
			var url_request = "cliente="+tienda+"&estado="+fechai;
			window.open("ajax/mod_rep/crea_plantilla_ventas.php?tipo=1&"+url_request,'_self'); 
			
			}
		   			
}
		
		function clientes_save_file(id,e){	
			var campos1, campos2, campos3, campos4, campos5, campos6,campos7,campos8,campos9,campos10;
			$("#tabla_ventas tr").each(function (index) 
			{	$(this).css("background-color", "#ECF8E0");
				var campo1, campo2, campo3, campo4, campo5, campo6, campo7,campo8,campo9,campo10;
				
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
						case 7: campo8 = $(this).text();
								break;
						case 8: campo9 = $(this).text();
								break;
						case 9: campo10 = $(this).text();
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
				campos8	= campos8+","+campo8;
				campos9	= campos9+","+campo9;
				campos10= campos10+","+campo10;

			})
			var t_marca = "";
			
			
			var t_logo = $("#excelfile").val();
				$.ajax({
			    url : 'ajax/mod_clientes/clientes_script.php',
			    data : { 
					
					nombre: campos1,
					phone: campos2,
					cumple: campos3,
					direcccion:campos4,
					condado:campos5,
					ssn:campos6,
					mail:campos7,
					plan:campos8,
					lg:campos9,
					t_tc:campos10,
			    	accion: 'addx'
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
					$("#fileupload").attr("disabled", false);
					if(json.response=='ok'){
							messageEmail = new PNotify({
		                        title: 'Clientes Cargados',
		                        text: 'Fue cargado con exito sus clientes: ',
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							if(json.Insert>0){
								$("#sregistro").show();
								$("#csi").html(json.Insert);
							}
							if(json.Error>0){
								$("#nregistro").show();
								$("#cno").html(json.Error);
							}
							
							setTimeout(function(){
								carga_clientes();
							}, 6000);
							
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

		function carga_clientes(){
			 $("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_clientes/?token_id=2122sKSKDSKDADKJAKDSJDKSDKASMUESCSWWSXSXSXSA", function(e){
				 $("#ajax-content").css({
                    "opacity": 1
                });
            });
		}	

function hpagos(a,b,c,d,e){
	   var mes = c;
	   var cli = a;
	   var codigo = d;
		swal({
						title: "\xbf Deseas Validar Pago para "+b+" del mes "+mes+" ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Validar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : 'ajax/mod_periodos/periodo_script.php',				
						    data : { 
						    	periodo:mes,
								accion: 'pagar',
								cl:cli,
								ano:codigo,
						    	confirm: 'ok'
						    },
						    type : 'POST',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Pagando mes '+mes+' ',
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
		                        text: 'Se ha registrado el pago del mes : '+mes,
		                        type: 'success',
								delay : 2500
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							//refresh_list_periodos();
							refresh_list_home()
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

function download_exl (){
	
	
	
	//ajax/mod_clientes/clientes_script.php
	swal({
						title: "\xbf Deseas Descargar todas las tarjetas sin imprimir ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Descargar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
					
					
					$.ajax({
						 type: 'GET',
						 url: 'ajax/mod_clientes/clientes_script.php',
						 xhr: function(){
							xhr = jQuery.ajaxSettings.xhr.apply(this, arguments);
							return xhr;
						},
						 xhrFields: {
							 responseType: 'blob'
						 },
						 data: {
							 ajax: true,
							 accion: 'excel'
						 },
						  beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Generando Archivo',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 2500
							});
							$("#loading_p").show();
	    		    },
						 success: function (json) {
						
							 var blobUrl = URL.createObjectURL(xhr.response);
							var a = document.createElement('a');
							$(a).attr({
								href: blobUrl
								, download: 'qr.xlsx'
							}).text('Click para descargar.');
					 
							document.body.appendChild(a);
							a.click();
						 },
						 error: function() {
							messageEmail = new PNotify({
		                        title: 'Ocurrio un error',
		                        text: 'No se puedo crear el archivo ',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
						 }
					 });
						
						
					});	
}

function download_zip (){
	
	
	
	//ajax/mod_clientes/clientes_script.php
	swal({
						title: "\xbf Deseas Descargar el zip ?",   
						text: "",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Descargar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
					
					
					$.ajax({
						 type: 'GET',
						 url: 'ajax/mod_clientes/clientes_script.php',
						 xhr: function(){
							xhr = jQuery.ajaxSettings.xhr.apply(this, arguments);
							return xhr;
						},
						 xhrFields: {
							 responseType: 'blob'
						 },
						 data: {
							 ajax: true,
							 accion: 'zip'
						 },
						  beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Generando Archivo',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 2500
							});
							$("#loading_p").show();
	    		    },
						 success: function (json) {
						
							 var blobUrl = URL.createObjectURL(xhr.response);
							var a = document.createElement('a');
							$(a).attr({
								href: blobUrl
								, download: 'qr.zip'
							}).text('Click para descargar.');
					 
							document.body.appendChild(a);
							a.click();
						 },
						 error: function() {
							messageEmail = new PNotify({
		                        title: 'Ocurrio un error',
		                        text: 'No se puedo crear el archivo ',
		                        type: 'warning'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
						 }
					 });
						
						
					});	
}