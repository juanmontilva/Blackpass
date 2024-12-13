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
function load_cat(){
	var j = 1;
	var pais = sessionStorage.pais;
	var device = sessionStorage.uid;
	
	$.ajax({
                    url : '../script/cat_script.php',
                    data : { 
                        token:rand_code(caracteres,longitud),
                        pais: pais,
						uid:device,
                        xyz:'vcat'  
                    },
                    type : 'POST',
                    dataType : 'json',
					beforeSend : function(){
						$("#load_cat").show();
                    },
                    success : function(json) {
                        console.log(json);
                        if (json.response == 'ok') {
                            var category = [];	
							  
								  var idmar = 0;
								  
									
									$.each(json.cat, function(d){
										
											  list_event ='<li class="list-group-item border-0 px-0">'+
											  '<div class="form-check form-switch ps-0">'+
												'<input class="form-check-input ms-auto" type="checkbox" name="ck" id="flexSwitchCheckDefault" value="'+json.cat[d].codigo+'">'+
												'<label class="form-check-label text-body ms-3 text-truncate w-80 mb-0" for="flexSwitchCheckDefault">'+json.cat[d].cat+'</label>'+
											  '</div></li>';
											$('#carga_cat').append(list_event);

									});
									$("input[name=ck]").each(function(){
										var catm = $(this).val();
										var cid = $(this);
										 $.each(json.cat2, function(c){
											if(catm ==json.cat2[c].cat2){
												cid.attr('checked', true);
											}
										});
										
									}); 
                             $("#load_cat").hide();
							 $("#cat").show();
							 $("#namid").html(json.name);
							 $("#phoneid").html(json.phone);
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
function save_cat2(x){
	var valoresCheck =[];
		$("input[type=checkbox]:checked").each(function(){
			valoresCheck.push(this.value);
		});
		console.log(valoresCheck);
		$.ajax({
						    url : '../script/cat_script.php',				
						    data : { 
						    	//cat:categorias,
								token:rand_code(caracteres,longitud),
								xyz: 'act_cat',
								uid:x,
								cat:valoresCheck
						    },
						    type : 'POST',
						    dataType : 'json',
						    beforeSend: function(){
							$("#btn_save").prop("disabled", true);
							// add spinner to button
							$("#btn_save").html(
							'<i class="spinner-border fa fa-spin"></i> Guardando'
							);
							//alert("paos");
							},
							success : function(json) {
								if(json.response == 'ok') {
									$("#btn_save").prop("disabled", false);
									$("#btn_save").html(
									'Registro con exito'
									);
									$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert bg-gradient-success text-center");
									$("#respuesta").html("Preferencias Guardadas");
									
								}else if(json.response == '2') {
									$("#mensaje").show();
									$("#mensaje").addClass("col-xs-12 col-sm-12 alert alert-warning text-center");
									$("#respuesta").html("Ocurrio un error en el registro");
									$("#btn_save").prop("disabled", false);
									$("#btn_save").html(
									'Suscribir'
									);
									
									  
								}	
							},
						    error: function(error){
						    	console.log(error);
						    }
				});
						
}