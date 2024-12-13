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
function view_plan(x){
	$.ajax({
                    url : '../script/plan_script.php',
                    data : { 
                        // data client
						token:rand_code(caracteres,longitud),
                        tuser: x,
                        xyz:'view'  
                    },
                    type : 'POST',
                    dataType : 'json',
					beforeSend : function(){
						$("#load_plan").show();
                    },
                    success : function(json) {
                        console.log(json);
                        if (json.response == 'ok') {
                            sesiones = json.token_aleatorio;
							$("#btnfree").prop("disabled", false);
									$("#btnfree").html(
									'ACTIVO'
									);
							$("#btnfree").removeClass();
							$("#btnfree").addClass("btn bg-gradient-success w-100 my-4 mb-2");
                              $("#load_plan").hide();
							 $("#list_plan").show();
                        }else{
							window.location.href = "sign-in.html";
							//waitingDialog.show('Error en Datos...', {dialogSize: 'sm', progressType: 'red'});setTimeout(function () {waitingDialog.hide();}, 1000);
                         //waitingDialog.show('Error en sus datos...', {dialogSize: 'm', progressType: 'red'});setTimeout(function () {waitingDialog.hide();}, 2000);
                        }
                    },
                   /* error : function(jqXHR, status, error) {
                       // console.log(error);
                    }*/
                }); 
}
