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
longitud = 64;

function validar_t(){
	//alert("ddd");
	var tp = $("#tc").val();
	
	if(tp==1){
		$('#fp').children().remove();
		$('#fp').prepend("<option value='V' >V</option>");
		$('#fp').prepend("<option value='E' >E</option>");
		$('#tcliente').children().remove();
		$('#tcliente').prepend("<option value='111' >XPRES</option>");
		$("#nacimiento").show();
		$("#nombre_l").html("Nombres");
	}if(tp==2){
		$('#fp').children().remove();
		$('#fp').prepend("<option value='J' >J</option>");
		$('#fp').prepend("<option value='G' >G</option>");
		$('#tcliente').children().remove();
		$('#tcliente').prepend("<option value='109' >Corporativa</option>");
		$("#nacimiento").hide();
		$("#datepicker").val('2021-01-01');
		$("#razon").show();	
		$("#nombre_l").html("Persona Contacto");
	}
}
function suscrip(x){
		var categorias = new Array();
 
		var chatid = pais2  = "";
		var enviar = 1;
		var pais = document.getElementById('s2_country');
		if(pais.selectedIndex>0){
			//alert('La opción seleccionada es: '+combo.options[combo.selectedIndex].value);	
			pais2 = pais.options[pais.selectedIndex].value;
			$("#s2_country").removeClass();
			$("#s2_country").addClass("form-control is-valid");
		}else{
			$("#s2_country").addClass("form-control is-invalid");
			$("#s2_country").focus();
			return;
		}
		var phone = $("#number").val();
		if(phone==""){
			$("#number").addClass("form-control is-invalid");
			$("#number").focus();
				
			return;
		}else{
			$("#number").removeClass();
			$("#number").addClass("form-control is-valid");
		}
		var fnac = $("#datepicker").val();
		//alert(fnac);
		var nick = $("#nick").val();
		if(fnac==""){
			$("#datepicker").addClass("form-control is-invalid");
			$("#datepicker").focus();
			return;
			
		}else{
			$("#datepicker").removeClass();
			$("#datepicker").addClass("form-control is-valid");
		}
		if(nick==""){
			$("#nick").addClass("form-control is-invalid");
			$("#nick").focus();
			return;
		}else{
			$("#nick").removeClass();
			$("#nick").addClass("form-control is-valid");
		}
		var sex = document.getElementById('sexo');
		var sexo = civ = edo2 = 0;
		if(sex.selectedIndex>0){
			//alert('La opción seleccionada es: '+sex.options[sex.selectedIndex].value);	
			sexo = sex.options[sex.selectedIndex].value;
			$("#sexo").removeClass();
			$("#sexo").addClass("form-control is-valid");
		}else{
			$("#sexo").addClass("form-control is-invalid");
			$("#sexo").focus();
			return;
		}
		
		var passw = $("#passw").val();
		if(passw==""){
			$("#passw").addClass("form-control is-invalid");
			$("#passw").focus();
			return;
		}else{
			$("#passw").removeClass();
			$("#passw").addClass("form-control is-valid");
		}
		
		var alertas = $("#autorizo").val();
		var publi = $("#autorizo1").val();
		var uid = "123211";
	/*	$("input:checkbox:checked").each(function() {
	
		  categorias.push($(this).val());
		});*/
		var ph = pais2+$("#number").val();
		$.ajax({
				type : 'GET',
				dataType : 'json',
				url : '../script/sub_cript.php',				
				    data : { 
						    number:ph,
							xyz: 'ws',
							},
				beforeSend: function(){
					$("#btnFetch").prop("disabled", true);
								// add spinner to button
					$("#btnFetch").html(
								'<i class="spinner-border fa fa-spin"></i> Validando Whatsapp'
					);
				 },
	    		success : function(json) {
					if (json.response == 'ok') {
							$.ajax({
						    url : '../script/sub_cript.php',				
						    data : { 
						    	//cat:categorias,
								 token:rand_code(caracteres,longitud),
								number:ph,
								xyz: 'rol',
								name:nick,
								fnac:fnac,
								sex:sexo,
								pais:pais2,
								pas:passw,
								uid:uid
						    },
						    type : 'GET',
						    dataType : 'json',
						    beforeSend: function(){
							$("#btnFetch").prop("disabled", true);
							// add spinner to button
							$("#btnFetch").html(
							'<i class="spinner-border fa fa-spin"></i> Validando Información'
							);
							//alert("paos");
							},
							success : function(json) {
								if(json.response == 'ok') {
									$("#btnFetch").prop("disabled", true);
									$("#btnFetch").html(
									'Registro con exito'
									);
									$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert bg-gradient-success text-center");
									$("#respuesta").html("Suscripción Realizada con Exito!<p>Hemos enviado un correo con sus datos de acceso</p>");
									setTimeout(function(){
									home();
									},5000);
								}else{
									alert("aqui"+json.response);
									$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert alert-danger text-center");
									$("#respuesta").html("La cuenta ya existe");
									$("#number").removeClass();
									$("#number").addClass("form-control is-invalid");
									$("#number").focus();
									$("#btnFetch").prop("disabled", false);
									$("#btnFetch").html(
									'Suscribir'
									);
									  
								}	
							},
						    error: function(error){
						    	console.log(error);
						    }
						});
						}else{
							$("#number").addClass("form-control is-invalid");
							$("#number").focus();
						}
				},
				    error: function(error){
				    	console.log(error);
				    }
		});							
		
}
function home(){
$(location).attr('href','profile.html');
}

		function add_cliente(e){
			//alert(document.getElementById('tcliente').value);
			//alert(document.getElementById('tcliente').value);
			
			var pais="";
			var t_tf = $("#telefono").val();
			var t_tc = document.getElementById("tc").value;
			var t_fd = $("#datepicker").val();
			var t_direc = $("#direc").val();
			var t_nombres = $("#nombres").val();
			var t_codigo = $("#codigo").val();
			var t_mail = $("#mail").val();
			var t_lg = $("#fp").val();
			var t_ssn = $("#ssn").val();
			var razon = 0;
			//t_codigo = t_codigo.replace('-', '');
			var plan = document.getElementById("tcliente").value;
			if(t_tc==2){
				if($("#rzs").val()==""){
				 $('#rzs').parent().addClass('has-error has-feedback');
				  $('#rzs').focus();
				 return;	
				}else{
					$('#rzs').parent().addClass('has-success has-feedback');
					razon = $("#rzs").val();
				}
			}
			if(t_codigo==""){
			 $('#codigo').parent().addClass('has-error has-feedback');
			  $('#codigo').focus();
			 return;	
			}else{
				$('#codigo').parent().addClass('has-success has-feedback');
			}
			if(t_nombres==""){
				$('#nombres').focus();
			 $('#nombres').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#nombres').parent().addClass('has-success has-feedback');
			}
			if(t_tc==""){
				$('#tc').focus();
			 $('#tc').parent().addClass('has-error has-feedback');
			 return;	
			}else{
				 $('#tc').parent().addClass('has-success has-feedback');
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
			if(t_lg==""){
				$('#fp').focus();
			 $('#fp').parent().addClass('has-error has-feedback');
			 return;	
			}else{
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
			    url : '../script/registre_script.php',
			    data : { 
					nombre_l: t_nombres,
					t_fd:t_fd,
					numero:t_tf,
					t_codigo:t_codigo,
					t_cl : cliente,
					t_mail : t_mail,
					t_lg : t_lg,
					t_direc:t_direc,
					t_ssn:t_ssn,
					t_tc:t_tc,
					plan:plan,
					razon:razon,
			    	accion: 'add'
					
			    },	
			    type : 'GET',
			    dataType : 'json',
				cache: false,
			    beforeSend: function(){
							$("#btnFetch").prop("disabled", true);
							// add spinner to button
							$("#btnFetch").html(
							'<i class="spinner-border fa fa-spin"></i> Validando Información'
							);
							//alert("paos");
							},
			    success : function(json) {
					$('#btnFetch').attr("disabled", false);
					if(json.response=='ok'){
							$("#btnFetch").prop("disabled", true);
									$("#btnFetch").html(
									'Registro con exito'
									);
									$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert bg-gradient-success text-center");
									$("#respuesta").html("Suscripción Realizada con Exito<p>Hemos enviado un correo con sus datos de acceso a: "+t_mail+"</p>");
									setTimeout(function(){
									window.location.href = "sign-in.html";
									},3000);
							 $("#form_edit_cl")[0].reset();
							 
					}else if(json.response==0){
							$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert bg-gradient-danger text-center");
									$("#respuesta").html("ERROR al realizar la suscripción");
									setTimeout(function(){
									home();
									},5000);
					}			
					else {
									$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert bg-gradient-danger text-center");
									$("#respuesta").html("ERROR al realizar la suscripción");
									$("#btnFetch").html(
									'Registrar'
									);
					}
				},
				error: function(error){
			    	console.log(error);
			    }
			});
			
		}