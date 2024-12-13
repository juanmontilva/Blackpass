function EnvioConsulta(ID){
	var aux=ID.value;

	new Ajax.Request('add_proveedor_action.jsp', {
	  method: 'post',
	  parameters: $("FormularioContacto").serialize(),
		onSuccess : function(resp) {
			if(resp.responseText=='ok')
				$('frmContacto').innerHTML=unescape('Gracias por comunicarse con nosotros.');
			else if(resp.responseText=='errint')
				{
				alert(unescape("Error interno, comuniquese con el webmaster"));
				}
			else
				{
				alert(unescape(resp.responseText));
				}
		},
		onFailure : function(resp) {
			alert("Hubo un error. Reintente. Si el problema persiste, comuniquese con el webmaster");
		},
		onLoading : function() {
			ID.value='Aguarde...';
			ID.disable();
		},
		onComplete : function() {
			ID.value=aux;
			ID.enable();
		}
	});
}


function FondoModal(){
	
	$('FndYnnova').setStyle({
	  width: getDocWidth()+'px',
	  height: getDocHeight()+'px',
	  top: '0px',
	  left:'0px',
	  visibility: 'visible'
	});
	
	var AnchoForm=$('frmContacto').getWidth();
	
	$('frmContacto').setStyle({
	  width: AnchoForm+'px',
	  top: getPageScroll()[1]+'px',
	  left:getDocWidth()/2-AnchoForm/2+'px'
	});
	
	MuestroFormulario()
}

function CerrarModal(){
	$('frmContacto').innerHTML='';
	$('FndYnnova').setStyle({
	  width: '0px',
	  height: '0px',
	  top: '0px',
	  left:'0px',
	  visibility: 'hidden'
	});

	$('frmContacto').setStyle({
	  top: '0px',
	  left:'0px'
	});
}


function MuestroFormulario(){
	new Ajax.Request('_formulario.php', {
	  method: 'post',
		onSuccess : function(resp) {
			$('frmContacto').innerHTML=resp.responseText
		},
		onFailure : function(resp) {
			alert("Hubo un error. Reintente. Si el problema persiste, comuniquese con el webmaster");
		},
		onLoading : function() {
			ID.innerHTML='<img src="loading.gif" width="49" height="50" />'
		}
	});
}