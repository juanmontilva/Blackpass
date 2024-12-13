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
longitud = 256;

function validar_s(x){
	$.ajax({
                    url : '../script/user_script.php',
                    data : { 
                        // data client
                        tuser: x,
                        xyz:'vaudi'  
                    },
                    type : 'GET',
                    dataType : 'json',
					beforeSend : function(){
						$("#load_name").show();
						$("#load_promo").show();
                    },
                    success : function(json) {
                        console.log(json);
                        if (json.response == 'ok') {
                            sesiones = json.token_aleatorio;
                            // alert(sesiones);
                             valid_login = true;    
                              sessionStorage.setItem("usuario",json.us);
							  sessionStorage.setItem("uid",json.device);
							  sessionStorage.setItem("token",json.token);
							  sessionStorage.setItem("pais",json.pais);
                              $("#load_name").hide();
							 $("#load_promo").hide();
							 $("#promo").show();
							 $("#name_label").html(json.us);
                        }else{
							window.location.href = "sign-in.html"
							//waitingDialog.show('Error en Datos...', {dialogSize: 'sm', progressType: 'red'});setTimeout(function () {waitingDialog.hide();}, 1000);
                         //waitingDialog.show('Error en sus datos...', {dialogSize: 'm', progressType: 'red'});setTimeout(function () {waitingDialog.hide();}, 2000);
                        }
                    },
                   /* error : function(jqXHR, status, error) {
                       // console.log(error);
                    }*/
                }); 
}
var sesiones = "";
            function validar_acceso(x)
            {   
                signIn(x);             
            }
             function signIn(y){
            // SIGNIN SERVER CALL CODE GOES HERE
			if($("#puser").val()!="" && $("#passw").val()!=""){
				$("#puser").removeClass();
				$("#passw").removeClass();
				$("#puser").addClass("form-control is-valid");
				$("#passw").addClass("form-control is-valid");
				var clave =  "h24."+$("#passw").val()+"."+rand_code(caracteres,longitud);
               $.ajax({
                    url : '../script/user_script.php',
                    data : { 
                        // data client
						tuser: y,
                        tuser: $("#puser").val(),
						token:rand_code(caracteres,longitud),
                        tmp: clave,
                        xyz:'validar'  
                    },
                    type : 'GET',
                    dataType : 'json',
					beforeSend : function(){
						$("#btnsign").prop("disabled", true);
								// add spinner to button
						$("#btnsign").html(
									'<i class="spinner-border fa fa-spin"></i> Validando Información'
						);
                    },
                    success : function(json) {
                        console.log(json);
                        if (json.response == 'ok') {
                            sesiones = json.token_aleatorio;
                            // alert(sesiones);
                             valid_login = true;    
                              sessionStorage.setItem("usuario",sesiones);
							  sessionStorage.setItem("uid",json.us);
							  sessionStorage.setItem("perfi",json.perfi);
                              //$('#loguear').show();
							   window.location.href = "home.php"
							}else{
							$("#btnsign").prop("disabled", false);
									// add spinner to button
							$("#btnsign").html(
										'Ingresar'
							);
                        }
                    },
                   /* error : function(jqXHR, status, error) {
                       // console.log(error);
                    }*/
                }); 
           }else if($("#puser").val()!=""){
			   
			 $("#puser").addClass("form-control is-invalid");
			  $("#puser").focus();
		   }else if($("#passw").val()!=""){
			   
			  $("#passw").focus();
			  $("#passw").addClass("form-control is-invalid");
		   }
		}
		
function start_r(x,y){
	$("#form_cambio_modal_add").show();
	$("#ncliente").val(x);
	$("#Modal_v_tax").modal({
		show: true,
		backdrop: 'static',
		keyboard: false,
		history: false,
		closer: false
	});
}
function passw(){
	$("#form_passw_modal_add").show();
	$("#form_passw_modal_add").modal({
		show: true,
		backdrop: 'static',
		keyboard: false,
		history: false,
		closer: false
	});
}
function start_t(x,y){
	$("#form_tf_modal_add").show();
	$("#ncliente").val(x);
	$("#Modal_v_tax").modal({
		show: true,
		backdrop: 'static',
		keyboard: false,
		history: false,
		closer: false
	});
}

function tipo_pago(){
		 
		 var selec = $("#tpc").val();
		 if(selec == 1 || selec == 3 || selec == 6){
			 $("#refere").show();
			 $("#bancos").show();
			 $("#nombre_t").show();
			 $("#cash").hide();
		 }else if(selec == 2){
			 $("#refere").hide();
			 $("#bancos").hide();
			 $("#nombre_t").hide();
			 $("#cash").show();
		 }else if(selec == 4){
			 $("#refere").hide();
			 $("#bancos").hide();
			 $("#nombre_t").hide();
			 $("#cash").hide();
		 }
		 
	 }
function validar_forma(){
	var envio = $("#envio").val();
	if(envio == 2){
		$("#senvio").val();
	}
}
function cerrar_tax2(){
	$("#form_cambio_modal_add").hide();
	$("#form_cambio_modal_add").modal('hide');//ocultamos el modal
	$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
	$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}
function cerrar_tax3(){
	$("#form_tf_modal_add").hide();
	$('#form_tf_cambio')[0].reset();
	$("#form_tf_modal_add").modal('hide');//ocultamos el modal
	$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
	$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}
function cerrar_tax4(){
	$("#form_passw_modal_add").hide();
	$('#form_pass_cambio')[0].reset();
	$("#form_passw_modal_add").modal('hide');//ocultamos el modal
	$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
	$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}
function cerrar_tax5(){
	$("#form_reload_modal_add").hide();
	$('#form_rel_cambio')[0].reset();
	$("#form_reload_modal_add").modal('hide');//ocultamos el modal
	$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
	$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}
function cerrar_tax6(){
	$("#form_emp_modal_add").hide();
	$('#form_emp_cambio')[0].reset();
	$("#form_emp_modal_add").modal('hide');//ocultamos el modal
	$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
	$('.modal-backdrop').remove();//eliminamos el backdrop del modal
}


		function save_pago_res(e){
			var namec = $("#tarjeta").val();
			var idc = $("#ncliente").val();
			var txtbanco2 = $("#banco").val();
			var cliente2 = document.getElementById("nombre_c").value;
			var tokenc = document.getElementById("client").value;
			var pick = document.getElementById("envio").value;
			var txtref = $("#refe").val();
			var txtmonto = $("#montos").val();
			var tipo = document.getElementById("tpc").value;
			var envio = 0;
			var direccion_e = telefono = contacto = hora = "";
			envio = $("#envio").val();
			if(tipo == 1 || tipo ==3 || tipo ==6){
					if(txtbanco2==""){
					   $('#banco').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#banco').parent().removeClass('has-error has-feedback');
						$('#banco').parent().addClass('has-success has-feedback');
					}
					if(cliente2==""){
					  $('#nombre_c').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#nombre_c').parent().removeClass('has-error has-feedback');
						$('#nombre_c').parent().addClass('has-success has-feedback');
					}
					if(txtref==""){
					  $('#refe').parent().addClass('has-error has-feedback');
						return;			  
					}else{
						$('#refe').parent().removeClass('has-error has-feedback');
						$('#refe').parent().addClass('has-success has-feedback');
					}
			}else{
				if(envio==2){
					direccion_e = $("#dir_rec").val();
					if(direccion_e==""){
					  $('#dir_rec').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#dir_rec').parent().removeClass('has-error has-feedback');
						$('#dir_rec').parent().addClass('has-success has-feedback');
					}
					contacto = $("#contacto").val();
					if(contacto==""){
					  $('#contacto').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#contacto').parent().removeClass('has-error has-feedback');
						$('#contacto').parent().addClass('has-success has-feedback');
					}
					telefono = $("#phone").val();
					if(telefono==""){
					  $('#phone').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#phone').parent().removeClass('has-error has-feedback');
						$('#phone').parent().addClass('has-success has-feedback');
					}
					hora = $("#hora").val();
					if(hora==""){
					  $('#hora').parent().addClass('has-error has-feedback');
					return;			  
					}else{
						$('#hora').parent().removeClass('has-error has-feedback');
						$('#hora').parent().addClass('has-success has-feedback');
					}
				}
			}
			if(txtmonto==""){
			  $('#montos').parent().addClass('has-error has-feedback');
				return;			  
		    }else{
				$('#montos').parent().removeClass('has-error has-feedback');
				$('#montos').parent().addClass('has-success has-feedback');
			}
			
			swal({
						title: "\xbf Deseas Recargar a "+namec+"?",   
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
						    url : '../script/user_script.php',				
						    data : { 
						    	namec : namec,
								idc :idc,
								txtbanco : txtbanco2,
								txttitular : cliente2,
								txtref :txtref,
								txtmonto : txtmonto,
								metodo : tipo,
								tokenc:tokenc,
								pick:pick,
								phone:telefono,
								contacto:contacto,
								direc2:direccion_e,
								hora:hora,
								
								xyz: 'addr',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
								$("#load_r").show();
							},
	    		    success : function(json) {
	    		    	if (json.response == 'ok') {
	        		    	$("#load_r").hide();
							$("#form_cambio_modal_add").hide();
							$("#form_cambio_modal_add").modal('hide');//ocultamos el modal
							$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							refresh_list_pagos();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error</div></div></div>");
        		    		
        		    	}	
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});	
		}
		function save_pass_res(e){
			var namec = $("#tarjeta").val();
			var t_mail = $("#correo").val();
			if(namec=="" ||  namec.length<16){
				$("#tarjeta").parent().addClass('has-error has-feedback');
				$("#tarjeta").focus();
				return;
			}else{
				$('#tarjeta').parent().removeClass('has-success has-error');
			}
			if(t_mail==""){
				$('#correo').focus();
			 $('#correo').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				  if($("#correo").val().indexOf('@', 0) == -1 || $("#correo").val().indexOf('.', 0) == -1) {
					$('#correo').focus();
					$('#correo').parent().addClass('has-error has-feedback');
					return false;
				  }else{
					  $('#correo').parent().addClass('has-success has-feedback');
				  }
				 
			}
 
				$.ajax({
						    url : '../script/user_script.php',				
						    data : { 
						    	namec : namec,
								token:rand_code(caracteres,longitud),
								txtmail : t_mail,
								xyz: 'pass'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
								$("#load_r").show();
							},
	    		    success : function(json) {
						$("#load_r").hide();
	    		    	if (json.response == 'ok') {
	        		    	$("#load_r").hide();
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-success'>Contraseña restablecida con exíto, Revise su correo</div></div></div>");
        		    		cerrar_tax4();
	    		    	}else if(json.response == '2'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error</div></div></div>");
        		    		
        		    	}	
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
				
		}
		
		function save_tf_res(e){
			var namec = $("#tarjeta").val();
			var idc = $("#ncliente").val();
			var txtbanco2 = $("#banco").val();
			var cliente2 = document.getElementById("nombre_c").value;
			var tokenc = document.getElementById("client").value;
			var pick = document.getElementById("envio").value;
			var txtref = $("#refe").val();
			var txtmonto = $("#montos").val();
			var tipo = document.getElementById("tpc").value;
			if(namec=="" ||  namec.length<16){
				$("#tarjeta").parent().addClass('has-error has-feedback');
				$("#tarjeta").focus();
				return;
			}else{
				$('#tarjeta').parent().removeClass('has-success has-error');
			}
			if(tipo==""){
				$("#tpc").parent().addClass('has-error has-feedback');
				$("#tpc").focus();
				return;
			}else{
				$('#tpc').parent().removeClass('has-success has-error');
			}if(tipo == 1 || tipo ==3){
					if(txtbanco2==""){
					   $('#banco').parent().addClass('has-error has-feedback');
					    $('#banco').focus();
					return;			  
					}else{
						$('#banco').parent().removeClass('has-success has-error');
					}
					if(cliente2==""){
					  $('#nombre_c').parent().addClass('has-error has-feedback');
					   $('#nombre_c').focus();
					return;			  
					}else{
						$('#nombre_c').parent().removeClass('has-success has-error');
					}
					if(txtref==""){
					  $('#refe').parent().addClass('has-error has-feedback');
						$('#refe').focus();
						return;			  
					}else{
						$('#refe').parent().removeClass('has-success has-error');
					}
			}	
			if(txtmonto==""){
			  $('#montos').parent().addClass('has-error has-feedback');
			  $('#montos').focus();
				return;			  
		    }else{
				$('#montos').parent().removeClass('has-success has-error');
			}
			
			swal({
						title: "\xbf Deseas Transferir Saldo a: "+namec+"?",   
						text: "Monto "+txtmonto+" USD",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Enviar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : '../script/user_script.php',				
						    data : { 
						    	namec : namec,
								idc :idc,
								txtbanco : txtbanco2,
								txttitular : cliente2,
								txtref :txtref,
								txtmonto : txtmonto,
								metodo : tipo,
								tokenc:tokenc,
								pick:pick,
								xyz: 'trf',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
								$("#load_r").show();
								$("#btn_save2").prop("disabled", true);
							},
	    		    success : function(json) {
						$("#load_r").hide();
						$("#btn_save2").prop("disabled", false);
						 
	    		    	if (json.response == 'ok') {
							$('#form_tf_cambio')[0].reset();
	        		    	$("#load_r").hide();
							$("#form_cambio_modal_add").hide();
							$("#form_cambio_modal_add").modal('hide');//ocultamos el modal
							$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							refresh_list_tf();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error</div></div></div>");
        		    		
        		    	}else if(json.response == '4'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>SALDO INSUFICIENTE</div></div></div>");
        		    		
        		    	}else if(json.response == '5'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>DESTINATARIO NO EXISTE</div></div></div>");
        		    		
        		    	}else if(json.response == '8'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>NO PUEDES TRANSFERIR A LA MISMA CUENTA</div></div></div>");
        		    		
        		    	}else{
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error Inesperado</div></div></div>");
        		    		
        		    	}				
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});	
		}
function reloader(x){
    consulta_t(x);
	$("#form_reload_modal_add").show();
	$("#ncliente").val(x);
	$("#form_reload_modal_add").modal({
		show: true,
		backdrop: 'static',
		keyboard: false,
		history: false,
		closer: false
	});
}
function new_p(x){

	$("#form_emp_modal_add").show();
	$("#emp").val(x);
	$("#form_emp_modal_add").modal({
		show: true,
		backdrop: 'static',
		keyboard: false,
		history: false,
		closer: false
	});
}

function consulta_t(y){
							$.ajax({
						    url : '../script/user_script.php',				
						    data : { 
						    	namec : y,
								xyz: 'trv',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
								$("#sping").show();
								$("#sping").html('<i class="spinner-border fa fa-spin"></i>');
								$("#btn_save").prop("disabled", true);
							},
	    		    success : function(json) {
						$("#sping").hide();
						$("#btn_save").prop("disabled", false);
						 
	    		    	if (json.response == 'ok') {
							$("#tarjeta").val("XXXX-XXXX-XXXX"+json.card);
	    		    	}else{
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error Inesperado</div></div></div>");
        		    		
        		    	}				
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
}
function save_pago_reloader(e){
			var txtmonto = $("#montos").val();
			var txtcl = $("#ncliente").val();
			var txtcard = $("#tarjeta").val();
			
			if(txtmonto==""){
			  $('#montos').parent().addClass('has-error has-feedback');
			  $('#montos').focus();
				return;			  
		    }else{
				$('#montos').parent().removeClass('has-success has-error');
			}
			
			swal({
						title: "\xbf Vas a Transferir a la Tarjeta Culminada: "+txtcard+"?",   
						text: "Monto "+txtmonto+" USD",   
						type: "warning",  
						showCancelButton: true,   
						cancelButtonText: "Cancelar",
						confirmButtonColor: "#008C23",   
						confirmButtonText: "Si, Enviar !",   
						closeOnConfirm: true 
					}, 
					function(){ 
						$.ajax({
						    url : '../script/user_script.php',				
						    data : { 
						    	namec : txtcl,
								idc :txtcard,
								txtmonto : txtmonto,
								token:rand_code(caracteres,longitud),
								xyz: 'trf2',
						    	confirm: 'ok'
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
								$("#load_r").show();
								$("#btn_save").prop("disabled", true);
							},
	    		    success : function(json) {
						$("#load_r").hide();
						$("#btn_save").prop("disabled", false);
						 
	    		    	if (json.response == 'ok') {
							$('#form_rel_cambio')[0].reset();
	        		    	$("#load_r").hide();
							$("#form_reload_modal_add").hide();
							$("#form_reload_modal_add").modal('hide');//ocultamos el modal
							$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
							$('.modal-backdrop').remove();//eliminamos el backdrop del modal
							refresh_list_tf2();
						//	$('#datatable-1').DataTable().ajax.reload(); 
	    		    	}else if(json.response == '2'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error</div></div></div>");
        		    		
        		    	}else if(json.response == '4'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>SALDO INSUFICIENTE</div></div></div>");
        		    		
        		    	}else if(json.response == '5'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>DESTINATARIO NO EXISTE</div></div></div>");
        		    		
        		    	}else if(json.response == '8'){
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>NO PUEDES TRANSFERIR A LA MISMA CUENTA</div></div></div>");
        		    		
        		    	}else{
							$("#div_msg").show();
							$("#div_msg").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>Ocurrio un Error Inesperado</div></div></div>");
        		    		
        		    	}				
	    		    },
						    error: function(error){
						    	console.log(error);
						    }
						});
						
					});				
}


		function add_cliente2(e){
			//alert(document.getElementById('tcliente').value);
			//alert(document.getElementById('tcliente').value);
			
			var t_tf = $("#telefono").val();
			var t_tc = 1;
			var t_fd = $("#datepicker").val();
			var t_nombres = $("#nombres").val();
			var t_mail = $("#mail").val();
			var t_lg = $("#fp").val();
			var t_ssn = $("#ssn").val();
			//t_codigo = t_codigo.replace('-', '');
			var plan = document.getElementById("tcliente").value;
			var patrono = $("#emp").val();

			if(t_nombres==""){
				$('#nombres').focus();
			 $('#nombres').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#nombres').parent().addClass('has-success has-feedback');
			}

			if(t_lg==""){
				$('#fp').focus();
			 $('#fp').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#fp').parent().addClass('has-success has-feedback');
			}
			if(t_ssn=="" || t_ssn.length<7){
				$('#ssn').focus();
			 $('#ssn').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#ssn').parent().addClass('has-success has-feedback');
			}
			if(t_fd==""){
				$('#datepicker').focus();
			 $('#datepicker').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#datepicker').parent().addClass('has-success has-feedback');
			}
			
			
			if(t_tf==""){
				$('#telefono').focus();
			 $('#telefono').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#telefono').parent().addClass('has-success has-feedback');
			}
			if(t_mail==""){
				$('#mail').focus();
			 $('#mail').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				  if($("#mail").val().indexOf('@', 0) == -1 || $("#mail").val().indexOf('.', 0) == -1) {
					$('#mail').focus();
					$('#mail').parent().addClass('has-error has-feedback');
					return false;
				  }else{
					  $('#mail').parent().addClass('has-success has-feedback');
				  }
				 
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
			    url : '../script/registre_script.php',
			    data : { 
					nombre_l: t_nombres,
					t_fd:t_fd,
					numero:t_tf,
					t_cl : patrono,
					t_mail : t_mail,
					t_lg : t_lg,
					t_ssn:t_ssn,
					t_tc:t_tc,
					plan:plan,
			    	accion: 'add_e'
					
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
							$("#load_r2").show();
							$("#btnFetch").prop("disabled", true);
							},
			    success : function(json) {
					$("#load_r2").hide();
					$("#btnFetch").prop("disabled", false);					
					if(json.response=='ok'){
				
						$("#div_msg").hide();
						
						$('#form_emp_cambio')[0].reset();
						
						$("#form_emp_modal_add").hide();
						$("#form_emp_modal_add").modal('hide');//ocultamos el modal
						$('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
						$('.modal-backdrop').remove();//eliminamos el backdrop del modal
						refresh_list_tf2();
							 
					}else if(json.response==0){
	
							$("#div_msg2").show();
							$("#div_msg2").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>ERROR al realizar la suscripción</div></div></div>");
					}else if(json.response=="ex"){

							$("#div_msg2").show();
							$("#div_msg2").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>El Cliente ya existe</div></div></div>");
					}else {
					
							$("#div_msg2").show();
							$("#div_msg2").html("<div class='form-group'><div class='col-sm-12'><div class='alert alert-danger'>ERROR al realizar la suscripción</div></div></div>");
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}
function refresh_list_pagos(){
	window.location.href = "billing.php?"+rand_code(caracteres,longitud);
}
function refresh_list_tf(){
	window.location.href = "transaccion.php?"+rand_code(caracteres,longitud);
}
function refresh_list_tf2(){
	window.location.href = "new_group.php?"+rand_code(caracteres,longitud);
}
