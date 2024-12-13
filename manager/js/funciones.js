var sesiones = "";
            function validar()
            {   
                signIn();             
            }
             function signIn(){
            // SIGNIN SERVER CALL CODE GOES HERE
			if($("#username").val()!="" && $("#password").val()!=""){
				$('#password').parent().removeClass('has-success has-error'); 
				$('#password').parent().removeClass('has-success has-error'); 
               $.ajax({
                    url : 'validar.php',
                    data : { 
                        // data client
                        tuser: $("#username").val(),
                        tpass: $("#password").val(),
                        op:'validar'  
                    },
                    type : 'GET',
                    dataType : 'json',
					beforeSend : function(){
						waitingDialog.show('Cargando Datos...', {dialogSize: 'sm', progressType: 'success'});setTimeout(function () {waitingDialog.hide();}, 1000);
                    },
                    success : function(json) {
                        console.log(json);
                        if (json.response == 'ok') {
                            sesiones = json.token_aleatorio;
                            // alert(sesiones);
                             valid_login = true;    
                              sessionStorage.setItem("usuario",sesiones);
							  sessionStorage.setItem("uid",json.uid);
							  sessionStorage.setItem("perfi",json.perfi);
                              //$('#loguear').show();
							   window.location.href = "home.php"
                        }else{
							waitingDialog.show('Error en Datos...', {dialogSize: 'sm', progressType: 'red'});setTimeout(function () {waitingDialog.hide();}, 1000);
                         //waitingDialog.show('Error en sus datos...', {dialogSize: 'm', progressType: 'red'});setTimeout(function () {waitingDialog.hide();}, 2000);
                        }
                    },
                   /* error : function(jqXHR, status, error) {
                       // console.log(error);
                    }*/
                }); 
           }else if($("#username").val()!="" && $("#password").val()==""){
			   
			  $("#password").focus();
			  $('#password').parent().addClass('has-error has-feedback');
			  $('#username').parent().removeClass('has-success has-error'); 
		   }else if($("#username").val()=="" && $("#password").val()!=""){
			   
			  $("#username").focus();
			  $('#username').parent().addClass('has-error has-feedback');
			  $('#password').parent().removeClass('has-success has-error'); 
		   }else{
			    $("#username").focus();
				$('#username').parent().addClass('has-error has-feedback');
			    $('#password').parent().addClass('has-error has-feedback');
		   }
		}
		

