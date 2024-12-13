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
var libre = 0;
function validar_camp2(y){
	//alert($("#"+y).val());
	if($("#"+y).val()==""){
		$("#"+y).parent().addClass('has-error has-feedback');
	}else{
		$("#"+y).parent().removeClass('has-error has-feedback');
		$("#"+y).parent().addClass('has-success has-feedback');
		libre = libre+1;
		console.log("libre = "+libre);
	}
	if(libre>=4){
		$( ".selector" ).tabs( "enable", "#tabs-3" );
	}
	
}

		function new_emple(){
			var cadena2 = rand_code(caracteres, 120);
				$("#Modal_v_ep").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
		   $("#carga_data_ep").load("ajax/mod_empleados/add.php?nik="+cadena2);
		}
		function permiso_emple(cl){
			var cadena2 = rand_code(caracteres, 120);
				$("#Modal_p_ep").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
			$("#emple_p").val(cl);
		}
		
		function update_emple(cl){
				$("#Modal_v_ep").modal({
						show: true,
						backdrop: 'static',
						keyboard: false,
						history: false,
						closer: false
				});
			
		   $("#carga_data_ep").load("ajax/mod_empleados/edit.php?nik="+cl);

		   	setTimeout(function(){ 
				$("#ide").val(cl);
				var id_c = $("#s2_city").val();
				localidades_emp(id_c);
				 $("#sl_tiendas option").each(function(){
						 
						var v3 = $(this).val();
						console.log(v3);
							if (v3 == id_c){
								$("#sl_tiendas option[value="+v3+"]").attr("selected",true);
							}
					 }); 
				$("#sl_tiendas").select2();
			}, 1000);
		}
		function add_per_emple(e){
			var t_fi = $("#fecha_ip").val();
			var t_ff = $("#fecha_fp").val();
			var cl = $("#emple_p").val();
				if($('#fecha_ip').val() ==""){
					 $('#fecha_ip').parent().addClass('has-error has-feedback');
					 messagebefore = new PNotify({
								title: 'ERROR',
								text: 'Por favor, selecciona Fecha Inicial',
								type: 'error',
								delay : 0
								});
					  return;
					}else{
						$('#fecha_ip').parent().removeClass('has-error has-feedback');
						$('#fecha_ip').parent().addClass('has-success has-feedback');
				}
				if($('#fecha_fp').val() ==""){
					 $('#fecha_fp').parent().addClass('has-error has-feedback');
					 messagebefore = new PNotify({
								title: 'ERROR',
								text: 'Por favor, selecciona Fecha Final',
								type: 'error',
								delay : 0
								});
					  return;
					}else{
						$('#fecha_fp').parent().removeClass('has-error has-feedback');
						$('#fecha_fp').parent().addClass('has-success has-feedback');
				}
				$.ajax({
						    url : 'ajax/mod_empleados/emple_script.php',				
						    data : { 
						    	cli:cl ,
								ff:t_ff,
								fi:t_fi,
								accion: 'pause'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							messageEmail = new PNotify({
	                        title: 'Agregando Permiso',
	                        text: 'Por favor espere.',
	                        type: 'info',
							delay : 4000
	                    });
	    		    },
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	messageEmail = new PNotify({
		                        title: 'Éxito',
		                        text: 'Se ha registrado con el exito el permsio ',
		                        type: 'success'
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#Modal_p_ep").modal('hide');
							refresh_list_emple();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){

        		    		messageEmail = new PNotify({
		                        title: 'Registro no completado',
		                        text: 'No se puedo agregar el permiso ',
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

			}
		function add_emple(e){
			//alert("xxx");
			var ciudad = document.getElementById('s2_city').value;
			var tienda = document.getElementById('sl_tiendas').value;
			//console.log("="+estado);
			var pais="";
			var t_tf = $("#telefono").val();
			var t_dir = $('input:text[name=direccion]').val();
			var t_fd = $("#fd").val();
			var t_nombres = $("#nombres").val();
			var t_codigo = $("#codigo").val();
			t_codigo = t_codigo.replace('-', '');
			if($('#s2_city').val()==""){
			 $('#s2_city').parent().addClass('has-error has-feedback');
			 			messagebefore = new PNotify({
                        title: 'ERROR',
                        text: 'Por favor, selecciona ciudad...',
                        type: 'error',
						delay : 0
						});
				return;
			}else{
				 $('#s2_city').parent().removeClass('has-error has-feedback');
				  $('#s2_city').parent().addClass('has-success has-feedback');
			}
			if($('#sl_tiendas').val() ==""){
			 $('#sl_tiendas').parent().addClass('has-error has-feedback');
			 messagebefore = new PNotify({
                        title: 'ERROR',
                        text: 'Por favor, selecciona Tienda',
                        type: 'error',
						delay : 0
						});
			  return;
			}else{
				$('#sl_tiendas').parent().removeClass('has-error has-feedback');
				$('#sl_tiendas').parent().addClass('has-success has-feedback');
			}
			//var pais = 1;
			//var estado = 2;
			var t_puesto = $("#puesto").val();
			if(t_codigo==""){
			 $('#codigo').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				$('#codigo').parent().removeClass('has-error has-feedback');
				$('#codigo').parent().addClass('has-success has-feedback');
			}
			if(t_nombres==""){
			 $('#nombres').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#nombres').parent().addClass('has-success has-feedback');
			}
			if(t_tf==""){
			 $('#telefono').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#telefono').parent().addClass('has-success has-feedback');
			}
			var selected = '';    
			$('#registro_e input[type=checkbox]').each(function(){
				if (this.checked) {
					selected += $(this).val()+', ';
				}
			}); 

			if (selected == '') {
				alert('Debes seleccionar al menos una opción.');
				return false;
			}
			var t_accion = $("#accion").val();
			var ide = $("#ide").val();
			$.ajax({
			    url : 'ajax/mod_empleados/emple_script.php',
			    data : { 
				   t_codigo:t_codigo,
					nombre_l: t_nombres,
					t_dir:t_dir,
					t_fd:t_fd,
					numero:t_tf,
					t_local:$('#sl_tiendas').val(),
 					servi:selected,
					t_ide : ide,
			    	accion: t_accion
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
					$('#add').attr("disabled", true);
			    	messagebefore = new PNotify({
                        title: 'Procesando informaci\u00f3n',
                        text: 'Por favor, espere...',
                        type: 'info',
						delay : 5
						
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
		                        title: 'Empleado Creado',
		                        text: 'Fue registrado el empleado: '+t_nombres,
		                        type: 'success',
								delay : 4000
		                    });
		                    $(e).css({
								'color':'#337ab7', 
								'pointer-events': 'auto',
				   				'cursor': 'pointer'
							});
							$("#Modal_v_ep").modal('hide');
							refresh_list_emple();
							 $("#registro_c")[0].reset();
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
							$("#Modal_v_ep").modal('hide');
							refresh_list_emple();
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
							$("#Modal_v_ep").modal('hide');
							refresh_list_emple();
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
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
		function delete_emple(cl,namec,e){
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
						    url : 'ajax/mod_empleados/emple_script.php',				
						    data : { 
						    	cli:cl ,
								accion: 'delete',
						    	confirm: 'ok'
						    },
						    type : 'GET',
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
							refresh_list_emple();
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
		



		function refresh_list_emple(){
			$("#ajax-content").css({
                "opacity": 0.4
            });
			$("#ajax-content").load("ajax/mod_empleados/index.php", function(e){
						 $("#ajax-content").css({
							"opacity": 1
						});
					});
		}
function validar_ne(){
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
		libre = libre + 1
	    $('#telefono').parent().removeClass('has-error has-feedback');
		$('#telefono').parent().addClass('has-success has-feedback');
		if(libre>=4){
			$( ".selector" ).tabs( "enable", "#tabs-3" );
		}
	}
	 
}	
		
		function localidades_emp(id_pais, e){
		var combo = document.getElementById('s2_city');
		if(combo.selectedIndex>0){
			//alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);	
			var formData = 'id_category='+combo.options[combo.selectedIndex].value;
			$.ajax({
			    url : 'ajax/mod_localidad/localidad_tienda.php',
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
                    $("#sl_tiendas").html(datos);
                },				
				error: function(error){
			    	console.log(error);
			    }
			});
			}
		}